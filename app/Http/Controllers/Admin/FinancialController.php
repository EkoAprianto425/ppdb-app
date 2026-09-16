<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdministrativeFee;
use App\Models\EducationalLevel;
use App\Support\AppCache;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index()
    {
        $levels = AppCache::educationalLevels();
        $fees   = AppCache::administrativeFees();

        return view('admin.financial.fees', compact('fees', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'educational_level_id' => 'required|exists:educational_levels,id',
            'amount' => 'required|numeric|min:0',
            'sort_order' => 'required|integer',
        ]);

        AdministrativeFee::create($validated);
        AppCache::forgetAdministrativeFees();

        return back()->with('status', 'Biaya administrasi berhasil ditambahkan.');
    }

    public function update(Request $request, AdministrativeFee $fee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'educational_level_id' => 'required|exists:educational_levels,id',
            'amount' => 'required|numeric|min:0',
            'sort_order' => 'required|integer',
        ]);

        $fee->update($validated);
        AppCache::forgetAdministrativeFees();

        return back()->with('status', "Biaya {$fee->name} berhasil diperbarui.");
    }

    public function destroy(AdministrativeFee $fee)
    {
        $fee->delete();
        AppCache::forgetAdministrativeFees();
        return back()->with('status', 'Biaya administrasi berhasil dihapus.');
    }

    public function indexPayments(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = \App\Models\Payment::with('registration.user')
            ->whereHas('registration');

        if ($status === 'belum_lunas') {
            // Pembayaran yang sudah ada paid_amount tapi belum full
            $query->whereColumn('paid_amount', '<', 'amount')
                  ->where('paid_amount', '>', 1)
                  ->where('status', 'success');
        } else {
            $query->where('status', $status);
        }

        $user = auth()->user();
        if (!$user) {
            abort(401);
        }
        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            if (empty($levelIds)) {
                $query->whereRaw('0 = 1'); // user has no managed levels
            } else {
                $query->whereHas('registration.user', function($q) use ($levelIds) {
                    $q->whereIn('educational_level_id', $levelIds);
                });
            }
        }

        // Filter by Unit/Jenjang
        if ($request->filled('level_id')) {
            $query->whereHas('registration.user', function($q) use ($request) {
                $q->where('educational_level_id', $request->level_id);
            });
        }

        $payments = $query->latest()->get();
        $levels   = AppCache::educationalLevels();

        return view('admin.financial.payments', compact('payments', 'status', 'levels'));
    }

    public function verifyPayment(Request $request, \App\Models\Payment $payment)
    {
        $request->validate([
            'status'      => 'required|in:success,failed',
            'paid_amount' => 'nullable|numeric|min:0',
            'admin_note'  => 'nullable|string'
        ]);

        $updateData = [
            'status'      => $request->status,
            'admin_note'  => $request->admin_note,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ];

        if ($request->filled('paid_amount')) {
            $updateData['paid_amount'] = $request->paid_amount;
        }

        $payment->update($updateData);

        // Logic check success status for registration
        if ($request->status === 'success') {
            $payment->registration->update(['payment_status' => 'success']);
        }

        return back()->with('status', "Pembayaran {$payment->fee_type} berhasil diverifikasi.");
    }

    public function recordCashPayment(Request $request, \App\Models\Payment $payment)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:1',
            'admin_note'  => 'nullable|string|max:500',
        ]);

        $payment->update([
            'paid_amount'     => $request->paid_amount,
            'payment_method'  => \App\Models\Payment::METHOD_CASH,
            'status'          => \App\Models\Payment::STATUS_SUCCESS,
            'admin_note'      => $request->admin_note ?? 'Pembayaran tunai diterima di sekolah',
            'verified_by'     => auth()->id(),
            'verified_at'     => now(),
        ]);

        // Update status registrasi & post ke SIDIGS (sama seperti flow VA)
        $fee = \App\Models\AdministrativeFee::where('name', $payment->fee_type)
            ->where('educational_level_id', $payment->registration->user->educational_level_id)
            ->first();

        if ($fee && $fee->sort_order == 1) {
            $payment->registration->update(['payment_status' => 'success']);
        } else {
            \App\Services\SidigsService::postStudent($payment->registration);
        }

        return back()->with('status', "Pembayaran cash untuk {$payment->fee_type} atas nama {$payment->registration->user->full_name} berhasil dicatat.");
    }

    public function checkVaStatus(Request $request, \App\Models\Payment $payment, \App\Services\BtnService $btnService)
    {
        if ($payment->payment_method !== \App\Models\Payment::METHOD_VA) {
            return back()->with('error', 'Metode pembayaran bukan VA BTN.');
        }

        $fee = \App\Models\AdministrativeFee::where('name', $payment->fee_type)
            ->where('educational_level_id', $payment->registration->user->educational_level_id)
            ->first();
            
        $kode_adm = str_pad($fee->sort_order ?? 1, 2, '0', STR_PAD_LEFT);
        $flag = ($fee && $fee->sort_order == 1) ? "F" : "P";

        $noid_base = $payment->registration->user->educational_level_id . $payment->registration->user_id;
        $no_id = str_pad($noid_base, 7, '0', STR_PAD_LEFT) . $kode_adm;

        $data = [
            'id_calsis'     => $payment->registration->user_id,
            'ref'           => $payment->va_ref,
            'va'            => $payment->va_number,
            'nama_siswa'    => $payment->registration->user->full_name ?? $payment->registration->user->name,
            'jenis_bayar'   => $payment->fee_type,
            'no_urut'       => $fee->sort_order ?? 1,
            'no_id'         => $no_id,
            'tagihan'       => (string) (int) $payment->amount,
            'flag'          => $flag
        ];

        try {
            $result = $btnService->inquiryVA($data);
            // dd($result);
            if ($result['status']) {
                $rspData = $result['data'];
                $terbayar = $rspData['terbayar'] ?? null;

                // Fallback: jika terbayar null/0 tapi payment sudah ada amount, pakai amount tagihan
                if (empty($terbayar) && !empty($payment->amount)) {
                    $terbayar = $payment->amount;
                }

                if ($terbayar > 0) {
                    // Parse waktu dari response BTN: createdate=DDMMYY, createtime=HHMMSS
                    $verifiedAt = now();
                    $cd = $rspData['createdate'] ?? null; // e.g. "150926"
                    $ct = $rspData['createtime'] ?? null; // e.g. "081207"
                    if ($cd && $ct) {
                        try {
                            $verifiedAt = \Carbon\Carbon::createFromFormat('dmyHis', $cd . $ct);
                        } catch (\Throwable) {}
                    }

                    $payment->update([
                        'status'      => \App\Models\Payment::STATUS_SUCCESS,
                        'paid_amount' => $terbayar,
                        'verified_by' => auth()->id(),
                        'verified_at' => $verifiedAt,
                        'admin_note'  => 'Auto-verified by BTN VA Inquiry'
                    ]);
                    if ($fee && $fee->sort_order == 1) {
                         $payment->registration->update(['payment_status' => 'success']);
                    }
                    return back()->with('status', 'Status VA: Sudah Dibayar (Rp ' . number_format($terbayar, 0, ',', '.') . '). Sistem telah mengupdate status otomatis.');
                }

                return back()->with('status', 'Inquiry VA berhasil. Status: Belum Dibayar. (Respon API: ' . json_encode($rspData) . ')');
            } else {
                return back()->with('error', 'Inquiry gagal: ' . ($result['messages'] ?? 'Unknown Error'));
            }
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function updatePaidAmount(Request $request, \App\Models\Payment $payment)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:1',
        ]);

        $payment->update([
            'paid_amount'  => $request->paid_amount,
            'verified_by'  => auth()->id(),
            'verified_at'  => now(),
        ]);

        return back()->with('status', "Nominal pembayaran berhasil diperbarui.");
    }

    public function exportPayments(Request $request)
    {
        $status = $request->get('status', 'success');

        if (!in_array($status, ['success', 'belum_lunas'])) {
            return back()->with('error', 'Export hanya tersedia untuk tab Berhasil dan Belum Lunas.');
        }

        $query = \App\Models\Payment::with(['registration.user.educationalLevel'])
            ->whereHas('registration');

        if ($status === 'belum_lunas') {
            $query->whereColumn('paid_amount', '<', 'amount')
                  ->where('paid_amount', '>', 1)
                  ->where('status', 'success');
        } else {
            $query->where('status', $status);
        }

        $user = auth()->user();
        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            if (empty($levelIds)) {
                $query->whereRaw('0 = 1');
            } else {
                $query->whereHas('registration.user', fn($q) => $q->whereIn('educational_level_id', $levelIds));
            }
        }

        if ($request->filled('level_id')) {
            $query->whereHas('registration.user', fn($q) => $q->where('educational_level_id', $request->level_id));
        }

        $payments = $query->latest()->get();

        $labelStatus  = $status === 'belum_lunas' ? 'Belum Lunas' : 'Berhasil';
        $isBelumLunas = $status === 'belum_lunas';
        $filename     = 'pembayaran_' . $status . '_' . now()->format('Ymd_His') . '.xlsx';

        $methodLabels = [
            'va'     => 'VA BTN',
            'va_bca' => 'VA BCA',
            'cash'   => 'Tunai',
            'manual' => 'Manual',
        ];

        // ── Build spreadsheet ──────────────────────────────────────────────────
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Verifikasi Pembayaran');

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'wrapText'   => true],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                             'color' => ['rgb' => 'FFFFFF']]],
        ];

        $subHeaderStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => '1E3A5F'], 'size' => 10],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E8F0FE']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'wrapText'   => true],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                             'color' => ['rgb' => 'B0C4DE']]],
        ];

        // ── Row 1: Judul laporan ───────────────────────────────────────────────
        $lastCol = $isBelumLunas ? 'L' : 'K';
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'LAPORAN VERIFIKASI PEMBAYARAN - ' . strtoupper($labelStatus));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '0D2137']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // ── Row 2: Tanggal cetak ───────────────────────────────────────────────
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Dicetak: ' . now()->format('d F Y, H:i') . ' WIB');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '555555']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F5F5F5']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Row 3: blank spacer ────────────────────────────────────────────────
        $sheet->getRowDimension(3)->setRowHeight(6);

        // ── Row 4: kolom header ────────────────────────────────────────────────
        $columns = ['No', 'Nama Siswa', 'Email', 'Unit/Jenjang', 'Tipe Biaya',
                    'Tagihan (Rp)', 'Dibayarkan (Rp)', 'Metode', 'Nomor VA',
                    'Tanggal Pembayaran', 'Status'];
        if ($isBelumLunas) {
            array_splice($columns, 7, 0, ['Sisa Tagihan (Rp)']);
        }

        foreach ($columns as $ci => $col) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci + 1) . '4';
            $sheet->setCellValue($cell, $col);
        }
        $sheet->getStyle("A4:{$lastCol}4")->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(22);

        // ── Data rows ──────────────────────────────────────────────────────────
        $rowNum     = 5;
        $totalTagihan    = 0;
        $totalDibayar    = 0;
        $totalSisa       = 0;

        $zebraEven = ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                 'startColor' => ['rgb' => 'F0F4FB']]];
        $borderData = ['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                       'color' => ['rgb' => 'D0D8E8']]]];

        foreach ($payments as $i => $p) {
            $tagihan   = (int) $p->amount;
            $dibayar   = (int) ($p->paid_amount ?? 0);
            $sisa      = $tagihan - $dibayar;
            $totalTagihan += $tagihan;
            $totalDibayar += $dibayar;
            $totalSisa    += $sisa;

            $rowData = [
                $i + 1,
                $p->registration->user->full_name ?? $p->registration->user->name ?? '-',
                $p->registration->user->email ?? '-',
                $p->registration->user->educationalLevel->name ?? '-',
                $p->fee_type,
                $tagihan,
                $dibayar,
                $methodLabels[$p->payment_method] ?? ($p->payment_method ?? '-'),
                $p->va_number ?? '-',
                $p->verified_at ? $p->verified_at->format('d/m/Y H:i') : '-',
                $labelStatus,
            ];
            if ($isBelumLunas) {
                array_splice($rowData, 7, 0, [$sisa]);
            }

            foreach ($rowData as $ci => $val) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci + 1);
                $sheet->setCellValue("{$colLetter}{$rowNum}", $val);
            }

            // Format angka sebagai number (bukan text)
            $sheet->getStyle("F{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("G{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
            if ($isBelumLunas) {
                $sheet->getStyle("H{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
            }

            // Alignment
            $sheet->getStyle("A{$rowNum}")->getAlignment()->setHorizontal('center');
            $sheet->getStyle("{$lastCol}{$rowNum}")->getAlignment()->setHorizontal('center');

            // Zebra stripe
            if ($i % 2 === 1) {
                $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray($zebraEven);
            }
            $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray($borderData);
            $rowNum++;
        }

        // ── Summary row ────────────────────────────────────────────────────────
        $sumRow = $rowNum + 1;
        $sheet->mergeCells("A{$sumRow}:E{$sumRow}");
        $sheet->setCellValue("A{$sumRow}", 'TOTAL');
        $sheet->setCellValue("F{$sumRow}", $totalTagihan);
        $sheet->setCellValue("G{$sumRow}", $totalDibayar);
        if ($isBelumLunas) {
            $sheet->setCellValue("H{$sumRow}", $totalSisa);
        }
        $sheet->getStyle("A{$sumRow}:{$lastCol}{$sumRow}")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                             'color' => ['rgb' => '0D2137']]],
        ]);
        $sheet->getStyle("F{$sumRow}")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("G{$sumRow}")->getNumberFormat()->setFormatCode('#,##0');
        if ($isBelumLunas) {
            $sheet->getStyle("H{$sumRow}")->getNumberFormat()->setFormatCode('#,##0');
        }

        // ── Auto width kolom ───────────────────────────────────────────────────
        $colWidths = [5, 30, 28, 18, 20, 16, 16, 12, 20, 20, 12];
        if ($isBelumLunas) {
            array_splice($colWidths, 7, 0, [16]);
        }
        foreach ($colWidths as $ci => $width) {
            $sheet->getColumnDimensionByColumn($ci + 1)->setWidth($width);
        }

        // Freeze pane: beku baris header
        $sheet->freezePane('A5');

        // ── Output ────────────────────────────────────────────────────────────
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}

