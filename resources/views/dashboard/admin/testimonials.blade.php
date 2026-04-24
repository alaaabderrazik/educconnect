@extends('layouts.dashboard')

@section('title', 'Témoignages')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Témoignages Étudiants ⭐</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez les avis affichés sur la page d'accueil.</p>
        </div>
        <button onclick="document.getElementById('addTestModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black text-sm transition-all shadow-xl shadow-blue-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nouveau Témoignage
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in text-sm font-bold">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($testimonials as $test)
        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm flex flex-col hover:border-blue-200 transition-colors group">
            <div class="flex justify-between items-start mb-6">
                <div class="flex gap-1 text-amber-400">
                    @for($i=0; $i<$test->stars; $i++)<i class="fa-solid fa-star text-[10px]"></i>@endfor
                </div>
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="openEditModal(this)" data-test="{{ json_encode($test) }}" class="text-slate-400 hover:text-blue-600 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                    <form action="{{ route('dashboard.admin.destroy', ['type' => 'testimonial', 'id' => $test->id]) }}" method="POST" onsubmit="return confirm('Supprimer ce témoignage ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <p class="text-slate-600 text-sm leading-relaxed italic mb-8 flex-grow">"{{ $test->content }}"</p>
            
            <div class="flex items-center justify-between mt-auto pt-6 border-t border-slate-50">
                <div class="flex items-center gap-3">
                    <img src="{{ $test->photo_path ? asset('storage/' . $test->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($test->name) }}" class="w-10 h-10 rounded-full border border-slate-100 object-cover">
                    <div>
                        <div class="font-bold text-slate-900 text-sm">{{ $test->name }}</div>
                        <div class="text-slate-400 text-[10px] font-black uppercase tracking-widest">{{ $test->position }}</div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400 italic bg-white rounded-3xl border border-slate-100 shadow-sm">
            Aucun témoignage configuré.
        </div>
        @endforelse
    </div>

    <!-- Add Modal -->
    <div id="addTestModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-white/20 animate-fade-in-up overflow-hidden">
            <div class="p-8 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="text-xl font-black flex items-center gap-3 uppercase tracking-tighter">
                    <i class="fa-solid fa-star text-blue-400"></i> Nouveau Témoignage
                </h3>
                <button onclick="document.getElementById('addTestModal').classList.add('hidden')" class="text-white/50 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('dashboard.admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nom</label>
                            <input type="text" name="name" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Rôle/Établissement</label>
                            <input type="text" name="position" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Témoignage</label>
                        <textarea name="content" required rows="4" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm leading-relaxed transition-all"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Évaluation (Étoiles)</label>
                            <select name="stars" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                                <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                <option value="3">⭐⭐⭐ (3/5)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Photo</label>
                            <input type="file" name="photo_path" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('addTestModal').classList.add('hidden')" class="flex-grow px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-widest rounded-2xl transition-all"> Annuler </button>
                    <button type="submit" class="flex-grow px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all shadow-xl shadow-blue-500/20"> Ajouter </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editTestModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-white/20 animate-fade-in-up overflow-hidden">
            <div class="p-8 bg-blue-600 text-white flex justify-between items-center">
                <h3 class="text-xl font-black flex items-center gap-3 uppercase tracking-tighter">
                    <i class="fa-solid fa-pen"></i> Modifier le Témoignage
                </h3>
                <button onclick="document.getElementById('editTestModal').classList.add('hidden')" class="text-white/50 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="editTestForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nom</label>
                            <input type="text" name="name" id="edit_name" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Rôle/Établissement</label>
                            <input type="text" name="position" id="edit_position" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Témoignage</label>
                        <textarea name="content" id="edit_content" required rows="4" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm leading-relaxed transition-all"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Évaluation (Étoiles)</label>
                            <select name="stars" id="edit_stars" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                                <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                <option value="3">⭐⭐⭐ (3/5)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Changer la Photo</label>
                            <input type="file" name="photo_path" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('editTestModal').classList.add('hidden')" class="flex-grow px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-widest rounded-2xl transition-all"> Annuler </button>
                    <button type="submit" class="flex-grow px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all shadow-xl shadow-blue-500/20"> Sauvegarder </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(button) {
            const test = JSON.parse(button.dataset.test);
            const form = document.getElementById('editTestForm');
            form.action = `/dashboard/admin/testimonials/${test.id}`;
            
            document.getElementById('edit_name').value = test.name;
            document.getElementById('edit_position').value = test.position;
            document.getElementById('edit_content').value = test.content;
            document.getElementById('edit_stars').value = test.stars;
            
            document.getElementById('editTestModal').classList.remove('hidden');
        }
    </script>
@endsection
