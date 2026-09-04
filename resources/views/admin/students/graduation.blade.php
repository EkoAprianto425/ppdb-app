@extends('layouts.app')

@section('title', 'Manajemen Kelulusan')
@section('page-title', 'Manajemen Kelulusan')
@section('page-subtitle', 'Tentukan status kelulusan peserta didik dan batas waktu pelunasan daftar ulang')

@section('content')
{{-- Filter Section --}}
<div class="mb-6 card-glass p-6 rounded-2xl shadow-xl">
    <form action="{{ route('admin.graduation.index') }}" method="GET" class="flex flex-wrap gap-6 items-end">
        @if(auth()->user()->isSuperAdmin())
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-bold themed-text-muted uppercase tracking-widest mb-2">Jenjang Tujuan</label>
            <select name="level_id" class="w-full themed-input rounded-xl px-4 py-2.5 text-xs themed-text focus:ring-primary appearance-none">
                <option value="" class="text-slate-900">Semua Jenjang</option>
                @foreach($levels as $lvl)
                    <option value="{{ $lvl->id }}" {{ request('level_id') == $lvl->id ? 'selected' : '' }} class="text-slate-900">{{ $lvl->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-bold themed-text-muted uppercase tracking-widest mb-2">Status Kelulusan</label>
            <select name="status" class="w-full themed-input rounded-xl px-4 py-2.5 text-xs themed-text focus:ring-primary appearance-none">
                <option value="" class="text-slate-900">Semua Status</option>
                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }} class="text-slate-900">Proses</option>
                <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }} class="text-slate-900">Lulus</option>
                <option value="tidak_lulus" {{ request('status') == 'tidak_lulus' ? 'selected' : '' }} class="text-slate-900">Gagal (Tidak Lulus)</option>
                <option value="mundur" {{ request('status') == 'mundur' ? 'selected' : '' }} class="text-slate-900">Mundur</option>
            </select>
        </div>
        
        <div class="flex gap-3">
            <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-primary-hover transition-all shadow-lg shadow-primary/20">
                Filter Data
            </button>
            <a href="{{ route('admin.graduation.index') }}" class="px-8 py-2.5 btn-soft-secondary rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-white/10 transition-all">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left datatable" id="graduation-table">
            <thead>
                <tr class="border-b bg-black/10" :style="'border-color: var(--border-color)'">
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Siswa</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Tujuan</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Jadwal Ujian</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Status Saat Ini</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest" data-dt-order="disable">Tindakan Kelulusan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                <tr class="hover:bg-primary/5 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                {{ strtoupper(substr($reg->user->name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold themed-text group-hover:text-primary transition-colors">{{ $reg->user->full_name ?? $reg->user->name ?? 'Siswa Terhapus' }}</p>
                                <p class="text-[10px] themed-text-muted">Gel. {{ $reg->registrationWave->name ?? 'Belum Dipilih' }} | ID: #{{ str_pad($reg->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="text-[10px] font-bold themed-text bg-card-bg px-3 py-1 rounded-lg border" :style="'border-color: var(--border-color)'">
                            {{ $reg->user->educationalLevel->name ?? '-' }}
                        </span>
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
                        @elseif($reg->status === 'mundur')
                            <span class="px-2 py-1 rounded text-[9px] font-bold uppercase tracking-widest bg-slate-500/10 text-slate-500 border border-slate-500/20">Mundur</span>
                        @else
                            <span class="px-2 py-1 rounded text-[9px] font-bold uppercase tracking-widest bg-amber-500/10 text-amber-500 border border-amber-500/20">Proses</span>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        {{-- Form Aksi --}}
                        <div x-data="{ 
                            showDeadline: {{ $reg->status === 'lulus' ? 'true' : 'false' }}
                        }">
                            <form action="{{ route('admin.students.update-status', $reg) }}" method="POST" class="flex flex-col gap-2">
                                @csrf
                                <div class="flex items-center gap-2">
                                    <div class="flex bg-black/20 rounded-lg p-1">
                                        <button type="button" 
                                            @click="showDeadline = true"
                                            class="px-3 py-1.5 rounded-md text-[9px] font-bold uppercase tracking-widest transition-all text-emerald-500 hover:bg-emerald-500/10 {{ $reg->status === 'lulus' ? 'bg-emerald-500/20' : '' }}">Lulus</button>
                                        <button type="submit" name="status" value="tidak_lulus"
                                            class="px-3 py-1.5 rounded-md text-[9px] font-bold uppercase tracking-widest transition-all text-rose-500 hover:bg-rose-500/10 {{ $reg->status === 'tidak_lulus' ? 'bg-rose-500/20' : '' }}">Gagal</button>
                                        <button type="submit" name="status" value="mundur"
                                            class="px-3 py-1.5 rounded-md text-[9px] font-bold uppercase tracking-widest transition-all text-slate-500 hover:bg-slate-500/10 {{ $reg->status === 'mundur' ? 'bg-slate-500/20' : '' }}">Mundur</button>
                                        <button type="submit" name="status" value="proses"
                                            class="px-3 py-1.5 rounded-md text-[9px] font-bold uppercase tracking-widest transition-all text-amber-500 hover:bg-amber-500/10 {{ $reg->status === 'proses' ? 'bg-amber-500/20' : '' }}">Reset</button>
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
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
