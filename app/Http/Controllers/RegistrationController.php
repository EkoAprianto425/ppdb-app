<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\EducationalLevel;
use App\Models\AdministrativeFee;
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

    public function uploadPayment(Request $request)
    {
        try {
            // Validasi input selain file dulu
            $request->validate([
                'fee_type'      => 'required|string',
                'amount'        => 'required|numeric',
            ]);

            $registration = Auth::user()->registration;

            if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
                return back()->with('error', 'Gagal mengunggah file. Kode Error: ' . ($_FILES['payment_proof']['error'] ?? 'No File'));
            }

            $tmpPath = $_FILES['payment_proof']['tmp_name'];
            $originalName = $_FILES['payment_proof']['name'];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            
            // Tentukan lokasi penyimpanan absolut
            $storageDir = storage_path('app/public/payments');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $extension;
            $destination = $storageDir . DIRECTORY_SEPARATOR . $filename;
            $dbPath = 'payments/' . $filename;

            // Pindahkan file secara manual (Native PHP)
            if (move_uploaded_file($tmpPath, $destination)) {
                $registration->payments()->create([
                    'fee_type' => $request->fee_type,
                    'amount'   => $request->amount,
                    'payment_proof' => $dbPath,
                    'status'   => 'pending',
                ]);

                return back()->with('status', 'Bukti pembayaran berhasil diunggah secara langsung.');
            } else {
                return back()->with('error', 'Gagal memindahkan file ke direktori tujuan: ' . $storageDir);
            }

        } catch (\Throwable $e) {
            return back()->with('error', 'Diagnosa: ' . $e->getMessage() . ' di ' . $e->getFile() . ':' . $e->getLine());
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
            $schedules = \App\Models\ExamSchedule::where('unit', Auth::user()->educationalLevel?->name)->get();
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
