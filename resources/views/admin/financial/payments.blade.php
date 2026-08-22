@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')
@section('page-subtitle', 'Kelola status pembayaran siswa dan catat pembayaran tunai di sekolah')

@section('content')
<div class="space-y-6">
    {{-- Filter Tabs & Unit Filter --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex gap-4 flex-wrap">
            @foreach(['pending' => 'Menunggu', 'success' => 'Berhasil', 'belum_lunas' => 'Belum Lunas'] as $val => $label)
                <a href="{{ route('admin.financial.payments', ['status' => $val, 'level_id' => request('level_id')]) }}"
                class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all {{ $status == $val ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-card-bg themed-text-muted hover:bg-primary/5 border border-white/5' }}">
                    {{ $label }}
                    @if($val === 'belum_lunas')
                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black {{ $status == $val ? 'bg-white/20' : 'bg-amber-500/20 text-amber-400' }}">PARTIAL</span>
                    @endif
                </a>
            @endforeach
        </div>

        <form action="{{ route('admin.financial.payments') }}" method="GET" class="flex items-end gap-3 min-w-[300px]">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="flex-1">
                <label class="block text-[10px] font-bold themed-text-muted uppercase tracking-widest mb-2 px-1">Filter Unit</label>
                <select name="level_id" class="w-full themed-input rounded-xl px-4 py-2.5 text-xs themed-text focus:ring-primary appearance-none border border-white/5">
                    <option value="" class="text-slate-900">Semua Unit</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }} class="text-slate-900">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-primary-hover transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span>Filter</span>
            </button>
            @if(request('level_id'))
                <a href="{{ route('admin.financial.payments', ['status' => $status]) }}" class="px-4 py-2.5 btn-soft-secondary rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-white/10 transition-all flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Info banner for belum_lunas --}}
    @if($status === 'belum_lunas')
    <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-[11px] text-amber-300 font-semibold">Menampilkan pembayaran yang sudah masuk namun <strong>nominal lebih kecil dari tagihan</strong> (pembayaran parsial / cicilan). Gunakan tombol <strong>Edit</strong> untuk memperbarui nominal.</p>
    </div>
    @endif

    <div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left datatable" id="payments-table">
                <thead>
                    <tr class="border-b" :style="'border-color: var(--border-color)'">
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Siswa</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Tujuan</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Tipe</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Tagihan</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Dibayarkan</th>
                        @if($status === 'belum_lunas')
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Sisa</th>
                        @endif
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Metode</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-right" data-dt-order="disable">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                    {{ strtoupper(substr($payment->registration->user->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold themed-text group-hover:text-primary transition-colors">{{ $payment->registration->user->full_name ?? $payment->registration->user->name ?? 'Siswa Terhapus' }}</p>
                                    <p class="text-[10px] themed-text-muted">{{ $payment->registration->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-[10px] font-bold themed-text">
                                {{ $payment->registration->user->educationalLevel->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $payment->fee_type == 'formulir' ? 'text-indigo-400' : 'text-emerald-400' }}">
                                {{ $payment->fee_type }}
                            </span>
                        </td>
                        {{-- Tagihan --}}
                        <td class="px-8 py-5 text-center font-bold themed-text text-sm" data-order="{{ $payment->amount }}">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        {{-- Dibayarkan --}}
                        <td class="px-8 py-5 text-center text-sm" data-order="{{ $payment->paid_amount ?? 0 }}">
                            @if($payment->paid_amount)
                                <span class="font-black {{ $status === 'belum_lunas' ? 'text-amber-400' : 'text-emerald-400' }}">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</span>
                            @else
                                <span class="text-[10px] themed-text-muted italic">Belum tercatat</span>
                            @endif
                        </td>
                        @if($status === 'belum_lunas')
                        {{-- Sisa tagihan --}}
                        <td class="px-8 py-5 text-center text-sm">
                            @php $sisa = $payment->amount - ($payment->paid_amount ?? 0); @endphp
                            <span class="font-black text-rose-400">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                        </td>
                        @endif
                        {{-- Metode Pembayaran --}}
                        <td class="px-8 py-5 text-center">
                            @php
                                $methodLabels = [
                                    'va'     => ['label' => 'VA BTN',  'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'],
                                    'va_bca' => ['label' => 'VA BCA',  'class' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20'],
                                    'cash'   => ['label' => 'Tunai',   'class' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'],
                                    'manual' => ['label' => 'Manual',  'class' => 'bg-amber-500/10 text-amber-400 border-amber-500/20'],
                                ];
                                $m = $methodLabels[$payment->payment_method] ?? ['label' => $payment->payment_method ?? '-', 'class' => 'bg-white/5 text-white/40 border-white/10'];
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg border text-[10px] font-bold uppercase tracking-widest {{ $m['class'] }}">
                                {{ $m['label'] }}
                            </span>
                        </td>
                        {{-- Aksi --}}
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($status === 'belum_lunas')
                                    {{-- Tombol Edit Paid Amount --}}
                                    <button @click="$dispatch('open-modal', 'edit-paid-{{ $payment->id }}')"
                                        class="px-4 py-2 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20 hover:bg-amber-500 text-[10px] font-bold uppercase tracking-widest hover:text-white transition-all flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </button>
                                @else
                                    {{-- VA BTN: Cek Status --}}
                                    @if($payment->payment_method === 'va')
                                        <form action="{{ route('admin.financial.check-va', $payment) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-500 text-[10px] font-bold uppercase tracking-widest hover:text-white transition-all">
                                                Cek Status VA
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Input Cash: muncul untuk semua payment pending yang punya VA --}}
                                    @if($payment->status === 'pending' && $payment->va_number)
                                        <button @click="$dispatch('open-modal', 'cash-{{ $payment->id }}')"
                                            class="px-4 py-2 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500 text-[10px] font-bold uppercase tracking-widest hover:text-white transition-all">
                                            Input Cash
                                        </button>
                                    @endif

                                    {{-- Verifikasi Manual (untuk method bukan va) --}}
                                    @if($payment->payment_method !== 'va' && $payment->status === 'pending')
                                        <button @click="$dispatch('open-modal', 'verify-{{ $payment->id }}')"
                                            class="px-4 py-2 rounded-lg bg-primary/10 text-primary border border-primary/20 hover:bg-primary text-[10px] font-bold uppercase tracking-widest hover:text-white transition-all">
                                            Verifikasi
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ====================== MODALS ====================== --}}
    @foreach($payments as $payment)

        {{-- Modal: Edit Paid Amount (Belum Lunas) --}}
        @if($status === 'belum_lunas')
        <div x-data="{ open: false }" @open-modal.window="if($event.detail === 'edit-paid-{{ $payment->id }}') open = true" x-show="open" x-cloak
             class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xl text-left">
            <div @click.away="open = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="card-glass rounded-[2rem] border-2 border-white/10 p-1 w-full max-w-lg shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)] overflow-hidden">

                <div class="p-8">
                    {{-- Header --}}
                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-xl bg-amber-500/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-black themed-text uppercase tracking-widest">Edit Nominal Dibayarkan</h3>
                            </div>
                            <p class="text-[10px] themed-text-muted">Perbarui nominal yang sudah diterima untuk pembayaran ini</p>
                        </div>
                        <button @click="open = false" class="w-8 h-8 rounded-full flex items-center justify-center bg-white/5 hover:bg-rose-500/20 text-white/40 hover:text-rose-400 transition-all border border-white/5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Info Siswa --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="col-span-2 p-4 rounded-2xl bg-white/5 border border-white/5">
                            <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Siswa</p>
                            <p class="text-sm font-black themed-text">{{ $payment->registration->user->full_name ?? $payment->registration->user->name }}</p>
                            <p class="text-[10px] themed-text-muted">{{ $payment->registration->user->educationalLevel->name ?? '-' }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                            <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Tipe Biaya</p>
                            <p class="text-xs font-black text-indigo-400 uppercase">{{ $payment->fee_type }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                            <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Total Tagihan</p>
                            <p class="text-sm font-black text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-span-2 p-4 rounded-2xl bg-amber-500/5 border border-amber-500/20">
                            <p class="text-[9px] text-amber-400 font-bold uppercase mb-1">Nominal Saat Ini</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-mono font-black text-amber-300">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</span>
                                <span class="text-[9px] text-rose-400 font-bold">Sisa: Rp {{ number_format($payment->amount - $payment->paid_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Edit Paid Amount --}}
                    <form action="{{ route('admin.financial.update-paid-amount', $payment) }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-3">
                                Nominal Baru Dibayarkan <span class="text-rose-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold themed-text-muted">Rp</span>
                                <input type="number"
                                    name="paid_amount"
                                    id="edit_paid_amount_{{ $payment->id }}"
                                    min="1"
                                    max="{{ (int) $payment->amount }}"
                                    step="1"
                                    value="{{ (int) $payment->paid_amount }}"
                                    required
                                    class="w-full bg-black/20 border-2 border-white/5 rounded-2xl pl-12 pr-5 py-4 text-sm font-bold themed-text focus:border-amber-500/50 focus:ring-0 transition-all">
                            </div>
                            <p class="text-[9px] themed-text-muted mt-2 px-1">Masukkan nominal antara <strong>Rp 1</strong> s/d <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></p>
                        </div>

                        <button type="submit"
                            class="w-full py-4 rounded-2xl bg-amber-500 text-white font-black uppercase tracking-[0.2em] text-xs shadow-[0_10px_30px_-10px_rgba(245,158,11,0.5)] hover:bg-amber-400 hover:-translate-y-0.5 transition-all active:scale-95 flex items-center justify-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- Modal: Input Pembayaran Cash --}}
        @if($payment->status === 'pending' && $payment->va_number)
        <div x-data="{ open: false }" @open-modal.window="if($event.detail === 'cash-{{ $payment->id }}') open = true" x-show="open" x-cloak
             class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xl text-left">
            <div @click.away="open = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="card-glass rounded-[2rem] border-2 border-white/10 p-1 w-full max-w-lg shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)] overflow-hidden">

                <div class="p-8">
                    {{-- Header --}}
                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-black themed-text uppercase tracking-widest">Input Pembayaran Cash</h3>
                            </div>
                            <p class="text-[10px] themed-text-muted">Catat pembayaran tunai yang diterima di sekolah</p>
                        </div>
                        <button @click="open = false" class="w-8 h-8 rounded-full flex items-center justify-center bg-white/5 hover:bg-rose-500/20 text-white/40 hover:text-rose-400 transition-all border border-white/5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Info Siswa --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="col-span-2 p-4 rounded-2xl bg-white/5 border border-white/5">
                            <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Siswa</p>
                            <p class="text-sm font-black themed-text">{{ $payment->registration->user->full_name ?? $payment->registration->user->name }}</p>
                            <p class="text-[10px] themed-text-muted">{{ $payment->registration->user->educationalLevel->name ?? '-' }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                            <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Tipe Biaya</p>
                            <p class="text-xs font-black text-indigo-400 uppercase">{{ $payment->fee_type }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                            <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Tagihan</p>
                            <p class="text-sm font-black text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-span-2 p-4 rounded-2xl bg-blue-500/5 border border-blue-500/20">
                            <p class="text-[9px] text-blue-400 font-bold uppercase mb-1">Nomor Virtual Account</p>
                            <p class="text-sm font-mono font-black text-blue-300 tracking-widest">{{ $payment->va_number }}</p>
                        </div>
                    </div>

                    {{-- Form Input Cash --}}
                    <form action="{{ route('admin.financial.record-cash', $payment) }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-3">
                                Nominal Diterima <span class="text-rose-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold themed-text-muted">Rp</span>
                                <input type="number"
                                    name="paid_amount"
                                    id="paid_amount_{{ $payment->id }}"
                                    min="1"
                                    step="1"
                                    value="{{ (int) $payment->amount }}"
                                    required
                                    class="w-full bg-black/20 border-2 border-white/5 rounded-2xl pl-12 pr-5 py-4 text-sm font-bold themed-text focus:border-emerald-500/50 focus:ring-0 transition-all"
                                    placeholder="{{ number_format($payment->amount, 0, ',', '.') }}">
                            </div>
                            <p class="text-[9px] themed-text-muted mt-2 px-1">Nominal tagihan: <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-3">Catatan (Opsional)</label>
                            <textarea name="admin_note"
                                rows="2"
                                class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-emerald-500/50 focus:ring-0 transition-all placeholder:text-white/10"
                                placeholder="Contoh: Dibayar tunai oleh orang tua siswa..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full py-4 rounded-2xl bg-emerald-500 text-white font-black uppercase tracking-[0.2em] text-xs shadow-[0_10px_30px_-10px_rgba(16,185,129,0.5)] hover:bg-emerald-400 hover:-translate-y-0.5 transition-all active:scale-95 flex items-center justify-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Konfirmasi Pembayaran Cash</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- Modal: Verifikasi Manual (non-VA, pending) --}}
        @if($payment->payment_method !== 'va' && $payment->status === 'pending')
        <div x-data="{ open: false }" @open-modal.window="if($event.detail === 'verify-{{ $payment->id }}') open = true" x-show="open" x-cloak
             class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xl text-left">
            <div @click.away="open = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="card-glass rounded-[2rem] border-2 border-white/10 p-1 w-full max-w-lg shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)] overflow-hidden">

                <div class="p-8">
                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <p class="text-[10px] themed-text-muted font-bold uppercase tracking-widest mb-1">Verifikasi Pembayaran</p>
                            <h4 class="text-xl font-black themed-text">{{ $payment->registration->user->full_name }}</h4>
                        </div>
                        <button @click="open = false" class="w-8 h-8 rounded-full flex items-center justify-center bg-white/5 hover:bg-rose-500/20 text-white/40 hover:text-rose-400 transition-all border border-white/5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                            <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Tipe Biaya</p>
                            <p class="text-xs font-black text-indigo-400 uppercase">{{ $payment->fee_type }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                            <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Tagihan</p>
                            <p class="text-lg font-black text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.financial.verify', $payment) }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-3">Nominal Dibayarkan (Opsional)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold themed-text-muted">Rp</span>
                                <input type="number" name="paid_amount" min="0" step="1"
                                    class="w-full bg-black/20 border-2 border-white/5 rounded-2xl pl-12 pr-5 py-4 text-sm font-bold themed-text focus:border-primary/50 focus:ring-0 transition-all"
                                    placeholder="{{ number_format($payment->amount, 0, ',', '.') }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-4">Tentukan Keputusan</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="group relative flex flex-col items-center p-4 rounded-2xl border-2 border-white/5 cursor-pointer bg-white/5 hover:bg-emerald-500/5 transition-all has-[:checked]:bg-emerald-500/10 has-[:checked]:border-emerald-500">
                                    <input type="radio" name="status" value="success" required class="sr-only">
                                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 mb-2 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-[10px] font-black themed-text uppercase tracking-widest">DISETUJUI</span>
                                </label>
                                <label class="group relative flex flex-col items-center p-4 rounded-2xl border-2 border-white/5 cursor-pointer bg-white/5 hover:bg-rose-500/5 transition-all has-[:checked]:bg-rose-500/10 has-[:checked]:border-rose-500">
                                    <input type="radio" name="status" value="failed" required class="sr-only">
                                    <div class="w-8 h-8 rounded-full bg-rose-500/20 flex items-center justify-center text-rose-400 mb-2 group-hover:scale-110 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </div>
                                    <span class="text-[10px] font-black themed-text uppercase tracking-widest">DITOLAK</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-3">Catatan Verifikasi (Opsional)</label>
                            <textarea name="admin_note" rows="3" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10" placeholder="Berikan catatan tambahan..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-5 rounded-2xl bg-primary text-white font-black uppercase tracking-[0.2em] text-xs shadow-[0_10px_30px_-10px_rgba(var(--primary-rgb),0.5)] hover:shadow-primary/40 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3">
                            <span>Eksekusi Keputusan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

    @endforeach
</div>
@endsection
