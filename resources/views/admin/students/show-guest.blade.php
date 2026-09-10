@extends('layouts.app')

@section('title', 'Detail Siswa - ' . ($user->full_name ?? $user->name))
@section('page-title', 'Profil Calon Siswa')
@section('page-subtitle', 'Data akun siswa (belum mengisi formulir pendaftaran)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-8">
        {{-- Student Profile Header --}}
        <div class="card-glass rounded-3xl p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            
            <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                <div class="w-32 h-32 rounded-3xl bg-primary/10 flex items-center justify-center text-primary text-4xl font-black border border-primary/20 shadow-2xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-black themed-text tracking-tight mb-2">{{ $user->full_name ?? $user->name }}</h2>
                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                        <span class="px-3 py-1 rounded-lg bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest border border-primary/20">
                            {{ $user->educationalLevel?->name ?? 'Belum Dipilih' }}
                        </span>
                        <span class="px-3 py-1 rounded-lg bg-slate-500/10 text-slate-400 text-[10px] font-bold uppercase tracking-widest border border-slate-500/20">
                            Status: TAMU
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Registrasi Awal --}}
        <div class="card-glass rounded-3xl p-8">
            <h3 class="text-xs font-bold text-sky-400 uppercase tracking-widest mb-6 border-b pb-4" :style="'border-color: var(--border-color)'">Registrasi Awal</h3>
            <div class="space-y-4">
                @php
                    $initData = [
                        'Nama Akun' => $user->name,
                        'Nama Lengkap' => $user->full_name,
                        'Email' => $user->email,
                        'No. WhatsApp' => $user->whatsapp_number,
                        'Asal Sekolah' => $user->asal_sekolah,
                        'Tujuan Masuk' => $user->educationalLevel?->name,
                        'Alasan Memilih' => $user->alasan_memilih,
                        'Sumber Informasi' => $user->sumber_informasi . ($user->sumber_informasi_tambahan ? ' (' . $user->sumber_informasi_tambahan . ')' : ''),
                        'Tanggal Daftar Akun' => $user->created_at->format('d F Y, H:i'),
                    ];
                @endphp
                @foreach($initData as $l => $v)
                <div class="flex flex-col gap-1 border-b pb-2" :style="'border-color: var(--border-color)'">
                    <span class="text-[9px] themed-text-muted uppercase tracking-widest font-bold">{{ $l }}</span>
                    <span class="text-sm themed-text font-medium leading-relaxed">{{ $v ?? '-' }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Info --}}
        <div class="card-glass rounded-3xl p-8 border-l-4 border-amber-500">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-amber-400 mb-1">Siswa Belum Mengisi Formulir</p>
                    <p class="text-xs themed-text-muted leading-relaxed">Siswa ini baru membuat akun dan belum melengkapi formulir pendaftaran. Data biodata, alamat, dan orang tua belum tersedia.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Side Actions --}}
    <div class="space-y-6">
        {{-- Status --}}
        <div class="card-glass rounded-3xl p-8 border-t-4 border-slate-500">
            <h3 class="text-xs font-bold themed-text uppercase tracking-widest mb-6 flex items-center justify-between">
                Status Pendaftaran
                <span class="px-3 py-1 rounded-lg bg-slate-500/10 text-slate-400 text-[10px] font-bold uppercase tracking-widest border border-slate-500/20">TAMU</span>
            </h3>
            <p class="text-xs themed-text-muted leading-relaxed">Siswa baru membuat akun. Belum mengisi formulir pendaftaran dan belum melakukan pembayaran.</p>
        </div>

        {{-- Aksi --}}
        <div class="space-y-3">
            <button x-data @click="$dispatch('open-modal', 'modal-reset-password')" class="w-full py-3 rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/20 hover:bg-amber-500/20 transition-all text-xs font-bold uppercase tracking-widest block text-center">Reset Password</button>
        </div>
    </div>
</div>

{{-- Modal Reset Password --}}
<div x-data="{ open: false }" @open-modal.window="if($event.detail === 'modal-reset-password') open = true" x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
    <div @click.away="open = false" class="card-glass rounded-3xl p-8 w-full max-w-md shadow-2xl scale-in-center">
        <h3 class="text-xl font-bold themed-text mb-4">Reset Password</h3>
        <p class="text-sm themed-text-muted mb-6">Masukkan password baru untuk akun siswa ini. Minimal 8 karakter.</p>
        
        <form action="{{ route('admin.students.reset-password-by-user', $user) }}" method="POST" class="space-y-6" x-data="{ showPassword: false }">
            @csrf
            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Password Baru</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" name="password" required minlength="8" class="w-full themed-input rounded-xl px-4 py-3 text-sm transition-all pr-10" placeholder="••••••••">
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-themed-text-muted hover:text-themed-text transition-colors">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Konfirmasi Password</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" required minlength="8" class="w-full themed-input rounded-xl px-4 py-3 text-sm transition-all pr-10" placeholder="••••••••">
                </div>
            </div>
            
            <div class="flex gap-4">
                <button type="button" @click="open = false" class="flex-1 py-3 rounded-xl btn-soft-secondary font-bold text-xs uppercase tracking-widest">Batal</button>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-amber-500 text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-amber-500/50">Simpan Password</button>
            </div>
        </form>
    </div>
</div>

@endsection
