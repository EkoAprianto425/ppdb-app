@extends('layouts.app')

@section('title', 'Manajemen Alasan Memilih Sekolah')
@section('page-title', 'Alasan Memilih Sekolah')
@section('page-subtitle', 'Kelola daftar pilihan alasan dari mana siswa memilih sekolah ini')

@section('content')
<div x-data="{ showEditModal: false, editData: { id: '', name: '', is_active: false } }" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Form Tambah Alasan --}}
    <div class="card-glass rounded-3xl p-8 h-fit lg:sticky lg:top-8 border border-white/5 shadow-2xl">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-black themed-text leading-none">Tambah Alasan</h3>
                <p class="text-[10px] themed-text-muted uppercase tracking-widest mt-1">Buat pilihan alasan baru</p>
            </div>
        </div>

        <form action="{{ route('admin.school-reasons.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Nama Alasan</label>
                <div class="relative">
                    <input type="text" name="name" required placeholder="Contoh: Dekat rumah, Prestasi, dll"
                           class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                </div>
                @error('name')
                    <p class="text-rose-500 text-[10px] mt-2 px-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-5 rounded-2xl bg-primary text-white font-black uppercase tracking-[0.2em] text-xs shadow-[0_10px_30px_-10px_rgba(var(--primary-rgb),0.5)] hover:shadow-primary/40 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Simpan Alasan</span>
            </button>
        </form>
    </div>

    {{-- Daftar Alasan --}}
    <div class="lg:col-span-2">
        <div class="card-glass rounded-3xl overflow-hidden border border-white/5 shadow-2xl">
            @if($reasons->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse datatable">
                    <thead>
                        <tr class="bg-black/20 border-b border-white/5">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">No</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Nama Alasan</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted text-right" data-dt-order="disable">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($reasons as $index => $reason)
                        <tr class="hover:bg-primary/5 transition-all group">
                            <td class="px-6 py-4 text-xs font-bold themed-text-muted">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-black themed-text group-hover:text-primary transition-colors mb-1">{{ $reason->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($reason->is_active)
                                    <span class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[9px] font-black uppercase tracking-widest text-emerald-500">Aktif</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-[9px] font-black uppercase tracking-widest text-rose-500">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Edit Trigger --}}
                                    <button @click="editData = { id: {{ $reason->id }}, name: {{ json_encode($reason->name) }}, is_active: {{ $reason->is_active ? 'true' : 'false' }} }; showEditModal = true" 
                                            class="p-2 rounded-lg btn-action-edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <form action="{{ route('admin.school-reasons.destroy', $reason) }}" method="POST" onsubmit="return confirm('Hapus alasan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 rounded-lg btn-action-delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-16 text-center">
                <div class="w-24 h-24 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="themed-text text-lg font-black mb-2 tracking-tight">Belum Ada Alasan</h4>
                <p class="text-xs themed-text-muted max-w-[280px] mx-auto leading-relaxed">Gunakan formulir di samping untuk menambah daftar alasan memilih sekolah.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Edit Modal --}}
    <template x-teleport="body">
        <div x-show="showEditModal" 
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-y-auto" 
             x-cloak 
             style="display: none;">
            
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="showEditModal = false"></div>

            {{-- Modal Container --}}
            <div class="relative w-full max-w-lg max-h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden border"
                 @click.away="showEditModal = false"
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
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <h3 class="text-lg font-extrabold themed-text">Edit Alasan Sekolah</h3>
                        </div>
                    </div>
                    <button @click="showEditModal = false"
                            class="w-9 h-9 rounded-xl flex items-center justify-center themed-text-muted hover:text-red-400 transition-colors border"
                            :style="'border-color: var(--border-color); background: var(--card-bg)'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="flex-1 overflow-y-auto p-6">
                    <form :action="`/admin/school-reasons/${editData.id}`" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Nama Alasan</label>
                            <input type="text" name="name" x-model="editData.name" required
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="edit_is_active" name="is_active" x-model="editData.is_active" value="1" class="w-5 h-5 rounded-lg bg-black/20 border-2 border-white/5 text-primary focus:ring-0 transition-all">
                            <label for="edit_is_active" class="text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] pt-0.5">Aktifkan Alasan</label>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t" :style="'border-color: var(--border-color)'">
                            <button type="button" @click="showEditModal = false" class="btn-soft-secondary rounded-xl px-5 py-2.5 text-xs font-bold">Batal</button>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-bold shadow-md transition-all active:scale-95">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
