@extends('layouts.dashboard')

@section('title', 'Mes Notes')
@section('user_role', 'Étudiante')

@section('sidebar_menu')
    @include('dashboard.student.sidebar')
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mes Notes & Résultats 🏆</h1>
        <p class="text-slate-500 text-sm mt-1">Consultez vos performances académiques détaillées.</p>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Moyenne Générale</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-black text-emerald-600">16.45</span>
                <span class="text-slate-400 text-sm font-medium mb-1">/ 20</span>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg w-fit">
                <i class="fa-solid fa-arrow-up"></i> +0.5 ce semestre
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Rang dans la classe</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-black text-blue-600">3<sup class="text-lg">ème</sup></span>
                <span class="text-slate-400 text-sm font-medium mb-1">sur 24</span>
            </div>
            <p class="text-xs text-slate-400 mt-4 italic">Top 15% de la promotion</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Crédits ECTS</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-black text-indigo-600">45</span>
                <span class="text-slate-400 text-sm font-medium mb-1">valoisés</span>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-5">
                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 75%"></div>
            </div>
        </div>
    </div>

    <!-- Detailed Results Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Résultats Détaillés</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-widest">
                        <th class="px-6 py-4">Matière / Examen</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-center">Coefficient</th>
                        <th class="px-6 py-4 text-center">Note</th>
                        <th class="px-6 py-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($grades as $grade)
                        <!-- Row -->
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-5">
                                <div class="font-bold text-slate-900">{{ $grade->title }}</div>
                                <div class="text-xs text-slate-400">{{ optional($grade->course)->subject_name ?? 'Cours Inconnu' }}</div>
                            </td>
                            <td class="px-6 py-5 text-slate-500">{{ \Carbon\Carbon::parse($grade->date)->format('d M Y') }}</td>
                            <td class="px-6 py-5 text-center font-bold">{{ number_format($grade->weight, 1) }}</td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-lg font-black {{ $grade->score >= 10 ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($grade->score, 1) }}</span><span class="text-slate-400 text-xs">/20</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                @if($grade->score >= 10)
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase">Validé</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-rose-100 text-rose-700 text-[10px] font-black uppercase">Échoué</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                <i class="fa-solid fa-graduation-cap text-4xl mb-3 text-slate-300"></i>
                                <p>Aucune note enregistrée pour le moment.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
