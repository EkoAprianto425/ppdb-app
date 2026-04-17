<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSchedule;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = ExamSchedule::with('academicYear')->withCount('registrations');

        if (!$user->isSuperAdmin()) {
            $query->where('unit', $user->getUnit());
        }

        $schedules = $query->latest()->get();
        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('admin.schedules.index', compact('schedules', 'activeYear'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required',
        ]);

        ExamSchedule::create([
            'academic_year_id' => $activeYear->id,
            'unit' => $user->isSuperAdmin() ? $request->unit : $user->getUnit(),
            'name' => $request->name,
            'date' => $request->date,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'quota' => 9999, // Kuota diatur tinggi karena field dihapus dari UI
        ]);

        return back()->with('status', 'Jadwal ujian berhasil ditambahkan.');
    }

    public function destroy(ExamSchedule $schedule)
    {
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $schedule->unit !== $user->getUnit()) {
            abort(403);
        }

        if ($schedule->registrations()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus jadwal yang sudah memiliki pendaftar.');
        }

        $schedule->delete();
        return back()->with('status', 'Jadwal ujian berhasil dihapus.');
    }
}
