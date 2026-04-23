<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\AcademicYear;
use App\Models\RegistrationWave;
use App\Models\ExamSchedule;
use App\Models\EducationalLevel;
use App\Models\AdministrativeFee;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // 1. Kosongkan database yang berkaitan dengan siswa
        $this->command->info('Cleaning up existing student data...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Payment::truncate();
        Registration::truncate();
        User::where('role', User::ROLE_SISWA)->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Pastikan Master Data Ada
        $this->ensureMasterData();

        // 3. Get Dependencies
        $levels = EducationalLevel::pluck('id')->toArray();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $waves = RegistrationWave::where('academic_year_id', $activeYear->id)->get();
        $waveIds = $waves->pluck('id')->toArray();

        $this->command->info('Creating 1000 dummy students (distributing across 3 waves)...');

        $password = Hash::make('password123');
        $now = Carbon::now();

        $chunkSize = 100;
        $total = 1000;

        for ($i = 0; $i < $total; $i += $chunkSize) {
            DB::transaction(function () use ($chunkSize, $faker, $password, $now, $levels, $activeYear, $waveIds) {
                for ($j = 0; $j < $chunkSize; $j++) {
                    $levelId = $faker->randomElement($levels);
                    
                    // Create User
                    $user = User::create([
                        'name' => strtolower($faker->firstName . $faker->lastName . rand(100, 999)),
                        'full_name' => $faker->name,
                        'email' => $faker->unique()->safeEmail,
                        'whatsapp_number' => '08' . $faker->numerify('##########'),
                        'password' => $password,
                        'asal_sekolah' => 'Sekolah ' . $faker->city,
                        'educational_level_id' => $levelId,
                        'alasan_memilih' => $faker->sentence(),
                        'sumber_informasi' => $faker->randomElement(['Sosial Media', 'Brosur', 'Teman', 'Website']),
                        'role' => User::ROLE_SISWA,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    // Create Registration
                    $reg = Registration::create([
                        'user_id' => $user->id,
                        'academic_year_id' => $activeYear->id,
                        'registration_wave_id' => $faker->randomElement($waveIds),
                        'nama_panggilan' => $faker->firstName,
                        'anak_ke' => $faker->numberBetween(1, 3),
                        'dari_saudara' => $faker->numberBetween(1, 5),
                        'alamat' => $faker->address,
                        'provinsi' => $faker->state,
                        'kabupaten' => $faker->city,
                        'kecamatan' => $faker->citySuffix,
                        'kebutuhan_khusus' => 'Tidak Ada',
                        'tempat_lahir' => $faker->city,
                        'tanggal_lahir' => $faker->date('Y-m-d', '-13 years'),
                        'agama' => 'Islam',
                        'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                        'nama_ayah' => $faker->name('male'),
                        'nama_ibu' => $faker->name('female'),
                        'pekerjaan_ayah' => $faker->jobTitle,
                        'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                        'pendidikan_ayah' => 'S1',
                        'pendidikan_ibu' => 'SMA',
                        'penghasilan_ayah' => $faker->numberBetween(3000000, 10000000),
                        'penghasilan_ibu' => 0,
                        'payment_status' => 'none',
                        'status' => 'baru',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    // 60% probability of paying for the form (Status: Formulir)
                    if ($faker->boolean(60)) {
                        Payment::create([
                            'registration_id' => $reg->id,
                            'fee_type' => 'Biaya Formulir',
                            'amount' => 250000,
                            'payment_method' => 'manual',
                            'status' => 'success',
                            'verified_at' => $now,
                            'created_at' => $now,
                        ]);
                        $reg->update(['payment_status' => 'partial']);
                    }
                }
            });
            $this->command->info('Inserted ' . ($i + $chunkSize) . ' students...');
        }

        $this->command->info('Successfully seeded 1000 dummy students.');
    }

    private function ensureMasterData()
    {
        // 1. Levels (Detailed list from user image)
        $this->command->info('Updating Educational Levels...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        EducationalLevel::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $levelData = [
            ['name' => 'SMP', 'parent_unit' => 'SMP', 'sort_order' => 1],
            ['name' => 'SMP Kelas Progresif', 'parent_unit' => 'SMP', 'sort_order' => 2],
            ['name' => 'SMA Plus', 'parent_unit' => 'SMA', 'sort_order' => 3],
            ['name' => 'SMA Kelas Progresif', 'parent_unit' => 'SMA', 'sort_order' => 4],
            ['name' => 'SMK Bisnis Kuliner', 'parent_unit' => 'SMK', 'sort_order' => 5],
            ['name' => 'SMK PB', 'parent_unit' => 'SMK', 'sort_order' => 6],
            ['name' => 'SMK TJKT', 'parent_unit' => 'SMK', 'sort_order' => 7],
        ];

        foreach ($levelData as $ld) {
            EducationalLevel::create($ld);
        }

        // 2. Academic Year
        $year = AcademicYear::where('is_active', true)->first();
        if (!$year) {
            $this->command->info('Seeding Academic Year...');
            $year = AcademicYear::create([
                'name' => '2025/2026',
                'is_active' => true
            ]);
        }

        // 3. Waves (Ensure 3 waves)
        if (RegistrationWave::where('academic_year_id', $year->id)->count() < 3) {
            $this->command->info('Seeding 3 Registration Waves...');
            RegistrationWave::where('academic_year_id', $year->id)->delete();
            RegistrationWave::create([
                'academic_year_id' => $year->id,
                'name' => 'Gelombang 1',
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->subMonth(),
                'is_active' => false
            ]);
            RegistrationWave::create([
                'academic_year_id' => $year->id,
                'name' => 'Gelombang 2',
                'start_date' => Carbon::now()->subMonth(),
                'end_date' => Carbon::now()->addMonth(),
                'is_active' => true
            ]);
            RegistrationWave::create([
                'academic_year_id' => $year->id,
                'name' => 'Gelombang 3',
                'start_date' => Carbon::now()->addMonth(),
                'end_date' => Carbon::now()->addMonths(2),
                'is_active' => false
            ]);
        }

        // 4. Detailed Administrative Fees
        $this->command->info('Updating Administrative Fees (Complete Set)...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AdministrativeFee::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $feeTemplates = [
            ['name' => 'Biaya Formulir', 'amount' => 250000, 'sort_order' => 1],
            ['name' => 'Uang Pangkal / Infaq Pendidikan', 'amount' => 6500000, 'sort_order' => 2],
            ['name' => 'SPP Bulan Juli', 'amount' => 850000, 'sort_order' => 3],
            ['name' => 'Biaya Seragam (5 Stel)', 'amount' => 1250000, 'sort_order' => 4],
            ['name' => 'Paket Buku & Modul', 'amount' => 950000, 'sort_order' => 5],
            ['name' => 'Biaya Kegiatan Tahunan', 'amount' => 1500000, 'sort_order' => 6],
        ];

        foreach (EducationalLevel::all() as $level) {
            foreach ($feeTemplates as $template) {
                // Variations based on level
                $amount = $template['amount'];
                if (str_contains($level->name, 'SMA')) $amount += 500000;
                if (str_contains($level->name, 'SMK')) $amount += 750000;
                if (str_contains($level->name, 'Progresif')) $amount += 1000000;

                AdministrativeFee::create([
                    'educational_level_id' => $level->id,
                    'name' => $template['name'],
                    'amount' => $amount,
                    'sort_order' => $template['sort_order']
                ]);
            }
        }

        // 5. Schedules
        $this->command->info('Updating Exam Schedules...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ExamSchedule::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        foreach (EducationalLevel::all() as $level) {
            ExamSchedule::create([
                'academic_year_id' => $year->id,
                'educational_level_id' => $level->id,
                'unit' => $level->parent_unit,
                'name' => 'Tes Masuk ' . $level->name,
                'date' => Carbon::now()->addDays(rand(7, 21))->format('Y-m-d'),
                'time_start' => '08:00',
                'time_end' => '12:00',
                'quota' => 150,
            ]);
        }
    }
}
