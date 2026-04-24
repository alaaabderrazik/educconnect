@extends('layouts.dashboard')

@section('title', 'Paiements')
@section('user_role', 'Étudiant')

@section('sidebar_menu')
    @include('dashboard.student.sidebar')
@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mes Paiements 💳</h1>
        <p class="text-slate-500 text-sm mt-1">Consultez l'historique de vos paiements et votre situation financière.</p>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Total Payé (Année En Cours)</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-black text-emerald-600">3,450€</span>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg w-fit">
                <i class="fa-solid fa-check"></i> À jour
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Reste à Payer</p>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-black text-amber-500">1,150€</span>
            </div>
            <p class="text-xs text-slate-400 mt-4 font-bold">Prochaine échéance: 05 Nov 2026</p>
        </div>

        <div class="bg-slate-900 p-6 rounded-2xl shadow-sm text-center flex flex-col justify-center relative overflow-hidden group hover:shadow-lg transition-all cursor-pointer">
            <div class="absolute inset-0 bg-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <i class="fa-solid fa-credit-card text-3xl text-white mb-3 relative z-10"></i>
            <span class="text-white font-black uppercase tracking-widest text-sm relative z-10">Effectuer un paiement</span>
        </div>
    </div>

    <!-- Detailed Transactions Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">Historique des Transactions</h3>
            <button class="text-blue-600 text-sm font-bold"><i class="fa-solid fa-download mr-1"></i> Relevé</button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-widest">
                        <th class="px-6 py-4">Référence / Description</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Méthode</th>
                        <th class="px-6 py-4 text-center">Montant</th>
                        <th class="px-6 py-4 text-right">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <!-- Fake Row 1 -->
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="font-bold text-slate-900">Frais de scolarité - Trimestre 1</div>
                            <div class="text-xs text-slate-400">REF: TXN-9823741A</div>
                        </td>
                        <td class="px-6 py-5 text-slate-500">01 Sept 2026</td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <i class="fa-brands fa-cc-visa text-lg text-blue-800"></i> <span class="text-xs font-bold text-slate-600">•••• 4242</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-base font-black text-slate-900">1,150.00 €</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase">Payé</span>
                        </td>
                    </tr>
                    
                    <!-- Fake Row 2 -->
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="font-bold text-slate-900">Frais d'inscription annuels</div>
                            <div class="text-xs text-slate-400">REF: TXN-4491022B</div>
                        </td>
                        <td class="px-6 py-5 text-slate-500">15 Jui 2026</td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-building-columns text-slate-600"></i> <span class="text-xs font-bold text-slate-600">Virement SEPA</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-base font-black text-slate-900">450.00 €</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase">Payé</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
