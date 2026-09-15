@extends('layouts.app')

@section('title', 'Edit Data Siswa - ' . ($user->full_name ?? $user->name))
@section('page-title', 'Edit Data Siswa')
@section('page-subtitle', 'Mengubah data akun siswa yang belum mengisi formulir pendaftaran')

@section('content')
<div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
    <div class="p-8 border-b" :style="'border-color: var(--border-color)'">
        <h2 class="text-xl font-bold themed-text">Data Calon Siswa</h2>
        <p class="text-xs themed-text-muted mt-1">Siswa ini belum mengisi formulir pendaftaran — hanya data registrasi awal yang dapat diedit.</p>
    </div>

    @if(session('status'))
    <div class="mx-8 mt-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-medium">
        {{ session('status') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mx-8 mt-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.students.update-guest', $user) }}" method="POST" class="p-8 space-y-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <h3 class="text-sky-400 text-xs font-bold uppercase tracking-widest border-l-2 border-sky-500 pl-3">Registrasi Awal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $user->asal_sekolah) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">No. WhatsApp</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Sumber Informasi</label>
                    <input type="text" name="sumber_informasi" value="{{ old('sumber_informasi', $user->sumber_informasi) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Sumber Informasi (Tambahan)</label>
                    <input type="text" name="sumber_informasi_tambahan" value="{{ old('sumber_informasi_tambahan', $user->sumber_informasi_tambahan) }}"
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Alasan Memilih</label>
                    <textarea name="alasan_memilih" rows="4" required
                              class="w-full themed-input rounded-xl px-4 py-3 resize-none">{{ old('alasan_memilih', $user->alasan_memilih) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex gap-4 pt-2">
            <a href="{{ route('admin.students.show-by-user', $user) }}"
               class="flex-1 py-3 rounded-xl btn-soft-secondary font-bold text-xs uppercase tracking-widest text-center">
                Batal
            </a>
            <button type="submit"
                    class="flex-1 py-3 rounded-xl bg-primary text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-primary/30 hover:opacity-90 transition-opacity">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
