@extends('layouts.dashboard')
@section('title', 'Cours en Ligne')
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Cours en Ligne 🌐</h1>
        <p class="text-slate-500 text-sm mt-1">Gérez vos sessions de cours à distance.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
        @php
            $now = now();
            $startTime = \Carbon\Carbon::parse($course->start_date);
            $endTime = \Carbon\Carbon::parse($course->end_date);
            $canJoin = $now->between($startTime->copy()->subMinutes(15), $endTime);
            $tooEarly = $now->lt($startTime->copy()->subMinutes(15));
            $finished = $now->gt($endTime);
        @endphp
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col transition-all hover:shadow-md">
            <div class="p-6 flex-grow">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded text-[10px] font-bold uppercase tracking-wider">{{ $course->subject->subject_name ?? 'N/A' }}</span>
                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-[10px] font-bold uppercase tracking-wider">{{ $course->studentClass->class_name ?? 'N/A' }}</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $course->title }}</h3>
                <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ $course->description }}</p>
                
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-600">
                        <i class="fa-regular fa-calendar text-blue-500"></i>
                        {{ $startTime->format('d/m/Y') }}
                    </div>
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-600">
                        <i class="fa-regular fa-clock text-blue-500"></i>
                        {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}
                    </div>
                </div>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-100">
                @if($canJoin)
                <a href="{{ $course->course_link }}" target="_blank" class="w-full py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-500/20">
                    <i class="fa-solid fa-video"></i> Rejoindre la session
                </a>
                @elseif($tooEarly)
                <button disabled title="Disponible 15 mins avant" class="w-full py-2.5 bg-slate-200 text-slate-400 rounded-xl font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                    <i class="fa-solid fa-lock text-xs"></i> Session verrouillée
                </button>
                <p class="text-[10px] text-center text-slate-400 mt-2 font-bold uppercase tracking-widest">Ouvre 15 min avant</p>
                @elseif($finished)
                <button disabled class="w-full py-2.5 bg-slate-100 text-slate-400 rounded-xl font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check text-xs"></i> Session terminée
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center text-slate-400 italic bg-white rounded-3xl border border-dashed border-slate-200">
            Aucun cours en ligne planifié.
        </div>
        @endforelse
    </div>

    @if($courses->count() > 0)
    <div class="mt-8">
        {{ $courses->links() }}
    </div>
    @endif
@endsection
