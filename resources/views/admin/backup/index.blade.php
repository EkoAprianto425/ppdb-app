@extends('layouts.app')

@section('title', 'Backup & Restore Data')

@section('page-title', 'Backup & Restore')
@section('page-subtitle', 'Pencadangan dan pemulihan data sistem')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 flex items-center justify-center text-indigo-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold themed-text">Manajemen Pencadangan</h2>
            <p class="themed-text-muted text-sm">Cadangkan atau pulihkan data penting sistem PPDB</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 font-medium">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 font-medium">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Kolom Backup --}}
        <div class="space-y-6">
            <h3 class="text-lg font-bold themed-text mb-4">Pencadangan Data (Backup)</h3>

            {{-- Backup Database --}}
            <div class="card-glass p-6 rounded-3xl relative overflow-hidden group hover:border-indigo-500/30 transition-all">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold themed-text mb-2">Database SQL</h4>
                        <p class="text-sm themed-text-muted mb-6">Unduh seluruh data registrasi, pengaturan, dan akun pengguna dalam format .sql.</p>
                        <a href="{{ route('admin.backup.download-db') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-500 hover:bg-indigo-400 text-white text-sm font-bold shadow-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download SQL
                        </a>
                    </div>
                </div>
            </div>

            {{-- Backup Bukti Pembayaran --}}
            <div class="card-glass p-6 rounded-3xl relative overflow-hidden group hover:border-emerald-500/30 transition-all">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold themed-text mb-2">Bukti Pembayaran (ZIP)</h4>
                        <p class="text-sm themed-text-muted mb-6">Unduh semua file gambar bukti pembayaran siswa untuk Tahun Ajaran yang aktif saat ini.</p>
                        <a href="{{ route('admin.backup.download-proofs') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-bold shadow-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download ZIP
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Restore --}}
        <div class="space-y-6">
            <h3 class="text-lg font-bold themed-text mb-4">Pemulihan Data (Restore)</h3>

            {{-- Restore Database --}}
            <div class="card-glass p-6 rounded-3xl border border-rose-500/20 relative overflow-hidden group transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/20 flex items-center justify-center text-rose-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold themed-text mb-2">Restore Database</h4>
                        <p class="text-sm text-rose-500 mb-6 font-bold">Peringatan: Aksi ini akan menimpa seluruh data yang ada di database saat ini!</p>
                        
                        <form action="{{ route('admin.backup.restore-db') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                <input type="file" name="sql_file" accept=".sql" required class="block w-full text-sm themed-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-rose-500/20 file:text-rose-500 hover:file:bg-rose-500/30 cursor-pointer">
                                <button type="submit" class="shrink-0 px-5 py-2.5 rounded-xl bg-rose-500 hover:bg-rose-600 text-white text-sm font-bold shadow-lg transition-all">
                                    Restore DB
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Restore Bukti Pembayaran --}}
            <div class="card-glass p-6 rounded-3xl relative overflow-hidden group transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold themed-text mb-2">Restore Bukti Pembayaran</h4>
                        <p class="text-sm themed-text-muted mb-6">Unggah file ZIP berisi gambar bukti pembayaran untuk memulihkannya.</p>
                        
                        <form action="{{ route('admin.backup.restore-proofs') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                <input type="file" name="zip_file" accept=".zip" required class="block w-full text-sm themed-text-muted file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-500/20 file:text-amber-500 hover:file:bg-amber-500/30 cursor-pointer">
                                <button type="submit" class="shrink-0 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-lg transition-all">
                                    Restore ZIP
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
