@extends('layouts.dashboard')

@section('title', 'Conversation')

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
<div class="flex h-[calc(100vh-140px)] bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
    
    <!-- Mobile Back Button overlay (only visible on small screens to go back to list... not fully implemented, we just link back to index) -->
    
    <!-- Left Sidebar: hidden on small screens -->
    <div class="hidden md:flex w-1/3 border-r border-slate-100 flex-col bg-slate-50">
        <div class="p-4 border-b border-slate-200 bg-white flex justify-between items-center gap-2">
            <h2 class="font-bold text-lg text-slate-900">Discussions</h2>
            <div class="flex flex-col items-end gap-1">
                <a href="{{ route('messages.index') }}" class="text-[10px] text-blue-600 hover:underline font-medium">Voir tout</a>
                <button onclick="document.getElementById('modal-private').classList.remove('hidden')" class="text-[11px] font-bold text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="fa-solid fa-user-plus mr-1"></i>Nouveau
                </button>
            </div>
        </div>
        <div class="overflow-y-auto flex-1">
            @foreach(auth()->user()->conversations()->with('latestMessage')->get() as $conv)
                <a href="{{ route('messages.show', $conv->id) }}" class="flex items-center gap-3 p-4 border-b border-slate-100 hover:bg-slate-100 transition-colors {{ $conversation->id == $conv->id ? 'bg-blue-50 border-l-4 border-l-blue-600' : 'bg-white' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $conv->type === 'private' ? 'bg-slate-200 text-slate-600' : 'bg-indigo-100 text-indigo-600' }}">
                        <i class="fa-solid {{ $conv->type === 'private' ? 'fa-user' : 'fa-users' }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-sm text-slate-900 truncate">
                            @if($conv->type === 'private')
                                {{ $conv->users->where('id', '!=', auth()->id())->first()->name ?? 'Utilisateur' }}
                            @else
                                {{ $conv->name }}
                            @endif
                        </h4>
                        <p class="text-xs text-slate-500 truncate mt-0.5">
                            {{ $conv->latestMessage->message_text ?? '...' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Active Chat Area -->
    <div class="flex-1 flex flex-col bg-slate-50 relative w-full md:w-2/3">
        <!-- Chat Header -->
        <div class="h-16 border-b border-slate-200 bg-white flex items-center px-6 justify-between shrink-0 shadow-sm z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('messages.index') }}" class="md:hidden text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $conversation->type === 'private' ? 'bg-slate-200' : 'bg-indigo-100' }}">
                    <i class="fa-solid {{ $conversation->type === 'private' ? 'fa-user' : 'fa-users text-indigo-600' }}"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">
                        @if($conversation->type === 'private')
                            {{ $conversation->users->where('id', '!=', auth()->id())->first()->name ?? 'Utilisateur supprimé' }}
                        @else
                            {{ $conversation->name }}
                        @endif
                    </h3>
                    <p class="text-xs text-slate-400">{{ $conversation->type === 'private' ? 'Discussion privée' : 'Diffusion' }}</p>
                </div>
            </div>
            <div>
                <!-- Extra settings per chat can go here -->
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-6 scroll-smooth" id="chat-container">
            <div class="flex flex-col gap-4">
                @forelse($messages as $message)
                    @php
                        $isMe = $message->sender_id === auth()->id();
                    @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} mb-2">
                        <div class="max-w-[75%] rounded-2xl px-5 py-3 shadow-sm {{ $isMe ? 'bg-blue-600 text-white rounded-br-sm' : 'bg-white text-slate-800 rounded-bl-sm border border-slate-100' }}">
                            @if(!$isMe && $conversation->type !== 'private')
                                <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">{{ $message->sender->name }}</div>
                            @endif
                            
                            @if($message->message_text)
                                <p class="text-sm leading-relaxed mb-1">{{ $message->message_text }}</p>
                            @endif
                            
                            @if($message->file_path)
                                <a href="{{ route('messages.download', $message->id) }}" class="mt-2 block w-full rounded-xl bg-black/10 hover:bg-black/20 transition-colors p-3 flex items-center gap-3 border {{ $isMe ? 'border-white/20 text-white' : 'border-slate-200 text-blue-600' }}">
                                    <div class="w-10 h-10 shrink-0 bg-white/30 rounded-lg flex items-center justify-center text-lg">
                                        @php
                                            $ext = pathinfo($message->file_name, PATHINFO_EXTENSION);
                                            $icon = 'fa-file';
                                            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) $icon = 'fa-image';
                                            if (in_array(strtolower($ext), ['pdf'])) $icon = 'fa-file-pdf';
                                        @endphp
                                        <i class="fa-solid {{ $icon }}"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold truncate">{{ $message->file_name }}</p>
                                        <p class="text-xs opacity-80">Cliquer pour télécharger</p>
                                    </div>
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            @endif
                            <div class="text-right mt-1">
                                <span class="text-[10px] {{ $isMe ? 'text-blue-200' : 'text-slate-400' }}">{{ $message->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-400 text-sm">
                        C'est le début de votre conversation. Envoyez un message !
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Input Form -->
        <div class="p-4 bg-white border-t border-slate-200 shrink-0">
            <!-- File preview area -->
            <div id="file-preview" class="hidden mb-3 p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-paperclip text-slate-400"></i>
                    <span id="file-name" class="text-sm font-medium text-slate-700">document.pdf</span>
                </div>
                <button type="button" id="clear-file" class="text-rose-500 hover:text-rose-600"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form action="{{ route('messages.store', $conversation->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-2 relative">
                @csrf
                <!-- Hidden file input -->
                <input type="file" name="file" id="file-input" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                
                <button type="button" onclick="document.getElementById('file-input').click()" class="w-12 h-12 rounded-full hover:bg-slate-100 text-slate-400 hover:text-blue-600 transition-colors flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-paperclip text-lg"></i>
                </button>
                
                <input type="text" name="message_text" placeholder="Écrivez votre message..." class="flex-1 rounded-full border-slate-200 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 text-sm px-6 py-3 transition-colors outline-none h-12" autocomplete="off">
                
                <button type="submit" class="w-12 h-12 rounded-full bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/30 flex items-center justify-center shrink-0 transition-all hover:scale-105">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto scroll to bottom
    const chatContainer = document.getElementById('chat-container');
    if(chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // File input handling
    const fileInput = document.getElementById('file-input');
    const filePreview = document.getElementById('file-preview');
    const fileNameDisplay = document.getElementById('file-name');
    const clearFileBtn = document.getElementById('clear-file');

    if(fileInput) {
        fileInput.addEventListener('change', function() {
            if(this.files && this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
                filePreview.classList.remove('hidden');
                filePreview.classList.add('flex');
            } else {
                filePreview.classList.add('hidden');
                filePreview.classList.remove('flex');
            }
        });
    }

    if(clearFileBtn) {
        clearFileBtn.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.classList.add('hidden');
            filePreview.classList.remove('flex');
        });
    }
</script>

<!-- Modal Private Message -->
<div id="modal-private" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Nouveau Message</h3>
            <button onclick="document.getElementById('modal-private').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form action="{{ route('messages.startPrivate') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Destinataire</label>
                <!-- the $users variable must be populated if this modal is rendered. 
                     Wait, $users isn't passed to show() method. Let's redirect to index instead of duplicating modal if users array isn't here. 
                     Actually, I'll update MessageController@show to pass $users too. -->
                <select name="user_id" class="w-full rounded-lg border-slate-300 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="" disabled selected>Sélectionner un contact</option>
                    @if(isset($users))
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role->name ?? 'User' }})</option>
                        @endforeach
                    @endif
                </select>
                <p class="text-[10px] text-slate-400 mt-1"><i class="fa-solid fa-circle-info mr-1"></i>Message adressé à 1 seul contact</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Message</label>
                <textarea name="message_text" rows="3" class="w-full rounded-lg border-slate-300 bg-slate-50 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Fichier (Optionnel)</label>
                <input type="file" name="file" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-private').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-bold transition-colors">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition-colors shadow-lg shadow-blue-500/30">Envoyer</button>
            </div>
        </form>
    </div>
</div>
@endsection
