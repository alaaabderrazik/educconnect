@extends('layouts.dashboard')

@section('title', 'Dashboard Parent')
@section('user_role', 'Parent')

@section('sidebar_menu')
    @include('dashboard.parent.sidebar')
@endsection

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Bonjour, {{ Auth::user()->first_name }} 👋</h1>
            <p class="text-slate-500 text-sm mt-1">Voici le suivi de la progression de vos enfants.</p>
        </div>
        @if($firstChild)
        <div class="flex items-center gap-3 bg-white p-2 pr-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black">
                {{ $firstChild->user->first_name[0] }}{{ $firstChild->user->last_name[0] }}
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Enfant suivi</p>
                <h4 class="text-sm font-black text-slate-800">{{ $firstChild->user->name }}</h4>
            </div>
        </div>
        @endif
    </div>

    <!-- 4. Notifications intelligentes -->
    @if($needsToStudyAlert && $firstChild)
    <div class="bg-rose-50 border-l-4 border-rose-500 p-6 mb-8 rounded-3xl shadow-sm animate-pulse-subtle">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center shadow-lg shadow-rose-200">
                <i class="fa-solid fa-bell text-xl"></i>
            </div>
            <div>
                <h3 class="text-rose-900 font-black text-lg">Attention !</h3>
                <p class="text-rose-700 font-medium text-sm">Votre enfant <strong>{{ $firstChild->user->first_name }}</strong> n'a pas étudié aujourd'hui. Un petit encouragement ?</p>
            </div>
        </div>
    </div>
    @endif

    <!-- 1. Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-children text-xl"></i>
            </div>
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-widest">Enfants</h3>
            <p class="text-3xl font-black text-slate-900 mt-1">{{ $childrenCount }}</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-book text-xl"></i>
            </div>
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-widest">Cours suivis</h3>
            <p class="text-3xl font-black text-slate-900 mt-1">{{ $coursesCount }}</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-chart-line text-xl"></i>
            </div>
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-widest">Progression Globale</h3>
            <p class="text-3xl font-black text-emerald-600 mt-1">{{ $globalProgress }}%</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i class="fa-solid fa-clock-rotate-left text-xl"></i>
            </div>
            <h3 class="text-slate-500 text-xs font-bold uppercase tracking-widest">Dernière activité</h3>
            <p class="text-xl font-black text-slate-900 mt-3 leading-tight">{{ $lastActivity }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- 2. Suivi des Cours -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-blue-600"></i> Suivi des Cours
                </h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-600 text-[10px] font-black uppercase rounded-full">En direct</span>
            </div>
            <div class="p-8 space-y-8">
                @forelse($coursesData as $course)
                <div>
                    <div class="flex justify-between items-end mb-3">
                        <div>
                            <h4 class="text-sm font-black text-slate-800">{{ $course['title'] }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Dernière leçon: {{ $course['last_lesson'] }}</p>
                        </div>
                        <span class="text-xs font-black {{ $course['progress'] > 70 ? 'text-emerald-500' : 'text-blue-500' }}">{{ $course['progress'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r {{ $course['progress'] > 70 ? 'from-emerald-400 to-emerald-600' : 'from-blue-400 to-blue-600' }} shadow-sm" style="width: {{ $course['progress'] }}%"></div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <i class="fa-solid fa-book-open text-4xl text-slate-200 mb-2"></i>
                    <p class="text-slate-400 italic text-sm">Aucun cours en cours.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- 3. Performance -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50">
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-award text-amber-500"></i> Performance & Résultats
                </h2>
            </div>
            <div class="p-8 flex-grow space-y-6">
                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Moyenne Générale</p>
                        <p class="text-2xl font-black text-slate-800">{{ $performanceData['average'] ?? 'N/A' }} <span class="text-xs text-slate-400">/ 20</span></p>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Dernier Quiz</p>
                        <p class="text-2xl font-black text-emerald-600">{{ $performanceData['last_quiz'] ?? 'N/A' }}%</p>
                    </div>
                </div>

                <!-- Global Score -->
                <div class="p-8 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl text-white shadow-xl shadow-indigo-200 relative overflow-hidden group">
                    <i class="fa-solid fa-trophy absolute -right-4 -bottom-4 text-8xl text-white/10 group-hover:scale-110 transition-transform"></i>
                    <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest mb-1">Score Global accumulé</p>
                    <p class="text-4xl font-black">{{ $performanceData['global_score'] ?? '0' }}</p>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-white/20 rounded text-[9px] font-black">TOP 10% DE LA CLASSE</span>
                    </div>
                </div>

                <!-- Action Button -->
                <button class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-black transition-all flex items-center justify-center gap-2">
                    Voir le bulletin détaillé <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes pulse-subtle {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.85; }
}
.animate-pulse-subtle {
    animation: pulse-subtle 3s ease-in-out infinite;
}
</style>
@endsection
