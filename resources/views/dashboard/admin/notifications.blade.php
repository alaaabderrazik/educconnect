@extends('layouts.dashboard')

@section('title', 'Notifications Admin')
@section('user_name', auth()->user()->name)
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Notifications</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez vos alertes et notifications système.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="divide-y divide-slate-50">
            @forelse($notifications as $notification)
            <div class="p-6 hover:bg-slate-50 transition-colors {{ $notification->is_read ? 'opacity-75' : 'bg-blue-50/30' }}">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $notification->type === 'alert' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                            <i class="fa-solid {{ $notification->type === 'alert' ? 'fa-triangle-exclamation' : 'fa-bell' }} text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-slate-900">{{ $notification->title }}</h3>
                            <span class="text-xs text-slate-400 font-medium">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed mb-3">{{ $notification->message }}</p>
                        @if($notification->link)
                        <a href="{{ $notification->link }}" class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 hover:text-blue-700 uppercase tracking-wider transition-colors">
                            Voir les détails <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-bell-slash text-slate-300 text-3xl"></i>
                </div>
                <h3 class="text-slate-900 font-bold mb-1">Aucune notification</h3>
                <p class="text-slate-500 text-sm">Vous n'avez aucune notification pour le moment.</p>
            </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
        <div class="p-6 border-t border-slate-50">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
