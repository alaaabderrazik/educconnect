@extends('layouts.dashboard')

@section('title', 'Messagerie')

@section('sidebar_menu')
    @if(auth()->user()->hasRole('admin'))
        @include('dashboard.admin.sidebar')
    @elseif(auth()->user()->hasRole('professor'))
        @include('dashboard.professor.sidebar')
    @else
        @include('dashboard.student.sidebar')
    @endif
@endsection

@section('content')
<div class="h-[calc(100vh-140px)] flex bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <!-- Conversations List -->
    <div class="w-1/3 border-r border-slate-100 flex flex-col bg-slate-50">
        <div class="p-4 border-b border-slate-200 bg-white flex justify-between items-center gap-2">
            <h2 class="font-bold text-lg text-slate-900">Discussions</h2>
            <div class="flex gap-2">
                <button onclick="document.getElementById('modal-private').classList.remove('hidden')" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold rounded-lg shadow-sm whitespace-nowrap">
                    <i class="fa-solid fa-user-plus mr-1"></i> Nouveau
                </button>
                @if(auth()->user()->hasRole('professor') || auth()->user()->hasRole('admin'))
                    <button onclick="document.getElementById('modal-group').classList.remove('hidden')" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold rounded-lg shadow-sm whitespace-nowrap">
                        <i class="fa-solid fa-bullhorn mr-1"></i> Diffusion
                    </button>
                @endif
            </div>
        </div>
        <div class="overflow-y-auto flex-1">
            @forelse($conversations as $conv)
                @php
                    $isUnread = $conv->latestMessage && $conv->latestMessage->created_at > ($conv->pivot->last_read_at ?? '1970-01-01');
                @endphp
                <a href="{{ route('messages.show', $conv->id) }}" class="flex items-center gap-3 p-4 border-b border-slate-100 hover:bg-slate-100 transition-colors {{ $isUnread ? 'bg-blue-50' : 'bg-white' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 shadow-inner {{ $conv->type === 'private' ? 'bg-slate-200 text-slate-600' : 'bg-indigo-100 text-indigo-600' }}">
                        <i class="fa-solid {{ $conv->type === 'private' ? 'fa-user' : 'fa-users' }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <h4 class="font-bold text-sm text-slate-900 truncate">
                                @if($conv->type === 'private')
                                    {{ $conv->users->where('id', '!=', auth()->id())->first()->name ?? 'Utilisateur' }}
                                @else
                                    {{ $conv->name }}
                                @endif
                            </h4>
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ $conv->latestMessage ? $conv->latestMessage->created_at->shortAbsoluteDiffForHumans() : '' }}
                            </span>
                        </div>
                        <p class="text-xs truncate {{ $isUnread ? 'text-slate-800 font-semibold' : 'text-slate-500' }}">
                            {{ $conv->latestMessage->message_text ?? ($conv->latestMessage ? 'Fichier joint' : 'Nouvelle conversation') }}
                        </p>
                    </div>
                    @if($isUnread)
                        <span class="w-2 h-2 bg-blue-600 rounded-full shrink-0"></span>
                    @endif
                </a>
            @empty
                <div class="p-6 text-center text-slate-400 text-sm">
                    Aucune discussion pour le moment.
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Chat Area Selection -->
    <div class="w-2/3 flex flex-col items-center justify-center bg-slate-50 text-slate-400">
        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
            <i class="fa-regular fa-comments text-4xl text-slate-300"></i>
        </div>
        <p class="font-medium text-slate-500">Sélectionnez ou démarrez une conversation</p>
    </div>
</div>

