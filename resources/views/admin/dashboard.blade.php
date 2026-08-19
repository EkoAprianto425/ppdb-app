@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Statistik pendaftaran unit ' . ($stats['unit'] ?? ''))

@section('content')
<div class="space-y-8" x-data="{ activeTab: '{{ $stats['levels'][0]['name'] ?? 'None' }}' }">
    {{-- 1. Summary Cards (Compact) --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Total --}}
        <div class="card-glass rounded-2xl p-4 border-l-4 border-primary">
            <p class="text-[9px] font-bold themed-text-muted uppercase tracking-widest mb-1">Total</p>
            <h3 class="text-2xl font-black themed-text">{{ $stats['summary']['total'] }}</h3>
        </div>
        {{-- Tamu --}}
        <div class="card-glass rounded-2xl p-4 border-l-4 border-slate-500">
            <p class="text-[9px] font-bold themed-text-muted uppercase tracking-widest mb-1">Tamu</p>
            <h3 class="text-2xl font-black themed-text">{{ $stats['summary']['tamu'] }}</h3>
        </div>
        {{-- Formulir --}}
        <div class="card-glass rounded-2xl p-4 border-l-4 border-indigo-500">
            <p class="text-[9px] font-bold themed-text-muted uppercase tracking-widest mb-1">Formulir</p>
            <h3 class="text-2xl font-black text-indigo-400">{{ $stats['summary']['formulir'] }}</h3>
        </div>
        {{-- Lulus --}}
        <div class="card-glass rounded-2xl p-4 border-l-4 border-amber-500">
            <p class="text-[9px] font-bold themed-text-muted uppercase tracking-widest mb-1">Lulus</p>
            <h3 class="text-2xl font-black text-amber-500">{{ $stats['summary']['lulus'] }}</h3>
        </div>
        {{-- Daftar --}}
        <div class="card-glass rounded-2xl p-4 border-l-4 border-emerald-500">
            <p class="text-[9px] font-bold themed-text-muted uppercase tracking-widest mb-1">Daftar</p>
            <h3 class="text-2xl font-black text-emerald-500">{{ $stats['summary']['daftar'] }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- 2. Distribution Chart --}}
        <div class="lg:col-span-1 card-glass rounded-3xl p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-xs font-black themed-text uppercase tracking-widest">Distribusi Global</h4>
                <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
            </div>
            <div class="flex-1 min-h-[250px] relative">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="p-3 rounded-2xl bg-white/5 border border-white/5 text-center">
                    <p class="text-[8px] themed-text-muted uppercase font-bold">Terverifikasi</p>
                    <p class="text-sm font-black text-emerald-500">
                        {{ $stats['summary']['total'] > 0 ? round(($stats['summary']['daftar'] / $stats['summary']['total']) * 100) : 0 }}%
                    </p>
                </div>
                <div class="p-3 rounded-2xl bg-white/5 border border-white/5 text-center">
                    <p class="text-[8px] themed-text-muted uppercase font-bold">Diterima</p>
                    <p class="text-sm font-black text-amber-500">
                        {{ $stats['summary']['total'] > 0 ? round(($stats['summary']['lulus'] / $stats['summary']['total']) * 100) : 0 }}%
                    </p>
                </div>
            </div>
        </div>

        {{-- 3. Unit Analysis Graphic --}}
        <div class="lg:col-span-2 card-glass rounded-3xl p-6 flex flex-col">
            <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                <h4 class="text-xs font-black themed-text uppercase tracking-widest">Rincian Per Unit</h4>
                <div class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[8px] font-bold uppercase tracking-widest">Model Grafik</div>
            </div>

            <div class="flex-1 flex flex-col justify-center">
                <p class="text-[9px] font-bold themed-text-muted uppercase tracking-widest mb-6 text-center">Perbandingan Status Pendaftaran Antar Unit (SMP, SMA, SMK)</p>
                <div class="h-[350px] relative">
                    <canvas id="unitComparisonChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- 4. Status per Wave per Level (Detailed Grid) --}}
        @php
            $waveCharts = [
                ['id' => 'waveTamuChart', 'title' => 'Tamu per Gelombang', 'status' => 'Tamu'],
                ['id' => 'waveFormulirChart', 'title' => 'Formulir per Gelombang', 'status' => 'Formulir'],
                ['id' => 'waveLulusChart', 'title' => 'Lulus per Gelombang', 'status' => 'Lulus'],
                ['id' => 'waveDaftarChart', 'title' => 'Daftar per Gelombang', 'status' => 'Daftar'],
            ];
        @endphp

        @foreach($waveCharts as $wc)
        <div class="card-glass rounded-3xl p-6 flex flex-col h-[350px]">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-[10px] font-black themed-text uppercase tracking-widest">{{ $wc['title'] }}</h4>
                <div class="w-2 h-2 rounded-full bg-primary/40 animate-pulse"></div>
            </div>
            <div class="flex-1 relative">
                @if(count($stats['wave_names']) > 0)
                    <canvas id="{{ $wc['id'] }}"></canvas>
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-center p-8">
                        <p class="text-[8px] font-bold themed-text-muted uppercase tracking-widest italic">Tidak ada data gelombang</p>
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- 5. Recent Activity --}}
    <div class="card-glass rounded-3xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h4 class="text-xs font-black themed-text uppercase tracking-widest">Pendaftar Terkini</h4>
            <a href="{{ route('admin.students.index') }}" class="text-[10px] font-bold text-primary hover:underline uppercase tracking-widest">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-white/5">
                        <th class="px-4 py-3 text-[10px] font-bold themed-text-muted uppercase">Siswa</th>
                        <th class="px-4 py-3 text-[10px] font-bold themed-text-muted uppercase text-center">Unit</th>
                        <th class="px-4 py-3 text-[10px] font-bold themed-text-muted uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($stats['recent_students'] as $student)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary font-bold text-xs">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold themed-text">{{ $student->full_name ?? $student->name }}</p>
                                    <p class="text-[10px] themed-text-muted">{{ $student->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="text-[10px] font-bold text-primary px-2 py-0.5 rounded-md bg-white/5 border border-white/10 uppercase">
                                {{ $student->educationalLevel->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <a href="{{ route('admin.students.index', ['search' => $student->email]) }}" class="p-2 rounded-lg bg-white/5 text-slate-400 hover:text-white transition-all inline-block">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-[10px] themed-text-muted uppercase font-bold italic">Belum ada aktivitas baru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Helper to get CSS variables
        const style = getComputedStyle(document.documentElement);
        const getPrimaryColor = () => style.getPropertyValue('--primary-color').trim() || '#0ea5e9';
        const getPrimaryRgb = () => style.getPropertyValue('--primary-rgb').trim() || '14, 165, 233';

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#94a3b8', font: { size: 10, weight: 'bold' }, padding: 15 }
                },
                tooltip: {
                    backgroundColor: '#081b33',
                    padding: 12,
                    displayColors: true,
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1
                }
            },
            cutout: '75%'
        };

        const safeInitChart = (id, config) => {
            const el = document.getElementById(id);
            if (el) {
                try {
                    return new Chart(el.getContext('2d'), config);
                } catch (e) {
                    console.error('Chart init error [' + id + ']:', e);
                }
            }
            return null;
        };

        // 1. Global Status Chart
        safeInitChart('statusChart', {
            type: 'doughnut',
            data: {
                labels: ['Tamu', 'Formulir', 'Lulus', 'Daftar'],
                datasets: [{
                    data: [{{ $stats['summary']['tamu'] }}, {{ $stats['summary']['formulir'] }}, {{ $stats['summary']['lulus'] }}, {{ $stats['summary']['daftar'] }}],
                    backgroundColor: ['rgba(148, 163, 184, 0.6)', 'rgba(99, 102, 241, 0.6)', 'rgba(245, 158, 11, 0.6)', 'rgba(16, 185, 129, 0.6)'],
                    borderColor: ['rgba(148, 163, 184, 1)', 'rgba(99, 102, 241, 1)', 'rgba(245, 158, 11, 1)', 'rgba(16, 185, 129, 1)'],
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });

        // 3. Detailed Wave-Status Charts
        const waveNames = {!! json_encode($stats['wave_names']) !!};
        const levelNames = {!! json_encode(collect($stats['levels'])->pluck('name')) !!};
        
        // Colors inspired by the example image
        const levelColors = [
            '#0ea5e9', // Blue (SMP)
            '#10b881', // Emerald (SMP Progresif)
            '#f59e0b', // Amber (SMA Plus)
            '#f43f5e', // Rose (SMA Progresif)
            '#8b5cf6', // Violet (SMK Bisnis)
            '#0284c7', // Sky (SMK PB)
            '#059669'  // Green (SMK TJKT)
        ];
        
        if (waveNames && waveNames.length > 0) {
            @foreach(['Tamu', 'Formulir', 'Lulus', 'Daftar'] as $status)
            safeInitChart('wave{{ $status }}Chart', {
                type: 'bar',
                data: {
                    labels: waveNames,
                    datasets: [
                        @foreach($stats['levels'] as $idx => $level)
                        {
                            label: '{{ $level['name'] }}',
                            data: [
                                @foreach($stats['wave_names'] as $waveName)
                                    {{ $stats['detailed_waves'][$status][$waveName][$level['name']] ?? 0 }},
                                @endforeach
                            ],
                            backgroundColor: levelColors[{{ $idx }} % levelColors.length],
                            borderRadius: 4,
                            barPercentage: 0.8,
                            categoryPercentage: 0.8
                        },
                        @endforeach
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom', 
                            labels: { color: '#94a3b8', font: { size: 8, weight: 'bold' }, padding: 10, usePointStyle: true } 
                        },
                        tooltip: { backgroundColor: '#081b33', padding: 10 }
                    },
                    scales: {
                        x: { 
                            stacked: false, // GROUPED, NOT STACKED
                            grid: { display: false }, 
                            ticks: { color: '#94a3b8', font: { size: 9, weight: 'bold' } } 
                        },
                        y: { 
                            stacked: false, // GROUPED, NOT STACKED
                            beginAtZero: true,
                            grid: { color: 'rgba(255,255,255,0.05)' }, 
                            ticks: { color: '#94a3b8', font: { size: 9 } } 
                        }
                    }
                }
            });
            @endforeach
        }

        // 4. Unit Comparison Chart (Grouped Bar)
        const unitLabels = {!! json_encode(collect($stats['levels'])->pluck('name')) !!};
        if (unitLabels && unitLabels.length > 0) {
            safeInitChart('unitComparisonChart', {
                type: 'bar',
                data: {
                    labels: unitLabels,
                    datasets: [
                        { label: 'Tamu', data: {!! json_encode(collect($stats['levels'])->pluck('tamu')) !!}, backgroundColor: 'rgba(148, 163, 184, 0.8)', borderRadius: 4 },
                        { label: 'Formulir', data: {!! json_encode(collect($stats['levels'])->pluck('formulir')) !!}, backgroundColor: 'rgba(99, 102, 241, 0.8)', borderRadius: 4 },
                        { label: 'Lulus', data: {!! json_encode(collect($stats['levels'])->pluck('lulus')) !!}, backgroundColor: 'rgba(245, 158, 11, 0.8)', borderRadius: 4 },
                        { label: 'Daftar', data: {!! json_encode(collect($stats['levels'])->pluck('daftar')) !!}, backgroundColor: 'rgba(16, 185, 129, 0.8)', borderRadius: 4 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom', 
                            labels: { color: '#94a3b8', font: { size: 10, weight: 'bold' }, padding: 15, usePointStyle: true } 
                        },
                        tooltip: { backgroundColor: '#081b33', padding: 12 }
                    },
                    scales: {
                        x: { 
                            stacked: false, // GROUPED
                            grid: { display: false }, 
                            ticks: { color: '#94a3b8', font: { size: 10, weight: 'bold' } } 
                        },
                        y: { 
                            stacked: false, // GROUPED
                            beginAtZero: true,
                            grid: { color: 'rgba(255,255,255,0.05)' }, 
                            ticks: { color: '#94a3b8', font: { size: 10 } } 
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
