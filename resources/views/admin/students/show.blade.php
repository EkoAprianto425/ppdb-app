@extends('layouts.app')

@section('title', 'Detail Siswa - ' . $registration->user->full_name)
@section('page-title', 'Profil Calon Siswa')
@section('page-subtitle', 'Kelola data pendaftaran dan penjadwalan ujian')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-8">
        {{-- Student Profile Header --}}
        <div class="card-glass rounded-3xl p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            
            <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                <div class="w-32 h-32 rounded-3xl bg-primary/10 flex items-center justify-center text-primary text-4xl font-black border border-primary/20 shadow-2xl">
                    {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-black themed-text tracking-tight mb-2">{{ $registration->user->full_name }}</h2>
                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                        <span class="px-3 py-1 rounded-lg bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest border border-primary/20">
                            {{ $registration->user->tujuan_masuk }}
                        </span>
                        <span class="px-3 py-1 rounded-lg bg-card-bg text-themed-text-muted text-[10px] font-bold uppercase tracking-widest border" :style="'border-color: var(--border-color)'">
                            Gelombang: {{ $registration->registrationWave->name ?? 'Belum Diatur' }}
                        </span>
                        <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[10px] font-bold uppercase tracking-widest border border-emerald-500/20">
                            ID: #{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Registrasi Awal & Biodata --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Registrasi Awal --}}
            <div class="card-glass rounded-3xl p-8">
                <h3 class="text-xs font-bold text-sky-400 uppercase tracking-widest mb-6 border-b pb-4" :style="'border-color: var(--border-color)'">Registrasi Awal</h3>
                <div class="space-y-4">
                    @php
                        $initData = [
                            'Asal Sekolah' => $registration->user->asal_sekolah,
                            'Tujuan Masuk' => $registration->user->tujuan_masuk,
                            'Alasan Memilih' => $registration->user->alasan_memilih,
                            'Sumber Informasi' => $registration->user->sumber_informasi,
                            'No. WhatsApp' => $registration->user->whatsapp_number,
                        ];
                    @endphp
                    @foreach($initData as $l => $v)
                    <div class="flex flex-col gap-1 border-b pb-2" :style="'border-color: var(--border-color)'">
                        <span class="text-[9px] themed-text-muted uppercase tracking-widest font-bold">{{ $l }}</span>
                        <span class="text-sm themed-text font-medium leading-relaxed">{{ $v ?? '-' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Data Pribadi --}}
            <div class="card-glass rounded-3xl p-8">
                <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-6 border-b pb-4" :style="'border-color: var(--border-color)'">Biodata Lengkap</h3>
                <div class="space-y-4">
                    @php
                        $basic = [
                            'Nama Panggilan' => $registration->nama_panggilan,
                            'Jenis Kelamin' => $registration->jenis_kelamin,
                            'Agama' => $registration->agama,
                            'Tempat Lahir' => $registration->tempat_lahir,
                            'Tanggal Lahir' => date('d F Y', strtotime($registration->tanggal_lahir)),
                            'Anak Ke' => $registration->anak_ke . ' dari ' . $registration->dari_saudara . ' bersaudara',
                            'Kebutuhan Khusus' => $registration->kebutuhan_khusus,
                        ];
                    @endphp
                    @foreach($basic as $l => $v)
                    <div class="flex flex-col gap-1 border-b pb-2" :style="'border-color: var(--border-color)'">
                        <span class="text-[9px] themed-text-muted uppercase tracking-widest font-bold">{{ $l }}</span>
                        <span class="text-sm themed-text font-medium">{{ $v }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Alamat & Orang Tua --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Alamat --}}
            <div class="card-glass rounded-3xl p-8">
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-6 border-b pb-4" :style="'border-color: var(--border-color)'">Alamat & Lokasi</h3>
                <div class="space-y-4">
                    <div class="flex flex-col gap-1 border-b pb-2" :style="'border-color: var(--border-color)'">
                        <span class="text-[9px] themed-text-muted uppercase tracking-widest font-bold">Alamat Lengkap</span>
                        <span class="text-sm themed-text leading-relaxed">{{ $registration->alamat }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] themed-text-muted uppercase tracking-widest font-bold">Provinsi</span>
                            <span class="text-xs themed-text">{{ $registration->provinsi }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] themed-text-muted uppercase tracking-widest font-bold">Kabupaten</span>
                            <span class="text-xs themed-text">{{ $registration->kabupaten }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] themed-text-muted uppercase tracking-widest font-bold">Kecamatan</span>
                            <span class="text-xs themed-text">{{ $registration->kecamatan }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Orang Tua --}}
            <div class="card-glass rounded-3xl p-8">
                <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-6 border-b pb-4" :style="'border-color: var(--border-color)'">Data Orang Tua</h3>
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-bold themed-text mb-2 text-emerald-500/80">AYAH</p>
                        <p class="text-sm font-bold themed-text">{{ $registration->nama_ayah }}</p>
                        <p class="text-[10px] themed-text-muted uppercase tracking-wide">{{ $registration->pendidikan_ayah }} - {{ $registration->pekerjaan_ayah }}</p>
                        <p class="text-xs text-emerald-500 font-bold mt-1">Rp {{ number_format($registration->penghasilan_ayah, 0, ',', '.') }}</p>
                    </div>
                    <div class="pt-4 border-t" :style="'border-color: var(--border-color)'">
                        <p class="text-[10px] font-bold themed-text mb-2 text-rose-500/80">IBU</p>
                        <p class="text-sm font-bold themed-text">{{ $registration->nama_ibu }}</p>
                        <p class="text-[10px] themed-text-muted uppercase tracking-wide">{{ $registration->pendidikan_ibu }} - {{ $registration->pekerjaan_ibu }}</p>
                        <p class="text-xs text-rose-500 font-bold mt-1">Rp {{ number_format($registration->penghasilan_ibu, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Side Actions --}}
    <div class="space-y-6">
        {{-- Status / Timeline --}}
        <div class="card-glass rounded-3xl p-8 border-t-4 {{ $registration->status === 'lulus' ? 'border-emerald-500' : ($registration->status === 'tidak_lulus' ? 'border-rose-500' : 'border-amber-500') }}">
            <h3 class="text-xs font-bold themed-text uppercase tracking-widest mb-6 flex items-center justify-between">
                Timeline Seleksi
                
                @if($registration->status === 'lulus')
                    <span class="px-3 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[10px] font-bold uppercase tracking-widest border border-emerald-500/20">LULUS</span>
                @elseif($registration->status === 'tidak_lulus')
                    <span class="px-3 py-1 rounded-lg bg-rose-500/10 text-rose-500 text-[10px] font-bold uppercase tracking-widest border border-rose-500/20">TIDAK LULUS</span>
                @else
                    <span class="px-3 py-1 rounded-lg bg-amber-500/10 text-amber-400 text-[10px] font-bold uppercase tracking-widest border border-amber-500/20">DALAM PROSES</span>
                @endif
            </h3>

            @php
                $level = \App\Models\EducationalLevel::where('name', $registration->user->tujuan_masuk)->first();
                $allFees = $level ? $level->fees()->orderBy('sort_order')->get() : collect();
                $fee1 = $allFees->where('sort_order', 1)->first();
                $fee2 = $allFees->where('sort_order', 2)->first();
                $p1 = $fee1 ? $registration->payments()->where('fee_type', $fee1->name)->latest()->first() : null;
                $p2 = $fee2 ? $registration->payments()->where('fee_type', $fee2->name)->latest()->first() : null;
                $p1Status = $p1 ? $p1->status : 'none';
                $p2Status = $p2 ? $p2->status : 'none';
                $isPassed = $registration->status === 'lulus';
                $hasExam = (bool)$registration->exam_schedule_id;

                $timelineSteps = [
                    [
                        'title' => 'Pembayaran Formulir',
                        'desc'  => $p1Status === 'success' ? 'LUNAS' : ($p1Status === 'pending' ? 'MENUNGGU VERIFIKASI' : 'BELUM BAYAR'),
                        'done'  => $p1Status === 'success',
                        'active'=> $p1Status !== 'success'
                    ],
                    [
                        'title' => 'Jadwal Ujian',
                        'desc'  => $hasExam ? $registration->examSchedule->name . ' (' . date('d M Y', strtotime($registration->examSchedule->date)) . ')' : 'BELUM DIJADWALKAN',
                        'done'  => $hasExam,
                        'active'=> $p1Status === 'success' && !$hasExam
                    ],
                    [
                        'title' => 'Hasil Seleksi',
                        'desc'  => $isPassed ? 'LULUS' : ($registration->status === 'tidak_lulus' ? 'TIDAK LULUS' : 'DALAM PROSES'),
                        'done'  => $isPassed || $registration->status === 'tidak_lulus',
                        'active'=> $hasExam && $registration->status === 'proses'
                    ],
                    [
                        'title' => 'Pembayaran Admin.',
                        'desc'  => $p2Status === 'success' ? 'LUNAS' : ($registration->reregistration_deadline ? 'Batas Akhir: '.date('d M Y', strtotime($registration->reregistration_deadline)) : ($isPassed ? 'BELUM BAYAR' : 'MENUNGGU KELULUSAN')),
                        'done'  => $p2Status === 'success',
                        'active'=> $isPassed && $p2Status !== 'success'
                    ],
                ];
            @endphp
            
            <div class="space-y-6 mt-6">
                @foreach($timelineSteps as $i => $step)
                <div class="flex items-start gap-4 line-wrapper relative pb-2">
                    @if($i < count($timelineSteps) - 1)
                        <div class="absolute left-[15px] top-8 bottom-0 w-px border-l-2 border-dashed {{ $step['done'] ? 'border-emerald-500/50' : 'border-white/10' }}"></div>
                    @endif
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0 transition-all duration-500 z-10
                        {{ $step['done'] ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : ($step['active'] ? 'bg-primary text-white ring-4 ring-primary/20 shadow-lg shadow-primary/30' : 'bg-card-bg themed-text-muted border border-white/10') }}">
                        @if($step['done'])
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <div class="pt-1.5 pb-2">
                        <p class="text-sm font-bold {{ $step['done'] ? 'text-emerald-400' : ($step['active'] ? 'themed-text' : 'themed-text-muted') }}">{{ $step['title'] }}</p>
                        <p class="text-[9px] {{ $step['done'] ? 'text-emerald-500/70' : 'themed-text-muted' }} mt-1 font-bold uppercase tracking-widest">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Aksi --}}
        <div class="space-y-3">
            <a href="{{ route('admin.students.edit', $registration) }}" class="w-full py-3 rounded-xl btn-soft-primary text-xs font-bold uppercase tracking-widest block text-center">Edit Biodata</a>
            
            @if(auth()->user()->role !== \App\Models\User::ROLE_ADMIN_SMP)
            <button x-data @click="$dispatch('open-modal', 'modal-transfer')" class="w-full py-3 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-500/20 transition-all text-xs font-bold uppercase tracking-widest">Pindah Jenjang</button>
            @endif
        </div>
    </div>
