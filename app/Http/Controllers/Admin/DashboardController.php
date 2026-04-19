<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = \App\Models\Registration::query();

        if (!$user->isSuperAdmin()) {
            $levelIds = $user->getManagedLevelIds();
            $query->whereHas('user', function($q) use ($levelIds) {
                $q->whereIn('educational_level_id', $levelIds);
            });
        }

        $stats = [
            'total' => $query->count(),
            'pending' => (clone $query)->where('payment_status', 'pending')->count(),
            'success' => (clone $query)->where('payment_status', 'success')->count(),
            'unit' => $user->isSuperAdmin() ? 'Global' : $user->getUnit()
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
