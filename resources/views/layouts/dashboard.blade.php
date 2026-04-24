<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'School Platform') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 6px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 3px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }
        
        /* Main content scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .transition-width { transition: width 0.3s ease-in-out; }
        .transition-margin { transition: margin-left 0.3s ease-in-out; }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased overflow-hidden selection:bg-blue-500 selection:text-white">
    
    <div class="flex h-screen w-full relative">
        
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-slate-900 text-white w-64 flex-shrink-0 flex flex-col h-full transition-width fixed md:relative z-20 -left-64 md:left-0 shadow-2xl md:shadow-none">
            
            <!-- Logo area -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-blue-500/20">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <a href="{{ route('public.home') }}" class="font-bold text-xl tracking-tight text-white sidebar-text">
                        Edu<span class="text-blue-400">Connect</span>
                    </a>
                </div>
                <!-- Close menu button for mobile -->
                <button id="close-sidebar" class="md:hidden text-slate-400 hover:text-white focus:outline-none">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <!-- User Info Summary (Optional, visible when expanded) -->
            <div class="px-6 py-6 border-b border-slate-800 sidebar-text flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" alt="User Avatar" class="w-10 h-10 rounded-full border-2 border-slate-700">
                <div>
                    <h4 class="font-semibold text-sm">{{ auth()->user()->name }}</h4>
                    <span class="text-xs text-slate-400 bg-slate-800 px-2 py-0.5 rounded-full inline-block mt-1">@yield('user_role', 'Utilisateur')</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-4 space-y-1">
                @yield('sidebar_menu')
            </nav>

            <!-- Bottom Actions -->
            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-slate-800 transition-colors w-full group">
                        <i class="fa-solid fa-arrow-right-from-bracket group-hover:-translate-x-1 transition-transform"></i>
                        <span class="sidebar-text font-medium text-sm">Déconnexion</span>
                    </button>
                </form>
            </div>
            
            <!-- Collapse Toggler for Desktop -->
            <button id="toggle-sidebar" class="hidden md:flex absolute -right-4 top-24 w-8 h-8 bg-slate-800 text-slate-300 rounded-full items-center justify-center border-4 border-slate-50 hover:bg-slate-700 focus:outline-none z-30 transition-colors shadow-sm">
                <i class="fa-solid fa-chevron-left text-xs transition-transform" id="toggle-icon"></i>
            </button>
        </aside>

        <!-- Overlay for mobile sidebar -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-10 hidden md:hidden transition-opacity opacity-0"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-full overflow-hidden transition-margin min-w-0 bg-slate-50/50">
            
            <!-- Top Header -->
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 flex-shrink-0">
                
                <div class="flex items-center gap-4">
                    <!-- Hamburger for mobile -->
                    <button id="open-sidebar" class="md:hidden text-slate-500 hover:text-slate-900 focus:outline-none p-2 rounded-lg hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Search Bar -->
                    <div class="hidden sm:flex items-center relative group">
                        <i class="fa-solid fa-search absolute left-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="text" placeholder="Search anything..." class="pl-11 pr-4 py-2.5 bg-slate-100 border-transparent rounded-full text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 w-64 lg:w-96 transition-all outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Notifications -->
                    @php
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                        $notifRoute = '#';
                        if(auth()->user()->hasRole('admin')) $notifRoute = route('dashboard.admin.notifications');
                        elseif(auth()->user()->hasRole('professor')) $notifRoute = route('dashboard.professor.notifications');
                        elseif(auth()->user()->hasRole('student')) $notifRoute = route('dashboard.student.notifications');
                    @endphp
                    <a href="{{ $notifRoute }}" class="relative p-2.5 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <i class="fa-regular fa-bell text-xl"></i>
                        @if($unreadCount > 0)
                        <span class="absolute top-2 right-2.5 w-4 h-4 bg-red-500 border-2 border-white rounded-full flex items-center justify-center text-[8px] font-black text-white">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                        @endif
                    </a>
                    
                    <!-- Chat / Messages -->
                    <a href="{{ route('messages.index') }}" class="hidden sm:block relative p-2.5 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <i class="fa-regular fa-envelope text-xl"></i>
                    </a>

                    <!-- Divider -->
                    <div class="h-8 w-px bg-slate-200 mx-2"></div>

                    <!-- Profile Dropdown -->
                    <div class="relative" id="profile-dropdown-container">
                        <button id="profile-btn" class="flex items-center gap-3 p-1 rounded-full hover:bg-slate-100 transition-colors focus:outline-none pr-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" alt="User Avatar" class="w-9 h-9 rounded-full shadow-sm">
                            <div class="hidden md:flex flex-col items-start leading-tight">
                                <span class="text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                                    {{ auth()->user()->role->name ?? 'User' }}
                                </span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 hidden md:block ml-1 transition-transform duration-200" id="profile-chevron"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profile-menu" class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden animate-fade-in-up z-50">
                            <div class="px-4 py-3 border-b border-slate-50 mb-1 md:hidden">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Utilisateur</p>
                                <p class="text-sm font-bold text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                            </div>
                            
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                <i class="fa-regular fa-circle-user text-lg"></i>
                                <span class="font-medium">Mon Profil</span>
                            </a>
                            <a href="{{ auth()->user()->hasRole('admin') ? route('dashboard.admin.settings') : '#' }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                <i class="fa-regular fa-sun text-lg"></i>
                                <span class="font-medium">Paramètres</span>
                            </a>
                            <div class="h-px bg-slate-50 my-1 mx-4"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                                    <span class="font-medium">Se déconnecter</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8 scroll-smooth">
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in-down" role="alert">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                        <span class="block sm:inline font-medium text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggle-sidebar');
            const toggleIcon = document.getElementById('toggle-icon');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            
            const openSidebarBtn = document.getElementById('open-sidebar');
            const closeSidebarBtn = document.getElementById('close-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            let isCollapsed = false;

            // Desktop toggle
            if(toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    isCollapsed = !isCollapsed;
                    
                    if(isCollapsed) {
                        sidebar.classList.remove('w-64');
                        sidebar.classList.add('w-20');
                        toggleIcon.classList.remove('fa-chevron-left');
                        toggleIcon.classList.add('fa-chevron-right');
                        
                        sidebarTexts.forEach(text => {
                            text.style.opacity = '0';
                            setTimeout(() => text.classList.add('hidden'), 200);
                        });
                    } else {
                        sidebar.classList.remove('w-20');
                        sidebar.classList.add('w-64');
                        toggleIcon.classList.remove('fa-chevron-right');
                        toggleIcon.classList.add('fa-chevron-left');
                        
                        sidebarTexts.forEach(text => {
                            text.classList.remove('hidden');
                            setTimeout(() => text.style.opacity = '1', 50);
                        });
                    }
                });
            }

            // Mobile toggle
            function openMobileSidebar() {
                sidebar.classList.remove('-left-64');
                sidebar.classList.add('left-0');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            }

            function closeMobileSidebar() {
                sidebar.classList.remove('left-0');
                sidebar.classList.add('-left-64');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }

            if(openSidebarBtn) openSidebarBtn.addEventListener('click', openMobileSidebar);
            if(closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeMobileSidebar);
            if(overlay) overlay.addEventListener('click', closeMobileSidebar);

            // Profile Dropdown Logic
            const profileBtn = document.getElementById('profile-btn');
            const profileMenu = document.getElementById('profile-menu');
            const profileChevron = document.getElementById('profile-chevron');

            if(profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                    if(profileChevron) profileChevron.classList.toggle('rotate-180');
                });

                document.addEventListener('click', (e) => {
                    if(!profileMenu.classList.contains('hidden') && !profileMenu.contains(e.target)) {
                        profileMenu.classList.add('hidden');
                        if(profileChevron) profileChevron.classList.remove('rotate-180');
                    }
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
