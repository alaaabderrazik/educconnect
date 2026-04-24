@extends('layouts.dashboard')
@section('title', 'Soumissions — ' . $assignment->title)
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dashboard.professor.assignments') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-all">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Soumissions — {{ $assignment->title }}</h1>
            <p class="text-slate-500 text-sm mt-1">
                {{ $assignment->studentClass->class_name ?? 'N/A' }} ·
                {{ $assignment->submissions->count() }} soumissions
                @if($assignment->due_date) · Deadline : {{ $assignment->due_date->format('d/m/Y') }} @endif
            </p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Étudiant</th>
                        <th class="px-6 py-4">Soumis le</th>
                        <th class="px-6 py-4">Fichier</th>
                        <th class="px-6 py-4 text-center">Note / {{ $assignment->max_grade }}</th>
                        <th class="px-6 py-4">Feedback</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($assignment->submissions as $sub)
                    <tr class="hover:bg-slate-50 transition-colors" id="row-{{ $sub->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($sub->student->name ?? 'E') }}&background=e2e8f0&color=475569&size=32" class="w-9 h-9 rounded-xl">
                                <div>
                                    <p class="font-bold text-sm text-slate-900">{{ $sub->student->first_name ?? '' }} {{ $sub->student->last_name ?? '' }}</p>
                                    <p class="text-xs text-slate-400">{{ $sub->student->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">
                            {{ $sub->submitted_at ? $sub->submitted_at->format('d/m/Y H:i') : 'Non soumis' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($sub->file_path)
                            <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-50 text-indigo-600 rounded text-xs font-bold hover:bg-indigo-100">
                                <i class="fa-solid fa-download text-xs"></i> Fichier
                            </a>
                            @else
                            <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($sub->isGraded())
                            <span class="font-black text-lg {{ $sub->grade >= ($assignment->max_grade * 0.5) ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $sub->grade }}
                            </span>
                            @else
                            <span class="text-slate-300 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate">{{ $sub->feedback ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openGrade('{{ $sub->id }}', '{{ $sub->grade }}', '{{ addslashes($sub->feedback) }}')"
                                class="px-3 py-1 {{ $sub->isGraded() ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} rounded-lg text-xs font-bold hover:opacity-80 transition-all">
                                {{ $sub->isGraded() ? 'Modifier Note' : 'Noter' }}
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-14 text-center text-slate-400 italic">Aucune soumission reçue pour ce devoir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Grade Modal --}}
    <div id="gradeModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-sm shadow-2xl p-8">
            <h3 class="text-xl font-black text-slate-900 mb-6">Attribuer une Note</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Note (max {{ $assignment->max_grade }})</label>
                    <input type="number" id="gradeInput" min="0" max="{{ $assignment->max_grade }}" step="0.5" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-black text-2xl text-center">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Feedback</label>
                    <textarea id="feedbackInput" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-medium text-sm"></textarea>
                </div>
                <div id="gradeStatus" class="text-sm font-bold"></div>
                <div class="flex gap-3 pt-2">
                    <button onclick="document.getElementById('gradeModal').classList.add('hidden')" class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-sm hover:bg-slate-200">Annuler</button>
                    <button onclick="submitGrade()" id="gradeBtn" class="flex-[2] py-3 bg-emerald-600 text-white rounded-xl font-black text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-500/20">
                        <i class="fa-solid fa-check mr-1"></i>Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSubId = null;
        function openGrade(subId, grade, feedback) {
            currentSubId = subId;
            document.getElementById('gradeInput').value = grade || '';
            document.getElementById('feedbackInput').value = feedback || '';
            document.getElementById('gradeStatus').textContent = '';
            document.getElementById('gradeModal').classList.remove('hidden');
        }
        function submitGrade() {
            const btn = document.getElementById('gradeBtn');
            const status = document.getElementById('gradeStatus');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Enregistrement...';

            fetch(`/dashboard/professor/submissions/${currentSubId}/grade`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    grade: document.getElementById('gradeInput').value,
                    feedback: document.getElementById('feedbackInput').value,
                })
            })
            .then(r => r.json())
            .then(d => {
                status.innerHTML = '<span class="text-emerald-600"><i class="fa-solid fa-check"></i> Sauvegardé !</span>';
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(() => {
                    document.getElementById('gradeModal').classList.add('hidden');
                    location.reload();
                }, 800);
            })
            .catch(() => {
                status.innerHTML = '<span class="text-rose-600">Erreur.</span>';
                btn.disabled = false;
                btn.innerHTML = 'Enregistrer';
            });
        }

        document.getElementById('gradeModal').addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });
    </script>
@endsection
