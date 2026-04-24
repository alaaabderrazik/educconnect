@extends('layouts.dashboard')

@section('title', 'Gestion des Pages')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Contenu des Pages 📄</h1>
        <p class="text-slate-500 text-sm mt-1">Sélectionnez une page pour modifier son contenu.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach(['home' => 'Accueil', 'about' => 'À Propos', 'services' => 'Services', 'contact' => 'Contact'] as $key => $label)
        <a href="{{ route('dashboard.admin.pages.' . $key) }}" class="group bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:border-blue-500 hover:shadow-xl hover:shadow-blue-500/10 transition-all text-center">
            <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                @if($key == 'home') <i class="fa-solid fa-house"></i>
                @elseif($key == 'about') <i class="fa-solid fa-circle-info"></i>
                @elseif($key == 'services') <i class="fa-solid fa-briefcase"></i>
                @elseif($key == 'contact') <i class="fa-solid fa-envelope"></i>
                @endif
            </div>
            <h3 class="text-xl font-black text-slate-900 mb-2">{{ $label }}</h3>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Modifier le contenu</p>
        </a>
        @endforeach
    </div>
@endsection
