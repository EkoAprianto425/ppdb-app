@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')
@section('page-title', 'Verifikasi Pembayaran')
@section('page-subtitle', 'Tinjau bukti transfer dan kelola status pembayaran siswa')

@section('content')
<div class="space-y-6">
    {{-- Filter Tabs --}}
    <div class="flex gap-4">
        @foreach(['pending' => 'Menunggu', 'success' => 'Berhasil', 'failed' => 'Ditolak'] as $val => $label)
            <a href="{{ route('admin.financial.payments', ['status' => $val]) }}" 
               class="px-6 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-all {{ $status == $val ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-card-bg themed-text-muted hover:bg-primary/5 border border-white/5' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left datatable" id="payments-table">
                <thead>
                    <tr class="border-b" :style="'border-color: var(--border-color)'">
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Siswa</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Tipe</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Jumlah</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Waktu Upload</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-right" data-dt-order="disable">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                    {{ strtoupper(substr($payment->registration->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold themed-text group-hover:text-primary transition-colors">{{ $payment->registration->user->full_name }}</p>
                                    <p class="text-[10px] themed-text-muted">{{ $payment->registration->user->getUnit() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $payment->fee_type == 'formulir' ? 'text-indigo-400' : 'text-emerald-400' }}">
                                {{ $payment->fee_type }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center font-bold themed-text text-sm" data-order="{{ $payment->amount }}">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-5 text-center text-[10px] themed-text-muted" data-order="{{ $payment->created_at->format('Y-m-d H:i:s') }}">
                            {{ $payment->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button @click="$dispatch('open-modal', 'verify-{{ $payment->id }}')" class="px-4 py-2 rounded-lg bg-primary/10 text-primary border border-primary/20 hover:bg-primary text-[10px] font-bold uppercase tracking-widest hover:text-white transition-all">
                                Verifikasi
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Move Modals outside table for correct stacking context --}}
    @foreach($payments as $payment)
        {{-- Modal Verify --}}
        <div x-data="{ open: false }" @open-modal.window="if($event.detail === 'verify-{{ $payment->id }}') open = true" x-show="open" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xl text-left">
            <div @click.away="open = false" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="card-glass rounded-[2rem] border-2 border-white/10 p-1 w-full max-w-4xl shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)] overflow-hidden">
                
                <div class="flex flex-col md:flex-row h-full max-h-[85vh]">
                    {{-- Left Side: Proof Preview --}}
                    <div class="md:w-1/2 p-8 bg-black/40 flex flex-col h-full overflow-hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xs font-black themed-text uppercase tracking-[0.2em]">Data Bukti Transfer</h3>
                            <span class="text-[9px] px-2 py-0.5 rounded-full bg-primary/10 text-primary border border-primary/20 font-bold uppercase">{{ $payment->fee_type }}</span>
                        </div>
                        
                        <div class="flex-1 relative rounded-2xl overflow-hidden bg-black/50 border border-white/5 flex items-center justify-center group">
                            @if($payment->payment_proof)
                                <img src="{{ Storage::url($payment->payment_proof) }}" class="max-w-full max-h-full object-contain cursor-zoom-in transition-transform duration-500 group-hover:scale-105" onclick="window.open(this.src)">
                                <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                    <p class="text-[9px] text-white/70 text-center font-bold uppercase tracking-widest">Klik gambar untuk memperbesar</p>
                                </div>
                            @else
                                <div class="text-center p-12">
                                    <svg class="w-12 h-12 text-white/10 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-xs themed-text-muted italic">File tidak ditemukan</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Right Side: Form & Info --}}
                    <div class="md:w-1/2 p-8 flex flex-col h-full overflow-y-auto">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <p class="text-[10px] themed-text-muted font-bold uppercase tracking-widest mb-1">Verifikasi Untuk</p>
                                <h4 class="text-xl font-black themed-text">{{ $payment->registration->user->full_name }}</h4>
                            </div>
                            <button @click="open = false" class="w-8 h-8 rounded-full flex items-center justify-center bg-white/5 hover:bg-rose-500/20 text-white/40 hover:text-rose-400 transition-all border border-white/5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                                <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Nominal Transfer</p>
                                <p class="text-lg font-black text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                                <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Waktu Kirim</p>
                                <p class="text-xs font-bold themed-text">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.financial.verify', $payment) }}" method="POST" class="space-y-6 flex-1 flex flex-col">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-4">Tentukan Keputusan</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="group relative flex flex-col items-center p-4 rounded-2xl border-2 border-white/5 cursor-pointer bg-white/5 hover:bg-emerald-500/5 transition-all has-[:checked]:bg-emerald-500/10 has-[:checked]:border-emerald-500">
                                        <input type="radio" name="status" value="success" required class="sr-only">
                                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 mb-2 group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <span class="text-[10px] font-black themed-text uppercase tracking-widest tracking-widest">DISETUJUI</span>
                                    </label>
                                    <label class="group relative flex flex-col items-center p-4 rounded-2xl border-2 border-white/5 cursor-pointer bg-white/5 hover:bg-rose-500/5 transition-all has-[:checked]:bg-rose-500/10 has-[:checked]:border-rose-500">
                                        <input type="radio" name="status" value="failed" required class="sr-only">
                                        <div class="w-8 h-8 rounded-full bg-rose-500/20 flex items-center justify-center text-rose-400 mb-2 group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </div>
                                        <span class="text-[10px] font-black themed-text uppercase tracking-widest tracking-widest">DITOLAK</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-3">Catatan Verifikasi (Opsional)</label>
                                <textarea name="admin_note" rows="3" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10" placeholder="Berikan alasan jika pembayaran ditolak atau catatan tambahan..."></textarea>
                            </div>

                            <div class="mt-auto pt-6">
                                <button type="submit" class="w-full py-5 rounded-2xl bg-primary text-white font-black uppercase tracking-[0.2em] text-xs shadow-[0_10px_30px_-10px_rgba(var(--primary-rgb),0.5)] hover:shadow-primary/40 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3">
                                    <span>Eksekusi Keputusan</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
