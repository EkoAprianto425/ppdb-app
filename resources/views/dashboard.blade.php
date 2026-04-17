@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan status pendaftaran Anda')

@section('content')

@php
    $user = auth()->user();
    $registration = $user->registration;
    $hasRegistration = (bool)$registration;
    
    // Cari Jenjang yang sesuai
    $level = \App\Models\EducationalLevel::where('name', $user->tujuan_masuk)->first();
    $allFees = $level ? $level->fees()->orderBy('sort_order')->get() : collect();
    
    // Temukan biaya aktif yang harus diproses sekarang
    $activeFee = null;
    $activePayment = null;
    
    foreach ($allFees as $f) {
        $p = $registration ? $registration->payments()->where('fee_type', $f->name)->latest()->first() : null;
        
        if (!$p || $p->status !== 'success') {
            $activeFee = $f;
            $activePayment = $p;
            break;
        }
    }
    
    // Jika biaya #1 sudah sukses, tapi belum LULUS, maka jangan tampilkan biaya berikutnya dulu
    if ($activeFee && $activeFee->sort_order > 1 && (!$registration || $registration->status !== 'lulus')) {
        $activeFee = null; 
    }
    
    $fee = $activeFee;
    $payment = $activePayment;
    $paymentStatus = $payment ? $payment->status : 'none'; 

    // Ambil jadwal ujian yang tersedia untuk unit siswa
    $schedules = \App\Models\ExamSchedule::where('unit', $user->tujuan_masuk)->get();
    $hasExam = $registration && $registration->exam_schedule_id;

    $statusColors = [
        'none' => 'bg-slate-700/50 text-slate-400 border-slate-700',
        'pending' => 'bg-amber-500/15 text-amber-400 border-amber-500/20',
        'success' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/20',
        'failed' => 'bg-rose-500/15 text-rose-400 border-rose-500/20',
    ];
    $statusLabels = [
        'none' => 'Belum Bayar',
        'pending' => 'Menunggu',
        'success' => 'Berhasil',
        'failed' => 'Gagal',
    ];
@endphp

@if(session('status'))
<div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium flex items-center gap-3 animate-slide-in">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('status') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm font-medium flex items-center gap-3 animate-slide-in">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
</div>
@endif

@if($errors->any())
<div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm font-medium animate-slide-in">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Welcome Banner --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 p-6 mb-8 shadow-2xl shadow-indigo-500/20 transition-all duration-500"
     :style="'background: linear-gradient(135deg, ' + themes[currentTheme].color + ', #6b21a8); shadow-color: ' + themes[currentTheme].color + '33'">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full translate-y-24 -translate-x-24"></div>
    </div>
    <div class="relative z-10 flex items-start justify-between">
        <div>
            <p class="text-white/70 text-sm font-medium mb-1">Selamat datang,</p>
            <h2 class="text-2xl font-bold text-white mb-1">{{ $user->full_name ?? $user->name }} 👋</h2>
            <p class="text-white/70 text-sm">Tujuan masuk: <span class="font-semibold text-white">{{ $user->tujuan_masuk ?? '-' }}</span></p>
        </div>
        <div class="hidden sm:block">
            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center text-3xl shadow-lg">
                🎓
            </div>
        </div>
    </div>
</div>

