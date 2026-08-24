<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = \App\Models\User::where('role', \App\Models\User::ROLE_SISWA)
            ->with(['registration.payments', 'educationalLevel']);

        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            $query->whereIn('educational_level_id', $levelIds);
        }

        $students = $query->latest()->get();
        $fees = \App\Models\AdministrativeFee::all()->groupBy('educational_level_id');
        $levels = \App\Models\EducationalLevel::all();

        $globalTamu = 0;
        $globalFormulir = 0;
        $globalLulus = 0;
        $globalDaftar = 0;

        $statsByLevel = [];
        foreach ($levels as $level) {
            // Skip if admin doesn't manage this level
            if (!$user->isSuperAdmin()) {
                $managedIds = $user->getManagedLevelIds();
                if (!in_array($level->id, $managedIds)) continue;
            }

            $levelStudents = $students->where('educational_level_id', $level->id);
            
            $tamu = 0;
            $formulir = 0;
            $lulus = 0;
            $daftar = 0;

            foreach ($levelStudents as $student) {
                $status = $this->calculateStatus($student, $fees);
                
                $tamu++; $globalTamu++;
                if (in_array($status, ['formulir', 'lulus', 'daftar'])) { $formulir++; $globalFormulir++; }
                if (in_array($status, ['lulus', 'daftar'])) { $lulus++; $globalLulus++; }
                if ($status === 'daftar') { $daftar++; $globalDaftar++; }
            }

            $statsByLevel[] = [
                'id' => $level->id,
                'name' => $level->name,
                'tamu' => $tamu,
                'formulir' => $formulir,
                'lulus' => $lulus,
                'daftar' => $daftar,
                'total' => $levelStudents->count()
            ];
        }

        // 4. Wave Stats (Detailed)
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $waves = \App\Models\RegistrationWave::where('academic_year_id', $activeYear?->id)->get();
        $detailedWaveStats = [
            'tamu' => [],
            'formulir' => [],
            'lulus' => [],
            'daftar' => []
        ];

        foreach ($waves as $wave) {
            foreach ($levels as $level) {
                if (!$user->isSuperAdmin()) {
                    $managedIds = $user->getManagedLevelIds();
                    if (!in_array($level->id, $managedIds)) continue;
                }

                $studentIdsInWave = \App\Models\Registration::where('registration_wave_id', $wave->id)
                    ->pluck('user_id')
                    ->toArray();
                
                $levelWaveStudents = $students->where('educational_level_id', $level->id)
                    ->whereIn('id', $studentIdsInWave);

                $tamu = 0; $formulir = 0; $lulus = 0; $daftar = 0;
                foreach ($levelWaveStudents as $student) {
                    $status = $this->calculateStatus($student, $fees);
                    
                    $tamu++;
                    if (in_array($status, ['formulir', 'lulus', 'daftar'])) $formulir++;
                    if (in_array($status, ['lulus', 'daftar'])) $lulus++;
                    if ($status === 'daftar') $daftar++;
                }

                $detailedWaveStats['tamu'][$wave->name][$level->name] = $tamu;
                $detailedWaveStats['formulir'][$wave->name][$level->name] = $formulir;
                $detailedWaveStats['lulus'][$wave->name][$level->name] = $lulus;
                $detailedWaveStats['daftar'][$wave->name][$level->name] = $daftar;
            }
        }

        $stats = [
            'summary' => [
                'tamu' => $globalTamu,
                'formulir' => $globalFormulir,
                'lulus' => $globalLulus,
                'daftar' => $globalDaftar,
                'total' => $students->count(),
            ],
            'unit' => $user->isSuperAdmin() ? 'Global' : $user->getUnit(),
            'levels' => $statsByLevel,
            'wave_names' => $waves->pluck('name')->toArray(),
            'detailed_waves' => $detailedWaveStats,
            'recent_students' => $students->take(5)
        ];

        return view('admin.dashboard', compact('stats'));
    }

    private function calculateStatus($user, $feesGrouped)
    {
        $reg = $user->registration;
        if (!$reg) return 'tamu';

        $levelFees = $feesGrouped->get($user->educational_level_id) ?? collect();
        $formulirFeeName = $levelFees->where('sort_order', 1)->first()?->name;
        $successPayments = $reg->payments->where('status', 'success');

        $otherFeeNames = $levelFees->where('sort_order', '>', 1)->pluck('name')->toArray();
        if ($successPayments->whereIn('fee_type', $otherFeeNames)->isNotEmpty()) {
            return 'daftar';
        }

        if ($reg->status === 'lulus') {
            return 'lulus';
        }

        if ($formulirFeeName && $successPayments->where('fee_type', $formulirFeeName)->isNotEmpty()) {
            return 'formulir';
        }

        return 'tamu';
    }
}
