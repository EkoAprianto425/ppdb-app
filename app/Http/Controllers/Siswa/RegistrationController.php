<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationWave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function index()
    {
        $registration = Auth::user()->registration;
        
        if (!$registration) {
            return redirect()->route('pendaftaran.create');
        }

        return view('pendaftaran.show', compact('registration'));
    }

    public function create()
    {
        if (Auth::user()->registration) {
            return redirect()->route('pendaftaran.index');
        }

        return view('pendaftaran.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_panggilan'   => 'required|string|max:50',
            'anak_ke'          => 'required|integer|min:1',
            'dari_saudara'     => 'required|integer|min:1',
            'alamat'           => 'required|string',
            'provinsi'         => 'required|string',
            'kabupaten'        => 'required|string',
            'kecamatan'        => 'required|string',
            'kebutuhan_khusus' => 'required|string',
            'tempat_lahir'     => 'required|string',
            'tanggal_lahir'    => 'required|date',
            'agama'            => 'required|string',
            'jenis_kelamin'    => 'required|in:Laki-laki,Perempuan',
            'nama_ayah'        => 'required|string',
            'nama_ibu'         => 'required|string',
            'pekerjaan_ayah'   => 'required|string',
            'pekerjaan_ibu'    => 'required|string',
            'pendidikan_ayah'  => 'required|string',
            'pendidikan_ibu'   => 'required|string',
            'penghasilan_ayah' => 'required|string',
            'penghasilan_ibu'  => 'required|string',
        ]);

        // Clean currency formatting for database
        $validated['penghasilan_ayah'] = (int) preg_replace('/[^0-9]/', '', $validated['penghasilan_ayah']);
        $validated['penghasilan_ibu'] = (int) preg_replace('/[^0-9]/', '', $validated['penghasilan_ibu']);
        
        $validated['user_id'] = Auth::id();
        
        $activeWave = RegistrationWave::where('is_active', true)->first();
        if ($activeWave) {
            $validated['registration_wave_id'] = $activeWave->id;
        }

        Registration::create($validated);

        return redirect()->route('pendaftaran.index')->with('status', 'Formulir pendaftaran berhasil disimpan!');
    }

    public function edit()
    {
        $registration = Auth::user()->registration;

        if (!$registration) {
            return redirect()->route('pendaftaran.create');
        }

        return view('pendaftaran.form', compact('registration'));
    }

    public function update(Request $request)
    {
        $registration = Auth::user()->registration;

        $validated = $request->validate([
            'nama_panggilan'   => 'required|string|max:50',
            'anak_ke'          => 'required|integer|min:1',
            'dari_saudara'     => 'required|integer|min:1',
            'alamat'           => 'required|string',
            'provinsi'         => 'required|string',
            'kabupaten'        => 'required|string',
            'kecamatan'        => 'required|string',
            'kebutuhan_khusus' => 'required|string',
            'tempat_lahir'     => 'required|string',
            'tanggal_lahir'    => 'required|date',
            'agama'            => 'required|string',
            'jenis_kelamin'    => 'required|in:Laki-laki,Perempuan',
            'nama_ayah'        => 'required|string',
            'nama_ibu'         => 'required|string',
            'pekerjaan_ayah'   => 'required|string',
            'pekerjaan_ibu'    => 'required|string',
            'pendidikan_ayah'  => 'required|string',
            'pendidikan_ibu'   => 'required|string',
            'penghasilan_ayah' => 'required|string',
            'penghasilan_ibu'  => 'required|string',
        ]);

        $validated['penghasilan_ayah'] = (int) preg_replace('/[^0-9]/', '', $validated['penghasilan_ayah']);
        $validated['penghasilan_ibu'] = (int) preg_replace('/[^0-9]/', '', $validated['penghasilan_ibu']);

        $registration->update($validated);

        return redirect()->route('pendaftaran.index')->with('status', 'Pembaruan formulir berhasil disimpan!');
    }
}
