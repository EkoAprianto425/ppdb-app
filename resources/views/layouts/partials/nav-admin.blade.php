{{-- Sidebar Navigation: Admin Unit (SMP/SMA/SMK) --}}
<p class="text-[10px] font-bold uppercase themed-text-muted px-4 mb-4 tracking-[0.2em]">Management Area</p>

<a href="{{ route('admin.dashboard') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
        </svg>
    </div>
    <span class="font-medium">Admin Dashboard</span>
</a>

@if(in_array(auth()->user()->role, [\App\Models\User::ROLE_ADMIN_SMP, \App\Models\User::ROLE_ADMIN_SMA, \App\Models\User::ROLE_ADMIN_SMK, \App\Models\User::ROLE_SUPER_ADMIN]))
    <a href="{{ route('admin.students.index') }}"
       class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.students.*') ? 'active' : 'themed-text-muted' }}">
        <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <span class="font-medium">Data Pendaftar</span>
    </a>

    <a href="{{ route('admin.graduation.index') }}"
       class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.graduation.index') ? 'active' : 'themed-text-muted' }}">
        <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
        </div>
        <span class="font-medium">Manajemen Kelulusan</span>
    </a>

    <a href="{{ route('admin.schedules.index') }}"
       class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.schedules.*') ? 'active' : 'themed-text-muted' }}">
        <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z"/>
            </svg>
        </div>
        <span class="font-medium">Jadwal Ujian</span>
    </a>

    <a href="{{ route('admin.discounts.index') }}"
       class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.discounts.*') ? 'active' : 'themed-text-muted' }}">
        <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="font-medium">Master Potongan</span>
    </a>

    <a href="{{ route('admin.discount-applications.index') }}"
       class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.discount-applications.*') ? 'active' : 'themed-text-muted' }}">
        <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A3.323 3.323 0 0010.605 8.3c-.643.085-1.25.4-1.743.894l-3.323 3.323a3.323 3.323 0 000 4.698l.698.698a3.323 3.323 0 004.698 0l3.323-3.323a3.323 3.323 0 00.894-1.743 3.323 3.323 0 00-.016-1.618z"/>
            </svg>
        </div>
        <span class="font-medium">Validasi Keringanan</span>
    </a>
@endif

@if(auth()->user()->role == 'admin_administrasi' || auth()->user()->isSuperAdmin())
    <p class="text-[10px] font-bold uppercase themed-text-muted px-4 mb-4 mt-6 tracking-[0.2em]">Keuangan</p>
    <a href="{{ route('admin.financial.fees') }}"
       class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.financial.fees') ? 'active' : 'themed-text-muted' }}">
        <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <span class="font-medium">Master Biaya</span>
    </a>
    <a href="{{ route('admin.financial.payments') }}"
       class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.financial.payments') ? 'active' : 'themed-text-muted' }}">
        <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="font-medium">Verifikasi Pembayaran</span>
    </a>
@endif
