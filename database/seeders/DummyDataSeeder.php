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

        // Get common dependencies
        $levels = EducationalLevel::pluck('id')->toArray();
        if (empty($levels)) {
            $this->command->error('No Educational Levels found. Please run DatabaseSeeder first.');
            return;
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            $this->command->error('No active Academic Year found.');
            return;
        }

        $waves = RegistrationWave::where('academic_year_id', $activeYear->id)->pluck('id')->toArray();
        $examSchedules = ExamSchedule::where('academic_year_id', $activeYear->id)->pluck('id')->toArray();
        $fees = AdministrativeFee::all();

        $this->command->info('Creating 1000 dummy students...');

        $password = Hash::make('password123');
        $now = Carbon::now();

        $chunkSize = 100;
        $total = 1000;

        for ($i = 0; $i < $total; $i += $chunkSize) {
            DB::transaction(function () use ($chunkSize, $faker, $password, $now, $levels, $activeYear, $waves, $examSchedules, $fees) {
                
                for ($j = 0; $j < $chunkSize; $j++) {
                    $levelId = $faker->randomElement($levels);
                    
                    // 1. Create User
                    $user = User::create([
                        'name' => $faker->userName . rand(1000, 9999),
                        'full_name' => $faker->name,
                        'email' => $faker->unique()->safeEmail,
                        'whatsapp_number' => $faker->phoneNumber,
                        'password' => $password,
                        'asal_sekolah' => 'SMP ' . $faker->city,
                        'educational_level_id' => $levelId,
                        'alasan_memilih' => $faker->sentence,
                        'sumber_informasi' => $faker->randomElement(['Brosur', 'Teman/Keluarga', 'Sosial Media', 'Website']),
                        'role' => User::ROLE_SISWA,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    // 2. Create Registration
                    $status = $faker->randomElement(['baru', 'menunggu_pembayaran', 'menunggu_ujian', 'lulus', 'tidak_lulus']);
                    $paymentStatus = $faker->randomElement(['none', 'pending', 'success']);

                    // Set logical constraints based on status
                    if ($status === 'lulus' || $status === 'tidak_lulus' || $status === 'menunggu_ujian') {
                        $paymentStatus = 'success';
                    }

                    $registration = Registration::create([
                        'user_id' => $user->id,
                        'academic_year_id' => $activeYear->id,
                        'registration_wave_id' => empty($waves) ? null : $faker->randomElement($waves),
                        'exam_schedule_id' => empty($examSchedules) ? null : $faker->randomElement($examSchedules),
                        
                        'nama_panggilan' => $faker->firstName,
                        'anak_ke' => $faker->numberBetween(1, 5),
                        'dari_saudara' => $faker->numberBetween(1, 5),
                        'alamat' => $faker->address,
                        'provinsi' => $faker->state,
                        'kabupaten' => $faker->city,
                        'kecamatan' => $faker->citySuffix,
                        'kebutuhan_khusus' => $faker->randomElement(['Tidak Ada', 'Disabilitas Fisik', 'Lainnya']),
                        'tempat_lahir' => $faker->city,
                        'tanggal_lahir' => $faker->date('Y-m-d', '-12 years'),
                        'agama' => 'Islam',
                        'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                        
                        'nama_ayah' => $faker->name('male'),
                        'nama_ibu' => $faker->name('female'),
                        'pekerjaan_ayah' => $faker->jobTitle,
                        'pekerjaan_ibu' => $faker->jobTitle,
                        'pendidikan_ayah' => 'S1',
                        'pendidikan_ibu' => 'SMA',
                        'penghasilan_ayah' => $faker->numberBetween(3000000, 15000000),
                        'penghasilan_ibu' => $faker->numberBetween(0, 5000000),
                        
                        'payment_status' => $paymentStatus,
                        'status' => $status,
                        'reregistration_deadline' => ($status === 'lulus') ? $now->copy()->addDays(14)->format('Y-m-d') : null,
                        
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    // 3. Create Payments
                    $levelFees = $fees->where('educational_level_id', $levelId);
                    if ($paymentStatus !== 'none' && $levelFees->isNotEmpty()) {
                        foreach ($levelFees as $fee) {
                            // Cuma bayar formulir dulu (sort_order = 1) atau bayar semua jika lunas
                            if ($fee->sort_order == 1 || $status === 'lulus') {
                                $pmtStatus = ($paymentStatus === 'success') ? Payment::STATUS_SUCCESS : Payment::STATUS_PENDING;
                                Payment::create([
                                    'registration_id' => $registration->id,
                                    'fee_type' => $fee->name,
                                    'amount' => $fee->amount,
                                    'payment_method' => Payment::METHOD_VA,
                                    'va_number' => '94842' . date('ymd') . str_pad($registration->id, 4, '0', STR_PAD_LEFT) . str_pad($fee->sort_order, 2, '0', STR_PAD_LEFT),
                                    'va_ref' => str_pad($registration->id . date('ymd'), 10, '0', STR_PAD_LEFT),
                                    'status' => $pmtStatus,
                                    'verified_by' => ($pmtStatus === Payment::STATUS_SUCCESS) ? 1 : null,
                                    'verified_at' => ($pmtStatus === Payment::STATUS_SUCCESS) ? $now : null,
                                ]);
                            }
                        }
                    }
                }
            });

            $this->command->info('Inserted ' . ($i + $chunkSize) . ' records...');
        }

        $this->command->info('Successfully seeded 1000 dummy students.');
    }
}
