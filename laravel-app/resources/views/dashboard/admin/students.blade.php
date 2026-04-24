@extends('layouts.dashboard')

@section('title', 'Gestion des Étudiants')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Gestion des Étudiants 👨‍🎓</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez les inscriptions et les informations des étudiants.</p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <form action="{{ route('dashboard.admin.students') }}" method="GET" class="flex items-center gap-2 flex-grow md:flex-grow-0">
                <select name="class_id" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ isset($classId) && $classId == $class->id ? 'selected' : '' }}>{{ $class->class_name }}</option>
                    @endforeach
                </select>
                <select name="profile_status" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                    <option value="">Tous les profils</option>
                    <option value="1" {{ isset($profileStatus) && $profileStatus == '1' ? 'selected' : '' }}>Profils complets</option>
                    <option value="0" {{ isset($profileStatus) && $profileStatus == '0' ? 'selected' : '' }}>Profils incomplets</option>
                </select>
            </form>
            <button onclick="document.getElementById('addStudentModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Ajouter un étudiant
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Students Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Étudiant</th>
                        <th class="px-6 py-4">Âge</th>
                        <th class="px-6 py-4">Niveau / Classe</th>
                        <th class="px-6 py-4">Profil</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($student->profile_photo)
                                    <img src="{{ asset('storage/' . $student->profile_photo) }}" class="w-10 h-10 rounded-xl shadow-sm object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=0D8ABC&color=fff" class="w-10 h-10 rounded-xl shadow-sm">
                                @endif
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">{{ $student->first_name }} {{ $student->last_name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $student->email }} | {{ $student->studentDetails->student_code ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                            {{ $student->age ?? 'N/A' }} ans
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-bold text-slate-700">{{ $student->studentDetails->academicLevel->name ?? 'N/A' }}</span>
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-600 font-bold text-[9px] uppercase w-fit">
                                    {{ $student->studentDetails->studentClass->class_name ?? 'Non assigné' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($student->profile_completed)
                                <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase tracking-wider">Complet</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-rose-50 text-rose-600 font-bold text-[10px] uppercase tracking-wider">Incomplet</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full {{ $student->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} font-bold text-[10px] uppercase">
                                {{ $student->status === 'active' ? 'Actif' : 'Suspendu' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal(this)" data-student="{{ json_encode($student) }}" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs hover:bg-blue-600 hover:text-white transition-all"><i class="fa-solid fa-pen"></i></button>
                                <form action="{{ route('dashboard.admin.destroy', ['type' => 'user', 'id' => $student->id]) }}" method="POST" onsubmit="return confirm('Supprimer cet étudiant ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs hover:bg-rose-600 hover:text-white transition-all"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Aucun étudiant trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
        <div class="p-6 border-t border-slate-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>

    <!-- Add Modal -->
    <div id="addStudentModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl animate-modal-in flex flex-col">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Nouvel Étudiant 🎓</h3>
                    <p class="text-slate-500 text-xs mt-1">Remplissez toutes les informations pour créer le compte étudiant.</p>
                </div>
                <button onclick="document.getElementById('addStudentModal').classList.add('hidden')" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto p-8 bg-slate-50/50">
                <form action="{{ route('dashboard.admin.students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Section: Personnel -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-blue-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-user-circle"></i> Informations de Base (Requis)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 shadow-sm">Nom de famille <span class="text-rose-500">*</span></label>
                                <input type="text" name="first_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all" placeholder="Ex: Dupont">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Prénom <span class="text-rose-500">*</span></label>
                                <input type="text" name="last_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all" placeholder="Ex: Jean">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Classe <span class="text-rose-500">*</span></label>
                                <select name="student_class_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                                    <option value="">Sélectionner une classe</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Contact -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-amber-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-address-book"></i> Informations Optionnelles
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email</label>
                                <input type="email" name="email" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all" placeholder="email@exemple.com">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Téléphone</label>
                                <input type="text" name="phone" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all" placeholder="06 XX XX XX XX">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Adresse complète</label>
                                <input type="text" name="address" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all" placeholder="Rue, Quartier, N°...">
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-3xl border border-blue-100">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-lg shadow-blue-500/20">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-black text-blue-900">Génération Automatique</h5>
                                <p class="text-xs text-blue-600/80 leading-relaxed mt-1">Le système générera automatiquement un <strong>nom d'utilisateur</strong> et un <strong>mot de passe par défaut</strong> (prenomnom / prenomnom@2026) si vous ne les spécifiez pas.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-white sticky bottom-0 p-6 z-10 border-t border-slate-100 rounded-b-[2rem]">
                        <button type="button" onclick="document.getElementById('addStudentModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all">Annuler</button>
                        <button type="submit" class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/20">Créer le compte étudiant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editStudentModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl animate-modal-in flex flex-col">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Modifier un Étudiant 🎓</h3>
                    <p class="text-slate-500 text-xs mt-1">Mettez à jour les informations de l'étudiant.</p>
                </div>
                <button onclick="document.getElementById('editStudentModal').classList.add('hidden')" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto p-8 bg-slate-50/50">
                <form id="editStudentForm" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Section: Personnel -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-blue-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-user-circle"></i> Informations Personnelles
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:row-span-2 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-3xl p-4 bg-slate-50 group hover:border-blue-400 transition-all cursor-pointer relative overflow-hidden">
                                <input type="file" name="profile_photo" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewEditImage(this)">
                                <div id="editPhotoPreview" class="text-center">
                                    <i class="fa-solid fa-camera text-3xl text-slate-300 group-hover:text-blue-500 mb-2"></i>
                                    <p class="text-[10px] font-bold text-slate-400 group-hover:text-blue-500 uppercase">Nouvelle photo</p>
                                </div>
                                <img id="editPreviewImg" class="hidden absolute inset-0 w-full h-full object-cover">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 shadow-sm">Nom de famille</label>
                                <input type="text" id="edit_first_name" name="first_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Prénom</label>
                                <input type="text" id="edit_last_name" name="last_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date de naissance</label>
                                <input type="date" id="edit_dob" name="dob" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sexe</label>
                                <select id="edit_gender" name="gender" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Contact -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-amber-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-address-book"></i> Contact & Adresse
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Téléphone</label>
                                <input type="text" id="edit_phone" name="phone" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Ville</label>
                                <input type="text" id="edit_city" name="city" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Adresse complète</label>
                                <input type="text" id="edit_address" name="address" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Scolaire -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-indigo-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap"></i> Informations Scolaires
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Code Étudiant</label>
                                <input type="text" id="edit_student_code" name="student_code" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Niveau</label>
                                <select id="edit_academic_level_id" name="academic_level_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                                    <option value="">Sélectionner un niveau</option>
                                    @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Classe</label>
                                <select id="edit_student_class_id" name="student_class_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                                    <option value="">Sélectionner une classe</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Filière / Formation</label>
                                <input type="text" id="edit_filiere" name="filiere" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Année Scolaire</label>
                                <select id="edit_academic_year_id" name="academic_year_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                                    @foreach($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date d'inscription</label>
                                <input type="date" id="edit_registration_date" name="registration_date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Compte -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-rose-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-lock"></i> Compte Utilisateur
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email de connexion</label>
                                <input type="email" id="edit_email" name="email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Username (Optionnel)</label>
                                <input type="text" id="edit_username" name="username" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nouveau mot de passe</label>
                                <input type="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all" placeholder="Laisser vide pour ne pas changer">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</label>
                                <select id="edit_status" name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                                    <option value="active">Actif</option>
                                    <option value="inactive">Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-white sticky bottom-0 p-6 z-10 border-t border-slate-100 rounded-b-[2rem]">
                        <button type="button" onclick="document.getElementById('editStudentModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all">Annuler</button>
                        <button type="submit" class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/20">Mettre à jour l'étudiant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('previewImg').classList.remove('hidden');
                    document.getElementById('photoPreview').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewEditImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('editPreviewImg').src = e.target.result;
                    document.getElementById('editPreviewImg').classList.remove('hidden');
                    document.getElementById('editPhotoPreview').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openEditModal(button) {
            let student = JSON.parse(button.getAttribute('data-student'));
            document.getElementById('editStudentForm').action = "/dashboard/admin/students/" + student.id;
            
            // Personal
            document.getElementById('edit_first_name').value = student.first_name || '';
            document.getElementById('edit_last_name').value = student.last_name || '';
            document.getElementById('edit_dob').value = student.dob || '';
            document.getElementById('edit_gender').value = student.gender || 'M';
            
            // Contact
            document.getElementById('edit_phone').value = student.phone || '';
            document.getElementById('edit_city').value = student.city || '';
            document.getElementById('edit_address').value = student.address || '';
            
            // Account
            document.getElementById('edit_email').value = student.email || '';
            document.getElementById('edit_username').value = student.username || '';
            document.getElementById('edit_status').value = student.status || 'active';
            
            // Academic Details
            if (student.student_details) {
                document.getElementById('edit_student_code').value = student.student_details.student_code || '';
                document.getElementById('edit_academic_level_id').value = student.student_details.academic_level_id || '';
                document.getElementById('edit_student_class_id').value = student.student_details.student_class_id || '';
                document.getElementById('edit_filiere').value = student.student_details.filiere || '';
                document.getElementById('edit_academic_year_id').value = student.student_details.academic_year_id || '';
                document.getElementById('edit_registration_date').value = student.student_details.registration_date || '';
            }
            
            if (student.profile_photo) {
                document.getElementById('editPreviewImg').src = "/storage/" + student.profile_photo;
                document.getElementById('editPreviewImg').classList.remove('hidden');
                document.getElementById('editPhotoPreview').classList.add('hidden');
            } else {
                document.getElementById('editPreviewImg').classList.add('hidden');
                document.getElementById('editPhotoPreview').classList.remove('hidden');
            }

            document.getElementById('editStudentModal').classList.remove('hidden');
        }
    </script>
@endsection
