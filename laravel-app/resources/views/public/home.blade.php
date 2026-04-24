@extends('layouts.public')

@section('title', 'Welcome to EduConnect | Future of Learning')

@section('content')

<!-- Hero Section with Animated Gradient Background -->
<section class="relative overflow-hidden bg-slate-900 text-white min-h-[90vh] flex items-center">
    <!-- Abstract Animated Background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full blur-[120px] animate-pulse" style="background-color: color-mix(in srgb, var(--primary-color), transparent 70%);"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full blur-[120px] animate-pulse" style="background-color: color-mix(in srgb, var(--secondary-color), transparent 70%); animation-delay: 2s;"></div>
        <div class="absolute inset-0 bg-cover bg-center opacity-20 mix-blend-overlay" style="background-image: url('{{ isset($page['hero']) && $page['hero']->image ? asset('storage/'.$page['hero']->image) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80' }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8">
                <div class="inline-block px-4 py-1.5 rounded-full border border-primary/30 bg-primary/10 backdrop-blur-sm text-primary font-semibold text-sm tracking-wide mb-2 animate-fade-in-up" style="border-color: color-mix(in srgb, var(--primary-color), transparent 70%); background-color: color-mix(in srgb, var(--primary-color), transparent 90%);">
                    <span class="relative flex h-2 w-2 inline-block mr-2 top-[3px]">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background-color: var(--primary-color)"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    Enrollment for 2026 is now open
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight leading-[1.1] animate-fade-in-up" style="animation-delay: 0.1s;">
                    {!! nl2br(e($page['hero']->title ?? 'L\'excellence académique au bout des doigts')) !!}
                </h1>
                
                <p class="text-lg md:text-xl text-slate-300 leading-relaxed max-w-lg mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                    {{ $page['hero']->content ?? 'Une plateforme moderne pour gérer vos cours, vos examens et votre réussite professionnelle.' }}
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 animate-fade-in-up" style="animation-delay: 0.3s;">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-full bg-primary hover-bg-primary text-white font-bold text-lg transition-all shadow-lg hover-shadow-primary hover:-translate-y-1 text-center flex items-center justify-center gap-2">
                            S'inscrire <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @else
                        <a href="#" class="px-8 py-4 rounded-full bg-primary hover-bg-primary text-white font-bold text-lg transition-all shadow-lg hover-shadow-primary hover:-translate-y-1 text-center flex items-center justify-center gap-2">
                            S'inscrire <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @endif
                    <a href="{{ route('public.services') }}" class="px-8 py-4 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white font-bold text-lg transition-all text-center flex items-center justify-center gap-2">
                        Découvrir nos formations
                    </a>
                </div>
                
                <div class="pt-8 flex items-center gap-4 animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-slate-900 object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Student">
                        <img class="w-10 h-10 rounded-full border-2 border-slate-900 object-cover" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Student">
                        <img class="w-10 h-10 rounded-full border-2 border-slate-900 object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Student">
                        <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-slate-800 flex items-center justify-center text-xs font-bold">+2k</div>
                    </div>
                    <div class="text-sm text-slate-400">
                        <div class="flex text-yellow-500 mb-1">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <span class="font-semibold text-white">4.9/5</span> from over 2,000 reviews
                    </div>
                </div>
            </div>
            
            <div class="hidden lg:block relative h-[600px] w-full animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="absolute inset-0 rounded-3xl transform rotate-3 scale-105 blur-lg" style="background: linear-gradient(to top right, color-mix(in srgb, var(--primary-color), transparent 70%), color-mix(in srgb, var(--accent-color), transparent 70%));"></div>
                <img src="{{ isset($page['hero']) && $page['hero']->image ? asset('storage/'.$page['hero']->image) : 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80' }}" alt="Students learning" class="absolute inset-0 w-full h-full object-cover rounded-3xl shadow-2xl border border-white/10 relative z-10 transition-transform hover:scale-[1.02] duration-500">
                
                <!-- Floating Glass Card -->
                <div class="absolute -bottom-10 -left-10 bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-2xl z-20 animate-bounce-slow" style="animation: bounceSlow 6s infinite;">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white text-xl">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <p class="text-white font-bold text-lg">98% Success Rate</p>
                            <p class="text-slate-200 text-sm">Job placement within 6 months</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features / Presentation -->
