@extends('layouts.app')

@section('title', 'Manajemen Admin')
@section('page-title', 'User Admin')
@section('page-subtitle', 'Kelola akses administrator unit')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Form --}}
    <div class="card-glass rounded-3xl p-8 h-fit">
        <h3 class="text-lg font-bold themed-text mb-6">Tambah Admin Baru</h3>
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="name" required
                       class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Email</label>
                <input type="email" name="email" required
                       class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Role / Unit</label>
                <select name="role" required
                        class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
                    <option value="admin_smp">Admin SMP</option>
                    <option value="admin_sma">Admin SMA</option>
                    <option value="admin_smk">Admin SMK</option>
                    <option value="admin_administrasi">Administrasi / Keuangan</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" required
                           class="w-full themed-input rounded-xl px-4 py-3 pr-10 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700" onclick="const input = this.previousElementSibling; if(input.type === 'password') { input.type = 'text'; this.innerHTML = '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\'/></svg>' } else { input.type = 'password'; this.innerHTML = '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\'/></svg>' }">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl btn-soft-primary font-bold uppercase tracking-widest text-xs">Buat Akun Admin</button>
        </form>
    </div>

    {{-- List --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="card-glass rounded-3xl overflow-hidden">
            <table class="w-full text-left datatable" id="admins-table">
                <thead>
                    <tr class="border-b" :style="'border-color: var(--border-color)'">
                        <th class="px-6 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest">Nama / Email</th>
                        <th class="px-6 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">Role</th>
                        <th class="px-6 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-right" data-dt-order="disable">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" :style="'divide-color: var(--border-color)'">
                    @foreach($admins as $admin)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold themed-text">{{ $admin->name }}</p>
                                    <p class="text-[10px] themed-text-muted">{{ $admin->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $roleColors = [
                                    'super_admin' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                    'admin_smp' => 'bg-sky-500/10 text-sky-500 border-sky-500/20',
                                    'admin_sma' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                                    'admin_smk' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                    'admin_administrasi' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                ];
                                $color = $roleColors[$admin->role] ?? 'bg-slate-500/10 text-slate-500 border-white/5';
                            @endphp
                            <span class="px-2 py-1 rounded-md border text-[9px] font-black uppercase tracking-widest {{ $color }}">
                                {{ str_replace('_', ' ', str_replace('admin_', '', $admin->role)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($admin->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $admin) }}" method="POST" onsubmit="return confirm('Hapus user admin ini?')">
                                @csrf @method('DELETE')
                                <button class="p-2 rounded-lg btn-action-delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @else
                            <span class="text-[10px] themed-text-muted italic px-2">Anda</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
