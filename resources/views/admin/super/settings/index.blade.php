@extends('layouts.app')

@section('title', 'Konfigurasi Website')
@section('page-title', 'Pengaturan Global')
@section('page-subtitle', 'Kustomisasi nama, logo, warna tema, dan informasi situs aplikasi')

@section('content')
<div class="max-w-4xl space-y-6">
    @if(session('status'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm mb-4">
            {{ session('status') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="card-glass rounded-3xl p-8">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- ── Bagian 1: Identitas Aplikasi ── --}}
            <div>
                <h2 class="text-xs font-extrabold themed-text-muted uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-1 h-4 rounded-full bg-primary inline-block"></span>
                    Identitas Aplikasi
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Nama Aplikasi</label>
                            <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'PPDB Online') }}" required
                                   class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
                            <p class="text-[10px] themed-text-muted mt-2">Nama ini akan menimpa seluruh pemanggilan statis "PPDB Online".</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Deskripsi Website (SEO Meta)</label>
                            <textarea name="meta_description" rows="3"
                                      class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">{{ old('meta_description', $settings['meta_description'] ?? 'PPDB Online - Pendaftaran Peserta Didik Baru') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Teks Footer Hak Cipta</label>
                            <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings['footer_copyright'] ?? '© ' . date('Y') . ' Yayasan Pendidikan Nusantara. All rights reserved.') }}"
                                   class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Logo Aplikasi</label>
                            <div class="flex items-center gap-6">
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-400/20 to-purple-500/20 flex flex-col items-center justify-center border border-white/10 shrink-0 overflow-hidden relative">
                                    @if(isset($settings['app_logo']) && $settings['app_logo'])
                                        <img src="{{ Storage::url($settings['app_logo']) }}" alt="Logo" class="w-full h-full object-contain p-2">
                                    @else
                                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-[8px] themed-text-muted absolute bottom-2">Default</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="app_logo" accept="image/*" class="w-full text-sm themed-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                    <p class="text-[10px] themed-text-muted mt-2">Biarkan kosong jika tidak ingin merubah logo. (Format: PNG/JPG/SVG transparan max 2MB).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-white/10">

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 rounded-xl btn-soft-primary font-bold uppercase tracking-widest text-xs">Simpan Konfigurasi</button>
            </div>
        </form>
    </div>
</div>
@endsection
