@extends('layouts.dashboard')
@section('title', 'Mes Classes & Groupes')
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mes Classes & Groupes 🏫</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez vos classes officielles et vos groupes personnalisés.</p>
        </div>
        <button onclick="document.getElementById('add_class_modal').classList.remove('hidden')" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-plus-circle"></i>
            Créer un Groupe
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-100 flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Personal Courses/Groups -->
    <div class="mb-12">
        <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-folder-tree text-indigo-500"></i>
            Mes Groupes Personnalisés
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($myCourses as $course)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow relative">
                <div class="p-6 bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-2 py-0.5 bg-white/20 rounded text-[10px] font-black uppercase tracking-wider border border-white/30 truncate max-w-[100px]">{{ $course->level }}</span>
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center border border-white/20">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-black mb-1 line-clamp-1">{{ $course->title }}</h3>
                    <p class="text-indigo-100/80 text-xs line-clamp-2 min-h-[32px]">{{ $course->description }}</p>
                </div>
                <div class="p-6 border-t border-slate-50">
                    <div class="flex justify-between items-center text-sm font-bold text-slate-600 mb-4">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-users text-slate-400"></i> {{ $course->enrollments_count }} Étudiants</span>
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-clock text-slate-400"></i> {{ $course->duration ?? '--' }}</span>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button class="flex-1 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-xl text-xs font-black transition-all">
                            Gérer les membres
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl text-center">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-slate-300 text-2xl mx-auto mb-4 border border-slate-100 shadow-sm">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <p class="text-slate-500 font-bold mb-1">Aucun groupe personnalisé</p>
                <p class="text-slate-400 text-xs px-8">Créez vos propres groupes pour organiser vos étudiants selon vos besoins.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Official Assigned Classes -->
    <div>
        <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-graduation-cap text-blue-500"></i>
            Classes Officielles (Admin)
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($assignedClasses as $class)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6 border-b border-slate-100 bg-slate-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-black text-slate-900">{{ $class->class_name }}</h3>
                            <p class="text-slate-500 text-xs font-bold mt-0.5 uppercase tracking-wider">{{ $class->academicLevel->level_name ?? 'Niveau N/A' }}</p>
                        </div>
                        <div class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-xl text-xs font-black">
                            <i class="fa-solid fa-users-rectangle mr-1"></i> {{ $class->student_details_count }}
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($class->studentDetails->take(3) as $detail)
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($detail->user->name ?? 'E') }}&background=eef2ff&color=4f46e5&size=32" class="w-8 h-8 rounded-lg border border-slate-100 shadow-sm">
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $detail->user->first_name ?? '' }} {{ $detail->user->last_name ?? '' }}</p>
                                <p class="text-[10px] text-slate-400 font-medium tracking-tight">{{ $detail->student_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="pt-6 flex gap-2 border-t border-slate-50 mt-6">
                        <a href="{{ route('dashboard.professor.students', ['class_id' => $class->id]) }}" class="flex-1 py-2.5 bg-blue-50 text-blue-600 rounded-xl text-xs font-black text-center hover:bg-blue-100 transition-all">
                            Étudiants
                        </a>
                        <a href="{{ route('dashboard.professor.attendance', ['class_id' => $class->id]) }}" class="flex-1 py-2.5 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-black text-center hover:bg-emerald-100 transition-all">
                            Présence
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-8 text-center text-slate-400 italic">
                Aucune classe officielle ne vous a été assignée.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Add Class Modal -->
    <div id="add_class_modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden transform transition-all border border-slate-100">
                <div class="bg-indigo-600 p-6 text-white flex justify-between items-center">
                    <h3 class="text-xl font-black">Créer un nouveau Groupe</h3>
                    <button onclick="document.getElementById('add_class_modal').classList.add('hidden')" class="hover:rotate-90 transition-transform">
                        <i class="fa-solid fa-xmark text-2xl"></i>
                    </button>
                </div>
                
                <form action="{{ route('dashboard.professor.classes.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nom du Groupe / Classe</label>
                        <input type="text" name="title" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="Ex: Math Avancé - Groupe A">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Niveau</label>
                            <select name="level" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all">
                                <option value="debutant">Débutant</option>
                                <option value="intermediaire">Intermédiaire</option>
                                <option value="avance">Avancé</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Durée (Optionnel)</label>
                            <input type="text" name="duration" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all" placeholder="Ex: 3 mois, 1 an...">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                        <textarea name="description" rows="4" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 transition-all resize-none" placeholder="Décrivez l'objectif de ce groupe..."></textarea>
                    </div>
                    
                    <div class="pt-4 flex gap-4">
                        <button type="button" onclick="document.getElementById('add_class_modal').classList.add('hidden')" class="flex-1 py-3 text-slate-400 font-bold hover:text-slate-600 transition-colors">Annuler</button>
                        <button type="submit" class="flex-2 py-3 px-8 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl shadow-lg shadow-indigo-100 transition-all">
                            Créer le Groupe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
