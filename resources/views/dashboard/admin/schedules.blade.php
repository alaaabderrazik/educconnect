@extends('layouts.dashboard')

@section('title', 'Emploi du Temps')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Emploi du Temps 📅</h1>
        <p class="text-slate-500 text-sm mt-1">Créez et gérez les créneaux horaires pour les professeurs et les classes.</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Nouveau Créneau
    </button>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3">
    <i class="fa-solid fa-circle-check"></i>
    <span class="text-sm font-bold">{{ session('success') }}</span>
</div>
@endif
@if(session('error'))
<div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl flex items-center gap-3">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <span class="text-sm font-bold">{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl flex flex-col gap-2">
    <div class="flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span class="text-sm font-bold">Le formulaire contient des erreurs :</span>
    </div>
    <ul class="list-disc list-inside text-sm pl-7">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Re-open modal if there are errors (we assume 'addModal' since it's the primary way)
    document.getElementById('addModal').classList.remove('hidden');
});
</script>
@endif

{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
    <form method="GET" action="{{ route('dashboard.admin.schedules') }}" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Professeur</label>
            <select name="teacher_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les professeurs</option>
                @foreach($professors as $p)
                    <option value="{{ $p->id }}" {{ request('teacher_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Classe</label>
            <select name="class_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Toutes les classes</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->class_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[140px]">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Jour</label>
            <select name="day" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les jours</option>
                @foreach(['monday'=>'Lundi','tuesday'=>'Mardi','wednesday'=>'Mercredi','thursday'=>'Jeudi','friday'=>'Vendredi','saturday'=>'Samedi','sunday'=>'Dimanche'] as $key=>$label)
                    <option value="{{ $key }}" {{ request('day') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all shadow-sm">
                <i class="fa-solid fa-filter mr-1"></i>Filtrer
            </button>
            <a href="{{ route('dashboard.admin.schedules') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-sm transition-all">
                <i class="fa-solid fa-xmark mr-1"></i>Réinitialiser
            </a>
        </div>
    </form>
</div>

{{-- Weekly Timetable --}}
@php
    $days = ['monday'=>'Lundi','tuesday'=>'Mardi','wednesday'=>'Mercredi','thursday'=>'Jeudi','friday'=>'Vendredi'];
    $colors = ['blue','emerald','indigo','amber','rose','violet','cyan'];
    $dayLabels = ['monday'=>'Lun','tuesday'=>'Mar','wednesday'=>'Mer','thursday'=>'Jeu','friday'=>'Ven'];
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
    <div class="grid grid-cols-6 border-b border-slate-100 bg-slate-50">
        <div class="py-4 px-2 text-center text-xs font-bold text-slate-400 uppercase tracking-widest border-r border-slate-100 italic">Heure</div>
        @foreach($days as $dayKey => $dayName)
            <div class="py-4 px-2 text-center text-xs font-bold text-slate-900 uppercase tracking-widest {{ !$loop->last ? 'border-r border-slate-100' : '' }}">{{ $dayName }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-6 divide-x divide-slate-100" style="height:920px;">
        {{-- Hours Column --}}
        <div class="flex flex-col">
            @foreach(['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00'] as $hour)
                <div class="flex-1 flex items-start justify-center pt-1 text-[10px] font-bold text-slate-400 border-b border-slate-50">{{ $hour }}</div>
            @endforeach
        </div>

        @foreach($days as $dayKey => $dayName)
        <div class="relative flex flex-col">
            @foreach($schedules->where('day_of_week', $dayKey) as $sched)
                @php
                    $startH = intval(date('H', strtotime($sched->start_time)));
                    $startM = intval(date('i', strtotime($sched->start_time)));
                    $endH   = intval(date('H', strtotime($sched->end_time)));
                    $endM   = intval(date('i', strtotime($sched->end_time)));
                    $top    = (($startH - 8) + ($startM / 60)) * (100/15);
                    $height = max(4, ((($endH - 8) + ($endM / 60)) * (100/15)) - $top);
                    $color  = $colors[$loop->index % count($colors)];
                @endphp
                <div style="top:{{ $top }}%; height:{{ $height }}%;"
                     class="absolute inset-x-0.5 bg-{{ $color }}-50 border-l-4 border-{{ $color }}-500 rounded-lg px-1.5 py-1 shadow-sm hover:shadow-md transition-shadow cursor-pointer z-10 overflow-hidden group">
                    <span class="block text-[9px] font-bold text-{{ $color }}-600 leading-tight">{{ date('H:i', strtotime($sched->start_time)) }}-{{ date('H:i', strtotime($sched->end_time)) }}</span>
                    <p class="text-[10px] font-bold text-slate-800 leading-tight truncate">{{ $sched->title }}</p>
                    <p class="text-[9px] text-slate-500 truncate">{{ optional($sched->studentClass)->class_name }}</p>
                    {{-- Actions overlay on hover --}}
                    <div class="absolute inset-0 bg-white/90 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-2">
                        <button onclick='openEditModal({{ json_encode($sched) }})' class="w-7 h-7 bg-blue-500 text-white rounded-lg flex items-center justify-center hover:bg-blue-600 text-xs">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('dashboard.admin.schedules.destroy', $sched->id) }}" method="POST" onsubmit="return confirm('Supprimer ce créneau ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-7 h-7 bg-red-500 text-white rounded-lg flex items-center justify-center hover:bg-red-600 text-xs">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
            {{-- Hour lines --}}
            @for($i = 0; $i < 16; $i++)<div class="flex-1 border-b border-slate-50"></div>@endfor
        </div>
        @endforeach
    </div>
</div>

{{-- Table List --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-900">Tous les créneaux <span class="text-slate-400 font-normal text-sm">({{ $schedules->count() }})</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-xs uppercase text-slate-400 tracking-wide bg-slate-50">
                    <th class="px-5 py-3 text-left font-bold">Titre</th>
                    <th class="px-5 py-3 text-left font-bold">Professeur</th>
                    <th class="px-5 py-3 text-left font-bold">Classe</th>
                    <th class="px-5 py-3 text-left font-bold">Matière</th>
                    <th class="px-5 py-3 text-left font-bold">Jour</th>
                    <th class="px-5 py-3 text-left font-bold">Horaire</th>
                    <th class="px-5 py-3 text-left font-bold">Salle</th>
                    <th class="px-5 py-3 text-center font-bold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($schedules as $sched)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-3.5 font-bold text-slate-900">{{ $sched->title }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ optional($sched->professor)->name ?? '—' }}</td>
                    <td class="px-5 py-3.5">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg">{{ optional($sched->studentClass)->class_name ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600">{{ optional($sched->subject)->subject_name ?? '—' }}</td>
                    <td class="px-5 py-3.5">
                        @php $dayMap = ['monday'=>'Lundi','tuesday'=>'Mardi','wednesday'=>'Mercredi','thursday'=>'Jeudi','friday'=>'Vendredi','saturday'=>'Samedi','sunday'=>'Dimanche']; @endphp
                        <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg">{{ $dayMap[$sched->day_of_week] ?? $sched->day_of_week }}</span>
                    </td>
                    <td class="px-5 py-3.5 font-mono text-xs text-slate-700">
                        {{ date('H:i', strtotime($sched->start_time)) }} – {{ date('H:i', strtotime($sched->end_time)) }}
                    </td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $sched->room ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick='openEditModal({{ json_encode($sched) }})'
                                    class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-all">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form action="{{ route('dashboard.admin.schedules.destroy', $sched->id) }}" method="POST" onsubmit="return confirm('Supprimer ce créneau ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-16 text-center text-slate-400 italic">
                        <i class="fa-regular fa-calendar-xmark text-4xl mb-3 block text-slate-300"></i>
                        Aucun créneau trouvé. Créez-en un en cliquant sur « Nouveau Créneau ».
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ============ ADD MODAL ============ --}}
<div id="addModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">➕ Nouveau Créneau</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center bg-slate-50 rounded-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('dashboard.admin.schedules.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Titre *</label>
                    <input type="text" name="title" placeholder="ex: Mathématiques - Cours" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Professeur *</label>
                    <select name="user_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner...</option>
                        @foreach($professors as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Classe *</label>
                    <select name="student_class_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner...</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Matière *</label>
                    <select name="subject_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner une matière...</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->subject_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Jour *</label>
                    <select name="day_of_week" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner...</option>
                        @foreach(['monday'=>'Lundi','tuesday'=>'Mardi','wednesday'=>'Mercredi','thursday'=>'Jeudi','friday'=>'Vendredi','saturday'=>'Samedi','sunday'=>'Dimanche'] as $k=>$v)
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Salle (optionnel)</label>
                    <input type="text" name="room" placeholder="ex: Salle A12"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Heure début *</label>
                    <input type="time" name="start_time" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Heure fin *</label>
                    <input type="time" name="end_time" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Description (optionnel)</label>
                    <textarea name="description" rows="2" placeholder="Informations complémentaires..."
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all">Annuler</button>
                <button type="submit"
                        class="flex-[2] py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">Créer le créneau</button>
            </div>
        </form>
    </div>
</div>

{{-- ============ EDIT MODAL ============ --}}
<div id="editModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900">✏️ Modifier le Créneau</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 w-8 h-8 flex items-center justify-center bg-slate-50 rounded-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Titre *</label>
                    <input type="text" name="title" id="edit_title" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Professeur *</label>
                    <select name="user_id" id="edit_user_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($professors as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Classe *</label>
                    <select name="student_class_id" id="edit_class_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->class_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Matière *</label>
                    <select name="subject_id" id="edit_subject_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->subject_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Jour *</label>
                    <select name="day_of_week" id="edit_day" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['monday'=>'Lundi','tuesday'=>'Mardi','wednesday'=>'Mercredi','thursday'=>'Jeudi','friday'=>'Vendredi','saturday'=>'Samedi','sunday'=>'Dimanche'] as $k=>$v)
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Salle</label>
                    <input type="text" name="room" id="edit_room"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Heure début *</label>
                    <input type="time" name="start_time" id="edit_start" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Heure fin *</label>
                    <input type="time" name="end_time" id="edit_end" required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Description</label>
                    <textarea name="description" id="edit_description" rows="2"
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
            </div>
            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="flex-1 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-all">Annuler</button>
                <button type="submit"
                        class="flex-[2] py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(sched) {
    document.getElementById('editForm').action = '/dashboard/admin/schedules/' + sched.id;
    document.getElementById('edit_title').value       = sched.title ?? '';
    document.getElementById('edit_user_id').value     = sched.user_id;
    document.getElementById('edit_class_id').value    = sched.student_class_id;
    document.getElementById('edit_subject_id').value  = sched.subject_id;
    document.getElementById('edit_day').value         = sched.day_of_week;
    document.getElementById('edit_room').value        = sched.room ?? '';
    document.getElementById('edit_start').value       = sched.start_time ? sched.start_time.substring(0,5) : '';
    document.getElementById('edit_end').value         = sched.end_time ? sched.end_time.substring(0,5) : '';
    document.getElementById('edit_description').value = sched.description ?? '';
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endsection
