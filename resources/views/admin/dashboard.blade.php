@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Statistik pendaftaran unit ' . ($stats['unit'] ?? ''))

@section('content')
<div class="space-y-8">
    {{-- Header Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total --}}
        <div class="card-glass rounded-3xl p-8 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 group-hover:bg-primary/10 transition-all duration-500"></div>
            <p class="text-[10px] font-bold themed-text-muted uppercase tracking-[0.2em] mb-4">Total Pendaftar</p>
            <div class="flex items-end gap-3">
                <h3 class="text-5xl font-black themed-text tracking-tighter">{{ $stats['total'] }}</h3>
                <span class="text-xs themed-text-muted mb-2 font-bold tracking-widest uppercase">Siswa</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <div class="w-full h-1 bg-primary/10 rounded-full overflow-hidden">
                    <div class="h-full bg-primary" :style="'width: 100%'"></div>
                </div>
            </div>
        </div>

        {{-- Pending Payment --}}
        <div class="card-glass rounded-3xl p-8 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full -mr-16 -mt-16 group-hover:bg-amber-500/10 transition-all duration-500"></div>
            <p class="text-[10px] font-bold themed-text-muted uppercase tracking-[0.2em] mb-4">Menunggu Verifikasi</p>
            <div class="flex items-end gap-3">
                <h3 class="text-5xl font-black themed-text tracking-tighter text-amber-500">{{ $stats['pending'] }}</h3>
                <span class="text-xs themed-text-muted mb-2 font-bold tracking-widest uppercase">Siswa</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <div class="w-full h-1 bg-amber-500/10 rounded-full overflow-hidden">
                    @php $pendingPerc = $stats['total'] > 0 ? ($stats['pending'] / $stats['total']) * 100 : 0 @endphp
                    <div class="h-full bg-amber-500" style="width: {{ $pendingPerc }}%"></div>
                </div>
            </div>
        </div>

        {{-- Verified --}}
        <div class="card-glass rounded-3xl p-8 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16 group-hover:bg-emerald-500/10 transition-all duration-500"></div>
            <p class="text-[10px] font-bold themed-text-muted uppercase tracking-[0.2em] mb-4">Terverifikasi</p>
            <div class="flex items-end gap-3">
                <h3 class="text-5xl font-black themed-text tracking-tighter text-emerald-500">{{ $stats['success'] }}</h3>
                <span class="text-xs themed-text-muted mb-2 font-bold tracking-widest uppercase">Siswa</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <div class="w-full h-1 bg-emerald-500/10 rounded-full overflow-hidden">
                    @php $successPerc = $stats['total'] > 0 ? ($stats['success'] / $stats['total']) * 100 : 0 @endphp
                    <div class="h-full bg-emerald-500" style="width: {{ $successPerc }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions & Recently Registered --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="card-glass rounded-3xl p-8 border border-white/5">
            <h3 class="text-lg font-bold themed-text mb-6">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.students.index') }}" class="p-6 rounded-2xl bg-primary/5 hover:bg-primary/10 border border-primary/10 transition-all group">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold themed-text">Manajemen Siswa</p>
                    <p class="text-[10px] themed-text-muted mt-1 uppercase tracking-widest">Lihat & Filter Data</p>
                </a>
                
                <a href="{{ route('admin.schedules.index') }}" class="p-6 rounded-2xl bg-indigo-500/5 hover:bg-indigo-500/10 border border-indigo-500/10 transition-all group">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-bold themed-text">Kelola Ujian</p>
                    <p class="text-[10px] themed-text-muted mt-1 uppercase tracking-widest">Input Slot Ujian</p>
                </a>
            </div>
        </div>

        <div class="card-glass rounded-3xl p-8 border border-white/5">
            <h3 class="text-lg font-bold themed-text mb-6">Informasi Unit</h3>
            <div class="space-y-6">
                <div class="p-6 rounded-2xl bg-card-bg border" :style="'border-color: var(--border-color)'">
                    <p class="text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2">Unit Saat Ini</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary font-bold text-xl">
                            {{ $stats['unit'] }}
                        </div>
                        <div>
                            <p class="themed-text font-bold">Administrator Unit {{ $stats['unit'] }}</p>
                            <p class="text-[10px] themed-text-muted">Hak akses terbatas pada manajemen pendaftar unit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
