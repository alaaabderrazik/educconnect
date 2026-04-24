@extends('layouts.dashboard')

@section('title', 'Chat avec ' . $student->name)
@section('user_role', 'Professeur')

@section('sidebar_menu')
    @include('dashboard.professor.sidebar')
@endsection

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                <a href="{{ route('dashboard.professor.messages') }}" class="text-slate-400 hover:text-blue-600 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                Chat avec {{ $student->first_name }} {{ $student->last_name }}
                @if(optional(optional($student->studentDetails)->studentClass)->class_name)
                    <span class="text-xs bg-blue-100 text-blue-700 px-2.5 py-1 rounded-md font-bold uppercase tracking-wider ml-2">
                        {{ $student->studentDetails->studentClass->class_name }}
                    </span>
                @endif
            </h1>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col h-[600px] overflow-hidden">
        <!-- Chat Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-4 bg-slate-50/50">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random" class="w-10 h-10 rounded-full shadow-sm">
            <div>
                <h3 class="font-bold text-slate-900">{{ $student->name }}</h3>
                <p class="text-xs text-slate-500">Étudiant</p>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="flex-grow p-6 overflow-y-auto bg-slate-50/30 flex flex-col gap-4" id="chat-container">
            @forelse($messages as $msg)
                @if($msg->sender_id === auth()->id())
                    <!-- Professor Message (Right) -->
                    <div class="flex justify-end">
                        <div class="max-w-[75%]">
                            <div class="bg-blue-600 text-white rounded-2xl rounded-tr-sm px-5 py-3 shadow-sm">
                                <p class="text-sm leading-relaxed">{{ $msg->content }}</p>
                            </div>
                            <div class="text-right mt-1.5">
                                <span class="text-[10px] font-medium text-slate-400">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Student Message (Left) -->
                    <div class="flex flex-col items-start">
                        <div class="max-w-[75%]">
                            <div class="bg-white border border-slate-100 text-slate-800 rounded-2xl rounded-tl-sm px-5 py-3 shadow-sm">
                                <p class="text-sm leading-relaxed">{{ $msg->content }}</p>
                            </div>
                            <div class="text-left mt-1.5 flex items-center gap-2">
                                <span class="text-[10px] font-medium text-slate-400">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="flex-grow flex flex-col items-center justify-center text-slate-400 space-y-3 h-full">
                    <i class="fa-regular fa-comments text-5xl opacity-40"></i>
                    <p class="text-sm">Envoyez le premier message à {{ $student->first_name }}.</p>
                </div>
            @endforelse
        </div>

        <!-- Chat Input Form -->
        <div class="p-4 bg-white border-t border-slate-100">
            <form action="{{ route('dashboard.professor.chat.send', $student->id) }}" method="POST" class="flex items-center gap-3">
                @csrf
                <div class="flex-grow relative">
                    <input type="text" name="content" placeholder="Écrivez votre message ici..." 
                           class="w-full bg-slate-50 border border-slate-200 rounded-full pl-5 pr-12 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400" required autocomplete="off" autofocus>
                </div>
                <button type="submit" class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors shadow-sm shadow-blue-600/20 flex-shrink-0">
                    <i class="fa-solid fa-paper-plane text-sm ml-1"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Scroll to bottom script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var chatContainer = document.getElementById('chat-container');
            if(chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        });
    </script>
@endsection
