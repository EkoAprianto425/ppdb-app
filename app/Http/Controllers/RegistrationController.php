<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\EducationalLevel;
use App\Models\AdministrativeFee;
use App\Models\Payment;
use App\Services\BtnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function financialIndex()
    {
        $user = Auth::user();
        $registration = $user->registration;

        if (!$registration) {
            return redirect()->route('pendaftaran.create');
        }

        // Ambil Jenjang via relationship
        $level = $user->educationalLevel;
        $fees = $level ? $level->fees()->orderBy('sort_order')->get() : collect();

        // Mapping status pembayaran untuk setiap biaya
        $feeData = $fees->map(function($fee) use ($registration) {
            $payment = $registration->payments()->where('fee_type', $fee->name)->latest()->first();
            return (object) [
                'id' => $fee->id,
                'name' => $fee->name,
                'amount' => $fee->amount,
                'sort_order' => $fee->sort_order,
                'payment' => $payment,
                'status' => $payment ? $payment->status : 'none',
            ];
        });

        // Hitung Summary
        $totalFees = $fees->sum('amount');
        $totalPaid = $registration->payments()->where('status', 'success')->sum('amount');
        $remaining = $totalFees - $totalPaid;

        return view('pendaftaran.financial', compact('registration', 'feeData', 'totalFees', 'totalPaid', 'remaining'));
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
        
        $activeWave = \App\Models\RegistrationWave::where('is_active', true)->first();
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

    public function createVaPayment(Request $request, BtnService $btnService)
    {
        $request->validate([
            'fee_id' => 'required|exists:administrative_fees,id',
        ]);

        $registration = Auth::user()->registration;
        $fee = AdministrativeFee::findOrFail($request->fee_id);

        // Cek jika sudah ada VA pending untuk fee ini
        $existingPayment = $registration->payments()
            ->where('fee_type', $fee->name)
            ->where('status', Payment::STATUS_PENDING)
            ->first();

        if ($existingPayment) {
            return back()->with('status', 'Anda sudah memiliki VA yang belum dibayar untuk ' . $fee->name);
        }

        // Generate VA Data
        $ref = str_pad($registration->id . date('ymd'), 10, '0', STR_PAD_LEFT);
        
        $va_prefix = "9" . config('btn.kode_institusi', '4842');
        $tgl = date('ymd');
        $va_daftar = str_pad($registration->id, 4, '0', STR_PAD_LEFT);
        
        $mao_bayar = $fee->sort_order;
        if ($mao_bayar == 1) {
            $kode_adm = "01";
            $flag = "F";
        } else {
            $kode_adm = str_pad($fee->sort_order, 2, '0', STR_PAD_LEFT);
            $flag = "P";
        }

        $no_va = $va_prefix . $tgl . $va_daftar . $kode_adm;

        $educational_level_id = Auth::user()->educational_level_id;
        $noid_base = $educational_level_id . Auth::id();
        $no_id = str_pad($noid_base, 7, '0', STR_PAD_LEFT) . $kode_adm;

        $data = [
            'id_calsis'     => Auth::id(),
            'ref'           => $ref,
            'va'            => $no_va,
            'nama_siswa'    => Auth::user()->full_name ?? Auth::user()->name,
            'jenis_bayar'   => $fee->name,
            'no_urut'       => $fee->sort_order,
            'no_id'         => $no_id,
            'tagihan'       => (string) (int) $fee->amount,
            'flag'          => $flag
        ];

        try {
            $result = $btnService->createVA($data);
            
            if ($result['status']) {
                $registration->payments()->create([
                    'fee_type' => $fee->name,
                    'amount'   => $fee->amount,
                    'va_number' => $no_va,
                    'va_ref' => $ref,
                    'payment_method' => Payment::METHOD_VA,
                    'status'   => Payment::STATUS_PENDING,
                ]);
                return back()->with('status', 'Virtual Account berhasil dibuat. Silakan lakukan pembayaran.');
            } else {
                return back()->with('error', 'Gagal membuat VA BTN: ' . ($result['messages'] ?? 'Unknown Error'));
            }
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function checkVaStatus(Request $request, BtnService $btnService)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        $payment = Payment::findOrFail($request->payment_id);
        
        if ($payment->registration->user_id !== Auth::id()) {
            abort(403);
        }

        if ($payment->status === Payment::STATUS_SUCCESS) {
            return back()->with('status', 'Pembayaran sudah lunas.');
        }

        $fee = AdministrativeFee::where('name', $payment->fee_type)
            ->where('educational_level_id', Auth::user()->educational_level_id)
            ->first();
            
        $kode_adm = str_pad($fee->sort_order ?? 1, 2, '0', STR_PAD_LEFT);
        $flag = ($fee && $fee->sort_order == 1) ? "F" : "P";

        $noid_base = Auth::user()->educational_level_id . Auth::id();
        $no_id = str_pad($noid_base, 7, '0', STR_PAD_LEFT) . $kode_adm;

        $data = [
            'id_calsis'     => Auth::id(),
            'ref'           => $payment->va_ref,
            'va'            => $payment->va_number,
            'nama_siswa'    => Auth::user()->full_name ?? Auth::user()->name,
            'jenis_bayar'   => $payment->fee_type,
            'no_urut'       => $fee->sort_order ?? 1,
            'no_id'         => $no_id,
            'tagihan'       => (string) (int) $payment->amount,
            'flag'          => $flag
        ];

        try {
            $result = $btnService->inquiryVA($data);
            
            if ($result['status']) {
                // Update status if API indicates payment is completed
                // This logic depends on the specific BTN API response structure for inqVA
                // Assuming $result['data']['status_pembayaran'] or similar
                $rspData = $result['data'];
                if (isset($rspData['status_bayar']) && $rspData['status_bayar'] == '1') {
                    $payment->update([
                        'status' => Payment::STATUS_SUCCESS,
                        'verified_at' => now()
                    ]);
                    // Update registration if all fees paid, or just this one
                    if ($fee && $fee->sort_order == 1) {
                         $payment->registration->update(['payment_status' => 'success']);
                    }
                    return back()->with('status', 'Pembayaran VA telah berhasil dilunasi!');
                }
                
                return back()->with('status', 'Status VA masih belum dibayar.');
            } else {
                return back()->with('error', 'Inquiry gagal: ' . ($result['messages'] ?? 'Unknown Error'));
            }
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function examIndex()
    {
        $registration = Auth::user()->registration;

        if (!$registration || $registration->payment_status !== 'success') {
            return redirect()->route('pendaftaran.financial')->with('error', 'Anda harus melunasi dan menunggu verifikasi pembayaran sebelum dapat mengakses Kartu Ujian.');
        }

        $schedules = [];
        if (!$registration->exam_schedule_id) {
            $schedules = \App\Models\ExamSchedule::where('educational_level_id', Auth::user()->educational_level_id)->get();
        }

        return view('pendaftaran.exam', compact('registration', 'schedules'));
    }

    public function selectExam(Request $request)
    {
        $request->validate([
            'exam_schedule_id' => 'required|exists:exam_schedules,id',
        ]);

        $registration = Auth::user()->registration;
        $registration->update([
            'exam_schedule_id' => $request->exam_schedule_id,
        ]);

        return back()->with('status', 'Jadwal ujian berhasil dipilih.');
    }

    public function downloadExamCard()
    {
        $registration = Auth::user()->registration;

        if (!$registration || $registration->payment_status !== 'success' || !$registration->exam_schedule_id) {
            return back()->with('error', 'Anda belum memenuhi syarat untuk mengunduh Kartu Ujian. Pastikan pembayaran sudah diverifikasi dan jadwal ujian telah dipilih.');
        }

        $schedule = $registration->examSchedule;
        $user = Auth::user();

        // Pass the required data to the view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.exam_card', compact('registration', 'schedule', 'user'));
        
        return $pdf->stream('Kartu_Ujian_' . $user->name . '.pdf');
    }
    public function announcementIndex()
    {
        $registration = Auth::user()->registration;

        if (!$registration) {
            return redirect()->route('pendaftaran.index')->with('error', 'Silakan lengkapi formulir pendaftaran terlebih dahulu.');
        }

        return view('pendaftaran.announcement', compact('registration'));
    }

    public function downloadSKL()
    {
        $registration = Auth::user()->registration;

        if (!$registration || $registration->status !== 'lulus') {
            return back()->with('error', 'Anda belum memenuhi syarat untuk mengunduh Surat Keterangan Lulus.');
        }

        $user = Auth::user();
        
        // Pass the required data to the view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.skl', compact('registration', 'user'));
        
        return $pdf->stream('SKL_' . $user->name . '.pdf');
    }
}
