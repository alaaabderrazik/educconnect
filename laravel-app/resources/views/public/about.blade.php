@extends('layouts.public')

@section('title', 'About Us | EduConnect')

@section('content')

<!-- Page Header -->
<section class="bg-slate-900 py-20 relative overflow-hidden">
<div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url('{{ isset($page['header']) && $page['header']->image ? asset('storage/'.$page['header']->image) : 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80' }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-transparent"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 animate-fade-in-up">{{ $page['header']->title ?? 'À Propos de Nous' }}</h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto animate-fade-in-up" style="animation-delay: 0.1s;">{{ $page['header']->content ?? 'Découvrez notre histoire, notre vision et l\'équipe passionnée qui se cache derrière EduConnect.' }}</p>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative animate-fade-in-up" style="animation-delay: 0.2s;">
                <!-- Decorative background blob -->
                <div class="absolute -top-10 -left-10 w-72 h-72 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob" style="background-color: color-mix(in srgb, var(--primary-color), transparent 80%);"></div>
                <div class="absolute -bottom-10 -right-10 w-72 h-72 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000" style="background-color: color-mix(in srgb, var(--secondary-color), transparent 80%);"></div>
                
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-100">
                    <img src="{{ isset($page['story']) && $page['story']->image ? asset('storage/'.$page['story']->image) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80' }}" alt="Notre histoire" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-700">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/80 to-transparent p-8">
                        <div class="text-white font-bold text-2xl">Depuis 2010</div>
                    </div>
                </div>
            </div>
            
            <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
                <h2 class="text-primary font-bold tracking-wide uppercase text-sm mb-2">Notre Histoire</h2>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">{{ $page['story']->title ?? 'Former les leaders de demain' }}</h3>
                
                <div class="space-y-6 text-slate-600 text-lg leading-relaxed">
                    <p>{{ $page['story']->content ?? 'Fondée en 2010, EduConnect est née d\'une idée simple : l\'éducation de qualité doit être accessible.' }}</p>
                </div>
                
                <div class="mt-8 flex gap-6">
                    <div class="border-l-4 border-primary pl-4">
                        <div class="text-3xl font-black text-slate-900">15+</div>
                        <div class="text-slate-500 font-medium text-sm uppercase">Années d'expérience</div>
                    </div>
                    <div class="border-l-4 border-secondary pl-4">
                        <div class="text-3xl font-black text-slate-900">40+</div>
                        <div class="text-slate-500 font-medium text-sm uppercase">Partenaires Entreprises</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision Cards -->
<section class="py-24 bg-slate-50 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-primary font-bold tracking-wide uppercase text-sm mb-2">Notre ADN</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900">Mission, Vision & Valeurs</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Mission -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-shadow group">
                <div class="w-14 h-14 bg-primary-light text-primary rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                    <i class="fa-solid fa-rocket"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-4">{{ $page['mission']->title ?? 'Notre Mission' }}</h4>
                <p class="text-slate-600">{{ $page['mission']->content ?? 'Démocratiser l\'accès à une éducation d\'excellence et doter nos étudiants des compétences clés pour une réussite professionnelle pérenne.' }}</p>
            </div>
            
            <!-- Vision -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-shadow group">
                <div class="w-14 h-14 bg-secondary-light text-secondary rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:bg-secondary group-hover:text-white transition-colors">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-4">{{ $page['vision']->title ?? 'Notre Vision' }}</h4>
                <p class="text-slate-600">{{ $page['vision']->content ?? 'Devenir le pont principal entre l\'apprentissage académique et le monde de l\'entreprise, en bâtissant une communauté mondiale de talents.' }}</p>
            </div>
            
            <!-- Valeurs -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-shadow group">
                <div class="w-14 h-14 bg-accent-light text-accent rounded-xl flex items-center justify-center text-2xl mb-6 group-hover:bg-accent group-hover:text-white transition-colors">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-4">{{ $page['values']->title ?? 'Nos Valeurs' }}</h4>
                <p class="text-slate-600">{{ $page['values']->content ?? 'L\'excellence au quotidien, l\'intégrité dans nos actions, l\'innovation pédagogique et le respect de la diversité de nos apprenants.' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-primary font-bold tracking-wide uppercase text-sm mb-2">Notre Équipe</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">Rencontrez nos experts</h3>
            <p class="text-slate-600 text-lg">Une équipe pédagogique et administrative dédiée à la réussite de chaque étudiant.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 text-center">
            @foreach($team as $member)
            <div class="group">
                <div class="relative w-48 h-48 mx-auto mb-6 rounded-full p-2 bg-gradient-to-tr overflow-hidden transform group-hover:-translate-y-2 transition-transform duration-300" style="background-image: linear-gradient(to top right, var(--primary-color), var(--secondary-color));">
                    <img src="{{ $member->photo_path ?? 'https://ui-avatars.com/api/?name='.urlencode($member->name) }}" alt="{{ $member->name }}" class="w-full h-full object-cover rounded-full border-4 border-white">
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-1">{{ $member->name }}</h4>
                <p class="text-primary font-medium text-sm mb-3">{{ $member->position }}</p>
                <div class="flex justify-center space-x-3 text-slate-400">
                    @if($member->linkedin_url)<a href="{{ $member->linkedin_url }}" class="hover-text-primary transition-colors"><i class="fa-brands fa-linkedin"></i></a>@endif
                    @if($member->twitter_url)<a href="{{ $member->twitter_url }}" class="hover-text-primary transition-colors"><i class="fa-brands fa-twitter"></i></a>@endif
                </div>
            </div>
            @endforeach
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
    
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
@endsection
