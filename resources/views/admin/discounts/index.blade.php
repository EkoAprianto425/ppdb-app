@extends('layouts.app')

@section('title', 'Manajemen Potongan Harga')
@section('page-title', 'Master Potongan Harga')
@section('page-subtitle', 'Kelola kriteria diskon untuk alumni, umum, dan anak pegawai')

@section('content')
<div class="space-y-6">
    {{-- Filter & Action Bar --}}
    <div class="card-glass rounded-3xl p-5 border border-white/5 shadow-2xl flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </div>
            <div>
                <h4 class="text-xs font-black themed-text uppercase tracking-wider">Filter Jenjang / Jurusan</h4>
                <p class="text-[10px] themed-text-muted">Tampilkan potongan berdasarkan unit pendidikan</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 flex-1 sm:flex-initial justify-end">
            <form action="{{ route('admin.discounts.index') }}" method="GET" class="flex items-center gap-3">
                <select name="level_id" onchange="this.form.submit()" class="bg-black/20 border-2 border-white/5 rounded-2xl px-4 py-2.5 text-xs themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none cursor-pointer min-w-[200px]">
                    <option value="" class="text-slate-900">Semua Jenjang / Jurusan</option>
                    <option value="general" {{ request('level_id') === 'general' ? 'selected' : '' }} class="text-slate-900">Semua Jenjang (Lintas Unit)</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }} class="text-slate-900">{{ $level->name }}</option>
                    @endforeach
                </select>

                @if(request()->filled('level_id'))
                    <a href="{{ route('admin.discounts.index') }}" class="px-4 py-2.5 rounded-2xl bg-white/5 hover:bg-white/10 text-[10px] font-black uppercase tracking-wider themed-text-muted transition-all flex items-center gap-1.5" title="Reset Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Reset</span>
                    </a>
                @endif
            </form>

            <button onclick="openCreateModal()" class="px-5 py-2.5 rounded-2xl bg-primary text-white text-xs font-black uppercase tracking-wider shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Potongan</span>
            </button>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card-glass rounded-3xl overflow-hidden border border-white/5 shadow-2xl">
        @if($discounts->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse datatable">
                <thead>
                    <tr class="bg-black/20 border-b border-white/5">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Kebijakan & Jenjang</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Besaran</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] themed-text-muted text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($discounts as $discount)
                    <tr class="hover:bg-primary/5 transition-all group">
                        <td class="px-6 py-4">
                            <p class="text-xs font-black themed-text group-hover:text-primary transition-colors mb-1">{{ $discount->name }}</p>
                            <span class="px-2 py-0.5 rounded-md bg-white/5 border border-white/10 text-[8px] font-black themed-text-muted uppercase tracking-widest">
                                {{ $discount->educationalLevel?->name ?? 'Semua Jenjang' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold themed-text-muted uppercase tracking-wider">
                                {{ str_replace('_', ' ', $discount->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                @if($discount->category === 'anak_pegawai')
                                    <span class="text-[11px] font-black themed-text text-primary">Biaya Pendaftaran: Rp {{ number_format($discount->amount, 0, ',', '.') }}</span>
                                    <span class="text-[9px] themed-text-muted font-bold italic">Biaya SPP: Rp {{ number_format($discount->spp_amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-[11px] font-black themed-text text-primary">Potongan: Rp {{ number_format($discount->amount, 0, ',', '.') }}</span>
                                    <span class="text-[9px] themed-text-muted font-bold italic">Sisa Kuota: {{ $discount->qty }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($discount->is_active)
                                <span class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[9px] font-black uppercase tracking-widest text-emerald-500">Aktif</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-[9px] font-black uppercase tracking-widest text-rose-500">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="openEditModal({{ json_encode($discount) }})" 
                                        class="p-2 rounded-lg bg-white/5 text-white/20 hover:bg-primary/10 hover:text-primary transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" onsubmit="return confirm('Hapus master potongan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 rounded-lg bg-white/5 text-white/20 hover:bg-rose-500/10 hover:text-rose-500 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
        <div class="p-20 text-center">
            <div class="w-24 h-24 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h4 class="themed-text text-lg font-black mb-2 tracking-tight">Belum Ada Kebijakan Potongan</h4>
            <p class="text-xs themed-text-muted max-w-[320px] mx-auto leading-relaxed uppercase tracking-widest">
                @if(request()->filled('level_id'))
                    Tidak ada kebijakan potongan yang sesuai dengan filter jenjang / jurusan ini.
                @else
                    Klik tombol "Tambah Potongan" di atas untuk mulai membuat skema potongan baru.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

{{-- Create Modal --}}
<div id="createModal" class="fixed inset-0 z-50 hidden" x-data="{ category: 'alumni' }">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeCreateModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <div class="card-glass rounded-[2rem] p-8 border border-white/10 shadow-2xl">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-black themed-text leading-none">Tambah Kebijakan</h3>
                    <p class="text-[10px] themed-text-muted uppercase tracking-widest mt-1">Buat kebijakan potongan baru</p>
                </div>
            </div>

            <form action="{{ route('admin.discounts.store') }}" method="POST" class="space-y-5">
                @csrf
                
                {{-- Kategori --}}
                <div>
                    <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Kategori Potongan</label>
                    <select name="category" x-model="category" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none" required>
                        <option value="alumni">Alumni</option>
                        <option value="umum">Umum</option>
                        <option value="anak_pegawai">Anak Pegawai</option>
                    </select>
                </div>

                {{-- Fields for Alumni & Umum --}}
                <template x-if="category === 'alumni' || category === 'umum'">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Kriteria</label>
                            <input type="text" name="name" required placeholder="Contoh: Prestasi Akademik"
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Besar Potongan (Rp)</label>
                            <input type="text" name="amount" required placeholder="0"
                                   onkeyup="formatRupiah(this)"
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Kuota (Qty)</label>
                            <input type="number" name="qty" required placeholder="Contoh: 10"
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Untuk Siapa</label>
                            <select name="apply_to" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none" required>
                                <option value="alumni">Alumni</option>
                                <option value="umum">Umum</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-3 px-1">
                            <input type="checkbox" name="require_document" id="req_doc" value="1" class="w-5 h-5 rounded-lg bg-black/20 border-2 border-white/5 text-primary focus:ring-0 transition-all">
                            <label for="req_doc" class="text-xs font-black themed-text-muted uppercase tracking-widest">Wajib Upload Dokumen?</label>
                        </div>
                    </div>
                </template>

                {{-- Fields for Anak Pegawai --}}
                <template x-if="category === 'anak_pegawai'">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Status Kepegawaian Orang Tua</label>
                            <input type="text" name="name" required placeholder="Contoh: Guru Tetap, Guru Tidak Tetap, Staff, dll"
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Besar Potongan / Biaya Masuk (Rp)</label>
                            <input type="text" name="amount" required placeholder="0"
                                   onkeyup="formatRupiah(this)"
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Biaya SPP (Rp)</label>
                            <input type="text" name="spp_amount" placeholder="0"
                                   onkeyup="formatRupiah(this)"
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                        </div>
                        {{-- Info: Gelombang & Jenjang tidak diperlukan untuk Anak Pegawai --}}
                        <div class="rounded-xl bg-indigo-500/10 border border-indigo-500/20 px-4 py-3 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-[10px] text-indigo-400 font-bold leading-relaxed">Keringanan Anak Pegawai berlaku lintas gelombang dan semua jenjang — tidak perlu memilih gelombang & jenjang.</p>
                        </div>
                    </div>
                </template>

                {{-- Common Fields: Jenjang hanya untuk Alumni & Umum --}}
                <template x-if="category !== 'anak_pegawai'">
                    <div>
                        <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Jenjang</label>
                        <select name="educational_level_id" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none" required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>
                
                <div>
                    <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Keterangan tambahan..."
                              class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10 resize-none"></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="closeCreateModal()" class="flex-1 py-4 rounded-2xl bg-white/5 text-white font-black uppercase tracking-widest text-[10px] hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-4 rounded-2xl bg-primary text-white font-black uppercase tracking-widest text-[10px] shadow-lg shadow-primary/20 hover:-translate-y-1 transition-all">Simpan Kebijakan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 z-50 hidden" x-data="{ category: 'alumni' }">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <div class="card-glass rounded-[2rem] p-8 border border-white/10 shadow-2xl">
            <h3 class="text-xl font-black themed-text mb-6">Edit Kebijakan Potongan</h3>
            
            <form id="editForm" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Kategori Potongan</label>
                    <select id="edit_category" name="category" x-model="category" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none" required>
                        <option value="alumni">Alumni</option>
                        <option value="umum">Umum</option>
                        <option value="anak_pegawai">Anak Pegawai</option>
                    </select>
                </div>

                {{-- Fields for Alumni & Umum --}}
                <template x-if="category === 'alumni' || category === 'umum'">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Kriteria</label>
                            <input type="text" id="edit_name" name="name" required
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Besar Potongan (Rp)</label>
                            <input type="text" id="edit_amount" name="amount" required
                                   onkeyup="formatRupiah(this)"
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Kuota (Qty)</label>
                            <input type="number" id="edit_qty" name="qty" required
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Untuk Siapa</label>
                            <select id="edit_apply_to" name="apply_to" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none">
                                <option value="alumni">Alumni</option>
                                <option value="umum">Umum</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-3 px-1">
                            <input type="checkbox" id="edit_require_document" name="require_document" value="1" class="w-5 h-5 rounded-lg bg-black/20 border-2 border-white/5 text-primary focus:ring-0 transition-all">
                            <label for="edit_require_document" class="text-xs font-black themed-text-muted uppercase tracking-widest">Wajib Upload Dokumen?</label>
                        </div>
                    </div>
                </template>

                {{-- Fields for Anak Pegawai --}}
                <template x-if="category === 'anak_pegawai'">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Status Kepegawaian Orang Tua</label>
                            <input type="text" id="edit_name_pegawai" name="name" required
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Besar Potongan / Biaya Masuk (Rp)</label>
                            <input type="text" id="edit_amount_pegawai" name="amount" required
                                   onkeyup="formatRupiah(this)"
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Biaya SPP (Rp)</label>
                            <input type="text" id="edit_spp_amount" name="spp_amount"
                                   onkeyup="formatRupiah(this)"
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all">
                        </div>
                        {{-- Info: Gelombang & Jenjang tidak diperlukan untuk Anak Pegawai --}}
                        <div class="rounded-xl bg-indigo-500/10 border border-indigo-500/20 px-4 py-3 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-[10px] text-indigo-400 font-bold leading-relaxed">Keringanan Anak Pegawai berlaku lintas gelombang dan semua jenjang — tidak perlu memilih gelombang & jenjang.</p>
                        </div>
                    </div>
                </template>

                {{-- Jenjang hanya untuk Alumni & Umum --}}
                <template x-if="category !== 'anak_pegawai'">
                    <div>
                        <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Jenjang</label>
                        <select id="edit_educational_level_id" name="educational_level_id" class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none" required>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>
                
                <div>
                    <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Deskripsi</label>
                    <textarea id="edit_description" name="description" rows="3"
                              class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10 resize-none"></textarea>
                </div>

                <div class="flex items-center gap-3 px-1">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="w-5 h-5 rounded-lg bg-black/20 border-2 border-white/5 text-primary focus:ring-0 transition-all">
                    <label for="edit_is_active" class="text-xs font-black themed-text-muted uppercase tracking-widest">Status Aktif</label>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="closeEditModal()" class="flex-1 py-4 rounded-2xl bg-white/5 text-white font-black uppercase tracking-widest text-[10px] hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-4 rounded-2xl bg-primary text-white font-black uppercase tracking-widest text-[10px] shadow-lg shadow-primary/20 hover:-translate-y-1 transition-all">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function formatRupiah(el) {
        let value = el.value.replace(/[^0-9]/g, '');
        el.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(discount) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        // Use Alpine's scope to set category
        const alpineData = Alpine.$data(modal);
        alpineData.category = discount.category;

        form.action = `/admin/discounts/${discount.id}`;
        
        // Common fields (Jenjang hanya ada untuk non anak_pegawai)
        document.getElementById('edit_description').value = discount.description || '';
        document.getElementById('edit_is_active').checked = discount.is_active;

        // Category specific fields — tunggu Alpine render konten kondisional
        setTimeout(() => {
            if (discount.category === 'anak_pegawai') {
                const namePegawai = document.getElementById('edit_name_pegawai');
                const amtPegawai  = document.getElementById('edit_amount_pegawai');
                const sppEl       = document.getElementById('edit_spp_amount');
                if (namePegawai) namePegawai.value = discount.name || '';
                if (amtPegawai)  amtPegawai.value  = formatNumber(discount.amount);
                if (sppEl)       sppEl.value        = formatNumber(discount.spp_amount);
            } else {
                const levelEl = document.getElementById('edit_educational_level_id');
                if (levelEl) levelEl.value = discount.educational_level_id;
                document.getElementById('edit_name').value    = discount.name || '';
                document.getElementById('edit_amount').value  = formatNumber(discount.amount);
                document.getElementById('edit_qty').value     = discount.qty;
                document.getElementById('edit_apply_to').value = discount.apply_to;
                document.getElementById('edit_require_document').checked = discount.require_document;
            }
        }, 80);

        modal.classList.remove('hidden');
    }

    function formatNumber(num) {
        return num.toString().split('.')[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
@endsection
