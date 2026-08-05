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
}
