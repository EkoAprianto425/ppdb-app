@extends('layouts.app')

@section('title', isset($registration) ? 'Edit Formulir Pendaftaran' : 'Formulir Pendaftaran')
@section('page-title', 'Formulir Pendaftaran')
@section('page-subtitle', 'Lengkapi biodata Anda untuk melanjutkan pendaftaran')

@section('content')
<div class="space-y-6">
    <div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
        <div class="p-8 border-b transition-colors duration-500" :style="'border-color: var(--border-color)'">
            <h2 class="text-xl font-bold themed-text mb-2">Biodata Calon Siswa Baru</h2>
            <p class="themed-text-muted text-sm opacity-80">Pastikan semua data yang Anda masukkan sesuai dengan dokumen resmi (KK/Akta Kelahiran).</p>
        </div>

        <form action="{{ isset($registration) ? route('pendaftaran.update') : route('pendaftaran.store') }}" method="POST" class="p-8 space-y-10">
            @csrf
            @if(isset($registration))
                @method('PUT')
            @endif

            {{-- Row 1: Identitas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h3 class="text-primary text-xs font-bold uppercase tracking-widest border-l-2 border-primary pl-3 transition-colors duration-500">Data Pribadi</h3>
                    
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $registration->nama_panggilan ?? '') }}" required
                               class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                        @error('nama_panggilan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Anak Ke</label>
                            <input type="number" name="anak_ke" value="{{ old('anak_ke', $registration->anak_ke ?? '') }}" required
                                   class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Dari (Saudara)</label>
                            <input type="number" name="dari_saudara" value="{{ old('dari_saudara', $registration->dari_saudara ?? '') }}" required
                                   class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">
                            JENIS KELAMIN
                        </label>

                        <div class="flex gap-4">
                            @foreach(['Laki-laki', 'Perempuan'] as $jk)
                                <label class="flex-1 cursor-pointer">
                                    <input
                                        type="radio"
                                        name="jenis_kelamin"
                                        value="{{ $jk }}"
                                        class="sr-only peer"
                                        {{ old('jenis_kelamin', $registration->jenis_kelamin ?? '') == $jk ? 'checked' : '' }}
                                        required
                                    >

                                    <div
                                        class="
                                            relative
                                            w-full
                                            py-3
                                            px-4
                                            rounded-xl
                                            border-2
                                            text-center
                                            text-sm
                                            font-medium

                                            bg-[var(--card-bg)]
                                            border-[var(--border-color)]
                                            text-[var(--text-color)]

                                            transition-all
                                            duration-200
                                            cursor-pointer

                                            hover:border-primary
                                            hover:-translate-y-0.5

                                            peer-checked:bg-primary
                                            peer-checked:border-primary
                                            peer-checked:text-white
                                            peer-checked:font-semibold
                                            peer-checked:shadow-lg
                                            peer-checked:shadow-primary/30
                                        "
                                    >
                                        {{ $jk }}

                                        <span
                                            class="
                                                absolute
                                                right-3
                                                top-1/2
                                                -translate-y-1/2

                                                flex
                                                items-center
                                                justify-center

                                                w-5
                                                h-5
                                                rounded-full

                                                bg-white
                                                text-primary
                                                text-xs
                                                font-bold

                                                opacity-0
                                                scale-50

                                                transition-all
                                                duration-200

                                                peer-checked:opacity-100
                                                peer-checked:scale-100
                                            "
                                        >
                                            ✓
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-primary text-xs font-bold uppercase tracking-widest border-l-2 border-primary pl-3 transition-colors duration-500">Kelahiran & Agama</h3>
                    
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $registration->tempat_lahir ?? '') }}" required
                               class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Tanggal Lahir</label>
                        <input type="text" name="tanggal_lahir" value="{{ old('tanggal_lahir', $registration->tanggal_lahir ?? '') }}" required readOnly
                               class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all datepicker" placeholder="Pilih Tanggal Lahir">
                    </div>

                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Agama</label>
                        <select name="agama" required class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none">
                            <option value="">-- Pilih Agama --</option>
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                                <option value="{{ $agama }}" {{ old('agama', $registration->agama ?? '') == $agama ? 'selected' : '' }} class="text-slate-900">{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="h-px transition-colors duration-500" :style="'background: var(--border-color)'"></div>

            {{-- Row 2: Alamat --}}
            <div class="space-y-6">
                <h3 class="text-purple-400 text-xs font-bold uppercase tracking-widest border-l-2 border-purple-500 pl-3">Lokasi & Alamat</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Provinsi</label>
                        <select name="provinsi" id="provinsi" required class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none">
                            <option value="">-- Memuat Provinsi... --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Kabupaten</label>
                        <select name="kabupaten" id="kabupaten" required class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none disabled:opacity-50">
                            <option value="">Pilih Provinsi Dulu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Kecamatan</label>
                        <select name="kecamatan" id="kecamatan" required class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none disabled:opacity-50">
                            <option value="">Pilih Kabupaten Dulu</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" required placeholder="Jl. Nama Jalan No. Rumah, RT/RW..."
                              class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all resize-none">{{ old('alamat', $registration->alamat ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Kebutuhan Khusus</label>
                    <select name="kebutuhan_khusus" required class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none">
                        @foreach(['Tidak Ada', 'Tuna Rungu', 'Tuna Wicara', 'Lainnya'] as $kh)
                            <option value="{{ $kh }}" {{ old('kebutuhan_khusus', $registration->kebutuhan_khusus ?? '') == $kh ? 'selected' : '' }} class="text-slate-900">{{ $kh }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="h-px transition-colors duration-500" :style="'background: var(--border-color)'"></div>

            {{-- Row 3: Data Orang Tua --}}
            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    {{-- Data Ayah --}}
                    <div class="space-y-6">
                        <h3 class="text-emerald-400 text-xs font-bold uppercase tracking-widest border-l-2 border-emerald-500 pl-3 transition-colors duration-500">Data Ayah</h3>
                        
                        <div>
                            <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Lengkap Ayah</label>
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $registration->nama_ayah ?? '') }}" required
                                   class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Pendidikan</label>
                                <select name="pendidikan_ayah" required class="w-full themed-input rounded-xl px-4 py-3 appearance-none">
                                    @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'] as $edu)
                                        <option value="{{ $edu }}" {{ old('pendidikan_ayah', $registration->pendidikan_ayah ?? '') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Pekerjaan</label>
                                <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $registration->pekerjaan_ayah ?? '') }}" required
                                       class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Penghasilan Per Bulan</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 themed-text-muted text-sm transition-colors duration-500">Rp</span>
                                <input type="text" name="penghasilan_ayah" value="{{ old('penghasilan_ayah', $registration->penghasilan_ayah ?? '') }}" required
                                       class="w-full themed-input rounded-xl pl-12 pr-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all currency-input">
                            </div>
                        </div>
                    </div>

                    {{-- Data Ibu --}}
                    <div class="space-y-6">
                        <h3 class="text-rose-400 text-xs font-bold uppercase tracking-widest border-l-2 border-rose-500 pl-3 transition-colors duration-500">Data Ibu</h3>
                        
                        <div>
                            <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Lengkap Ibu</label>
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $registration->nama_ibu ?? '') }}" required
                                   class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Pendidikan</label>
                                <select name="pendidikan_ibu" required class="w-full themed-input rounded-xl px-4 py-3 appearance-none">
                                    @foreach(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'] as $edu)
                                        <option value="{{ $edu }}" {{ old('pendidikan_ibu', $registration->pendidikan_ibu ?? '') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Pekerjaan</label>
                                <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $registration->pekerjaan_ibu ?? '') }}" required
                                       class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Penghasilan Per Bulan</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 themed-text-muted text-sm transition-colors duration-500">Rp</span>
                                <input type="text" name="penghasilan_ibu" value="{{ old('penghasilan_ibu', $registration->penghasilan_ibu ?? '') }}" required
                                       class="w-full themed-input rounded-xl pl-12 pr-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all currency-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-8 border-t" :style="'border-color: var(--border-color)'">
                <a href="{{ route('dashboard') }}" class="px-8 py-3 rounded-xl btn-soft-secondary text-sm font-semibold">Batal</a>
                <button type="submit" class="px-10 py-3 rounded-xl btn-soft-primary text-sm font-bold shadow-lg shadow-primary/5">
                    {{ isset($registration) ? 'Simpan Perubahan' : 'Submit Formulir' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Currency Multi-digit formatter logic
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

    // Cascading Dropdown Region
    document.addEventListener('DOMContentLoaded', function () {
        const provinsiSelect = document.getElementById('provinsi');
        const kabupatenSelect = document.getElementById('kabupaten');
        const kecamatanSelect = document.getElementById('kecamatan');
        
        const baseUrl = "{{ url('') }}";
        
        const oldProvinsi = "{{ old('provinsi', $registration->provinsi ?? '') }}";
        const oldKabupaten = "{{ old('kabupaten', $registration->kabupaten ?? '') }}";
        const oldKecamatan = "{{ old('kecamatan', $registration->kecamatan ?? '') }}";

        let isFirstLoadProvinsi = true;
        let isFirstLoadKabupaten = true;

        // Fetch Provinsi
        fetch(baseUrl + '/api/region/provinsi')
            .then(res => res.json())
            .then(data => {
                provinsiSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
                data.forEach(item => {
                    const selected = item === oldProvinsi ? 'selected' : '';
                    provinsiSelect.innerHTML += `<option value="${item}" ${selected} class="text-slate-900">${item}</option>`;
                });
                
                if (oldProvinsi) {
                    provinsiSelect.dispatchEvent(new Event('change'));
                }
            })
            .catch(err => console.error(err));

        // Event: Provinsi Changed
        provinsiSelect.addEventListener('change', function () {
            const val = this.value;
            kabupatenSelect.innerHTML = '<option value="">-- Memuat... --</option>';
            kabupatenSelect.disabled = true;
            kecamatanSelect.innerHTML = '<option value="">Pilih Kabupaten Dulu</option>';
            kecamatanSelect.disabled = true;

            if (val) {
                fetch(baseUrl + `/api/region/kabupaten?propinsi=${encodeURIComponent(val)}`)
                    .then(res => res.json())
                    .then(data => {
                        kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
                        data.forEach(item => {
                            // Cek jika sedang load awal dan ada nilai old
                            const selected = (isFirstLoadProvinsi && item === oldKabupaten && val === oldProvinsi) ? 'selected' : '';
                            kabupatenSelect.innerHTML += `<option value="${item}" ${selected} class="text-slate-900">${item}</option>`;
                        });
                        kabupatenSelect.disabled = false;
                        
                        if (isFirstLoadProvinsi && oldKabupaten && val === oldProvinsi) {
                            kabupatenSelect.dispatchEvent(new Event('change'));
                        }
                        isFirstLoadProvinsi = false;
                    })
                    .catch(err => console.error(err));
            } else {
                kabupatenSelect.innerHTML = '<option value="">Pilih Provinsi Dulu</option>';
            }
        });

        // Event: Kabupaten Changed
        kabupatenSelect.addEventListener('change', function () {
            const val = this.value;
            kecamatanSelect.innerHTML = '<option value="">-- Memuat... --</option>';
            kecamatanSelect.disabled = true;

            if (val) {
                fetch(baseUrl + `/api/region/kecamatan?kabupaten=${encodeURIComponent(val)}`)
                    .then(res => res.json())
                    .then(data => {
                        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                        data.forEach(item => {
                            const selected = (isFirstLoadKabupaten && item === oldKecamatan && val === oldKabupaten) ? 'selected' : '';
                            kecamatanSelect.innerHTML += `<option value="${item}" ${selected} class="text-slate-900">${item}</option>`;
                        });
                        kecamatanSelect.disabled = false;
                        isFirstLoadKabupaten = false;
                    })
                    .catch(err => console.error(err));
            } else {
                kecamatanSelect.innerHTML = '<option value="">Pilih Kabupaten Dulu</option>';
            }
        });

        if (!oldProvinsi) {
            kabupatenSelect.disabled = true;
            kecamatanSelect.disabled = true;
        }
    });
</script>
@endsection
