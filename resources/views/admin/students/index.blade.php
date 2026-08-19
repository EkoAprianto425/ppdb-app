@extends('layouts.app')

@section('title', 'Data Pendaftar')
@section('page-title', 'Data Calon Siswa')
@section('page-subtitle', 'Manajemen pendaftar ' . (auth()->user()->isSuperAdmin() ? 'Seluruh Unit' : 'unit ' . auth()->user()->getUnit()))

@section('content')
{{-- Filter Section --}}
<div class="mb-6 card-glass p-6 rounded-2xl shadow-xl">
    <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-wrap gap-6 items-end">
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
            <label class="block text-[10px] font-bold themed-text-muted uppercase tracking-widest mb-2">Status PPDB</label>
            <select name="status" class="w-full themed-input rounded-xl px-4 py-2.5 text-xs themed-text focus:ring-primary appearance-none">
                <option value="" class="text-slate-900">Semua Status</option>
                @foreach(['tamu', 'Formulir', 'Lulus', 'daftar'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }} class="text-slate-900">{{ ucfirst($st) }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="flex gap-3">
            <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-primary-hover transition-all shadow-lg shadow-primary/20">
                Filter Data
            </button>
            <a href="{{ route('admin.students.export', request()->all()) }}" class="px-8 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/20">
                Export Excel
            </a>
            <a href="{{ route('admin.students.index') }}" class="px-8 py-2.5 btn-soft-secondary rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-white/10 transition-all">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="card-glass rounded-3xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left datatable" id="students-table">
            <thead>
                <tr class="border-b" :style="'border-color: var(--border-color)'">
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Nama Lengkap / Email</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Tujuan</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Wave</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Status PPDB</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Jadwal Ujian</th>
                    <th class="px-8 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-right" data-dt-order="disable">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr class="hover:bg-primary/5 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                {{ strtoupper(substr($student->name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold themed-text group-hover:text-primary transition-colors">{{ $student->full_name ?? $student->name }}</p>
                                <p class="text-[10px] themed-text-muted">{{ $student->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="text-[10px] font-bold themed-text bg-card-bg px-3 py-1 rounded-lg border" :style="'border-color: var(--border-color)'">
                            {{ $student->educationalLevel?->name ?? '-' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="text-[10px] font-bold themed-text bg-card-bg px-3 py-1 rounded-lg border" :style="'border-color: var(--border-color)'">
                            {{ $student->registration->registrationWave->name ?? '-' }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @php
                            $statusColors = [
                                'tamu' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                                'Formulir' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                'Lulus' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                'daftar' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-md border text-[9px] font-black uppercase tracking-widest {{ $statusColors[$student->ppdb_status] ?? 'bg-slate-500/10 text-slate-400' }}">
                            {{ $student->ppdb_status }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @if($student->registration && $student->registration->examSchedule)
                            <p class="text-[10px] font-bold themed-text">{{ date('d M', strtotime($student->registration->examSchedule->date)) }}</p>
                            <p class="text-[9px] themed-text-muted">{{ substr($student->registration->examSchedule->time_start, 0, 5) }}</p>
                        @else
                            <span class="text-[10px] italic themed-text-muted">-</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            @if($student->registration)
                            <a href="{{ route('admin.students.show', $student->registration) }}" class="p-2 rounded-lg btn-action-view" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.students.edit', $student->registration) }}" class="p-2 rounded-lg btn-action-edit" title="Edit Data">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @else
                            <span class="text-[9px] themed-text-muted italic">Belum isi formulir</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
