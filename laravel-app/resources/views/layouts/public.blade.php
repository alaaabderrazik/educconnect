<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'School Platform'))</title>

    <!-- Google Fonts: Inter as the primary modern sans-serif -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @if(isset($settings['site_favicon']))
        <link rel="icon" href="{{ asset('storage/'.$settings['site_favicon']) }}" type="image/x-icon">
    @endif

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        :root {
            --primary-color: {{ $settings['primary_color'] ?? '#2563eb' }};
            --secondary-color: {{ $settings['secondary_color'] ?? '#fbbf24' }};
            --accent-color: {{ $settings['accent_color'] ?? '#ec4899' }};
            
            /* Calculated shades using color-mix */
            --primary-light: color-mix(in srgb, var(--primary-color), white 90%);
            --primary-dark: color-mix(in srgb, var(--primary-color), black 20%);
            --secondary-light: color-mix(in srgb, var(--secondary-color), white 90%);
            --accent-light: color-mix(in srgb, var(--accent-color), white 90%);
        }
        body { font-family: {{ $settings['font_family'] ?? "'Inter', sans-serif" }}; }
        html { scroll-behavior: smooth; }
        
        /* Dynamic Theme Utilities */
        .text-primary { color: var(--primary-color); }
        .text-primary-dark { color: var(--primary-dark); }
        .bg-primary { background-color: var(--primary-color); }
        .bg-primary-light { background-color: var(--primary-light); }
        .border-primary { border-color: var(--primary-color); }
        .border-primary-light { border-color: var(--primary-light); }
        
        .text-secondary { color: var(--secondary-color); }
        .bg-secondary { background-color: var(--secondary-color); }
        .bg-secondary-light { background-color: var(--secondary-light); }
        
        .text-accent { color: var(--accent-color); }
        .bg-accent { background-color: var(--accent-color); }
        .bg-accent-light { background-color: var(--accent-light); }

        /* Hover States */
        .hover-text-primary:hover { color: var(--primary-dark); }
        .hover-bg-primary:hover { background-color: var(--primary-dark); }
        .hover-shadow-primary:hover { box-shadow: 0 10px 25px -5px color-mix(in srgb, var(--primary-color), transparent 50%); }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased flex flex-col min-h-screen">

    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3 text-primary">
                    <a href="{{ route('public.home') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white font-bold text-xl shadow-lg hover-shadow-primary transition-all">
                            @if(!empty($settings['site_logo']))
                                <img src="{{ asset('storage/'.$settings['site_logo']) }}" alt="Logo" class="w-8 h-8 object-contain">
                            @else
                                <i class="fa-solid fa-graduation-cap"></i>
                            @endif
                        </div>
                        <span class="font-bold text-2xl tracking-tight text-slate-800">
                            {{ $settings['school_name'] ?? 'EduConnect' }}
                        </span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('public.home') }}" class="text-slate-600 hover-text-primary font-medium transition-colors {{ request()->routeIs('public.home') ? 'text-primary' : '' }}">Home</a>
                    <a href="{{ route('public.about') }}" class="text-slate-600 hover-text-primary font-medium transition-colors {{ request()->routeIs('public.about') ? 'text-primary' : '' }}">About</a>
                    <a href="{{ route('public.services') }}" class="text-slate-600 hover-text-primary font-medium transition-colors {{ request()->routeIs('public.services') ? 'text-primary' : '' }}">Services</a>
                    <a href="{{ route('public.contact') }}" class="text-slate-600 hover-text-primary font-medium transition-colors {{ request()->routeIs('public.contact') ? 'text-primary' : '' }}">Contact</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-slate-600 hover-text-primary font-medium transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover-text-primary font-medium transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-primary hover-bg-primary text-white px-5 py-2.5 rounded-full font-medium transition-all shadow-md hover-shadow-primary transform hover:-translate-y-0.5">Register</a>
                        @endif
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-slate-500 hover:text-slate-900 focus:outline-none p-2">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-slate-200 px-4 pt-2 pb-4 space-y-1 shadow-lg absolute w-full left-0">
            <a href="{{ route('public.home') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-700 hover-text-primary hover:bg-slate-50">Home</a>
            <a href="{{ route('public.about') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-700 hover-text-primary hover:bg-slate-50">About</a>
            <a href="{{ route('public.services') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-700 hover-text-primary hover:bg-slate-50">Services</a>
            <a href="{{ route('public.contact') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-700 hover-text-primary hover:bg-slate-50">Contact</a>
            <div class="border-t border-slate-100 my-2 pt-2"></div>
            @auth
                <a href="{{ url('/dashboard') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-700 hover-text-primary hover:bg-slate-50">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-700 hover-text-primary hover:bg-slate-50">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block px-3 py-3 rounded-lg text-base text-primary font-semibold hover-bg-primary-light transition-colors">Register</a>
                @endif
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-1 border-b border-slate-800 pb-8 md:border-0 md:pb-0">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <span class="font-bold text-2xl tracking-tight text-white">
                            {{ $settings['school_name'] ?? 'EduConnect' }}
                        </span>
                    </div>
                    <p class="text-slate-400 leading-relaxed mb-6">
                        Empowering the next generation through accessible, high-quality education and innovative learning tools.
                    </p>
                    <div class="flex space-x-4">
                        <a href="{{ $settings['social_facebook'] ?? '#' }}" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover-bg-primary hover:text-white transition-all"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="{{ $settings['social_twitter'] ?? '#' }}" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-400 hover:text-white transition-all"><i class="fa-brands fa-twitter"></i></a>
                        <a href="{{ $settings['social_instagram'] ?? '#' }}" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-accent hover:text-white transition-all"><i class="fa-brands fa-instagram"></i></a>
                        <a href="{{ $settings['social_linkedin'] ?? '#' }}" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-900 hover:text-white transition-all"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-6 text-white tracking-wide uppercase text-sm">Quick Links</h3>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="{{ route('public.home') }}" class="hover-text-primary transition-colors inline-block transform hover:translate-x-1 duration-200">Home</a></li>
                        <li><a href="{{ route('public.about') }}" class="hover-text-primary transition-colors inline-block transform hover:translate-x-1 duration-200">About Us</a></li>
                        <li><a href="{{ route('public.services') }}" class="hover-text-primary transition-colors inline-block transform hover:translate-x-1 duration-200">Our Services</a></li>
                        <li><a href="{{ route('public.contact') }}" class="hover-text-primary transition-colors inline-block transform hover:translate-x-1 duration-200">Contact Us</a></li>
                        <li><a href="{{ route('login') }}" class="hover-text-primary transition-colors inline-block transform hover:translate-x-1 duration-200">Student Portal</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-6 text-white tracking-wide uppercase text-sm">Services</h3>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="#" class="hover-text-primary transition-colors inline-block transform hover:translate-x-1 duration-200">Online Courses</a></li>
                        <li><a href="#" class="hover-text-primary transition-colors inline-block transform hover:translate-x-1 duration-200">Live Mentoring</a></li>
                        <li><a href="#" class="hover-text-primary transition-colors inline-block transform hover:translate-x-1 duration-200">Corporate Training</a></li>
                        <li><a href="#" class="hover-text-primary transition-colors inline-block transform hover:translate-x-1 duration-200">Certifications</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-6 text-white tracking-wide uppercase text-sm">Contact Info</h3>
                    <ul class="space-y-4 text-slate-400">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot mt-1 text-primary"></i>
                            <span>{!! nl2br(e($settings['contact_address'] ?? "123 Innovation Drive,\nTech City, France")) !!}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-secondary"></i>
                            <span>{{ $settings['contact_phone'] ?? '+33 (0)1 23 45 67 89' }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-accent"></i>
                            <a href="mailto:{{ $settings['contact_email'] ?? 'contact@educonnect.com' }}" class="hover-text-primary transition-colors">{{ $settings['contact_email'] ?? 'contact@educonnect.com' }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} {{ $settings['school_name'] ?? 'EduConnect' }}. All rights reserved.
                </p>
                <div class="flex space-x-6 text-sm text-slate-500">
                    <a href="#" class="hover:text-slate-300 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-300 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            var icon = this.querySelector('i');
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                menu.classList.add('block');
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
            } else {
                menu.classList.remove('block');
                menu.classList.add('hidden');
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        });
        
        // Header scroll effect
        window.addEventListener('scroll', function() {
            var header = document.querySelector('header');
            if (window.scrollY > 10) {
                header.classList.add('shadow-md', 'py-0');
            } else {
                header.classList.remove('shadow-md', 'py-0');
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
