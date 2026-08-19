@extends('layouts.app')

@section('title', 'Manajemen Informasi PDF')
@section('page-title', 'Informasi PDF')
@section('page-subtitle', 'Kelola file brosur dan informasi biaya')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Form Upload --}}
    <div class="card-glass rounded-3xl p-8 h-fit">
        <h3 class="text-lg font-bold themed-text mb-6">Upload PDF Baru</h3>
        <form action="{{ route('admin.information-pdfs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Judul PDF</label>
                <input type="text" name="title" required placeholder="Contoh: Brosur SMP 2024"
                       class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Tipe File</label>
                <select name="type" required class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] themed-text-muted mt-2 italic">*Jika tipe sudah ada, file lama akan ditimpa.</p>
            </div>

            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">File PDF</label>
                <input type="file" name="file" accept="application/pdf" required
                       class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
            </div>

            <button type="submit" class="w-full py-3 rounded-xl btn-soft-primary font-bold uppercase tracking-widest text-xs mt-2">Upload File</button>
        </form>
    </div>

    {{-- List PDF --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($pdfs as $pdf)
            <div class="card-glass rounded-3xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <form action="{{ route('admin.information-pdfs.destroy', $pdf) }}" method="POST" onsubmit="return confirm('Hapus file ini?')">
                        @csrf @method('DELETE')
                        <button class="w-8 h-8 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-500/10 flex items-center justify-center text-red-500 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <span class="inline-block px-2 py-1 rounded-md border border-primary/20 bg-primary/10 text-primary text-[9px] font-black uppercase tracking-widest mb-2">
                            {{ $types[$pdf->type] ?? $pdf->type }}
                        </span>
                        <h4 class="font-bold themed-text text-sm mb-1 line-clamp-2">{{ $pdf->title }}</h4>
                        <p class="text-[10px] themed-text-muted mb-3">Diunggah: {{ $pdf->created_at->format('d M Y') }}</p>
                        
                        <a href="{{ Storage::url($pdf->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-[10px] font-bold text-primary hover:underline">
                            Lihat File <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
            
            @if($pdfs->isEmpty())
                <div class="col-span-1 md:col-span-2 card-glass rounded-3xl p-10 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-white/30 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-sm font-bold themed-text">Belum ada file PDF</p>
                    <p class="text-xs themed-text-muted">Upload file brosur atau informasi biaya pada form di samping.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
