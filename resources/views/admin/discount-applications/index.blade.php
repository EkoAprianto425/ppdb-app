@extends('layouts.app')

@section('title', 'Validasi Keringanan Biaya')
@section('page-title', 'Pengajuan Keringanan')
@section('page-subtitle', 'Daftar pengajuan keringanan biaya oleh calon siswa')

@section('content')
<div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left datatable">
            <thead>
                <tr class="border-b" :style="'border-color: var(--border-color)'">
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Nama / Email</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Kategori</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Program Diskon</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Status Kepegawaian</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Dokumen</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Status</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-right" data-dt-order="disable">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                <tr class="hover:bg-primary/5 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                {{ strtoupper(substr($app->registration->user->name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold themed-text group-hover:text-primary transition-colors">{{ $app->registration->user->full_name ?? $app->registration->user->name }}</p>
                                <p class="text-[10px] themed-text-muted">{{ $app->registration->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="px-3 py-1 rounded-md border text-[9px] font-black uppercase tracking-widest {{ $app->discount->category === 'anak_pegawai' ? 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20' : ($app->discount->category === 'alumni' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20') }}">
                            {{ str_replace('_', ' ', $app->discount->category) }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        <p class="text-sm font-bold themed-text">{{ $app->discount->name }}</p>
                        @if($app->discount->category === 'anak_pegawai')
                        <p class="text-[10px] themed-text-muted">Biaya Pendaftaran: Rp {{ number_format($app->discount->amount, 0, ',', '.') }}</p>
                        <p class="text-[10px] themed-text-muted">Biaya SPP: Rp {{ number_format($app->discount->spp_amount, 0, ',', '.') }}</p>
                        @else
                        <p class="text-[10px] themed-text-muted">Potongan: Rp {{ number_format($app->discount->amount, 0, ',', '.') }}</p>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        @if($app->employee_status)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-500/10 text-purple-400 text-[10px] font-bold border border-purple-500/20">
                                🏷️ {{ $app->employee_status }}
                            </span>
                        @else
                            <span class="text-[10px] italic themed-text-muted">—</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-center">
                        @if($app->document_path)
                        <a href="{{ Storage::url($app->document_path) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-primary hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Lihat File
                        </a>
                        @else
                        <span class="text-[10px] italic themed-text-muted">Tidak ada file</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-center">
                        @php
                            $statusColors = [
                                'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                'approved' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                'rejected' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-md border text-[9px] font-black uppercase tracking-widest {{ $statusColors[$app->status] ?? 'bg-slate-500/10 text-slate-400' }}">
                            {{ $app->status }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        @if($app->status === 'pending')
                        <div class="flex justify-end gap-2" x-data="{ 
                            openModal: false, 
                            action: '', 
                            notes: '',
                            submitForm() {
                                $refs.form.status.value = this.action;
                                $refs.form.notes.value = this.notes;
                                $refs.form.submit();
                            }
                        }">
                            <button @click="action = 'approved'; openModal = true" class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all" title="Setujui">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                            <button @click="action = 'rejected'; openModal = true" class="p-2 rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500/20 transition-all" title="Tolak">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            {{-- Validation Modal --}}
                            <template x-teleport="body">
                                <div x-show="openModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
                                    <div class="card-glass rounded-3xl p-8 w-full max-w-md shadow-2xl scale-in-center overflow-hidden border" :style="'border-color: var(--border-color); background: var(--surface-color)'">
                                        <h3 class="text-lg font-extrabold themed-text mb-4" x-text="action === 'approved' ? 'Setujui Pengajuan' : 'Tolak Pengajuan'"></h3>
                                        <div class="mb-6">
                                            <label class="block text-[10px] font-bold themed-text-muted uppercase tracking-widest mb-2">Catatan (Opsional)</label>
                                            <textarea x-model="notes" class="w-full themed-input rounded-xl px-4 py-3 text-sm themed-text focus:ring-primary h-24 placeholder-slate-500" placeholder="Masukkan alasan atau catatan..."></textarea>
                                        </div>
                                        <div class="flex justify-end gap-3">
                                            <button @click="openModal = false" class="px-6 py-2.5 btn-soft-secondary rounded-xl text-xs font-bold">Batal</button>
                                            <button @click="submitForm()" class="px-6 py-2.5 text-white rounded-xl text-xs font-bold transition-all" :class="action === 'approved' ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-rose-500 shadow-rose-500/20'">Konfirmasi</button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <form x-ref="form" action="{{ route('admin.discount-applications.update', $app) }}" method="POST" class="hidden">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status">
                                <input type="hidden" name="notes">
                            </form>
                        </div>
                        @else
                        <span class="text-[10px] themed-text-muted italic">{{ $app->notes ?: '-' }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
