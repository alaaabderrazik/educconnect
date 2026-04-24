@extends('layouts.dashboard')

@section('title', 'Gestion des Matières')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Matières & Modules 📚</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez le catalogue des matières par niveau et assignez les professeurs.</p>
        </div>
        <button onclick="document.getElementById('addSubjectModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nouvelle Matière
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="space-y-8">
        @forelse($levels as $level)
        @if($level->subjects->count() > 0)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter">{{ $level->name }} <span class="text-xs text-slate-400 font-bold ml-2 tracking-widest">({{ $level->code }})</span></h3>
                <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 shadow-sm"><i class="fa-solid fa-book-open text-blue-500 mr-2"></i>{{ $level->subjects->count() }} Matières</span>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($level->subjects as $subject)
                <div class="p-5 rounded-2xl border border-slate-100 bg-white shadow-sm hover:shadow-md transition-all group relative flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl font-black">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <div>
                                <h4 class="text-md font-bold text-slate-900">{{ $subject->subject_name }}</h4>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $subject->subject_code }}</p>
                            </div>
                        </div>
                        <form action="{{ route('dashboard.admin.destroy', ['type' => 'subject', 'id' => $subject->id]) }}" method="POST" onsubmit="return confirm('Attention ! Supprimer cette matière ?')" class="z-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-slate-100 text-slate-300 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center shadow-sm"><i class="fa-solid fa-trash-can text-sm"></i></button>
                        </form>
                    </div>
                    
                    <div class="space-y-3 mb-5 flex-1">
                        <div class="flex items-center gap-2 text-sm">
                            <i class="fa-solid fa-chalkboard-user w-5 text-center text-slate-400"></i>
                            <span class="font-medium text-slate-700">{{ $subject->professor->name ?? 'Aucun professeur' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class="fa-regular fa-clock w-5 text-center text-blue-400"></i>
                            <span class="font-bold text-slate-900">{{ $subject->hours }} <span class="font-medium text-slate-500">Heures</span></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <i class="fa-solid fa-star w-5 text-center text-amber-400"></i>
                            <span class="font-bold text-slate-900">Coef. {{ $subject->coefficient }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @empty
        <div class="col-span-full py-16 text-center text-slate-400 italic bg-white rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            Veuillez d'abord configurer des niveaux.
        </div>
        @endforelse
    </div>

    <!-- Add Modal -->
    <div id="addSubjectModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl animate-modal-in">
            <div class="p-8 border-b border-slate-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-slate-900">Nouvelle Matière</h3>
                    <button onclick="document.getElementById('addSubjectModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center bg-slate-50 rounded-lg hover:bg-slate-100"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
            <div class="p-8">
                <form action="{{ route('dashboard.admin.subjects.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nom de la Matière *</label>
                            <input type="text" name="subject_name" placeholder="ex: Mathématiques Avancées" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all focus:border-blue-400">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Code *</label>
                            <input type="text" name="code" placeholder="ex: MATH-201" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Niveau *</label>
                            <select name="academic_level_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm text-slate-700">
                                <option value="">Sélectionner...</option>
                                @foreach($levels as $l)
                                    <option value="{{ $l->id }}">{{ $l->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Professeur (Optionnel)</label>
                            <select name="professor_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm text-slate-700">
                                <option value="">Aucun professeur assigné</option>
                                @foreach($professors as $p)
                                    <option value="{{ $p->id }}">Pr. {{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Volume Horaire *</label>
                            <div class="relative">
                                <input type="number" name="hours" value="30" min="1" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm pr-12">
                                <span class="absolute right-4 top-3 text-slate-400 text-sm font-bold">h</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Coefficient *</label>
                            <div class="relative">
                                <input type="number" name="coefficient" value="1" min="1" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm pl-10">
                                <span class="absolute left-4 top-3 text-amber-500 text-sm"><i class="fa-solid fa-star"></i></span>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Description (Optionnelle)</label>
                            <textarea name="description" placeholder="Objectifs de la matière..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="pt-4 mt-6 border-t border-slate-100 flex gap-3">
                        <button type="button" onclick="document.getElementById('addSubjectModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">Annuler</button>
                        <button type="submit" class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">Créer la matière</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
