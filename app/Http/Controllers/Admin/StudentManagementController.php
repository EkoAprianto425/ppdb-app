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
        
        // Base Query: Start from User to include "Tamu" (registered only)
        $query = User::where('role', User::ROLE_SISWA)
            ->with(['registration.payments', 'registration.examSchedule', 'educationalLevel', 'registration.registrationWave']);

        // Filter Jenjang (Tujuan) - Terutama untuk Super Admin
        if ($request->filled('level_id')) {
            $query->where('educational_level_id', $request->level_id);
        }

        // Filter Scope Admin Unit
        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            $query->whereIn('educational_level_id', $levelIds);
        }

        $students = $query->latest()->get();
        
        // Ambil data fees untuk menentukan status
        $fees = \App\Models\AdministrativeFee::all()->groupBy('educational_level_id');
        $levels = \App\Models\EducationalLevel::all();

        $students->each(function($student) use ($fees) {
            $student->ppdb_status = $this->calculateStatus($student, $fees);
        });

        // Filter Status PPDB
        if ($request->filled('status')) {
            $students = $students->filter(function($student) use ($request) {
                return $student->ppdb_status == $request->status;
            });
        }

        return view('admin.students.index', compact('students', 'levels'));
    }

    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $query = User::where('role', User::ROLE_SISWA)
            ->with(['registration.payments', 'registration.examSchedule', 'educationalLevel', 'registration.registrationWave']);

        if ($request->filled('level_id')) {
            $query->where('educational_level_id', $request->level_id);
        }

        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            $query->whereIn('educational_level_id', $levelIds);
        }

        $students = $query->latest()->get();
        $fees = \App\Models\AdministrativeFee::all()->groupBy('educational_level_id');

        $students->each(function($student) use ($fees) {
            $student->ppdb_status = $this->calculateStatus($student, $fees);
        });

        if ($request->filled('status')) {
            $students = $students->filter(fn($s) => $s->ppdb_status == $request->status);
        }

        $fileName = 'Data_Pendaftar_PPDB_' . date('Y-m-d_H-i') . '.xls';

        $headers = [
            "Content-Type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'No', 'Tgl Daftar Akun', 'Nama Pembuat Akun', 'Nama Lengkap', 'Nama Panggilan', 'Email', 'No. WhatsApp', 
            'Asal Sekolah', 'Alasan Memilih', 'Sumber Informasi', 'Jenjang Tujuan', 'Tahun Ajaran', 'Gelombang', 
            'Status PPDB', 'Status Kelulusan', 'Deadline Daftar Ulang', 'Tempat Lahir', 'Tanggal Lahir', 
            'Jenis Kelamin', 'Agama', 'Alamat', 'Provinsi', 'Kabupaten', 'Kecamatan', 'Kebutuhan Khusus',
            'Anak Ke', 'Dari Saudara', 'Nama Ayah', 'Pendidikan Ayah', 'Pekerjaan Ayah', 'Penghasilan Ayah', 
            'Nama Ibu', 'Pendidikan Ibu', 'Pekerjaan Ibu', 'Penghasilan Ibu', 'Jadwal Ujian'
        ];

        $callback = function() use($students, $columns) {
            echo "<html><head><meta charset='UTF-8'></head><body>";
            echo "<table border='1'>";
            
            // Header
            echo "<tr>";
            foreach ($columns as $column) {
                echo "<th style='background-color: #f2f2f2; font-weight: bold;'>$column</th>";
            }
            echo "</tr>";

            // Data
            foreach ($students as $key => $student) {
                $reg = $student->registration;
                echo "<tr>";
                echo "<td>" . ($key + 1) . "</td>";
                echo "<td>" . $student->created_at->format('d/m/Y H:i') . "</td>";
                echo "<td>" . ($student->name ?? '-') . "</td>";
                echo "<td>" . ($student->full_name ?? $student->name) . "</td>";
                echo "<td>" . ($reg?->nama_panggilan ?? '-') . "</td>";
                echo "<td>" . $student->email . "</td>";
                echo "<td>&nbsp;" . $student->whatsapp_number . "</td>"; // Use &nbsp; to prevent number formatting
                echo "<td>" . ($student->asal_sekolah ?? '-') . "</td>";
                echo "<td>" . ($student->alasan_memilih ?? '-') . "</td>";
                echo "<td>" . ($student->sumber_informasi ?? '-') . "</td>";
                echo "<td>" . ($student->educationalLevel?->name ?? '-') . "</td>";
                echo "<td>" . ($reg?->academicYear?->name ?? '-') . "</td>";
                echo "<td>" . ($reg?->registrationWave?->name ?? '-') . "</td>";
                echo "<td>" . $student->ppdb_status . "</td>";
                echo "<td>" . strtoupper($reg?->status ?? 'PROSES') . "</td>";
                echo "<td>" . ($reg?->reregistration_deadline ? date('d/m/Y', strtotime($reg->reregistration_deadline)) : '-') . "</td>";
                echo "<td>" . ($reg?->tempat_lahir ?? '-') . "</td>";
                echo "<td>" . ($reg?->tanggal_lahir ?? '-') . "</td>";
                echo "<td>" . ($reg?->jenis_kelamin ?? '-') . "</td>";
                echo "<td>" . ($reg?->agama ?? '-') . "</td>";
                echo "<td>" . ($reg?->alamat ?? '-') . "</td>";
                echo "<td>" . ($reg?->provinsi ?? '-') . "</td>";
                echo "<td>" . ($reg?->kabupaten ?? '-') . "</td>";
                echo "<td>" . ($reg?->kecamatan ?? '-') . "</td>";
                echo "<td>" . ($reg?->kebutuhan_khusus ?? '-') . "</td>";
                echo "<td>" . ($reg?->anak_ke ?? '-') . "</td>";
                echo "<td>" . ($reg?->dari_saudara ?? '-') . "</td>";
                echo "<td>" . ($reg?->nama_ayah ?? '-') . "</td>";
                echo "<td>" . ($reg?->pendidikan_ayah ?? '-') . "</td>";
                echo "<td>" . ($reg?->pekerjaan_ayah ?? '-') . "</td>";
                echo "<td>" . ($reg?->penghasilan_ayah ? 'Rp ' . number_format($reg->penghasilan_ayah, 0, ',', '.') : '-') . "</td>";
                echo "<td>" . ($reg?->nama_ibu ?? '-') . "</td>";
                echo "<td>" . ($reg?->pendidikan_ibu ?? '-') . "</td>";
                echo "<td>" . ($reg?->pekerjaan_ibu ?? '-') . "</td>";
                echo "<td>" . ($reg?->penghasilan_ibu ? 'Rp ' . number_format($reg->penghasilan_ibu, 0, ',', '.') : '-') . "</td>";
                echo "<td>" . ($reg?->examSchedule ? $reg->examSchedule->date . ' ' . substr($reg->examSchedule->time_start, 0, 5) : '-') . "</td>";
                echo "</tr>";
            }

            echo "</table></body></html>";
        };

        return response()->stream($callback, 200, $headers);
    }

    private function calculateStatus($user, $feesGrouped)
    {
        $reg = $user->registration;
        if (!$reg) return 'tamu';

        $levelFees = $feesGrouped->get($user->educational_level_id) ?? collect();
        $formulirFeeName = $levelFees->where('sort_order', 1)->first()?->name;
        
        $successPayments = $reg->payments->where('status', 'success');

        // 1. daftar: sudah membayar selain formulir (sort_order > 1)
        $otherFeeNames = $levelFees->where('sort_order', '>', 1)->pluck('name')->toArray();
        if ($successPayments->whereIn('fee_type', $otherFeeNames)->isNotEmpty()) {
            return 'daftar';
        }

        // 2. Lulus: sudah dinyatakan lulus, tapi belum bayar daftar ulang
        if ($reg->status === 'lulus') {
            return 'lulus';
        }

        // 3. Formulir: sudah membayar formulir
        if ($formulirFeeName && $successPayments->where('fee_type', $formulirFeeName)->isNotEmpty()) {
            return 'formulir';
        }

        return 'tamu';
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
        $query = Registration::with('user', 'academicYear', 'registrationWave', 'user.educationalLevel')
            ->where('payment_status', 'success');

        // Filter Status Kelulusan (proses, lulus, tidak_lulus)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Jenjang (Super Admin)
        if ($request->filled('level_id')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('educational_level_id', $request->level_id);
            });
        }

        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            $query->whereHas('user', function($q) use ($levelIds) {
                $q->whereIn('educational_level_id', $levelIds);
            });
        }

        $registrations = $query->latest()->get();
        $levels = \App\Models\EducationalLevel::orderBy('sort_order')->get();

        return view('admin.students.graduation', compact('registrations', 'levels'));
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
            if ($registration->user && !in_array($registration->user->educational_level_id, $levelIds)) {
                abort(403, 'Anda tidak memiliki akses ke data siswa unit lain.');
            }
        }
    }
}
