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

        $students = $query->get();
        $fees = \App\Models\AdministrativeFee::all()->groupBy('educational_level_id');
        $levels = \App\Models\EducationalLevel::all();

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
                if ($status === 'tamu') $tamu++;
                elseif ($status === 'Formulir') $formulir++;
                elseif ($status === 'Lulus') $lulus++;
                elseif ($status === 'daftar') $daftar++;
            }

            $statsByLevel[] = [
                'name' => $level->name,
                'tamu' => $tamu,
                'formulir' => $formulir,
                'lulus' => $lulus,
                'daftar' => $daftar,
                'total' => $levelStudents->count()
            ];
        }

        $stats = [
            'unit' => $user->isSuperAdmin() ? 'Global' : $user->getUnit(),
            'levels' => $statsByLevel
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
            return 'Lulus';
        }

        if ($formulirFeeName && $successPayments->where('fee_type', $formulirFeeName)->isNotEmpty()) {
            return 'Formulir';
        }

        return 'tamu';
    }
}
