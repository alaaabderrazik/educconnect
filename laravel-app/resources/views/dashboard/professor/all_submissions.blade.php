@extends('layouts.dashboard')
@section('title', 'Toutes les Soumissions')
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Toutes les Soumissions 📥</h1>
        <p class="text-slate-500 text-sm mt-1">Consultez tous les devoirs remis par vos étudiants.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h2 class="font-bold text-slate-800">Historique des remises</h2>
        </div>

        @if($submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-bold">Étudiant</th>
                            <th class="p-4 font-bold">Devoir & Cours</th>
                            <th class="p-4 font-bold">Fichier</th>
                            <th class="p-4 font-bold">Date de remise</th>
                            <th class="p-4 font-bold text-center">Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 align-top">
                        @foreach($submissions as $submission)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <!-- Student Info -->
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                            {{ substr($submission->student->first_name, 0, 1) }}{{ substr($submission->student->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $submission->student->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $submission->assignment->studentClass->class_name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    @if($submission->notes)
                                        <div class="mt-3 p-3 bg-amber-50 rounded-lg text-xs text-amber-800 border border-amber-100">
                                            <span class="font-bold block mb-1"><i class="fa-solid fa-comment-dots"></i> Message de l'étudiant:</span>
                                            {{ $submission->notes }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Assignment Info -->
                                <td class="p-4">
                                    <p class="font-bold text-slate-900 text-sm max-w-xs truncate" title="{{ $submission->assignment->title }}">
                                        {{ $submission->assignment->title }}
                                    </p>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-indigo-50 text-indigo-600 rounded text-[10px] font-bold uppercase tracking-wider mt-1">
                                        {{ $submission->assignment->subject->subject_name ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- Download Button -->
                                <td class="p-4">
                                    <a href="{{ Storage::url($submission->file_path) }}" download target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-600 rounded-lg text-xs font-bold transition-colors">
                                        <i class="fa-solid fa-download"></i> Télécharger
                                    </a>
                                </td>

                                <!-- Date Info -->
                                <td class="p-4 whitespace-nowrap">
                                    <p class="text-sm font-bold text-slate-700">{{ $submission->submitted_at->format('d/m/Y') }}</p>
                                    <p class="text-xs text-slate-500">{{ $submission->submitted_at->format('H:i') }}</p>
                                    
                                    @if($submission->submitted_at > $submission->assignment->due_date)
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-rose-50 text-rose-600 rounded text-[10px] font-bold uppercase">En retard</span>
                                    @endif
                                </td>

                                <!-- Grade Info -->
                                <td class="p-4 text-center">
                                    @if($submission->grade !== null)
                                        <div class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg font-bold">
                                            <span class="text-base">{{ $submission->grade }}</span>
                                            <span class="text-xs opacity-60">/ 20</span>
                                        </div>
                                    @else
                                        <button onclick="openGradeModal({{ $submission->id }}, '{{ addslashes($submission->student->name) }}', '{{ addslashes($submission->assignment->title) }}')" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                                            Noter
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-slate-100">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="py-20 text-center">
                <i class="fa-solid fa-inbox text-5xl text-slate-200 mb-4"></i>
                <p class="text-lg font-bold text-slate-400">Aucune soumission pour le moment.</p>
                <p class="text-sm text-slate-400 mt-1">Les travaux remis apparaîtront ici.</p>
            </div>
        @endif
    </div>

    <!-- Grade Modal -->
    <div id="gradeModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-black text-slate-800">Évaluer le devoir</h3>
                <button onclick="closeGradeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="gradeForm" class="p-6 space-y-4">
                @csrf
                <p class="text-sm text-slate-600 mb-4">
                    Étudiant: <span id="modalStudentName" class="font-bold text-slate-900"></span><br>
                    Devoir: <span id="modalAssignmentTitle" class="font-bold text-slate-900"></span>
                </p>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Note (sur 20)</label>
                    <input type="number" id="gradeInput" name="grade" min="0" max="20" step="0.5" required class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Commentaire (optionnel)</label>
                    <textarea id="feedbackInput" name="feedback" rows="3" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:outline-none transition-all"></textarea>
                </div>
                
                <button type="submit" class="w-full py-3 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/20">
                    Enregistrer la note
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openGradeModal(id, studentName, assignmentTitle) {
        document.getElementById('gradeForm').dataset.id = id;
        document.getElementById('modalStudentName').textContent = studentName;
        document.getElementById('modalAssignmentTitle').textContent = assignmentTitle;
        document.getElementById('gradeInput').value = '';
        document.getElementById('feedbackInput').value = '';
        document.getElementById('gradeModal').classList.remove('hidden');
    }

    function closeGradeModal() {
        document.getElementById('gradeModal').classList.add('hidden');
    }

    document.getElementById('gradeForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = e.target.dataset.id;
        const grade = document.getElementById('gradeInput').value;
        const feedback = document.getElementById('feedbackInput').value;
        const token = document.querySelector('input[name="_token"]').value;

        try {
            const res = await fetch(`/dashboard/professor/submissions/${id}/grade`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ grade, feedback })
            });

            if(res.ok) {
                window.location.reload();
            } else {
                alert('Erreur lors de l\'enregistrement de la note.');
            }
        } catch (error) {
            console.error(error);
            alert('Erreur système.');
        }
    });
</script>
@endsection
