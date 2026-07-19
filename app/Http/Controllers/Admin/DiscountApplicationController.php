<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountApplication;
use Illuminate\Http\Request;

class DiscountApplicationController extends Controller
{
    public function index()
    {
        $applications = DiscountApplication::with(['registration.user', 'discount'])
            ->latest()
            ->get();

        return view('admin.discount-applications.index', compact('applications'));
    }

    public function update(Request $request, DiscountApplication $application)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $application->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        $message = $request->status === 'approved' 
            ? 'Pengajuan keringanan berhasil disetujui.' 
            : 'Pengajuan keringanan telah ditolak.';

        return back()->with('status', $message);
    }
}
