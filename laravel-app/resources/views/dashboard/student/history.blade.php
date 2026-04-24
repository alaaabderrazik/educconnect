@extends('layouts.dashboard')

@section('title', 'Historique')
@section('user_role', 'Étudiant')

@section('sidebar_menu')
    @include('dashboard.student.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Historique des Cours 🕰️</h1>
            <p class="text-slate-500 text-sm mt-1">Retrouvez tous les cours que vous avez terminés et vos certificats.</p>
        </div>
    </div>

    <!-- History List -->
    <div class="space-y-4">
        @forelse($pastAssignments as $assignment)
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">{{ $assignment->title }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                        Terminé le {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y') }} 
                        @php
                            $submission = $assignment->submissions->firstWhere('user_id', Auth::id());
                        @endphp
                        @if($submission && $submission->grade) 
                            — Note: {{ $submission->grade }}/20
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.student.assignments') }}" class="px-4 py-2 bg-slate-50 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition-all">Détails</a>
            </div>
        </div>
        @empty
        <div class="py-12 text-center text-slate-500">
            <p>Aucun historique de devoirs terminés.</p>
        </div>
        @endforelse
        
        <div class="mt-6">
            {{ $pastAssignments->links() }}
        </div>
    </div>
@endsection
