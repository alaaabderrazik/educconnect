@extends('layouts.public')

@section('title', 'Compléter votre profil - EduConnect')

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        
        <!-- Header with Progress -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-8 text-white relative">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fa-solid fa-chalkboard-user text-8xl"></i>
            </div>
            <div class="relative z-10">
                <h1 class="text-3xl font-extrabold mb-2">Bienvenue, Pr. {{ $user->last_name }} !</h1>
                <p class="text-indigo-100 mb-6">Veuillez compléter vos informations professionnelles pour activer votre compte.</p>
                
                <!-- Progress Bar -->
                <div class="space-y-2">
                    <div class="flex justify-between text-sm font-bold">
                        <span>Progression du profil</span>
                        <span>{{ round($progress) }}%</span>
                    </div>
                    <div class="w-full bg-white/20 rounded-full h-3 backdrop-blur-sm">
                        <div class="bg-white h-3 rounded-full transition-all duration-1000 ease-out shadow-[0_0_15px_rgba(255,255,255,0.5)]" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8">
            @if ($errors->any())
                <div class="mb-8 bg-rose-50 text-rose-600 text-sm p-4 rounded-2xl border border-rose-100 animate-pulse">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="font-medium">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('dashboard.professor.complete_profile.submit') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Section 1: Coordonnées -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-sm">1</span>
                        Informations de contact
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Adresse E-mail professionnelle</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all outline-none"
                                placeholder="votre.nom@educonnect.com">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Numéro de Téléphone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all outline-none"
                                placeholder="+221 .. ... .. ..">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Profil Professionnel -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-sm">2</span>
                        Profil & Bio
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Adresse Résidentielle</label>
                            <textarea name="address" rows="2" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all outline-none resize-none"
                                placeholder="Votre adresse complète...">{{ old('address', $user->address) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Bio Professionnelle</label>
                            <textarea name="bio" rows="4" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all outline-none resize-none"
                                placeholder="Parlez-nous de votre expérience, vos diplômes et vos passions pédagogiques...">{{ old('bio', optional($user->professorDetails)->bio) }}</textarea>
                            <p class="text-xs text-slate-400 mt-2 italic">Cette bio sera visible par vos étudiants et collègues.</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Sécurité -->
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-sm">3</span>
                        Sécurisation du compte
                    </h3>
                    <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 mb-6 flex items-start gap-3">
                        <i class="fa-solid fa-shield-halved text-blue-600 mt-1"></i>
                        <p class="text-xs text-blue-700 leading-relaxed font-medium">Pour des raisons de sécurité, vous devez changer le mot de passe temporaire généré par l'administration.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nouveau mot de passe</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all outline-none"
                                placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent transition-all outline-none"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-4 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 transition-all hover:-translate-y-1 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-check-double"></i>
                        Finaliser mon activation
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-6">
                        En finalisant, vous acceptez les conditions d'utilisation d'EduConnect.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
