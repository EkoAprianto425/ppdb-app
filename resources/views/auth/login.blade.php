@extends('layouts.guest')

@section('title', 'Masuk ke Akun')

@section('content')
<div class="min-h-screen bg-transparent flex items-center justify-center p-6">

    {{-- Background blobs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-amber-500/10 rounded-full filter blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-orange-600/10 rounded-full filter blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-700/20 to-amber-900/20 border border-amber-900/10 shadow-xl shadow-amber-900/5 mb-4 overflow-hidden">
                @if(\App\Models\Setting::get('app_logo'))
                    <img src="{{ Storage::url(\App\Models\Setting::get('app_logo')) }}" alt="Logo" class="w-full h-full object-cover">
                @else
                    <svg class="w-7 h-7 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                @endif
            </div>
            <h1 class="text-2xl font-bold text-amber-950">Selamat Datang Kembali</h1>
            <p class="text-amber-800/70 text-sm mt-1">Masuk ke akun {{ \App\Models\Setting::get('app_name', 'PPDB Online') }} Anda</p>
        </div>

        {{-- Card --}}
        <div class="bg-white/60 backdrop-blur-xl border border-amber-900/10 rounded-2xl p-8 shadow-2xl shadow-amber-900/5">

            @if (session('status'))
                <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-amber-900 mb-1.5">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autofocus autocomplete="username"
                           placeholder="email@contoh.com"
                           class="w-full px-4 py-3 rounded-xl bg-white border {{ $errors->has('email') ? 'border-red-500/50' : 'border-amber-900/20' }} text-amber-950 text-sm placeholder-amber-900/40 focus:outline-none focus:border-amber-700 focus:ring-1 focus:ring-amber-700/30 transition-all shadow-sm">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="text-xs font-medium text-amber-900">Password</label>
                        @if (Route::has('password.request'))
                            <a href="#" onclick="document.getElementById('forgotPasswordModal').classList.remove('hidden'); return false;" class="text-xs text-amber-700 hover:text-amber-600 transition-colors">Lupa password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <input id="password" type="password" name="password" autocomplete="current-password"
                               placeholder="Masukkan password"
                               class="w-full px-4 py-3 pr-10 rounded-xl bg-white border {{ $errors->has('password') ? 'border-red-500/50' : 'border-amber-900/20' }} text-amber-950 text-sm placeholder-amber-900/40 focus:outline-none focus:border-amber-700 focus:ring-1 focus:ring-amber-700/30 transition-all shadow-sm">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-amber-900/50 hover:text-amber-700" onclick="const input = this.previousElementSibling; if(input.type === 'password') { input.type = 'text'; this.innerHTML = '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\'/></svg>' } else { input.type = 'password'; this.innerHTML = '<svg class=\'w-5 h-5\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\'/><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\'/></svg>' }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="w-4 h-4 rounded bg-white border-amber-900/30 text-amber-700 focus:ring-amber-700/30 focus:ring-offset-white">
                    <label for="remember_me" class="text-sm text-amber-900 cursor-pointer">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button id="btn-login" type="submit"
                        class="w-full py-3 px-6 rounded-xl bg-gradient-to-r from-amber-700 to-amber-900 text-white font-semibold text-sm hover:from-amber-600 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-700/50 transition-all duration-200 shadow-lg shadow-amber-900/20 active:scale-[0.99]">
                    Masuk ke Akun
                </button>
            </form>

            @if (Route::has('register'))
            <p class="text-center text-sm text-amber-900/70 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-amber-700 hover:text-amber-600 font-medium transition-colors">Daftar sekarang</a>
            </p>
            @endif
        </div>

        <p class="text-center text-xs text-amber-900/50 mt-6">
            {{ \App\Models\Setting::get('footer_copyright', '© ' . date('Y') . ' Yayasan Pendidikan Nusantara. All rights reserved.') }}
        </p>
    </div>
</div>

    {{-- Forgot Password Modal --}}
    <div id="forgotPasswordModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-amber-950/40 backdrop-blur-sm transition-opacity" style="margin:0;">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all border border-amber-900/10">
            <div class="px-6 py-5 border-b border-amber-900/10 flex items-center justify-between bg-amber-50/50">
                <h3 class="text-lg font-semibold text-amber-950">Lupa Password</h3>
                <button type="button" onclick="document.getElementById('forgotPasswordModal').classList.add('hidden')" class="text-amber-900/50 hover:text-amber-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="px-6 py-6">
                <div class="mb-5 px-4 py-3 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-700 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Untuk mereset password Anda, harap hubungi Admin sekolah melalui WhatsApp di bawah ini:</p>
                </div>
                
                <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($adminWaNumbers as $admin)
                        @php
                            $waNumber = preg_replace('/[^0-9]/', '', $admin->whatsapp_number);
                            if (str_starts_with($waNumber, '0')) {
                                $waNumber = '62' . substr($waNumber, 1);
                            }
                            $roleName = ucwords(str_replace('_', ' ', $admin->role));
                            $roleName = str_replace(['Smp', 'Sma', 'Smk', 'Adm'], ['SMP', 'SMA', 'SMK', 'Administrasi'], $roleName);
                        @endphp
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="flex items-center gap-3 p-3 rounded-xl border border-amber-900/10 hover:bg-amber-50 hover:border-amber-900/30 transition-all group">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 1.745.448 3.39 1.226 4.832L2 22l5.342-1.189A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zM9.54 8.784c.15-.333.308-.34.45-.346.136-.006.291-.006.446-.006.155 0 .408-.058.621.408.214.466.738 1.808.806 1.944.068.136.116.292.039.447-.078.155-.117.253-.233.389-.117.136-.253.311-.35.408-.117.117-.243.243-.107.477.136.233.602 1.002 1.293 1.621.886.793 1.63 1.04 1.864 1.157.233.117.37.097.505-.058.136-.155.583-.68.738-.914.155-.233.31-.194.524-.117.214.078 1.36.642 1.593.758.233.117.389.175.447.272.058.097.058.564-.175 1.108-.233.544-1.36 1.069-1.884 1.088-.524.02-1.04-.155-2.914-.894-2.253-.894-3.71-3.187-3.827-3.342-.117-.155-.913-1.224-.913-2.332s.583-1.652.797-1.885c.214-.233.466-.291.622-.291z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-amber-950 group-hover:text-amber-700 transition-colors">{{ $admin->name }}</h4>
                                <p class="text-xs text-amber-800/70">{{ $roleName }} - {{ $admin->whatsapp_number }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-6 text-amber-900/50 text-sm">
                            Belum ada kontak Admin yang tersedia.
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="px-6 py-4 border-t border-amber-900/10 bg-amber-50/30 flex justify-end">
                <button type="button" onclick="document.getElementById('forgotPasswordModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-amber-900/20 text-amber-900 text-sm font-medium rounded-xl hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-amber-700/30 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection
