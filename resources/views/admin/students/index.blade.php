@extends('layouts.app')

@section('title', 'Data Pendaftar')
@section('page-title', 'Data Calon Siswa')
@section('page-subtitle', 'Manajemen pendaftar unit ' . auth()->user()->getUnit())

@section('content')
<div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left datatable" id="students-table">
            <thead>
                <tr class="border-b" :style="'border-color: var(--border-color)'">
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Nama Lengkap / Email</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Tujuan</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Wave</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Status Bayar</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Jadwal Ujian</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-right" data-dt-order="disable">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                <tr class="hover:bg-primary/5 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                {{ strtoupper(substr($reg->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold themed-text group-hover:text-primary transition-colors">{{ $reg->user->full_name }}</p>
                                <p class="text-[10px] themed-text-muted">{{ $reg->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="text-[10px] font-bold themed-text bg-card-bg px-3 py-1 rounded-lg border" :style="'border-color: var(--border-color)'">
                            {{ $reg->user->educationalLevel?->name ?? '-' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="text-[10px] font-bold themed-text bg-card-bg px-3 py-1 rounded-lg border" :style="'border-color: var(--border-color)'">
                            {{ $reg->registrationWave->name ?? 'Belum Dipilih' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @php
                            $statusColors = [
                                'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                'success' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                'failed'  => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-md border text-[9px] font-black uppercase tracking-widest {{ $statusColors[$reg->payment_status] ?? 'bg-slate-500/10 text-slate-400' }}">
                            {{ $reg->payment_status }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @if($reg->examSchedule)
                            <p class="text-[10px] font-bold themed-text">{{ date('d M', strtotime($reg->examSchedule->date)) }}</p>
                            <p class="text-[9px] themed-text-muted">{{ substr($reg->examSchedule->time_start, 0, 5) }}</p>
                        @else
                            <span class="text-[10px] italic themed-text-muted">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.students.show', $reg) }}" class="p-2 rounded-lg bg-primary/10 text-primary border border-primary/20 hover:bg-primary/20 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.students.edit', $reg) }}" class="p-2 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-500/20 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
