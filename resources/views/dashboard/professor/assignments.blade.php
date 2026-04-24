@extends('layouts.dashboard')
@section('title', 'Devoirs')
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Gestion des Devoirs ✏️</h1>
            <p class="text-slate-500 text-sm mt-1">Créez, assignez et corrigez les devoirs de vos classes.</p>
        </div>
        <button onclick="document.getElementById('addAssignmentModal').classList.remove('hidden')" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-amber-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nouveau Devoir
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i><span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Summary Cards --}}
    <div class="grid grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-file-pen"></i></div>
            <div><p class="text-xl font-black text-slate-900">{{ $assignments->count() }}</p><p class="text-xs text-slate-500">Total devoirs</p></div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-hourglass-half"></i></div>
            <div><p class="text-xl font-black text-slate-900">{{ $assignments->filter->isOverdue()->count() }}</p><p class="text-xs text-slate-500">Expirés</p></div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-xl"><i class="fa-solid fa-check-double"></i></div>
            <div><p class="text-xl font-black text-slate-900">{{ $assignments->sum(fn($a) => $a->submissions->count()) }}</p><p class="text-xs text-slate-500">Soumissions reçues</p></div>
        </div>
    </div>

    {{-- Assignments List --}}
    <div class="space-y-4">
        @forelse($assignments as $a)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl {{ $a->isOverdue() ? 'bg-rose-50 text-rose-500' : 'bg-amber-50 text-amber-500' }} flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-file-pen"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="font-black text-slate-900 text-base">{{ $a->title }}</h3>
                        @if($a->isOverdue())<span class="px-2 py-0.5 bg-rose-50 text-rose-600 rounded-full text-[10px] font-black">EXPIRÉ</span>@endif
                    </div>
                    <p class="text-xs text-slate-500 truncate max-w-2xl">{{ $a->description ?? 'Pas de description.' }}</p>
                    <div class="flex flex-wrap gap-3 mt-2 text-xs text-slate-500 font-medium">
                        <span><i class="fa-solid fa-users mr-1 text-blue-400"></i>{{ $a->studentClass->class_name ?? 'N/A' }}</span>
                        <span><i class="fa-solid fa-book mr-1 text-indigo-400"></i>{{ $a->subject->subject_name ?? 'N/A' }}</span>
                        @if($a->due_date)<span><i class="fa-regular fa-clock mr-1 text-amber-400"></i>{{ $a->due_date->format('d/m/Y H:i') }}</span>@endif
                        <span><i class="fa-solid fa-file-lines mr-1 text-emerald-400"></i>{{ $a->submissions->count() }} soumissions</span>
                    </div>
                </div>
                <div class="flex-shrink-0 flex items-center gap-2">
                    @if($a->file_path)
                    <a href="{{ Storage::url($a->file_path) }}" download target="_blank" class="w-9 h-9 bg-slate-50 text-slate-500 rounded-xl flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-all" title="Télécharger le fichier">
                        <i class="fa-solid fa-download text-sm"></i>
                    </a>
                    @endif
                    <a href="{{ route('dashboard.professor.submissions', $a->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-black hover:bg-blue-700 transition-all">
                        Soumissions ({{ $a->submissions->count() }})
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="py-16 text-center bg-white rounded-2xl border border-slate-100">
            <i class="fa-solid fa-file-pen text-5xl text-slate-200 mb-4"></i>
            <p class="text-lg font-bold text-slate-400">Aucun devoir créé.</p>
            <p class="text-sm text-slate-400 mt-1">Cliquez sur "Nouveau Devoir" pour commencer.</p>
        </div>
        @endforelse
    </div>

    {{-- Add Assignment Modal --}}
    <div id="addAssignmentModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black text-slate-900">Nouveau Devoir ✏️</h3>
                    <p class="text-slate-500 text-xs mt-1">Assignez un devoir à une classe avec une deadline.</p>
                </div>
                <button onclick="document.getElementById('addAssignmentModal').classList.add('hidden')" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="overflow-y-auto p-8 bg-slate-50/50">
                <form action="{{ route('dashboard.professor.assignments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Titre du devoir</label>
                        <input type="text" name="title" required placeholder="Ex: TP Algorithmique N°3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-amber-500/10 font-bold text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Classe</label>
                            <select name="student_class_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-amber-500/10 font-bold text-sm">
                                <option value="">Sélectionner</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Matière</label>
                            <select name="subject_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-amber-500/10 font-bold text-sm">
                                <option value="">Sélectionner</option>
                                @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->subject_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date Limite</label>
                            <input type="datetime-local" name="due_date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-amber-500/10 font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Note maximum</label>
                            <input type="number" name="max_grade" value="20" min="1" max="100" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-amber-500/10 font-bold text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Description / Consignes</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-amber-500/10 font-bold text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Fichier Joint</label>
                        <input type="file" name="file" accept=".pdf,.docx,.pptx,.zip" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600">
                    </div>
                    <div class="flex gap-4 pt-2">
                        <button type="button" onclick="document.getElementById('addAssignmentModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200">Annuler</button>
                        <button type="submit" class="flex-[2] py-4 bg-amber-500 text-white rounded-2xl font-black text-sm hover:bg-amber-600 shadow-xl shadow-amber-500/20">Créer le Devoir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
