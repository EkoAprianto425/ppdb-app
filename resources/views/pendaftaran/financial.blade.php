@extends('layouts.app')

@section('title', 'Administrasi Keuangan')
@section('page-title', 'Administrasi Keuangan')
@section('page-subtitle', 'Pantau status pembayaran dan kewajiban biaya pendaftaran Anda')

@section('content')

@php
    $statusColors = [
        'none' => 'bg-slate-700/50 text-slate-400 border-slate-700',
        'pending' => 'bg-amber-500/15 text-amber-400 border-amber-500/20',
        'success' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/20',
        'failed' => 'bg-rose-500/15 text-rose-400 border-rose-500/20',
    ];
    $statusLabels = [
        'none' => 'Belum Bayar',
        'pending' => 'Menunggu Verifikasi',
        'success' => 'Lunas',
        'failed' => 'Gagal / Ditolak',
    ];
@endphp

{{-- Flash Messages --}}
@if(session('status'))
<div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold flex items-center gap-3 animate-slide-in">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('status') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold flex items-center gap-3 animate-slide-in">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- Financial Summary Cards --}}
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
    <div class="flex-1 space-y-2">
        <h2 class="text-xl font-bold themed-text">Ringkasan Keuangan</h2>
        <p class="text-xs themed-text-muted">Total kewajiban dan pembayaran Anda</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    {{-- Total Tagihan --}}
    <div class="card-glass rounded-3xl p-6 border-l-4 border-indigo-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 shadow-lg shadow-indigo-500/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold themed-text-muted uppercase tracking-widest leading-none mb-1.5">Total Kewajiban</p>
                <p class="text-xl font-black themed-text tracking-tight">Rp {{ number_format($totalFees, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Sudah Terbayar --}}
    <div class="card-glass rounded-3xl p-6 border-l-4 border-emerald-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 shadow-lg shadow-emerald-500/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold themed-text-muted uppercase tracking-widest leading-none mb-1.5">Total Terbayar</p>
                <p class="text-xl font-black text-emerald-400 tracking-tight">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Sisa Tagihan --}}
    <div class="card-glass rounded-3xl p-6 border-l-4 border-amber-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-400 shadow-lg shadow-amber-500/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold themed-text-muted uppercase tracking-widest leading-none mb-1.5">Sisa Tagihan</p>
                <p class="text-xl font-black text-amber-400 tracking-tight">Rp {{ number_format($remaining, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Fee List --}}
<div class="card-glass rounded-3xl overflow-hidden mb-8">
    <div class="px-8 py-6 border-b" :style="'border-color: var(--border-color)'">
        <h3 class="text-sm font-bold themed-text uppercase tracking-widest">Daftar Komponen Biaya</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 uppercase text-[10px] font-bold themed-text-muted">
                    <th class="px-8 py-4">No. Urut</th>
                    <th class="px-8 py-4">Nama Komponen</th>
                    <th class="px-8 py-4 text-right">Nominal</th>
                    <th class="px-8 py-4 text-center">Status</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y" :style="'border-color: var(--border-color)'">
                @php
                    $canPayNext = true;
                @endphp
                @foreach($feeData as $fee)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="px-8 py-6 text-sm themed-text-muted font-bold">#{{ $fee->sort_order }}</td>
                        <td class="px-8 py-6">
                            <p class="text-sm font-bold themed-text group-hover:text-primary transition-colors">{{ $fee->name }}</p>
                        </td>
                        <td class="px-8 py-6 text-sm themed-text text-right font-bold">
                            Rp {{ number_format($fee->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusColors[$fee->status] }}">
                                {{ $statusLabels[$fee->status] }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            @if($fee->status === 'none' || $fee->status === 'failed')
                                @if($canPayNext)
                                    @if($fee->sort_order == 1 || ($registration && $registration->status === 'lulus'))
                                        <div class="flex flex-col items-end gap-1.5">
                                            @if($fee->sort_order == 2 && $registration && $registration->reregistration_deadline)
                                                <span class="text-[9px] font-bold text-rose-500 bg-rose-500/10 px-2 py-0.5 rounded border border-rose-500/20">
                                                    Batas: {{ \Carbon\Carbon::parse($registration->reregistration_deadline)->translatedFormat('d M Y') }}
                                                </span>
                                            @endif
                                            <button @click="$dispatch('open-modal', 'modal-pay-{{ $fee->id }}')" 
                                                    class="px-4 py-2 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 active:scale-95 transition-all">
                                                Bayar Sekarang
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[9px] themed-text-muted italic opacity-50">Menunggu Kelulusan</span>
                                    @endif
                                    @php $canPayNext = false; @endphp
                                @else
                                    <span class="text-[10px] themed-text-muted italic opacity-50">Menunggu Tahap Sebelumnya</span>
                                @endif
                            @elseif($fee->status === 'pending')
                                <span class="text-[10px] text-amber-500 font-bold uppercase tracking-widest">Diverifikasi Admin</span>
                                @php $canPayNext = false; @endphp
                            @else
                                <div class="flex justify-end">
                                    <div class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-500 border border-emerald-500/30">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Local Modals for Specific Fees --}}
@foreach($feeData as $fee)
    @if($fee->status === 'none' || $fee->status === 'failed')
    <div x-data="{ open: false }" @open-modal.window="if($event.detail === 'modal-pay-{{ $fee->id }}') open = true" x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
        <div @click.away="open = false" class="card-glass rounded-3xl p-8 w-full max-w-lg shadow-2xl scale-in-center">
            <h3 class="text-xl font-bold themed-text mb-2">Unggah Bukti: {{ $fee->name }}</h3>
            <p class="text-xs themed-text-muted mb-6">Nominal yang harus dibayarkan: <span class="text-white font-black">Rp {{ number_format($fee->amount, 0, ',', '.') }}</span></p>
            
            <form action="{{ route('pendaftaran.payment.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="fee_type" value="{{ $fee->name }}">
                <input type="hidden" name="amount" value="{{ $fee->amount }}">

                <div class="relative group">
                    <input type="file" name="payment_proof" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="p-8 border-2 border-dashed border-white/10 rounded-2xl group-hover:border-primary/50 group-hover:bg-primary/5 transition-all flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <p class="text-sm themed-text font-medium">Klik atau tarik foto ke sini</p>
                        <p class="text-[10px] themed-text-muted mt-1 uppercase">Format: PNG, JPG (Maks. 2MB)</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-orange-500/10 border border-orange-500/20">
                    <p class="text-[10px] text-orange-400 leading-relaxed font-bold uppercase tracking-wide">
                        Transfer ke Rekening BCA: 123-456-7890 (A/N YAYASAN PPDB)
                    </p>
                </div>

                <div class="flex gap-4">
                    <button type="button" @click="open = false" class="flex-1 py-3 rounded-xl btn-soft-secondary font-bold text-xs uppercase tracking-widest">Batal</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-primary text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-primary/20 shadow-primary/20 active:scale-95 transition-all">Kirim Bukti</button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endforeach

@endsection
