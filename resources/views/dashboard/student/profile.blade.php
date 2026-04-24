@extends('layouts.dashboard')

@section('title', 'Mon Profil')
@section('user_role', 'Étudiant')

@section('sidebar_menu')
    @include('dashboard.student.sidebar')
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Profile Sidebar Card -->
        <div class="lg:col-span-1 border border-slate-100 italic">
             <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 text-center not-italic">
                <div class="relative inline-block mb-6">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff&size=128" class="w-32 h-32 rounded-3xl shadow-lg border-4 border-white">
                    <button class="absolute -bottom-2 -right-2 w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center border-4 border-white hover:bg-blue-700 transition-all">
                        <i class="fa-solid fa-camera text-sm"></i>
                    </button>
                </div>
                <h2 class="text-2xl font-black text-slate-900">{{ auth()->user()->name }}</h2>
                <p class="text-blue-600 font-bold text-sm tracking-widest uppercase mt-1">Étudiant — Master 2</p>
                
                <div class="mt-8 space-y-3">
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                        <span class="text-xs text-slate-500 font-bold uppercase">Cours Suivis</span>
                        <span class="font-black text-slate-900">12</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                        <span class="text-xs text-slate-500 font-bold uppercase">Moyenne</span>
                        <span class="font-black text-slate-900">14.5/20</span>
                    </div>
                </div>
             </div>
        </div>

        <!-- Settings Form -->
        <div class="lg:col-span-2 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 flex items-center gap-3 font-bold text-sm">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-id-card text-blue-500"></i> Informations Personnelles
                </h3>
                
                <form method="POST" action="{{ route('dashboard.student.profile.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Nom complet</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Email académique</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-sm">
                    </div>
                    
                    <div class="md:col-span-2 pt-4 border-t border-slate-100 mt-4">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-3">
                            <i class="fa-solid fa-lock text-rose-500"></i> Changement de mot de passe (Optionnel)
                        </h3>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Nouveau mot de passe</label>
                        <input type="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 transition-all font-medium text-sm">
                    </div>

                    <div class="md:col-span-2 pt-4">
                        <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
