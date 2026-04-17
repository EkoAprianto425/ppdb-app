@extends('layouts.app')

@section('title', 'Master Administrasi')
@section('page-title', 'Master Administrasi')
@section('page-subtitle', 'Kelola daftar biaya administrasi berdasarkan jenjang pendidikan')

@section('content')
<div class="space-y-6">
    {{-- Action Bar --}}
    <div class="flex justify-end">
        <button @click="$dispatch('open-modal', 'modal-add-fee')" 
                class="px-6 py-3 rounded-2xl bg-primary text-white text-xs font-bold uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all">
            Tambah Biaya Baru
        </button>
    </div>

    {{-- Fees Table --}}
    <div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b" :style="'border-color: var(--border-color)'">
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">No. Urut</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Nama Administrasi</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Jenjang</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Harga</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" :style="'divide-color: var(--border-color)'">
                    @forelse($fees as $fee)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-xs font-bold themed-text-muted">#{{ $fee->sort_order }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-bold themed-text">{{ $fee->name }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase">{{ $fee->level->name }}</span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <p class="text-sm font-black themed-text">Rp {{ number_format($fee->amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="$dispatch('open-modal', 'edit-fee-{{ $fee->id }}')" 
                                        class="p-2 rounded-lg themed-text-muted hover:bg-primary/10 hover:text-primary transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <form action="{{ route('admin.financial.destroy', $fee) }}" method="POST" onsubmit="return confirm('Hapus biaya ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg themed-text-muted hover:bg-rose-500/10 hover:text-rose-500 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m3 4h.01"/></svg>
                                    </button>
                                </form>
                            </div>

                            {{-- Modal Edit --}}
                            <div x-data="{ open: false }" @open-modal.window="if($event.detail === 'edit-fee-{{ $fee->id }}') open = true" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm text-left">
                                <div @click.away="open = false" class="card-glass rounded-3xl p-8 w-full max-w-lg shadow-2xl scale-in-center">
                                    <h3 class="text-xl font-bold themed-text mb-6">Edit Biaya Administrasi</h3>
                                    <form action="{{ route('admin.financial.update', $fee) }}" method="POST" class="space-y-4">
                                        @csrf @method('PUT')
                                        <div>
                                            <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Nama Administrasi</label>
                                            <input type="text" name="name" value="{{ $fee->name }}" required class="w-full themed-input rounded-xl px-4 py-3 text-sm" placeholder="Contoh: Biaya Seragam">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Jenjang</label>
                                            <select name="educational_level_id" required class="w-full themed-input rounded-xl px-4 py-3 text-sm">
                                                @foreach($levels as $level)
                                                    <option value="{{ $level->id }}" {{ $fee->educational_level_id == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Harga (Rp)</label>
                                                <input type="number" name="amount" value="{{ (int)$fee->amount }}" required class="w-full themed-input rounded-xl px-4 py-3 text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">No. Urut Bayar</label>
                                                <input type="number" name="sort_order" value="{{ $fee->sort_order }}" required class="w-full themed-input rounded-xl px-4 py-3 text-sm">
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full py-3 rounded-xl bg-primary text-white font-bold uppercase tracking-widest text-xs mt-4">Simpan Perubahan</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-10 text-center themed-text-muted italic text-xs">Belum ada data biaya administrasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Add --}}
<div x-data="{ open: false }" @open-modal.window="if($event.detail === 'modal-add-fee') open = true" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm text-left">
    <div @click.away="open = false" class="card-glass rounded-3xl p-8 w-full max-w-lg shadow-2xl scale-in-center">
        <h3 class="text-xl font-bold themed-text mb-6">Tambah Biaya Baru</h3>
        <form action="{{ route('admin.financial.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Nama Administrasi</label>
                <input type="text" name="name" required class="w-full themed-input rounded-xl px-4 py-3 text-sm" placeholder="Contoh: Biaya Pendaftaran">
            </div>
            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Jenjang</label>
                <select name="educational_level_id" required class="w-full themed-input rounded-xl px-4 py-3 text-sm">
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Harga (Rp)</label>
                    <input type="number" name="amount" required class="w-full themed-input rounded-xl px-4 py-3 text-sm" placeholder="0">
                </div>
                <div>
                    <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">No. Urut Bayar</label>
                    <input type="number" name="sort_order" value="1" required class="w-full themed-input rounded-xl px-4 py-3 text-sm">
                </div>
            </div>
            <button type="submit" class="w-full py-3 rounded-xl bg-primary text-white font-bold uppercase tracking-widest text-xs mt-4 shadow-lg shadow-primary/20">Tambah Biaya</button>
        </form>
    </div>
</div>
@endsection
