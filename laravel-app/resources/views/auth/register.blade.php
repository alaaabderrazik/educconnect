@extends('layouts.public')

@section('title', 'Inscription - EduConnect')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-20 pt-32">
    <div class="max-w-xl w-full mx-auto p-8 bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
        
        <!-- Decorative blobs -->
        <div class="absolute -right-20 -top-20 w-48 h-48 bg-blue-100 rounded-full blur-3xl opacity-50 z-0"></div>
        <div class="absolute -left-20 -bottom-20 w-48 h-48 bg-emerald-100 rounded-full blur-3xl opacity-50 z-0"></div>

        <div class="relative z-10 text-center mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Rejoignez-nous 🚀</h2>
            <p class="text-slate-500">Créez votre compte et commencez votre apprentissage.</p>
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

        <form method="POST" action="{{ route('register.submit') }}" class="relative z-10 space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nom Complet</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none"
                            placeholder="John Doe">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">E-mail</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none"
                            placeholder="vous@exemple.com">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none"
                            placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Confirmer</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all outline-none"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>
            
            <!-- Default Registration sets role to Student automatically in AuthController -->

            <button type="submit" class="w-full flex justify-center py-3.5 px-4 mt-8 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Créer mon compte
            </button>
        </form>

        <p class="relative z-10 mt-8 text-center text-sm text-slate-500 font-medium">
            Vous avez déjà un compte ? 
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-bold ml-1 transition-colors">Se connecter</a>
        </p>
    </div>
</div>
@endsection
