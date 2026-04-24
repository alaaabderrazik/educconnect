@extends('layouts.dashboard')

@section('title', 'Modifier Page À Propos')
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
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Modifier Page À Propos ℹ️</h1>
        <p class="text-slate-500 text-sm mt-1">Gérez les sections, textes et images de la page "À Propos".</p>
    </div>
    <a href="{{ route('public.about') }}" target="_blank" class="ml-auto flex items-center gap-2 px-5 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl font-bold text-xs uppercase tracking-widest transition-all">
        <i class="fa-solid fa-eye"></i> Voir la page
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 text-sm font-bold">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

<div class="space-y-6">
    @foreach($contents as $section => $item)
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden hover:border-blue-200 transition-colors">
        <div class="p-6 border-b border-slate-50 bg-slate-50/60 flex items-center justify-between">
            <div>
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Section: {{ $section }}</span>
                <h3 class="text-lg font-bold text-slate-900 mt-0.5">{{ $item->title ?? 'Sans titre' }}</h3>
            </div>
            <button onclick="openEditModal({{ json_encode($item->toArray()) }})"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs uppercase tracking-widest transition-all shadow">
                <i class="fa-solid fa-pen mr-1"></i> Modifier
            </button>
        </div>
        <div class="p-6 flex gap-6">
            <div class="flex-shrink-0">
                @if($item->image)
                <div class="relative group w-36 h-28 rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                    <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-2">
                        <label class="cursor-pointer w-8 h-8 bg-white rounded-full flex items-center justify-center text-blue-600 text-xs" title="Remplacer">
                            <i class="fa-solid fa-upload"></i>
                            <input type="file" accept="image/*" class="hidden quick-upload" data-page-id="{{ $item->id }}">
                        </label>
                        <button onclick="deleteImage({{ $item->id }})" class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-xs" title="Supprimer">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                @else
                <label class="cursor-pointer w-36 h-28 rounded-xl border-2 border-dashed border-slate-200 hover:border-blue-400 flex flex-col items-center justify-center gap-2 text-slate-300 hover:text-blue-400 transition-colors">
                    <i class="fa-solid fa-image text-2xl"></i>
                    <span class="text-xs font-bold">Ajouter image</span>
                    <input type="file" accept="image/*" class="hidden quick-upload" data-page-id="{{ $item->id }}">
                </label>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-slate-500 text-sm leading-relaxed line-clamp-4">{{ Str::limit($item->content, 250) }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Edit Modal --}}
<div id="editSectionModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        <div class="p-6 bg-slate-900 text-white flex justify-between items-center flex-shrink-0">
            <h3 class="text-lg font-black flex items-center gap-3 uppercase tracking-tighter">
                <i class="fa-solid fa-pen text-blue-400"></i> Modifier: <span id="sectionNameDisplay" class="text-blue-400"></span>
            </h3>
            <button onclick="closeModal()" class="text-white/50 hover:text-white transition-colors w-8 h-8 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="{{ route('dashboard.admin.pages.section.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-5 overflow-y-auto flex-1">
            @csrf
            <input type="hidden" name="id" id="section_id">
            <input type="hidden" name="clear_image" id="clear_image_flag" value="0">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Titre</label>
                <input type="text" name="title" id="section_title" oninput="updatePreview()"
                       class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Contenu</label>
                <textarea name="content" id="section_content" rows="6" oninput="updatePreview()"
                          class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm leading-relaxed"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Image</label>
                <div id="existingImageWrap" class="hidden relative group rounded-xl overflow-hidden border border-slate-200 shadow-sm max-w-xs">
                    <img src="" id="existingImagePreview" class="w-full h-40 object-cover">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-3">
                        <label class="cursor-pointer px-3 py-1.5 bg-white text-blue-600 text-xs font-bold rounded-lg shadow">
                            <i class="fa-solid fa-upload mr-1"></i> Remplacer
                            <input type="file" name="image" accept="image/*" class="hidden" onchange="previewNewFile(this)">
                        </label>
                        <button type="button" onclick="clearImage()" class="px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg shadow">
                            <i class="fa-solid fa-trash mr-1"></i> Supprimer
                        </button>
                    </div>
                </div>
                <label id="uploadZone" class="hidden cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-xl py-8 gap-2 text-slate-300 hover:text-blue-400 transition-colors">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl"></i>
                    <span class="text-sm font-bold">Cliquer pour choisir une image</span>
                    <span class="text-xs opacity-70">PNG, JPG, WEBP — max 5MB</span>
                    <input type="file" name="image" accept="image/*" class="hidden" onchange="previewNewFile(this)">
                </label>
            </div>
            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Aperçu en direct</p>
                <h4 id="preview_title" class="text-base font-bold text-slate-800 mb-1"></h4>
                <p id="preview_content" class="text-slate-500 text-sm leading-relaxed"></p>
            </div>
            <div class="flex gap-4 pt-2">
                <button type="button" onclick="closeModal()" class="flex-grow px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-widest rounded-xl transition-all"> Annuler </button>
                <button type="submit" class="flex-grow px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all shadow shadow-blue-500/20">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const UPLOAD_URL = "{{ route('dashboard.admin.pages.image.upload') }}";
const DELETE_URL = "{{ route('dashboard.admin.pages.image.delete') }}";
const CSRF = "{{ csrf_token() }}";

document.querySelectorAll('.quick-upload').forEach(input => {
    input.addEventListener('change', function() {
        const fd = new FormData();
        fd.append('image', this.files[0]);
        fd.append('page_id', this.dataset.pageId);
        fd.append('_token', CSRF);
        fetch(UPLOAD_URL, { method: 'POST', body: fd }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
    });
});

function deleteImage(pageId) {
    if (!confirm('Supprimer cette image ?')) return;
    fetch(DELETE_URL, { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF }, body: JSON.stringify({ page_id: pageId }) })
        .then(r => r.json()).then(d => { if (d.success) location.reload(); });
}

function openEditModal(item) {
    document.getElementById('section_id').value = item.id;
    document.getElementById('section_title').value = item.title || '';
    document.getElementById('section_content').value = item.content || '';
    document.getElementById('sectionNameDisplay').innerText = item.section?.toUpperCase() || '';
    document.getElementById('clear_image_flag').value = '0';
    updatePreview();
    const ew = document.getElementById('existingImageWrap');
    const uz = document.getElementById('uploadZone');
    if (item.image) { document.getElementById('existingImagePreview').src = '/storage/' + item.image; ew.classList.remove('hidden'); uz.classList.add('hidden'); }
    else { ew.classList.add('hidden'); uz.classList.remove('hidden'); }
    document.getElementById('editSectionModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModal() { document.getElementById('editSectionModal').classList.add('hidden'); document.body.style.overflow = ''; }
function clearImage() { document.getElementById('clear_image_flag').value='1'; document.getElementById('existingImageWrap').classList.add('hidden'); document.getElementById('uploadZone').classList.remove('hidden'); }
function previewNewFile(input) {
    const reader = new FileReader();
    reader.onload = e => { document.getElementById('existingImagePreview').src = e.target.result; document.getElementById('existingImageWrap').classList.remove('hidden'); document.getElementById('uploadZone').classList.add('hidden'); document.getElementById('clear_image_flag').value='0'; };
    reader.readAsDataURL(input.files[0]);
}
function updatePreview() { document.getElementById('preview_title').textContent = document.getElementById('section_title').value; document.getElementById('preview_content').textContent = document.getElementById('section_content').value; }
document.getElementById('editSectionModal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
</script>
@endsection
