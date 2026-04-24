@extends('layouts.dashboard')

@section('title', 'Boîte de Réception')
@section('user_role', 'Professeur')

@section('sidebar_menu')
    @include('dashboard.professor.sidebar')
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Conversations 💬</h1>
        <p class="text-slate-500 text-sm mt-1">Gérez vos échanges avec les étudiants.</p>
    </div>

    <!-- Conversations List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Boîte de Réception</h3>
        </div>
        
        <div class="divide-y divide-slate-100">
            @forelse($conversations as $student)
                <a href="{{ route('dashboard.professor.chat', $student->id) }}" class="block p-6 hover:bg-slate-50 transition-colors {{ $student->unread_count > 0 ? 'bg-blue-50/30' : '' }}">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name ?? 'User') }}&background=random" class="w-12 h-12 rounded-full">
                            @if($student->unread_count > 0)
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-blue-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-white shadow-sm">
                                    {{ $student->unread_count }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <h4 class="text-sm font-bold text-slate-900 truncate flex items-center gap-2">
                                    {{ $student->name ?? 'Utilisateur Inconnu' }}
                                    @if(optional(optional($student->studentDetails)->studentClass)->class_name)
                                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-medium uppercase tracking-wider">
                                            {{ $student->studentDetails->studentClass->class_name }}
                                        </span>
                                    @endif
                                </h4>
                                @if($student->last_message)
                                    <span class="text-xs font-bold {{ $student->unread_count > 0 ? 'text-blue-600' : 'text-slate-400' }}">
                                        {{ $student->last_message->created_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm truncate {{ $student->unread_count > 0 ? 'text-slate-800 font-medium' : 'text-slate-500' }}">
                                @if($student->last_message)
                                    @if($student->last_message->sender_id === auth()->id())
                                        <span class="text-slate-400 mr-1"><i class="fa-solid fa-reply"></i></span>
                                    @endif
                                    {{ $student->last_message->content }}
                                @else
                                    <span class="italic text-slate-400">Aucun message</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-10 text-center text-slate-500">
                    <i class="fa-regular fa-comments text-4xl mb-3 text-slate-300"></i>
                    <p>Vous n'avez aucune conversation en cours.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
