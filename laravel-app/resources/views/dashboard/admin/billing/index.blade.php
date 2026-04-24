@extends('layouts.dashboard')

@section('title', 'Facturation')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Facturation & Paiements 💳</h1>
    <p class="text-slate-500 text-sm mt-1">Vue avancée des abonnements par classe et salaires des professeurs.</p>
</div>

{{-- Tab Navigation --}}
<div class="flex border-b border-slate-200 mb-6 gap-6">
    <button onclick="switchTab('students')" id="tab-btn-students" class="pb-3 text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition-colors">
        Abonnements Étudiants
    </button>
    <button onclick="switchTab('teachers')" id="tab-btn-teachers" class="pb-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors">
        Salaires Professeurs
    </button>
</div>

{{-- =========================================== --}}
{{-- 1. STUDENTS TAB --}}
{{-- =========================================== --}}
<div id="tab-students" class="block">
    {{-- Filtering --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <form method="GET" action="{{ route('dashboard.admin.billing') }}" class="flex flex-wrap gap-4 items-end">
            <input type="hidden" name="active_tab" value="students">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Recherche</label>
                <input type="text" name="student_search" value="{{ request('student_search') }}" placeholder="Ex: Jean Dupont" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="w-40">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Statut</label>
                <select name="student_status" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="paid" {{ request('student_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                    <option value="unpaid" {{ request('student_status') == 'unpaid' ? 'selected' : '' }}>Non payé</option>
                    <option value="partial" {{ request('student_status') == 'partial' ? 'selected' : '' }}>Partiel</option>
                </select>
            </div>
            <div class="w-48">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Classe</label>
                <select name="class_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->class_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all shadow-sm">
                    <i class="fa-solid fa-filter mr-1"></i> Filtrer
                </button>
                <a href="{{ route('dashboard.admin.billing') }}?active_tab=students" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-sm transition-all">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Students Class Grid --}}
    <div class="space-y-8">
        @forelse($studentClasses as $classLevel)
            @if($classLevel->studentDetails->isEmpty())
                @continue
            @endif
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center justify-between">
                    <span>
                        <i class="fa-solid fa-users-rectangle text-blue-500 mr-2"></i>
                        Classe: {{ $classLevel->class_name }}
                    </span>
                    <span class="text-sm font-semibold text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-200">{{ $classLevel->studentDetails->count() }} Étudiant(s)</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($classLevel->studentDetails as $detail)
                        @php
                            $user = $detail->user;
                            if(!$user) continue;
                            
                            $sum = $user->paymentSummary;
                            $total = $sum ? $sum->total_amount : 0;
                            $paid = $sum ? $sum->paid_amount : 0;
                            $remaining = $sum ? $sum->remaining_amount : 0;
                            $planName = $sum && $sum->subscription_plan ? str_replace('_', ' ', ucfirst($sum->subscription_plan)) : 'Aucun plan';
                            
                            if ($total > 0 && $paid >= $total) {
                                $statusLabel = 'Payé';
                                $statusColor = 'bg-emerald-100 text-emerald-700';
                            } elseif ($paid > 0 && $paid < $total) {
                                $statusLabel = 'Partiel';
                                $statusColor = 'bg-amber-100 text-amber-700';
                            } else {
                                $statusLabel = 'Non payé';
                                $statusColor = 'bg-red-100 text-red-700';
                            }
                        @endphp
                        
                        <div class="border border-slate-100 rounded-2xl p-5 hover:border-blue-200 hover:shadow-lg hover:-translate-y-0.5 transition-all flex flex-col h-full bg-gradient-to-b from-white to-slate-50/50">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-bold text-slate-900 leading-tight">{{ $user->name }}</h4>
                                    <p class="text-[11px] text-slate-500 truncate mt-1 bg-white inline-block px-1.5 py-0.5 rounded border border-slate-100">{{ $user->email }}</p>
                                </div>
                                <span class="px-2.5 py-1 {{ $statusColor }} text-[10px] uppercase font-black rounded-lg tracking-wider border border-white/50 shadow-sm">{{ $statusLabel }}</span>
                            </div>
                            
                            <div class="mt-auto space-y-2 pt-4 border-t border-slate-100 border-dashed">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-400 font-semibold text-xs">Abonnement:</span>
                                    <span class="font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md text-xs">{{ $planName }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-400 font-semibold text-xs">Total Dû:</span>
                                    <span class="font-bold text-slate-700 font-mono">{{ number_format($total, 2) }} €</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-400 font-semibold text-xs">Reste à Payer:</span>
                                    <span class="font-bold text-rose-600 font-mono">{{ number_format($remaining, 2) }} €</span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2 mt-3">
                                    <button onclick='openDetailsModal(@json($user), "student")' class="w-full py-2 bg-slate-900 border border-slate-800 text-white hover:bg-slate-800 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-colors shadow-sm">
                                        <i class="fa-solid fa-eye"></i> Détails
                                    </button>
                                    
                                    @php
                                        // Simple heuristic to check if paid this month on UI render
                                        $currentRaw = ucfirst(now()->translatedFormat('F'));
                                        $isPaidThisMonth = $user->payments->contains(function($p) use ($currentRaw) {
                                            return stripos($p->month, $currentRaw) !== false && \Carbon\Carbon::parse($p->payment_date)->year == now()->year;
                                        });
                                    @endphp

                                    @if($isPaidThisMonth)
                                        <button disabled class="w-full py-2 bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed rounded-xl text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                            <i class="fa-solid fa-check-double mr-1"></i> Payé
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('dashboard.admin.billing.pay_month', $user->id) }}">
                                            @csrf
                                            <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white border border-emerald-700 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-colors shadow-sm">
                                                <i class="fa-solid fa-money-bill-wave mr-1"></i> Payer Mens.
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center text-slate-400 italic">
                <i class="fa-solid fa-folder-open text-3xl mb-3 text-slate-200"></i>
                <p>Aucun étudiant trouvé avec des abonnements pour ces critères.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- =========================================== --}}
{{-- 2. TEACHERS TAB --}}
{{-- =========================================== --}}
<div id="tab-teachers" class="hidden">
    {{-- Filtering --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <form method="GET" action="{{ route('dashboard.admin.billing') }}" class="flex flex-wrap gap-4 items-end">
            <input type="hidden" name="active_tab" value="teachers">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Recherche (Nom / Email)</label>
                <input type="text" name="teacher_search" value="{{ request('teacher_search') }}" placeholder="Ex: Professor Smith" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="w-40">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Statut</label>
                <select name="teacher_status" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="paid" {{ request('teacher_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                    <option value="unpaid" {{ request('teacher_status') == 'unpaid' ? 'selected' : '' }}>Non payé</option>
                    <option value="partial" {{ request('teacher_status') == 'partial' ? 'selected' : '' }}>Partiel</option>
                </select>
            </div>
            <div class="w-48">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Matière</label>
                <select name="subject_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les matières</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->subject_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all shadow-sm">
                    <i class="fa-solid fa-filter mr-1"></i> Filtrer
                </button>
                <a href="{{ route('dashboard.admin.billing') }}?active_tab=teachers" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-sm transition-all">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Teachers Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-xs uppercase text-slate-400 tracking-wide bg-slate-50">
                    <th class="px-5 py-3 text-left font-bold">Professeur</th>
                    <th class="px-5 py-3 text-left font-bold">Spécialité</th>
                    <th class="px-5 py-3 text-left font-bold">Salaire Total</th>
                    <th class="px-5 py-3 text-left font-bold">Montant Versé</th>
                    <th class="px-5 py-3 text-left font-bold">Reste à Payer</th>
                    <th class="px-5 py-3 text-left font-bold">Statut</th>
                    <th class="px-5 py-3 text-center font-bold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($teachers as $user)
                    @php
                        $sum = $user->paymentSummary;
                        $total = $sum ? $sum->total_amount : 0;
                        $paid = $sum ? $sum->paid_amount : 0;
                        $remaining = $sum ? $sum->remaining_amount : 0;
                        
                        if ($total > 0 && $paid >= $total) {
                            $statusLabel = 'Payé';
                            $statusColor = 'bg-emerald-100 text-emerald-700';
                        } elseif ($paid > 0 && $paid < $total) {
                            $statusLabel = 'Partiel';
                            $statusColor = 'bg-amber-100 text-amber-700';
                        } else {
                            $statusLabel = 'Non payé';
                            $statusColor = 'bg-red-100 text-red-700';
                        }
                    @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="font-bold text-slate-900">{{ $user->name }}</div>
                        <div class="text-xs text-slate-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-5 py-4">
                        @foreach($user->subjects as $subject)
                            <span class="px-2 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg mr-1">{{ $subject->subject_name }}</span>
                        @endforeach
                    </td>
                    <td class="px-5 py-4 font-mono font-bold text-slate-700">{{ number_format($total, 2) }} €</td>
                    <td class="px-5 py-4 font-mono font-bold text-emerald-600">{{ number_format($paid, 2) }} €</td>
                    <td class="px-5 py-4 font-mono font-bold text-rose-600">{{ number_format($remaining, 2) }} €</td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 {{ $statusColor }} text-[10px] uppercase font-black tracking-wider rounded-md">{{ $statusLabel }}</span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <button onclick='openDetailsModal(@json($user), "teacher")' class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center transition-all" title="Détails du salaire">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-slate-400 italic">Aucun professeur trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $teachers->links() }}
        </div>
    </div>
</div>

{{-- =========================================== --}}
{{-- MODAL (Unified) --}}
{{-- =========================================== --}}
<div id="detailsModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] ring-1 ring-white/20">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/80">
            <h3 class="text-xl font-black text-slate-900" id="modalTitle">Détails Structurels</h3>
            <button onclick="closeDetailsModal()" class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center bg-white rounded-xl shadow-sm border border-slate-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto bg-white">
            <div class="grid grid-cols-3 gap-5 mb-6" id="modalMetrics">
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex flex-col">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1" id="lblTotalMonths">Total Dû</p>
                    <p class="text-2xl font-black text-slate-800 font-mono mt-auto" id="valTotalMonths">0</p>
                </div>
                <div class="bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100 flex flex-col relative overflow-hidden">
                    <div class="absolute -right-2 -bottom-4 opacity-5 pointer-events-none drop-shadow-sm"><i class="fa-solid fa-coins text-8xl text-emerald-900"></i></div>
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-1" id="lblPaidMonths">Montant Payé</p>
                    <p class="text-2xl font-black text-emerald-700 font-mono mt-auto" id="valPaidMonths">0</p>
                </div>
                <div class="bg-rose-50/50 p-5 rounded-2xl border border-rose-100 flex flex-col relative overflow-hidden">
                    <div class="absolute -right-2 -bottom-4 opacity-5 pointer-events-none"><i class="fa-solid fa-triangle-exclamation text-8xl text-rose-900"></i></div>
                    <p class="text-[10px] font-bold text-rose-600 uppercase tracking-widest mb-1" id="lblRemMonths">Reste à Payer</p>
                    <p class="text-2xl font-black text-rose-700 font-mono mt-auto" id="valRemMonths">0</p>
                </div>
            </div>

            <div class="flex justify-between items-end mb-4">
                <h4 class="font-bold text-slate-900 text-lg">Historique d'Abonnement</h4>
                <div id="planBadge" class="hidden px-3 py-1 bg-blue-50 border border-blue-100 text-blue-700 rounded-lg text-xs font-bold"></div>
            </div>
            
            <div class="border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-100 text-[10px] uppercase tracking-widest text-slate-500 font-bold">
                        <tr>
                            <th class="px-5 py-3">Mois d'échéance</th>
                            <th class="px-5 py-3">Statut du Paiement</th>
                            <th class="px-5 py-3">Date d'opération</th>
                            <th class="px-5 py-3">Montant</th>
                            <th class="px-5 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="modalPaymentsList" class="divide-y divide-slate-50">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        document.getElementById('tab-students').classList.toggle('hidden', tab !== 'students');
        document.getElementById('tab-teachers').classList.toggle('hidden', tab !== 'teachers');
        
        const btnStudents = document.getElementById('tab-btn-students');
        const btnTeachers = document.getElementById('tab-btn-teachers');
        
        if(tab === 'students') {
            btnStudents.className = 'pb-3 text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition-colors';
            btnTeachers.className = 'pb-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors';
        } else {
            btnTeachers.className = 'pb-3 text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition-colors';
            btnStudents.className = 'pb-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition-colors';
        }
        
        let url = new URL(window.location);
        url.searchParams.set('active_tab', tab);
        window.history.pushState({}, '', url);
    }

    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('active_tab') === 'teachers') {
        switchTab('teachers');
    } else {
        switchTab('students');
    }

    function openDetailsModal(user, type) {
        document.getElementById('detailsModal').classList.remove('hidden');
        document.getElementById('modalTitle').innerText = type === 'student' ? 'Dossier Étudiant : ' + user.name : 'Dossier Professeur : ' + user.name;
        
        let sum = user.payment_summary || user.paymentSummary || {};
        document.getElementById('valTotalMonths').innerText = (parseFloat(sum.total_amount) || 0).toFixed(2) + ' €';
        document.getElementById('valPaidMonths').innerText = (parseFloat(sum.paid_amount) || 0).toFixed(2) + ' €';
        document.getElementById('valRemMonths').innerText = (parseFloat(sum.remaining_amount) || 0).toFixed(2) + ' €';

        let payments = user.payments || [];
        let tbody = document.getElementById('modalPaymentsList');
        tbody.innerHTML = '';
        
        if (type === 'student' && sum.start_date && sum.end_date) {
            let start = new Date(sum.start_date);
            let end = new Date(sum.end_date);
            let current = new Date(start.getFullYear(), start.getMonth(), 1); 
            let endConstraint = new Date(end.getFullYear(), end.getMonth(), 1);
            
            let planBdg = document.getElementById('planBadge');
            planBdg.classList.remove('hidden');
            let plname = sum.subscription_plan ? sum.subscription_plan.replace('_', ' ') : '';
            planBdg.innerText = 'Forfait: ' + plname.charAt(0).toUpperCase() + plname.slice(1);
            
            let formatter = new Intl.DateTimeFormat('fr-FR', { month: 'long', year: 'numeric' });
            
            while(current <= endConstraint) {
                let monthLabelRaw = formatter.format(current);
                let monthName = monthLabelRaw.charAt(0).toUpperCase() + monthLabelRaw.slice(1);
                
                let matchingPayment = payments.find(p => p.month && p.month.toLowerCase().includes(monthLabelRaw.split(' ')[0].toLowerCase()));
                
                let badge = '';
                let dateStr = '-';
                let amtStr = '-';
                
                if (matchingPayment) {
                    if (matchingPayment.status === 'paid') {
                        badge = '<span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] uppercase font-black tracking-wider rounded-md"><i class="fa-solid fa-check mr-1 text-[8px]"></i> Payé</span>';
                    } else if (matchingPayment.status === 'partial') {
                        badge = '<span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] uppercase font-black tracking-wider rounded-md">Partiel</span>';
                    } else {
                        badge = '<span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] uppercase font-black tracking-wider rounded-md">Non payé</span>';
                    }
                    dateStr = new Date(matchingPayment.payment_date).toLocaleDateString('fr-FR');
                    amtStr = parseFloat(matchingPayment.amount).toFixed(2) + ' €';
                } else {
                    // Si on est dans le futur par rapport à aujourd'hui, on ne marque pas "Non payé" (c'est juste "En attente")
                    let today = new Date();
                    if (current.getFullYear() > today.getFullYear() || (current.getFullYear() === today.getFullYear() && current.getMonth() > today.getMonth())) {
                        badge = '<span class="px-2.5 py-1 bg-slate-100 text-slate-500 text-[10px] uppercase font-black tracking-wider rounded-md border border-slate-200 border-dashed">⏳ À Venir</span>';
                    } else {
                        badge = '<span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] uppercase font-black tracking-wider rounded-md"><i class="fa-solid fa-xmark mr-1 text-[8px]"></i> Non payé</span>';
                    }
                }

                
                let actionBtn = '';
                
                if (matchingPayment) {
                    actionBtn = `
                    <form method="POST" action="/dashboard/admin/billing/revoke-month/${user.id}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="month" value="${matchingPayment.month}">
                        <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded text-[10px] font-bold uppercase transition-colors shrink-0 whitespace-nowrap">Annuler</button>
                    </form>`;
                } else {
                    actionBtn = `
                    <form method="POST" action="/dashboard/admin/billing/pay-month-specific/${user.id}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="month" value="${monthName}">
                        <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded text-[10px] font-bold uppercase transition-colors shrink-0 whitespace-nowrap">Payer</button>
                    </form>`;
                }

                tbody.innerHTML += `<tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4 font-bold text-slate-800 tracking-tight">${monthName}</td>
                    <td class="px-5 py-4 w-32">${badge}</td>
                    <td class="px-5 py-4 text-xs font-semibold text-slate-500">${dateStr}</td>
                    <td class="px-5 py-4 font-mono font-bold text-slate-700">${amtStr}</td>
                    <td class="px-5 py-4 text-center">${actionBtn}</td>
                </tr>`;
                
                current.setMonth(current.getMonth() + 1);
            }
        } else {
            document.getElementById('planBadge').classList.add('hidden');
            
            // Render unstructured flat list
            if(payments.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-5 py-8 text-center text-slate-400 italic font-medium">Aucun historique de paiement pour cet utilisateur.</td></tr>`;
            } else {
                payments.forEach(p => {
                    let badge = p.status === 'paid' ? '<span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] uppercase font-black tracking-wider rounded-md">Payé</span>' 
                              : (p.status === 'partial' ? '<span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] uppercase font-black tracking-wider rounded-md">Partiel</span>' 
                              : '<span class="px-2.5 py-1 bg-red-100 text-red-700 text-[10px] uppercase font-black tracking-wider rounded-md">Non payé</span>');
                    
                    let df = new Date(p.payment_date).toLocaleDateString('fr-FR');
                    
                    tbody.innerHTML += `<tr class="hover:bg-slate-50">
                        <td class="px-5 py-4 font-bold text-slate-800 tracking-tight">${p.month || '-'}</td>
                        <td class="px-5 py-4">${badge}</td>
                        <td class="px-5 py-4 text-xs font-semibold text-slate-500">${df}</td>
                        <td class="px-5 py-4 font-mono font-bold text-slate-700">${parseFloat(p.amount).toFixed(2)} €</td>
                        <td class="px-5 py-4 text-center">
                            <form method="POST" action="/dashboard/admin/billing/revoke-month/${user.id}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="month" value="${p.month}">
                                <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded text-[10px] font-bold uppercase transition-colors">Annuler</button>
                            </form>
                        </td>
                    </tr>`;
                });
            }
        }
    }

    function closeDetailsModal() {
        document.getElementById('detailsModal').classList.add('hidden');
    }
</script>
@endsection
