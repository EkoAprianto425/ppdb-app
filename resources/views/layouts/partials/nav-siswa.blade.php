{{-- Sidebar Navigation: Siswa --}}
<p class="text-[10px] font-bold uppercase themed-text-muted px-4 mb-4 tracking-[0.2em]">Main Navigation</p>

<a href="{{ route('dashboard') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('dashboard') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
    </div>
    <span class="font-medium">Dashboard</span>
</a>

<a href="{{ route('pendaftaran.index') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs(['pendaftaran.index', 'pendaftaran.create', 'pendaftaran.edit', 'pendaftaran.update', 'pendaftaran.store']) ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </div>
    <span class="font-medium">Formulir Pendaftaran</span>
</a>

<a href="{{ route('pendaftaran.financial') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('pendaftaran.financial') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
    </div>
    <span class="font-medium">Administrasi</span>
</a>

<a href="{{ route('pendaftaran.exam') }}" class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('pendaftaran.exam*') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
        </svg>
    </div>
    <span class="font-medium">Kartu Ujian</span>
</a>

<a href="{{ route('pendaftaran.announcement') }}" class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('pendaftaran.announcement*') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
    </div>
    <span class="font-medium">Pengumuman</span>
</a>
