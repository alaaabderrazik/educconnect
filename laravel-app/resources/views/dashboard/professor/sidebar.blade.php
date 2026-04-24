{{-- Professor Sidebar Menu --}}
<a href="{{ route('dashboard.professor') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor') && !request()->routeIs('dashboard.professor.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-chalkboard-user w-5 text-center group-hover:text-blue-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Dashboard Prof</span>
</a>

<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Enseignement</span>
</div>

<a href="{{ route('dashboard.professor.classes') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.classes') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-users-rectangle w-5 text-center group-hover:text-blue-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Mes Classes</span>
</a>

<a href="{{ route('dashboard.professor.courses') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.courses') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-book-bookmark w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Mes Cours</span>
</a>

<a href="{{ route('dashboard.professor.online-courses') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.online-courses') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-globe w-5 text-center group-hover:text-sky-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Cours en ligne</span>
</a>

<a href="{{ route('dashboard.professor.schedule') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.schedule') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-regular fa-calendar-check w-5 text-center group-hover:text-teal-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Mon Planning</span>
</a>


<a href="{{ route('dashboard.professor.students') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.students') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-user-graduate w-5 text-center group-hover:text-emerald-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Mes Étudiants</span>
</a>

<a href="{{ route('dashboard.professor.assignments') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.assignments') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-file-pen w-5 text-center group-hover:text-amber-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Devoirs</span>
</a>

<a href="{{ route('dashboard.professor.all-submissions') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.all-submissions') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-inbox w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Soumissions</span>
</a>

<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Gestion</span>
</div>

<a href="{{ route('dashboard.professor.attendance') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.attendance') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-clipboard-user w-5 text-center group-hover:text-emerald-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Présence</span>
</a>

<a href="{{ route('dashboard.professor.statistics') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.statistics') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-chart-line w-5 text-center group-hover:text-purple-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Statistiques</span>
</a>

<div class="mt-4 mb-2">
    <span class="sidebar-text text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Personnel</span>
</div>

<a href="{{ route('messages.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg {{ request()->routeIs('messages.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <div class="flex items-center gap-3">
        <i class="fa-regular fa-message w-5 text-center group-hover:text-cyan-400 transition-colors"></i>
        <span class="sidebar-text font-medium text-sm">Messages</span>
    </div>
    @php
        $unreadMessages = auth()->user()->conversations()->with('latestMessage')->get()->filter(function($conv) {
            return $conv->latestMessage && $conv->latestMessage->created_at > ($conv->pivot->last_read_at ?? '1970-01-01');
        })->count();
    @endphp
    @if($unreadMessages > 0)
    <span class="bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadMessages }}</span>
    @endif
</a>

<a href="{{ route('dashboard.professor.notifications') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.notifications') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-bell w-5 text-center group-hover:text-rose-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Notifications</span>
</a>

<a href="{{ route('dashboard.professor.profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.professor.profile') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-300 hover:text-white hover:bg-slate-800' }} transition-colors group">
    <i class="fa-solid fa-user-gear w-5 text-center group-hover:text-slate-400 transition-colors"></i>
    <span class="sidebar-text font-medium text-sm">Profil</span>
</a>
