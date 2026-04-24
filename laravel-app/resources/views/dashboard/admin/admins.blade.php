@extends('layouts.dashboard')

@section('title', 'Gestion des Administrateurs')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Gestion des Administrateurs 🛡️</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez les comptes administratifs et leurs niveaux d'accès.</p>
        </div>
        <button onclick="document.getElementById('addAdminModal').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-emerald-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Ajouter un administrateur
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Admins Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                        <th class="px-6 py-4">Administrateur</th>
                        <th class="px-6 py-4">Type d'accès</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($admins as $admin)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($admin->profile_photo)
                                    <img src="{{ asset('storage/' . $admin->profile_photo) }}" class="w-10 h-10 rounded-xl shadow-sm object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&background=10b981&color=fff" class="w-10 h-10 rounded-xl shadow-sm">
                                @endif
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">{{ $admin->first_name }} {{ $admin->last_name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $admin->username ?? $admin->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase">
                                @if($admin->admin_type === 'super') Super Admin
                                @elseif($admin->admin_type === 'academic') Académique
                                @elseif($admin->admin_type === 'live_courses') Cours Live
                                @elseif($admin->admin_type === 'content') Contenu Site
                                @else Admin
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 font-medium">
                            {{ $admin->email }}<br>
                            {{ $admin->phone ?? 'Pas de téléphone' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full {{ $admin->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} font-bold text-[10px] uppercase">
                                {{ $admin->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal(this)" data-admin="{{ json_encode($admin) }}" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs hover:bg-emerald-600 hover:text-white transition-all"><i class="fa-solid fa-pen"></i></button>
                                <form action="{{ route('dashboard.admin.destroy', ['type' => 'user', 'id' => $admin->id]) }}" method="POST" onsubmit="return confirm('Supprimer cet administrateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs hover:bg-rose-600 hover:text-white transition-all"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Aucun administrateur trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addAdminModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-3xl max-h-[90vh] overflow-hidden shadow-2xl animate-modal-in flex flex-col">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Nouvel Administrateur 🛡️</h3>
                    <p class="text-slate-500 text-xs mt-1">Créez un compte administratif avec des permissions spécifiques.</p>
                </div>
                <button onclick="document.getElementById('addAdminModal').classList.add('hidden')" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto p-8 bg-slate-50/50">
                <form action="{{ route('dashboard.admin.admins.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Section: Personnel -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-emerald-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-user-circle"></i> Informations Personnelles
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:row-span-2 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-3xl p-4 bg-slate-50 group hover:border-emerald-400 transition-all cursor-pointer relative overflow-hidden">
                                <input type="file" name="profile_photo" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this)">
                                <div id="photoPreview" class="text-center">
                                    <i class="fa-solid fa-camera text-3xl text-slate-300 group-hover:text-emerald-500 mb-2"></i>
                                    <p class="text-[10px] font-bold text-slate-400 group-hover:text-emerald-500 uppercase">Photo de profil</p>
                                </div>
                                <img id="previewImg" class="hidden absolute inset-0 w-full h-full object-cover">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nom de famille</label>
                                <input type="text" name="first_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Prénom</label>
                                <input type="text" name="last_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Téléphone</label>
                                <input type="text" name="phone" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Rôles & Permissions -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-blue-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-user-shield"></i> Rôles et Type d'accès
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-blue-50 transition-all group border-slate-200 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                                <input type="radio" name="admin_type" value="super" required class="w-4 h-4 text-blue-600">
                                <div class="ml-3">
                                    <p class="text-sm font-black text-slate-900">Super Admin</p>
                                    <p class="text-[10px] text-slate-500">Accès complet.</p>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-emerald-50 transition-all group border-slate-200 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50">
                                <input type="radio" name="admin_type" value="academic" class="w-4 h-4 text-emerald-600">
                                <div class="ml-3">
                                    <p class="text-sm font-black text-slate-900">Admin Académique</p>
                                    <p class="text-[10px] text-slate-500">Gestion scolaire.</p>
                                </div>
                            </label>
                        </div>

                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Permissions du rôle</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($permissions as $group => $groupPermissions)
                                <div class="space-y-2">
                                    <p class="text-[9px] font-bold text-blue-500 uppercase">{{ $group }}</p>
                                    @foreach($groupPermissions as $permission)
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                                            <span class="text-xs text-slate-600 group-hover:text-slate-900 transition-colors">{{ $permission->label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
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
                                <input type="email" name="email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-rose-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Username</label>
                                <input type="text" name="username" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-rose-500/10 font-bold text-sm transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mot de passe</label>
                                <input type="password" name="password" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-rose-500/10 font-bold text-sm transition-all" placeholder="Minimum 8 caractères">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-white sticky bottom-0 p-6 z-10 border-t border-slate-100 rounded-b-[2rem]">
                        <button type="button" onclick="document.getElementById('addAdminModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all">Annuler</button>
                        <button type="submit" class="flex-[2] py-4 bg-emerald-600 text-white rounded-2xl font-black text-sm hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-500/20">Enregistrer l'administrateur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editAdminModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-3xl max-h-[90vh] overflow-hidden shadow-2xl animate-modal-in flex flex-col">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Modifier l'Administrateur 🛡️</h3>
                    <p class="text-slate-500 text-xs mt-1">Mettez à jour le compte et les niveaux d'accès.</p>
                </div>
                <button onclick="document.getElementById('editAdminModal').classList.add('hidden')" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="overflow-y-auto p-8 bg-slate-50/50">
                <form id="editAdminForm" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Section: Personnel -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-emerald-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-user-circle"></i> Informations Personnelles
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:row-span-2 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-3xl p-4 bg-slate-50 group hover:border-emerald-400 transition-all cursor-pointer relative overflow-hidden">
                                <input type="file" name="profile_photo" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewEditImage(this)">
                                <div id="editPhotoPreview" class="text-center">
                                    <i class="fa-solid fa-camera text-3xl text-slate-300 group-hover:text-emerald-500 mb-2"></i>
                                    <p class="text-[10px] font-bold text-slate-400 group-hover:text-emerald-500 uppercase">Nouvelle photo</p>
                                </div>
                                <img id="editPreviewImg" class="hidden absolute inset-0 w-full h-full object-cover">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 shadow-sm">Nom de famille</label>
                                <input type="text" id="edit_first_name" name="first_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Prénom</label>
                                <input type="text" id="edit_last_name" name="last_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Téléphone</label>
                                <input type="text" id="edit_phone" name="phone" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-emerald-500/10 font-bold text-sm transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Rôles & Permissions -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <h4 class="text-sm font-black text-blue-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-user-shield"></i> Rôles et Type d'accès
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-blue-50 transition-all group border-slate-200 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                                <input type="radio" id="edit_admin_type_super" name="admin_type" value="super" required class="w-4 h-4 text-blue-600">
                                <div class="ml-3">
                                    <p class="text-sm font-black text-slate-900">Super Admin</p>
                                    <p class="text-[10px] text-slate-500">Accès complet.</p>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 border rounded-2xl cursor-pointer hover:bg-emerald-50 transition-all group border-slate-200 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50">
                                <input type="radio" id="edit_admin_type_academic" name="admin_type" value="academic" class="w-4 h-4 text-emerald-600">
                                <div class="ml-3">
                                    <p class="text-sm font-black text-slate-900">Admin Académique</p>
                                    <p class="text-[10px] text-slate-500">Gestion scolaire.</p>
                                </div>
                            </label>
                        </div>

                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Permissions du rôle</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($permissions as $group => $groupPermissions)
                                <div class="space-y-2">
                                    <p class="text-[9px] font-bold text-blue-500 uppercase">{{ $group }}</p>
                                    @foreach($groupPermissions as $permission)
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="edit-permission-checkbox w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                                            <span class="text-xs text-slate-600 group-hover:text-slate-900 transition-colors">{{ $permission->label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
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
                                <input type="email" id="edit_email" name="email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-rose-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Username</label>
                                <input type="text" id="edit_username" name="username" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-rose-500/10 font-bold text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nouveau mot de passe</label>
                                <input type="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-rose-500/10 font-bold text-sm transition-all" placeholder="Laisser vide pour ne pas changer">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</label>
                                <select id="edit_status" name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm transition-all">
                                    <option value="active">Actif</option>
                                    <option value="inactive">Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-white sticky bottom-0 p-6 z-10 border-t border-slate-100 rounded-b-[2rem]">
                        <button type="button" onclick="document.getElementById('editAdminModal').classList.add('hidden')" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all">Annuler</button>
                        <button type="submit" class="flex-[2] py-4 bg-emerald-600 text-white rounded-2xl font-black text-sm hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-500/20">Mettre à jour l'administrateur</button>
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
            let admin = JSON.parse(button.getAttribute('data-admin'));
            document.getElementById('editAdminForm').action = "/dashboard/admin/admins/" + admin.id;
            
            // Personal
            document.getElementById('edit_first_name').value = admin.first_name || '';
            document.getElementById('edit_last_name').value = admin.last_name || '';
            document.getElementById('edit_phone').value = admin.phone || '';
            
            // Account
            document.getElementById('edit_email').value = admin.email || '';
            document.getElementById('edit_username').value = admin.username || '';
            document.getElementById('edit_status').value = admin.status || 'active';
            
            // Set radio button for admin type
            let type = admin.admin_type || 'super';
            let radio = document.getElementById('edit_admin_type_' + type);
            if (radio) radio.checked = true;

            // Pre-select permissions
            let permissionIds = admin.role && admin.role.permissions ? admin.role.permissions.map(p => p.id) : [];
            document.querySelectorAll('.edit-permission-checkbox').forEach(checkbox => {
                checkbox.checked = permissionIds.includes(parseInt(checkbox.value));
            });
            
            if (admin.profile_photo) {
                document.getElementById('editPreviewImg').src = "/storage/" + admin.profile_photo;
                document.getElementById('editPreviewImg').classList.remove('hidden');
                document.getElementById('editPhotoPreview').classList.add('hidden');
            } else {
                document.getElementById('editPreviewImg').classList.add('hidden');
                document.getElementById('editPhotoPreview').classList.remove('hidden');
            }

            document.getElementById('editAdminModal').classList.remove('hidden');
        }
    </script>
@endsection
