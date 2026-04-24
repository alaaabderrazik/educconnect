@extends('layouts.dashboard')
@section('title', 'Dashboard Professeur')
@section('user_role', 'Professeur')
@section('sidebar_menu')
    @include('dashboard.professor.sidebar')
@endsection

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Espace Enseignant 👨‍🏫</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez vos cours, classes et ressources pédagogiques.</p>
        </div>
        <div class="flex items-center gap-3 text-sm">
            <a href="{{ route('dashboard.professor.assignments') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl shadow-sm font-semibold transition-all">
                <i class="fa-solid fa-file-pen text-amber-500"></i> Nouveau Devoir
            </a>
            <a href="{{ route('dashboard.professor.attendance') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm shadow-blue-500/20 font-semibold transition-all">
                <i class="fa-solid fa-clipboard-user"></i> Faire l'Appel
            </a>
        </div>
    </div>

    {{-- Upcoming Online Course Banner --}}
    @if($nextOnlineCourse)
    @php
        $startTime = \Carbon\Carbon::parse($nextOnlineCourse->start_date);
        $endTime = \Carbon\Carbon::parse($nextOnlineCourse->end_date);
        $now = now();
        $canJoin = $now->between($startTime->copy()->subMinutes(15), $endTime);
    @endphp
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 rounded-2xl p-6 shadow-lg text-white mb-8 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-400 opacity-20 rounded-full blur-xl pointer-events-none"></div>
        
        <div class="relative z-10 flex items-start gap-4 w-full md:w-auto">
            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full flex items-center justify-center text-rose-300 text-2xl animate-pulse flex-shrink-0">
                <i class="fa-solid fa-video"></i>
            </div>
            <div>
                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-bold bg-rose-500/20 text-rose-100 border border-rose-500/30 uppercase tracking-wider mb-2">
                    <span class="w-1.5 h-1.5 bg-rose-400 rounded-full"></span> Live à venir
                </div>
                <h3 class="text-xl font-bold mb-1">{{ $nextOnlineCourse->title }}</h3>
                <p class="text-blue-100 text-sm">{{ $nextOnlineCourse->studentClass->class_name ?? 'N/A' }} — {{ $startTime->format('d/m/Y') }} à {{ $startTime->format('H:i') }}</p>
            </div>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
            <div id="countdown" data-start="{{ $startTime->toIso8601String() }}" class="flex items-center gap-3 bg-slate-900/40 px-4 py-2 rounded-xl backdrop-blur-sm border border-white/10">
                <div class="text-center">
                    <span id="days" class="block text-xl font-extrabold leading-none">00</span>
                    <span class="text-[10px] text-blue-200 uppercase font-medium">Jours</span>
                </div>
                <span class="text-xl font-bold opacity-50">:</span>
                <div class="text-center">
                    <span id="hours" class="block text-xl font-extrabold leading-none">00</span>
                    <span class="text-[10px] text-blue-200 uppercase font-medium">Heures</span>
                </div>
                <span class="text-xl font-bold opacity-50">:</span>
                <div class="text-center">
                    <span id="minutes" class="block text-xl font-extrabold leading-none">00</span>
                    <span class="text-[10px] text-blue-200 uppercase font-medium">Mins</span>
                </div>
            </div>
            
            @if($canJoin)
            <a href="{{ $nextOnlineCourse->course_link }}" target="_blank" class="w-full md:w-auto px-6 py-3 bg-white text-blue-700 hover:bg-blue-50 font-bold rounded-xl shadow-lg transition-all hover:-translate-y-0.5 whitespace-nowrap">
                Rejoindre maintenant <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
            @else
            <button disabled class="w-full md:w-auto px-6 py-3 bg-white/20 text-white/50 font-bold rounded-xl border border-white/20 cursor-not-allowed whitespace-nowrap">
                Accès verrouillé <i class="fa-solid fa-lock ml-1 text-xs"></i>
            </button>
            @endif
        </div>
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @php
        $cards = [
            ['label'=>'Classes','value'=>$stats['classes'],'icon'=>'fa-users-rectangle','color'=>'blue','sub'=>'Classes assignées'],
            ['label'=>'Matières','value'=>$stats['subjects'],'icon'=>'fa-book','color'=>'indigo','sub'=>'Matières enseignées'],
            ['label'=>"Aujourd'hui",'value'=>$todaySchedules->count(),'icon'=>'fa-calendar-day','color'=>'emerald','sub'=>'Leçons prévues'],
            ['label'=>'Devoirs','value'=>$stats['assignments'],'icon'=>'fa-file-pen','color'=>'amber','sub'=>'Total créés'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-{{ $card['color'] }}-50 text-{{ $card['color'] }}-600 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid {{ $card['icon'] }}"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $card['value'] }}</p>
                <p class="text-xs text-slate-500 font-medium">{{ $card['sub'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Classes List and Today's Schedule --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Today's Schedule --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-emerald-50/50">
                    <h3 class="font-black text-slate-900"><i class="fa-solid fa-calendar-day text-emerald-600 mr-2"></i> Emploi du temps d'Aujourd'hui</h3>
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                            <th class="px-6 py-3">Horaire</th>
                            <th class="px-6 py-3">Classe</th>
                            <th class="px-6 py-3 text-center">Matière</th>
                            <th class="px-6 py-3 text-right">Salle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($todaySchedules as $schedule)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-slate-100 text-slate-700 font-black text-xs rounded-lg">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-sm text-slate-900">{{ $schedule->studentClass->class_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-center text-sm font-semibold text-indigo-600">{{ $schedule->subject->subject_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-right text-xs text-slate-500 font-bold"><i class="fa-solid fa-door-open mr-1"></i>{{ $schedule->room ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400 italic text-sm">Aucun cours de prévu aujourd'hui. Profitez-en ! 🎉</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mes Classes --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-black text-slate-900">Mes Classes</h3>
                    <a href="{{ route('dashboard.professor.classes') }}" class="text-blue-600 text-sm font-bold hover:text-blue-800">Voir tout</a>
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                            <th class="px-6 py-3">Classe</th>
                            <th class="px-6 py-3 text-center">Étudiants</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($classes->take(5) as $class)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-sm text-slate-900">{{ $class->class_name }}</p>
                                <p class="text-xs text-slate-400">{{ $class->academicLevel->level_name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-700 rounded-full font-bold text-xs">
                                    {{ $class->studentDetails->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('dashboard.professor.students', ['class_id' => $class->id]) }}" class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100">Voir</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-6 py-10 text-center text-slate-400 italic text-sm">Aucune classe trouvée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Assignments & Notifications --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-black text-slate-900 mb-4">Devoirs récents</h3>
                <div class="space-y-3">
                    @forelse($assignments as $a)
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                        <div class="w-9 h-9 rounded-xl {{ $a->isOverdue() ? 'bg-rose-50 text-rose-500' : 'bg-amber-50 text-amber-500' }} flex items-center justify-center text-sm flex-shrink-0">
                            <i class="fa-solid fa-file-pen"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $a->title }}</p>
                            <p class="text-xs text-slate-400">{{ $a->studentClass->class_name ?? 'N/A' }} · {{ $a->submissions->count() }} soumissions</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 italic">Aucun devoir créé.</p>
                    @endforelse
                </div>
                <a href="{{ route('dashboard.professor.assignments') }}" class="block mt-4 text-center text-xs font-bold text-blue-600 hover:text-blue-800">Gérer les devoirs →</a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-black text-slate-900 mb-4">Notifications</h3>
                <div class="space-y-3">
                    @forelse($notifications as $n)
                    <div class="flex items-start gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500 flex-shrink-0 mt-1.5"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $n->title }}</p>
                            <p class="text-xs text-slate-400">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-400 italic">Aucune notification.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownEl = document.getElementById('countdown');
    if (countdownEl) {
        const startDate = new Date(countdownEl.dataset.start);
        
        function updateCountdown() {
            const now = new Date();
            const diff = startDate - now;
            
            if (diff <= 0) {
                document.getElementById('days').innerText = '00';
                document.getElementById('hours').innerText = '00';
                document.getElementById('minutes').innerText = '00';
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            document.getElementById('days').innerText = days.toString().padStart(2, '0');
            document.getElementById('hours').innerText = hours.toString().padStart(2, '0');
            document.getElementById('minutes').innerText = minutes.toString().padStart(2, '0');
        }
        
        updateCountdown();
        setInterval(updateCountdown, 60000);
    }
});
</script>
@endsection
