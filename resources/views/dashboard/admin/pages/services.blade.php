@extends('layouts.dashboard')

@section('title', 'Modifier Page Services')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('dashboard.admin.pages') }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors shadow-sm">
        <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Modifier Page Services 💼</h1>
        <p class="text-slate-500 text-sm mt-1">Gérez l'entête, les images et accédez à la gestion des services.</p>
    </div>
    <a href="{{ route('public.services') }}" target="_blank" class="ml-auto flex items-center gap-2 px-5 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
        <i class="fa-solid fa-eye"></i> Voir la page
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 text-sm font-bold">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Header Content Editor -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50 bg-slate-50/60">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Entête de Page</h3>
            </div>
            <form action="{{ route('dashboard.admin.pages.section.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="id" value="{{ $page ? $page->id : '' }}">
                <input type="hidden" name="clear_image" id="clear_image_flag" value="0">
                @if(!$page)
                <div class="text-amber-600 text-xs font-bold bg-amber-50 rounded-xl p-3 border border-amber-100">
                    ⚠ Aucune section d'entête trouvée. Veuillez d'abord créer un enregistrement "services / header" depuis la base de données.
                </div>
                @else
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Titre</label>
                    <input type="text" name="title" id="sec_title" value="{{ $page->title }}" oninput="document.getElementById('prev_title').textContent = this.value"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="content" rows="4" id="sec_content" oninput="document.getElementById('prev_content').textContent = this.value"
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm leading-relaxed">{{ $page->content }}</textarea>
                </div>

                {{-- Image Manager --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Image d'Entête</label>
                    @if($page->image)
                    <div class="relative group rounded-xl overflow-hidden border border-slate-200 shadow-sm" id="imgWrap">
                        <img src="{{ asset('storage/' . $page->image) }}" id="imgPreview" class="w-full h-32 object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-3">
                            <label class="cursor-pointer px-3 py-1.5 bg-white text-blue-600 text-xs font-bold rounded-lg shadow">
                                <i class="fa-solid fa-upload mr-1"></i> Remplacer
                                <input type="file" name="image" accept="image/*" class="hidden" onchange="previewFile(this)">
                            </label>
                            <button type="button" onclick="clearImg()" class="px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg shadow">
                                <i class="fa-solid fa-trash mr-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                    @else
                    <label id="uploadZone" class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-xl py-6 gap-2 text-slate-300 hover:text-blue-400 transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                        <span class="text-xs font-bold">Ajouter une image d'entête</span>
                        <input type="file" name="image" accept="image/*" class="hidden" onchange="previewFile(this)">
                    </label>
                    @endif
                </div>

                {{-- Live Preview --}}
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Aperçu en direct</p>
                    <h4 id="prev_title" class="text-sm font-bold text-slate-800">{{ $page->title }}</h4>
                    <p id="prev_content" class="text-slate-500 text-xs leading-relaxed mt-1">{{ Str::limit($page->content, 100) }}</p>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all shadow shadow-blue-500/20">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Enregistrer
                </button>
                @endif
            </form>
        </div>
    </div>

    <!-- Services Management Shortcut -->
    <div class="lg:col-span-2">
        <div class="bg-slate-900 rounded-[2rem] p-8 text-white h-full flex flex-col justify-between">
            <div>
                <h3 class="text-blue-400 text-xs font-black uppercase tracking-widest mb-6">Management des Offres</h3>
                <h4 class="text-3xl font-black mb-4">Gestion des Services</h4>
                <p class="text-slate-400 leading-relaxed mb-8">
                    Ajoutez, modifiez ou supprimez les cartes de services qui apparaissent sur la page publique. Chaque service peut avoir une icône, une image, un titre et une description.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('dashboard.admin.services') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all text-center">
                    <i class="fa-solid fa-list-check mr-2"></i> Gérer le catalogue de services
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewFile(input) {
    const reader = new FileReader();
    reader.onload = e => {
        let prev = document.getElementById('imgPreview');
        if (!prev) {
            document.getElementById('uploadZone').innerHTML = '<img src="'+e.target.result+'" class="w-full h-32 object-cover rounded-xl">';
        } else {
            prev.src = e.target.result;
        }
    };
    reader.readAsDataURL(input.files[0]);
}
function clearImg() {
    document.getElementById('clear_image_flag').value = '1';
    document.getElementById('imgWrap').innerHTML = '<div class="bg-slate-50 rounded-xl p-3 text-center text-xs text-slate-400 font-bold"><i class="fa-solid fa-trash-can mr-1"></i> Image supprimée (sera effacée à la sauvegarde)</div>';
}
</script>
@endsection