{{-- Status Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Formulir --}}
    <a href="{{ route('pendaftaran.index') }}" class="card-glass rounded-2xl p-5 hover:border-primary/30 transition-all group relative overflow-hidden active:scale-95">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-all"
                 :class="{{ $hasRegistration ? "'bg-emerald-500/20'" : "'bg-primary/20'" }}">
                <svg class="w-5 h-5 transition-colors duration-500" 
                     :class="{{ $hasRegistration ? "'text-emerald-400'" : "'text-primary'" }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            @if($hasRegistration)
                <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-500/15 text-emerald-400 font-medium border border-emerald-500/20">Sudah Diisi</span>
            @else
                <span class="text-xs px-2.5 py-1 rounded-full bg-amber-500/15 text-amber-400 font-medium border border-amber-500/20">Belum Diisi</span>
            @endif
        </div>
        <p class="text-lg font-bold themed-text mb-0.5">Formulir</p>
        <p class="text-[10px] themed-text-muted uppercase tracking-widest font-bold">Biodata Siswa</p>
    </a>

    {{-- Administrasi --}}
    <button @click="$dispatch('open-modal', 'payment-info')" 
            class="card-glass rounded-2xl p-5 hover:border-purple-500/30 transition-all group text-left">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center group-hover:scale-110 transition-all text-purple-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <span class="text-[10px] px-2.5 py-1 rounded-full font-medium border uppercase {{ $statusColors[$paymentStatus] }}">
                {{ $statusLabels[$paymentStatus] }}
            </span>
        </div>
        <p class="text-lg font-bold themed-text mb-0.5">Administrasi</p>
        <p class="text-[10px] themed-text-muted uppercase tracking-widest font-bold">Biaya Pendaftaran</p>
    </button>

    {{-- Jadwal Ujian --}}
    <button @click="$dispatch('open-modal', 'modal-select-exam')" 
            class="card-glass rounded-2xl p-5 hover:border-blue-500/30 transition-all group text-left">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center group-hover:scale-110 transition-all text-blue-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="text-[10px] px-2.5 py-1 rounded-full font-medium border uppercase {{ $hasExam ? 'bg-emerald-500/15 text-emerald-400 border-emerald-500/20' : 'bg-slate-700/50 text-slate-400 border-slate-700' }}">
                {{ $hasExam ? 'Sudah Pilih' : 'Belum Pilih' }}
            </span>
        </div>
        <p class="text-lg font-bold themed-text mb-0.5">Jadwal Ujian</p>
        <p class="text-[10px] themed-text-muted uppercase tracking-widest font-bold">{{ $hasExam ? $registration->examSchedule->name : 'Pilih Sesi Ujian' }}</p>
    </button>

    {{-- Pengumuman --}}
    <div class="card-glass rounded-2xl p-5 group opacity-60">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2-2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <span class="text-[10px] px-2.5 py-1 rounded-full bg-slate-700/50 text-slate-400 font-medium border border-slate-700 uppercase">Belum Ada</span>
        </div>
        <p class="text-lg font-bold themed-text mb-0.5">Pengumuman</p>
        <p class="text-[10px] themed-text-muted uppercase tracking-widest font-bold">Status Kelulusan</p>
    </div>
</div>

