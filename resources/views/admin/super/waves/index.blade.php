@extends('layouts.app')

@section('title', 'Manajemen Gelombang')
@section('page-title', 'Gelombang Pendaftaran')
@section('page-subtitle', 'Periode Aktif: ' . ($activeYear->name ?? '-'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Form --}}
    <div class="card-glass rounded-3xl p-8 h-fit">
        <h3 class="text-lg font-bold themed-text mb-6">Tambah Gelombang</h3>
        <form action="{{ route('admin.wave.store') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
            
            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Nama Gelombang</label>
                <input type="text" name="name" placeholder="Contoh: Gelombang 1" required
                       class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Mulai</label>
                    <input type="text" name="start_date" required readOnly
                           class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all datepicker" placeholder="Pilih Tanggal">
                </div>
                <div>
                    <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Selesai</label>
                    <input type="text" name="end_date" required readOnly
                           class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all datepicker" placeholder="Pilih Tanggal">
                </div>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl btn-soft-primary font-bold uppercase tracking-widest text-xs">Simpan Gelombang</button>
        </form>
    </div>

    {{-- List --}}
    <div class="lg:col-span-2 space-y-4">
        @if(session('error'))
            <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="card-glass rounded-3xl overflow-hidden">
            <table class="w-full text-left datatable" id="waves-table">
                <thead>
                    <tr class="border-b" :style="'border-color: var(--border-color)'">
                        <th class="px-6 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Nama Gelombang</th>
                        <th class="px-6 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Periode</th>
                        <th class="px-6 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-right" data-dt-order="disable">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" :style="'divide-color: var(--border-color)'">
                    @foreach($waves as $wave)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold themed-text">{{ $wave->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs themed-text-muted">{{ date('d M Y', strtotime($wave->start_date)) }} - {{ date('d M Y', strtotime($wave->end_date)) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($wave->is_active)
                                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20">Aktif</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-slate-500/10 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-white/5">Antrean</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$wave->is_active)
                                <form action="{{ route('admin.wave.update', $wave) }}" method="POST">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="activate" value="1">
                                    <button class="p-2 rounded-lg btn-soft-primary" title="Aktifkan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.wave.destroy', $wave) }}" method="POST" onsubmit="return confirm('Hapus gelombang ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 rounded-lg btn-action-delete" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
