@extends('layouts.public')

@section('title', 'Contactez-nous | EduConnect')

@section('content')

<!-- Page Header -->
<section class="bg-slate-900 py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1596524430615-b46475ddff6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-center opacity-20"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-transparent"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-fade-in-up">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">{{ $page['header']->title ?? 'Contactez-Nous' }}</h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto">{{ $page['header']->content ?? 'Une question sur nos formations ? Besoin d\'aide pour votre inscription ? Notre équipe est à votre écoute.' }}</p>
    </div>
</section>

<section class="py-24 bg-slate-50 relative">
    
    <!-- Decorative background blobs -->
    <div class="absolute top-40 left-0 w-72 h-72 rounded-full mix-blend-multiply filter blur-3xl opacity-10" style="background-color: var(--primary-color)"></div>
    <div class="absolute bottom-40 right-0 w-72 h-72 rounded-full mix-blend-multiply filter blur-3xl opacity-10" style="background-color: var(--secondary-color)"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Contact Info Sidebar -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <h3 class="text-2xl font-bold text-slate-900 mb-6">Informations</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary-light text-primary rounded-full flex items-center justify-center flex-shrink-0 text-xl">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Notre Campus</h4>
                                <p class="text-slate-600 mt-1">{!! nl2br(e($settings['contact_address'] ?? '123 Innovation Drive, Tech City')) !!}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-secondary-light text-secondary rounded-full flex items-center justify-center flex-shrink-0 text-xl">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Téléphone</h4>
                                <p class="text-slate-600 mt-1">{{ $settings['contact_phone'] ?? '+33 (0)1 23 45 67 89' }}</p>
                                <p class="text-slate-500 text-sm mt-1">Lun-Ven: 9h00 - 18h00</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-accent-light text-accent rounded-full flex items-center justify-center flex-shrink-0 text-xl">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Email</h4>
                                <a href="mailto:{{ $settings['contact_email'] ?? 'contact@educonnect.com' }}" class="text-primary hover:text-primary-dark transition-colors mt-1 block">{{ $settings['contact_email'] ?? 'contact@educonnect.com' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="p-8 rounded-3xl shadow-lg text-white animate-fade-in-up" style="background: linear-gradient(to bottom right, var(--primary-color), var(--secondary-color)); animation-delay: 0.2s;">
                    <h3 class="text-xl font-bold mb-4">Rejoignez la communauté</h3>
                    <p class="text-blue-100 mb-6">Suivez-nous sur les réseaux pour ne rien manquer de nos actualités.</p>
                    <div class="flex space-x-4">
                        @if($settings['linkedin_url'] ?? null)<a href="{{ $settings['linkedin_url'] }}" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all hover:-translate-y-1"><i class="fa-brands fa-linkedin-in"></i></a>@endif
                        @if($settings['twitter_url'] ?? null)<a href="{{ $settings['twitter_url'] }}" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all hover:-translate-y-1"><i class="fa-brands fa-twitter"></i></a>@endif
                        @if($settings['instagram_url'] ?? null)<a href="{{ $settings['instagram_url'] }}" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all hover:-translate-y-1"><i class="fa-brands fa-instagram"></i></a>@endif
                        @if($settings['facebook_url'] ?? null)<a href="{{ $settings['facebook_url'] }}" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center backdrop-blur-sm transition-all hover:-translate-y-1"><i class="fa-brands fa-facebook-f"></i></a>@endif
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="lg:col-span-8 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="bg-white p-8 md:p-12 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100">
                    <div class="mb-8">
                        <h2 class="text-3xl font-extrabold text-slate-900">Envoyez-nous un message</h2>
                        <p class="text-slate-600 mt-2">Remplissez le formulaire ci-dessous et nous vous répondrons dans les plus brefs délais.</p>
                    </div>
                    
                    <!-- Success Message Mockup (Hidden by default) -->
                    <div id="success-alert" class="hidden mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-4 rounded-xl items-start gap-4">
                        <div class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold">Message envoyé avec succès !</h4>
                            <p class="text-emerald-700 text-sm mt-1">Merci de nous avoir contactés. Un membre de notre équipe vous répondra sous 24h ouvrées.</p>
                        </div>
                        <button onclick="document.getElementById('success-alert').classList.add('hidden')" class="ml-auto text-emerald-500 hover:text-emerald-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <!-- Form -->
                    <form id="contact-form" onsubmit="event.preventDefault(); showSuccessMessage();" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="space-y-2">
                                <label for="name" class="block font-medium text-slate-700 text-sm">Nom complet <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-regular fa-user"></i>
                                    </div>
                                    <input type="text" id="name" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" placeholder="Jean Dupont">
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="block font-medium text-slate-700 text-sm">Adresse email <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-regular fa-envelope"></i>
                                    </div>
                                    <input type="email" id="email" required class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" placeholder="jean@exemple.com">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subject -->
                        <div class="space-y-2">
                            <label for="subject" class="block font-medium text-slate-700 text-sm">Sujet <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="subject" required class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all appearance-none">
                                    <option value="" disabled selected>Sélectionnez un sujet</option>
                                    <option value="admission">Information sur les admissions</option>
                                    <option value="courses">Question sur une formation</option>
                                    <option value="technical">Problème technique (Dashboard)</option>
                                    <option value="other">Autre demande</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Message -->
                        <div class="space-y-2">
                            <label for="message" class="block font-medium text-slate-700 text-sm">Votre message <span class="text-red-500">*</span></label>
                            <textarea id="message" rows="5" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all resize-y" placeholder="Comment pouvons-nous vous aider ?"></textarea>
                        </div>
                        
                        <div class="pt-2">
                            <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-primary hover-bg-primary text-white font-bold rounded-xl shadow-lg hover-shadow-primary transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <span>Envoyer le message</span>
                                <i class="fa-regular fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Google Maps Integration -->
<section class="h-[500px] w-full relative animate-fade-in-up" style="animation-delay: 0.4s;">
    <!-- Utilisation d'un iframe Google Maps stylisé ou carte standard -->
    <iframe 
        src="{{ $settings['google_maps_link'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10207.2471!2d2.3488!3d48.8534!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1f06e2b70f%3A0x40b82c3688c9460!2sParis%2C%20France!5e0!3m2!1sen!2sus!4v1625680193892!5m2!1sen!2sus' }}" 
        class="absolute inset-0 w-full h-full border-0" 
        allowfullscreen="" 
        loading="lazy">
    </iframe>
    
    <!-- Floating overlay on map for pure aesthetics -->
    <div class="absolute inset-0 pointer-events-none shadow-[inset_0_0_40px_rgba(0,0,0,0.1)]"></div>
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
</style>
@endsection

@section('scripts')
<script>
    function showSuccessMessage() {
        const alert = document.getElementById('success-alert');
        const form = document.getElementById('contact-form');
        
        // Hide form, show alert (or just show alert)
        alert.classList.remove('hidden');
        alert.classList.add('flex');
        
        // Reset form
        form.reset();
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            alert.classList.add('hidden');
            alert.classList.remove('flex');
        }, 5000);
    }
</script>
@endsection
