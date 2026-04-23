@extends('layouts.app')

@section('title', 'Manajemen Jadwal Ujian')
@section('page-title', 'Jadwal Ujian')
@section('page-subtitle', 'Kelola slot waktu dan kuota ujian unit ' . auth()->user()->getUnit())

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Form Tambah Jadwal --}}
    <div class="card-glass rounded-3xl p-8 h-fit lg:sticky lg:top-8 border border-white/5 shadow-2xl">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-black themed-text leading-none">Tambah Sesi</h3>
                <p class="text-[10px] themed-text-muted uppercase tracking-widest mt-1">Buat jadwal ujian baru</p>
            </div>
        </div>

        <form action="{{ route('admin.schedules.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Nama Sesi</label>
                <div class="relative">
                    <input type="text" name="name" required placeholder="Contoh: Gelombang 1 - Sesi 1"
                           class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Unit / Jenjang</label>
                <select name="educational_level_id" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none" required>
                    <option value="" disabled selected>Pilih Jenjang</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" class="text-slate-900">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Tanggal Ujian</label>
                <input type="text" name="date" required readOnly
                       class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all datepicker" placeholder="Pilih Tanggal">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Jam Mulai</label>
                    <input type="time" name="time_start" required
                           class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Jam Selesai</label>
                    <input type="time" name="time_end" required
                           class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                </div>
            </div>

            <button type="submit" class="w-full py-5 rounded-2xl bg-primary text-white font-black uppercase tracking-[0.2em] text-xs shadow-[0_10px_30px_-10px_rgba(var(--primary-rgb),0.5)] hover:shadow-primary/40 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Publikasikan Jadwal</span>
            </button>
        </form>
    </div>

    {{-- Daftar Jadwal (Table Format) --}}
    <div class="lg:col-span-2">
        <div class="card-glass rounded-3xl overflow-hidden border border-white/5 shadow-2xl">
            @if($schedules->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse datatable">
                    <thead>
                        <tr class="bg-black/20 border-b border-white/5">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">No</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Sesi & Unit</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Waktu Pelaksanaan</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Peserta</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted text-right" data-dt-order="disable">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($schedules as $index => $schedule)
                        <tr class="hover:bg-primary/5 transition-all group">
                            <td class="px-6 py-4 text-xs font-bold themed-text-muted">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-black themed-text group-hover:text-primary transition-colors mb-1">{{ $schedule->name }}</p>
                                <span class="px-2 py-0.5 rounded-md bg-white/5 border border-white/10 text-[8px] font-black themed-text-muted uppercase tracking-widest">
                                    {{ $schedule->educationalLevel->name ?? 'Global' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z"/></svg>
                                        <span class="text-[10px] font-black themed-text">{{ date('d M Y', strtotime($schedule->date)) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[10px] themed-text-muted font-bold tracking-tight">{{ substr($schedule->time_start, 0, 5) }} - {{ substr($schedule->time_end, 0, 5) }} WIB</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                                    <span class="text-[10px] font-black themed-text">{{ $schedule->registrations_count }} <span class="text-white/20 ml-0.5">Siswa</span></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 rounded-lg bg-white/5 text-white/20 hover:bg-rose-500/10 hover:text-rose-500 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-16 text-center">
                <div class="w-24 h-24 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z"/>
                    </svg>
                </div>
                <h4 class="themed-text text-lg font-black mb-2 tracking-tight">Belum Ada Jadwal Ujian</h4>
                <p class="text-xs themed-text-muted max-w-[280px] mx-auto leading-relaxed">Gunakan formulir di samping untuk mulai mempublikasikan slot waktu ujian bagi calon siswa.</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Daftar Peserta Ujian --}}
<div class="mt-16">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 px-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-2 h-6 bg-primary rounded-full"></div>
                <h3 class="text-xl font-black themed-text tracking-tight">Peserta Ujian</h3>
            </div>
            <p class="text-[10px] themed-text-muted uppercase tracking-[0.2em]">Data real-time siswa yang telah melakukan pemilihan jadwal</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-5 py-3 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                <span class="text-xs font-black themed-text">{{ $participants->count() }} <span class="text-[10px] themed-text-muted uppercase ml-1">Siswa Terdaftar</span></span>
            </div>
        </div>
    </div>

    <div class="card-glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
        @if($participants->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse datatable">
                    <thead>
                        <tr class="bg-black/20 border-b border-white/5">
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Siswa & Kontak</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Unit Tujuan</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Detail Sesi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($participants as $participant)
                        <tr class="hover:bg-primary/5 transition-all duration-300 group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center text-primary font-black text-sm border border-primary/10 group-hover:scale-110 transition-transform">
                                        {{ substr($participant->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black themed-text group-hover:text-primary transition-colors">{{ $participant->user->full_name ?? $participant->user->name ?? 'Siswa Terhapus' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <svg class="w-3 h-3 themed-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002-2z"/></svg>
                                            <p class="text-[10px] themed-text-muted font-bold tracking-tight">{{ $participant->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[9px] font-black uppercase tracking-widest shadow-sm">
                                    {{ $participant->user->educationalLevel->name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.8)] animate-pulse"></div>
                                    <div>
                                        <p class="text-xs font-black themed-text tracking-tight">{{ $participant->examSchedule->name ?? '-' }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[10px] themed-text-muted font-bold">{{ $participant->examSchedule ? date('d M Y', strtotime($participant->examSchedule->date)) : '-' }}</span>
                                            <span class="w-1 h-1 rounded-full bg-white/20"></span>
                                            <span class="text-[10px] themed-text-muted font-bold">{{ $participant->examSchedule ? substr($participant->examSchedule->time_start, 0, 5) : '-' }} WIB</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-8 py-20 text-center">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                    <div class="absolute inset-0 bg-white/10 rounded-full animate-pulse opacity-20"></div>
                    <svg class="w-10 h-10 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h4 class="themed-text text-base font-black mb-2 tracking-tight">Belum Ada Peserta Terdaftar</h4>
                <p class="text-[10px] themed-text-muted uppercase tracking-widest max-w-[320px] mx-auto leading-relaxed">Daftar ini akan terisi secara otomatis saat siswa memilih jadwal ujian mereka melalui portal siswa.</p>
            </div>
        @endif
    </div>
</div>
@endsection
