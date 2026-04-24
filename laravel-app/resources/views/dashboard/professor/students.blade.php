@extends('layouts.dashboard')
@section('title', 'Mes Étudiants')
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mes Étudiants 🎓</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $students->count() }} étudiant(s) trouvé(s).</p>
        </div>
        {{-- Class Filter --}}
        <form method="GET" class="flex items-center gap-2">
            <select name="class_id" onchange="this.form.submit()" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10">
                <option value="">Toutes les classes</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Étudiant</th>
                        <th class="px-6 py-4">Classe</th>
                        <th class="px-6 py-4">Filière</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($student->profile_photo)
                                    <img src="{{ asset('storage/' . $student->profile_photo) }}" class="w-10 h-10 rounded-xl object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=e2e8f0&color=475569" class="w-10 h-10 rounded-xl">
                                @endif
                                <div>
                                    <p class="font-bold text-sm text-slate-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $student->studentDetails->student_code ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs font-bold">
                                {{ $student->studentDetails->studentClass->class_name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $student->studentDetails->filiere ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-xs text-slate-500">{{ $student->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full {{ $student->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} font-bold text-[10px] uppercase">
                                {{ $student->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Aucun étudiant trouvé pour cette sélection.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
