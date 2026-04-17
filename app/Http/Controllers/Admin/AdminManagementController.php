<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = \App\Models\User::whereIn('role', [
            \App\Models\User::ROLE_ADMIN_SMP,
            \App\Models\User::ROLE_ADMIN_SMA,
            \App\Models\User::ROLE_ADMIN_SMK,
            \App\Models\User::ROLE_ADMIN_ADM,
            \App\Models\User::ROLE_SUPER_ADMIN
        ])->get();

        return view('admin.super.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin_smp,admin_sma,admin_smk,admin_administrasi,super_admin',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'full_name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('status', 'User Admin berhasil ditambahkan.');
    }

    public function destroy(\App\Models\User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('status', 'User Admin berhasil dihapus.');
    }
}
