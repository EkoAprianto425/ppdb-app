{{--
    Partial: discount-info
    Menampilkan status dan rincian keringanan biaya yang sudah diajukan.

    Label nominal seragam dengan tampilan admin (index.blade.php):
      - anak_pegawai : "Biaya Pendaftaran" + "Biaya SPP"
      - lainnya      : "Potongan"

    Variabel yang dibutuhkan:
      - $activeDiscountApp : DiscountApplication instance (relasi ->discount sudah di-load)
--}}
@php
    $disc      = $activeDiscountApp->discount;
    $isPegawai = $disc->category === 'anak_pegawai';
    $statusColor = match($activeDiscountApp->status) {
        'approved' => ['badge' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20', 'amount' => 'text-emerald-400'],
        'rejected' => ['badge' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',           'amount' => 'text-rose-400'],
        default    => ['badge' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',        'amount' => 'text-amber-400'],
    };
@endphp

<div class="flex flex-col items-end gap-1">

    {{-- Nama diskon + badge status --}}
    <p class="text-[10px] font-bold themed-text">{{ $disc->name }}</p>
    <span class="px-3 py-1 rounded-md border text-[9px] font-black uppercase tracking-widest {{ $statusColor['badge'] }}">
        {{ $activeDiscountApp->status }}
    </span>

    {{-- Rincian nominal (selalu ditampilkan, sesuai referensi admin) --}}
    <div class="mt-1.5 flex flex-col items-end gap-0.5">
        @if($isPegawai)
            {{-- Keluarga Karyawan: Biaya Pendaftaran + Biaya SPP --}}
            <span class="text-[9px] themed-text-muted">
                Biaya Pendaftaran:
                <span class="font-bold {{ $statusColor['amount'] }}">Rp {{ number_format($disc->amount, 0, ',', '.') }}</span>
            </span>
            <span class="text-[9px] themed-text-muted">
                Biaya SPP:
                <span class="font-bold {{ $statusColor['amount'] }}">Rp {{ number_format($disc->spp_amount, 0, ',', '.') }}</span>
            </span>
        @else
            {{-- Alumni / Umum: Potongan --}}
            <span class="text-[9px] themed-text-muted">
                Potongan:
                <span class="font-bold {{ $statusColor['amount'] }}">Rp {{ number_format($disc->amount, 0, ',', '.') }}</span>
            </span>
        @endif
    </div>

    {{-- Catatan dari admin --}}
    @if($activeDiscountApp->notes)
        <p class="text-[9px] themed-text-muted italic max-w-[220px] text-right mt-1">
            {{ $activeDiscountApp->notes }}
        </p>
    @endif

</div>