<!-- Modal Private Message (with live user search) -->
<div id="modal-private" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4"
     data-search-url="{{ route('messages.searchUsers') }}">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg flex flex-col overflow-hidden" style="max-height:90vh;">

        {{-- Header --}}
        <div class="flex flex-col border-b border-slate-100 shrink-0">
            <div class="flex justify-between items-center p-6 pb-2">
                <h3 class="font-bold text-lg text-slate-900">✉️ Nouveau Message</h3>
                <button onclick="document.getElementById('modal-private').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('professor'))
            <div class="flex px-6 space-x-4 border-b border-slate-100">
                <button type="button" onclick="switchTab('individual')" id="tab-btn-individual" class="pb-2 font-bold text-sm text-blue-600 border-b-2 border-blue-600">Individuel</button>
                <button type="button" onclick="switchTab('class')" id="tab-btn-class" class="pb-2 font-bold text-sm text-slate-500 border-b-2 border-transparent hover:text-slate-700">Classe</button>
            </div>
            @endif
        </div>

        {{-- Tab Content: Individual --}}
        <div id="content-individual" class="flex flex-col flex-1 overflow-hidden">
            <form action="{{ route('messages.startPrivate') }}" method="POST" enctype="multipart/form-data"
                  id="form-private" class="flex flex-col flex-1 overflow-hidden">
            @csrf

            {{-- Search & filter area --}}
            <div class="p-4 border-b border-slate-100 bg-slate-50 shrink-0">
                {{-- Search input --}}
                <div class="relative mb-3">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
                    <input id="user-search-input" type="text"
                           placeholder="Rechercher par nom, pseudo ou e-mail…"
                           autocomplete="off"
                           class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                {{-- Role filter pills --}}
                <div class="flex gap-2 flex-wrap" id="role-filter-group">
                    @foreach([
                        ['slug'=>'all',       'label'=>'Tous',        'color'=>'bg-slate-800 text-white'],
                        ['slug'=>'student',   'label'=>'Étudiants',   'color'=>'bg-emerald-100 text-emerald-700'],
                        ['slug'=>'professor', 'label'=>'Professeurs', 'color'=>'bg-blue-100 text-blue-700'],
                        ['slug'=>'admin',     'label'=>'Admins',      'color'=>'bg-purple-100 text-purple-700'],
                    ] as $pill)
                    <button type="button"
                            data-role="{{ $pill['slug'] }}"
                            class="role-pill px-3 py-1 rounded-full text-xs font-semibold transition-all border border-transparent
                                   {{ $pill['slug'] === 'all' ? 'bg-slate-800 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $pill['label'] }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Results list --}}
            <div id="user-search-results"
                 class="overflow-y-auto flex-1 divide-y divide-slate-50 min-h-[160px] max-h-64">
                <div class="flex items-center justify-center h-40 text-slate-400 text-sm" id="search-placeholder">
                    <div class="text-center">
                        <i class="fa-solid fa-users text-2xl mb-2 text-slate-300 block"></i>
                        Commencez à taper pour trouver un contact
                    </div>
                </div>
            </div>

            {{-- Selected user banner --}}
            <div id="selected-user-banner"
                 class="hidden items-center gap-3 px-4 py-3 bg-blue-50 border-t border-blue-100 shrink-0">
                <i class="fa-solid fa-circle-check text-blue-500 text-lg"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-blue-600 font-semibold uppercase tracking-wide">Destinataire sélectionné</p>
                    <p id="selected-user-name" class="text-sm font-bold text-slate-900 truncate"></p>
                </div>
                <button type="button" id="clear-selection"
                        class="text-slate-400 hover:text-red-500 transition-colors text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Hidden field --}}
            <input type="hidden" name="user_id" id="selected-user-id" value="">

            {{-- Message & file --}}
            <div class="p-4 border-t border-slate-100 space-y-3 shrink-0">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Message</label>
                    <textarea name="message_text" rows="2"
                              placeholder="Écrivez votre message…"
                              class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 resize-none outline-none transition-all" required></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Fichier <span class="text-slate-400 normal-case">(optionnel)</span></label>
                    <input type="file" name="file"
                           class="w-full text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                </div>
                {{-- Error notice --}}
                <p id="recipient-error" class="hidden text-xs text-red-600 font-medium">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>Veuillez sélectionner un destinataire.
                </p>
                <div class="flex justify-end gap-3 pt-1">
                    <button type="button"
                            onclick="document.getElementById('modal-private').classList.add('hidden')"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-colors shadow-lg shadow-blue-500/30 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Envoyer
                    </button>
                </div>
            </div>
        </form>
        </div>

        {{-- Tab Content: Class --}}
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('professor'))
        <div id="content-class" class="flex flex-col flex-1 overflow-hidden hidden">
            <form action="{{ route('messages.startGroup') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <input type="hidden" name="target_type" value="class">
                
                <div class="p-6 border-b border-slate-100 bg-slate-50 shrink-0">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sélectionnez une classe</label>
                    <select name="target_id" class="w-full rounded-xl border border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all outline-none" required>
                        <option value="">-- Choisir une classe --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->class_name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Message & file --}}
                <div class="p-4 border-t border-slate-100 space-y-3 shrink-0 mt-auto">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Message</label>
                        <textarea name="message_text" rows="3"
                                  placeholder="Écrivez votre message à l'ensemble de la classe…"
                                  class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 resize-none outline-none transition-all" required></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Fichier <span class="text-slate-400 normal-case">(optionnel)</span></label>
                        <input type="file" name="file"
                               class="w-full text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button"
                                onclick="document.getElementById('modal-private').classList.add('hidden')"
                                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-colors shadow-lg shadow-blue-500/30 flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Envoyer à la classe
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

    </div>
