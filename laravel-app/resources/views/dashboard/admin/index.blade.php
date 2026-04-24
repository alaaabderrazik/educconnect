@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('user_name', auth()->user()->name)
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')

    <!-- Top Action Buttons & Title -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Vue d'ensemble</h1>
            <p class="text-slate-500 text-sm mt-1">Bonjour {{ auth()->user()->name }}, voici le résumé de l'activité d'aujourd'hui.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.admin.pages') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl shadow-sm text-sm font-semibold transition-all hover:border-slate-300">
                <i class="fa-solid fa-pen-to-square text-blue-600"></i> Modifier Site Public
            </a>
            <a href="{{ route('dashboard.admin.students') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm shadow-emerald-500/20 text-sm font-semibold transition-all hover:-translate-y-0.5">
                <i class="fa-solid fa-users"></i> Gérer Étudiants
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Students -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i class="fa-solid fa-user-graduate text-xl"></i>
                </div>
            </div>
            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Étudiants</h3>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['students_count'] }}</span>
            </div>
        </div>

        <!-- Professors -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <i class="fa-solid fa-chalkboard-user text-xl"></i>
                </div>
            </div>
            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Professeurs</h3>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['professors_count'] }}</span>
            </div>
        </div>

        <!-- Courses -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i class="fa-solid fa-book text-xl"></i>
                </div>
            </div>
            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Cours Actifs</h3>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $stats['courses_count'] }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-8 overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-amber-500"></i>
                Activités Récentes
            </h3>
            <a href="{{ route('dashboard.admin.notifications') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors uppercase tracking-wider">Tout voir</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Titre</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Message</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['recent_notifications'] as $notification)
                    <tr class="hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $notification->title }}</td>
                        <td class="px-6 py-4 text-slate-600 text-sm">{{ Str::limit($notification->message, 100) }}</td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $notification->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($notification->link)
                                <a href="{{ $notification->link }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold uppercase tracking-tight">Ouvrir</a>
                            @else
                                <span class="text-slate-300 text-xs font-bold uppercase">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Aucune activité récente.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Bar Chart -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Inscriptions par mois</h3>
            <div class="relative h-64 w-full">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>
        
        <!-- Doughnut Chart -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Répartition par niveau</h3>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="levelChart"></canvas>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ChartJS Configuration
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b'; // slate-500
        
        // Bar Chart (Enrollments)
        const ctxBar = document.getElementById('enrollmentChart');
        if(ctxBar) {
            const ctx = ctxBar.getContext('2d');
            const gradientBlue = ctx.createLinearGradient(0, 0, 0, 400);
            gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.8)'); // blue-500
            gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.2)');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep'],
                    datasets: [{
                        label: 'Nouvelles Inscriptions',
                        data: [65, 85, 120, 95, 110, 85, 60, 150, 210],
                        backgroundColor: gradientBlue,
                        borderRadius: 6,
                        borderSkipped: false,
                        barThickness: 24
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b', // slate-800
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 14, weight: 'bold' },
                            displayColors: false,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9', drawBorder: false }, // slate-100
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            border: { display: false }
                        }
                    }
                }
            });
        }

        // Doughnut Chart (Levels)
        const ctxDoughnut = document.getElementById('levelChart');
        if(ctxDoughnut) {
            new Chart(ctxDoughnut.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($stats['levels_labels']) !!},
                    datasets: [{
                        data: {!! json_encode($stats['levels_data']) !!},
                        backgroundColor: [
                            '#3b82f6', // blue-500
                            '#8b5cf6', // violet-500
                            '#10b981', // emerald-500
                            '#f59e0b', // amber-500
                            '#ef4444', // red-500
                            '#06b6d4', // cyan-500
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 20, usePointStyle: true, pointStyle: 'circle' }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.raw + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