{{-- Main Panels --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Tahapan Pendaftaran (Kiri) --}}
    <div class="lg:col-span-2 space-y-8">
        {{-- Payment Upload Info --}}
        @if($hasRegistration && ($paymentStatus == 'none' || $paymentStatus == 'failed'))
            <div class="card-glass rounded-3xl p-8 border-l-4 border-primary">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h3 class="text-xl font-bold themed-text mb-2">Unggah Bukti Pembayaran</h3>
                        <p class="text-sm themed-text-muted leading-relaxed">Lakukan pembayaran biaya pendaftaran sebesar <span class="text-primary font-bold">Rp {{ number_format($fee->amount ?? 0, 0, ',', '.') }}</span> untuk melanjutkan proses verifikasi.</p>
                    </div>
                    <button @click="$dispatch('open-modal', 'modal-upload-payment')" class="px-8 py-3 rounded-xl bg-primary text-white font-bold text-sm shadow-lg shadow-primary/20 whitespace-nowrap active:scale-95 transition-all">
                        Upload Bukti Sekarang
                    </button>
                </div>
            </div>
        @endif

        {{-- Timeline --}}
        <div class="card-glass rounded-3xl p-8">
            <h3 class="text-lg font-bold themed-text mb-8">Tahapan Pendaftaran</h3>
            <div class="space-y-6">
                @php
                $fee1 = $allFees->where('sort_order', 1)->first();
                $fee2 = $allFees->where('sort_order', 2)->first();
                $p1 = $fee1 && $registration ? $registration->payments()->where('fee_type', $fee1->name)->latest()->first() : null;
                $p2 = $fee2 && $registration ? $registration->payments()->where('fee_type', $fee2->name)->latest()->first() : null;
                $status1 = $p1 ? $p1->status : 'none';
                $status2 = $p2 ? $p2->status : 'none';
                $isPassed = $registration && $registration->status === 'lulus';

                $steps = [
                    [
                        'title' => 'Buat Akun',
                        'done'  => true,
                        'active'=> false
                    ],
                    [
                        'title' => 'Isi Formulir Pendaftaran',
                        'done'  => $hasRegistration,
                        'active'=> !$hasRegistration
                    ],
                    [
                        'title' => 'Bayar Formulir',
                        'done'  => $status1 === 'success',
                        'active'=> $hasRegistration && $status1 !== 'success'
                    ],
                    [
                        'title' => 'Pilih Jadwal Ujian',
                        'done'  => $hasExam,
                        'active'=> $status1 === 'success' && !$hasExam
                    ],
                    [
                        'title' => 'Pengumuman Kelulusan',
                        'done'  => $isPassed,
                        'active'=> $hasExam && !$isPassed
                    ],
                    [
                        'title' => 'Bayar Uang Masuk',
                        'done'  => $status2 === 'success',
                        'active'=> $isPassed && $status2 !== 'success'
                    ],
                ];
                @endphp
                @foreach($steps as $i => $step)
                <div class="flex items-start gap-4 line-wrapper relative pb-2">
                    @if($i < count($steps) - 1)
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
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <p class="text-sm font-bold {{ $step['done'] ? 'text-emerald-400' : ($step['active'] ? 'themed-text' : 'themed-text-muted') }}">{{ $step['title'] }}</p>
                            @if($step['title'] === 'Pilih Jadwal Ujian' && $step['done'])
                                <a href="{{ route('pendaftaran.exam-card') }}" target="_blank" class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500 hover:text-white transition-colors text-[9px] font-black uppercase tracking-widest flex items-center gap-1 w-max">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Sidebar (Kanan) --}}
    <div class="space-y-6">
        <div class="card-glass rounded-3xl p-6">
            <h3 class="text-[10px] font-bold themed-text-muted uppercase tracking-[0.2em] mb-6">Informasi Sekolah</h3>
            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-primary/5 border border-primary/10">
                    <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Unit Pendidikan</p>
                    <p class="text-sm font-bold themed-text">{{ $user->tujuan_masuk }}</p>
                </div>
                <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                    <p class="text-[9px] themed-text-muted font-bold uppercase mb-1">Tahun Ajaran</p>
                    <p class="text-sm font-bold themed-text">{{ \App\Models\AcademicYear::where('is_active', true)->first()->name ?? 'Belum Diatur' }}</p>
                </div>
            </div>
        </div>

        @if($level && $level->contact_whatsapp)
        <div class="card-glass rounded-3xl p-6 bg-gradient-to-br from-emerald-500/10 to-teal-500/5 border-emerald-500/20 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
            
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-emerald-400">Pusat Bantuan</h3>
                    <p class="text-[9px] text-emerald-500/70 uppercase tracking-widest font-bold">Panitia Unit {{ $user->tujuan_masuk }}</p>
                </div>
            </div>
            
            <p class="text-xs themed-text-muted mb-5 leading-relaxed">
                Punya kendala atau pertanyaan terkait pendaftaran khusus jurusan Anda?
            </p>
            
            <a href="https://wa.me/{{ $level->contact_whatsapp }}" target="_blank" 
               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-500/20 hover:bg-emerald-400 transition-all uppercase tracking-widest">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Hubungi Kami
            </a>
        </div>
        @endif
    </div>
</div>

{{-- MODALS --}}
{{-- 1. Modal Upload Payment --}}
@if($hasRegistration)
<div x-data="{ open: false }" @open-modal.window="if($event.detail === 'modal-upload-payment') open = true" x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
    <div @click.away="open = false" class="card-glass rounded-3xl p-8 w-full max-w-lg shadow-2xl scale-in-center">
        <h3 class="text-xl font-bold themed-text mb-2">Kirim Bukti Pembayaran</h3>
        <p class="text-xs themed-text-muted mb-6">Pastikan foto bukti transfer terlihat jelas dengan nominal Rp {{ number_format($fee->amount ?? 0, 0, ',', '.') }}</p>
        
        <form action="{{ route('pendaftaran.payment.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="fee_type" value="formulir">
            <input type="hidden" name="amount" value="{{ $fee->amount ?? 0 }}">

            <div class="relative group">
                <input type="file" name="payment_proof" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="p-8 border-2 border-dashed border-white/10 rounded-2xl group-hover:border-primary/50 group-hover:bg-primary/5 transition-all flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <p class="text-sm themed-text font-medium">Klik untuk pilih file foto</p>
                    <p class="text-[10px] themed-text-muted mt-1 uppercase">PNG, JPG (Max 2MB)</p>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="button" @click="open = false" class="flex-1 py-3 rounded-xl btn-soft-secondary font-bold text-xs uppercase">Batal</button>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-primary text-white font-bold text-xs uppercase shadow-lg shadow-primary/20 transition-all active:scale-95">Unggah Bukti</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- 2. Modal Payment Info / Detail --}}
