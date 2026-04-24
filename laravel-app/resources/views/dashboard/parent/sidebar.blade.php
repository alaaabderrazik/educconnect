<!-- Dashboard Link -->
<a href="{{ route('dashboard.parent') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard.parent') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 border border-indigo-500' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-all group">
    <i class="fa-solid fa-house-chimney-user w-5 text-center"></i>
    <span class="sidebar-text font-medium text-sm">Mon Espace Parent</span>
</a>

<!-- Follow-up Section -->
<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Suivi</span>
</div>

<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
    <i class="fa-solid fa-graduation-cap w-5 text-center group-hover:text-blue-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Cours & Progrès</span>
</a>

<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
    <i class="fa-solid fa-award w-5 text-center group-hover:text-amber-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Résultats Quiz</span>
</a>

<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
    <i class="fa-solid fa-calendar-check w-5 text-center group-hover:text-teal-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Planning Enfant</span>
</a>

<!-- Communication -->
<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Echanges</span>
</div>

<a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('messages.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-regular fa-comments w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Messages</span>
</a>

<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
    <i class="fa-regular fa-bell w-5 text-center group-hover:text-rose-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Alertes</span>
</a>

<!-- Account -->
<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Mon Compte</span>
</div>

<a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition-colors group">
    <i class="fa-regular fa-user-circle w-5 text-center group-hover:text-slate-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Profil</span>
</a>
