@extends('layouts.dashboard')

@section('title', 'Mes Cours')
@section('user_role', 'Étudiante')

@section('sidebar_menu')
    @include('dashboard.student.sidebar')
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mes Cours 📚</h1>
        <p class="text-slate-500 text-sm mt-1">Gérez vos modules et suivez votre progression académique.</p>
    </div>

    <!-- Course Filter Tabs -->
    <div class="flex gap-4 mb-8 border-b border-slate-200">
        <button class="px-4 py-2 text-sm font-bold border-b-2 border-blue-600 text-blue-600">En cours</button>
        <button class="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">Terminés</button>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($subjects as $subject)
            @php
                $professor = $subject->professor; // One-to-one relationship
                $colors = ['blue', 'emerald', 'amber', 'rose', 'indigo', 'purple'];
                $color = $colors[$loop->index % count($colors)];
            @endphp
            <!-- Course Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-32 bg-{{ $color }}-600 relative flex items-center justify-center overflow-hidden">
                    <i class="fa-solid fa-graduation-cap text-6xl text-white/20 absolute -right-4 -bottom-4"></i>
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-3xl border border-white/20">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-slate-900 text-lg">{{ $subject->subject_name }}</h3>
                            <p class="text-sm text-slate-500">{{ $professor->name ?? 'Professeur non assigné' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                            <i class="fa-solid fa-building-columns text-{{ $color }}-500"></i>
                            <span>Module Académique</span>
                        </div>
                        
                        <div class="w-full">
                            <div class="flex justify-between text-xs mb-1.5 font-bold">
                                <span class="text-slate-500 uppercase tracking-wider">État</span>
                                <span class="text-{{ $color }}-600">En cours</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-{{ $color }}-600 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex gap-2">
                        <button disabled class="flex-1 py-2.5 bg-slate-900/50 text-white rounded-xl font-bold text-sm cursor-not-allowed">
                            Accéder
                        </button>
                        <button disabled class="px-3 py-2.5 bg-slate-100 text-slate-400 rounded-xl cursor-not-allowed" title="Contenu bientôt disponible">
                            <i class="fa-solid fa-lock"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center text-slate-400 italic bg-white rounded-3xl border border-dashed border-slate-200">
                Aucun cours inscrit pour votre classe.
            </div>
        @endforelse
    </div>
@endsection
