@extends('layouts.guest')

@section('title', 'Daftar Akun Siswa')

@section('content')
<div class="min-h-screen bg-slate-950 flex">

    {{-- Main Panel - Form Centered --}}
    <div class="w-full flex items-center justify-center py-12 px-6 sm:px-10 overflow-y-auto">
        <div class="w-full max-w-lg">
            {{-- Logo --}}
            <div class="flex items-center gap-3 mb-8">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center overflow-hidden">
                    @if(\App\Models\Setting::get('app_logo'))
                        <img src="{{ Storage::url(\App\Models\Setting::get('app_logo')) }}" alt="Logo" class="w-full h-full object-cover">
                    @else
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    @endif
                </div>
                <p class="font-bold text-white">{{ \App\Models\Setting::get('app_name', 'PPDB Online') }}</p>
            </div>

            <h1 class="text-2xl font-bold text-white mb-1">Buat Akun Pendaftaran</h1>
            <p class="text-slate-400 text-sm mb-8">Isi formulir di bawah untuk mendaftar sebagai calon siswa baru</p>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30">
                    <p class="text-red-400 text-sm font-medium mb-2">Terdapat kesalahan:</p>
                    <ul class="text-red-400 text-sm space-y-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Section: Info Akun --}}
                <div class="bg-white/3 border border-white/8 rounded-2xl p-5 space-y-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-indigo-400 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold text-xs">1</span>
                        Informasi Akun
                    </p>

                    {{-- Nama Pembuat Akun --}}
                    <div>
                        <label for="name" class="block text-xs font-medium text-slate-400 mb-1.5">Nama Pembuat Akun <span class="text-red-400">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                               placeholder="Nama Anda (bisa orang tua/wali)"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border {{ $errors->has('name') ? 'border-red-500/50' : 'border-slate-700' }} text-white text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all">
                        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-medium text-slate-400 mb-1.5">Alamat Email <span class="text-red-400">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               placeholder="email@contoh.com"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border {{ $errors->has('email') ? 'border-red-500/50' : 'border-slate-700' }} text-white text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all">
                        @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-medium text-slate-400 mb-1.5">Password <span class="text-red-400">*</span></label>
                            <input id="password" type="password" name="password"
                                   placeholder="Min. 8 karakter"
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border {{ $errors->has('password') ? 'border-red-500/50' : 'border-slate-700' }} text-white text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all">
                            @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-medium text-slate-400 mb-1.5">Ulangi Password <span class="text-red-400">*</span></label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   placeholder="Ulangi password"
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Section: Data Siswa --}}
                <div class="bg-white/3 border border-white/8 rounded-2xl p-5 space-y-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-purple-400 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-purple-500/20 flex items-center justify-center text-purple-400 font-bold text-xs">2</span>
                        Data Diri Siswa
                    </p>

                    {{-- Nama Lengkap Siswa --}}
                    <div>
                        <label for="full_name" class="block text-xs font-medium text-slate-400 mb-1.5">Nama Lengkap Siswa <span class="text-red-400">*</span></label>
                        <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}"
                               placeholder="Nama lengkap sesuai dokumen"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border {{ $errors->has('full_name') ? 'border-red-500/50' : 'border-slate-700' }} text-white text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all">
                        @error('full_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- WhatsApp --}}
                    <div>
                        <label for="whatsapp_number" class="block text-xs font-medium text-slate-400 mb-1.5">Nomor WhatsApp <span class="text-red-400">*</span></label>
                        <input id="whatsapp_number" type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border {{ $errors->has('whatsapp_number') ? 'border-red-500/50' : 'border-slate-700' }} text-white text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all">
                        @error('whatsapp_number') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Data Asal Sekolah (Cascading Dropdown) --}}
                    <div class="space-y-4 pt-4 mt-4 border-t border-slate-700/50">
                        <p class="text-sm font-medium text-white">Data Asal Sekolah</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="provinsi" class="block text-xs font-medium text-slate-400 mb-1.5">Provinsi <span class="text-red-400">*</span></label>
                                <select id="provinsi" class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all appearance-none">
                                    <option value="">-- Memuat Provinsi... --</option>
                                </select>
                            </div>
                            <div>
                                <label for="kabupaten" class="block text-xs font-medium text-slate-400 mb-1.5">Kabupaten/Kota <span class="text-red-400">*</span></label>
                                <select id="kabupaten" disabled class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all appearance-none disabled:opacity-50">
                                    <option value="">Pilih Provinsi Dulu</option>
                                </select>
                            </div>
                            <div>
                                <label for="kecamatan" class="block text-xs font-medium text-slate-400 mb-1.5">Kecamatan <span class="text-red-400">*</span></label>
                                <select id="kecamatan" disabled class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all appearance-none disabled:opacity-50">
                                    <option value="">Pilih Kabupaten Dulu</option>
                                </select>
                            </div>
                            <div>
                                <label for="sekolah_select" class="block text-xs font-medium text-slate-400 mb-1.5">Sekolah <span class="text-red-400">*</span></label>
                                <select id="sekolah_select" disabled class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all appearance-none disabled:opacity-50">
                                    <option value="">Pilih Kecamatan Dulu</option>
                                </select>
                            </div>
                        </div>

                        {{-- Input Manual / Penampung Asal Sekolah --}}
                        <div id="manual_sekolah_container" class="{{ old('asal_sekolah') ? 'block' : 'hidden' }}">
                            <label for="asal_sekolah" class="block text-xs font-medium text-slate-400 mb-1.5">Ketik Nama Asal Sekolah <span class="text-red-400">*</span></label>
                            <input id="asal_sekolah" type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                                   placeholder="Contoh: SMPN 1 Jakarta"
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border {{ $errors->has('asal_sekolah') ? 'border-red-500/50' : 'border-slate-700' }} text-white text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all">
                            <p class="text-[10px] text-slate-500 mt-1">Isi manual jika sekolah tidak ada dalam daftar atau pilih dari dropdown di atas.</p>
                        </div>
                        @error('asal_sekolah') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tujuan Masuk --}}
                    <div>
                        <label for="educational_level_id" class="block text-xs font-medium text-slate-400 mb-1.5">Pilihan Tujuan Masuk <span class="text-red-400">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @foreach($levels as $level)
                            <label class="tujuan-option cursor-pointer">
                                <input type="radio" name="educational_level_id" value="{{ $level->id }}" class="hidden peer" {{ old('educational_level_id') == $level->id ? 'checked' : '' }}>
                                <div class="peer-checked:bg-indigo-500/20 peer-checked:border-indigo-400 peer-checked:text-indigo-300 border border-slate-700 rounded-xl px-4 py-3 text-center text-sm text-slate-400 hover:border-slate-600 hover:bg-slate-800 transition-all flex items-center justify-between">
                                    <p class="font-bold">{{ $level->name }}</p>
                                    <span class="text-[9px] themed-text-muted opacity-50">{{ $level->parent_unit }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('educational_level_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Section: Informasi Tambahan --}}
                <div class="bg-white/3 border border-white/8 rounded-2xl p-5 space-y-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-400 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold text-xs">3</span>
                        Informasi Tambahan
                    </p>

                    {{-- Alasan Memilih --}}
                    <div>
                        <label for="alasan_memilih" class="block text-xs font-medium text-slate-400 mb-1.5">Alasan Memilih Sekolah Ini <span class="text-red-400">*</span></label>
                        <textarea id="alasan_memilih" name="alasan_memilih" rows="3"
                               placeholder="Tuliskan alasan Anda memilih sekolah ini... (min. 20 karakter)"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border {{ $errors->has('alasan_memilih') ? 'border-red-500/50' : 'border-slate-700' }} text-white text-sm placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all resize-none">{{ old('alasan_memilih') }}</textarea>
                        @error('alasan_memilih') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Sumber Informasi --}}
                    <div>
                        <label for="sumber_informasi" class="block text-xs font-medium text-slate-400 mb-1.5">Dari Mana Anda Tahu Sekolah Ini? <span class="text-red-400">*</span></label>
                        <select id="sumber_informasi" name="sumber_informasi"
                                 class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border {{ $errors->has('sumber_informasi') ? 'border-red-500/50' : 'border-slate-700' }} text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/30 transition-all {{ old('sumber_informasi') ? 'text-white' : 'text-slate-500' }} appearance-none">
                            <option value="" disabled {{ old('sumber_informasi') ? '' : 'selected' }}>-- Pilih sumber informasi --</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->name }}" {{ old('sumber_informasi') === $source->name ? 'selected' : '' }} class="text-white bg-slate-800">{{ $source->name }}</option>
                            @endforeach
                        </select>
                        @error('sumber_informasi') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Submit --}}
                <button id="btn-daftar" type="submit"
                        class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold text-sm hover:from-indigo-400 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all duration-200 shadow-lg shadow-indigo-500/20 active:scale-[0.99]">
                    Buat Akun & Daftar Sekarang
                </button>

                <p class="text-center text-sm text-slate-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">Masuk di sini</a>
                </p>
            </form>

            <p class="text-center text-xs text-slate-600 mt-6 pb-4">
                {{ \App\Models\Setting::get('footer_copyright', '© ' . date('Y') . ' Yayasan Pendidikan Nusantara. All rights reserved.') }}
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const provinsiSelect = document.getElementById('provinsi');
        const kabupatenSelect = document.getElementById('kabupaten');
        const kecamatanSelect = document.getElementById('kecamatan');
        const sekolahSelect = document.getElementById('sekolah_select');
        const asalSekolahInput = document.getElementById('asal_sekolah');
        const manualSekolahContainer = document.getElementById('manual_sekolah_container');
        
        const baseUrl = "{{ url('') }}";

        // Fetch Provinsi
        fetch(baseUrl + '/api/region/provinsi')
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                provinsiSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
                data.forEach(item => {
                    provinsiSelect.innerHTML += `<option value="${item}">${item}</option>`;
                });
            })
            .catch(error => console.error('Error fetching provinsi:', error));

        // Event: Provinsi Changed
        provinsiSelect.addEventListener('change', function () {
            const val = this.value;
            kabupatenSelect.innerHTML = '<option value="">-- Memuat... --</option>';
            kabupatenSelect.disabled = true;
            kecamatanSelect.innerHTML = '<option value="">Pilih Kabupaten Dulu</option>';
            kecamatanSelect.disabled = true;
            sekolahSelect.innerHTML = '<option value="">Pilih Kecamatan Dulu</option>';
            sekolahSelect.disabled = true;

            if (val) {
                fetch(baseUrl + `/api/region/kabupaten?propinsi=${encodeURIComponent(val)}`)
                    .then(response => response.json())
                    .then(data => {
                        kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
                        data.forEach(item => {
                            kabupatenSelect.innerHTML += `<option value="${item}">${item}</option>`;
                        });
                        kabupatenSelect.disabled = false;
                    });
            } else {
                kabupatenSelect.innerHTML = '<option value="">Pilih Provinsi Dulu</option>';
            }
        });

        // Event: Kabupaten Changed
        kabupatenSelect.addEventListener('change', function () {
            const val = this.value;
            kecamatanSelect.innerHTML = '<option value="">-- Memuat... --</option>';
            kecamatanSelect.disabled = true;
            sekolahSelect.innerHTML = '<option value="">Pilih Kecamatan Dulu</option>';
            sekolahSelect.disabled = true;

            if (val) {
                fetch(baseUrl + `/api/region/kecamatan?kabupaten=${encodeURIComponent(val)}`)
                    .then(response => response.json())
                    .then(data => {
                        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                        data.forEach(item => {
                            kecamatanSelect.innerHTML += `<option value="${item}">${item}</option>`;
                        });
                        kecamatanSelect.disabled = false;
                    });
            } else {
                kecamatanSelect.innerHTML = '<option value="">Pilih Kabupaten Dulu</option>';
            }
        });

        // Event: Kecamatan Changed
        kecamatanSelect.addEventListener('change', function () {
            const val = this.value;
            sekolahSelect.innerHTML = '<option value="">-- Memuat... --</option>';
            sekolahSelect.disabled = true;

            if (val) {
                fetch(baseUrl + `/api/region/sekolah?kecamatan=${encodeURIComponent(val)}`)
                    .then(response => response.json())
                    .then(data => {
                        sekolahSelect.innerHTML = '<option value="">-- Pilih Sekolah --</option>';
                        data.forEach(item => {
                            sekolahSelect.innerHTML += `<option value="${item}">${item}</option>`;
                        });
                        sekolahSelect.innerHTML += `<option value="lainnya" class="font-bold text-indigo-400">++ LAINNYA (Ketik Manual) ++</option>`;
                        sekolahSelect.disabled = false;
                    });
            } else {
                sekolahSelect.innerHTML = '<option value="">Pilih Kecamatan Dulu</option>';
            }
        });

        // Event: Sekolah Changed
        sekolahSelect.addEventListener('change', function () {
            const val = this.value;
            
            if (val === 'lainnya') {
                manualSekolahContainer.classList.remove('hidden');
                manualSekolahContainer.classList.add('block');
                if (asalSekolahInput.value === 'lainnya' || document.querySelector(`#sekolah_select option[value="${asalSekolahInput.value}"]`)) {
                    asalSekolahInput.value = ''; 
                }
                asalSekolahInput.focus();
            } else if (val) {
                manualSekolahContainer.classList.remove('block');
                manualSekolahContainer.classList.add('hidden');
                asalSekolahInput.value = val;
            } else {
                manualSekolahContainer.classList.remove('block');
                manualSekolahContainer.classList.add('hidden');
                asalSekolahInput.value = '';
            }
        });
    });
</script>
@endpush
