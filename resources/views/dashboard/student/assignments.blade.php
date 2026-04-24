@extends('layouts.dashboard')
@section('title', 'Mes Devoirs')
@section('user_role', 'Étudiant')
@section('sidebar_menu')@include('dashboard.student.sidebar')@endsection

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mes Devoirs 📝</h1>
            <p class="text-slate-500 text-sm mt-1">Consultez et soumettez vos travaux à rendre.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-circle-xmark text-xl"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6">
        @forelse($assignments as $assignment)
            @php
                $submission = $assignment->submissions->first();
                $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                $isOverdue = now()->gt($dueDate) && !$submission;
            @endphp
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col md:flex-row items-center p-6 gap-6 hover:shadow-md transition-all">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl flex-shrink-0 border border-blue-100">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                
                <div class="flex-grow">
                    <div class="flex flex-wrap items-center gap-3 mb-3 border-b border-slate-100 pb-3">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Cours</span>
                            <span class="text-sm font-bold text-indigo-600">{{ $assignment->subject->subject_name ?? 'N/A' }}</span>
                        </div>
                        <div class="h-6 w-px bg-slate-200 mx-2 hidden sm:block"></div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Professeur</span>
                            <span class="text-sm font-bold text-slate-700">{{ $assignment->subject->professor->name ?? 'Non assigné' }}</span>
                        </div>
                        <div class="ml-auto flex gap-2">
                            @if($submission)
                                <span class="px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-lg text-xs font-bold uppercase tracking-wider">Soumis</span>
                            @elseif($isOverdue)
                                <span class="px-3 py-1 bg-rose-50 border border-rose-100 text-rose-600 rounded-lg text-xs font-bold uppercase tracking-wider">En retard</span>
                            @else
                                <span class="px-3 py-1 bg-amber-50 border border-amber-100 text-amber-600 rounded-lg text-xs font-bold uppercase tracking-wider">À faire</span>
                            @endif
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $assignment->title }}</h3>
                    <div class="mb-4">
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $assignment->description }}</p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 p-3 bg-slate-50 rounded-xl">
                        <div class="flex items-center gap-2 text-sm font-medium">
                            <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                <i class="fa-regular fa-calendar-xmark"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 m-0 uppercase tracking-wider">Échéance</p>
                                <p class="text-rose-600 m-0 font-bold">{{ $dueDate->format('d M Y - H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($assignment->file_path)
                        <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>
                        <div class="flex items-center gap-2 text-sm font-medium">
                             <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-paperclip"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 m-0 uppercase tracking-wider">Pièce jointe</p>
                                <a href="{{ Storage::url($assignment->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline font-bold">Télécharger le fichier</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex-shrink-0 w-full md:w-auto">
                    @if($submission)
                        <div class="text-center">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Soumis le {{ \Carbon\Carbon::parse($submission->submitted_at)->format('d/m/Y') }}</p>
                            @if($submission->grade)
                                <div class="inline-flex items-center gap-2 bg-emerald-600 text-white font-bold py-2 px-4 rounded-xl shadow-lg shadow-emerald-500/20">
                                    <span class="text-lg">{{ $submission->grade }}</span>
                                    <span class="text-xs opacity-70">/ 20</span>
                                </div>
                            @else
                                <span class="inline-block py-2 px-4 bg-slate-100 text-slate-400 rounded-xl font-bold text-sm">En attente de correction</span>
                            @endif
                        </div>
                    @else
                        <button onclick="openSubmitModal({{ $assignment->id }}, '{{ addslashes($assignment->title) }}')" class="w-full py-2.5 px-6 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Déposer mon travail
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-20 text-center text-slate-400 italic bg-white rounded-3xl border border-dashed border-slate-200">
                Aucun devoir assigné pour le moment.
            </div>
        @endforelse
    </div>

    @if($assignments->count() > 0)
        <div class="mt-8">
            {{ $assignments->links() }}
        </div>
    @endif

    <!-- Submission Modal -->
    <div id="submitModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-black text-slate-800">Déposer un devoir</h3>
                <button onclick="closeSubmitModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="submitForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div>
                    <p id="modalAssignmentTitle" class="text-sm font-bold text-blue-600 mb-4"></p>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Votre fichier (PDF, DOC, ZIP)</label>
                    <div class="relative group">
                        <input type="file" name="attachment" required class="w-full px-4 py-3 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:outline-none transition-all">
                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center opacity-0 group-hover:opacity-100 bg-white/80 transition-opacity rounded-xl">
                            <p class="text-xs font-bold text-blue-600">Cliquez pour choisir</p>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2 mb-4 italic">Max 10MB. Formats acceptés: .pdf, .doc, .docx, .zip</p>

                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Message pour le professeur (Optionnel)</label>
                    <textarea name="message" rows="3" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:outline-none transition-all resize-none" placeholder="Ajoutez un commentaire sur votre travail..."></textarea>
                </div>
                
                <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/25 uppercase tracking-widest mt-4">
                    Envoyer mon travail <i class="fa-solid fa-paper-plane ml-2"></i>
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openSubmitModal(id, title) {
        const modal = document.getElementById('submitModal');
        const form = document.getElementById('submitForm');
        const titleEl = document.getElementById('modalAssignmentTitle');
        
        form.action = `/dashboard/student/assignments/${id}/submit`;
        titleEl.innerText = title;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSubmitModal() {
        const modal = document.getElementById('submitModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on outside click
    window.onclick = function(event) {
        const modal = document.getElementById('submitModal');
        if (event.target == modal) {
            closeSubmitModal();
        }
    }
</script>
@endsection
