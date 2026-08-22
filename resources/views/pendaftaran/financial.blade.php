@extends('layouts.app')

@section('title', 'Administrasi Keuangan')
@section('page-title', 'Administrasi Keuangan')
@section('page-subtitle', 'Pantau status pembayaran dan kewajiban biaya pendaftaran Anda')

@section('content')

@php
    $statusColors = [
        'none' => 'bg-orange-500/15 text-orange-400 border-orange-500/20',
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

@php
    $fee2 = $feeData->where('sort_order', '>', 1)->first();
    $status2 = $fee2 ? $fee2->status : 'none';
    $isPassed = $registration && $registration->status === 'lulus';
    $activeDiscountApp = $registration ? $registration->discountApplications()->latest()->first() : null;

    // Cek apakah ada pembayaran (pending/success) untuk fee sort_order > 1
    $hasPaidFee2 = $feeData->where('sort_order', '>', 1)->filter(function($f) {
        return in_array($f->status, ['pending', 'success']);
    })->isNotEmpty();

    // Pop-up keringanan tampil jika: lulus, sudah bayar fee > 1, belum pernah mengajukan keringanan
    $showKeringanPopup = $isPassed && $hasPaidFee2 && !$activeDiscountApp;
@endphp

{{-- Blok keringanan: selalu tampil jika siswa lulus --}}
@if($isPassed)
    <div class="mb-8 card-glass rounded-3xl p-6 border-l-4 border-purple-500 flex flex-col md:flex-row items-center justify-between gap-4 animate-slide-in">
        <div>
            <h3 class="text-sm font-bold themed-text uppercase tracking-widest mb-1">Informasi Administrasi</h3>
            <p class="text-xs themed-text-muted">Bagi calon peserta didik baru yang <b>mengundurkan diri</b> maka <b>semua biaya</b> yang telah dibayarkan <b>tidak dapat ditarik kembali/tidak dikembalikan.</b></p>
        </div>
        <div>
            {{-- Jika sudah ada pengajuan keringanan: tampilkan info discount --}}
            @if($activeDiscountApp)
                @include('pendaftaran.partials.discount-info')

            {{-- Jika belum ada pengajuan tapi sudah ada pembayaran sort_order > 1: tampilkan tombol --}}
            @elseif($hasPaidFee2)
                <button @click="$dispatch('open-discount-modal')" class="px-6 py-2.5 rounded-xl bg-purple-500 hover:bg-purple-400 text-white text-[10px] font-bold uppercase shadow-lg shadow-purple-500/20 transition-all active:scale-95 whitespace-nowrap">
                    Ajukan Keringanan
                </button>
                @include('pendaftaran.partials.discount-modal')
            @endif
        </div>
    </div>
@endif

{{-- ═══════════════ POP-UP KERINGANAN OTOMATIS ═══════════════ --}}
@if($showKeringanPopup)
<template x-teleport="body">
    <div
        x-data="{
            open: false,
            init() {
                setTimeout(() => { this.open = true; }, 800);
            },
            dismiss() {
                this.open = false;
            },
            openModal() {
                this.open = false;
                this.$nextTick(() => window.dispatchEvent(new CustomEvent('open-discount-modal')));
            }
        }"
        x-show="open"
        x-cloak
        style="display:none;"
        class="fixed inset-0 z-[9998] flex items-end sm:items-center justify-center p-4"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="dismiss()"></div>

        {{-- Modal Card --}}
        <div
            class="relative w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border"
            :style="'background: var(--surface-color); border-color: var(--border-color)'"
            x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            {{-- Header gradient --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500"></div>

            {{-- Content --}}
            <div class="p-7">
                {{-- Icon + Title --}}
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-14 h-14 shrink-0 rounded-2xl flex items-center justify-center text-3xl"
                         style="background: rgba(168,85,247,0.12);">
                        🎁
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-extrabold themed-text mb-1 leading-tight">
                            Tahukah Anda? Ada Keringanan Biaya!
                        </h3>
                        <p class="text-xs themed-text-muted leading-relaxed">
                            Anda sudah melakukan pembayaran dan berpotensi mendapatkan keringanan biaya melalui program diskon yang tersedia.
                        </p>
                    </div>
                    {{-- Close button --}}
                    <button @click="dismiss()"
                            class="shrink-0 w-8 h-8 rounded-xl flex items-center justify-center themed-text-muted hover:text-red-400 transition-colors border"
                            :style="'border-color: var(--border-color); background: var(--card-bg)'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Kategori tersedia --}}
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="rounded-2xl p-3 text-center border" style="background: var(--card-bg); border-color: var(--border-color);">
                        <div class="text-2xl mb-1">👨‍💼</div>
                        <p class="text-[10px] font-bold themed-text leading-tight">Keluarga<br>Karyawan</p>
                    </div>
                    <div class="rounded-2xl p-3 text-center border" style="background: var(--card-bg); border-color: var(--border-color);">
                        <div class="text-2xl mb-1">🎓</div>
                        <p class="text-[10px] font-bold themed-text leading-tight">Alumni<br>Al Hasra</p>
                    </div>
                    <div class="rounded-2xl p-3 text-center border" style="background: var(--card-bg); border-color: var(--border-color);">
                        <div class="text-2xl mb-1">🌟</div>
                        <p class="text-[10px] font-bold themed-text leading-tight">Umum &<br>Prestasi</p>
                    </div>
                </div>

                {{-- Info note --}}
                <div class="rounded-xl p-3.5 mb-6 flex items-start gap-3 border-l-4 border-amber-500"
                     style="background: rgba(245,158,11,0.08);">
                    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-[11px] text-amber-400 leading-relaxed">
                        Pengajuan keringanan biaya hanya dapat dilakukan <strong>satu kali</strong>. Pastikan data dan dokumen yang Anda unggah sudah benar sebelum mengajukan.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3">
                    <button @click="dismiss()"
                            class="flex-1 py-2.5 rounded-xl text-xs font-bold transition-all border themed-text-muted hover:themed-text"
                            :style="'border-color: var(--border-color); background: var(--card-bg)'">
                        Nanti Saja
                    </button>
                    <button @click="openModal()"
                            class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-purple-500 to-violet-500 hover:from-purple-400 hover:to-violet-400 text-white text-xs font-bold shadow-lg shadow-purple-500/25 transition-all active:scale-95">
                        Ajukan Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
@if(!$activeDiscountApp)
    @include('pendaftaran.partials.discount-modal')
@endif
@endif

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
                    <th class="px-8 py-4 text-right">No. VA</th>
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
                            <div class="flex flex-col items-end gap-1">
                                @if(isset($fee->discount_amount) && $fee->discount_amount > 0)
                                    <span class="text-[10px] text-gray-400 line-through">Rp {{ number_format($fee->original_amount, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">
                                        {{ $fee->discount_name }} (-Rp {{ number_format($fee->discount_amount, 0, ',', '.') }})
                                    </span>
                                    <span class="text-sm">Rp {{ number_format($fee->amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-sm">Rp {{ number_format($fee->amount, 0, ',', '.') }}</span>
                                @endif
                                @if(($fee->paid_amount ?? 0) > 0)
                                    <span class="text-[10px] text-emerald-400 font-semibold">
                                        Terbayar: Rp {{ number_format($fee->paid_amount, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            @if($fee->status === 'pending' && $fee->payment)
                                @php
                                    $vaBank = $fee->payment->va_bank ?? 'btn';
                                    $isBcaVaCell = $vaBank === 'bca';
                                @endphp
                                <div class="flex flex-col items-end gap-1">
                                    <span class="text-[12px] text-amber-500 font-bold font-mono">{{ $fee->payment->va_number }}</span>
                                    <span class="px-2 py-0.5 rounded text-[7px] font-black uppercase tracking-widest border
                                        {{ $isBcaVaCell ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20' }}">
                                        {{ strtoupper($vaBank) }}
                                    </span>
                                    
                                </div>
                            @else
                                <span class="text-[10px] themed-text-muted opacity-40">—</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex flex-col items-center gap-1.5">
                                @php
                                    $isBelumLunas = $fee->status === 'success' && ($fee->paid_amount ?? 0) < $fee->amount;
                                @endphp
                                @if($isBelumLunas)
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border bg-amber-500/15 text-amber-400 border-amber-500/20">
                                        Belum Lunas
                                    </span>
                                    <span class="text-[9px] text-amber-400 font-semibold">
                                        Sisa: Rp {{ number_format($fee->amount - $fee->paid_amount, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusColors[$fee->status] }}">
                                        {{ $statusLabels[$fee->status] }}
                                    </span>
                                    @if(($fee->paid_amount ?? 0) > 0 && $fee->amount > $fee->paid_amount)
                                        <span class="text-[9px] text-amber-400 font-semibold">
                                            Sisa: Rp {{ number_format($fee->amount - $fee->paid_amount, 0, ',', '.') }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            @if($fee->status === 'none' || $fee->status === 'failed')
                                @if($canPayNext)
                                    @if($fee->sort_order == 1 || ($registration && $registration->status === 'lulus'))
                                        <div class="flex flex-col items-end gap-2">
                                            @if($fee->sort_order == 2 && $registration && $registration->reregistration_deadline)
                                                <span class="text-[9px] font-bold text-rose-500 bg-rose-500/10 px-2 py-0.5 rounded border border-rose-500/20">
                                                    Batas: {{ \Carbon\Carbon::parse($registration->reregistration_deadline)->translatedFormat('d M Y') }}
                                                </span>
                                            @endif
                                            {{-- Tombol VA BTN --}}
                                            <form action="{{ route('pendaftaran.payment.create-va') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="fee_id" value="{{ $fee->id }}">
                                                <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 active:scale-95 transition-all">
                                                    Bayar via VA BTN
                                                </button>
                                            </form>
                                            {{-- Tombol VA BCA --}}
                                            <form action="{{ route('pendaftaran.payment.create-va-bca') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="fee_id" value="{{ $fee->id }}">
                                                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/20 active:scale-95 transition-all">
                                                    Bayar via VA BCA
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-[9px] themed-text-muted italic opacity-50">Menunggu Kelulusan</span>
                                    @endif
                                    @php $canPayNext = false; @endphp
                                @else
                                    <span class="text-[10px] themed-text-muted italic opacity-50">Menunggu Tahap Sebelumnya</span>
                                @endif

                            @elseif($fee->status === 'pending')
                                @php
                                    $pendingPayment = $fee->payment;
                                    $vaBank = $pendingPayment->va_bank ?? 'btn';
                                    $isBtnVa = $vaBank === 'btn';
                                    $isBcaVa = $vaBank === 'bca';
                                @endphp
                                <div class="flex flex-col items-end gap-2">
                                    {{-- Cek Status (hanya untuk VA BTN) --}}
                                    @if($isBtnVa)
                                        <form action="{{ route('pendaftaran.payment.check-va') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="payment_id" value="{{ $pendingPayment->id }}">
                                            <button type="submit" class="px-3 py-1.5 rounded-lg btn-soft-secondary text-[9px] font-bold uppercase tracking-widest">
                                                Cek Status
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Switch --}}
                                    @if($isBtnVa)
                                        {{-- Ganti dari BTN ke BCA --}}
                                        <form action="{{ route('pendaftaran.payment.switch-to-bca') }}" method="POST"
                                              onsubmit="return confirm('VA BTN akan dihapus dan diganti VA BCA. Lanjutkan?')">
                                            @csrf
                                            <input type="hidden" name="payment_id" value="{{ $pendingPayment->id }}">
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-blue-600/10 hover:bg-blue-600/20 text-blue-400 border border-blue-500/20 text-[9px] font-bold uppercase tracking-widest transition-all">
                                                Ganti ke VA BCA
                                            </button>
                                        </form>
                                    @else
                                        {{-- Ganti dari BCA ke BTN --}}
                                        <form action="{{ route('pendaftaran.payment.switch-to-btn') }}" method="POST"
                                              onsubmit="return confirm('VA BCA akan dihapus dan diganti VA BTN. Lanjutkan?')">
                                            @csrf
                                            <input type="hidden" name="payment_id" value="{{ $pendingPayment->id }}">
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-indigo-600/10 hover:bg-indigo-600/20 text-indigo-400 border border-indigo-500/20 text-[9px] font-bold uppercase tracking-widest transition-all">
                                                Ganti ke VA BTN
                                            </button>
                                        </form>
                                    @endif
                                </div>
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

{{-- No Modals needed anymore as payment is direct via VA generation --}}

@endsection
