@extends('layouts.dashboard')

@section('title', 'Gestion des Professeurs')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Gestion des Professeurs 👨‍🏫</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez les comptes des enseignants et leurs attributions.</p>
        </div>
        <div class="flex gap-3">
            <div class="flex bg-slate-100 p-1 rounded-xl">
                <a href="{{ route('dashboard.admin.professors') }}" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ !$profile_status ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Tous</a>
                <a href="{{ route('dashboard.admin.professors', ['profile_status' => 'completed']) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $profile_status === 'completed' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Completés</a>
                <a href="{{ route('dashboard.admin.professors', ['profile_status' => 'incomplete']) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $profile_status === 'incomplete' ? 'bg-white text-amber-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Incomplets</a>
            </div>
            <button onclick="document.getElementById('addProfModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-indigo-500/20 flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Nouveau Professeur
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Professors Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Professeur</th>
                        <th class="px-6 py-4">Spécialité & Classes</th>
                        <th class="px-6 py-4">Matières</th>
                        <th class="px-6 py-4">Profil</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($professors as $prof)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($prof->profile_photo)
                                    <img src="{{ asset('storage/' . $prof->profile_photo) }}" class="w-10 h-10 rounded-xl shadow-sm object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($prof->name) }}&background=6366f1&color=fff" class="w-10 h-10 rounded-xl shadow-sm">
                                @endif
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">{{ $prof->first_name }} {{ $prof->last_name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $prof->email }} | {{ $prof->professorDetails->professor_code ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-700">{{ $prof->professorDetails->specialty ?? 'N/A' }}</div>
                            <div class="text-[10px] text-slate-400 mt-1">
                                <span class="bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded">{{ $prof->assigned_classes_count }} classes</span>
                                <span class="bg-purple-50 text-purple-600 px-1.5 py-0.5 rounded ml-1">{{ $prof->courses_taught_count }} groupes</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($prof->subjects as $subject)
                                    <span class="px-2 py-1 rounded bg-indigo-100 text-indigo-700 font-bold text-[9px] uppercase border border-indigo-200">
                                        {{ $subject->subject_name }}
                                    </span>
                                @empty
                                    <span class="text-[10px] text-slate-400 italic font-medium">Aucune matière</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($prof->profile_completed)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px] uppercase border border-emerald-200">
                                    <i class="fa-solid fa-check-circle"></i> Complet
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 font-bold text-[10px] uppercase border border-amber-200">
                                    <i class="fa-solid fa-clock"></i> Incomplet
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg {{ $prof->status === 'active' ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-500' }} font-bold text-[10px] uppercase">
                                {{ $prof->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal(this)" data-prof="{{ json_encode($prof) }}" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs hover:bg-blue-600 hover:text-white transition-all"><i class="fa-solid fa-pen"></i></button>
                                <form action="{{ route('dashboard.admin.destroy', ['type' => 'user', 'id' => $prof->id]) }}" method="POST" onsubmit="return confirm('Supprimer ce professeur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs hover:bg-rose-600 hover:text-white transition-all"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Aucun professeur trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addProfModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl animate-modal-in flex flex-col">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Nouveau Professeur 👨‍🏫</h3>
                    <p class="text-slate-500 text-xs mt-1">Remplissez les informations professionnelles et personnelles.</p>
                </div>
                <button onclick="document.getElementById('addProfModal').classList.add('hidden')" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto p-8 bg-slate-50/50">
                <form action="{{ route('dashboard.admin.professors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Section: Personnel -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-indigo-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-user-circle"></i> Informations Personnelles
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:row-span-2 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-3xl p-4 bg-slate-50 group hover:border-indigo-400 transition-all cursor-pointer relative overflow-hidden">
                                <input type="file" name="profile_photo" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this)">
                                <div id="photoPreview" class="text-center">
                                    <i class="fa-solid fa-camera text-3xl text-slate-300 group-hover:text-indigo-500 mb-2"></i>
                                    <p class="text-[10px] font-bold text-slate-400 group-hover:text-indigo-500 uppercase">Photo de profil</p>
                                </div>
                                <img id="previewImg" class="hidden absolute inset-0 w-full h-full object-cover">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 shadow-sm">Nom de famille</label>
                                <input type="text" name="first_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all" placeholder="Ex: Dupont">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Prénom</label>
                                <input type="text" name="last_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all" placeholder="Ex: Jean">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date de naissance</label>
                                <input type="date" name="dob" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sexe</label>
                                <select name="gender" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all">
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
                                <input type="text" name="phone" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all" placeholder="06 XX XX XX XX">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Adresse complète</label>
                                <input type="text" name="address" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all" placeholder="Rue, Quartier, N°...">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Professionnelle -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-emerald-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-briefcase"></i> Informations Professionnelles
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Code Professeur</label>
                                <input type="text" name="professor_code" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all" placeholder="Ex: PRF-2024-01">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Spécialité</label>
                                <input type="text" name="specialty" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all" placeholder="Ex: Mathématiques Appliquées">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Matières enseignées (Maintenez Ctrl pour choix multiple)</label>
                                <select name="subjects[]" multiple class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all h-24">
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }} ({{ $subject->academicLevel->name ?? '' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Classes assignées (Maintenez Ctrl pour choix multiple)</label>
                                <select name="classes[]" multiple class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all h-24">
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }} ({{ $class->academicLevel->name ?? '' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Bureau / Salle</label>
                                <input type="text" name="office" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all" placeholder="Ex: Bâtiment A - Bureau 102">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date d'embauche</label>
                                <input type="date" name="hire_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Compte -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-rose-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-lock"></i> Compte Utilisateur (Auto)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 shadow-sm">Email professionnel (Optionnel)</label>
                                <input type="email" name="email" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all" placeholder="email@exemple.com">
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-start gap-3">
                                <i class="fa-solid fa-info-circle text-indigo-500 mt-1"></i>
                                <p class="text-[10px] text-slate-500 leading-tight font-medium italic">
                                    Les identifiants seront générés automatiquement : <br>
                                    <span class="font-bold text-indigo-600">Username:</span> prenomnom <br>
                                    <span class="font-bold text-indigo-600">Password:</span> prenomnom@2026
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-white sticky bottom-0 p-6 z-10 border-t border-slate-100 rounded-b-[2rem]">
                        <button type="button" onclick="document.getElementById('addProfModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all">Annuler</button>
                        <button type="submit" class="flex-[2] py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20">Enregistrer le professeur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editProfModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl animate-modal-in flex flex-col">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Modifier un Professeur 👨‍🏫</h3>
                    <p class="text-slate-500 text-xs mt-1">Mettez à jour les informations du professeur.</p>
                </div>
                <button onclick="document.getElementById('editProfModal').classList.add('hidden')" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto p-8 bg-slate-50/50">
                <form id="editProfForm" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Section: Personnel -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-indigo-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-user-circle"></i> Informations Personnelles
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:row-span-2 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-3xl p-4 bg-slate-50 group hover:border-indigo-400 transition-all cursor-pointer relative overflow-hidden">
                                <input type="file" name="profile_photo" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewEditImage(this)">
                                <div id="editPhotoPreview" class="text-center">
                                    <i class="fa-solid fa-camera text-3xl text-slate-300 group-hover:text-indigo-500 mb-2"></i>
                                    <p class="text-[10px] font-bold text-slate-400 group-hover:text-indigo-500 uppercase">Nouvelle photo</p>
                                </div>
                                <img id="editPreviewImg" class="hidden absolute inset-0 w-full h-full object-cover">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 shadow-sm">Nom de famille</label>
                                <input type="text" id="edit_first_name" name="first_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Prénom</label>
                                <input type="text" id="edit_last_name" name="last_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date de naissance</label>
                                <input type="date" id="edit_dob" name="dob" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sexe</label>
                                <select id="edit_gender" name="gender" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all">
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
                                <input type="text" id="edit_phone" name="phone" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Adresse complète</label>
                                <input type="text" id="edit_address" name="address" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Professionnelle -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-emerald-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-briefcase"></i> Informations Professionnelles
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Code Professeur</label>
                                <input type="text" id="edit_professor_code" name="professor_code" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Spécialité</label>
                                <input type="text" id="edit_specialty" name="specialty" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Bureau / Salle</label>
                                <input type="text" id="edit_office" name="office" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Matières enseignées (Maintenez Ctrl pour choix multiple)</label>
                                <select id="edit_subjects" name="subjects[]" multiple class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all h-24">
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }} ({{ $subject->academicLevel->name ?? '' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Classes assignées (Maintenez Ctrl pour choix multiple)</label>
                                <select id="edit_classes" name="classes[]" multiple class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all h-24">
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }} ({{ $class->academicLevel->name ?? '' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date d'embauche</label>
                                <input type="date" id="edit_hire_date" name="hire_date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Compte -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-rose-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-lock"></i> Compte Utilisateur
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email de connexion</label>
                                <input type="email" id="edit_email" name="email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nouveau mot de passe</label>
                                <input type="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-indigo-500/10 font-bold text-sm transition-all" placeholder="Laisser vide pour ne pas changer">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</label>
                                <select id="edit_status" name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                                    <option value="active">Actif</option>
                                    <option value="inactive">Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-white sticky bottom-0 p-6 z-10 border-t border-slate-100 rounded-b-[2rem]">
                        <button type="button" onclick="document.getElementById('editProfModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all">Annuler</button>
                        <button type="submit" class="flex-[2] py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20">Mettre à jour le professeur</button>
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
            let prof = JSON.parse(button.getAttribute('data-prof'));
            document.getElementById('editProfForm').action = "/dashboard/admin/professors/" + prof.id;
            
            // Personal
            document.getElementById('edit_first_name').value = prof.first_name || '';
            document.getElementById('edit_last_name').value = prof.last_name || '';
            document.getElementById('edit_dob').value = prof.dob || '';
            document.getElementById('edit_gender').value = prof.gender || 'M';
            
            // Contact
            document.getElementById('edit_phone').value = prof.phone || '';
            document.getElementById('edit_address').value = prof.address || '';
            
            // Account
            document.getElementById('edit_email').value = prof.email || '';
            document.getElementById('edit_status').value = prof.status || 'active';
            
            // Professional Details
            if (prof.professor_details) {
                document.getElementById('edit_professor_code').value = prof.professor_details.professor_code || '';
                document.getElementById('edit_specialty').value = prof.professor_details.specialty || '';
                document.getElementById('edit_hire_date').value = prof.professor_details.hire_date || '';
                document.getElementById('edit_office').value = prof.professor_details.office || '';
                
                // Pre-select subjects
                let subjectsSelect = document.getElementById('edit_subjects');
                let assignedSubjectIds = prof.subjects.map(s => s.id);
                Array.from(subjectsSelect.options).forEach(option => {
                    option.selected = assignedSubjectIds.includes(parseInt(option.value));
                });

                // Pre-select classes
                let classesSelect = document.getElementById('edit_classes');
                let assignedClassIds = prof.assigned_classes.map(c => c.id);
                Array.from(classesSelect.options).forEach(option => {
                    option.selected = assignedClassIds.includes(parseInt(option.value));
                });
            }
            
            if (prof.profile_photo) {
                document.getElementById('editPreviewImg').src = "/storage/" + prof.profile_photo;
                document.getElementById('editPreviewImg').classList.remove('hidden');
                document.getElementById('editPhotoPreview').classList.add('hidden');
            } else {
                document.getElementById('editPreviewImg').classList.add('hidden');
                document.getElementById('editPhotoPreview').classList.remove('hidden');
            }

            document.getElementById('editProfModal').classList.remove('hidden');
        }
    </script>
@endsection
