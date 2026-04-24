@extends('layouts.dashboard')

@section('title', 'Student Dashboard')
@section('user_name', auth()->user()->first_name . ' ' . auth()->user()->last_name)
@section('user_role', auth()->user()->gender == 'F' ? 'Étudiante' : 'Étudiant')

@section('sidebar_menu')
    @include('dashboard.student.sidebar')
@endsection

@section('content')

    <!-- Top Action Buttons & Title -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Bonjour, {{ auth()->user()->first_name }} ! 👋</h1>
            <p class="text-slate-500 text-sm mt-1">Prête à continuer votre apprentissage aujourd'hui ?</p>
        </div>
        <div class="flex items-center gap-3 text-sm">
            <span class="text-slate-500 font-medium">Prochaine échéance: <span class="text-emerald-600 font-bold">Total solde réglé</span></span>
        </div>
    </div>

    <!-- Live Course Banner -->
    @if($nextOnlineCourse)
    @php
        $startTime = \Carbon\Carbon::parse($nextOnlineCourse->start_date);
        $endTime = \Carbon\Carbon::parse($nextOnlineCourse->end_date);
        $now = now();
        $canJoin = $now->between($startTime->copy()->subMinutes(15), $endTime);
    @endphp
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 rounded-2xl p-6 shadow-lg text-white mb-8 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
        <!-- Background decoration -->
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
                <p class="text-blue-100 text-sm">Par {{ $nextOnlineCourse->professor->name }} — {{ $startTime->format('H:i') }}</p>
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
                Accéder au salon <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
            @else
            <button disabled class="w-full md:w-auto px-6 py-3 bg-white/20 text-white/50 font-bold rounded-xl border border-white/20 cursor-not-allowed whitespace-nowrap">
                Accès verrouillé <i class="fa-solid fa-lock ml-1 text-xs"></i>
            </button>
            @endif
        </div>
    </div>
    @endif

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 text-xl flex-shrink-0">
                <i class="fa-solid fa-ranking-star"></i>
            </div>
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Moyenne Générale</p>
                <h3 class="text-2xl font-extrabold text-slate-900">16.5<span class="text-sm font-medium text-slate-400">/20</span></h3>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 text-xl flex-shrink-0">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Devoirs à Rendre</p>
                <div class="flex items-center gap-2">
                    <h3 class="text-2xl font-extrabold text-slate-900">{{ $assignmentsCount }}</h3>
                    @if($assignmentsCount > 0)
                        <span class="text-xs font-semibold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-full">Proche</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 text-xl flex-shrink-0">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">Cours inscrits</p>
                <div class="flex flex-col w-full min-w-[120px]">
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-1">{{ $enrolledCoursesCount }}</h3>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-purple-500 h-1.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Left Col: Active Courses -->
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Today's Schedule --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-emerald-50/50">
                    <h3 class="font-black text-slate-900"><i class="fa-solid fa-calendar-day text-emerald-600 mr-2"></i> Mon Emploi du Temps d'Aujourd'hui</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                                <th class="px-6 py-3">Horaire</th>
                                <th class="px-6 py-3">Matière</th>
                                <th class="px-6 py-3">Professeur</th>
                                <th class="px-6 py-3 text-right">Salle</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($todaySchedules as $schedule)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-700 font-black text-xs rounded-lg">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 font-bold text-sm text-slate-900">{{ $schedule->subject->subject_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $schedule->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-right text-xs text-slate-500 font-bold"><i class="fa-solid fa-door-open mr-1"></i>{{ $schedule->room ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400 italic text-sm">Aucun cours prévu pour aujourd'hui.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Historique / Replay -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Ressources & Replays</h3>
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Resource 1 -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-bold text-slate-900 line-clamp-1" title="Chapitre 3 : Gestion d'état global">Chapitre 3 : Gestion d'état</h4>
                            <p class="text-xs text-slate-500 mt-1 mb-2">Mis en ligne hier</p>
                            <button class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1 transition-colors">
                                <i class="fa-solid fa-download"></i> Télécharger 2.4 MB
                            </button>
                        </div>
                    </div>
                    
                    <!-- Resource 2 -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex gap-4 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fa-solid fa-circle-play"></i>
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-bold text-slate-900 line-clamp-1" title="Replay Live: Introduction Hooks">Replay Live: Intro Hooks</h4>
                            <p class="text-xs text-slate-500 mt-1 mb-2">Oct 12, 1h 45m</p>
                            <button class="text-indigo-600 hover:text-indigo-800 text-xs font-bold flex items-center gap-1 transition-colors">
                                <i class="fa-solid fa-play"></i> Voir la vidéo
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
        
        <!-- Right Col: Assignments & Calendar -->
        <div class="lg:col-span-1 space-y-8">
            
            <!-- Assignments -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900">À Rendre</h3>
                    <a href="{{ route('dashboard.student.assignments') }}" class="text-xs font-bold text-blue-600 hover:underline">Voir tout</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentAssignments as $assignment)
                        @php
                            $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                            $submission = $assignment->submissions->first();
                        @endphp
                        <div class="p-5 hover:bg-slate-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide {{ $dueDate->isToday() ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $dueDate->diffForHumans() }}
                                </span>
                                <span class="text-xs font-semibold text-slate-500"><i class="fa-regular fa-calendar"></i> {{ $dueDate->format('d M') }}</span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1 leading-tight">{{ $assignment->title }}</h4>
                            <p class="text-xs text-slate-500 mb-3">{{ $assignment->subject->subject_name ?? 'N/A' }}</p>
                            
                            @if(!$submission)
                                <a href="{{ route('dashboard.student.assignments') }}" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition-colors flex justify-center items-center gap-2">
                                    <i class="fa-solid fa-cloud-arrow-up"></i> Déposer
                                </a>
                            @else
                                <div class="w-full py-2 bg-emerald-50 text-emerald-600 text-sm font-bold rounded-lg flex justify-center items-center gap-2">
                                    <i class="fa-solid fa-check-circle"></i> Soumis
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-10 text-center text-slate-400 italic text-sm">
                            Aucun devoir à rendre bientôt.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Grades Summary (Mini Chart) -->
             <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Évolution Moyenne</h3>
                <div class="relative h-40 w-full mb-2">
                    <canvas id="gradeChart"></canvas>
                </div>
             </div>

             {{-- Recent Notifications --}}
             <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-900">Notifications</h3>
                    <a href="{{ route('dashboard.student.notifications') }}" class="text-xs font-bold text-blue-600 hover:underline">Voir tout</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentNotifications as $notification)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 leading-snug">{{ $notification->title }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Aucune nouvelle notification.</p>
                    @endforelse
                </div>
             </div>

        </div>
        
    </div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#94a3b8'; // slate-400

        // Countdown Logic
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
        
        // Mini Line Chart (Grades)
        const ctxGrade = document.getElementById('gradeChart').getContext('2d');
        const gradientGrade = ctxGrade.createLinearGradient(0, 0, 0, 200);
        gradientGrade.addColorStop(0, 'rgba(16, 185, 129, 0.2)'); // emerald-500/20
        gradientGrade.addColorStop(1, 'rgba(16, 185, 129, 0)');
        
        new Chart(ctxGrade, {
            type: 'line',
            data: {
                labels: ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'],
                datasets: [{
                    label: 'Moyenne (/20)',
                    data: [14.5, 15.0, 14.8, 16.0, 15.5, 16.5],
                    borderColor: '#10b981', // emerald-500
                    backgroundColor: gradientGrade,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        bodyFont: { weight: 'bold' },
                        displayColors: false,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) { return context.raw + ' / 20'; }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 10,
                        max: 20,
                        grid: { color: '#f8fafc', drawBorder: false }, // very light slate
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
