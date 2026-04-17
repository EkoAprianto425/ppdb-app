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
            $unit = $user->getUnit();
            $query->whereHas('user', function($q) use ($unit) {
                $q->where('tujuan_masuk', $unit);
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
