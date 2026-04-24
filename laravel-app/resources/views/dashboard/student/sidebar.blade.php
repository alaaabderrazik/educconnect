<!-- Dashboard Link -->
<a href="{{ route('dashboard.student') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard.student') && !request()->routeIs('dashboard.student.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20 shadow-md border border-emerald-500' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-all group">
    <i class="fa-solid fa-graduation-cap w-5 text-center"></i>
    <span class="sidebar-text font-medium text-sm">Mon Espace</span>
</a>

<!-- Learning Section -->
<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Apprentissage</span>
</div>

<a href="{{ route('dashboard.student.courses') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.student.courses') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-book-open-reader w-5 text-center group-hover:text-blue-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Mes Cours</span>
</a>

<a href="{{ route('dashboard.student.assignments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.student.assignments') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-file-pen w-5 text-center group-hover:text-yellow-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Mes Devoirs</span>
</a>

<a href="{{ route('dashboard.student.online-courses') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.student.online-courses') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-globe w-5 text-center group-hover:text-sky-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Cours en ligne</span>
</a>

<a href="{{ route('dashboard.student.schedule') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.student.schedule') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-regular fa-calendar-check w-5 text-center group-hover:text-amber-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Planning</span>
</a>

<a href="{{ route('dashboard.student.grades') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.student.grades') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-star-half-stroke w-5 text-center group-hover:text-emerald-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Mes Notes</span>
</a>

<!-- Admin Section -->
<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Scolarité</span>
</div>

<a href="{{ route('dashboard.student.payments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.student.payments') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-credit-card w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Paiements</span>
</a>

<a href="{{ route('dashboard.student.history') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.student.history') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-file-pdf w-5 text-center group-hover:text-rose-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Historique</span>
</a>

<!-- Communication -->
<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Communication</span>
</div>

<a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('messages.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-regular fa-comments w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Messages</span>
</a>

<a href="{{ route('dashboard.student.notifications') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.student.notifications') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-regular fa-bell w-5 text-center group-hover:text-rose-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Notifications</span>
</a>

<a href="{{ route('dashboard.student.profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.student.profile') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-regular fa-id-badge w-5 text-center group-hover:text-slate-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Mon Profil</span>
</a>