</div>

{{-- Modal Transfer --}}
<div x-data="{ open: false }" @open-modal.window="if($event.detail === 'modal-transfer') open = true" x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
    <div @click.away="open = false" class="card-glass rounded-3xl p-8 w-full max-w-md shadow-2xl scale-in-center">
        <h3 class="text-xl font-bold themed-text mb-4">Pindah Jenjang</h3>
        <p class="text-sm themed-text-muted mb-6">Pindahkan siswa ini ke jenjang lain. Data pendaftaran akan tetap ada namun unit tujuan akan berubah.</p>
        
        <form action="{{ route('admin.students.transfer', $registration) }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Pilih Jenjang Tujuan</label>
                <select name="unit" required class="w-full themed-input rounded-xl px-4 py-3 text-sm transition-all appearance-none">
                    @foreach(\App\Models\EducationalLevel::where('name', '!=', 'SMP')->get() as $level)
                        <option value="{{ $level->name }}" {{ $registration->user->tujuan_masuk === $level->name ? 'selected' : '' }}>Unit {{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex gap-4">
                <button type="button" @click="open = false" class="flex-1 py-3 rounded-xl btn-soft-secondary font-bold text-xs uppercase tracking-widest">Batal</button>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-indigo-500 text-white font-bold text-xs uppercase tracking-widest shadow-lg shadow-indigo-500/50">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
