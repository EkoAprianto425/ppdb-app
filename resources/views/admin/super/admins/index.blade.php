@extends('layouts.app')

@section('title', 'Manajemen Admin')
@section('page-title', 'User Admin')
@section('page-subtitle', 'Kelola akses administrator unit')

@section('content')
<div x-data="{ showEditModal: false, editAdmin: {} }">
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
                <label class="block text-xs font-bold themed-text-muted uppercase tracking-widest mb-2">No WA (Opsional)</label>
                <input type="text" name="whatsapp_number"
                       class="w-full themed-input rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 transition-all">
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
                        <th class="px-6 py-4 text-[10px] font-bold themed-text-muted uppercase tracking-widest text-center">No WA</th>
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
                            <span class="text-sm themed-text">{{ $admin->whatsapp_number ?? '-' }}</span>
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
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" class="p-2 rounded-lg btn-action-edit"
                                        @click="editAdmin = {{ json_encode(['id' => $admin->id, 'name' => $admin->name, 'email' => $admin->email, 'role' => $admin->role, 'whatsapp_number' => $admin->whatsapp_number]) }}; showEditModal = true">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
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
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Edit Modal -->
    <template x-teleport="body">
        <div x-show="showEditModal" 
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-y-auto" 
             x-cloak 
             style="display: none;">
            
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="showEditModal = false"></div>

            {{-- Modal Container --}}
            <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden border"
                 @click.away="showEditModal = false"
                 :style="'background: var(--surface-color); border-color: var(--border-color)'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                {{-- HEADER --}}
                <div class="shrink-0 px-6 py-5 border-b flex items-center justify-between"
                     :style="'border-color: var(--border-color); background: var(--card-bg)'">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <div class="w-8 h-8 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <h3 class="text-lg font-extrabold themed-text">Edit Admin</h3>
                        </div>
                        <p class="text-xs themed-text-muted mt-0.5">Perbarui informasi dan hak akses admin</p>
                    </div>
                    <button @click="showEditModal = false"
                            class="w-9 h-9 rounded-xl flex items-center justify-center themed-text-muted hover:text-red-400 transition-colors border"
                            :style="'border-color: var(--border-color); background: var(--card-bg)'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                {{-- BODY --}}
                <div class="flex-1 overflow-y-auto p-6">
                    <form :action="`/admin/users/${editAdmin.id}`" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Nama Lengkap</label>
                            <input type="text" name="name" x-model="editAdmin.name" required
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Email</label>
                            <input type="email" name="email" x-model="editAdmin.email" required
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Role / Unit</label>
                            <select name="role" x-model="editAdmin.role" required
                                    class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all appearance-none">
                                <option value="admin_smp">Admin SMP</option>
                                <option value="admin_sma">Admin SMA</option>
                                <option value="admin_smk">Admin SMK</option>
                                <option value="admin_administrasi">Administrasi / Keuangan</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">No WA (Opsional)</label>
                            <input type="text" name="whatsapp_number" x-model="editAdmin.whatsapp_number"
                                   class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black themed-text-muted uppercase tracking-[0.2em] mb-2 px-1">Password (Kosongkan jika tidak diubah)</label>
                            <div class="relative">
                                <input type="password" name="password"
                                       class="w-full bg-black/20 border-2 border-white/5 rounded-2xl px-5 py-4 pr-12 text-sm themed-text focus:border-primary/50 focus:ring-0 transition-all placeholder:text-white/10">
                                <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center themed-text-muted hover:text-white transition-colors" onclick="const input = this.previousElementSibling; if(input.type === 'password') { input.type = 'text'; this.innerHTML = '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\'/></svg>' } else { input.type = 'password'; this.innerHTML = '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\'/></svg>' }">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        {{-- Footer Actions --}}
                        <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t" :style="'border-color: var(--border-color)'">
                            <button type="button" @click="showEditModal = false" class="btn-soft-secondary rounded-xl px-5 py-2.5 text-xs font-bold">Batal</button>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-bold shadow-md transition-all active:scale-95">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
