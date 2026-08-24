<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $adminWaNumbers = \App\Models\User::whereIn('role', [
            \App\Models\User::ROLE_ADMIN_SMP,
            \App\Models\User::ROLE_ADMIN_SMA,
            \App\Models\User::ROLE_ADMIN_SMK,
            \App\Models\User::ROLE_ADMIN_ADM
        ])->whereNotNull('whatsapp_number')->where('whatsapp_number', '!=', '')->orderBy('role', 'asc')->get();

        return view('auth.login', compact('adminWaNumbers'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->user()->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
