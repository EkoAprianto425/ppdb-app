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
                    @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">No. WhatsApp</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" required
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all">
                    @error('whatsapp_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Asal Sekolah --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $user->asal_sekolah) }}" required
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
                                {{ old('alasan_memilih', $user->alasan_memilih) === $reason->name ? 'selected' : '' }}>
                                {{ $reason->name }}
                            </option>
                        @endforeach
                        <option value="lainnya"
                            {{ old('alasan_memilih', $user->alasan_memilih) && !collect($reasons)->contains('name', old('alasan_memilih', $user->alasan_memilih)) ? 'selected' : '' }}>
                            ++ LAINNYA (Ketik Manual) ++
                        </option>
                    </select>
                    @php
                        $currentAlasan = old('alasan_memilih', $user->alasan_memilih);
                        $showManualAlasan = $currentAlasan && !collect($reasons)->contains('name', $currentAlasan);
                    @endphp
                    <div id="manual_alasan_container" class="{{ $showManualAlasan ? 'block' : 'hidden' }} mt-3">
                        <textarea id="alasan_memilih" name="alasan_memilih" rows="3"
                                  class="w-full themed-input rounded-xl px-4 py-3 resize-none"
                                  placeholder="Tuliskan alasan memilih sekolah ini...">{{ $showManualAlasan ? $currentAlasan : '' }}</textarea>
                    </div>
                    <input type="hidden" id="alasan_memilih_hidden" name="alasan_memilih"
                           value="{{ !$showManualAlasan ? $currentAlasan : '' }}">
                    @error('alasan_memilih') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Sumber Informasi --}}
                <div>
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Sumber Informasi</label>
                    <select id="sumber_informasi_select" name="sumber_informasi"
                            class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all appearance-none">
                        <option value="" disabled {{ !old('sumber_informasi', $user->sumber_informasi) ? 'selected' : '' }}>-- Pilih sumber informasi --</option>
                        @foreach($sources as $source)
                            <option value="{{ $source->name }}"
                                    data-requires-manual="{{ $source->requires_manual_input ? 'true' : 'false' }}"
                                {{ old('sumber_informasi', $user->sumber_informasi) === $source->name ? 'selected' : '' }}>
                                {{ $source->name }}
                            </option>
                        @endforeach
                        <option value="lainnya" {{ old('sumber_informasi', $user->sumber_informasi) == 'lainnya' ? 'selected' : '' }}>
                            ++ LAINNYA (Ketik Manual) ++
                        </option>
                    </select>
                    @error('sumber_informasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    @php
                        $currentSumber = old('sumber_informasi', $user->sumber_informasi);
                        $showManualSumber = $currentSumber == 'lainnya' ||
                            (collect($sources)->firstWhere('name', $currentSumber)?->requires_manual_input ?? false);
                    @endphp
                    <label class="block text-xs font-medium themed-text-muted mb-2 uppercase tracking-wide">Sumber Informasi (Tambahan)</label>
                    <input type="text" id="sumber_informasi_tambahan" name="sumber_informasi_tambahan"
                           value="{{ old('sumber_informasi_tambahan', $user->sumber_informasi_tambahan) }}"
                           placeholder="{{ $showManualSumber ? 'Contoh: Nama teman, nama akun IG, dll' : 'Isi jika sumber informasi memerlukan keterangan' }}"
                           {{ !$showManualSumber ? 'disabled' : '' }}
                           class="w-full themed-input rounded-xl px-4 py-3 focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all {{ !$showManualSumber ? 'opacity-50 cursor-not-allowed' : '' }}">
                    @error('sumber_informasi_tambahan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

<script>
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

        // Init
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
        const sumberTambahanInput = document.getElementById('sumber_informasi_tambahan');

        sumberSelect.addEventListener('change', function() {
            const val = this.value;
            const selectedOption = this.options[this.selectedIndex];
            const requiresManual = selectedOption?.getAttribute('data-requires-manual') === 'true' || val === 'lainnya';

            if (requiresManual) {
                sumberTambahanInput.disabled = false;
                sumberTambahanInput.classList.remove('opacity-50', 'cursor-not-allowed');
                sumberTambahanInput.placeholder = 'Contoh: Nama teman, nama akun IG, dll';
                sumberTambahanInput.focus();
            } else {
                sumberTambahanInput.disabled = true;
                sumberTambahanInput.classList.add('opacity-50', 'cursor-not-allowed');
                sumberTambahanInput.placeholder = 'Isi jika sumber informasi memerlukan keterangan';
                sumberTambahanInput.value = '';
            }
        });
    })();
</script>
@endsection
