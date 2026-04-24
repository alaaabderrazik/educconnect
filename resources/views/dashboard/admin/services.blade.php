@extends('layouts.dashboard')

@section('title', 'Gestion des Services')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Services Proposés 🛠️</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez les cartes de services affichées sur le site.</p>
        </div>
        <button onclick="document.getElementById('addServiceModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nouveau Service
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in text-sm font-bold">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($services as $service)
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-md transition-all group relative flex flex-col">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i class="fa-solid {{ $service->icon }} text-2xl"></i>
                </div>
                @if($service->image)
                    <img src="{{ asset('storage/' . $service->image) }}" class="w-14 h-14 rounded-2xl object-cover border border-slate-100 shadow-sm">
                @endif
            </div>
            
            <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $service->title }}</h3>
            <p class="text-sm text-slate-500 leading-relaxed mb-4 flex-grow">{{ $service->description }}</p>
            
            <div class="space-y-2 mb-6">
                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    <i class="fa-solid fa-graduation-cap text-blue-500"></i>
                    {{ $service->related_course ?? 'N/A' }}
                </div>
                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    <i class="fa-solid fa-user-tie text-emerald-500"></i>
                    {{ $service->professor_name ?? 'N/A' }}
                </div>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                <span class="px-2 py-0.5 rounded {{ $service->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} text-[10px] font-black uppercase">
                    {{ $service->is_active ? 'Actif' : 'Inactif' }}
                </span>
                <div class="flex gap-2">
                    <button onclick="openEditModal(this)" data-service="{{ json_encode($service) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <form action="{{ route('dashboard.admin.destroy', ['type' => 'service', 'id' => $service->id]) }}" method="POST" onsubmit="return confirm('Supprimer ce service ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-600 hover:text-white transition-all flex items-center justify-center">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400 italic bg-white rounded-3xl border border-slate-100 shadow-sm">
            Aucun service configuré.
        </div>
        @endforelse
    </div>

    <!-- Add Modal -->
    <div id="addServiceModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-2xl shadow-2xl animate-modal-in overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Nouveau Service 🛠️</h2>
                <button onclick="document.getElementById('addServiceModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('dashboard.admin.services.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Titre du Service</label>
                        <input type="text" name="title" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-700 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Icon FontAwesome (ex: fa-laptop)</label>
                        <input type="text" name="icon" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-700 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Description</label>
                        <textarea name="description" rows="3" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-700 transition-all"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cours Relatif</label>
                        <input type="text" name="related_course" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-700 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nom du Professeur</label>
                        <input type="text" name="professor_name" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-700 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Image de Couverture</label>
                        <input type="file" name="image" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none font-medium text-slate-700 transition-all">
                    </div>
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-blue-500/20">
                        Créer le Service
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editServiceModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[2rem] w-full max-w-2xl shadow-2xl animate-modal-in overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Modifier le Service 🛠️</h2>
                <button onclick="document.getElementById('editServiceModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form id="editServiceForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Titre du Service</label>
                        <input type="text" name="title" id="edit_title" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-700 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Icon FontAwesome</label>
                        <input type="text" name="icon" id="edit_icon" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-700 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Description</label>
                        <textarea name="description" id="edit_description" rows="3" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-700 transition-all"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cours Relatif</label>
                        <input type="text" name="related_course" id="edit_related_course" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-700 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nom du Professeur</label>
                        <input type="text" name="professor_name" id="edit_professor_name" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 outline-none font-medium text-slate-700 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Changer l'Image</label>
                        <input type="file" name="image" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none font-medium text-slate-700 transition-all">
                    </div>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-5 h-5 rounded border-slate-200 text-blue-600 focus:ring-blue-500/10">
                            <span class="text-xs font-bold text-slate-700">Service Actif</span>
                        </label>
                    </div>
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-blue-500/20">
                        Sauvegarder les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(button) {
            const service = JSON.parse(button.dataset.service);
            const form = document.getElementById('editServiceForm');
            form.action = `/dashboard/admin/services/${service.id}`;
            
            document.getElementById('edit_title').value = service.title;
            document.getElementById('edit_icon').value = service.icon;
            document.getElementById('edit_description').value = service.description;
            document.getElementById('edit_related_course').value = service.related_course || '';
            document.getElementById('edit_professor_name').value = service.professor_name || '';
            document.getElementById('edit_is_active').checked = service.is_active;
            
            document.getElementById('editServiceModal').classList.remove('hidden');
        }
    </script>
@endsection
