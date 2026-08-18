@php
    $user = auth()->user();
    $registration = $user->registration;

    $activeDiscountApp = $registration ? $registration->discountApplications()->latest()->first() : null;

    // Filter diskon berdasarkan parent_unit jenjang siswa (SMP / SMA / SMK)
    $userParentUnit = $user->educationalLevel?->parent_unit;

    $discounts = \App\Models\Discount::where('is_active', true)
        ->where(function ($q) use ($userParentUnit) {
            $q->whereNull('educational_level_id');
            if ($userParentUnit) {
                $q->orWhereHas('educationalLevel', function ($q2) use ($userParentUnit) {
                    $q2->where('parent_unit', $userParentUnit);
                });
            }
        })->get();
        
    $isAlumni = stripos($user->asal_sekolah ?? '', 'AL HASRA') !== false;

    $discountsPegawai = $discounts->where('category', 'anak_pegawai')->values();
    $discountsAlumni  = $discounts->where('category', 'alumni')->values();
    $discountsUmum    = $discounts->where('category', 'umum')->values();
@endphp

{{-- Discount Modal --}}
<template x-teleport="body">
    <div x-data="{
            open: false,
            activeCategory: null,
            employeeStatus: null,
            selectedDiscount: null,
            reset() {
                this.activeCategory   = null;
                this.employeeStatus   = null;
                this.selectedDiscount = null;
            },
            get categoryDiscounts() {
                if (this.activeCategory === 'anak_pegawai') return {{ $discountsPegawai->toJson() }};
                if (this.activeCategory === 'alumni')       return {{ $discountsAlumni->toJson() }};
                if (this.activeCategory === 'umum')         return {{ $discountsUmum->toJson() }};
                return [];
            },
            selectDiscount(item) {
                this.selectedDiscount = item;
                // Untuk anak_pegawai, gunakan name sebagai status kepegawaian
                this.employeeStatus = (this.activeCategory === 'anak_pegawai') ? item.name : null;
            },
            get currentStep() {
                if (!this.activeCategory)   return 1;
                if (!this.selectedDiscount) return 2;
                return 3;
            }
        }"
        @open-discount-modal.window="open = true; reset()"
        x-show="open"
        x-cloak
        style="display:none;"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-y-auto"
    >
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="open = false"></div>

    {{-- Modal Container --}}
    <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden border"
         :style="'background: var(--surface-color); border-color: var(--border-color)'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
    >
        {{-- ═══════════════ HEADER ═══════════════ --}}
        <div class="shrink-0 px-6 py-5 border-b flex items-center justify-between"
             :style="'border-color: var(--border-color); background: var(--card-bg)'">
            <div>
                <h3 class="text-lg font-extrabold themed-text">Pengajuan Keringanan Biaya</h3>
                <p class="text-xs themed-text-muted mt-0.5">Pilih program diskon yang sesuai dengan kualifikasi Anda</p>
            </div>
            <button @click="open = false"
                    class="w-9 h-9 rounded-xl flex items-center justify-center themed-text-muted hover:text-red-400 transition-colors border"
                    :style="'border-color: var(--border-color); background: var(--card-bg)'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- ═══════════════ STEPPER ═══════════════ --}}
        <div class="shrink-0 px-6 py-3 border-b flex items-center gap-3"
             :style="'border-color: var(--border-color); background: var(--card-bg)'">

            {{-- Step 1: Kategori --}}
            <div class="flex items-center gap-2 shrink-0">
                <span class="w-6 h-6 rounded-full text-[10px] font-black flex items-center justify-center transition-colors"
                      :class="currentStep === 1 ? 'bg-primary text-white' : 'bg-emerald-500 text-white'">
                    <template x-if="currentStep > 1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </template>
                    <template x-if="currentStep === 1">1</template>
                </span>
                <span class="text-[11px] font-bold hidden sm:inline"
                      :class="currentStep === 1 ? 'text-primary' : 'text-emerald-500'">Kategori</span>
            </div>

            <svg class="w-3.5 h-3.5 themed-text-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>

            {{-- Step 2: Pilih Diskon --}}
            <div class="flex items-center gap-2 shrink-0">
                <span class="w-6 h-6 rounded-full text-[10px] font-black flex items-center justify-center transition-colors"
                      :class="currentStep === 2 ? 'bg-primary text-white' : (currentStep > 2 ? 'bg-emerald-500 text-white' : 'themed-text-muted')"
                      :style="currentStep < 2 ? 'background: var(--card-bg); border: 1px solid var(--border-color)' : ''">
                    <template x-if="currentStep > 2">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </template>
                    <template x-if="currentStep <= 2">2</template>
                </span>
                <span class="text-[11px] font-bold hidden sm:inline"
                      :class="currentStep === 2 ? 'text-primary' : (currentStep > 2 ? 'text-emerald-500' : 'themed-text-muted')">Pilih Diskon</span>
            </div>

            <svg class="w-3.5 h-3.5 themed-text-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>

            {{-- Step 3: Formulir --}}
            <div class="flex items-center gap-2 shrink-0">
                <span class="w-6 h-6 rounded-full text-[10px] font-black flex items-center justify-center transition-colors"
                      :class="currentStep === 3 ? 'bg-primary text-white' : 'themed-text-muted'"
                      :style="currentStep < 3 ? 'background: var(--card-bg); border: 1px solid var(--border-color)' : ''">3</span>
                <span class="text-[11px] font-bold hidden sm:inline"
                      :class="currentStep === 3 ? 'text-primary' : 'themed-text-muted'">Formulir</span>
            </div>
        </div>

        {{-- ═══════════════ BODY (scrollable) ═══════════════ --}}
        <div class="flex-1 overflow-y-auto p-6">

            {{-- ── STEP 1: Pilih Kategori ── --}}
            <div x-show="currentStep === 1">
                <p class="text-sm themed-text-muted mb-5">Silakan pilih kategori keringanan yang sesuai:</p>

                <div class="grid grid-cols-1 sm:grid-cols-{{ $isAlumni ? '3' : '2' }} gap-4">
                    {{-- Karyawan --}}
                    <button type="button" @click="activeCategory = 'anak_pegawai'"
                            class="card-glass rounded-2xl p-5 text-center cursor-pointer transition-all hover:border-primary/50 hover:shadow-lg hover:shadow-primary/10 group">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-primary/15 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition-transform">👨‍💼</div>
                        <h4 class="font-bold themed-text text-sm mb-1">Keluarga Karyawan</h4>
                        <p class="text-[11px] themed-text-muted leading-relaxed">Diskon khusus anak pegawai internal yayasan</p>
                    </button>

                    {{-- Alumni --}}
                    @if($isAlumni)
                    <button type="button" @click="activeCategory = 'alumni'"
                            class="card-glass rounded-2xl p-5 text-center cursor-pointer transition-all hover:border-emerald-500/50 hover:shadow-lg hover:shadow-emerald-500/10 group">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-500/15 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition-transform">🎓</div>
                        <h4 class="font-bold themed-text text-sm mb-1">Alumni</h4>
                        <p class="text-[11px] themed-text-muted leading-relaxed">Keringanan bagi lulusan SMP AL HASRA</p>
                    </button>
                    @endif

                    {{-- Umum --}}
                    <button type="button" @click="activeCategory = 'umum'"
                            class="card-glass rounded-2xl p-5 text-center cursor-pointer transition-all hover:border-blue-500/50 hover:shadow-lg hover:shadow-blue-500/10 group">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-500/15 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition-transform">🌟</div>
                        <h4 class="font-bold themed-text text-sm mb-1">Umum & Prestasi</h4>
                        <p class="text-[11px] themed-text-muted leading-relaxed">Potongan jalur prestasi atau reguler</p>
                    </button>
                </div>
            </div>

            {{-- ── STEP 2: Pilih Diskon ── --}}
            <div x-show="currentStep === 2" style="display:none;">
                <button type="button" @click="activeCategory = null"
                        class="btn-soft-secondary rounded-lg px-3 py-1.5 text-xs font-bold flex items-center gap-1.5 mb-5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </button>

                {{-- Info banner khusus anak_pegawai --}}
                <template x-if="activeCategory === 'anak_pegawai'">
                    <div class="card-glass rounded-xl p-4 mb-5 border-l-4 border-primary flex items-start gap-3">
                        <div class="w-9 h-9 shrink-0 rounded-xl bg-primary/15 flex items-center justify-center text-xl">👨‍💼</div>
                        <div>
                            <p class="text-sm font-bold themed-text mb-0.5">Keluarga Karyawan</p>
                            <p class="text-xs themed-text-muted">Setiap program di bawah sesuai dengan <strong class="themed-text">status kepegawaian</strong> orang tua / wali di yayasan. Pilih yang sesuai.</p>
                        </div>
                    </div>
                </template>

                <p class="text-sm font-bold themed-text mb-4">Pilih program keringanan:</p>

                <div class="space-y-3">
                    <template x-for="item in categoryDiscounts" :key="item.id">
                        <div class="card-glass rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-primary/30 transition-colors">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h5 class="font-bold themed-text text-sm" x-text="item.name"></h5>
                                    <span x-show="item.require_document"
                                          class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-500/15 text-amber-500 border border-amber-500/20">Wajib Dokumen</span>
                                </div>
                                <p class="text-xs themed-text-muted mb-2 line-clamp-2" x-text="item.description"></p>
                                <div class="flex flex-wrap gap-2">
                                    {{-- Biaya Pendaftaran --}}
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-xs font-bold border border-emerald-500/20">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span x-text="'Biaya Pendaftaran: Rp ' + new Intl.NumberFormat('id-ID').format(item.amount)"></span>
                                    </span>
                                    {{-- Biaya SPP: hanya tampil untuk anak_pegawai --}}
                                    <template x-if="activeCategory === 'anak_pegawai'">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-400 text-xs font-bold border border-blue-500/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span x-text="'Biaya SPP: Rp ' + new Intl.NumberFormat('id-ID').format(item.spp_amount || 0)"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>
                            <button type="button" @click="selectDiscount(item)"
                                    class="shrink-0 px-5 py-2.5 rounded-xl bg-primary text-white text-xs font-bold shadow-md hover:opacity-90 transition-all active:scale-95 w-full sm:w-auto text-center">
                                Pilih
                            </button>
                        </div>
                    </template>

                    {{-- Empty state --}}
                    <div x-show="categoryDiscounts.length === 0"
                         class="card-glass rounded-xl p-10 text-center">
                        <div class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center themed-text-muted mb-3"
                             :style="'background: var(--card-bg)'">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                        <p class="text-sm themed-text-muted">Belum ada program diskon untuk kategori ini.</p>
                    </div>
                </div>
            </div>

            {{-- ── STEP 3: Formulir Pengajuan ── --}}
            <div x-show="currentStep === 3" style="display:none;">
                <button type="button" @click="selectedDiscount = null; employeeStatus = null"
                        class="btn-soft-secondary rounded-lg px-3 py-1.5 text-xs font-bold flex items-center gap-1.5 mb-5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </button>

                <form action="{{ route('discount.apply') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="discount_id" :value="selectedDiscount?.id">
                    {{-- employee_status diisi otomatis dari name diskon anak_pegawai --}}
                    <input type="hidden" name="employee_status" :value="employeeStatus ?? ''">

                    {{-- Tampilkan status kepegawaian jika kategori anak_pegawai --}}
                    <template x-if="employeeStatus">
                        <div class="card-glass rounded-xl p-4 mb-4 flex items-center gap-3 border-l-4 border-purple-500">
                            <div class="w-9 h-9 shrink-0 rounded-xl bg-purple-500/15 flex items-center justify-center text-lg">🏷️</div>
                            <div>
                                <p class="text-[10px] themed-text-muted font-bold uppercase tracking-widest mb-0.5">Status Kepegawaian</p>
                                <p class="text-sm font-extrabold themed-text" x-text="employeeStatus"></p>
                            </div>
                        </div>
                    </template>

                    {{-- Selected discount info --}}
                    <div class="card-glass rounded-xl p-5 mb-5 border-l-4 border-primary">
                        <p class="text-[10px] themed-text-muted font-bold uppercase tracking-widest mb-1">Program Terpilih</p>
                        <h4 class="text-base font-extrabold themed-text mb-1" x-text="selectedDiscount?.name"></h4>
                        <p class="text-xs themed-text-muted mb-3" x-text="selectedDiscount?.description"></p>
                        <div class="flex flex-wrap gap-2">
                            {{-- Biaya Pendaftaran --}}
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-xs font-bold border border-emerald-500/20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="'Biaya Pendaftaran: Rp ' + new Intl.NumberFormat('id-ID').format(selectedDiscount?.amount || 0)"></span>
                            </span>
                            {{-- Biaya SPP: hanya untuk anak_pegawai --}}
                            <template x-if="activeCategory === 'anak_pegawai'">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-400 text-xs font-bold border border-blue-500/20">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span x-text="'Biaya SPP: Rp ' + new Intl.NumberFormat('id-ID').format(selectedDiscount?.spp_amount || 0)"></span>
                                </span>
                            </template>
                        </div>
                    </div>

                    {{-- Upload section (conditional) --}}
                    <template x-if="activeCategory === 'anak_pegawai' || selectedDiscount?.require_document">
                        <div class="card-glass rounded-xl p-5 mb-5 border-l-4 border-amber-500">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 shrink-0 rounded-xl bg-amber-500/15 text-amber-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold themed-text mb-1">
                                        <template x-if="activeCategory === 'anak_pegawai'">
                                            <span>Upload Kartu Keluarga (KK) <span class="text-red-400">*</span></span>
                                        </template>
                                        <template x-if="activeCategory !== 'anak_pegawai'">
                                            <span>Upload Dokumen Pendukung <span class="text-red-400">*</span></span>
                                        </template>
                                    </p>
                                    <p class="text-xs themed-text-muted mb-3">
                                        <template x-if="activeCategory === 'anak_pegawai'">
                                            <span>Kartu Keluarga wajib sebagai bukti hubungan keluarga dengan karyawan yayasan. Format: PDF, JPG, PNG &mdash; Maks 2MB.</span>
                                        </template>
                                        <template x-if="activeCategory !== 'anak_pegawai'">
                                            <span>Bukti pendukung pengajuan diskon. Format: PDF, JPG, PNG &mdash; Maks 2MB.</span>
                                        </template>
                                    </p>
                                    <input type="file" name="document" required accept=".pdf,.jpg,.jpeg,.png"
                                           class="block w-full text-xs themed-text-muted themed-input rounded-lg p-2 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:opacity-90 file:cursor-pointer file:transition-opacity">
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Footer Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t" :style="'border-color: var(--border-color)'">
                        <button type="button" @click="open = false"
                                class="btn-soft-secondary rounded-xl px-5 py-2.5 text-xs font-bold">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold shadow-md transition-all active:scale-95">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
