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
        $waves = RegistrationWave::where('academic_year_id', $activeYear->id)->pluck('id')->toArray();

        $this->command->info('Creating 1000 dummy students (up to form filling)...');

        $password = Hash::make('password123');
        $now = Carbon::now();

        $chunkSize = 100;
        $total = 1000;

        for ($i = 0; $i < $total; $i += $chunkSize) {
            DB::transaction(function () use ($chunkSize, $faker, $password, $now, $levels, $activeYear, $waves) {
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
                    Registration::create([
                        'user_id' => $user->id,
                        'academic_year_id' => $activeYear->id,
                        'registration_wave_id' => $faker->randomElement($waves),
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
                }
            });
            $this->command->info('Inserted ' . ($i + $chunkSize) . ' students...');
        }

        $this->command->info('Successfully seeded 1000 dummy students.');
    }

    private function ensureMasterData()
    {
        // Levels
        if (EducationalLevel::count() === 0) {
            $this->command->info('Seeding Educational Levels...');
            EducationalLevel::create(['name' => 'SMP', 'parent_unit' => 'SMP', 'sort_order' => 1]);
            EducationalLevel::create(['name' => 'SMA', 'parent_unit' => 'SMA', 'sort_order' => 2]);
            EducationalLevel::create(['name' => 'SMK', 'parent_unit' => 'SMK', 'sort_order' => 3]);
        }

        // Academic Year
        $year = AcademicYear::where('is_active', true)->first();
        if (!$year) {
            $this->command->info('Seeding Academic Year...');
            $year = AcademicYear::create([
                'name' => '2025/2026',
                'is_active' => true
            ]);
        }

        // Waves
        if (RegistrationWave::where('academic_year_id', $year->id)->count() === 0) {
            $this->command->info('Seeding Registration Waves...');
            RegistrationWave::create([
                'academic_year_id' => $year->id,
                'name' => 'Gelombang 1',
                'start_date' => Carbon::now()->subMonth(),
                'end_date' => Carbon::now()->addMonth(),
                'is_active' => true
            ]);
        }

        // Fees
        if (AdministrativeFee::count() === 0) {
            $this->command->info('Seeding Administrative Fees...');
            foreach (EducationalLevel::all() as $level) {
                AdministrativeFee::create([
                    'educational_level_id' => $level->id,
                    'name' => 'Biaya Formulir',
                    'amount' => 250000,
                    'sort_order' => 1
                ]);
                AdministrativeFee::create([
                    'educational_level_id' => $level->id,
                    'name' => 'Uang Pangkal',
                    'amount' => 5000000,
                    'sort_order' => 2
                ]);
            }
        }

        // Schedules
        if (ExamSchedule::count() === 0) {
            $this->command->info('Seeding Exam Schedules...');
            foreach (EducationalLevel::all() as $level) {
                ExamSchedule::create([
                    'academic_year_id' => $year->id,
                    'educational_level_id' => $level->id,
                    'date' => Carbon::now()->addDays(7)->format('Y-m-d'),
                    'time_start' => '08:00',
                    'time_end' => '12:00',
                    'location' => 'Kampus Pusat ' . $level->name,
                    'quota' => 100,
                ]);
            }
        }
    }
}
