<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AdministrativeFee;
use App\Models\Payment;
use App\Services\BtnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $registration = $user->registration;

        if (!$registration) {
            return redirect()->route('pendaftaran.create');
        }

        // Cek Diskon Approved (hanya untuk informasi, tidak mengurangi biaya)
        $approvedDiscount = $registration->discountApplications()
            ->where('status', 'approved')
            ->with('discount')
            ->first();

        $fees = AdministrativeFee::where('educational_level_id', $user->educational_level_id)->get()->sortBy('sort_order');

        $isAlumni = str_contains(strtolower($user->asal_sekolah ?? ''), 'al hasra');

        // Mapping status pembayaran untuk setiap biaya (nominal penuh, tanpa potongan diskon)
        $feeData = $fees->map(function($fee) use ($registration, $isAlumni) {
            $payment = $registration->payments()->where('fee_type', $fee->name)->latest()->first();

            $originalAmount = $fee->amount;
            $finalAmount = $fee->amount;
            $discountAmount = 0;
            $discountName = null;

            if ($isAlumni && $fee->sort_order == 1) { // Biaya Formulir
                $finalAmount = 200000;
                $discountAmount = $originalAmount - 200000;
                $discountName = 'Discount Formulir untuk Alumni';
            }

            return (object) [
                'id'              => $fee->id,
                'name'            => $fee->name,
                'amount'          => $finalAmount,
                'paid_amount'     => $payment->paid_amount ?? 0,
                'original_amount' => $originalAmount,
                'discount_amount' => $discountAmount,
                'discount_name'   => $discountName,
                'sort_order'      => $fee->sort_order,
                'payment'         => $payment,
                'status'          => $payment ? $payment->status : 'none',
            ];
        });

        // Hitung Summary
        $totalFees = $feeData->sum('amount');
        $totalPaid = $registration->payments()->where('status', 'success')->sum('amount');
        $remaining = $totalFees - $totalPaid;

        return view('pendaftaran.financial', compact('registration', 'feeData', 'totalFees', 'totalPaid', 'remaining', 'approvedDiscount'));
    }

    // ─────────────────────────────────────────────────────────────
    // VA BTN
    // ─────────────────────────────────────────────────────────────

    public function createVa(Request $request, BtnService $btnService)
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
            return back()->with('error', 'Anda sudah memiliki VA yang belum dibayar untuk ' . $fee->name . '. Gunakan tombol "Ganti ke VA BTN" jika ingin beralih metode.');
        }

        return $this->doCreateVaBtn($registration, $fee, $btnService);
    }

    public function checkVa(Request $request, BtnService $btnService)
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
            // dd($result);
            if ($result['status']) {
                $rspData = $result['data'];
                if (isset($rspData['terbayar']) && $rspData['terbayar'] > 0) {
                    $payment->update([
                        'status' => Payment::STATUS_SUCCESS,
                        'amount' => $rspData['terbayar'],
                        'verified_at' => now()
                    ]);
                    if ($fee && $fee->sort_order == 1) {
                         $payment->registration->update(['payment_status' => 'success']);
                    }
                    return back()->with('status', 'Pembayaran VA sebesar Rp ' . number_format($rspData['terbayar'], 0, ',', '.') . ' telah berhasil dilunasi!');
                }
                
                return back()->with('status', 'Status VA masih belum dibayar.');
            } else {
                return back()->with('error', 'Inquiry gagal: ' . ($result['messages'] ?? 'Unknown Error'));
            }
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    // VA BCA (generate lokal, tidak hit endpoint eksternal)
    // ─────────────────────────────────────────────────────────────

    public function createVaBca(Request $request)
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
            return back()->with('error', 'Anda sudah memiliki VA yang belum dibayar untuk ' . $fee->name . '. Gunakan tombol "Ganti ke VA BCA" jika ingin beralih metode.');
        }

        return $this->doCreateVaBca($registration, $fee);
    }

    // ─────────────────────────────────────────────────────────────
    // Switch: BTN ↔ BCA
    // ─────────────────────────────────────────────────────────────

    /**
     * Ganti metode pembayaran dari BTN ke BCA.
     * VA BTN lama akan di-delete via API BTN, lalu record diganti ke VA BCA.
     */
    public function switchToBca(Request $request, BtnService $btnService)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        $payment = Payment::findOrFail($request->payment_id);
        $registration = Auth::user()->registration;

        if ($payment->registration_id !== $registration->id) {
            abort(403);
        }

        if ($payment->status !== Payment::STATUS_PENDING) {
            return back()->with('error', 'Hanya VA dengan status pending yang dapat diganti.');
        }

        if ($payment->va_bank === Payment::BANK_BCA) {
            return back()->with('error', 'Metode pembayaran sudah menggunakan VA BCA.');
        }

        $fee = AdministrativeFee::where('name', $payment->fee_type)
            ->where('educational_level_id', Auth::user()->educational_level_id)
            ->firstOrFail();

        // Hapus VA BTN via API
        $kode_adm = str_pad($fee->sort_order, 2, '0', STR_PAD_LEFT);
        $flag = ($fee->sort_order == 1) ? "F" : "P";
        $noid_base = Auth::user()->educational_level_id . Auth::id();
        $no_id = str_pad($noid_base, 7, '0', STR_PAD_LEFT) . $kode_adm;

        $deleteData = [
            'id_calsis'   => Auth::id(),
            'ref'         => $payment->va_ref,
            'va'          => $payment->va_number,
            'nama_siswa'  => Auth::user()->full_name ?? Auth::user()->name,
            'jenis_bayar' => $payment->fee_type,
            'no_urut'     => $fee->sort_order,
            'no_id'       => $no_id,
            'tagihan'     => (string) (int) $payment->amount,
            'flag'        => $flag,
        ];

        try {
            $result = $btnService->deleteVA($deleteData);
            if (!$result['status']) {
                return back()->with('error', 'Gagal menghapus VA BTN: ' . ($result['messages'] ?? 'Unknown Error'));
            }
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus VA BTN: ' . $e->getMessage());
        }

        // Hapus record payment lama, buat BCA baru
        $payment->delete();

        return $this->doCreateVaBca($registration, $fee);
    }

    /**
     * Ganti metode pembayaran dari BCA ke BTN.
     * Record VA BCA dihapus dari DB, lalu VA BTN dibuat via API.
     */
    public function switchToBtn(Request $request, BtnService $btnService)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        $payment = Payment::findOrFail($request->payment_id);
        $registration = Auth::user()->registration;

        if ($payment->registration_id !== $registration->id) {
            abort(403);
        }

        if ($payment->status !== Payment::STATUS_PENDING) {
            return back()->with('error', 'Hanya VA dengan status pending yang dapat diganti.');
        }

        if ($payment->va_bank === Payment::BANK_BTN) {
            return back()->with('error', 'Metode pembayaran sudah menggunakan VA BTN.');
        }

        $fee = AdministrativeFee::where('name', $payment->fee_type)
            ->where('educational_level_id', Auth::user()->educational_level_id)
            ->firstOrFail();

        // Hapus record VA BCA dari DB (tidak perlu hit API)
        $payment->delete();

        return $this->doCreateVaBtn($registration, $fee, $btnService);
    }

    // ─────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────

    /**
     * Generate dan simpan VA BTN via API.
     */
    private function doCreateVaBtn($registration, $fee, BtnService $btnService)
    {
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

        // Terapkan Diskon jika ada yang disetujui
        $finalAmount = $this->resolveFinalAmount($registration, $fee);

        $data = [
            'id_calsis'     => Auth::id(),
            'ref'           => $ref,
            'va'            => $no_va,
            'nama_siswa'    => Auth::user()->full_name ?? Auth::user()->name,
            'jenis_bayar'   => $fee->name,
            'no_urut'       => $fee->sort_order,
            'no_id'         => $no_id,
            'tagihan'       => (string) (int) $finalAmount,
            'flag'          => $flag
        ];

        try {
            $result = $btnService->createVA($data);
            
            if ($result['status']) {
                $registration->payments()->create([
                    'fee_type'       => $fee->name,
                    'amount'         => $finalAmount,
                    'va_number'      => $no_va,
                    'va_ref'         => $ref,
                    'payment_method' => Payment::METHOD_VA,
                    'va_bank'        => Payment::BANK_BTN,
                    'status'         => Payment::STATUS_PENDING,
                ]);
                return back()->with('status', 'Virtual Account BTN berhasil dibuat. Silakan lakukan pembayaran.');
            } else {
                return back()->with('error', 'Gagal membuat VA BTN: ' . ($result['messages'] ?? 'Unknown Error'));
            }
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Generate nomor VA BCA dan simpan ke DB tanpa hit API eksternal.
     *
     * Format VA BCA (spesifikasi):
     *  [15822] [024] [yy] [mm] [dd] [rrr] [ss]
     *   kode    app  thn  bln  tgl  regId sort
     *   5 dig   3 d  2 d  2 d  2 d  3 d   2 d  = 20 karakter total
     *
     * Catatan: spesifikasi menyebut 18 karakter. Jika perlu tepat 18,
     * sesuaikan segmen (misal kode aplikasi 2 digit "24" bukan "024").
     * Implementasi ini mengikuti seluruh komponen yang disebutkan secara eksplisit.
     */
    private function doCreateVaBca($registration, $fee)
    {
        $kodeInstansi  = '15822';                                            // 5 digit
        $kodeAplikasi  = '024';                                              // 3 digit
        $tahun         = date('y');                                          // 2 digit
        $bulan         = date('m');                                          // 2 digit
        $tanggal       = date('d');                                          // 2 digit
        $regId         = str_pad($registration->id, 4, '0', STR_PAD_LEFT);  // 4 digit
        $sortOrder     = str_pad($fee->sort_order, 2, '0', STR_PAD_LEFT);   // 2 digit

        // Contoh: 15822 + 024 + 26 + 08 + 08 + 0001 + 01 = 15822024260808000101
        $noVaBca = $kodeInstansi . $kodeAplikasi . $tahun . $bulan . $tanggal . $regId . $sortOrder;

        // Terapkan diskon jika ada
        $finalAmount = $this->resolveFinalAmount($registration, $fee);

        $registration->payments()->create([
            'fee_type'       => $fee->name,
            'amount'         => $finalAmount,
            'va_number'      => $noVaBca,
            'va_ref'         => null,
            'payment_method' => Payment::METHOD_VA_BCA,
            'va_bank'        => Payment::BANK_BCA,
            'status'         => Payment::STATUS_PENDING,
        ]);

        return back()->with('status', 'Virtual Account BCA berhasil dibuat. Silakan lakukan pembayaran ke nomor VA BCA Anda.');
    }

    /**
     * Hitung jumlah tagihan final.
     * Diskon tidak diterapkan ke nominal VA — diskon dikelola secara terpisah
     * untuk keperluan cashback / program keringanan lainnya.
     */
    private function resolveFinalAmount($registration, $fee): float
    {
        $user = $registration->user;
        $isAlumni = str_contains(strtolower($user->asal_sekolah ?? ''), 'al hasra');
        
        if ($isAlumni && $fee->sort_order == 1) {
            return 200000;
        }

        return (float) $fee->amount;
    }
}
