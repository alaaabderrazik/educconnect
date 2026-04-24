@extends('layouts.dashboard')

@section('title', 'Logs Système')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    <!-- Dashboard Link -->
    <a href="{{ route('dashboard.admin') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
        <i class="fa-solid fa-chart-pie w-5 text-center group-hover:text-blue-400 transition-colors"></i>
        <span class="sidebar-text font-medium text-sm">Dashboard</span>
    </a>

    <!-- Users Section -->
    <div class="mt-4 mb-2">
        <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Utilisateurs</span>
    </div>
    
    <a href="{{ route('dashboard.admin.students') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
        <i class="fa-solid fa-user-graduate w-5 text-center group-hover:text-blue-400 transition-colors"></i>
        <span class="sidebar-text font-medium text-sm">Étudiants</span>
    </a>
    
    <a href="{{ route('dashboard.admin.professors') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
        <i class="fa-solid fa-chalkboard-user w-5 text-center group-hover:text-amber-400 transition-colors"></i>
        <span class="sidebar-text font-medium text-sm">Professeurs</span>
    </a>
    
    <a href="{{ route('dashboard.admin.roles') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
        <i class="fa-solid fa-shield-halved w-5 text-center group-hover:text-purple-400 transition-colors"></i>
        <span class="sidebar-text font-medium text-sm">Rôles & Permissions</span>
    </a>

    <!-- Academics Section -->
    <div class="mt-4 mb-2">
        <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Scolarité</span>
    </div>
    
    <a href="{{ route('dashboard.admin.levels') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
        <i class="fa-solid fa-layer-group w-5 text-center group-hover:text-emerald-400 transition-colors"></i>
        <span class="sidebar-text font-medium text-sm">Niveaux</span>
    </a>
    
    <a href="{{ route('dashboard.admin.subjects') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
        <i class="fa-solid fa-book-open w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
        <span class="sidebar-text font-medium text-sm">Matières</span>
    </a>
    
    <a href="{{ route('dashboard.admin.years') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
        <i class="fa-regular fa-calendar-days w-5 text-center group-hover:text-orange-400 transition-colors"></i>
        <span class="sidebar-text font-medium text-sm">Année Scolaire</span>
    </a>

     <!-- Public Site Management -->
     <div class="mt-4 mb-2">
        <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Site Vitrine</span>
    </div>
    
    <a href="{{ route('dashboard.admin.pages') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
        <i class="fa-solid fa-browser w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
        <span class="sidebar-text font-medium text-sm">Pages</span>
    </a>
    
    <a href="{{ route('dashboard.admin.services') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
        <i class="fa-solid fa-briefcase w-5 text-center group-hover:text-rose-400 transition-colors"></i>
        <span class="sidebar-text font-medium text-sm">Services</span>
    </a>

    <!-- System -->
    <div class="mt-4 mb-2">
        <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Système</span>
    </div>
    
    <a href="{{ route('dashboard.admin.logs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-600 font-medium text-white transition-all shadow-md shadow-blue-500/20 group border border-blue-500">
        <i class="fa-solid fa-server w-5 text-center"></i>
        <span class="sidebar-text">Logs</span>
    </a>
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Journaux d'Activité 🕵️‍♂️</h1>
            <p class="text-slate-500 text-sm mt-1">Surveillez les actions des utilisateurs et les événements système.</p>
        </div>
        <button class="px-4 py-2 flex items-center gap-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold shadow-sm hover:bg-slate-50 transition-all font-black uppercase tracking-widest">
            <i class="fa-solid fa-trash mr-1 text-rose-500"></i> Nettoyer
        </button>
    </div>

    <!-- Logs List -->
    <div class="bg-slate-900 rounded-2xl shadow-xl border border-slate-800 overflow-hidden font-mono text-[11px]">
        <div class="p-4 bg-slate-800/50 border-b border-slate-800 flex items-center gap-2">
            <div class="flex gap-1.5">
                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
            </div>
            <span class="ml-4 text-slate-500 font-bold uppercase tracking-widest">System_log_stream</span>
        </div>
        <div class="p-6 space-y-3">
            <div class="flex gap-4">
                <span class="text-slate-500">[2026-10-12 14:32:01]</span>
                <span class="text-emerald-400">INFO:</span>
                <span class="text-slate-300">User 'Admin' updated page 'Accueil' content.</span>
            </div>
            <div class="flex gap-4">
                <span class="text-slate-500">[2026-10-12 14:35:12]</span>
                <span class="text-amber-400">WARN:</span>
                <span class="text-slate-300">Unusual login attempt detected from IP 192.168.1.45 (User: Jean Dupont).</span>
            </div>
             <div class="flex gap-4">
                <span class="text-slate-500">[2026-10-12 14:40:00]</span>
                <span class="text-blue-400">DEBUG:</span>
                <span class="text-slate-300">Scheduled task 'DatabaseBackup' completed successfully.</span>
            </div>
            <div class="flex gap-4">
                <span class="text-slate-500">[2026-10-12 14:45:05]</span>
                <span class="text-rose-400">ERROR:</span>
                <span class="text-slate-300">File upload failed (PDF) - Storage limit reached for User ID 42.</span>
            </div>
        </div>
        <div class="p-4 bg-slate-800/20 text-slate-600 border-t border-slate-800 text-center">
            Fin du flux de log — <span class="text-blue-500">Rafraîchir</span>
        </div>
    </div>
@endsection
