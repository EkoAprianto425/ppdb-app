@extends('layouts.app')

@section('title', 'Manajemen Sumber Informasi')
@section('page-title', 'Sumber Informasi')
@section('page-subtitle', 'Kelola daftar pilihan dari mana siswa mengetahui sekolah')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Form Tambah Sumber Informasi --}}
    <div class="card-glass rounded-3xl p-8 h-fit lg:sticky lg:top-8 border border-white/5 shadow-2xl">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-black themed-text leading-none">Tambah Sumber</h3>
                <p class="text-[10px] themed-text-muted uppercase tracking-widest mt-1">Buat pilihan sumber baru</p>
            </div>
        </div>

        <form action="{{ route('admin.information-sources.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Nama Sumber</label>
                <div class="relative">
                    <input type="text" name="name" required placeholder="Contoh: Media Sosial, Spanduk, dll"
                           class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                </div>
                @error('name')
                    <p class="text-rose-500 text-[10px] mt-2 px-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-5 rounded-2xl bg-primary text-white font-black uppercase tracking-[0.2em] text-xs shadow-[0_10px_30px_-10px_rgba(var(--primary-rgb),0.5)] hover:shadow-primary/40 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Simpan Sumber</span>
            </button>
        </form>
    </div>

    {{-- Daftar Sumber Informasi --}}
    <div class="lg:col-span-2">
        <div class="card-glass rounded-3xl overflow-hidden border border-white/5 shadow-2xl">
            @if($sources->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse datatable">
                    <thead>
                        <tr class="bg-black/20 border-b border-white/5">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">No</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Nama Sumber</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted text-right" data-dt-order="disable">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($sources as $index => $source)
                        <tr class="hover:bg-primary/5 transition-all group">
                            <td class="px-6 py-4 text-xs font-bold themed-text-muted">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-black themed-text group-hover:text-primary transition-colors mb-1">{{ $source->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($source->is_active)
                                    <span class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[9px] font-black uppercase tracking-widest text-emerald-500">Aktif</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-[9px] font-black uppercase tracking-widest text-rose-500">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Edit Trigger --}}
                                    <button onclick="openEditModal({{ $source->id }}, {{ json_encode($source->name) }}, {{ $source->is_active ? 1 : 0 }})" 
                                            class="p-2 rounded-lg bg-white/5 text-white/20 hover:bg-primary/10 hover:text-primary transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <form action="{{ route('admin.information-sources.destroy', $source) }}" method="POST" onsubmit="return confirm('Hapus sumber informasi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 rounded-lg bg-white/5 text-white/20 hover:bg-rose-500/10 hover:text-rose-500 transition-all">
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
                <h4 class="themed-text text-lg font-black mb-2 tracking-tight">Belum Ada Sumber Informasi</h4>
                <p class="text-xs themed-text-muted max-w-[280px] mx-auto leading-relaxed">Gunakan formulir di samping untuk menambah daftar dari mana siswa mengetahui informasi sekolah.</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-6">
        <div class="card-glass rounded-[2rem] p-8 border border-white/10 shadow-2xl">
            <h3 class="text-xl font-black themed-text mb-6">Edit Sumber Informasi</h3>
            
            <form id="editForm" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Nama Sumber</label>
                    <input type="text" id="edit_name" name="name" required
                           class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="edit_is_active" name="is_active" class="w-5 h-5 rounded-lg bg-black/20 border-2 border-white/5 text-primary focus:ring-0 transition-all">
                    <label for="edit_is_active" class="text-xs font-black themed-text-muted uppercase tracking-widest">Aktifkan Sumber</label>
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="closeEditModal()" class="flex-1 py-4 rounded-2xl bg-white/5 text-white font-black uppercase tracking-widest text-[10px] hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-4 rounded-2xl bg-primary text-white font-black uppercase tracking-widest text-[10px] shadow-lg shadow-primary/20 hover:-translate-y-1 transition-all">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function openEditModal(id, name, isActive) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        const nameInput = document.getElementById('edit_name');
        const activeInput = document.getElementById('edit_is_active');
        
        form.action = `/admin/information-sources/${id}`;
        nameInput.value = name;
        activeInput.checked = isActive;
        
        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
@endsection
