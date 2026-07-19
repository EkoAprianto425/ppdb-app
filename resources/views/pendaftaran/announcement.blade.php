@extends('layouts.app')

@section('title', 'Pengumuman Kelulusan')
@section('page-title', 'Pengumuman Kelulusan')
@section('page-subtitle', 'Pusat informasi hasil seleksi peserta didik baru')

@section('content')

@if(session('error'))
<div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm font-medium flex items-center gap-3 animate-slide-in">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
</div>
@endif

<div class="max-w-4xl mx-auto mt-8">
    @if($registration->status === 'proses')
        {{-- Status: Proses --}}
        <div class="card-glass rounded-3xl p-12 text-center relative overflow-hidden shadow-2xl group">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-32 h-32 mb-8 relative">
                    <div class="absolute inset-0 bg-amber-500/20 rounded-full animate-ping opacity-50"></div>
                    <div class="w-full h-full bg-amber-500/20 rounded-full flex items-center justify-center border border-amber-500/30 backdrop-blur-sm relative z-10">
                        <svg class="w-16 h-16 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-3xl font-black themed-text mb-4">Pengumuman Belum Tersedia</h2>
                <p class="text-base themed-text-muted max-w-lg mb-8 leading-relaxed">
                    Halo <span class="font-bold themed-text">{{ $registration->user->nama_panggilan ?? $registration->user->name }}</span>, saat ini data pendaftaran dan hasil tes ujian Anda masih dalam tahap seleksi dan evaluasi oleh panitia PPDB. 
                </p>
                
                <div class="px-6 py-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 inline-flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-amber-500 uppercase tracking-widest">Silakan periksa kembali halaman ini secara berkala.</span>
                </div>
            </div>
        </div>

    @elseif($registration->status === 'lulus')
        {{-- Status: Lulus --}}
        <div class="card-glass rounded-3xl p-1 md:p-2 shadow-2xl relative overflow-hidden bg-gradient-to-br from-emerald-500/20 via-card-bg to-primary/20 border-2 border-emerald-500/30 group">
            {{-- Decorative Blobs --}}
            <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>
            
            <div class="bg-surface-color/80 backdrop-blur-xl rounded-/[1.25rem] p-8 md:p-12 relative z-10 text-center">
                
                <div class="w-24 h-24 mx-auto mb-6 rounded-3xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white shadow-[0_0_40px_rgba(16,185,129,0.4)] animate-bounce-slow">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>

                <h2 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400 mb-4 tracking-tight">SELAMAT!</h2>
                
                <p class="text-lg md:text-xl themed-text max-w-2xl mx-auto mb-10 leading-relaxed font-light">
                    Ananda <span class="font-bold">{{ $registration->user->full_name }}</span> dinyatakan <span class="font-bold text-emerald-400">LULUS</span> seleksi Penerimaan Peserta Didik Baru (PPDB) untuk unit <span class="font-bold">{{ $registration->user->educationalLevel?->name }}</span> Tahun Ajaran {{ $registration->academicYear->name }}.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto mb-10 text-left">
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10">
                        <p class="text-[10px] themed-text-muted font-bold uppercase tracking-widest mb-2">1. Tahap Selanjutnya</p>
                        <p class="text-sm themed-text leading-relaxed">
                            Silakan unduh Surat Keterangan Lulus (SKL) di bawah ini sebagai bukti kelulusan seleksi yang sah.
                        </p>
                    </div>
                    <div class="p-6 rounded-2xl border border-rose-500/30 bg-rose-500/5 relative overflow-hidden">
                        <div class="absolute inset-0 bg-rose-500/10 w-1 rounded-l-2xl"></div>
                        <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-2">2. Daftar Ulang</p>
                        <p class="text-sm themed-text leading-relaxed mb-3">
                            Lakukan pelunasan biaya Daftar Ulang (Tabel Administrasi) selambat-lambatnya:
                        </p>
                        @if($registration->reregistration_deadline)
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-rose-500/20 text-rose-400 font-black text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z"/></svg>
                                {{ \Carbon\Carbon::parse($registration->reregistration_deadline)->translatedFormat('d F Y') }}
                            </div>
                        @else
                            <span class="text-xs font-bold text-rose-500">Belum Ditentukan</span>
                        @endif
                    </div>
                </div>

                @php
                    $level = $registration->user->educationalLevel;
                    $allFees = $level ? $level->fees()->orderBy('sort_order')->get() : collect();
                    $fee2 = $allFees->where('sort_order', '>', 1)->first();
                    $p2 = $fee2 && $registration ? $registration->payments()->where('fee_type', $fee2->name)->latest()->first() : null;
                    $status2 = $p2 ? $p2->status : 'none';
                    $activeDiscountApp = $registration->discountApplications()->latest()->first();
                @endphp
                
                @if($status2 !== 'success')
                <div class="max-w-2xl mx-auto mb-10 text-left">
                    <div class="p-6 rounded-2xl bg-purple-500/5 border border-purple-500/20 flex flex-col md:flex-row items-center justify-between gap-4 animate-slide-in">
                        <div>
                            <h4 class="text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-1">Keringanan Biaya</h4>
                            <p class="text-sm themed-text leading-relaxed">Tersedia potongan biaya masuk untuk jalur Karyawan, Alumni, atau Umum.</p>
                        </div>
                        <div>
                            @if($activeDiscountApp)
                                <div class="flex flex-col items-end">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[9px] font-black uppercase tracking-widest themed-text-muted">Status:</span>
                                        <span class="px-3 py-1 rounded-md border text-[9px] font-black uppercase tracking-widest {{ $activeDiscountApp->status === 'approved' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : ($activeDiscountApp->status === 'rejected' ? 'bg-rose-500/10 text-rose-500 border-rose-500/20' : 'bg-amber-500/10 text-amber-500 border-amber-500/20') }}">
                                            {{ $activeDiscountApp->status }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] font-bold themed-text">{{ $activeDiscountApp->discount->name }}</p>
                                    @if($activeDiscountApp->notes)
                                        <p class="text-[9px] themed-text-muted italic max-w-[200px] text-right mt-1">{{ $activeDiscountApp->notes }}</p>
                                    @endif
                                </div>
                            @else
                                <button @click="$dispatch('open-discount-modal')" class="px-6 py-2.5 rounded-xl bg-purple-500 hover:bg-purple-400 text-white text-[10px] font-bold uppercase shadow-lg shadow-purple-500/20 transition-all active:scale-95 whitespace-nowrap">
                                    Ajukan Keringanan
                                </button>
                                @include('pendaftaran.partials.discount-modal')
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('pendaftaran.announcement.skl') }}" target="_blank" class="w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-black text-sm uppercase tracking-widest shadow-[0_10px_30px_-10px_rgba(16,185,129,0.5)] hover:-translate-y-1 hover:shadow-emerald-500/40 transition-all flex items-center justify-center gap-3 active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Unduh SKL (PDF)
                    </a>
                    <a href="{{ route('pendaftaran.financial') }}" class="w-full sm:w-auto px-8 py-4 rounded-full btn-soft-secondary font-black text-sm uppercase tracking-widest hover:-translate-y-1 transition-all flex items-center justify-center gap-3 active:scale-95">
                        Menuju Daftar Ulang
                    </a>
                </div>
            </div>
        </div>

    @else
        {{-- Status: Tidak Lulus --}}
        <div class="card-glass rounded-3xl p-12 text-center relative overflow-hidden shadow-2xl border-t-4 border-rose-500">
            <div class="absolute top-0 left-0 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl -translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-24 h-24 mb-6 rounded-3xl bg-rose-500/10 flex items-center justify-center text-rose-400 border border-rose-500/20">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h2a2 2 0 002-2v-5m0 0h4.236a2 2 0 001.789-2.894l-3.5-7A2 2 0 0016.264 3H12M12 14v5m0-5h-4"/>
                    </svg>
                </div>
                
                <h2 class="text-3xl font-black themed-text mb-4">Mohon Maaf</h2>
                <p class="text-base themed-text max-w-xl mx-auto mb-8 leading-relaxed font-light">
                    Dari hasil seleksi yang telah dilakukan oleh panitia PPDB, kami memohon maaf bahwa Ananda <span class="font-bold">{{ $registration->user->full_name }}</span> dinyatakan <span class="font-bold text-rose-400">TIDAK LULUS</span>.
                </p>

                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 max-w-xl mx-auto mb-8">
                    <p class="text-sm themed-text-muted leading-relaxed italic">
                        Hasil keputusan ini bersifat final. Jangan berkecil hati dan tetap semangat dalam menuntut ilmu di tempat yang terbaik bagi Ananda. Terima kasih atas partisipasi Anda dalam prores PPDB kami.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
