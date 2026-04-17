@extends('layouts.app')

@section('title', 'Manajemen Kelulusan')
@section('page-title', 'Manajemen Kelulusan')
@section('page-subtitle', 'Tentukan status kelulusan peserta didik dan batas waktu pelunasan daftar ulang')

@section('content')
<div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
    <div class="p-8 border-b flex items-center justify-between" :style="'border-color: var(--border-color)'">
        <h3 class="text-lg font-bold themed-text">Pengumuman Kelulusan</h3>
        <div class="flex gap-2 text-[10px] font-bold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-lg">
            Total Valid: {{ $registrations->where('payment_status', 'success')->count() }} Siswa
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b bg-black/10" :style="'border-color: var(--border-color)'">
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Siswa</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Jadwal Ujian</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Status Saat Ini</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Tindakan Kelulusan</th>
                </tr>
            </thead>
            <tbody class="divide-y" :style="'divide-color: var(--border-color)'">
                @forelse($registrations->where('payment_status', 'success') as $reg)
                <tr class="hover:bg-primary/5 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                {{ strtoupper(substr($reg->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold themed-text group-hover:text-primary transition-colors">{{ $reg->user->full_name }}</p>
                                <p class="text-[10px] themed-text-muted">Gel. {{ $reg->registrationWave->name ?? 'Belum Dipilih' }} | ID: #{{ str_pad($reg->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @if($reg->examSchedule)
                            <p class="text-[10px] font-bold themed-text">{{ date('d M Y', strtotime($reg->examSchedule->date)) }}</p>
                        @else
                            <span class="text-[10px] italic themed-text-muted">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-center">
                        @if($reg->status === 'lulus')
                            <span class="px-2 py-1 rounded text-[9px] font-bold uppercase tracking-widest bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Lulus</span>
                            <div class="mt-1 text-[9px] themed-text-muted">Jatuh Tempo: {{ $reg->reregistration_deadline ? date('d M Y', strtotime($reg->reregistration_deadline)) : '-' }}</div>
                        @elseif($reg->status === 'tidak_lulus')
                            <span class="px-2 py-1 rounded text-[9px] font-bold uppercase tracking-widest bg-rose-500/10 text-rose-500 border border-rose-500/20">Tidak Lulus</span>
                        @else
                            <span class="px-2 py-1 rounded text-[9px] font-bold uppercase tracking-widest bg-amber-500/10 text-amber-500 border border-amber-500/20">Proses</span>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        {{-- Form Aksi --}}
                        <div x-data="{ 
                            showDeadline: false
                        }">
                            <form action="{{ route('admin.students.update-status', $reg) }}" method="POST" class="flex flex-col gap-2">
                                @csrf
                                <div class="flex items-center gap-2">
                                    <div class="flex bg-black/20 rounded-lg p-1">
                                        <button type="button" 
                                            @click="showDeadline = true"
                                            class="px-3 py-1.5 rounded-md text-[9px] font-bold uppercase tracking-widest transition-all text-emerald-500 hover:bg-emerald-500/10">Lulus</button>
                                        <button type="submit" name="status" value="tidak_lulus"
                                            class="px-3 py-1.5 rounded-md text-[9px] font-bold uppercase tracking-widest transition-all text-rose-500 hover:bg-rose-500/10">Gagal</button>
                                        <button type="submit" name="status" value="proses"
                                            class="px-3 py-1.5 rounded-md text-[9px] font-bold uppercase tracking-widest transition-all text-amber-500 hover:bg-amber-500/10">Reset</button>
                                    </div>
                                </div>
                                
                                {{-- Input Tanggal --}}
                                <div x-show="showDeadline" x-collapse x-cloak>
                                    <div class="flex items-center gap-2 mt-2">
                                        <input type="text" name="reregistration_deadline"
                                            value="{{ $reg->reregistration_deadline ?? '' }}"
                                            class="datepicker themed-input text-xs rounded-lg px-2 py-1.5 min-w-[130px] border" :style="'border-color: var(--border-color)'"
                                            :required="showDeadline" placeholder="Pilih Tanggal">
                                        <button type="submit" name="status" value="lulus" class="px-3 py-1.5 rounded-lg bg-emerald-500 text-white text-[9px] font-bold uppercase tracking-widest shadow-md hover:bg-emerald-600 transition-colors">
                                            Simpan
                                        </button>
                                    </div>
                                    <p class="text-[9px] text-amber-500 mt-1">* Wajib diisi batas waktu lunas (biaya urut #2).</p>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-10 text-center themed-text-muted italic text-xs">Belum ada pendaftar yang pembayarannya terverifikasi (success).</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
