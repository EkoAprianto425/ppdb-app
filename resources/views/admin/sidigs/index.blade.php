@extends('layouts.app')

@section('title', 'Integrasi SIDIGS')
@section('page-title', 'Integrasi SIDIGS')
@section('page-subtitle', 'Riwayat sinkronisasi data siswa pendaftar ke SIDIGS API')

@section('content')
<div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full datatable" id="sidigs-table">
            <thead>
                <tr class="border-b" :style="'border-color: var(--border-color)'">
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Nama Siswa</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">NISN</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Jenjang</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Status</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Waktu Sinkron</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center" data-dt-order="disable">Response</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                <tr class="hover:bg-primary/5 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                {{ strtoupper(substr($record->registration->nama_lengkap ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold themed-text group-hover:text-primary transition-colors">{{ $record->registration->nama_lengkap ?? 'N/A' }}</p>
                                <p class="text-[10px] themed-text-muted">{{ $record->registration->user->email ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="text-[10px] font-bold themed-text bg-card-bg px-3 py-1 rounded-lg border" :style="'border-color: var(--border-color)'">
                            {{ $record->registration->nisn ?? '-' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="text-[10px] font-bold themed-text bg-card-bg px-3 py-1 rounded-lg border" :style="'border-color: var(--border-color)'">
                            {{ $record->registration->user->educationalLevel->name ?? '-' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @if($record->status === 'success')
                            <span class="px-3 py-1 rounded-md border text-[9px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-500 border-emerald-500/20">
                                Sukses
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-md border text-[9px] font-black uppercase tracking-widest bg-red-500/10 text-red-500 border-red-500/20">
                                Gagal
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-center">
                        <p class="text-[10px] font-bold themed-text">{{ $record->created_at->format('d M Y') }}</p>
                        <p class="text-[9px] themed-text-muted">{{ $record->created_at->format('H:i') }}</p>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <button onclick="document.getElementById('detail-{{ $record->id }}').classList.toggle('hidden')" class="p-2 rounded-lg btn-action-view" title="Lihat Response">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        <div id="detail-{{ $record->id }}" class="hidden mt-2 text-left">
                            <pre class="text-[9px] themed-text-muted bg-card-bg p-3 rounded-xl border overflow-x-auto max-w-xs" :style="'border-color: var(--border-color)'">{{ json_encode($record->response_payload, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
