@extends('layouts.dashboard')

@section('title', 'Rôles & Permissions')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Rôles & Permissions 🛡️</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez les niveaux d'accès et les permissions pour chaque rôle.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Role Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
        @php
            $roleColors = [
                'admin'     => ['bg' => 'blue',    'icon' => 'fa-user-shield',   'label' => 'Administrateur', 'badge' => 'Accès Total'],
                'professor' => ['bg' => 'amber',   'icon' => 'fa-chalkboard-user','label' => 'Professeur',     'badge' => 'Accès Partiel'],
                'student'   => ['bg' => 'emerald', 'icon' => 'fa-user-graduate', 'label' => 'Étudiant',        'badge' => 'Accès Minimal'],
            ];
            $cfg   = $roleColors[$role->name] ?? ['bg' => 'slate', 'icon' => 'fa-user', 'label' => $role->name, 'badge' => 'Personnalisé'];
            $count = $role->permissions->count();
            $totalPermissions = \App\Models\Permission::count();
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">

            {{-- Card Header --}}
            <div class="p-6 border-b border-slate-100 flex items-center gap-4">
                <div class="w-14 h-14 bg-{{ $cfg['bg'] }}-50 text-{{ $cfg['bg'] }}-600 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid {{ $cfg['icon'] }}"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-black text-slate-900">{{ $cfg['label'] }}</h3>
                    <span class="px-2 py-0.5 rounded-full bg-{{ $cfg['bg'] }}-50 text-{{ $cfg['bg'] }}-600 text-[10px] font-black uppercase tracking-widest">{{ $cfg['badge'] }}</span>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-black text-slate-900">{{ $count }}</p>
                    <p class="text-[10px] text-slate-400 font-bold">/ {{ $totalPermissions }} perms</p>
                </div>
            </div>

            {{-- Current Permissions List --}}
            <div class="p-6 flex-1">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Permissions actives</p>
                <div class="space-y-2">
                    @forelse($role->permissions->take(5) as $perm)
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                        <span class="text-xs text-slate-600 font-medium">{{ $perm->label }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic">Aucune permission assignée.</p>
                    @endforelse
                    @if($count > 5)
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-ellipsis text-slate-300 text-xs"></i>
                        <span class="text-xs text-slate-400 italic">+ {{ $count - 5 }} autres permissions</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Action Button --}}
            <div class="px-6 pb-6">
                <button
                    onclick="openPermissionModal({{ $role->id }}, '{{ $cfg['label'] }}', '{{ $cfg['bg'] }}')"
                    class="w-full py-3 bg-{{ $cfg['bg'] }}-600 text-white rounded-xl font-black text-sm hover:bg-{{ $cfg['bg'] }}-700 transition-all shadow-lg shadow-{{ $cfg['bg'] }}-500/20 flex items-center justify-center gap-2"
                >
                    <i class="fa-solid fa-sliders"></i> Modifier les Permissions
                </button>
            </div>
        </div>
        @endforeach
    </div>

    {{-- All Permissions Overview Table --}}
    <div class="mt-10">
        <h2 class="text-lg font-black text-slate-900 mb-4">Vue d'ensemble des permissions</h2>
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4">Permission</th>
                            <th class="px-6 py-4">Groupe</th>
                            @foreach($roles as $role)
                            @php $cfg2 = ['admin'=>'Administrateur','professor'=>'Professeur','student'=>'Étudiant'][$role->name] ?? $role->name; @endphp
                            <th class="px-6 py-4 text-center">{{ $cfg2 }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($permissions as $group => $groupPerms)
                        <tr class="bg-slate-50/50">
                            <td colspan="{{ 2 + $roles->count() }}" class="px-6 py-2 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $group }}</td>
                        </tr>
                        @foreach($groupPerms as $perm)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3 text-sm font-bold text-slate-900">{{ $perm->label }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-bold">{{ $perm->group }}</span>
                            </td>
                            @foreach($roles as $role)
                            <td class="px-6 py-3 text-center">
                                @if($role->permissions->contains($perm))
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                @else
                                    <i class="fa-solid fa-circle-xmark text-slate-200"></i>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PERMISSION MODAL --}}
    <div id="permissionModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col">

            {{-- Modal Header --}}
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight" id="modalTitle">Modifier les Permissions</h3>
                    <p class="text-slate-500 text-xs mt-1">Activez ou désactivez les permissions pour ce rôle.</p>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Modal Body: Dynamic Permission Toggles --}}
            <div class="overflow-y-auto p-8 bg-slate-50/50 flex-1">
                <div id="modalPermissions" class="space-y-6">
                    @foreach($permissions as $group => $groupPerms)
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">{{ $group }}</p>
                        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden divide-y divide-slate-100">
                            @foreach($groupPerms as $perm)
                            <label class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 transition-all permission-row" data-perm-id="{{ $perm->id }}">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $perm->label }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $perm->key }}</p>
                                </div>
                                <div class="relative">
                                    <input
                                        type="checkbox"
                                        class="sr-only perm-checkbox"
                                        data-perm-id="{{ $perm->id }}"
                                    >
                                    <div class="toggle-track w-11 h-6 bg-slate-200 rounded-full transition-colors duration-200 flex items-center px-0.5">
                                        <div class="toggle-thumb w-5 h-5 bg-white rounded-full shadow-sm transition-transform duration-200"></div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-6 border-t border-slate-100 bg-white flex items-center gap-4">
                <div id="saveStatus" class="flex-1 text-sm font-bold text-slate-400"></div>
                <button onclick="closeModal()" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-black text-sm hover:bg-slate-200 transition-all">Annuler</button>
                <button onclick="savePermissions()" id="saveBtn" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-black text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>

    <style>
        .perm-checkbox:checked + .toggle-track { background-color: #2563eb; }
        .perm-checkbox:checked + .toggle-track .toggle-thumb { transform: translateX(20px); }
    </style>

    <script>
        let currentRoleId = null;

        // Map: role_id => array of permission ids
        const rolePermissionsMap = {
            @foreach($roles as $role)
            {{ $role->id }}: [{{ $role->permissions->pluck('id')->implode(',') }}],
            @endforeach
        };

        function openPermissionModal(roleId, roleName, color) {
            currentRoleId = roleId;
            document.getElementById('modalTitle').textContent = 'Permissions — ' + roleName;

            const activePerms = rolePermissionsMap[roleId] || [];

            // Set all checkboxes
            document.querySelectorAll('.perm-checkbox').forEach(cb => {
                const permId = parseInt(cb.dataset.permId);
                const checked = activePerms.includes(permId);
                cb.checked = checked;
                // Sync toggle visuals
                const track = cb.nextElementSibling;
                if (checked) {
                    track.classList.remove('bg-slate-200');
                    track.classList.add('bg-blue-600');
                    track.querySelector('.toggle-thumb').style.transform = 'translateX(20px)';
                } else {
                    track.classList.add('bg-slate-200');
                    track.classList.remove('bg-blue-600');
                    track.querySelector('.toggle-thumb').style.transform = '';
                }
            });

            document.getElementById('saveStatus').textContent = '';
            document.getElementById('permissionModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('permissionModal').classList.add('hidden');
        }

        // Live toggle visual feedback
        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const track = this.nextElementSibling;
                if (this.checked) {
                    track.classList.remove('bg-slate-200');
                    track.classList.add('bg-blue-600');
                    track.querySelector('.toggle-thumb').style.transform = 'translateX(20px)';
                } else {
                    track.classList.add('bg-slate-200');
                    track.classList.remove('bg-blue-600');
                    track.querySelector('.toggle-thumb').style.transform = '';
                }
            });
        });

        function savePermissions() {
            const btn = document.getElementById('saveBtn');
            const status = document.getElementById('saveStatus');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Enregistrement...';

            const checkedIds = [...document.querySelectorAll('.perm-checkbox:checked')].map(cb => cb.dataset.permId);

            fetch(`/dashboard/admin/roles/${currentRoleId}/permissions`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ permissions: checkedIds })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    status.innerHTML = '<span class="text-emerald-600"><i class="fa-solid fa-circle-check"></i> ' + data.message + '</span>';
                    // Update local map
                    rolePermissionsMap[currentRoleId] = checkedIds.map(Number);
                    btn.innerHTML = '<i class="fa-solid fa-check"></i> Enregistré !';
                    btn.classList.replace('bg-blue-600','bg-emerald-600');
                    btn.classList.replace('hover:bg-blue-700','hover:bg-emerald-700');
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Enregistrer';
                        btn.disabled = false;
                        btn.classList.replace('bg-emerald-600','bg-blue-600');
                        btn.classList.replace('hover:bg-emerald-700','hover:bg-blue-700');
                        // Refresh page to update overview table
                        location.reload();
                    }, 1200);
                }
            })
            .catch(() => {
                status.innerHTML = '<span class="text-rose-600"><i class="fa-solid fa-circle-xmark"></i> Une erreur s\'est produite.</span>';
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Enregistrer';
            });
        }

        // Close modal on backdrop click
        document.getElementById('permissionModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>

@endsection
