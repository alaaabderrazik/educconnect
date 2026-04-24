@extends('layouts.public')

@section('title', 'Connexion - EduConnect')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-20">
    <div class="max-w-md w-full mx-auto p-8 bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
        
        <!-- Decorative blobs -->
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-100 rounded-full blur-3xl opacity-50 z-0"></div>
        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-emerald-100 rounded-full blur-3xl opacity-50 z-0"></div>

        <div class="relative z-10 text-center mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Bon retour 👋</h2>
            <p class="text-slate-500">Connectez-vous pour accéder à votre espace.</p>
        </div>

        @if ($errors->any())
            <div class="relative z-10 mb-6 bg-rose-50 text-rose-600 text-sm p-4 rounded-xl border border-rose-100">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="relative z-10 space-y-5">
            @csrf
            
            <div>
                <label for="login_identity" class="block text-sm font-bold text-slate-700 mb-2">Email ou Nom d'utilisateur</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-user-circle"></i>
                    </div>
                    <input type="text" name="login_identity" id="login_identity" value="{{ old('login_identity') }}" required autofocus
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none"
                        placeholder="votre@email.com ou username">
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-sm font-bold text-slate-700">Mot de passe</label>
                    <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">Mot de passe oublié ?</a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input type="password" name="password" id="password" required
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500">
                <label for="remember" class="ml-2 block text-sm text-slate-600 font-medium">Se souvenir de moi</label>
            </div>

            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Se connecter
            </button>
        </form>

        <p class="relative z-10 mt-8 text-center text-sm text-slate-500 font-medium">
            Pas encore de compte ? 
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-bold ml-1 transition-colors">S'inscrire</a>
        </p>

        <!-- Mock Login Helper -->
         <div class="mt-8 pt-6 border-t border-slate-100 relative z-10">
            <p class="text-xs text-slate-400 font-semibold mb-3 text-center uppercase tracking-wider">Identifiants de Demo</p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <button type="button" onclick="document.getElementById('login_identity').value='admin@educonnect.com'; document.getElementById('password').value='password';" class="p-2 bg-slate-50 hover:bg-slate-100 rounded-lg text-slate-600 font-medium border border-slate-200 transition-colors">Admin</button>
                <button type="button" onclick="document.getElementById('login_identity').value='professor@educonnect.com'; document.getElementById('password').value='password';" class="p-2 bg-slate-50 hover:bg-slate-100 rounded-lg text-slate-600 font-medium border border-slate-200 transition-colors">Professeur</button>
                <button type="button" onclick="document.getElementById('login_identity').value='student@educonnect.com'; document.getElementById('password').value='password';" class="p-2 bg-slate-50 hover:bg-slate-100 rounded-lg text-slate-600 font-medium border border-slate-200 transition-colors col-span-2">Étudiant</button>
            </div>
         </div>
    </div>
</div>
@endsection
