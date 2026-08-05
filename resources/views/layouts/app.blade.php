<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ \App\Models\Setting::get('meta_description', 'PPDB Online - Pendaftaran Peserta Didik Baru') }}">
    <title>@yield('title', \App\Models\Setting::get('app_name', 'PPDB Online')) - {{ \App\Models\Setting::get('app_name', 'PPDB Online') }}</title>

    <!-- jQuery + DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = { 
            darkMode: 'class', 
            theme: { 
                extend: {
                    colors: {
                        primary: 'var(--primary-color)',
                        surface: 'var(--surface-color)',
                    }
                } 
            } 
        }
    </script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        :root {
            --primary-color: #0ea5e9;
            --primary-rgb: 14, 165, 233;
            --surface-color: #081b33;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --card-bg: rgba(255, 255, 255, 0.04);
            --border-color: rgba(255, 255, 255, 0.1);
            --sidebar-bg: rgba(7, 25, 48, 0.5);
            --header-bg: rgba(255, 255, 255, 0.04);
            --input-bg: rgba(0, 0, 0, 0.2);
        }
        
        body {
            background-color: var(--surface-color);
            color: var(--text-main);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .themed-text { color: var(--text-main); }
        .themed-text-muted { color: var(--text-muted); }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.15), rgba(var(--primary-rgb), 0.05));
            border-left: 4px solid var(--primary-color);
            color: var(--text-main);
            box-shadow: inset 0 0 20px rgba(var(--primary-rgb), 0.05);
        }
        
        .sidebar-link:hover:not(.active) {
            background: rgba(var(--primary-rgb), 0.05);
            transform: translateX(4px);
        }

        .card-glass {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);
        }
        
        .floating-header {
            background: var(--header-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 24px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .floating-header.scrolled {
            backdrop-filter: blur(40px);
            background: var(--header-bg);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-color) / 10;
        }

        /* Generic Input Styling */
        .themed-input {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            color: var(--text-main);
        }

        /* Soft Contrast Buttons */
        .btn-soft-primary {
            background: rgba(var(--primary-rgb), 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(var(--primary-rgb), 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-soft-primary:hover {
            background: rgba(var(--primary-rgb), 0.2);
            border-color: rgba(var(--primary-rgb), 0.4);
            transform: translateY(-1px);
        }
        .btn-soft-primary:active { transform: translateY(0); }

        .btn-soft-secondary {
            background: var(--card-bg);
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }
        .btn-soft-secondary:hover {
            background: rgba(var(--primary-rgb), 0.05);
            color: var(--text-main);
            border-color: var(--primary-color);
        }

        [x-cloak] { display: none !important; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(var(--primary-rgb), 0.2); border-radius: 10px; }

        /* Flatpickr Custom Theme */
        .flatpickr-calendar {
            background: var(--card-bg) !important;
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
        }
        .flatpickr-day { color: var(--text-main) !important; border-radius: 8px !important; }
        .flatpickr-day.today { border-color: var(--primary-color) !important; }
        .flatpickr-day.selected, .flatpickr-day.selected:hover { background: var(--primary-color) !important; border-color: var(--primary-color) !important; color: white !important; }
        .flatpickr-day:hover { background: rgba(var(--primary-rgb), 0.1) !important; }
        .flatpickr-months .flatpickr-month, .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month {
            color: var(--text-main) !important;
            fill: var(--text-main) !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months { 
            font-weight: bold !important; 
            background: transparent !important;
            color: var(--text-main) !important;
            border-radius: 6px;
            padding: 2px 4px;
        }
        .flatpickr-monthDropdown-months:hover { background: rgba(var(--primary-rgb), 0.1) !important; }
        .flatpickr-monthDropdown-month {
            background-color: var(--sidebar-bg) !important;
            color: var(--text-main) !important;
        }
        .numInputWrapper span.arrowUp:after { border-bottom-color: var(--text-main) !important; }
        .numInputWrapper span.arrowDown:after { border-top-color: var(--text-main) !important; }
        .flatpickr-current-month input.cur-year { font-weight: bold !important; color: var(--text-main) !important; }
        
        .flatpickr-weekday { color: var(--text-muted) !important; font-weight: 800 !important; font-size: 10px !important; text-transform: uppercase; }
        span.flatpickr-weekday { color: var(--text-muted) !important; }

        /* ── DataTables Dark Theme ── */
        table.dataTable { border-collapse: collapse !important; width: 100% !important; }
        .dt-container { padding: 0 !important; }
        .dt-container .dt-search { padding: 1rem 2rem 0 !important; }
        .dt-container .dt-search label { font-size: 0; }
        .dt-container .dt-search input {
            background: var(--input-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
            border-radius: 0.75rem !important;
            padding: 0.6rem 1rem !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            width: 260px !important;
            outline: none !important;
            transition: border-color 0.2s;
        }
        .dt-container .dt-search input::placeholder { color: var(--text-muted) !important; opacity: 0.6; }
        .dt-container .dt-search input:focus { border-color: var(--primary-color) !important; box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1) !important; }
        .dt-container .dt-length { padding: 1rem 2rem 0 !important; }
        .dt-container .dt-length label { color: var(--text-muted) !important; font-size: 0.7rem !important; font-weight: 600 !important; text-transform: uppercase; letter-spacing: 0.1em; }
        .dt-container .dt-length select {
            background: var(--input-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
            border-radius: 0.5rem !important;
            padding: 0.35rem 0.5rem !important;
            font-size: 0.75rem !important;
            margin: 0 0.3rem !important;
        }
        .dt-container .dt-info { padding: 1rem 2rem !important; color: var(--text-muted) !important; font-size: 0.65rem !important; font-weight: 600 !important; text-transform: uppercase; letter-spacing: 0.05em; }
        .dt-container .dt-paging { padding: 0.75rem 2rem 1.5rem !important; }
        .dt-container .dt-paging .dt-paging-button {
            background: transparent !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-muted) !important;
            border-radius: 0.5rem !important;
            padding: 0.35rem 0.7rem !important;
            font-size: 0.7rem !important;
            font-weight: 600 !important;
            margin: 0 2px !important;
            transition: all 0.2s !important;
        }
        .dt-container .dt-paging .dt-paging-button:hover { background: rgba(var(--primary-rgb), 0.1) !important; color: var(--primary-color) !important; border-color: rgba(var(--primary-rgb), 0.3) !important; }
        .dt-container .dt-paging .dt-paging-button.current { background: var(--primary-color) !important; color: #fff !important; border-color: var(--primary-color) !important; }
        .dt-container .dt-paging .dt-paging-button.disabled { opacity: 0.3 !important; cursor: default !important; }
        table.dataTable thead th { border-bottom: 1px solid var(--border-color) !important; background: transparent !important; cursor: pointer; position: relative; }
        table.dataTable thead th:after, table.dataTable thead th:before { opacity: 0.3 !important; }
        table.dataTable thead th.dt-ordering-asc:after, table.dataTable thead th.dt-ordering-desc:before { opacity: 1 !important; color: var(--primary-color) !important; }
        table.dataTable tbody tr { border-bottom: 1px solid var(--border-color) !important; transition: background 0.15s; }
        table.dataTable tbody tr:hover { background: rgba(var(--primary-rgb), 0.04) !important; }
        table.dataTable tbody tr:last-child { border-bottom: none !important; }
        table.dataTable tbody td { border: none !important; vertical-align: middle !important; }
        .dt-container .dt-layout-row { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        table.dataTable.no-footer { border-bottom: none !important; }
        table.dataTable thead .dt-column-order { display: none !important; }
    </style>
</head>
<body class="h-full" 
      x-data="{ 
        sidebarOpen: false,
        scrolled: false,
        currentTheme: localStorage.getItem('ppdb_theme') || 'sky',
        currentBg: localStorage.getItem('ppdb_bg') || 'ocean',
        themes: {
            indigo: { color: '#6366f1', rgb: '99, 102, 241' },
            emerald: { color: '#10b981', rgb: '16, 185, 129' },
            rose: { color: '#f43f5e', rgb: '244, 63, 94' },
            amber: { color: '#f59e0b', rgb: '245, 158, 11' },
            sky: { color: '#0ea5e9', rgb: '14, 165, 233' }
        },
        bgThemes: {
            softlight: { color: '#f8fafc', name: 'Soft Light', text: '#334155', muted: '#64748b', card: 'rgba(255,255,255,0.8)', border: 'rgba(0,0,0,0.06)', sidebar: 'rgba(255, 255, 255, 0.8)', header: 'rgba(255, 255, 255, 0.6)', input: '#ffffff', isLight: true },
            midnight: { color: '#020617', name: 'Midnight', text: '#f1f5f9', muted: '#64748b', card: 'rgba(255,255,255,0.03)', border: 'rgba(255,255,255,0.08)', sidebar: 'rgba(15, 23, 42, 0.4)', header: 'rgba(255,255,255,0.03)', input: 'rgba(0,0,0,0.2)', isLight: false },
            ocean: { color: '#081b33', name: 'Ocean', text: '#f1f5f9', muted: '#94a3b8', card: 'rgba(255,255,255,0.04)', border: 'rgba(255,255,255,0.1)', sidebar: 'rgba(7, 25, 48, 0.5)', header: 'rgba(255,255,255,0.04)', input: 'rgba(0,0,0,0.2)', isLight: false },
            royal: { color: '#1a0b32', name: 'Royal', text: '#f1f5f9', muted: '#a5b4fc', card: 'rgba(255,255,255,0.05)', border: 'rgba(255,255,255,0.12)', sidebar: 'rgba(22, 9, 44, 0.6)', header: 'rgba(255,255,255,0.05)', input: 'rgba(0,0,0,0.2)', isLight: false }
        },
        setTheme(name) {
            this.currentTheme = name;
            localStorage.setItem('ppdb_theme', name);
            this.applyTheme();
        },
        setBg(name) {
            this.currentBg = name;
            localStorage.setItem('ppdb_bg', name);
            this.applyTheme();
        },
        applyTheme() {
            const theme = this.themes[this.currentTheme];
            const bg = this.bgThemes[this.currentBg];
            const root = document.documentElement.style;
            root.setProperty('--primary-color', theme.color);
            root.setProperty('--primary-rgb', theme.rgb);
            root.setProperty('--surface-color', bg.color);
            root.setProperty('--text-main', bg.text);
            root.setProperty('--text-muted', bg.muted);
            root.setProperty('--card-bg', bg.card);
            root.setProperty('--border-color', bg.border);
            root.setProperty('--sidebar-bg', bg.sidebar);
            root.setProperty('--header-bg', bg.header);
            root.setProperty('--input-bg', bg.input);
        }
      }"
      x-init="applyTheme(); window.addEventListener('scroll', () => scrolled = window.scrollY > 20)"
>
<div class="flex h-full min-h-screen">
    {{-- Mobile Overlay --}}
    <div x-show="sidebarOpen" 
         x-cloak
         x-transition:enter="transition opacity-100 duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:leave="transition opacity-0 duration-300"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 w-64 backdrop-blur-2xl border-r flex flex-col z-50 transition-all duration-500 ease-in-out lg:translate-x-0"
           :style="'background: var(--sidebar-bg); border-color: var(--border-color)'">
        
        {{-- Logo --}}
        <div class="px-6 py-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-xl transition-all duration-700 hover:rotate-12 overflow-hidden"
                     :style="'background: linear-gradient(135deg, ' + themes[currentTheme].color + ', #a855f7)'">
                    @if(\App\Models\Setting::get('app_logo'))
                        <img src="{{ Storage::url(\App\Models\Setting::get('app_logo')) }}" alt="Logo" class="w-full h-full object-cover">
                    @else
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-extrabold themed-text tracking-tight uppercase">{{ \App\Models\Setting::get('app_name', 'PPDB Online') }}</p>
                    <p class="text-[10px] font-medium themed-text-muted uppercase tracking-[0.2em] mt-1">Pendaftaran Siswa</p>
                </div>
            </div>
        </div>

        {{-- Nav Menu --}}
        <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto">
            @if(auth()->user()->role === \App\Models\User::ROLE_SISWA)
                @include('layouts.partials.nav-siswa')
            @endif

            @if(auth()->user()->isAdmin())
                @include('layouts.partials.nav-admin')

                @if(auth()->user()->isSuperAdmin())
                    @include('layouts.partials.nav-super-admin')
                @endif
            @endif
        </nav>

        {{-- User Info --}}
        <div class="p-6">
            <div class="p-4 rounded-2xl border space-y-4" :style="'background: var(--card-bg); border-color: var(--border-color)'">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold text-white transition-all duration-700"
                         :style="'background: linear-gradient(135deg, ' + themes[currentTheme].color + ', #a855f7)'">
                        {{ strtoupper(substr(auth()->user()->full_name ?? auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold themed-text truncate">{{ auth()->user()->full_name ?? auth()->user()->name }}</p>
                        <p class="text-[10px] themed-text-muted truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full h-10 flex items-center justify-center gap-2 rounded-xl text-xs font-semibold themed-text-muted hover:bg-red-500/10 hover:text-red-400 transition-all border border-transparent hover:border-red-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar Aplikasi
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 lg:ml-64 flex flex-col min-h-screen">
        {{-- Floating Header Container --}}
        <div class="sticky top-0 z-40 px-4 pt-4 sm:px-8">
            <header class="floating-header rounded-2xl px-6 py-4 flex items-center justify-between transition-all duration-500 relative"
                    :class="scrolled ? 'scrolled' : ''">
                {{-- Subtle Glow Effect (Clipped) --}}
                <div class="absolute inset-0 rounded-2xl overflow-hidden pointer-events-none">
                    <div class="absolute -top-10 -right-10 w-32 h-32 blur-3xl opacity-20 transition-all duration-700" 
                         :style="'background:' + themes[currentTheme].color"></div>
                </div>

                <div class="flex items-center gap-4 relative z-10">
                    {{-- Hamburger --}}
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-400 hover:bg-black/5 transition-all" :style="'background: var(--border-color)'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-base sm:text-xl font-bold themed-text tracking-tight">@yield('page-title', 'Dashboard')</h1>
                        <p class="hidden sm:block text-[10px] uppercase tracking-widest themed-text-muted font-semibold">@yield('page-subtitle', 'Selamat Datang')</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 relative z-10">
                    {{-- Compact Theme Switcher --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center gap-2 p-2 px-3 rounded-xl text-xs font-bold transition-all btn-soft-secondary">
                            <span class="w-4 h-4 rounded-lg flex items-center justify-center" :style="'background:' + themes[currentTheme].color + '33'">
                                <span class="w-2 h-2 rounded-full" :style="'background:' + themes[currentTheme].color"></span>
                            </span>
                            <span class="hidden sm:inline">Pengaturan Tema</span>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             class="absolute right-0 mt-4 w-64 border rounded-3xl shadow-2xl p-4 space-y-5 z-[60]"
                             :style="'background: var(--surface-color); border-color: var(--border-color)'">
                            
                            <div>
                                <p class="text-[10px] font-bold themed-text-muted uppercase tracking-widest mb-3">Warna Aksen</p>
                                <div class="grid grid-cols-5 gap-2">
                                    <template x-for="(theme, name) in themes" :key="name">
                                        <button @click="setTheme(name)" 
                                                class="w-full aspect-square rounded-xl transition-all duration-300 transform hover:scale-110 flex items-center justify-center"
                                                :style="'background: ' + theme.color"
                                                :class="currentTheme === name ? 'ring-2 ring-primary ring-offset-4' : ''"
                                                :style="'ring-offset-color: var(--surface-color)'">
                                            <svg x-show="currentTheme === name" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div class="pt-4 border-t" :style="'border-color: var(--border-color)'">
                                <p class="text-[10px] font-bold themed-text-muted uppercase tracking-widest mb-3">Latar Belakang</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <template x-for="(bg, name) in bgThemes" :key="name">
                                        <button @click="setBg(name)" 
                                                class="w-full py-2 rounded-xl text-[10px] font-bold transition-all border flex items-center px-2 gap-2"
                                                :class="currentBg === name ? 'bg-primary/10 text-primary' : 'themed-text-muted hover:bg-primary/5'"
                                                :style="'border-color: var(--border-color)'">
                                            <span class="w-3 h-3 rounded-md" :style="'background:' + bg.color"></span>
                                            <span x-text="bg.name"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2 h-10 px-4 rounded-xl border font-bold transition-all duration-700"
                         :style="'background: var(--card-bg); border-color: ' + themes[currentTheme].color + '33; color: ' + themes[currentTheme].color">
                        <span class="w-2 h-2 rounded-full animate-pulse" :style="'background:' + themes[currentTheme].color"></span>
                        <span class="text-xs uppercase tracking-widest">{{ auth()->user()->educationalLevel?->name ?? 'CALON' }}</span>
                    </div>
                </div>
            </header>
        </div>

        <div class="p-6 sm:p-8 flex-1">
            @if (session('status'))
                <div class="mb-8 px-6 py-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="p-2 bg-emerald-500/20 rounded-xl">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            @endif
            
            <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 themed-text">
                @yield('content')
            </div>
        </div>
        
        <footer class="p-8 text-center border-t" :style="'border-color: var(--border-color)'">
            <p class="text-[10px] font-bold themed-text-muted uppercase tracking-[0.3em]">PPDB APP &copy; 2025 - Professional Registration System</p>
        </footer>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr(".datepicker", {
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
            disableMobile: "true",
            animate: true,
            monthSelectorType: "dropdown",
            yearSelectorType: "dropdown"
        });

        // Auto-init DataTables on all tables with class 'datatable'
        $('.datatable').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    pageLength: 25,
                    ordering: true,
                    language: {
                        search: '',
                        searchPlaceholder: '🔍  Cari data...',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                        infoEmpty: 'Tidak ada data',
                        infoFiltered: '(dari _MAX_ total data)',
                        zeroRecords: 'Data tidak ditemukan',
                        emptyTable: 'Belum ada data',
                        paginate: { first: '«', previous: '‹', next: '›', last: '»' }
                    }
                });
            }
        });
    });
</script>
@if (session('swal_error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: '{{ session('swal_error') }}',
            confirmButtonColor: 'var(--primary-color)'
        });
    });
</script>
@endif
@yield('scripts')
</body>
</html>
