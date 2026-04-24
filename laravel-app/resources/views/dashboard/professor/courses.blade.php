@extends('layouts.dashboard')
@section('title', 'Mes Cours')
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mes Cours & Ressources 📚</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez vos cours et partagez des matériaux pédagogiques.</p>
        </div>
        <button onclick="document.getElementById('addMaterialModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-up"></i> Ajouter un Support
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i><span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Subjects Banner --}}
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($subjects as $subj)
        <div class="p-4 bg-indigo-50 border border-indigo-100 text-indigo-700 rounded-2xl flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-500 shrink-0">
                <i class="fa-solid fa-book"></i>
            </div>
            <div>
                <p class="font-black text-sm">{{ $subj->subject_name }}</p>
                <p class="text-[10px] uppercase font-bold text-indigo-500">{{ $subj->academicLevel->level_name ?? '' }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Materials Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-black text-slate-900">Supports de cours publiés</h3>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                    <th class="px-6 py-4">Titre & Description</th>
                    <th class="px-6 py-4">Classe</th>
                    <th class="px-6 py-4">Matière</th>
                    <th class="px-6 py-4 text-center">Fichier/Lien</th>
                    <th class="px-6 py-4 text-right">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($materials as $m)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-bold text-sm text-slate-900">{{ $m->title }}</p>
                        <p class="text-xs text-slate-400 truncate max-w-xs">{{ Str::limit($m->description, 60) }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $m->studentClass->class_name ?? 'Toutes les classes' }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-xs font-bold">{{ $m->subject->subject_name ?? 'N/A' }}</span></td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            @if($m->file_path)
                            <a href="{{ Storage::url($m->file_path) }}" download target="_blank" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors" title="Télécharger">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            @endif
                            @if($m->link)
                            <a href="{{ $m->link }}" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-colors" title="Ouvrir le lien">
                                <i class="fa-solid fa-link"></i>
                            </a>
                            @endif
                            @if(!$m->file_path && !$m->link)
                            <span class="text-slate-300">-</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right text-xs font-bold text-slate-500">
                        {{ $m->created_at->format('d/m/Y') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Aucun support de cours partagé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Add Material Modal --}}
    <div id="addMaterialModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black text-slate-900">Ajouter un support de cours</h3>
                    <p class="text-slate-500 text-xs mt-1">Partagez des fichiers ou des liens avec vos classes.</p>
                </div>
                <button onclick="document.getElementById('addMaterialModal').classList.add('hidden')" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="overflow-y-auto p-8 bg-slate-50/50">
                <form action="{{ route('dashboard.professor.courses.materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Titre du support</label>
                        <input type="text" name="title" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Classe ciblée</label>
                            <select name="student_class_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                                <option value="">Toutes les classes</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Matière</label>
                            <select name="subject_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                                <option value="">Sélectionner</option>
                                @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->subject_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Lien Web (Optionnel)</label>
                            <input type="url" name="link" placeholder="https://" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Fichier Joint (PDF, DOCX...)</label>
                            <input type="file" name="file" accept=".pdf,.docx,.pptx,.zip" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600 bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Description / Notes</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all"></textarea>
                    </div>
                    
                    <div class="flex gap-4 pt-2">
                        <button type="button" onclick="document.getElementById('addMaterialModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all">Annuler</button>
                        <button type="submit" class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/20"><i class="fa-solid fa-cloud-arrow-up mr-2"></i>Publier le support</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
