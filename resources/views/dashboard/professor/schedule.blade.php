@extends('layouts.dashboard')

@section('title', 'Mon Planning')
@section('user_role', 'Professeur')

@section('sidebar_menu')
    @include('dashboard.professor.sidebar')
@endsection

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mon Planning 📅</h1>
        <p class="text-slate-500 text-sm mt-1">Votre emploi du temps de la semaine.</p>
    </div>
    <div class="flex items-center gap-2 text-sm text-slate-500 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm">
        <i class="fa-regular fa-calendar text-blue-500"></i>
        <span class="font-semibold text-slate-700">{{ now()->translatedFormat('W') }} — Semaine en cours</span>
    </div>
</div>

@php
    $days   = ['monday'=>'Lundi','tuesday'=>'Mardi','wednesday'=>'Mercredi','thursday'=>'Jeudi','friday'=>'Vendredi'];
    $colors = ['blue','emerald','indigo','amber','rose','violet','cyan'];
    $todayKey = strtolower(now()->englishDayOfWeek);
@endphp

{{-- Stats Row --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php $totalHours = $schedules->sum(fn($s) => (strtotime($s->end_time) - strtotime($s->start_time)) / 3600); @endphp
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600"><i class="fa-solid fa-calendar-week"></i></div>
        <div><p class="text-xs text-slate-500">Créneaux</p><p class="text-xl font-black text-slate-900">{{ $schedules->count() }}</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600"><i class="fa-regular fa-clock"></i></div>
        <div><p class="text-xs text-slate-500">Heures/semaine</p><p class="text-xl font-black text-slate-900">{{ number_format($totalHours, 1) }}h</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600"><i class="fa-solid fa-users-rectangle"></i></div>
        <div><p class="text-xs text-slate-500">Classes</p><p class="text-xl font-black text-slate-900">{{ $schedules->unique('student_class_id')->count() }}</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600"><i class="fa-solid fa-book"></i></div>
        <div><p class="text-xs text-slate-500">Matières</p><p class="text-xl font-black text-slate-900">{{ $schedules->unique('subject_id')->count() }}</p></div>
    </div>
</div>

{{-- Weekly Timetable --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="grid grid-cols-6 border-b border-slate-100 bg-slate-50">
        <div class="py-4 px-2 text-center text-xs font-bold text-slate-400 uppercase tracking-widest border-r border-slate-100 italic">Heure</div>
        @foreach($days as $dayKey => $dayName)
        <div class="py-4 px-2 text-center text-xs font-bold uppercase tracking-widest {{ !$loop->last ? 'border-r border-slate-100' : '' }} {{ $dayKey === $todayKey ? 'text-blue-600 bg-blue-50/50' : 'text-slate-900' }}">
            {{ $dayName }}
            @if($dayKey === $todayKey)
                <span class="block text-[9px] text-blue-500 font-bold mt-0.5">Aujourd'hui</span>
            @endif
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-6 divide-x divide-slate-100" style="height:920px;">
        {{-- Hours --}}
        <div class="flex flex-col">
            @foreach(['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00'] as $hour)
                <div class="flex-1 flex items-start justify-center pt-1 text-[10px] font-bold text-slate-400 border-b border-slate-50">{{ $hour }}</div>
            @endforeach
        </div>

        @foreach($days as $dayKey => $dayName)
        <div class="relative flex flex-col {{ $dayKey === $todayKey ? 'bg-blue-50/20' : '' }}">
            @foreach($schedules->where('day_of_week', $dayKey) as $sched)
                @php
                    $startH = intval(date('H', strtotime($sched->start_time)));
                    $startM = intval(date('i', strtotime($sched->start_time)));
                    $endH   = intval(date('H', strtotime($sched->end_time)));
                    $endM   = intval(date('i', strtotime($sched->end_time)));
                    $top    = (($startH - 8) + ($startM / 60)) * (100/15);
                    $height = max(5, ((($endH - 8) + ($endM / 60)) * (100/15)) - $top);
                    $color  = $colors[$loop->index % count($colors)];
                    $isLive = $dayKey === $todayKey
                           && now()->format('H:i:s') >= $sched->start_time
                           && now()->format('H:i:s') <= $sched->end_time;
                @endphp
                <div style="top:{{ $top }}%; height:{{ $height }}%;"
                     class="absolute inset-x-0.5 bg-{{ $color }}-50 border-l-4 border-{{ $color }}-500 rounded-lg px-2 py-1.5 shadow-sm hover:shadow-md transition-shadow z-10 overflow-hidden group">
                    @if($isLive)
                        <div class="flex items-center gap-1 mb-0.5">
                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
                            <span class="text-[9px] font-bold text-rose-600 uppercase">En cours</span>
                        </div>
                    @endif
                    <span class="block text-[9px] font-bold text-{{ $color }}-600 leading-tight">{{ date('H:i', strtotime($sched->start_time)) }}-{{ date('H:i', strtotime($sched->end_time)) }}</span>
                    <p class="text-[10px] font-bold text-slate-800 leading-tight truncate">{{ $sched->title }}</p>
                    <p class="text-[9px] text-slate-500 truncate">{{ optional($sched->subject)->subject_name }}</p>
                    @if($sched->room)
                        <p class="text-[9px] text-slate-400 truncate"><i class="fa-solid fa-location-dot mr-0.5"></i>{{ $sched->room }}</p>
                    @endif
                </div>
            @endforeach
            @for($i=0;$i<16;$i++)<div class="flex-1 border-b border-slate-50"></div>@endfor
        </div>
        @endforeach
    </div>
</div>

{{-- If no schedules --}}
@if($schedules->isEmpty())
<div class="mt-8 p-10 bg-white rounded-2xl border border-slate-100 text-center text-slate-400">
    <i class="fa-regular fa-calendar-xmark text-4xl mb-3 block text-slate-300"></i>
    <p class="font-medium">Aucun créneau n'a encore été assigné à votre emploi du temps.</p>
    <p class="text-sm mt-1">Contactez l'administrateur pour qu'il configure votre planning.</p>
</div>
@endif
@endsection
