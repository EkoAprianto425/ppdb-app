<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Registration;
use App\Models\Sekolah;
use App\Support\AppCache;
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

        $students = $query->orderByDesc('id')->get();
        
        // Ambil data fees untuk menentukan status
        $fees   = AppCache::administrativeFeesGrouped();
        $levels = AppCache::educationalLevels();

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
        $fees = AppCache::administrativeFeesGrouped();

        $students->each(function($student) use ($fees) {
            $student->ppdb_status = $this->calculateStatus($student, $fees);
        });

        if ($request->filled('status')) {
            $students = $students->filter(fn($s) => $s->ppdb_status == $request->status);
        }

        $fileName = 'Data_Pendaftar_PPDB_' . date('Y-m-d_H-i') . '.xlsx';

        $columns = [
            'No', 'Tgl Daftar Akun', 'Nama Pembuat Akun', 'Nama Lengkap', 'Nama Panggilan', 'Email', 'No. WhatsApp',
            'Asal Sekolah', 'Alasan Memilih', 'Sumber Informasi', 'Jenjang Tujuan', 'Tahun Ajaran', 'Gelombang',
            'Status PPDB', 'Status Kelulusan', 'Deadline Daftar Ulang', 'Tempat Lahir', 'Tanggal Lahir',
            'Jenis Kelamin', 'Agama', 'Alamat', 'Provinsi', 'Kabupaten', 'Kecamatan', 'Kebutuhan Khusus',
            'Anak Ke', 'Dari Saudara', 'Nama Ayah', 'Pendidikan Ayah', 'Pekerjaan Ayah', 'Penghasilan Ayah',
            'Nama Ibu', 'Pendidikan Ibu', 'Pekerjaan Ibu', 'Penghasilan Ibu', 'Jadwal Ujian'
        ];
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($columns));

        // ── Build spreadsheet ─────────────────────────────────────────────────
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pendaftar');

        // Row 1: Judul
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'LAPORAN DATA PENDAFTAR PPDB');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '0D2137']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Row 2: Tanggal cetak
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Dicetak: ' . now()->format('d F Y, H:i') . ' WIB');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '555555']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F5F5F5']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // Row 3: spacer
        $sheet->getRowDimension(3)->setRowHeight(6);

        // Row 4: Header kolom
        foreach ($columns as $ci => $col) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci + 1) . '4';
            $sheet->setCellValue($cell, $col);
        }
        $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'wrapText'   => true],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                             'color' => ['rgb' => 'FFFFFF']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(22);

        // ── Data rows ─────────────────────────────────────────────────────────
        $rowNum    = 5;
        $zebraEven = ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                 'startColor' => ['rgb' => 'F0F4FB']]];
        $borderData = ['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                       'color' => ['rgb' => 'D0D8E8']]]];

        foreach ($students->values() as $i => $student) {
            $reg = $student->registration;

            $rowData = [
                $i + 1,
                $student->created_at->format('d/m/Y H:i'),
                $student->name ?? '-',
                $student->full_name ?? $student->name,
                $reg?->nama_panggilan ?? '-',
                $student->email,
                "'" . ($student->whatsapp_number ?? ''),  // prefix ' agar tidak diformat angka
                $student->asal_sekolah ?? '-',
                $student->alasan_memilih ?? '-',
                $student->sumber_informasi ?? '-',
                $student->educationalLevel?->name ?? '-',
                $reg?->academicYear?->name ?? '-',
                $reg?->registrationWave?->name ?? '-',
                $student->ppdb_status,
                strtoupper($reg?->status ?? 'PROSES'),
                $reg?->reregistration_deadline ? date('d/m/Y', strtotime($reg->reregistration_deadline)) : '-',
                $reg?->tempat_lahir ?? '-',
                $reg?->tanggal_lahir ?? '-',
                $reg?->jenis_kelamin ?? '-',
                $reg?->agama ?? '-',
                $reg?->alamat ?? '-',
                $reg?->provinsi ?? '-',
                $reg?->kabupaten ?? '-',
                $reg?->kecamatan ?? '-',
                $reg?->kebutuhan_khusus ?? '-',
                $reg?->anak_ke ?? '-',
                $reg?->dari_saudara ?? '-',
                $reg?->nama_ayah ?? '-',
                $reg?->pendidikan_ayah ?? '-',
                $reg?->pekerjaan_ayah ?? '-',
                $reg?->penghasilan_ayah ? 'Rp ' . number_format($reg->penghasilan_ayah, 0, ',', '.') : '-',
                $reg?->nama_ibu ?? '-',
                $reg?->pendidikan_ibu ?? '-',
                $reg?->pekerjaan_ibu ?? '-',
                $reg?->penghasilan_ibu ? 'Rp ' . number_format($reg->penghasilan_ibu, 0, ',', '.') : '-',
                $reg?->examSchedule ? $reg->examSchedule->date . ' ' . substr($reg->examSchedule->time_start, 0, 5) : '-',
            ];

            foreach ($rowData as $ci => $val) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci + 1);
                $sheet->setCellValue("{$colLetter}{$rowNum}", $val);
            }

            $sheet->getStyle("A{$rowNum}")->getAlignment()->setHorizontal('center');

            if ($i % 2 === 1) {
                $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray($zebraEven);
            }
            $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray($borderData);
            $rowNum++;
        }

        // ── Lebar kolom ───────────────────────────────────────────────────────
        $colWidths = [4, 16, 22, 25, 15, 28, 16, 20, 30, 20, 14, 14, 15, 12, 14, 18, 15, 14, 12, 12, 30, 16, 16, 16, 18, 8, 10, 22, 16, 18, 16, 22, 16, 18, 16, 20];
        foreach ($colWidths as $ci => $width) {
            $sheet->getColumnDimensionByColumn($ci + 1)->setWidth($width);
        }

        $sheet->freezePane('A5');

        // ── Output ────────────────────────────────────────────────────────────
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
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

        $namaProvinsi  = $registration->provinsi
            ? AppCache::regionLookup('propinsi', 'kode_prop', $registration->provinsi)
            : null;
        $namaKabupaten = $registration->kabupaten
            ? AppCache::regionLookup('kabupaten_kota', 'kode_kab_kota', $registration->kabupaten)
            : null;
        $namaKecamatan = $registration->kecamatan
            ? AppCache::regionLookup('kecamatan', 'kode_kec', $registration->kecamatan)
            : null;

        return view('admin.students.show', compact('registration', 'namaProvinsi', 'namaKabupaten', 'namaKecamatan'));
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
            // Registrasi Awal (User)
            'full_name'                 => 'required|string|max:255',
            'asal_sekolah'              => 'required|string|max:255',
            'whatsapp_number'           => 'required|string|max:20',
            'alasan_memilih'            => 'required|string',
            'sumber_informasi'          => 'required|string|max:255',
            'sumber_informasi_tambahan' => 'nullable|string|max:255',
            // Biodata (Registration)
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

        // Update User (Registrasi Awal)
        $registration->user->update([
            'full_name'                 => $validated['full_name'],
            'asal_sekolah'              => $validated['asal_sekolah'],
            'whatsapp_number'           => $validated['whatsapp_number'],
            'alasan_memilih'            => $validated['alasan_memilih'],
            'sumber_informasi'          => $validated['sumber_informasi'],
            'sumber_informasi_tambahan' => $validated['sumber_informasi_tambahan'],
        ]);

        // Update Registration (Biodata)
        $registration->update(\Illuminate\Support\Arr::except($validated, [
            'full_name', 'asal_sekolah', 'whatsapp_number', 'alasan_memilih', 'sumber_informasi', 'sumber_informasi_tambahan',
        ]));

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

    public function resetPassword(Request $request, Registration $registration)
    {
        $this->authorizeAccess($registration);

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $registration->user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('status', 'Password siswa berhasil direset.');
    }

    public function graduationIndex(Request $request)
    {
        $user = auth()->user();
        $query = Registration::with('user', 'academicYear', 'registrationWave', 'user.educationalLevel')
            ->where('payment_status', 'success')
            ->whereNotNull('exam_schedule_id');

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
        $levels = AppCache::educationalLevels();

        return view('admin.students.graduation', compact('registrations', 'levels'));
    }

    public function updateStatus(Request $request, Registration $registration)
    {
        $this->authorizeAccess($registration);

        $rules = [
            'status' => 'required|in:lulus,tidak_lulus,proses,mundur'
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

    public function showByUser(User $user)
    {
        $this->authorizeUserAccess($user);
        $registration = $user->registration;
        if ($registration) {
            return redirect()->route('admin.students.show', $registration);
        }
        return view('admin.students.show-guest', compact('user'));
    }

    public function resetPasswordByUser(Request $request, User $user)
    {
        $this->authorizeUserAccess($user);

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('status', 'Password siswa berhasil direset.');
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

    private function authorizeUserAccess(User $user)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin()) {
            $levelIds = $authUser->getManagedLevelIds();
            if (!in_array($user->educational_level_id, $levelIds)) {
                abort(403, 'Anda tidak memiliki akses ke data siswa unit lain.');
            }
        }
    }

    public function editGuest(User $user)
    {
        $this->authorizeUserAccess($user);
        if ($user->registration) {
            return redirect()->route('admin.students.edit', $user->registration);
        }
        return view('admin.students.edit-guest', compact('user'));
    }

    public function updateGuest(Request $request, User $user)
    {
        $this->authorizeUserAccess($user);

        $validated = $request->validate([
            'full_name'                 => 'required|string|max:255',
            'asal_sekolah'              => 'required|string|max:255',
            'whatsapp_number'           => 'required|string|max:20',
            'alasan_memilih'            => 'required|string',
            'sumber_informasi'          => 'required|string|max:255',
            'sumber_informasi_tambahan' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('admin.students.show-by-user', $user)
            ->with('status', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Hanya super admin yang boleh hapus siswa
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang dapat menghapus data siswa.');
        }

        $name = $user->full_name ?? $user->name;
        $user->delete(); // cascade ke registration via FK on delete cascade

        return redirect()->route('admin.students.index')
            ->with('status', "Data siswa \"{$name}\" berhasil dihapus.");
    }
}

