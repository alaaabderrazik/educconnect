@extends('layouts.dashboard')

@section('title', 'Messages')
@section('user_role', 'Étudiant')

@section('sidebar_menu')
    @include('dashboard.student.sidebar')
@endsection

@section('content')
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden flex h-[calc(100vh-200px)] min-h-[600px]">
        
        <!-- Sidebar Contacts -->
        <div class="w-80 border-r border-slate-100 flex flex-col hidden lg:flex">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Discussions</h3>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>
            <div class="flex-grow overflow-y-auto divide-y divide-slate-50">
                @foreach($professors as $prof)
                <a href="{{ route('dashboard.student.messages', ['receiver_id' => $prof->id]) }}" class="block p-4 flex items-center gap-4 hover:bg-slate-50 cursor-pointer transition-colors {{ $activeUserId == $prof->id ? 'bg-blue-50/50' : '' }}">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($prof->name) }}&background=random" class="w-12 h-12 rounded-2xl">
                    </div>
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-center mb-0.5">
                            <h4 class="text-sm font-bold text-slate-900 truncate">{{ $prof->name }}</h4>
                        </div>
                        <p class="text-xs text-slate-500 truncate">Cliquez pour converser</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        @php
            $activeProf = $professors->firstWhere('id', $activeUserId);
        @endphp

        <!-- Chat Area -->
        <div class="flex-grow flex flex-col bg-slate-50/30">
            @if($activeProf)
            <!-- Header -->
            <div class="p-4 border-b border-slate-100 bg-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($activeProf->name) }}&background=random" class="w-10 h-10 rounded-xl">
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">{{ $activeProf->name }}</h4>
                        <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest">Connecté</p>
                    </div>
                </div>
            </div>

            <!-- Messages Stream -->
            <div class="flex-grow p-6 overflow-y-auto space-y-6">
                @forelse($messages as $msg)
                    @if($msg->sender_id !== auth()->id())
                    <!-- Received -->
                    <div class="flex gap-3 max-w-[80%]">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($msg->sender->name ?? 'User') }}&background=random" class="w-8 h-8 rounded-lg self-end">
                        <div class="bg-white p-4 rounded-2xl rounded-bl-none shadow-sm border border-slate-100">
                            <p class="text-sm text-slate-800">{{ $msg->content }}</p>
                            <span class="text-[10px] text-slate-400 mt-2 block">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                    @else
                    <!-- Sent -->
                    <div class="flex flex-row-reverse gap-3 max-w-[80%] ml-auto">
                        <div class="bg-blue-600 p-4 rounded-2xl rounded-br-none shadow-md text-white">
                            <p class="text-sm">{{ $msg->content }}</p>
                            <span class="text-[10px] text-white/60 mt-2 block">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                    @endif
                @empty
                    <div class="flex justify-center items-center h-full">
                        <p class="text-slate-400 text-sm">Aucun message pour le moment. Envoyez le premier message.</p>
                    </div>
                @endforelse
            </div>

            <!-- Input -->
            <div class="p-6 bg-white border-t border-slate-100">
                <form class="flex gap-3" method="POST" action="{{ route('dashboard.student.messages.send') }}">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $activeProf->id }}">
                    <input type="text" name="message" required placeholder="Écrire un message..." class="flex-grow bg-slate-50 border border-slate-200 rounded-xl px-4 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="w-11 h-11 bg-blue-600 text-white rounded-xl flex items-center justify-center hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
            @else
            <!-- No active Professor -->
            <div class="flex-grow flex items-center justify-center">
                <p class="text-slate-400">Sélectionnez un contact pour commencer la discussion.</p>
            </div>
            @endif
        </div>

    </div>
@endsection
