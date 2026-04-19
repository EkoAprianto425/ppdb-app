<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Http\Request;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Registration::with('user', 'academicYear', 'registrationWave');

        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            $query->whereHas('user', function($q) use ($levelIds) {
                $q->whereIn('educational_level_id', $levelIds);
            });
        }

        $registrations = $query->latest()->get();

        return view('admin.students.index', compact('registrations'));
    }

    public function show(Registration $registration)
    {
        $this->authorizeAccess($registration);
        return view('admin.students.show', compact('registration'));
    }

    public function edit(Registration $registration)
    {
        $this->authorizeAccess($registration);
        return view('admin.students.edit', compact('registration'));
    }

    public function update(Request $request, Registration $registration)
    {
        $this->authorizeAccess($registration);

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

        // Clean currency
        $validated['penghasilan_ayah'] = (int) preg_replace('/[^0-9]/', '', $validated['penghasilan_ayah']);
        $validated['penghasilan_ibu'] = (int) preg_replace('/[^0-9]/', '', $validated['penghasilan_ibu']);

        $registration->update($validated);

        return redirect()->route('admin.students.show', $registration)->with('status', 'Biodata siswa berhasil diperbarui.');
    }

    public function transfer(Request $request, Registration $registration)
    {
        $this->authorizeAccess($registration);
        
        $user = auth()->user();
        if ($user->role === User::ROLE_ADMIN_SMP) {
            abort(403, 'Unit SMP tidak diperbolehkan melakukan pindah jenjang.');
        }

        $request->validate([
            'unit' => 'required|exists:educational_levels,id'
        ]);

        $registration->user->update([
            'educational_level_id' => $request->unit
        ]);

        return redirect()->route('admin.students.index')->with('status', 'Siswa berhasil dipindahkan ke jenjang ' . $request->unit);
    }

    public function graduationIndex(Request $request)
    {
        $user = auth()->user();
        $query = Registration::with('user', 'academicYear', 'registrationWave');

        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            $query->whereHas('user', function($q) use ($levelIds) {
                $q->whereIn('educational_level_id', $levelIds);
            });
        }

        $registrations = $query->latest()->get();

        return view('admin.students.graduation', compact('registrations'));
    }

    public function updateStatus(Request $request, Registration $registration)
    {
        $this->authorizeAccess($registration);

        $rules = [
            'status' => 'required|in:lulus,tidak_lulus,proses'
        ];

        if ($request->status === 'lulus') {
            $rules['reregistration_deadline'] = 'required|date';
        }

        $request->validate($rules);

        $registration->update([
            'status' => $request->status,
            'reregistration_deadline' => $request->status === 'lulus' ? $request->reregistration_deadline : null
        ]);

        return back()->with('status', 'Status kelulusan siswa berhasil diperbarui menjadi: ' . strtoupper($request->status));
    }

    private function authorizeAccess(Registration $registration)
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            if (!in_array($registration->user->educational_level_id, $levelIds)) {
                abort(403, 'Anda tidak memiliki akses ke data siswa unit lain.');
            }
        }
    }
}