<div x-data="{ open: false }" @open-modal.window="if($event.detail === 'payment-info') open = true" x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
    <div @click.away="open = false" class="card-glass rounded-3xl p-8 w-full max-w-lg">
        <h3 class="text-xl font-bold themed-text mb-6">Informasi Pembayaran</h3>
        <div class="space-y-6">
            <div class="p-6 rounded-2xl bg-white/5 border border-white/5">
                <p class="text-[10px] themed-text-muted uppercase font-bold tracking-widest mb-1">Status Pembayaran</p>
                <p class="text-lg font-bold {{ $paymentStatus == 'success' ? 'text-emerald-400' : ($paymentStatus == 'pending' ? 'text-amber-400' : 'themed-text') }}">
                    {{ $statusLabels[$paymentStatus] }}
                </p>
                @if($payment && $payment->admin_note)
                    <div class="mt-3 p-3 rounded-lg bg-rose-500/10 border border-rose-500/20 text-xs text-rose-400 italic">
                        Catatan: {{ $payment->admin_note }}
                    </div>
                @endif
            </div>

            @if($paymentStatus == 'none' || $paymentStatus == 'failed')
            <div class="space-y-4">
                <p class="text-xs themed-text-muted leading-relaxed">Silakan transfer biaya pendaftaran sebesar <span class="text-white font-black">Rp {{ number_format($fee->amount ?? 0, 0, ',', '.') }}</span> ke rekening berikut:</p>
                <div class="p-5 rounded-2xl bg-primary/5 border border-primary/10 flex justify-between items-center group">
                    <div>
                        <p class="text-[10px] text-primary/70 font-bold uppercase mb-1 tracking-widest">BCA Virtual Account</p>
                        <p class="text-xl font-black text-primary tracking-widest">123-456-7890</p>
                        <p class="text-[9px] themed-text font-black mt-1 uppercase opacity-60">A/N YAYASAN PPDB SEKOLAH</p>
                    </div>
                </div>
            </div>
            @endif

            <button @click="open = false" class="w-full py-4 rounded-xl btn-soft-secondary font-bold text-xs uppercase transition-all active:scale-95">Tutup</button>
        </div>
    </div>
</div>

{{-- 3. Modal Select Exam --}}
@if($hasRegistration)
<div x-data="{ open: false }" @open-modal.window="if($event.detail === 'modal-select-exam') open = true" x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
    <div @click.away="open = false" class="card-glass rounded-3xl p-8 w-full max-w-2xl shadow-2xl scale-in-center">
        <h3 class="text-xl font-bold themed-text mb-2">Pilih Jadwal Ujian</h3>
        <p class="text-xs themed-text-muted mb-8 italic">Silakan pilih salah satu sesi ujian yang tersedia. Pastikan Anda bisa hadir pada waktu tersebut.</p>
        
        <form action="{{ route('pendaftaran.exam.select') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($schedules as $schedule)
                <label class="relative group cursor-pointer">
                    <input type="radio" name="exam_schedule_id" value="{{ $schedule->id }}" 
                           {{ $registration->exam_schedule_id == $schedule->id ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="p-5 rounded-2xl border border-white/5 bg-white/5 transition-all group-hover:bg-primary/5 peer-checked:bg-primary peer-checked:border-primary shadow-lg shadow-black/20">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-black/20 flex flex-col items-center justify-center text-white border border-white/10">
                                <span class="text-[9px] font-black uppercase tracking-tighter">{{ date('M', strtotime($schedule->date)) }}</span>
                                <span class="text-lg font-black leading-none">{{ date('d', strtotime($schedule->date)) }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-black text-white mb-1 uppercase tracking-tight">{{ $schedule->name }}</p>
                                <p class="text-[9px] text-white/70 uppercase tracking-widest font-bold">Jam {{ substr($schedule->time_start, 0, 5) }} WIB</p>
                            </div>
                        </div>
                    </div>
                </label>
                @empty
                    <div class="col-span-2 py-10 text-center themed-text-muted italic text-xs">Belum ada jadwal tersedia untuk unit {{ $user->tujuan_masuk }}.</div>
                @endforelse
            </div>

            <div class="flex gap-4 mt-8 pt-6 border-t border-white/5">
                <button type="button" @click="open = false" class="flex-1 py-4 rounded-xl btn-soft-secondary font-bold text-xs uppercase">Batal</button>
                <button type="submit" class="flex-1 py-4 rounded-xl bg-primary text-white font-bold text-xs uppercase shadow-lg shadow-primary/20 transition-all active:scale-95">Simpan Pilihan</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
