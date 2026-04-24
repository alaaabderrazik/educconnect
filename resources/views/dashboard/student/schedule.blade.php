@extends('layouts.dashboard')

@section('title', 'Planning')
@section('user_role', 'Étudiante')

@section('sidebar_menu')
    @include('dashboard.student.sidebar')
@endsection

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mon Emploi du Temps 📅</h1>
            <p class="text-slate-500 text-sm mt-1">Semaine du 12 au 18 Octobre 2026</p>
        </div>
        <div class="flex items-center gap-2">
            <button class="p-2 bg-white border border-slate-200 rounded-lg hover:bg-slate-50"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="px-4 py-2 bg-white border border-slate-200 rounded-lg font-bold text-sm tracking-wide">Aujourd'hui</button>
            <button class="p-2 bg-white border border-slate-200 rounded-lg hover:bg-slate-50"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="grid grid-cols-6 border-b border-slate-100 bg-slate-50">
            <div class="py-4 px-2 text-center text-xs font-bold text-slate-400 uppercase tracking-widest border-r border-slate-100 italic">Heure</div>
            <div class="py-4 px-2 text-center text-xs font-bold text-slate-900 uppercase tracking-widest border-r border-slate-100">Lundi</div>
            <div class="py-4 px-2 text-center text-xs font-bold text-slate-900 uppercase tracking-widest border-r border-slate-100">Mardi</div>
            <div class="py-4 px-2 text-center text-xs font-bold text-slate-900 uppercase tracking-widest border-r border-slate-100">Mercredi</div>
            <div class="py-4 px-2 text-center text-xs font-bold text-slate-900 uppercase tracking-widest border-r border-slate-100">Jeudi</div>
            <div class="py-4 px-2 text-center text-xs font-bold text-slate-900 uppercase tracking-widest">Vendredi</div>
        </div>

        <div class="grid grid-cols-6 divide-x divide-slate-100 h-[920px]">
            <!-- Hours Column -->
            <div class="flex flex-col">
                @foreach(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'] as $hour)
                    <div class="flex-1 flex items-center justify-center text-xs font-bold text-slate-400 border-b border-slate-100">{{ $hour }}</div>
                @endforeach
            </div>

            @php
                $days = ['monday' => 'Lundi', 'tuesday' => 'Mardi', 'wednesday' => 'Mercredi', 'thursday' => 'Jeudi', 'friday' => 'Vendredi'];
                $colors = ['blue', 'emerald', 'indigo', 'amber', 'rose'];
            @endphp

            @foreach($days as $dayKey => $dayName)
                @php $color = $colors[$loop->index % count($colors)]; @endphp
                <div class="flex flex-col relative">
                    <!-- Events -->
                    @foreach($schedules->where('day_of_week', $dayKey) as $schedule)
                        @php
                            $startH = intval(date('H', strtotime($schedule->start_time)));
                            $startM = intval(date('i', strtotime($schedule->start_time)));
                            $endH = intval(date('H', strtotime($schedule->end_time)));
                            $endM = intval(date('i', strtotime($schedule->end_time)));
                            
                            $top = (($startH - 8) + ($startM / 60)) * (100 / 15);
                            $height = max(4, ((($endH - 8) + ($endM / 60)) * (100 / 15)) - $top);
                            $isLive = \Carbon\Carbon::now()->englishDayOfWeek === ucfirst($dayKey) && 
                                      \Carbon\Carbon::now()->format('H:i:s') >= $schedule->start_time &&
                                      \Carbon\Carbon::now()->format('H:i:s') <= $schedule->end_time;
                        @endphp
                        
                        <div style="top: {{ $top }}%; height: {{ $height }}%;" class="absolute inset-x-1 bg-{{$color}}-50 border-l-4 border-{{$color}}-500 rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow cursor-pointer group z-10 overflow-hidden">
                            @if($isLive)
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
                                    <span class="block text-[10px] font-bold text-{{$color}}-600 uppercase">{{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</span>
                                </div>
                            @else
                                <span class="block text-[10px] font-bold text-{{$color}}-600 uppercase mb-1">{{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</span>
                            @endif
                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-{{$color}}-700 transition-colors">{{ optional($schedule->subject)->subject_name }}</h4>
                            <p class="text-[10px] text-slate-500 mt-1"><i class="fa-solid fa-location-dot mr-1"></i> {{ $schedule->room ?? 'Salle N/A' }}</p>
                        </div>
                    @endforeach
                    
                    <!-- Hour line markers -->
                    @for ($i = 0; $i < 16; $i++) <div class="flex-1 border-b border-slate-100"></div> @endfor
                </div>
            @endforeach
        </div>
    </div>
@endsection
