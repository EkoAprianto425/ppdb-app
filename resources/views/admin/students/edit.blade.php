@extends('layouts.app')

@section('title', 'Edit Biodata Siswa')
@section('page-title', 'Edit Biodata')
@section('page-subtitle', 'Mengubah data pendaftaran untuk ' . $registration->user->full_name)

@section('content')
<div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
    <div class="p-8 border-b" :style="'border-color: var(--border-color)'">
        <h2 class="text-xl font-bold themed-text">Data Calon Siswa</h2>
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

    <form action="{{ route('admin.students.update', $registration) }}" method="POST" class="p-8 space-y-10">
        @csrf
        @method('PUT')

        {{-- Registrasi Awal --}}
        <div class="space-y-6">
            <h3 class="text-sky-400 text-xs font-bold uppercase tracking-widest border-l-2 border-sky-500 pl-3">Registrasi Awal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $registration->user->full_name) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                    @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">No. WhatsApp</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $registration->user->whatsapp_number) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                    @error('whatsapp_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Asal Sekolah --}}
                <div class="md:col-span-2 space-y-4">
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" id="asal_sekolah" value="{{ old('asal_sekolah', $registration->user->asal_sekolah) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all"
                           placeholder="Nama asal sekolah siswa">
                    @error('asal_sekolah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Alasan Memilih --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Alasan Memilih Sekolah Ini</label>
                    <select id="alasan_memilih_select"
                            class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none">
                        <option value="" disabled>-- Pilih alasan memilih sekolah --</option>
                        @foreach($reasons as $reason)
                            <option value="{{ $reason->name }}"
                                {{ old('alasan_memilih', $registration->user->alasan_memilih) === $reason->name ? 'selected' : '' }}>
                                {{ $reason->name }}
                            </option>
                        @endforeach
                        <option value="lainnya"
                            {{ old('alasan_memilih', $registration->user->alasan_memilih) && !collect($reasons)->contains('name', old('alasan_memilih', $registration->user->alasan_memilih)) ? 'selected' : '' }}>
                            ++ LAINNYA (Ketik Manual) ++
                        </option>
                    </select>
                    @php
                        $currentAlasan = old('alasan_memilih', $registration->user->alasan_memilih);
                        $showManualAlasan = $currentAlasan && !collect($reasons)->contains('name', $currentAlasan);
                    @endphp
                    <div id="manual_alasan_container" class="{{ $showManualAlasan ? 'block' : 'hidden' }} mt-3">
                        <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Ketik Alasan Memilih</label>
                        <textarea id="alasan_memilih" name="alasan_memilih" rows="3"
                                  class="w-full themed-input rounded-xl px-4 py-3 resize-none">{{ $showManualAlasan ? $currentAlasan : '' }}</textarea>
                    </div>
                    {{-- Hidden alasan_memilih untuk saat dipilih dari dropdown --}}
                    <input type="hidden" id="alasan_memilih_hidden" name="alasan_memilih"
                           value="{{ !$showManualAlasan ? $currentAlasan : '' }}">
                    @error('alasan_memilih') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Sumber Informasi --}}
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Sumber Informasi</label>
                    <select id="sumber_informasi_select" name="sumber_informasi"
                            class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none">
                        <option value="" disabled {{ !old('sumber_informasi', $registration->user->sumber_informasi) ? 'selected' : '' }}>-- Pilih sumber informasi --</option>
                        @foreach($sources as $source)
                            <option value="{{ $source->name }}"
                                    data-requires-manual="{{ $source->requires_manual_input ? 'true' : 'false' }}"
                                {{ old('sumber_informasi', $registration->user->sumber_informasi) === $source->name ? 'selected' : '' }}>
                                {{ $source->name }}
                            </option>
                        @endforeach
                        <option value="lainnya" {{ old('sumber_informasi', $registration->user->sumber_informasi) == 'lainnya' ? 'selected' : '' }}>
                            ++ LAINNYA (Ketik Manual) ++
                        </option>
                    </select>
                    @error('sumber_informasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    @php
                        $currentSumber = old('sumber_informasi', $registration->user->sumber_informasi);
                        $showManualSumber = $currentSumber == 'lainnya' ||
                            (collect($sources)->firstWhere('name', $currentSumber)?->requires_manual_input ?? false);
                    @endphp
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Sumber Informasi (Tambahan)</label>
                    <input type="text" id="sumber_informasi_tambahan" name="sumber_informasi_tambahan"
                           value="{{ old('sumber_informasi_tambahan', $registration->user->sumber_informasi_tambahan) }}"
                           placeholder="{{ $showManualSumber ? 'Contoh: Nama teman, nama akun IG, dll' : 'Isi jika sumber informasi memerlukan keterangan' }}"
                           {{ !$showManualSumber ? 'disabled' : '' }}
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all {{ !$showManualSumber ? 'opacity-50 cursor-not-allowed' : '' }}">
                    @error('sumber_informasi_tambahan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="h-px transition-colors duration-500" :style="'background: var(--border-color)'"></div>

        {{-- Row 1: Identitas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <h3 class="text-primary text-xs font-bold uppercase tracking-widest border-l-2 border-primary pl-3">Data Pribadi</h3>
                
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Panggilan</label>
                    <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $registration->nama_panggilan) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                    @error('nama_panggilan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
                    <div class="flex gap-4">
                        @foreach(['Laki-laki', 'Perempuan'] as $jk)
                            <label class="flex-1 cursor-pointer">
                                <input
                                    type="radio"
                                    name="jenis_kelamin"
                                    value="{{ $jk }}"
                                    class="sr-only peer"
                                    {{ old('jenis_kelamin', $registration->jenis_kelamin) == $jk ? 'checked' : '' }}
                                    required
                                >
                                <div class="
                                    relative w-full py-3 px-4 rounded-xl border-2 text-center text-sm font-medium
                                    bg-[var(--card-bg)] border-[var(--border-color)] text-[var(--text-color)]
                                    transition-all duration-200 cursor-pointer
                                    hover:border-primary hover:-translate-y-0.5
                                    peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white
                                    peer-checked:font-semibold peer-checked:shadow-lg peer-checked:shadow-primary/30
                                ">
                                    {{ $jk }}
                                    <span class="
                                        absolute right-3 top-1/2 -translate-y-1/2
                                        flex items-center justify-center w-5 h-5 rounded-full
                                        bg-white text-primary text-xs font-bold
                                        opacity-0 scale-50 transition-all duration-200
                                        peer-checked:opacity-100 peer-checked:scale-100
                                    ">✓</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
                    <input type="text" name="tanggal_lahir" value="{{ old('tanggal_lahir', $registration->tanggal_lahir) }}" required readonly
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all datepicker" placeholder="Pilih Tanggal Lahir">
                </div>

                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Agama</label>
                    <select name="agama" required class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none">
                        <option value="">-- Pilih Agama --</option>
                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                            <option value="{{ $agama }}" {{ old('agama', $registration->agama) == $agama ? 'selected' : '' }} class="text-slate-900">{{ $agama }}</option>
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
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Lengkap Ayah</label>
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
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Penghasilan Per Bulan</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 themed-text-muted text-sm transition-colors duration-500">Rp</span>
                        <input type="text" name="penghasilan_ayah" value="{{ old('penghasilan_ayah', $registration->penghasilan_ayah ? number_format($registration->penghasilan_ayah, 0, ',', '.') : '') }}" required
                               class="w-full themed-input rounded-xl pl-12 pr-4 py-3 transition-all currency-input">
                    </div>
                </div>
            </div>

            {{-- Data Ibu --}}
            <div class="space-y-6">
                <h3 class="text-rose-400 text-xs font-bold uppercase tracking-widest border-l-2 border-rose-500 pl-3">Data Ibu</h3>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Nama Lengkap Ibu</label>
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
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Penghasilan Per Bulan</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 themed-text-muted text-sm transition-colors duration-500">Rp</span>
                        <input type="text" name="penghasilan_ibu" value="{{ old('penghasilan_ibu', $registration->penghasilan_ibu ? number_format($registration->penghasilan_ibu, 0, ',', '.') : '') }}" required
                               class="w-full themed-input rounded-xl pl-12 pr-4 py-3 transition-all currency-input">
                    </div>
                </div>
            </div>
        </div>

        {{-- Alamat --}}
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
                          class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all resize-none">{{ old('alamat', $registration->alamat) }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Kebutuhan Khusus</label>
                <select name="kebutuhan_khusus" required class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none">
                    @foreach(['Tidak Ada', 'Tuna Rungu', 'Tuna Wicara', 'Lainnya'] as $kh)
                        <option value="{{ $kh }}" {{ old('kebutuhan_khusus', $registration->kebutuhan_khusus) == $kh ? 'selected' : '' }} class="text-slate-900">{{ $kh }}</option>
                    @endforeach
                </select>
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

    // Alasan Memilih: toggle manual textarea
    (function() {
        const alasanSelect = document.getElementById('alasan_memilih_select');
        const alasanTextarea = document.getElementById('alasan_memilih');
        const alasanHidden = document.getElementById('alasan_memilih_hidden');
        const manualAlasanContainer = document.getElementById('manual_alasan_container');

        function syncAlasan() {
            const val = alasanSelect.value;
            if (val === 'lainnya') {
                manualAlasanContainer.classList.remove('hidden');
                alasanHidden.disabled = true;
                alasanHidden.name = '';
                alasanTextarea.name = 'alasan_memilih';
                alasanTextarea.focus();
            } else if (val) {
                manualAlasanContainer.classList.add('hidden');
                alasanTextarea.name = '';
                alasanHidden.name = 'alasan_memilih';
                alasanHidden.value = val;
            } else {
                manualAlasanContainer.classList.add('hidden');
                alasanHidden.name = 'alasan_memilih';
                alasanHidden.value = '';
            }
        }

        alasanSelect.addEventListener('change', syncAlasan);

        // Init on load
        const currentVal = alasanSelect.value;
        if (currentVal && currentVal !== 'lainnya') {
            alasanTextarea.name = '';
            alasanHidden.name = 'alasan_memilih';
            alasanHidden.value = currentVal;
        } else if (currentVal === 'lainnya') {
            alasanHidden.name = '';
            alasanTextarea.name = 'alasan_memilih';
        }
    })();

    // Sumber Informasi: toggle tambahan input
    (function() {
        const sumberSelect = document.getElementById('sumber_informasi_select');
        const sumberTambahan = document.getElementById('sumber_informasi_tambahan');

        sumberSelect.addEventListener('change', function() {
            const val = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const requiresManual = selectedOption?.getAttribute('data-requires-manual') === 'true' || val === 'lainnya';

            if (requiresManual) {
                sumberTambahan.disabled = false;
                sumberTambahan.classList.remove('opacity-50', 'cursor-not-allowed');
                sumberTambahan.placeholder = 'Contoh: Nama teman, nama akun IG, dll';
                sumberTambahan.focus();
            } else {
                sumberTambahan.disabled = true;
                sumberTambahan.classList.add('opacity-50', 'cursor-not-allowed');
                sumberTambahan.placeholder = 'Isi jika sumber informasi memerlukan keterangan';
                sumberTambahan.value = '';
            }
        });
    })();

    // Cascading Dropdown Region
    document.addEventListener('DOMContentLoaded', function () {
        const provinsiSelect = document.getElementById('provinsi');
        const kabupatenSelect = document.getElementById('kabupaten');
        const kecamatanSelect = document.getElementById('kecamatan');
        
        const baseUrl = "{{ url('') }}";
        
        // Nilai yang tersimpan di DB (bisa nama atau kode)
        const oldProvinsi  = "{{ old('provinsi', $registration->provinsi ?? '') }}";
        const oldKabupaten = "{{ old('kabupaten', $registration->kabupaten ?? '') }}";
        const oldKecamatan = "{{ old('kecamatan', $registration->kecamatan ?? '') }}";

        // Cocokkan berdasarkan kode ATAU nama
        function matchProvinsi(item) {
            return item.kode_prop === oldProvinsi || item.propinsi === oldProvinsi;
        }
        function matchKabupaten(item) {
            return item.kode_kab_kota === oldKabupaten || item.kabupaten_kota === oldKabupaten;
        }
        function matchKecamatan(item) {
            return item.kode_kec === oldKecamatan || item.kecamatan === oldKecamatan;
        }

        let isFirstLoadProvinsi  = true;
        let isFirstLoadKabupaten = true;

        // Fetch Provinsi
        fetch(baseUrl + '/api/region/provinsi')
            .then(res => res.json())
            .then(data => {
                provinsiSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
                let selectedKode = '';
                data.forEach(item => {
                    const isSelected = matchProvinsi(item);
                    if (isSelected) selectedKode = item.kode_prop;
                    provinsiSelect.innerHTML += `<option value="${item.kode_prop}" ${isSelected ? 'selected' : ''} class="text-slate-900">${item.propinsi}</option>`;
                });
                if (oldProvinsi) {
                    provinsiSelect.dispatchEvent(new Event('change'));
                }
            })
            .catch(err => console.error(err));

        // Event: Provinsi Changed
        provinsiSelect.addEventListener('change', function () {
            const val = this.value; // selalu kode_prop setelah user pilih
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
                            const isSelected = isFirstLoadProvinsi && matchKabupaten(item);
                            kabupatenSelect.innerHTML += `<option value="${item.kode_kab_kota}" ${isSelected ? 'selected' : ''} class="text-slate-900">${item.kabupaten_kota}</option>`;
                        });
                        kabupatenSelect.disabled = false;
                        if (isFirstLoadProvinsi && oldKabupaten) {
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
                            const isSelected = isFirstLoadKabupaten && matchKecamatan(item);
                            kecamatanSelect.innerHTML += `<option value="${item.kode_kec}" ${isSelected ? 'selected' : ''} class="text-slate-900">${item.kecamatan}</option>`;
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
