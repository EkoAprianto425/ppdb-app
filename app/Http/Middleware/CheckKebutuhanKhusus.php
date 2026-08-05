<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckKebutuhanKhusus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->registration) {
            if ($user->registration->kebutuhan_khusus !== 'Tidak Ada') {
                return redirect()->route('pendaftaran.index')
                                 ->with('swal_error', 'Anda tidak dapat melanjutkan ke menu ini karena memiliki keterangan berkebutuhan khusus.');
            }
        }

        return $next($request);
    }
}
