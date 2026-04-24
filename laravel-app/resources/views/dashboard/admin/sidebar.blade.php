<!-- Dashboard Link -->
<a href="{{ route('dashboard.admin') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-gauge-high w-5 text-center group-hover:text-blue-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Dashboard Admin</span>
</a>

<!-- Users Section -->
<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Utilisateurs</span>
</div>

<a href="{{ route('dashboard.admin.students') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.students') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-user-graduate w-5 text-center group-hover:text-blue-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Étudiants</span>
</a>

<a href="{{ route('dashboard.admin.professors') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.professors') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-chalkboard-user w-5 text-center group-hover:text-amber-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Professeurs</span>
</a>

<a href="{{ route('dashboard.admin.admins') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.admins') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-user-shield w-5 text-center group-hover:text-emerald-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Administrateurs</span>
</a>

<a href="{{ route('dashboard.admin.parents.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.parents.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-user-group w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Parents (Liaison)</span>
</a>

<!-- Academic Section -->
<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Académique</span>
</div>

<a href="{{ route('dashboard.admin.levels') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.levels') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-layer-group w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Niveaux & Classes</span>
</a>

<a href="{{ route('dashboard.admin.subjects') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.subjects') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-book-open w-5 text-center group-hover:text-emerald-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Matières</span>
</a>

<a href="{{ route('dashboard.admin.online-courses') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.online-courses') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-globe w-5 text-center group-hover:text-sky-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Cours en ligne</span>
</a>

<a href="{{ route('dashboard.admin.years') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.years') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-calendar-days w-5 text-center group-hover:text-rose-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Années Scolaires</span>
</a>

<a href="{{ route('dashboard.admin.schedules') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.schedules') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-calendar-week w-5 text-center group-hover:text-teal-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Emploi du Temps</span>
</a>


<a href="{{ route('dashboard.admin.billing') }}" class="flex items-center gap-3 px-3 py-2 border-b border-slate-700/50 mb-2 {{ request()->routeIs('dashboard.admin.billing') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-file-invoice-dollar w-5 text-center group-hover:text-emerald-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Facturation</span>
</a>

<!-- Site Section -->
<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Site Vitrine</span>
</div>

<a href="{{ route('dashboard.admin.pages') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.pages') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-file-lines w-5 text-center group-hover:text-blue-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Contenu Pages</span>
</a>

<a href="{{ route('dashboard.admin.services') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.services') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-briefcase w-5 text-center group-hover:text-amber-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Nos Services</span>
</a>

<a href="{{ route('dashboard.admin.team') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.team') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-users-gear w-5 text-center group-hover:text-blue-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Équipe & Staff</span>
</a>

<a href="{{ route('dashboard.admin.faqs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.faqs') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-circle-question w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Questions (FAQ)</span>
</a>

<a href="{{ route('dashboard.admin.testimonials') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.testimonials') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-quote-left w-5 text-center group-hover:text-rose-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Témoignages</span>
</a>

<a href="{{ route('dashboard.admin.settings') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.settings') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-sliders w-5 text-center group-hover:text-slate-300 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Configuration Site</span>
</a>

<!-- System Section -->
<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Communication</span>
</div>

<a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('messages.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-regular fa-comments w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Messages</span>
</a>

<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Système</span>
</div>

<a href="{{ route('dashboard.admin.roles') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.roles') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-shield-halved w-5 text-center group-hover:text-emerald-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Rôles & Permissions</span>
</a>

<a href="{{ route('dashboard.admin.logs') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin.logs') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-terminal w-5 text-center group-hover:text-slate-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Logs Système</span>
</a>
