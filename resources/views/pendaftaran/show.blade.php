@extends('layouts.app')

@section('title', 'Detail Pendaftaran')
@section('page-title', 'Detail Pendaftaran')
@section('page-subtitle', 'Ringkasan biodata yang telah Anda kirimkan')

@section('content')
<div class="space-y-6">
    {{-- Status Card --}}
    <div class="card-glass rounded-2xl p-6 border-l-4 border-emerald-500 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="themed-text font-bold">Data Berhasil Disimpan</p>
                <p class="themed-text-muted text-xs">Anda dapat mengubah data ini kapan saja sebelum batas waktu pendaftaran berakhir.</p>
            </div>
        </div>
        <a href="{{ route('pendaftaran.edit') }}" 
           class="px-5 py-2 rounded-xl transition-all flex items-center gap-2 btn-soft-primary text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
            Edit Data
        </a>
    </div>

    {{-- Info Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Data Akun (NEW) --}}
        <div class="card-glass rounded-2xl p-6">
            <h3 class="text-xs font-bold text-sky-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Informasi Registrasi
            </h3>
            <div class="space-y-4">
                @php
                    $accFields = [
                        'Asal Sekolah' => auth()->user()->asal_sekolah,
                        'Tujuan Masuk' => auth()->user()->educationalLevel?->name,
                        'Sumber Info' => auth()->user()->sumber_informasi,
                    ];
                @endphp
                @foreach($accFields as $label => $val)
                <div class="flex flex-col border-b pb-2" :style="'border-color: var(--border-color)'">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider mb-1">{{ $label }}</span>
                    <span class="text-sm themed-text font-medium">{{ $val ?? '-' }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Data Siswa --}}
        <div class="card-glass rounded-2xl p-6">
            <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Biodata Pribadi
            </h3>
            <div class="space-y-4">
                @php
                    $fields = [
                        'Nama Panggilan' => $registration->nama_panggilan,
                        'Gender' => $registration->jenis_kelamin,
                        'Anak Ke' => $registration->anak_ke . ' dari ' . $registration->dari_saudara . ' bersaudara',
                        'TL' => $registration->tempat_lahir . ', ' . date('d F Y', strtotime($registration->tanggal_lahir)),
                        'Agama' => $registration->agama,
                        'Kebutuhan Khusus' => $registration->kebutuhan_khusus,
                    ];
                @endphp
                @foreach($fields as $label => $val)
                <div class="flex justify-between border-b pb-2" :style="'border-color: var(--border-color)'">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider">{{ $label }}</span>
                    <span class="text-sm themed-text font-medium text-right">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Alamat --}}
        <div class="card-glass rounded-2xl p-6">
            <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Alamat & Kontak
            </h3>
            <div class="space-y-4">
                <div class="flex flex-col gap-1 border-b pb-2" :style="'border-color: var(--border-color)'">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider">Alamat Lengkap</span>
                    <span class="text-sm themed-text leading-relaxed">{{ $registration->alamat }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <div class="flex flex-col">
                        <span class="text-[9px] themed-text-muted uppercase tracking-widest">Provinsi</span>
                        <span class="text-xs themed-text font-medium">{{ $registration->provinsi }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] themed-text-muted uppercase tracking-widest">Kabupaten</span>
                        <span class="text-xs themed-text font-medium">{{ $registration->kabupaten }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] themed-text-muted uppercase tracking-widest">Kecamatan</span>
                        <span class="text-xs themed-text font-medium">{{ $registration->kecamatan }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Ayah --}}
        <div class="card-glass rounded-2xl p-6">
            <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-6 border-b pb-4" :style="'border-color: var(--border-color)'">
                Data Ayah
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between border-b pb-2" :style="'border-color: var(--border-color)'">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider">Nama</span>
                    <span class="text-sm themed-text font-medium">{{ $registration->nama_ayah }}</span>
                </div>
                <div class="flex justify-between border-b pb-2" :style="'border-color: var(--border-color)'">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider">Pendidikan</span>
                    <span class="text-sm themed-text font-medium">{{ $registration->pendidikan_ayah }}</span>
                </div>
                <div class="flex justify-between border-b pb-2" :style="'border-color: var(--border-color)'">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider">Pekerjaan</span>
                    <span class="text-sm themed-text font-medium">{{ $registration->pekerjaan_ayah }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider">Penghasilan</span>
                    <span class="text-sm text-emerald-500 font-bold">Rp {{ number_format($registration->penghasilan_ayah, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Data Ibu --}}
        <div class="card-glass rounded-2xl p-6">
            <h3 class="text-xs font-bold text-rose-400 uppercase tracking-widest mb-6 border-b pb-4" :style="'border-color: var(--border-color)'">
                Data Ibu
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between border-b pb-2" :style="'border-color: var(--border-color)'">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider">Nama</span>
                    <span class="text-sm themed-text font-medium">{{ $registration->nama_ibu }}</span>
                </div>
                <div class="flex justify-between border-b pb-2" :style="'border-color: var(--border-color)'">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider">Pendidikan</span>
                    <span class="text-sm themed-text font-medium">{{ $registration->pendidikan_ibu }}</span>
                </div>
                <div class="flex justify-between border-b pb-2" :style="'border-color: var(--border-color)'">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider">Pekerjaan</span>
                    <span class="text-sm themed-text font-medium">{{ $registration->pekerjaan_ibu }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[10px] themed-text-muted uppercase tracking-wider">Penghasilan</span>
                    <span class="text-sm text-rose-500 font-bold">Rp {{ number_format($registration->penghasilan_ibu, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
