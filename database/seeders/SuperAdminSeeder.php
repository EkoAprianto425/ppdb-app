<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Super Admin
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@ppdb.com'],
            [
                'name' => 'Super Admin',
                'full_name' => 'Administrator Utama',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // 2. Create Sample Academic Year
        $ay = \App\Models\AcademicYear::updateOrCreate(
            ['name' => '2025/2026'],
            ['is_active' => true]
        );

        // 3. Create Sample Wave
        \App\Models\RegistrationWave::updateOrCreate(
            ['academic_year_id' => $ay->id, 'name' => 'Gelombang 1'],
            [
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'is_active' => true,
            ]
        );

        // 4. Create Unit Admins for Testing
        $units = [
            'SMP' => \App\Models\User::ROLE_ADMIN_SMP,
            'SMA' => \App\Models\User::ROLE_ADMIN_SMA,
            'SMK' => \App\Models\User::ROLE_ADMIN_SMK,
        ];

        foreach ($units as $unitName => $role) {
            \App\Models\User::updateOrCreate(
                ['email' => 'admin.' . strtolower($unitName) . '@ppdb.com'],
                [
                    'name' => 'Admin ' . $unitName,
                    'full_name' => 'Administrator ' . $unitName,
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'role' => $role,
                ]
            );
        }

        // 5. Create Financial Admin
        \App\Models\User::updateOrCreate(
            ['email' => 'admin.adm@ppdb.com'],
            [
                'name' => 'Admin Keuangan',
                'full_name' => 'Administrator Administrasi',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => \App\Models\User::ROLE_ADMIN_ADM,
            ]
        );
    }
}
