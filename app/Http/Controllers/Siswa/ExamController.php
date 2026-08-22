<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
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

    public function select(Request $request)
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

    public function downloadCard()
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

    public function downloadCounsel(){
        $registration = Auth::user()->registration;

        if (!$registration || $registration->payment_status !== 'success' || !$registration->exam_schedule_id) {
            return back()->with('error', 'Anda belum memenuhi syarat untuk mengunduh Kartu Ujian. Pastikan pembayaran sudah diverifikasi dan jadwal ujian telah dipilih.');
        }

        $unit = Auth::user()->educationalLevel->parent_unit;
        if ($unit == 'SMP') {
            $pdf = \App\Models\InformationPdf::where('type', 'kisi_kisi_ujian_smp')->get();
        }else if ($unit == 'SMA') {
            $pdf = \App\Models\InformationPdf::where('type', 'kisi_kisi_ujian_sma')->get();
        }else {
            $pdf = \App\Models\InformationPdf::where('type', 'kisi_kisi_ujian_smk')->get();
        }

        if ($pdf->isEmpty()) {
            return back()->with('error', 'Kisi-kisi ujian belum tersedia.');
        }

        $pdfLocation = $pdf->first()->file_path;
        $filePath = storage_path('app/public/' . $pdfLocation);

        if (!file_exists($filePath)) {
            return back()->with('error', 'Kisi-kisi ujian tidak ditemukan.');
        }

        return response()->download($filePath);
    }
}
