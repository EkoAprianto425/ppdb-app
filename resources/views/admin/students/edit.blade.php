@extends('layouts.app')

@section('title', 'Edit Biodata Siswa')
@section('page-title', 'Edit Biodata')
@section('page-subtitle', 'Mengubah data pendaftaran untuk ' . $registration->user->full_name)

@section('content')
<div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
    <div class="p-8 border-b" :style="'border-color: var(--border-color)'">
        <h2 class="text-xl font-bold themed-text">Data Calon Siswa</h2>
    </div>

    <form action="{{ route('admin.students.update', $registration) }}" method="POST" class="p-8 space-y-10">
        @csrf
        @method('PUT')

        {{-- Row 1: Identitas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <h3 class="text-primary text-xs font-bold uppercase tracking-widest border-l-2 border-primary pl-3">Data Pribadi</h3>
                
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Panggilan</label>
                    <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $registration->nama_panggilan) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Anak Ke</label>
                        <input type="number" name="anak_ke" value="{{ old('anak_ke', $registration->anak_ke) }}" required
                               class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Dari (Saudara)</label>
                        <input type="number" name="dari_saudara" value="{{ old('dari_saudara', $registration->dari_saudara) }}" required
                               class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full themed-input rounded-xl px-4 py-3 appearance-none">
                        <option value="Laki-laki" {{ $registration->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $registration->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-primary text-xs font-bold uppercase tracking-widest border-l-2 border-primary pl-3">Kelahiran & Agama</h3>
                
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $registration->tempat_lahir) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                </div>

                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Tanggal Lahir</label>
                    <input type="text" name="tanggal_lahir" value="{{ old('tanggal_lahir', $registration->tanggal_lahir) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all datepicker">
                </div>

                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Agama</label>
                    <select name="agama" required class="w-full themed-input rounded-xl px-4 py-3 appearance-none">
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                            <option value="{{ $agama }}" {{ (old('agama', $registration->agama) == $agama) ? 'selected' : '' }}>{{ $agama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="h-px transition-colors duration-500" :style="'background: var(--border-color)'"></div>

        {{-- Parent Data Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            {{-- Data Ayah --}}
            <div class="space-y-6">
                <h3 class="text-emerald-400 text-xs font-bold uppercase tracking-widest border-l-2 border-emerald-500 pl-3">Data Ayah</h3>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Ayah</label>
                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $registration->nama_ayah) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Pendidikan</label>
                        <select name="pendidikan_ayah" required class="w-full themed-input rounded-xl px-4 py-3 appearance-none">
                            @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'] as $edu)
                                <option value="{{ $edu }}" {{ $registration->pendidikan_ayah == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Pekerjaan</label>
                        <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $registration->pekerjaan_ayah) }}" required
                               class="w-full themed-input rounded-xl px-4 py-3 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Penghasilan Ayah</label>
                    <input type="text" name="penghasilan_ayah" value="{{ old('penghasilan_ayah', $registration->penghasilan_ayah) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 transition-all currency-input">
                </div>
            </div>

            {{-- Data Ibu --}}
            <div class="space-y-6">
                <h3 class="text-rose-400 text-xs font-bold uppercase tracking-widest border-l-2 border-rose-500 pl-3">Data Ibu</h3>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Ibu</label>
                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $registration->nama_ibu) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Pendidikan</label>
                        <select name="pendidikan_ibu" required class="w-full themed-input rounded-xl px-4 py-3 appearance-none">
                            @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'] as $edu)
                                <option value="{{ $edu }}" {{ $registration->pendidikan_ibu == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Pekerjaan</label>
                        <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $registration->pekerjaan_ibu) }}" required
                               class="w-full themed-input rounded-xl px-4 py-3 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Penghasilan Ibu</label>
                    <input type="text" name="penghasilan_ibu" value="{{ old('penghasilan_ibu', $registration->penghasilan_ibu) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 transition-all currency-input">
                </div>
            </div>
        </div>

        {{-- Alamat --}}
        <div class="space-y-6">
            <h3 class="text-purple-400 text-xs font-bold uppercase tracking-widest border-l-2 border-purple-500 pl-3">Lokasi & Alamat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Provinsi</label>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $registration->provinsi) }}" required class="w-full themed-input rounded-xl px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Kabupaten</label>
                    <input type="text" name="kabupaten" value="{{ old('kabupaten', $registration->kabupaten) }}" required class="w-full themed-input rounded-xl px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $registration->kecamatan) }}" required class="w-full themed-input rounded-xl px-4 py-3">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" required class="w-full themed-input rounded-xl px-4 py-3 resize-none">{{ old('alamat', $registration->alamat) }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Kebutuhan Khusus</label>
                <input type="text" name="kebutuhan_khusus" value="{{ old('kebutuhan_khusus', $registration->kebutuhan_khusus) }}" required class="w-full themed-input rounded-xl px-4 py-3">
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t" :style="'border-color: var(--border-color)'">
            <a href="{{ route('admin.students.show', $registration) }}" class="px-8 py-3 rounded-xl btn-soft-secondary text-sm font-semibold">Batal</a>
            <button type="submit" class="px-10 py-3 rounded-xl btn-soft-primary text-sm font-bold shadow-lg shadow-primary/5">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
    // Currency formatter
    document.querySelectorAll('.currency-input').forEach(input => {
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value) {
                this.value = new Intl.NumberFormat('id-ID').format(value);
            } else {
                this.value = '';
            }
        });
    });
</script>
@endsection
