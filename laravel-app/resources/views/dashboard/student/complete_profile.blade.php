@extends('layouts.dashboard')

@section('title', 'Compléter votre Profil')
@section('user_role', 'Étudiant')

@section('sidebar_menu')
    <div class="px-6 py-4">
        <div class="bg-blue-600/10 rounded-2xl p-4 border border-blue-600/20">
            <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-2">Progression</h4>
            <div class="w-full bg-slate-200 rounded-full h-2 mb-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-1000" style="width: {{ $progress }}%"></div>
            </div>
            <p class="text-[10px] font-bold text-slate-500">{{ round($progress) }}% complété</p>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden animate-fade-in">
        <div class="p-8 md:p-12 border-b border-slate-50 bg-gradient-to-br from-blue-600 to-indigo-700 text-white relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-400/20 rounded-full -ml-24 -mb-24 blur-2xl"></div>
            
            <div class="relative z-10">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-xl border border-white/30">
                    👋
                </div>
                <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">Bienvenue, {{ $user->first_name }} !</h1>
                <p class="text-blue-100 text-lg font-medium max-w-xl leading-relaxed">Veuillez compléter vos informations de profil pour accéder à votre tableau de bord complet.</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            @if(session('warning'))
            <div class="mb-8 p-4 bg-amber-50 border border-amber-100 text-amber-700 rounded-2xl flex items-start gap-4 animate-shake">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h4 class="font-black text-sm uppercase tracking-wider mb-1">Action Requise</h4>
                    <p class="text-xs font-medium leading-relaxed">{{ session('warning') }}</p>
                </div>
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-8 p-6 bg-rose-50 border border-rose-100 rounded-2xl">
                <div class="flex items-center gap-3 text-rose-600 mb-4">
                    <i class="fa-solid fa-circle-xmark text-xl"></i>
                    <h4 class="font-black text-sm uppercase tracking-wider">Erreurs de validation</h4>
                </div>
                <ul class="space-y-2">
                    @foreach ($errors->all() as $error)
                        <li class="text-xs text-rose-500 font-bold flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('student.complete_profile.update') }}" method="POST" class="space-y-10">
                @csrf
                
                <!-- Section: Contact -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Coordonnées & Contact</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Adresse Email</label>
                            <div class="relative group">
                                <i class="fa-solid fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                       class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/20 font-bold text-slate-700 transition-all"
                                       placeholder="votre@email.com">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Téléphone</label>
                            <div class="relative group">
                                <i class="fa-solid fa-phone absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required 
                                       class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/20 font-bold text-slate-700 transition-all"
                                       placeholder="06 XX XX XX XX">
                            </div>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Adresse Résidentielle</label>
                            <div class="relative group">
                                <i class="fa-solid fa-location-dot absolute left-5 top-5 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                                <textarea name="address" required rows="3" 
                                          class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/20 font-bold text-slate-700 transition-all resize-none"
                                          placeholder="Votre adresse complète...">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Informations Personnelles -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-1.5 h-8 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Détails Personnels</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Date de Naissance</label>
                            <div class="relative group">
                                <i class="fa-solid fa-calendar absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                <input type="date" name="dob" value="{{ old('dob', $user->dob) }}" required 
                                       class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/20 font-bold text-slate-700 transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Sexe</label>
                            <div class="relative group">
                                <i class="fa-solid fa-venus-mars absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                                <select name="gender" required 
                                        class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/20 font-bold text-slate-700 transition-all appearance-none">
                                    <option value="M" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('gender', $user->gender) == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Sécurité -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-1.5 h-8 bg-rose-600 rounded-full"></div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Sécurité du Compte</h3>
                    </div>
                    
                    <div class="bg-rose-50/50 p-6 rounded-3xl border border-rose-100 mb-6">
                        <div class="flex items-start gap-4 text-rose-600">
                            <i class="fa-solid fa-shield-halved text-xl mt-1"></i>
                            <p class="text-xs font-bold leading-relaxed">Pour votre sécurité, vous devez changer votre mot de passe temporaire avant de pouvoir accéder à votre espace personnel.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nouveau Mot de Passe</label>
                            <div class="relative group">
                                <i class="fa-solid fa-key absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-rose-500 transition-colors"></i>
                                <input type="password" name="password" required 
                                       class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/20 font-bold text-slate-700 transition-all"
                                       placeholder="••••••••">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Confirmer le Mot de Passe</label>
                            <div class="relative group">
                                <i class="fa-solid fa-check-double absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-rose-500 transition-colors"></i>
                                <input type="password" name="password_confirmation" required 
                                       class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/20 font-bold text-slate-700 transition-all"
                                       placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8">
                    <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-lg tracking-tight transition-all shadow-2xl shadow-blue-500/30 flex items-center justify-center gap-3 group">
                        <span>Finaliser mon inscription</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    <p class="text-center text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-6">
                        <i class="fa-solid fa-lock mr-1"></i> Connexion Sécurisée • EduConnect v2.0
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake {
        animation: shake 0.4s ease-in-out infinite alternate;
        animation-iteration-count: 2;
    }
</style>
@endsection
