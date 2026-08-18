<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\EducationalLevel;
use App\Models\RegistrationWave;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Discount::with(['educationalLevel', 'registrationWave']);

        // Filter based on role
        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            $query->where(function ($q) use ($levelIds) {
                $q->whereIn('educational_level_id', $levelIds)
                  ->orWhereNull('educational_level_id');
            });
        }

        // Filter based on parent_unit (SMP / SMA / SMK)
        if ($request->filled('level_id')) {
            if ($request->level_id === 'general') {
                $query->whereNull('educational_level_id');
            } else {
                // level_id is now a parent_unit string (SMP / SMA / SMK)
                $parentUnit = $request->level_id;
                $query->where(function ($q) use ($parentUnit) {
                    $q->whereNull('educational_level_id')
                      ->orWhereHas('educationalLevel', function ($q2) use ($parentUnit) {
                          $q2->where('parent_unit', $parentUnit);
                      });
                });
            }
        }

        $discounts = $query->latest()->get();

        // Build parent-unit options (distinct parent_unit from accessible levels)
        $activeYear = AcademicYear::where('is_active', true)->first();
        $allLevels  = EducationalLevel::all();
        if (!$user->isSuperAdmin()) {
            $allLevels = $allLevels->whereIn('id', $user->getManagedLevelIds());
        }
        // $levelsByParent: ['SMP' => <representative level>, 'SMA' => ..., 'SMK' => ...]
        $levelsByParent = $allLevels->groupBy('parent_unit')->map->first();

        $waves = RegistrationWave::where('academic_year_id', $activeYear?->id)->get();

        return view('admin.discounts.index', compact('discounts', 'levelsByParent', 'waves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'          => 'required|in:anak_pegawai,alumni,umum',
            'level_parent_unit' => 'nullable|in:SMP,SMA,SMK',
            'is_active'         => 'boolean',
        ]);

        $data = $request->all();

        // Clean currency formats
        if (isset($data['amount'])) {
            $data['amount'] = $this->parseCurrency($data['amount']);
        }
        if (isset($data['spp_amount'])) {
            $data['spp_amount'] = $this->parseCurrency($data['spp_amount']);
        }

        // Handle specific fields based on category
        if ($request->category === 'anak_pegawai') {
            $data['educational_level_id'] = null; // berlaku lintas jenjang
            $data['registration_wave_id'] = null; // berlaku lintas gelombang
            $data['apply_to']             = null;
            $data['qty']                  = null;
            $data['require_document']     = 1;
        } else {
            // Convert parent_unit string → representative educational_level_id
            if ($request->filled('level_parent_unit')) {
                $rep = EducationalLevel::where('parent_unit', $request->level_parent_unit)->first();
                $data['educational_level_id'] = $rep?->id;
            } else {
                $data['educational_level_id'] = null;
            }
            $data['registration_wave_id'] = null;
            $data['spp_amount']           = 0;
            $data['require_document']     = $request->has('require_document');
        }

        Discount::create($data);

        return redirect()->back()->with('success', 'Master potongan harga berhasil ditambahkan.');
    }

    public function update(Request $request, Discount $discount)
    {
        $data = $request->all();

        // Clean currency formats
        if (isset($data['amount'])) {
            $data['amount'] = $this->parseCurrency($data['amount']);
        }
        if (isset($data['spp_amount'])) {
            $data['spp_amount'] = $this->parseCurrency($data['spp_amount']);
        }

        if ($request->category === 'anak_pegawai') {
            $data['educational_level_id'] = null; // berlaku lintas jenjang
            $data['registration_wave_id'] = null; // berlaku lintas gelombang
            $data['apply_to']             = null;
            $data['qty']                  = null;
            $data['require_document']     = 1;
        } else {
            // Convert parent_unit string → representative educational_level_id
            if ($request->filled('level_parent_unit')) {
                $rep = EducationalLevel::where('parent_unit', $request->level_parent_unit)->first();
                $data['educational_level_id'] = $rep?->id;
            } else {
                $data['educational_level_id'] = null;
            }
            $data['registration_wave_id'] = null;
            $data['spp_amount']           = 0;
            $data['require_document']     = $request->has('require_document');
        }

        $data['is_active'] = $request->has('is_active');

        $discount->update($data);

        return redirect()->back()->with('success', 'Master potongan harga berhasil diperbarui.');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->back()->with('success', 'Master potongan harga berhasil dihapus.');
    }

    private function parseCurrency($value)
    {
        if (!$value) return 0;
        return (float) preg_replace('/[^0-9]/', '', $value);
    }
}
