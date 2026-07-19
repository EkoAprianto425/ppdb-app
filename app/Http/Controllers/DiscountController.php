<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\DiscountApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DiscountController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'discount_id'     => 'required|exists:discounts,id',
            'employee_status' => 'nullable|string|max:100',
            'document'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $registration = $user->registration;

        if (!$registration) {
            return back()->with('error', 'Anda belum mengisi formulir pendaftaran.');
        }

        $discount = Discount::findOrFail($request->discount_id);

        // Jika kategori anak_pegawai, status kepegawaian wajib diisi
        if ($discount->category === 'anak_pegawai' && empty($request->employee_status)) {
            return back()->with('error', 'Status kepegawaian wajib dipilih untuk kategori Keluarga Karyawan.');
        }

        // Dokumen wajib untuk anak_pegawai (Kartu Keluarga) atau jika diskon require_document
        $needsDocument  = ($discount->category === 'anak_pegawai') || $discount->require_document;
        $hasValidFile   = $request->hasFile('document')
                       && $request->file('document')->isValid()
                       && $request->file('document')->getSize() > 0;
        if ($needsDocument && !$hasValidFile) {
            $label = $discount->category === 'anak_pegawai'
                ? 'Kartu Keluarga (KK) wajib diupload untuk pengajuan keringanan Keluarga Karyawan.'
                : 'Kategori diskon ini mewajibkan upload dokumen pendukung.';
            return back()->with('error', $label);
        }

        // Check if already applied
        $existing = DiscountApplication::where('registration_id', $registration->id)->first();
        if ($existing) {
            return back()->with('error', 'Anda sudah mengajukan keringanan biaya. Status saat ini: ' . ucfirst($existing->status));
        }

        $documentPath = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            if ($file->isValid() && $file->getSize() > 0) {
                if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
                    $tmpPath = $_FILES['document']['tmp_name'];
                    $extension = $file->getClientOriginalExtension();
                    
                    $storageDir = storage_path('app/public/discount_documents');
                    if (!file_exists($storageDir)) {
                        mkdir($storageDir, 0755, true);
                    }

                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $destination = $storageDir . DIRECTORY_SEPARATOR . $filename;
                    
                    if (move_uploaded_file($tmpPath, $destination)) {
                        $documentPath = 'discount_documents/' . $filename;
                    } else {
                        return back()->with('error', 'Gagal memindahkan file dokumen. Pastikan folder public dapat diakses.');
                    }
                } else {
                    // Fallback to Laravel store method (e.g. for testing environments)
                    try {
                        $documentPath = $file->store('discount_documents', 'public');
                    } catch (\Throwable $e) {
                        return back()->with('error', 'Gagal menyimpan dokumen pengajuan: ' . $e->getMessage());
                    }
                }
            }
        }

        DiscountApplication::create([
            'registration_id' => $registration->id,
            'discount_id'     => $discount->id,
            'employee_status' => $discount->category === 'anak_pegawai' ? $request->employee_status : null,
            'status'          => 'pending',
            'document_path'   => $documentPath,
        ]);

        return back()->with('status', 'Pengajuan keringanan biaya berhasil dikirim dan sedang menunggu verifikasi.');
    }
}
