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
        $payments = \App\Models\Payment::with('registration.user')
                    ->where('status', $status)
                    ->latest()
                    ->get();

        return view('admin.financial.payments', compact('payments', 'status'));
    }

    public function verifyPayment(Request $request, \App\Models\Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:success,failed',
            'admin_note' => 'nullable|string'
        ]);

        $payment->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Logic check success status for registration
        if ($request->status === 'success') {
            $payment->registration->update(['payment_status' => 'success']);
        }

        return back()->with('status', "Pembayaran {$payment->fee_type} berhasil diverifikasi.");
    }
}
