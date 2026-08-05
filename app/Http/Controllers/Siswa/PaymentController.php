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

        // Cek Diskon Approved
        $approvedDiscount = $registration->discountApplications()
            ->where('status', 'approved')
            ->with('discount')
            ->first();
        $discountAmount = $approvedDiscount ? $approvedDiscount->discount->amount : 0;

        $fees = AdministrativeFee::where('educational_level_id', $user->educational_level_id)->get()->sortBy('sort_order');

        // Mapping status pembayaran untuk setiap biaya
        $feeData = $fees->map(function($fee) use ($registration, $discountAmount) {
            $payment = $registration->payments()->where('fee_type', $fee->name)->latest()->first();
            
            // Terapkan diskon jika sort_order > 1 (biasanya biaya masuk/daftar ulang)
            $amount = $fee->amount;
            if ($fee->sort_order > 1) {
                $amount -= $discountAmount;
            }
            if ($amount < 0) $amount = 0;

            return (object) [
                'id' => $fee->id,
                'name' => $fee->name,
                'amount' => $amount,
                'original_amount' => $fee->amount,
                'is_discounted' => ($fee->sort_order > 1 && $discountAmount > 0),
                'discount_amount' => ($fee->sort_order > 1) ? $discountAmount : 0,
                'sort_order' => $fee->sort_order,
                'payment' => $payment,
                'status' => $payment ? $payment->status : 'none',
            ];
        });

        // Hitung Summary
        $totalFees = $feeData->sum('amount');
        $totalPaid = $registration->payments()->where('status', 'success')->sum('amount');
        $remaining = $totalFees - $totalPaid;

        return view('pendaftaran.financial', compact('registration', 'feeData', 'totalFees', 'totalPaid', 'remaining', 'approvedDiscount'));
    }

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

        // Terapkan Diskon jika ada yang disetujui
        $finalAmount = $fee->amount;
        if ($fee->sort_order > 1) {
            $approvedDiscount = $registration->discountApplications()
                ->where('status', 'approved')
                ->with('discount')
                ->first();
            if ($approvedDiscount) {
                $finalAmount -= $approvedDiscount->discount->amount;
            }
        }
        if ($finalAmount < 0) $finalAmount = 0;

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
                    'fee_type' => $fee->name,
                    'amount'   => $finalAmount,
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
}
