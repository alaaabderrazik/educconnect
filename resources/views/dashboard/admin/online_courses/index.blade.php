@extends('layouts.dashboard')
@section('title', 'Cours en Ligne')
@section('user_role', 'Administrateur')
@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Cours en Ligne 🌐</h1>
            <p class="text-slate-500 text-sm mt-1">Planifiez et publiez des sessions de cours à distance.</p>
        </div>
        <button onclick="document.getElementById('addCourseModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-500/20 text-sm font-semibold transition-all">
            <i class="fa-solid fa-plus"></i> Nouveau Cours
        </button>
    </div>

    {{-- Courses Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Cours / Sujet</th>
                        <th class="px-6 py-4">Professeur</th>
                        <th class="px-6 py-4 text-center">Classe</th>
                        <th class="px-6 py-4 text-center">Horaire</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-sm text-slate-900">{{ $course->title }}</p>
                            <p class="text-xs text-indigo-500 font-bold uppercase tracking-wider">{{ $course->subject->subject_name ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($course->professor->name) }}&background=0D8ABC&color=fff" class="w-8 h-8 rounded-full border border-slate-100">
                                <p class="text-sm font-medium text-slate-700">{{ $course->professor->name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs font-bold">{{ $course->studentClass->class_name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-xs font-black text-slate-900">{{ $course->start_date->format('d/m/Y') }}</p>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $course->start_date->format('H:i') }} - {{ $course->end_date->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ $course->course_link }}" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-100 transition-colors" title="Lien du cours">
                                    <i class="fa-solid fa-video text-xs"></i>
                                </a>
                                @if($course->attachment)
                                <a href="{{ asset('storage/' . $course->attachment) }}" target="_blank" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-100 transition-colors" title="Pièce jointe">
                                    <i class="fa-solid fa-paperclip text-xs"></i>
                                </a>
                                @endif
                                <form action="{{ route('dashboard.admin.online-courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Supprimer ce cours ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-100 transition-colors">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-20 text-center text-slate-400 italic">Aucun cours en ligne planifié.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($courses->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $courses->links() }}
        </div>
        @endif
    </div>

    {{-- Add Course Modal --}}
    <div id="addCourseModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-2xl shadow-2xl p-8 max-h-[90vh] overflow-y-auto scrollbar-hide">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-900">Publier un Nouveau Cours</h3>
                <button onclick="document.getElementById('addCourseModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="{{ route('dashboard.admin.online-courses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Titre du Cours</label>
                        <input type="text" name="title" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Professeur</label>
                        <select name="professor_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm">
                            <option value="">Sélectionner</option>
                            @foreach($professors as $prof)
                            <option value="{{ $prof->id }}">{{ $prof->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Classe / Niveau</label>
                        <select name="class_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm">
                            <option value="">Sélectionner</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Matière</label>
                        <select name="subject_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm">
                            <option value="">Sélectionner</option>
                            @foreach($subjects as $subj)
                            <option value="{{ $subj->id }}">{{ $subj->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date & Heure de début</label>
                        <input type="datetime-local" name="start_date" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date & Heure de fin</label>
                        <input type="datetime-local" name="end_date" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Lien du cours (Zoom/Meet)</label>
                        <input type="url" name="course_link" required placeholder="https://zoom.us/j/..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Support (Optionnel PDF/PPT)</label>
                        <input type="file" name="attachment" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="document.getElementById('addCourseModal').classList.add('hidden')" class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200">Annuler</button>
                    <button type="submit" class="flex-[2] py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-500/20">Publier le cours</button>
                </div>
            </form>
        </div>
    </div>
@endsection
