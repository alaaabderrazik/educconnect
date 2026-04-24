@extends('layouts.dashboard')

@section('title', 'Années Scolaires')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Années Scolaires 📅</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez les cycles et les périodes académiques.</p>
        </div>
        <button onclick="document.getElementById('addYearModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nouvelle Année
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($years as $year)
        <div class="bg-white rounded-3xl p-8 border {{ $year->is_active ? 'border-blue-200 ring-4 ring-blue-500/10' : 'border-slate-100' }} shadow-sm relative group transition-all hover:shadow-md">
            @if($year->is_active)
                <span class="absolute top-4 right-4 bg-blue-600 text-white text-[10px] font-black px-2 py-1 rounded-full uppercase tracking-widest">Actif</span>
            @else
                <span class="absolute top-4 right-4 bg-slate-100 text-slate-400 text-[10px] font-black px-2 py-1 rounded-full uppercase tracking-widest">Clôturé</span>
            @endif
            
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all {{ $year->is_active ? 'bg-blue-50 text-blue-600' : 'bg-slate-50 text-slate-400 group-hover:bg-slate-100 group-hover:text-slate-500' }}">
                    <i class="fa-solid fa-calendar-check text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 leading-tight">{{ $year->name }}</h3>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="p-3 bg-slate-50 rounded-xl">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Classes</p>
                    <p class="text-lg font-black text-slate-900">{{ $year->student_classes_count ?? 0 }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Étudiants</p>
                    <p class="text-lg font-black text-slate-900">{{ $year->students_count ?? 0 }}</p>
                </div>
            </div>

            <div class="space-y-2 mb-6 text-sm text-slate-500 font-medium">
                <p class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-emerald-500"></div> Début: {{ \Carbon\Carbon::parse($year->start_date)->format('d/m/Y') }}</p>
                <p class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-rose-500"></div> Fin: {{ \Carbon\Carbon::parse($year->end_date)->format('d/m/Y') }}</p>
            </div>
            
            <div class="flex gap-2">
                @if(!$year->is_active)
                    <form action="{{ route('dashboard.admin.years.activate', $year->id) }}" method="POST" onsubmit="return confirm('Activer cette année scolaire ? Les autres seront clôturées.')" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-all text-center">Activer</button>
                    </form>
                @else
                    <form action="{{ route('dashboard.admin.years.close', $year->id) }}" method="POST" onsubmit="return confirm('Clôturer cette année scolaire ?')" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all text-center">Clôturer</button>
                    </form>
                @endif
                
                <form action="{{ route('dashboard.admin.destroy', ['type' => 'year', 'id' => $year->id]) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette année ?')" class="shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-10 h-3 py-3 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center hover:bg-rose-100 transition-all font-bold h-full"><i class="fa-solid fa-trash-can text-sm"></i></button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-slate-400 md:-full py-16 text-center text-slate-400 italic bg-white rounded-3xl border border-slate-100 shadow-sm">
            <i class="fa-solid fa-calendar-xmark text-4xl mb-3 opacity-50"></i>
            <p>Aucune année scolaire configurée.</p>
        </div>
        @endforelse
    </div>

    <!-- Add Modal -->
    <div id="addYearModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl w-full max-w-md shadow-2xl animate-modal-in">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-900">Nouvelle Année</h3>
                    <button onclick="document.getElementById('addYearModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('dashboard.admin.years.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Libellé</label>
                        <input type="text" name="name" placeholder="ex: 2025-2026" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Date Début</label>
                            <input type="date" name="start_date" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Date Fin</label>
                            <input type="date" name="end_date" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-bold text-slate-700">Définir comme année active</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all mt-4">Enregistrer l'année</button>
                </form>
            </div>
        </div>
    </div>
@endsection
