@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Statistik pendaftaran unit ' . ($stats['unit'] ?? ''))

@section('content')
<div class="space-y-8">
    {{-- Header Stats --}}
    {{-- Header Stats --}}
    {{-- Stats by Level --}}
    @foreach($stats['levels'] as $level)
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <div class="px-4 py-1 rounded-full bg-primary/10 border border-primary/20">
                <h2 class="text-sm font-black text-primary uppercase tracking-widest">Unit {{ $level['name'] }}</h2>
            </div>
            <div class="h-px flex-1 bg-white/5"></div>
            <span class="text-[10px] themed-text-muted font-bold uppercase tracking-[0.2em]">{{ $level['total'] }} Pendaftar</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Tamu --}}
            <div class="card-glass rounded-3xl p-6 relative overflow-hidden group border border-white/5 hover:border-white/10 transition-all">
                <div class="absolute top-0 right-0 w-24 h-24 bg-slate-500/5 rounded-full -mr-12 -mt-12 group-hover:bg-slate-500/10 transition-all"></div>
                <p class="text-[9px] font-bold themed-text-muted uppercase tracking-widest mb-4">Tamu</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-4xl font-black themed-text tracking-tighter">{{ $level['tamu'] }}</h3>
                    <span class="text-[10px] themed-text-muted mb-1 font-bold uppercase">Siswa</span>
                </div>
                <p class="text-[8px] themed-text-muted mt-3 uppercase tracking-widest italic opacity-60">Baru Membuat Akun</p>
            </div>

            {{-- Formulir --}}
            <div class="card-glass rounded-3xl p-6 relative overflow-hidden group border border-white/5 hover:border-white/10 transition-all">
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-500/5 rounded-full -mr-12 -mt-12 group-hover:bg-indigo-500/10 transition-all"></div>
                <p class="text-[9px] font-bold themed-text-muted uppercase tracking-widest mb-4">Formulir</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-4xl font-black text-indigo-400 tracking-tighter">{{ $level['formulir'] }}</h3>
                    <span class="text-[10px] themed-text-muted mb-1 font-bold uppercase">Siswa</span>
                </div>
                <p class="text-[8px] themed-text-muted mt-3 uppercase tracking-widest italic opacity-60">Bayar Formulir</p>
            </div>

            {{-- Lulus --}}
            <div class="card-glass rounded-3xl p-6 relative overflow-hidden group border border-white/5 hover:border-white/10 transition-all">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-full -mr-12 -mt-12 group-hover:bg-amber-500/10 transition-all"></div>
                <p class="text-[9px] font-bold themed-text-muted uppercase tracking-widest mb-4">Lulus</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-4xl font-black text-amber-500 tracking-tighter">{{ $level['lulus'] }}</h3>
                    <span class="text-[10px] themed-text-muted mb-1 font-bold uppercase">Siswa</span>
                </div>
                <p class="text-[8px] themed-text-muted mt-3 uppercase tracking-widest italic opacity-60">Dinyatakan Lulus</p>
            </div>

            {{-- Daftar --}}
            <div class="card-glass rounded-3xl p-6 relative overflow-hidden group border border-white/5 hover:border-white/10 transition-all">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/5 rounded-full -mr-12 -mt-12 group-hover:bg-emerald-500/10 transition-all"></div>
                <p class="text-[9px] font-bold themed-text-muted uppercase tracking-widest mb-4">Daftar</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-4xl font-black text-emerald-500 tracking-tighter">{{ $level['daftar'] }}</h3>
                    <span class="text-[10px] themed-text-muted mb-1 font-bold uppercase">Siswa</span>
                </div>
                <p class="text-[8px] themed-text-muted mt-3 uppercase tracking-widest italic opacity-60">Selesai Daftar Ulang</p>
            </div>
        </div>
    </div>
    @endforeach

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
