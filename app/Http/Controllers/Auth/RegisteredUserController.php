<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterStudentRequest;
use App\Models\User;
use App\Support\AppCache;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $levels  = AppCache::educationalLevelsActive();
        $sources = AppCache::informationSourcesActive();
        $reasons = AppCache::schoolReasonsActive();
        return view('auth.register', compact('levels', 'sources', 'reasons'));
    }

    public function store(RegisterStudentRequest $request): RedirectResponse
    {
        $user = User::create([
            'name'             => $request->name,
            'full_name'        => $request->full_name,
            'email'            => $request->email,
            'whatsapp_number'  => $request->whatsapp_number,
            'password'         => Hash::make($request->password),
            'asal_sekolah'     => $request->asal_sekolah,
            'educational_level_id' => $request->educational_level_id,
            'alasan_memilih'   => $request->alasan_memilih,
            'sumber_informasi' => $request->sumber_informasi,
            'sumber_informasi_tambahan' => $request->sumber_informasi_tambahan,
            'role'             => 'siswa',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
