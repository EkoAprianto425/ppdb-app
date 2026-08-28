@extends('layouts.app')

@section('title', 'Manajemen Jenjang')
@section('page-title', 'Manajemen Jenjang')
@section('page-subtitle', 'Kelola daftar jenjang pendidikan yang tersedia di sistem')

@section('content')
<div class="space-y-6">
    {{-- Action Bar --}}
    <div class="flex justify-end">
        <button @click="$dispatch('open-modal', 'modal-add-level')" 
                class="px-6 py-3 rounded-2xl bg-primary text-white text-xs font-bold uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all">
            Tambah Jenjang Baru
        </button>
    </div>

    {{-- Level Table --}}
    <div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left datatable" id="levels-table">
                <thead>
                    <tr class="border-b" :style="'border-color: var(--border-color)'">
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">No. Urut</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Nama Jenjang</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Induk Unit</th>
                        <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-right" data-dt-order="disable">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" :style="'divide-color: var(--border-color)'">
                    @foreach($levels as $level)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-xs font-bold themed-text-muted">#{{ $level->sort_order }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-bold themed-text">{{ $level->name }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase">{{ $level->parent_unit }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="$dispatch('open-modal', 'edit-level-{{ $level->id }}')" 
                                        class="p-2 rounded-lg btn-action-edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <form action="{{ route('admin.levels.destroy', $level) }}" method="POST" onsubmit="return confirm('Hapus jenjang ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg btn-action-delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2m3 4h.01"/></svg>
                                    </button>
                                </form>
                            </div>

                            {{-- Modal Edit --}}
                            <template x-teleport="body">
                                <div x-data="{ open: false }" 
                                     @open-modal.window="if($event.detail === 'edit-level-{{ $level->id }}') open = true" 
                                     x-show="open" 
                                     x-cloak 
                                     style="display:none;"
                                     class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-y-auto text-left">
                                    
                                    {{-- Backdrop --}}
                                    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="open = false"></div>

                                    {{-- Modal Container --}}
                                    <div class="relative w-full max-w-lg max-h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden border"
                                         @click.away="open = false"
                                         :style="'background: var(--surface-color); border-color: var(--border-color)'"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95">
                                        
                                        {{-- HEADER --}}
                                        <div class="shrink-0 px-6 py-5 border-b flex items-center justify-between"
                                             :style="'border-color: var(--border-color); background: var(--card-bg)'">
                                            <div>
                                                <div class="flex items-center gap-3 mb-1">
                                                    <div class="w-8 h-8 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                    </div>
                                                    <h3 class="text-lg font-extrabold themed-text">Edit Jenjang</h3>
                                                </div>
                                            </div>
                                            <button @click="open = false"
                                                    class="w-9 h-9 rounded-xl flex items-center justify-center themed-text-muted hover:text-red-400 transition-colors border"
                                                    :style="'border-color: var(--border-color); background: var(--card-bg)'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>

                                        {{-- BODY --}}
                                        <div class="flex-1 overflow-y-auto p-6">
                                            <form action="{{ route('admin.levels.update', $level) }}" method="POST" class="space-y-5">
                                                @csrf @method('PUT')
                                                <div>
                                                    <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Nama Jenjang</label>
                                                    <input type="text" name="name" value="{{ $level->name }}" required class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10" placeholder="Contoh: SMA REGULER">
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Induk Unit</label>
                                                        <select name="parent_unit" required class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none">
                                                            <option value="SMP" {{ $level->parent_unit == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                            <option value="SMA" {{ $level->parent_unit == 'SMA' ? 'selected' : '' }}>SMA</option>
                                                            <option value="SMK" {{ $level->parent_unit == 'SMK' ? 'selected' : '' }}>SMK</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">No. Urut</label>
                                                        <input type="number" name="sort_order" value="{{ $level->sort_order }}" required class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Kontak WhatsApp Unit (Opsional)</label>
                                                    <input type="text" name="contact_whatsapp" value="{{ $level->contact_whatsapp }}" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10" placeholder="Contoh: 08123456789">
                                                </div>
                                                
                                                {{-- Footer Actions --}}
                                                <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t" :style="'border-color: var(--border-color)'">
                                                    <button type="button" @click="open = false" class="btn-soft-secondary rounded-xl px-5 py-2.5 text-xs font-bold">Batal</button>
                                                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-bold shadow-md transition-all active:scale-95">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Add --}}
<template x-teleport="body">
    <div x-data="{ open: false }" 
         @open-modal.window="if($event.detail === 'modal-add-level') open = true" 
         x-show="open" 
         x-cloak 
         style="display:none;"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-y-auto">
        
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="open = false"></div>

        {{-- Modal Container --}}
        <div class="relative w-full max-w-lg max-h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden border"
             @click.away="open = false"
             :style="'background: var(--surface-color); border-color: var(--border-color)'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            {{-- HEADER --}}
            <div class="shrink-0 px-6 py-5 border-b flex items-center justify-between"
                 :style="'border-color: var(--border-color); background: var(--card-bg)'">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-8 h-8 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <h3 class="text-lg font-extrabold themed-text">Tambah Jenjang Baru</h3>
                    </div>
                </div>
                <button @click="open = false"
                        class="w-9 h-9 rounded-xl flex items-center justify-center themed-text-muted hover:text-red-400 transition-colors border"
                        :style="'border-color: var(--border-color); background: var(--card-bg)'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- BODY --}}
            <div class="flex-1 overflow-y-auto p-6">
                <form action="{{ route('admin.levels.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Nama Jenjang</label>
                        <input type="text" name="name" required class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10" placeholder="Contoh: SMA REGULER">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Induk Unit</label>
                            <select name="parent_unit" required class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none">
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="SMK">SMK</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">No. Urut</label>
                            <input type="number" name="sort_order" value="0" required class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Kontak WhatsApp Unit (Opsional)</label>
                        <input type="text" name="contact_whatsapp" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10" placeholder="Contoh: 08123456789">
                    </div>
                    
                    {{-- Footer Actions --}}
                    <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t" :style="'border-color: var(--border-color)'">
                        <button type="button" @click="open = false" class="btn-soft-secondary rounded-xl px-5 py-2.5 text-xs font-bold">Batal</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-bold shadow-md transition-all active:scale-95">Tambah Jenjang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
@endsection
