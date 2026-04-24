@extends('layouts.dashboard')

@section('title', 'Niveaux & Classes')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Niveaux & Classes 🏫</h1>
            <p class="text-slate-500 text-sm mt-1">Configurez les niveaux académiques et les classes de l'établissement.</p>
        </div>
        <button onclick="document.getElementById('addLevelModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nouveau Niveau
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($levels as $level)
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all group flex flex-col">
            <div class="flex justify-between items-start mb-4">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all text-xl font-black">
                    {{ $level->code }}
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard.admin.classes', ['level_id' => $level->id]) }}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" title="Gestion des Classes">
                        <i class="fa-solid fa-chalkboard-user text-sm"></i>
                    </a>
                    <form action="{{ route('dashboard.admin.destroy', ['type' => 'level', 'id' => $level->id]) }}" method="POST" onsubmit="return confirm('Attention ! La suppression d\'un niveau entraînera la suppression de toutes ses classes et matières. Continuer ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-100 transition-colors" title="Supprimer">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $level->name }}</h3>
            @if($level->academicYear)
            <p class="text-xs font-bold text-slate-500 mb-4">{{ $level->academicYear->name }}</p>
            @else
            <p class="text-xs font-bold text-amber-500 mb-4">Non lié à une année scolaire active</p>
            @endif
            
            <div class="mt-auto grid grid-cols-2 gap-3 pt-4 border-t border-slate-100">
                <div class="bg-slate-50 p-3 rounded-xl">
                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Classes</p>
                    <p class="text-lg font-black text-slate-900">{{ $level->student_classes_count ?? 0 }}</p>
                </div>
                <div class="bg-slate-50 p-3 rounded-xl">
                    <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1">Matières</p>
                    <p class="text-lg font-black text-slate-900">{{ $level->subjects_count ?? 0 }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center text-slate-400 italic bg-white rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            Aucun niveau configuré.
        </div>
        @endforelse
    </div>

    <!-- Add Level Modal -->
    <div id="addLevelModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl animate-modal-in">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-900">Nouveau Niveau</h3>
                    <button onclick="document.getElementById('addLevelModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('dashboard.admin.levels.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nom du Niveau</label>
                        <input type="text" name="name" placeholder="ex: Licence 1" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Code</label>
                        <input type="text" name="code" placeholder="ex: L1" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Description (Optionnel)</label>
                        <textarea name="description" placeholder="Description du niveau académique..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm" rows="3"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Année Scolaire Active</label>
                        <select name="academic_year_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                            <option value="">Sélectionner l'année scolaire</option>
                            @foreach(\App\Models\AcademicYear::where('status', 'active')->get() as $year)
                                <option value="{{ $year->id }}" selected>{{ $year->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-500 mt-1 italic">Le niveau sera rattaché par défaut à l'année scolaire active.</p>
                    </div>
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all mt-6 shadow-lg shadow-blue-500/20">Créer le niveau</button>
                </form>
            </div>
        </div>
    </div>
@endsection
