@extends('layouts.public')

@section('title', 'Nos Formations | EduConnect')

@section('content')

<!-- Page Header -->
<section class="bg-primary py-16 relative overflow-hidden">
    <!-- Decorative abstract elements -->
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 rounded-full blur-2xl" style="background-color: color-mix(in srgb, var(--primary-color), black 40%); opacity: 0.2;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-fade-in-up">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">{{ $page['header']->title ?? 'Nos Formations' }}</h1>
        <p class="text-xl text-white opacity-90 max-w-2xl mx-auto">{{ $page['header']->content ?? 'Explorez notre catalogue de formations intensives et trouvez celle qui propulsera votre carrière.' }}</p>
    </div>
</section>

<!-- Filter & Search Section -->
<section class="py-8 bg-white border-b border-slate-200 sticky top-20 z-40 shadow-sm animate-fade-in-up" style="animation-delay: 0.1s;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            
            <!-- Category Filters -->
            <div class="flex space-x-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 scrollbar-hide py-1">
                <button class="px-5 py-2 rounded-full bg-primary text-white font-medium text-sm whitespace-nowrap shadow-md hover-shadow-primary transition-all">Tous les domaines</button>
                <button class="px-5 py-2 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 font-medium text-sm whitespace-nowrap transition-all border border-slate-200">Développement Web</button>
                <button class="px-5 py-2 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 font-medium text-sm whitespace-nowrap transition-all border border-slate-200">Data Science</button>
                <button class="px-5 py-2 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 font-medium text-sm whitespace-nowrap transition-all border border-slate-200">Business</button>
                <button class="px-5 py-2 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 font-medium text-sm whitespace-nowrap transition-all border border-slate-200">Design UI/UX</button>
            </div>
            
            <!-- Level Filter / Select -->
            <div class="w-full md:w-48 flex-shrink-0">
                <select class="w-full bg-slate-50 border border-slate-200 text-slate-700 rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-shadow">
                    <option value="all">Tous les niveaux</option>
                    <option value="beginner">Débutant</option>
                    <option value="intermediate">Intermédiaire</option>
                    <option value="advanced">Avancé</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Courses Grid -->
<section class="py-16 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($services as $service)
            <!-- Course Card -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover-shadow-primary transition-all duration-500 group flex flex-col animate-fade-in-up">
                @if($service->image)
                <div class="h-48 overflow-hidden relative">
                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute top-4 left-4 w-10 h-10 rounded-xl bg-white/90 backdrop-blur-sm text-primary flex items-center justify-center text-xl shadow-lg">
                        <i class="fa-solid {{ $service->icon }}"></i>
                    </div>
                </div>
                @else
                <div class="p-8 pb-0">
                    <div class="w-16 h-16 rounded-2xl bg-primary-light text-primary flex items-center justify-center text-3xl group-hover:bg-primary group-hover:text-white group-hover:scale-110 transition-all duration-500">
                        <i class="fa-solid {{ $service->icon }}"></i>
                    </div>
                </div>
                @endif
                
                <div class="p-8 flex-grow flex flex-col">
                    <h4 class="text-xl font-bold text-slate-900 mb-4 group-hover:text-primary transition-colors leading-tight">{{ $service->title }}</h4>
                    <p class="text-slate-600 text-sm mb-6 flex-grow leading-relaxed line-clamp-3">{{ $service->description }}</p>
                    
                    @if($service->professor_name)
                    <div class="flex items-center gap-2 mb-6 text-xs text-slate-400 font-medium">
                        <i class="fa-solid fa-chalkboard-user text-secondary"></i>
                        <span>Par {{ $service->professor_name }}</span>
                    </div>
                    @endif
                    
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                            {{ $service->related_course ?? 'Formation Accréditée' }}
                        </span>
                        <a href="{{ route('public.contact') }}" class="text-primary font-bold text-sm hover-text-primary transition-colors flex items-center gap-1">
                            S'inscrire <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <p class="text-slate-500 font-medium">Aucune formation disponible pour le moment.</p>
            </div>
            @endforelse
        </div>
        
    </div>
</section>

<!-- Comment ça fonctionne -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-primary font-bold tracking-wide uppercase text-sm mb-2">Méthodologie</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900">Comment ça fonctionne ?</h3>
        </div>
        
        <div class="relative max-w-4xl mx-auto">
            <!-- Connecting line for desktop -->
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 -translate-y-1/2 z-0" style="background: linear-gradient(to right, color-mix(in srgb, var(--primary-color), white 80%), color-mix(in srgb, var(--secondary-color), white 80%), color-mix(in srgb, var(--primary-color), white 80%));"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative z-10">
                
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto bg-white rounded-full border-4 border-primary-light flex items-center justify-center text-2xl text-primary shadow-xl mb-6 relative hover:scale-110 transition-transform duration-300">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-slate-900 text-white text-sm font-bold flex items-center justify-center border-2 border-white">1</span>
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Choisissez votre voie</h4>
                    <p class="text-slate-600 text-sm">Parcourez notre catalogue et sélectionnez la formation qui correspond à vos aspirations.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto bg-white rounded-full border-4 border-secondary-light flex items-center justify-center text-2xl text-secondary shadow-xl mb-6 relative hover:scale-110 transition-transform duration-300">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-slate-900 text-white text-sm font-bold flex items-center justify-center border-2 border-white">2</span>
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Apprenez par la pratique</h4>
                    <p class="text-slate-600 text-sm">Suivez les cours en ligne, participez aux sessions live et réalisez des projets concrets.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto bg-white rounded-full border-4 border-accent-light flex items-center justify-center text-2xl text-accent shadow-xl mb-6 relative hover:scale-110 transition-transform duration-300">
                        <span class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-slate-900 text-white text-sm font-bold flex items-center justify-center border-2 border-white">3</span>
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-3">Certifiez-vous</h4>
                    <p class="text-slate-600 text-sm">Validez vos acquis et obtenez une certification reconnue pour propulser votre carrière.</p>
                </div>
                
            </div>
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
        animation: fade-in-up 0.6s ease-out forwards;
        opacity: 0;
    }
    
    /* Hide scrollbar for category filters but keep functionality */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
