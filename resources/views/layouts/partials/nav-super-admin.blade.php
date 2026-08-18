{{-- Sidebar Navigation: Super Admin --}}
<p class="text-[10px] font-bold uppercase themed-text-muted px-4 mb-4 mt-6 tracking-[0.2em]">Super Admin</p>

<a href="{{ route('admin.users.index') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.users*') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
    </div>
    <span class="font-medium">Manajemen Admin</span>
</a>

<a href="{{ route('admin.year.index') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.year*') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </div>
    <span class="font-medium">Tahun Ajaran</span>
</a>

<a href="{{ route('admin.levels.index') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.levels*') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
    </div>
    <span class="font-medium">Manajemen Jenjang</span>
</a>

<a href="{{ route('admin.wave.index') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.wave*') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
    </div>
    <span class="font-medium">Gelombang</span>
</a>

<a href="{{ route('admin.settings.index') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.settings*') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </div>
    <span class="font-medium">Pengaturan Global</span>
</a>

<a href="{{ route('admin.backup.index') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.backup.*') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
        </svg>
    </div>
    <span class="font-medium">Backup & Restore</span>
</a>

<a href="{{ route('admin.information-sources.index') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.information-sources.*') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <span class="font-medium">Sumber Informasi</span>
</a>

<a href="{{ route('admin.school-reasons.index') }}"
   class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs('admin.school-reasons.*') ? 'active' : 'themed-text-muted' }}">
    <div class="p-2 rounded-lg group-hover:bg-primary/10 transition-colors" :style="'background: var(--border-color)'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <span class="font-medium">Alasan Sekolah</span>
</a>

