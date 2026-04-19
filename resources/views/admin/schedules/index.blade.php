@extends('layouts.app')

@section('title', 'Manajemen Jadwal Ujian')
@section('page-title', 'Jadwal Ujian')
@section('page-subtitle', 'Kelola slot waktu dan kuota ujian unit ' . auth()->user()->getUnit())

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Form Tambah Jadwal --}}
    <div class="card-glass rounded-3xl p-8 h-fit lg:sticky lg:top-8">
        <h3 class="text-lg font-bold themed-text mb-6">Tambah Slot Ujian</h3>
        <form action="{{ route('admin.schedules.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Nama Sesi</label>
                <input type="text" name="name" required placeholder="Contoh: Gelombang 1 - Sesi 1"
                       class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Tanggal Ujian</label>
                <input type="text" name="date" required readOnly
                       class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all datepicker" placeholder="Pilih Tanggal">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Jam Mulai</label>
                    <input type="time" name="time_start" required
                           class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Jam Selesai</label>
                    <input type="time" name="time_end" required
                           class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Unit / Jenjang</label>
                <select name="educational_level_id" class="w-full themed-input rounded-xl px-4 py-3 text-sm transition-all appearance-none" required>
                    <option value="" disabled selected>Pilih Jenjang</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl btn-soft-primary font-bold uppercase tracking-widest text-xs">Buat Slot Ujian</button>
        </form>
    </div>

    {{-- Daftar Jadwal --}}
    <div class="lg:col-span-2 space-y-4">
        @forelse($schedules as $schedule)
        <div class="card-glass rounded-3xl p-6 hover:border-primary/30 transition-all group">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 flex flex-col items-center justify-center text-primary border border-primary/20 shadow-lg">
                        <span class="text-[10px] font-black uppercase tracking-tighter">{{ date('M', strtotime($schedule->date)) }}</span>
                        <span class="text-xl font-black leading-none">{{ date('d', strtotime($schedule->date)) }}</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold themed-text group-hover:text-primary transition-colors">{{ $schedule->name }}</h4>
                        <p class="text-[10px] themed-text-muted mt-1 uppercase tracking-widest">
                            {{ date('l', strtotime($schedule->date)) }} • {{ substr($schedule->time_start, 0, 5) }} - {{ substr($schedule->time_end, 0, 5) }} WIB
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                        @csrf @method('DELETE')
                        <button class="p-3 rounded-xl bg-rose-500/10 text-rose-500 border border-rose-500/20 hover:bg-rose-500/20 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="card-glass rounded-3xl p-12 text-center">
            <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z"/>
                </svg>
            </div>
            <p class="themed-text font-bold mb-1">Belum Ada Jadwal Ujian</p>
            <p class="text-xs themed-text-muted">Buat slot ujian pertama untuk unit ini menggunakan form di samping.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Daftar Peserta Ujian --}}
<div class="mt-12">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold themed-text">Peserta Ujian</h3>
            <p class="text-xs themed-text-muted uppercase tracking-widest mt-1">Daftar siswa yang telah memilih jadwal ujian</p>
        </div>
        <div class="px-4 py-2 rounded-xl bg-primary/10 border border-primary/20 text-primary text-xs font-bold">
            Total: {{ $participants->count() }} Siswa
        </div>
    </div>

    <div class="card-glass rounded-3xl overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse datatable">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest themed-text-muted">Siswa</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest themed-text-muted">Unit / Jenjang</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest themed-text-muted">Jadwal Terpilih</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest themed-text-muted text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($participants as $participant)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-xs">
                                    {{ substr($participant->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold themed-text">{{ $participant->user->full_name ?? $participant->user->name }}</p>
                                    <p class="text-[10px] themed-text-muted uppercase tracking-tighter">{{ $participant->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold uppercase">
                                {{ $participant->user->educationalLevel->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                                <div>
                                    <p class="text-xs font-bold themed-text">{{ $participant->examSchedule->name }}</p>
                                    <p class="text-[10px] themed-text-muted uppercase tracking-tighter">{{ date('d M Y', strtotime($participant->examSchedule->date)) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.students.show', $participant) }}" class="inline-flex items-center justify-center p-2 rounded-lg bg-white/5 text-primary hover:bg-primary hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <p class="text-xs themed-text-muted uppercase tracking-widest font-bold">Belum ada siswa yang memilih jadwal</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
