<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdministrativeFee;
use App\Models\EducationalLevel;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index()
    {
        $levels = EducationalLevel::orderBy('sort_order')->get();
        $fees = AdministrativeFee::with('level')->orderBy('educational_level_id')->orderBy('sort_order')->get();

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

        return back()->with('status', "Biaya {$fee->name} berhasil diperbarui.");
    }

    public function destroy(AdministrativeFee $fee)
    {
        $fee->delete();
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
        $levels = EducationalLevel::all();

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

        // Update status registrasi
        $payment->registration->update(['payment_status' => 'success']);

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
                if (isset($rspData['terbayar']) && $rspData['terbayar'] > 0) {
                    $payment->update([
                        'status' => \App\Models\Payment::STATUS_SUCCESS,
                        'amount' => $rspData['terbayar'],
                        'verified_by' => auth()->id(),
                        'verified_at' => now(),
                        'admin_note' => 'Auto-verified by BTN VA Inquiry'
                    ]);
                    if ($fee && $fee->sort_order == 1) {
                         $payment->registration->update(['payment_status' => 'success']);
                    }
                    return back()->with('status', 'Status VA: Sudah Dibayar (Rp ' . number_format($rspData['terbayar'], 0, ',', '.') . '). Sistem telah mengupdate status otomatis.');
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
}
