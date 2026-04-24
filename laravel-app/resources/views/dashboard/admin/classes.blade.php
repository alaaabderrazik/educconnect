@extends('layouts.dashboard')

@section('title', 'Gestion des Classes')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard.admin.levels') }}" class="w-10 h-10 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Gestion des Classes 🎓</h1>
            </div>
            <p class="text-slate-500 text-sm mt-1 ml-13">Gérez les classes et assignez les professeurs responsables.</p>
        </div>
        <button onclick="document.getElementById('addClassModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nouvelle Classe
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes as $class)
        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition-all group flex flex-col">
            <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-black text-slate-900">{{ $class->class_name }}</h3>
                    <p class="text-xs font-bold text-blue-600 mt-1">{{ $class->academicLevel->name ?? 'Niveau Inconnu' }}</p>
                </div>
                <form action="{{ route('dashboard.admin.destroy', ['type' => 'class', 'id' => $class->id]) }}" method="POST" onsubmit="return confirm('Confirmer la suppression de cette classe ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-white text-slate-300 rounded-lg shadow-sm hover:bg-rose-50 hover:text-rose-500 transition-colors" title="Supprimer">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </form>
            </div>
            
            <div class="p-6 space-y-4 flex-1">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Prof. Principal</p>
                        <p class="text-sm font-bold text-slate-900">{{ $class->responsibleProfessor->name ?? 'Non assigné' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Salle Attitrée</p>
                        <p class="text-sm font-bold text-slate-900">{{ $class->room ?? 'Aucune' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="bg-emerald-50 rounded-xl p-3 text-center border border-emerald-100">
                        <p class="text-xl font-black text-emerald-600">{{ $class->student_details_count ?? 0 }}</p>
                        <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Étudiants</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100">
                        <p class="text-xl font-black text-slate-600">{{ $class->capacity ?? 0 }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Capacité</p>
                    </div>
                </div>
            </div>
            
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <button class="w-full py-3 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-bold hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-solid fa-users"></i> Voir les étudiants
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center text-slate-400 italic bg-white rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                <i class="fa-solid fa-users-slash"></i>
            </div>
            <p>Aucune classe configurée{{ $levelId ? ' pour ce niveau' : '' }}.</p>
        </div>
        @endforelse
    </div>

    <!-- Add Class Modal -->
    <div id="addClassModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl animate-modal-in">
            <div class="p-8 border-b border-slate-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-slate-900">Nouvelle Classe</h3>
                    <button onclick="document.getElementById('addClassModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center bg-slate-50 rounded-lg hover:bg-slate-100"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
            <div class="p-8">
                @if(!$activeYear)
                    <div class="p-4 bg-amber-50 text-amber-600 rounded-xl border border-amber-200 mb-6 flex items-start gap-3">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                        <div class="text-sm font-medium">Vous devez <a href="{{ route('dashboard.admin.years') }}" class="font-bold underline text-amber-700 hover:text-amber-800">activer une année scolaire</a> avant de créer des classes.</div>
                    </div>
                @else
                    <form action="{{ route('dashboard.admin.classes.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nom de la Classe *</label>
                                <input type="text" name="class_name" placeholder="ex: L2-A Informatique" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all focus:border-blue-400">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Niveau *</label>
                                <select name="level_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm text-slate-700">
                                    <option value="">Sélectionnez un niveau</option>
                                    @foreach($levels as $lvl)
                                        <option value="{{ $lvl->id }}" {{ $levelId == $lvl->id ? 'selected' : '' }}>{{ $lvl->name }} ({{ $lvl->code }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Capacité *</label>
                                <input type="number" name="capacity" value="30" min="1" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Professeur Principal (Optionnel)</label>
                                <select name="professor_responsible" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm text-slate-700">
                                    <option value="">Aucun professeur assigné</option>
                                    @foreach($professors as $prof)
                                        <option value="{{ $prof->id }}">Pr. {{ $prof->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Salle de cours fixe (Optionnelle)</label>
                                <input type="text" name="room" placeholder="ex: Salle B-102" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                            </div>
                        </div>

                        <div class="pt-4 mt-6 border-t border-slate-100 flex gap-3">
                            <button type="button" onclick="document.getElementById('addClassModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">Annuler</button>
                            <button type="submit" class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">Créer la classe</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
