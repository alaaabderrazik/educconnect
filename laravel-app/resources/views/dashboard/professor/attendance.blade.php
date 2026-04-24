@extends('layouts.dashboard')
@section('title', 'Présence')
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Gestion de la Présence 📋</h1>
            <p class="text-slate-500 text-sm mt-1">Prenez l'appel et suivez l'assiduité de vos étudiants.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i><span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Take Attendance Form --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-600 to-teal-600">
                <h3 class="font-black text-white text-lg">Nouvelle Séance</h3>
                <p class="text-emerald-100 text-xs mt-1">Sélectionnez une classe pour charger les étudiants.</p>
            </div>
            <div class="p-6">
                <form action="{{ route('dashboard.professor.attendance.save') }}" method="POST" id="attendanceForm">
                    @csrf
                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Classe</label>
                            <select name="student_class_id" id="classSelect" required onchange="loadStudents(this.value)" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm">
                                <option value="">Sélectionner une classe</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date de séance</label>
                            <input type="date" name="session_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sujet / Thème de la séance</label>
                            <input type="text" name="session_topic" placeholder="Ex: Chapitre 3 — Les algorithmes de tri" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm">
                        </div>
                    </div>

                    {{-- Student List --}}
                    <div id="studentList" class="mb-5">
                        <p class="text-sm text-slate-400 italic text-center py-6">Sélectionnez une classe pour afficher les étudiants.</p>
                    </div>

                    <div id="submitSection" class="hidden">
                        <div class="flex items-center gap-3 mb-4">
                            <button type="button" onclick="markAll('present')" class="flex-1 py-2 bg-emerald-50 text-emerald-600 rounded-xl font-bold text-xs hover:bg-emerald-100">✓ Tous Présents</button>
                            <button type="button" onclick="markAll('absent')" class="flex-1 py-2 bg-rose-50 text-rose-600 rounded-xl font-bold text-xs hover:bg-rose-100">✗ Tous Absents</button>
                        </div>
                        <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black text-sm hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-500/20">
                            <i class="fa-solid fa-floppy-disk mr-2"></i>Enregistrer la Présence
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Attendance History --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="font-black text-slate-900">Historique des séances</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($history as $record)
                <div class="p-5 hover:bg-slate-50 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-black text-sm text-slate-900">{{ $record->studentClass->name ?? 'N/A' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $record->session_topic ?? 'Pas de sujet' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-700">{{ $record->session_date->format('d/m/Y') }}</p>
                            <div class="flex gap-2 mt-1 justify-end">
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[10px] font-black">
                                    {{ $record->entries->where('status','present')->count() }} présents
                                </span>
                                <span class="px-2 py-0.5 bg-rose-50 text-rose-600 rounded text-[10px] font-black">
                                    {{ $record->entries->where('status','absent')->count() }} absents
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-slate-400 italic">Aucune séance enregistrée.</div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function loadStudents(classId) {
            const container = document.getElementById('studentList');
            const submitSection = document.getElementById('submitSection');

            if (!classId) {
                container.innerHTML = '<p class="text-sm text-slate-400 italic text-center py-6">Sélectionnez une classe.</p>';
                submitSection.classList.add('hidden');
                return;
            }

            container.innerHTML = '<p class="text-sm text-slate-400 text-center py-6 animate-pulse">Chargement...</p>';

            fetch(`/dashboard/professor/attendance/students?class_id=${classId}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(students => {
                if (!students.length) {
                    container.innerHTML = '<p class="text-sm text-slate-400 italic text-center py-6">Aucun étudiant dans cette classe.</p>';
                    submitSection.classList.add('hidden');
                    return;
                }

                container.innerHTML = `
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Étudiants (${students.length})</p>
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        ${students.map(s => `
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <div>
                                <p class="text-sm font-bold text-slate-900">${s.first_name || ''} ${s.last_name || ''}</p>
                            </div>
                            <div class="flex gap-2">
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="radio" name="entries[${s.id}]" value="present" checked class="text-emerald-500">
                                    <span class="text-xs font-bold text-emerald-600">Présent</span>
                                </label>
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="radio" name="entries[${s.id}]" value="absent" class="text-rose-500">
                                    <span class="text-xs font-bold text-rose-600">Absent</span>
                                </label>
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="radio" name="entries[${s.id}]" value="late" class="text-amber-500">
                                    <span class="text-xs font-bold text-amber-600">Retard</span>
                                </label>
                            </div>
                        </div>`).join('')}
                    </div>`;

                submitSection.classList.remove('hidden');
            })
            .catch(() => {
                container.innerHTML = '<p class="text-sm text-rose-400 text-center py-6">Erreur lors du chargement.</p>';
            });
        }

        function markAll(status) {
            document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(r => r.checked = true);
        }

        // Auto-load if class is pre-selected via URL
        window.addEventListener('DOMContentLoaded', () => {
            const sel = document.getElementById('classSelect');
            if (sel.value) loadStudents(sel.value);
        });
    </script>
@endsection
