@extends('layouts.dashboard')
@section('title', 'Notifications')
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Notifications 🔔</h1>
        <p class="text-slate-500 text-sm mt-1">Toutes vos notifications système et alertes.</p>
    </div>

    @php
    $typeColors = [
        'info'       => ['icon' => 'fa-circle-info',  'bg' => 'blue-50',   'text' => 'blue-600'],
        'success'    => ['icon' => 'fa-circle-check', 'bg' => 'emerald-50','text' => 'emerald-600'],
        'warning'    => ['icon' => 'fa-triangle-exclamation','bg'=>'amber-50','text'=>'amber-600'],
        'assignment' => ['icon' => 'fa-file-pen',     'bg' => 'indigo-50', 'text' => 'indigo-600'],
    ];
    @endphp

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        @if($notifications->count() > 0)
        <div class="divide-y divide-slate-100">
            @foreach($notifications as $n)
            @php $cfg = $typeColors[$n->type] ?? $typeColors['info']; @endphp
            <div class="p-5 hover:bg-slate-50 transition-colors flex items-start gap-4">
                <div class="w-11 h-11 bg-{{ $cfg['bg'] }} text-{{ $cfg['text'] }} rounded-2xl flex items-center justify-center text-lg flex-shrink-0">
                    <i class="fa-solid {{ $cfg['icon'] }}"></i>
                </div>
                <div class="flex-1">
                    <p class="font-black text-sm text-slate-900">{{ $n->title }}</p>
                    <p class="text-xs text-slate-500 mt-1">{!! nl2br(e($n->message)) !!}</p>
                    <p class="text-[10px] text-slate-400 font-bold mt-2">{{ $n->created_at->diffForHumans() }}</p>
                </div>
                @if($n->link)
                <a href="{{ $n->link }}" class="flex-shrink-0 px-3 py-1 bg-slate-50 text-slate-500 rounded-lg text-xs font-bold hover:bg-blue-50 hover:text-blue-600 transition-all">Voir →</a>
                @endif
            </div>
            @endforeach
        </div>
        {{ $notifications->links() }}
        @else
        <div class="py-20 text-center">
            <i class="fa-solid fa-bell-slash text-5xl text-slate-200 mb-4"></i>
            <p class="text-lg font-black text-slate-400">Aucune notification.</p>
            <p class="text-sm text-slate-400 mt-1">Vous êtes à jour !</p>
        </div>
        @endif
    </div>
@endsection
