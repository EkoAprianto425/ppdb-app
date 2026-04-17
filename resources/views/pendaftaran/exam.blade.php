@extends('layouts.app')

@section('title', 'Kartu Ujian')
@section('page-title', 'Kartu Ujian')
@section('page-subtitle', 'Pilih jadwal dan unduh kartu ujian Anda')

@section('content')

@if(session('status'))
<div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium flex items-center gap-3 animate-slide-in">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('status') }}
</div>
@endif

@if($registration->exam_schedule_id)
    {{-- Hasil Kartu Ujian (Unduh) --}}
    <div class="card-glass rounded-3xl p-8 max-w-3xl mx-auto shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        
        <div class="flex flex-col items-center text-center relative z-10">
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 mb-6 shadow-[0_0_30px_rgba(16,185,129,0.3)]">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            
            <h2 class="text-3xl font-black themed-text mb-2">Jadwal Ujian Terkonfirmasi!</h2>
            <p class="text-sm themed-text-muted mb-10 max-w-lg">Anda telah memilih jadwal ujian. Silakan unduh kartu ujian Anda dan bawa fotokopi serta lampiran lainnya saat pelaksanaan ujian.</p>
            
            <div class="w-full bg-white/5 border border-white/10 rounded-2xl p-6 mb-10 text-left grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-[10px] themed-text-muted font-bold uppercase tracking-widest mb-1">Gelombang / Sesi</p>
                    <p class="text-lg font-bold themed-text">{{ $registration->examSchedule->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] themed-text-muted font-bold uppercase tracking-widest mb-1">Jadwal Ujian</p>
                    <p class="text-lg font-bold text-primary">{{ \Carbon\Carbon::parse($registration->examSchedule->date)->translatedFormat('d F Y') }}</p>
                    <p class="text-xs font-bold themed-text-muted mt-1">{{ substr($registration->examSchedule->time_start, 0, 5) }} - {{ substr($registration->examSchedule->time_end, 0, 5) }} WIB</p>
                </div>
            </div>
            
            <a href="{{ route('pendaftaran.exam-card') }}" target="_blank" class="px-8 py-4 rounded-full bg-gradient-to-r from-primary to-indigo-600 text-white font-black text-sm uppercase tracking-widest shadow-[0_10px_30px_-10px_rgba(var(--primary-rgb),0.5)] hover:-translate-y-1 hover:shadow-primary/40 transition-all flex items-center gap-3 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh PDF Kartu Ujian
            </a>
        </div>
    </div>
@else
    {{-- Pilihan Jadwal Ujian --}}
    <div class="card-glass rounded-3xl p-8 max-w-4xl mx-auto shadow-2xl">
        <div class="mb-10 text-center">
            <h2 class="text-2xl font-black themed-text mb-2">Pilih Jadwal Ujian</h2>
            <p class="text-sm themed-text-muted max-w-lg mx-auto">Silakan pilih salah satu jadwal ujian seleksi yang tersedia di bawah ini. Keputusan ini bersifat final dan tidak bisa diubah.</p>
        </div>

        <form action="{{ route('pendaftaran.exam.select') }}" method="POST" class="space-y-6">
            @csrf
            
            @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm font-medium">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse($schedules as $schedule)
                <label class="relative group cursor-pointer block">
                    <input type="radio" name="exam_schedule_id" value="{{ $schedule->id }}" class="sr-only peer" required>
                    <div class="p-6 rounded-2xl border-2 border-white/5 bg-white/5 transition-all group-hover:bg-primary/5 peer-checked:bg-primary/10 peer-checked:border-primary shadow-lg shadow-black/10">
                        <div class="flex items-start gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-black/20 flex flex-col items-center justify-center text-white border border-white/10 peer-checked:bg-primary peer-checked:text-white shrink-0 transition-colors">
                                <span class="text-[10px] font-black uppercase tracking-tighter">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('M') }}</span>
                                <span class="text-xl font-black leading-none">{{ \Carbon\Carbon::parse($schedule->date)->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-black themed-text mb-1 uppercase tracking-tight truncate">{{ $schedule->name }}</p>
                                <p class="text-[10px] themed-text-muted font-bold uppercase tracking-widest mb-1">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d F Y') }}</p>
                                <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-white/5 text-[9px] font-bold themed-text">
                                    <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ substr($schedule->time_start, 0, 5) }} - {{ substr($schedule->time_end, 0, 5) }} WIB
                                </div>
                            </div>
                            <div class="w-6 h-6 rounded-full border-2 border-white/20 flex items-center justify-center shrink-0 peer-checked:border-primary peer-checked:bg-primary transition-all">
                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                        </div>
                    </div>
                </label>
                @empty
                <div class="col-span-2 py-12 text-center border-2 border-dashed border-white/10 rounded-2xl">
                    <p class="text-sm themed-text-muted font-bold italic">Belum ada jadwal yang tersedia untuk jenjang Anda saat ini.</p>
                </div>
                @endforelse
            </div>

            @if($schedules->isNotEmpty())
            <div class="mt-8 pt-8 border-t border-white/5 flex justify-end">
                <button type="submit" class="px-8 py-4 rounded-xl bg-primary text-white font-black text-sm uppercase tracking-widest shadow-lg shadow-primary/20 hover:-translate-y-1 hover:shadow-primary/40 active:scale-95 transition-all">
                    Simpan Pilihan Ujian
                </button>
            </div>
            @endif
        </form>
    </div>
@endif

@endsection