</div>

{{-- ============================================================
     Inline JS: Live user search for the New Message modal
     ============================================================ --}}
<script>
function switchTab(tab) {
    if (tab === 'individual') {
        document.getElementById('tab-btn-individual').className = 'pb-2 font-bold text-sm text-blue-600 border-b-2 border-blue-600';
        document.getElementById('tab-btn-class').className = 'pb-2 font-bold text-sm text-slate-500 border-b-2 border-transparent hover:text-slate-700';
        document.getElementById('content-individual').classList.remove('hidden');
        document.getElementById('content-class').classList.add('hidden');
    } else {
        document.getElementById('tab-btn-class').className = 'pb-2 font-bold text-sm text-blue-600 border-b-2 border-blue-600';
        document.getElementById('tab-btn-individual').className = 'pb-2 font-bold text-sm text-slate-500 border-b-2 border-transparent hover:text-slate-700';
        document.getElementById('content-class').classList.remove('hidden');
        document.getElementById('content-individual').classList.add('hidden');
    }
}

(function () {
    const modal       = document.getElementById('modal-private');
    const searchInput = document.getElementById('user-search-input');
    const resultsBox  = document.getElementById('user-search-results');
    const placeholder = document.getElementById('search-placeholder');
    const banner      = document.getElementById('selected-user-banner');
    const selectedId  = document.getElementById('selected-user-id');
    const selectedName= document.getElementById('selected-user-name');
    const clearBtn    = document.getElementById('clear-selection');
    const form        = document.getElementById('form-private');
    const errorMsg    = document.getElementById('recipient-error');
    const searchUrl   = modal.dataset.searchUrl;

    let activeRole = 'all';
    let debounceTimer = null;
    let currentResults = [];

    // ---------- Role filter pills ----------
    document.querySelectorAll('.role-pill').forEach(btn => {
        btn.addEventListener('click', () => {
            activeRole = btn.dataset.role;
            document.querySelectorAll('.role-pill').forEach(b => {
                b.classList.remove('bg-slate-800', 'text-white', 'shadow-sm',
                                   'bg-emerald-500', 'bg-blue-500', 'bg-purple-500');
                b.classList.add('bg-slate-100', 'text-slate-600');
            });
            btn.classList.remove('bg-slate-100', 'text-slate-600');
            btn.classList.add('bg-slate-800', 'text-white', 'shadow-sm');
            fetchUsers();
        });
    });

    // ---------- Search input ----------
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchUsers, 300);
    });

    // ---------- Fetch users via AJAX ----------
    function fetchUsers() {
        const q = searchInput.value.trim();
        const url = `${searchUrl}?query=${encodeURIComponent(q)}&role=${activeRole}`;

        // Show spinner
        resultsBox.innerHTML = '<div class="flex items-center justify-center h-24 text-slate-400 text-sm gap-2"><i class="fa-solid fa-circle-notch fa-spin text-blue-400"></i> Chargement…</div>';

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(users => renderResults(users))
        .catch(() => {
            resultsBox.innerHTML = '<div class="p-6 text-center text-red-400 text-sm"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Erreur de chargement.</div>';
        });
    }

    // ---------- Render results ----------
    function renderResults(users) {
        currentResults = users;
        if (users.length === 0) {
            resultsBox.innerHTML = '<div class="flex items-center justify-center h-32 text-slate-400 text-sm"><i class="fa-solid fa-user-slash mr-2"></i>Aucun utilisateur trouvé.</div>';
            return;
        }

        const roleStyles = {
            student:   { bg: 'bg-emerald-100',  text: 'text-emerald-700',  label: 'Étudiant' },
            professor: { bg: 'bg-blue-100',      text: 'text-blue-700',     label: 'Professeur' },
            admin:     { bg: 'bg-purple-100',    text: 'text-purple-700',   label: 'Admin' },
        };

        resultsBox.innerHTML = users.map(u => {
            const rs  = roleStyles[u.role] || { bg: 'bg-slate-100', text: 'text-slate-600', label: u.role };
            const isSelected = selectedId.value == u.id;
            return `
            <button type="button"
                    data-user-id="${u.id}"
                    data-user-name="${escapeHtml(u.name)}"
                    class="user-row w-full flex items-center gap-3 px-4 py-3 text-left
                           hover:bg-blue-50 transition-colors
                           ${isSelected ? 'bg-blue-50 ring-1 ring-inset ring-blue-300' : 'bg-white'}">
                <img src="${u.avatar}" alt="" class="w-10 h-10 rounded-full shrink-0 shadow-sm">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="text-sm font-bold text-slate-900 truncate">${escapeHtml(u.name)}</span>
                        <span class="shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-full ${rs.bg} ${rs.text} uppercase tracking-wide">${rs.label}</span>
                    </div>
                    ${u.info ? `<p class="text-xs text-slate-500 truncate"><i class="fa-solid fa-tag mr-1"></i>${escapeHtml(u.info)}</p>` : ''}
                </div>
                ${isSelected ? '<i class="fa-solid fa-circle-check text-blue-500 shrink-0"></i>' : ''}
            </button>`;
        }).join('');

        // Bind row click
        resultsBox.querySelectorAll('.user-row').forEach(row => {
            row.addEventListener('click', () => selectUser(row.dataset.userId, row.dataset.userName));
        });
    }

    // ---------- Select a user ----------
    function selectUser(id, name) {
        selectedId.value  = id;
        selectedName.textContent = name;
        banner.classList.remove('hidden');
        banner.classList.add('flex');
        errorMsg.classList.add('hidden');
        // Re-render to highlight
        renderResults(currentResults);
    }

    // ---------- Clear selection ----------
    clearBtn.addEventListener('click', () => {
        selectedId.value = '';
        banner.classList.add('hidden');
        banner.classList.remove('flex');
        renderResults(currentResults);
    });

    // ---------- Form submit guard ----------
    form.addEventListener('submit', (e) => {
        if (!selectedId.value) {
            e.preventDefault();
            errorMsg.classList.remove('hidden');
            searchInput.focus();
        }
    });

    // ---------- Open modal: load initial results ----------
    document.querySelectorAll('[onclick*="modal-private"]').forEach(btn => {
        btn.addEventListener('click', () => {
            // Reset state
            searchInput.value = '';
            selectedId.value  = '';
            banner.classList.add('hidden');
            banner.classList.remove('flex');
            errorMsg.classList.add('hidden');
            // Reset pill to "All"
            document.querySelectorAll('.role-pill').forEach(b => {
                b.classList.remove('bg-slate-800', 'text-white', 'shadow-sm');
                b.classList.add('bg-slate-100', 'text-slate-600');
            });
            const allPill = document.querySelector('.role-pill[data-role="all"]');
            if (allPill) { allPill.classList.remove('bg-slate-100','text-slate-600'); allPill.classList.add('bg-slate-800','text-white','shadow-sm'); }
            activeRole = 'all';
            fetchUsers();
        });
    });

    // ---------- Helper ----------
    function escapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }
})();
</script>

<!-- Modal Group Broadcast -->
<div id="modal-group" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Nouvelle Diffusion</h3>
            <button onclick="document.getElementById('modal-group').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form action="{{ route('messages.startGroup') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Cible</label>
                <select name="target_type" class="w-full rounded-lg border-slate-300 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @if(auth()->user()->hasRole('admin'))
                        <option value="all_students">Tous les étudiants</option>
                        <option value="all_teachers">Tous les professeurs</option>
                    @endif
                    @if(auth()->user()->hasRole('professor'))
                        <option value="class">Une classe (ID requis)</option>
                    @endif
                </select>
            </div>
            @if(auth()->user()->hasRole('professor'))
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">ID de la Classe</label>
                <input type="number" name="target_id" class="w-full rounded-lg border-slate-300 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            @endif
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Message</label>
                <textarea name="message_text" rows="3" class="w-full rounded-lg border-slate-300 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Fichier (Optionnel)</label>
                <input type="file" name="file" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-group').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-bold transition-colors">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition-colors shadow-lg shadow-blue-500/30">Envoyer</button>
            </div>
        </form>
    </div>
</div>
@endsection
