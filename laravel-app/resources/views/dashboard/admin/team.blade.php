@extends('layouts.dashboard')

@section('title', 'Gestion de l\'Équipe')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Membres de l'Équipe 👥</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez le personnel affiché sur la page À Propos.</p>
        </div>
        <button onclick="document.getElementById('addTeamModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black text-sm transition-all shadow-xl shadow-blue-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Ajouter un membre
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in text-sm font-bold">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Membre</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Position</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Réseaux</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($members as $member)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $member->photo_path ? asset('storage/' . $member->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($member->name) }}" class="w-10 h-10 rounded-full object-cover border-2 border-slate-100 shadow-sm">
                            <div class="font-bold text-slate-900">{{ $member->name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-black uppercase tracking-tighter">{{ $member->position }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-3 text-slate-400">
                            @if($member->linkedin_url)<a href="{{ $member->linkedin_url }}" target="_blank" class="hover:text-blue-600 transition-colors"><i class="fa-brands fa-linkedin text-lg"></i></a>@else <i class="fa-brands fa-linkedin opacity-20 text-lg"></i> @endif
                            @if($member->twitter_url)<a href="{{ $member->twitter_url }}" target="_blank" class="hover:text-blue-400 transition-colors"><i class="fa-brands fa-twitter text-lg"></i></a>@else <i class="fa-brands fa-twitter opacity-20 text-lg"></i> @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openEditModal(this)" data-member="{{ json_encode($member) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form action="{{ route('dashboard.admin.destroy', ['type' => 'team', 'id' => $member->id]) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce membre ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div id="addTeamModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-xl shadow-2xl border border-white/20 animate-fade-in-up overflow-hidden">
            <div class="p-8 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="text-xl font-black flex items-center gap-3 uppercase tracking-tighter">
                    <i class="fa-solid fa-user-plus text-blue-400"></i> Nouveau Membre
                </h3>
                <button onclick="document.getElementById('addTeamModal').classList.add('hidden')" class="text-white/50 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('dashboard.admin.team.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nom Complet</label>
                        <input type="text" name="name" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Poste / Position</label>
                        <input type="text" name="position" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Ordre d'affichage</label>
                        <input type="number" name="order" value="0" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">LinkedIn URL</label>
                        <input type="text" name="linkedin_url" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Twitter URL</label>
                        <input type="text" name="twitter_url" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Photo du profil</label>
                        <input type="file" name="photo_path" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('addTeamModal').classList.add('hidden')" class="flex-grow px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-widest rounded-2xl transition-all"> Annuler </button>
                    <button type="submit" class="flex-grow px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all shadow-xl shadow-blue-500/20"> Ajouter </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editTeamModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-xl shadow-2xl border border-white/20 animate-fade-in-up overflow-hidden">
            <div class="p-8 bg-blue-600 text-white flex justify-between items-center">
                <h3 class="text-xl font-black flex items-center gap-3 uppercase tracking-tighter">
                    <i class="fa-solid fa-user-pen"></i> Modifier le Membre
                </h3>
                <button onclick="document.getElementById('editTeamModal').classList.add('hidden')" class="text-white/50 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="editTeamForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nom Complet</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Poste / Position</label>
                        <input type="text" name="position" id="edit_position" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Ordre d'affichage</label>
                        <input type="number" name="order" id="edit_order" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">LinkedIn URL</label>
                        <input type="text" name="linkedin_url" id="edit_linkedin" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Twitter URL</label>
                        <input type="text" name="twitter_url" id="edit_twitter" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Changer la Photo</label>
                        <input type="file" name="photo_path" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('editTeamModal').classList.add('hidden')" class="flex-grow px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-widest rounded-2xl transition-all"> Annuler </button>
                    <button type="submit" class="flex-grow px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all shadow-xl shadow-blue-500/20"> Sauvegarder </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(button) {
            const member = JSON.parse(button.dataset.member);
            const form = document.getElementById('editTeamForm');
            form.action = `/dashboard/admin/team/${member.id}`;
            
            document.getElementById('edit_name').value = member.name;
            document.getElementById('edit_position').value = member.position;
            document.getElementById('edit_order').value = member.order || 0;
            document.getElementById('edit_linkedin').value = member.linkedin_url || '';
            document.getElementById('edit_twitter').value = member.twitter_url || '';
            
            document.getElementById('editTeamModal').classList.remove('hidden');
        }
    </script>
@endsection
