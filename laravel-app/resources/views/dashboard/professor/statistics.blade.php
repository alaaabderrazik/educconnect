@extends('layouts.dashboard')
@section('title', 'Statistiques')
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Statistiques 📊</h1>
        <p class="text-slate-500 text-sm mt-1">Vue d'ensemble des performances de vos classes.</p>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm text-center">
            <div class="text-4xl font-black {{ $averageGrade >= 10 ? 'text-indigo-600' : 'text-rose-600' }}">{{ $averageGrade }}</div>
            <p class="text-xs text-slate-500 mt-2 font-medium">Moyenne Générale (/20)</p>
            <div class="w-full bg-slate-100 rounded-full h-2 mt-3">
                <div class="{{ $averageGrade >= 10 ? 'bg-indigo-500' : 'bg-rose-500' }} h-2 rounded-full" style="width:{{ ($averageGrade/20)*100 }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm text-center">
            <div class="text-4xl font-black {{ $attendanceRate >= 80 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $attendanceRate }}%</div>
            <p class="text-xs text-slate-500 mt-2 font-medium">Taux de Présence Global</p>
            <div class="w-full bg-slate-100 rounded-full h-2 mt-3">
                <div class="{{ $attendanceRate >= 80 ? 'bg-emerald-500' : 'bg-rose-500' }} h-2 rounded-full" style="width:{{ $attendanceRate }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm text-center">
            <div class="text-4xl font-black text-blue-600">{{ $assignmentsDone }}</div>
            <p class="text-xs text-slate-500 mt-2 font-medium">Devoirs Créés</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm text-center">
            <div class="text-4xl font-black text-indigo-600">{{ $totalSubmissions }}</div>
            <p class="text-xs text-slate-500 mt-2 font-medium">Soumissions Reçues</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm text-center">
            <div class="text-4xl font-black text-amber-600">{{ $gradedSubmissions }}</div>
            <p class="text-xs text-slate-500 mt-2 font-medium">Soumissions Corrigées</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Attendance per Class --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-black text-slate-900 mb-6">Présence par Classe</h3>
            <div class="relative h-72">
                <canvas id="classAttendanceChart"></canvas>
            </div>
        </div>

        {{-- Submissions Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-black text-slate-900 mb-6">État des Soumissions</h3>
            <div class="relative h-72 flex items-center justify-center">
                @if($totalSubmissions > 0)
                <canvas id="submissionsChart"></canvas>
                @else
                <p class="text-slate-400 italic">Aucune soumission reçue.</p>
                @endif
            </div>
        </div>

        {{-- Per Class Attendance Table --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="font-black text-slate-900">Détail par Classe</h3>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
                        <th class="px-6 py-3">Classe</th>
                        <th class="px-6 py-3">Taux de Présence</th>
                        <th class="px-6 py-3">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($classAttendance as $c)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-sm text-slate-900">{{ $c['name'] }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 bg-slate-100 rounded-full h-2">
                                    <div class="{{ $c['rate'] >= 80 ? 'bg-emerald-500' : 'bg-amber-500' }} h-2 rounded-full" style="width:{{ $c['rate'] }}%"></div>
                                </div>
                                <span class="text-sm font-black {{ $c['rate'] >= 80 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $c['rate'] }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-[10px] font-black {{ $c['rate'] >= 80 ? 'bg-emerald-50 text-emerald-600' : ($c['rate'] >= 60 ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600') }}">
                                {{ $c['rate'] >= 80 ? 'Excellente' : ($c['rate'] >= 60 ? 'Moyenne' : 'Insuffisante') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    @if(empty($classAttendance))
                    <tr><td colspan="3" class="px-6 py-10 text-center text-slate-400 italic">Aucune donnée de présence.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const classData = @json($classAttendance);

    // Bar Chart: Attendance per class
    new Chart(document.getElementById('classAttendanceChart'), {
        type: 'bar',
        data: {
            labels: classData.map(c => c.name),
            datasets: [{
                label: 'Taux de présence (%)',
                data: classData.map(c => c.rate),
                backgroundColor: classData.map(c => c.rate >= 80 ? 'rgba(16,185,129,0.8)' : 'rgba(245,158,11,0.8)'),
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { min: 0, max: 100, grid: { color: '#f1f5f9' }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        }
    });

    // Donut chart: Submissions graded vs not
    @if($totalSubmissions > 0)
    new Chart(document.getElementById('submissionsChart'), {
        type: 'doughnut',
        data: {
            labels: ['Corrigées', 'En attente'],
            datasets: [{
                data: [{{ $gradedSubmissions }}, {{ $totalSubmissions - $gradedSubmissions }}],
                backgroundColor: ['#10b981', '#f59e0b'],
                borderWidth: 0, borderRadius: 4,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { weight: 'bold' } } }
            }
        }
    });
    @endif
});
</script>
@endsection