<section class="py-24 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-primary font-bold tracking-wide uppercase text-sm mb-2">Notre École</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">Excellence en Éducation</h3>
            <p class="text-slate-600 text-lg">Nous formons les leaders de demain grâce à une pédagogie innovante et un accompagnement personnalisé.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Mission Card -->
            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 rounded-2xl bg-primary-light text-primary flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">{{ $page['mission']->title ?? 'Notre Mission' }}</h4>
                <p class="text-slate-600 leading-relaxed">{{ $page['mission']->content ?? 'Démocratiser l\'accès à une éducation d\'excellence.' }}</p>
            </div>
            
            <!-- Vision Card -->
            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 rounded-2xl bg-indigo-100 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-all" style="color: var(--secondary-color); background-color: color-mix(in srgb, var(--secondary-color), transparent 90%);">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">{{ $page['vision']->title ?? 'Notre Vision' }}</h4>
                <p class="text-slate-600 leading-relaxed">{{ $page['vision']->content ?? 'Devenir le pont principal entre l\'apprentissage et l\'entreprise.' }}</p>
            </div>
            
            <!-- Valeurs Card -->
            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-all" style="color: var(--accent-color); background-color: color-mix(in srgb, var(--accent-color), transparent 90%);">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">{{ $page['values']->title ?? 'Nos Valeurs' }}</h4>
                <p class="text-slate-600 leading-relaxed">{{ $page['values']->content ?? 'L\'excellence, l\'intégrité, l\'innovation et le respect.' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Services / Formations -->
<section class="py-24 bg-slate-50 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12">
            <div class="max-w-2xl">
                <h2 class="text-primary font-bold tracking-wide uppercase text-sm mb-2">Nos Formations</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900">Développez vos compétences</h3>
            </div>
            <a href="{{ route('public.services') }}" class="mt-4 md:mt-0 text-primary font-semibold hover-text-primary flex items-center gap-2 group">
                Voir toutes les formations <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($services as $service)
            <!-- Course Card -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover-shadow-primary transition-all duration-500 group flex flex-col">
                <div class="p-8 flex-grow flex flex-col">
                    <div class="w-14 h-14 bg-primary-light text-primary rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:bg-primary group-hover:text-white transition-all">
                        <i class="fa-solid {{ $service->icon }}"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-primary transition-colors">{{ $service->title }}</h4>
                    <p class="text-slate-600 text-sm mb-4 line-clamp-3 leading-relaxed">{{ $service->description }}</p>
                    
                    <div class="mt-auto pt-4 flex items-center justify-between">
                        <a href="{{ route('public.services') }}" class="text-primary font-bold text-xs uppercase tracking-widest hover-text-primary flex items-center gap-2">
                            En savoir plus <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-slate-400">Aucun service disponible.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Animated Statistics -->
<section class="py-20 bg-primary relative overflow-hidden">
    <div class="absolute inset-0 mix-blend-multiply" style="background: linear-gradient(to right, color-mix(in srgb, var(--primary-color), black 20%), color-mix(in srgb, var(--secondary-color), black 20%));"></div>
    <!-- Decorative circles -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-2xl transform -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl transform translate-x-1/3 translate-y-1/3"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
            @php 
                $stats = isset($page['stats']) ? json_decode($page['stats']->content, true) : [];
            @endphp
            <div class="p-4">
                <div class="text-5xl font-black mb-2 flex justify-center items-center"><span class="counter" data-target="{{ $stats['students'] ?? '5000' }}">0</span>+</div>
                <div class="text-primary-light font-medium uppercase tracking-wider text-sm">Étudiants</div>
            </div>
            <div class="p-4">
                <div class="text-5xl font-black mb-2 flex justify-center items-center"><span class="counter" data-target="{{ $stats['experts'] ?? '150' }}">0</span>+</div>
                <div class="text-primary-light font-medium uppercase tracking-wider text-sm">Experts</div>
            </div>
            <div class="p-4">
                <div class="text-5xl font-black mb-2 flex justify-center items-center"><span class="counter" data-target="{{ $stats['courses'] ?? '45' }}">0</span></div>
                <div class="text-primary-light font-medium uppercase tracking-wider text-sm">Formations</div>
            </div>
            <div class="p-4">
                <div class="text-5xl font-black mb-2 flex justify-center items-center"><span class="counter" data-target="{{ $stats['graduates'] ?? '98' }}">0</span>%</div>
                <div class="text-primary-light font-medium uppercase tracking-wider text-sm">Diplômés</div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials (Simple representation) -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-primary font-bold tracking-wide uppercase text-sm mb-2">Témoignages</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900">Ce que nos étudiants disent</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testimonials as $test)
            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 relative flex flex-col">
                <div class="text-yellow-400 mb-4 flex gap-1 text-lg">
                    @for($i=0; $i<$test->stars; $i++)<i class="fa-solid fa-star"></i>@endfor
                </div>
                <p class="text-slate-600 italic mb-6 flex-grow">"{{ $test->content }}"</p>
                <div class="flex items-center gap-4">
                    <img src="{{ $test->photo_path ?? 'https://ui-avatars.com/api/?name='.$test->name }}" alt="{{ $test->name }}" class="w-12 h-12 rounded-full border-2 border-white shadow-md object-cover">
                    <div>
                        <h5 class="font-bold text-slate-900">{{ $test->name }}</h5>
                        <p class="text-xs text-slate-500">{{ $test->position }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-24 bg-slate-50 border-y border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-primary font-bold tracking-wide uppercase text-sm mb-2">FAQ</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900">Questions Fréquentes</h3>
            <p class="text-slate-600 mt-4">Tout ce que vous devez savoir sur nos formations et notre méthode d'apprentissage.</p>
        </div>
        
        <div class="space-y-4">
            @foreach($faqs as $faq)
            <div class="faq-item bg-white rounded-2xl border border-slate-200 overflow-hidden transition-all duration-300">
                <button class="w-full px-8 py-6 text-left flex justify-between items-center focus:outline-none group">
                    <span class="font-bold text-slate-900 group-hover:text-primary transition-colors">{{ $faq->question }}</span>
                    <i class="fa-solid fa-chevron-down text-slate-400 transition-transform duration-300"></i>
                </button>
                <div class="faq-answer max-h-0 overflow-hidden transition-all duration-300 bg-slate-50">
                    <div class="px-8 py-6 text-slate-600 leading-relaxed">
                        {{ $faq->answer }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Blog Preview Section -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16">
            <div class="max-w-2xl text-left">
                <h2 class="text-primary font-bold tracking-wide uppercase text-sm mb-2">Notre Blog</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900">Actualités & Conseils</h3>
                <p class="text-slate-600 mt-4 text-lg">Restez informé sur les dernières tendances du marché et profitez des conseils de nos experts.</p>
            </div>
            <a href="#" class="mt-8 md:mt-0 px-6 py-3 border-2 border-slate-900 rounded-xl font-bold hover:bg-slate-900 hover:text-white transition-all group flex items-center gap-2">
                Voir tous les articles <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Article 1 -->
            <article class="group">
                <div class="relative overflow-hidden rounded-3xl mb-6 shadow-sm group-hover:shadow-xl transition-all duration-500">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Blog 1" class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4">
                        <span class="bg-primary text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Carrière</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-slate-500 text-sm mb-3">
                    <span class="flex items-center gap-1.5"><i class="fa-regular fa-calendar"></i> 12 Mars 2026</span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span>5 min de lecture</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-primary transition-colors leading-tight">
                    Comment réussir sa reconversion dans la Data Science en 2026
                </h4>
                <p class="text-slate-600 line-clamp-2 mb-4">
                    Découvrez les compétences clés et les erreurs à éviter pour pivoter sereinement vers les métiers de la donnée.
                </p>
                <a href="#" class="inline-flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all">
                    Lire la suite <i class="fa-solid fa-arrow-right-long"></i>
                </a>
            </article>

            <!-- Article 2 -->
            <article class="group">
                <div class="relative overflow-hidden rounded-3xl mb-6 shadow-sm group-hover:shadow-xl transition-all duration-500">
                    <img src="https://images.prismic.io/littlebigthings/ZsXDoEaF0TcGJKZp_IAGenerative.jpg?auto=format,compress" alt="Blog 2" class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4">
                        <span class="bg-secondary text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Techno</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-slate-500 text-sm mb-3">
                    <span class="flex items-center gap-1.5"><i class="fa-regular fa-calendar"></i> 08 Mars 2026</span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span>8 min de lecture</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-secondary transition-colors leading-tight">
                    L'IA générative : Pourquoi tout développeur doit s'y former
                </h4>
                <p class="text-slate-600 line-clamp-2 mb-4">
                    L'industrie du logiciel change radicalement. Voici comment rester pertinent face à l'émergence des outils d'IA.
                </p>
                <a href="#" class="inline-flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all">
                    Lire la suite <i class="fa-solid fa-arrow-right-long"></i>
                </a>
            </article>

            <!-- Article 3 -->
            <article class="group">
                <div class="relative overflow-hidden rounded-3xl mb-6 shadow-sm group-hover:shadow-xl transition-all duration-500">
                    <img src="https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Blog 3" class="w-full h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4">
                        <span class="bg-accent text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Pédagogie</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-slate-500 text-sm mb-3">
                    <span class="flex items-center gap-1.5"><i class="fa-regular fa-calendar"></i> 05 Mars 2026</span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    <span>4 min de lecture</span>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-accent transition-colors leading-tight">
                    Apprendre par la pratique : Les secrets de notre méthodologie
                </h4>
                <p class="text-slate-600 line-clamp-2 mb-4">
                    Pourquoi réaliser des projets réels est 10x plus efficace que de simples cours théoriques pour votre carrière.
                </p>
                <a href="#" class="inline-flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all">
                    Lire la suite <i class="fa-solid fa-arrow-right-long"></i>
                </a>
            </article>
        </div>
    </div>
</section>

<!-- Partners Logo Carousel -->
<section class="py-12 bg-slate-50 border-t border-slate-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-8">
        <p class="text-slate-400 font-semibold tracking-wider text-sm uppercase">Ils nous font confiance</p>
    </div>
    
    <!-- CSS Auto-scrolling Carousel -->
    <div class="relative w-full flex overflow-x-hidden">
        <div class="animate-marquee flex items-center whitespace-nowrap gap-16 py-4 px-8">
            <!-- Simulated Logos -->
            <i class="fa-brands fa-microsoft text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-google text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-amazon text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-apple text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-meta text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-stripe text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-spotify text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-paypal text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            
            <!-- Duplicated for seamless loop -->
            <i class="fa-brands fa-microsoft text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-google text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-amazon text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-apple text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-meta text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-stripe text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-spotify text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
            <i class="fa-brands fa-paypal text-4xl text-slate-400 hover:text-slate-600 transition-colors"></i>
        </div>
    </div>
</section>

@endsection

@section('styles')
<style>
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes bounceSlow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        animation: marquee 30s linear infinite;
        width: max-content;
    }
</style>
@endsection

@section('scripts')
<script>
    // Simple counter animation
    document.addEventListener("DOMContentLoaded", () => {
        const counters = document.querySelectorAll('.counter');
        const speed = 200; // Lower is faster

        const animateCounters = () => {
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const inc = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(animateCounters, 1);
                } else {
                    counter.innerText = target;
                }
            });
        };

        // Trigger animation when section is in view
        const observer = new IntersectionObserver((entries) => {
            if(entries[0].isIntersecting) {
                animateCounters();
                observer.disconnect();
            }
        });
        
        observer.observe(document.querySelector('.bg-primary'));
    });

    // FAQ Accordion Toggle
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const button = item.querySelector('button');
        const answer = item.querySelector('.faq-answer');
        const icon = item.querySelector('i');

        button.addEventListener('click', () => {
            const isOpen = item.classList.contains('active');
            
            // Close all other items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                    otherItem.querySelector('.faq-answer').style.maxHeight = null;
                    otherItem.querySelector('i').style.transform = 'rotate(0deg)';
                    otherItem.classList.remove('border-primary');
                    otherItem.classList.add('border-slate-200');
                }
            });

            // Toggle current item
            if (isOpen) {
                item.classList.remove('active');
                answer.style.maxHeight = null;
                icon.style.transform = 'rotate(0deg)';
                item.classList.remove('border-blue-500');
                item.classList.add('border-slate-200');
            } else {
                item.classList.add('active');
                answer.style.maxHeight = answer.scrollHeight + "px";
                icon.style.transform = 'rotate(180deg)';
                item.classList.add('border-primary');
                item.classList.remove('border-slate-200');
            }
        });
    });
</script>
@endsection
