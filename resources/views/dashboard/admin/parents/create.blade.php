@extends('layouts.dashboard')

@section('title', 'Ajouter un Parent')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Ajouter un Parent 👪</h1>
        <p class="text-slate-500 text-sm mt-1">Créez un compte parent et liez-le à un étudiant.</p>
    </div>

    @if(session('success'))
    <div class="mb-8 p-6 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-3xl flex flex-col gap-2 animate-fade-in shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-xl text-emerald-500"></i>
            <span class="text-base font-bold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-8 p-6 bg-rose-50 border border-rose-100 text-rose-700 rounded-3xl flex flex-col gap-2 animate-fade-in shadow-sm">
        <ul class="list-disc list-inside text-sm font-bold">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('dashboard.admin.parents.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        
        <!-- Parent Information -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-blue-600"></i> Infos Parent
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 shadow-sm">Prénom</label>
                        <input type="text" name="first_name" required value="{{ old('first_name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all" placeholder="Ex: Lucas">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 shadow-sm">Nom</label>
                        <input type="text" name="last_name" required value="{{ old('last_name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all" placeholder="Ex: Blanc">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 shadow-sm">Email de connexion</label>
                        <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all" placeholder="parent@exemple.com">
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-50">
                    <div class="flex items-start gap-4 p-4 bg-blue-50 rounded-2xl border border-blue-100">
                        <i class="fa-solid fa-info-circle text-blue-500 mt-1"></i>
                        <p class="text-[11px] text-blue-600/80 leading-relaxed font-medium">Le mot de passe sera <strong>généré automatiquement</strong> et affiché après la création.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Selection -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 min-h-[500px] flex flex-col">
                <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-indigo-600"></i> Lier à un Étudiant
                </h3>

                <!-- Filters -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Filtrer par Niveau</label>
                        <select id="level_filter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            <option value="">Tous les niveaux</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Filtrer par Classe</label>
                        <select id="class_filter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            <option value="">Toutes les classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" data-level="{{ $class->level_id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Search Input -->
                <div class="relative mb-6">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="student_search" class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all shadow-inner" placeholder="Rechercher un étudiant par nom, email ou code...">
                </div>

                <!-- Student List -->
                <div class="flex-grow overflow-y-auto max-h-[300px] pr-2 space-y-3 custom-scrollbar" id="student_list">
                    <div class="text-center py-12 text-slate-400 italic bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                        Saisissez une recherche ou sélectionnez des filtres.
                    </div>
                </div>

                <input type="hidden" name="student_id" id="selected_student_id" required>

                <div id="selection_preview" class="mt-6 p-4 bg-indigo-50 rounded-2xl border border-indigo-100 hidden animate-fade-in">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-user-check text-indigo-500"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Étudiant Sélectionné</p>
                                <h4 id="selected_student_name" class="text-sm font-black text-indigo-900"></h4>
                            </div>
                        </div>
                        <button type="button" id="clear_selection" class="text-indigo-400 hover:text-rose-500 transition-colors p-2 text-xl">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="flex-grow py-5 bg-blue-600 text-white rounded-3xl font-black text-lg hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/30 flex items-center justify-center gap-3 active:scale-95">
                    <i class="fa-solid fa-user-shield"></i> Créer le compte Parent
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const levelFilter = document.getElementById('level_filter');
    const classFilter = document.getElementById('class_filter');
    const studentSearch = document.getElementById('student_search');
    const studentList = document.getElementById('student_list');
    const selectedStudentId = document.getElementById('selected_student_id');
    const selectedStudentName = document.getElementById('selected_student_name');
    const selectionPreview = document.getElementById('selection_preview');
    const clearSelection = document.getElementById('clear_selection');

    let debounceTimer;

    function fetchStudents() {
        const levelId = levelFilter.value;
        const classId = classFilter.value;
        const search = studentSearch.value;

        if (!levelId && !classId && search.length < 2) {
            studentList.innerHTML = '<div class="text-center py-12 text-slate-400 italic bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">Saisissez une recherche ou sélectionnez des filtres.</div>';
            return;
        }

        studentList.innerHTML = '<div class="flex items-center justify-center py-12"><i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500"></i></div>';

        const params = new URLSearchParams({
            level_id: levelId,
            class_id: classId,
            search: search
        });

        fetch(`{{ route('dashboard.admin.parents.search_students') }}?${params}`)
            .then(response => response.json())
            .then(data => {
                studentList.innerHTML = '';
                if (data.length === 0) {
                    studentList.innerHTML = '<div class="text-center py-12 text-slate-400 italic bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">Aucun étudiant trouvé.</div>';
                    return;
                }

                data.forEach(student => {
                    const studentCard = document.createElement('div');
                    studentCard.className = 'group flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl hover:border-blue-300 hover:shadow-md transition-all cursor-pointer shadow-sm';
                    studentCard.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-black group-hover:bg-blue-600 group-hover:text-white transition-all">
                                ${student.first_name.charAt(0)}${student.last_name.charAt(0)}
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-900">${student.first_name} ${student.last_name}</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">${student.student_details?.student_class?.class_name || 'N/A'} - ${student.student_details?.student_code || '---'}</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-plus-circle text-slate-200 group-hover:text-blue-500 text-xl transition-all"></i>
                    `;
                    studentCard.onclick = () => selectStudent(student);
                    studentList.appendChild(studentCard);
                });
            });
    }

    function selectStudent(student) {
        selectedStudentId.value = student.id;
        selectedStudentName.textContent = `${student.first_name} ${student.last_name}`;
        selectionPreview.classList.remove('hidden');
        studentList.classList.add('opacity-50', 'pointer-events-none');
    }

    clearSelection.onclick = () => {
        selectedStudentId.value = '';
        selectionPreview.classList.add('hidden');
        studentList.classList.remove('opacity-50', 'pointer-events-none');
    };

    levelFilter.addEventListener('change', fetchStudents);
    classFilter.addEventListener('change', fetchStudents);
    studentSearch.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchStudents, 300);
    });

    // Sub-filter: Update Class options when Level changes (client-side)
    levelFilter.addEventListener('change', () => {
        const selectedLevel = levelFilter.value;
        Array.from(classFilter.options).forEach(option => {
            if (!selectedLevel || option.value === "" || option.dataset.level === selectedLevel) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        classFilter.value = "";
    });
});
</script>
@endsection
