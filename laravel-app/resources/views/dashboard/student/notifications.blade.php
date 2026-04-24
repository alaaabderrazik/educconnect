@extends('layouts.dashboard')

@section('title', 'Notifications')
@section('user_role', 'Étudiant')

@section('sidebar_menu')
    @include('dashboard.student.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Notifications 🔔</h1>
            <p class="text-slate-500 text-sm mt-1">Restez informé des dernières activités de vos cours.</p>
        </div>
        <button class="text-blue-600 text-xs font-bold uppercase tracking-widest hover:text-blue-800 transition-colors">Tout marquer comme lu</button>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden divide-y divide-slate-50">
        @php
        $typeColors = [
            'info'       => ['icon' => 'fa-circle-info',  'color' => 'blue'],
            'success'    => ['icon' => 'fa-circle-check', 'color' => 'emerald'],
            'warning'    => ['icon' => 'fa-triangle-exclamation','color'=>'amber'],
            'assignment' => ['icon' => 'fa-file-pen',     'color' => 'rose'],
        ];
        @endphp

        @forelse($notifications as $n)
            @php $cfg = $typeColors[$n->type] ?? $typeColors['info']; @endphp
            <div class="p-6 flex items-start gap-4 hover:bg-slate-50/50 transition-colors cursor-pointer group {{ !$n->is_read ? 'bg-blue-50/20' : '' }}">
                <div class="w-12 h-12 bg-{{ $cfg['color'] }}-50 text-{{ $cfg['color'] }}-600 rounded-2xl flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform relative">
                    <i class="fa-solid {{ $cfg['icon'] }}"></i>
                    @if(!$n->is_read)
                        <div class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-blue-500 border-2 border-white rounded-full"></div>
                    @endif
                </div>
                <div class="flex-grow">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-bold {{ !$n->is_read ? 'text-blue-900' : 'text-slate-900' }}">{{ $n->title }}</h3>
                        <span class="text-[10px] {{ !$n->is_read ? 'text-blue-500' : 'text-slate-400' }} font-bold uppercase">{{ $n->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm {{ !$n->is_read ? 'text-slate-700 font-medium' : 'text-slate-500' }} leading-relaxed">{!! nl2br(e($n->message)) !!}</p>
                    @if($n->link)
                    <a href="{{ $n->link }}" class="mt-2 inline-block text-xs font-bold text-blue-600 hover:text-blue-800">Voir les détails →</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-20 text-center">
                <i class="fa-solid fa-bell-slash text-5xl text-slate-200 mb-4"></i>
                <p class="text-lg font-black text-slate-400">Aucune notification.</p>
                <p class="text-sm text-slate-400 mt-1">Vous êtes à jour !</p>
            </div>
        @endforelse
    </div>
@endsection
