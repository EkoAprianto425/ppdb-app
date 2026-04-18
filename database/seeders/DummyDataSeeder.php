<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\AdministrativeFee;
use App\Models\EducationalLevel;
use App\Models\ExamSchedule;
use App\Models\Registration;
use App\Models\RegistrationWave;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ─────────────────────────────────────────────
        // 1. Jenjang Pendidikan (Educational Levels)
        // ─────────────────────────────────────────────
        $this->command->info('📚 Membuat data jenjang pendidikan...');

        $levels = [
            ['name' => 'SMP',          'parent_unit' => 'SMP', 'sort_order' => 1, 'contact_whatsapp' => '6281234567001'],
            ['name' => 'SMA Reguler',  'parent_unit' => 'SMA', 'sort_order' => 2, 'contact_whatsapp' => '6281234567002'],
            ['name' => 'SMA Tahfidz',  'parent_unit' => 'SMA', 'sort_order' => 3, 'contact_whatsapp' => '6281234567003'],
            ['name' => 'SMK TKJ',      'parent_unit' => 'SMK', 'sort_order' => 4, 'contact_whatsapp' => '6281234567004'],
            ['name' => 'SMK PBS',      'parent_unit' => 'SMK', 'sort_order' => 5, 'contact_whatsapp' => '6281234567005'],
            ['name' => 'SMK Kuliner',  'parent_unit' => 'SMK', 'sort_order' => 6, 'contact_whatsapp' => '6281234567006'],
        ];

        $levelModels = [];
        foreach ($levels as $level) {
            $levelModels[$level['name']] = EducationalLevel::updateOrCreate(
                ['name' => $level['name']],
                $level
            );
        }
        $this->command->info('  ✅ 6 jenjang pendidikan dibuat.');

        // ─────────────────────────────────────────────
        // 2. Biaya Administrasi per Jenjang
        // ─────────────────────────────────────────────
        $this->command->info('💰 Membuat data biaya administrasi...');

        $fees = [
            'SMP' => [
                ['name' => 'Biaya Formulir',     'amount' => 150000, 'sort_order' => 1],
                ['name' => 'Uang Pangkal',       'amount' => 5000000, 'sort_order' => 2],
                ['name' => 'Seragam',             'amount' => 1500000, 'sort_order' => 3],
                ['name' => 'Buku Paket',          'amount' => 800000, 'sort_order' => 4],
            ],
            'SMA Reguler' => [
                ['name' => 'Biaya Formulir',     'amount' => 200000, 'sort_order' => 1],
                ['name' => 'Uang Pangkal',       'amount' => 7000000, 'sort_order' => 2],
                ['name' => 'Seragam',             'amount' => 1800000, 'sort_order' => 3],
                ['name' => 'Buku Paket',          'amount' => 1000000, 'sort_order' => 4],
            ],
            'SMA Tahfidz' => [
                ['name' => 'Biaya Formulir',     'amount' => 200000, 'sort_order' => 1],
                ['name' => 'Uang Pangkal',       'amount' => 8000000, 'sort_order' => 2],
                ['name' => 'Seragam',             'amount' => 2000000, 'sort_order' => 3],
                ['name' => 'Buku Paket',          'amount' => 1000000, 'sort_order' => 4],
                ['name' => 'Program Tahfidz',     'amount' => 3000000, 'sort_order' => 5],
            ],
            'SMK TKJ' => [
                ['name' => 'Biaya Formulir',     'amount' => 200000, 'sort_order' => 1],
                ['name' => 'Uang Pangkal',       'amount' => 6500000, 'sort_order' => 2],
                ['name' => 'Seragam',             'amount' => 1800000, 'sort_order' => 3],
                ['name' => 'Buku Paket',          'amount' => 1000000, 'sort_order' => 4],
                ['name' => 'Peralatan Praktik',   'amount' => 2500000, 'sort_order' => 5],
            ],
            'SMK PBS' => [
                ['name' => 'Biaya Formulir',     'amount' => 200000, 'sort_order' => 1],
                ['name' => 'Uang Pangkal',       'amount' => 6500000, 'sort_order' => 2],
                ['name' => 'Seragam',             'amount' => 1800000, 'sort_order' => 3],
                ['name' => 'Buku Paket',          'amount' => 1000000, 'sort_order' => 4],
                ['name' => 'Peralatan Praktik',   'amount' => 2000000, 'sort_order' => 5],
            ],
            'SMK Kuliner' => [
                ['name' => 'Biaya Formulir',     'amount' => 200000, 'sort_order' => 1],
                ['name' => 'Uang Pangkal',       'amount' => 7000000, 'sort_order' => 2],
                ['name' => 'Seragam',             'amount' => 1800000, 'sort_order' => 3],
                ['name' => 'Buku Paket',          'amount' => 1000000, 'sort_order' => 4],
                ['name' => 'Peralatan Praktik',   'amount' => 3000000, 'sort_order' => 5],
            ],
        ];

        foreach ($fees as $levelName => $feeList) {
            foreach ($feeList as $fee) {
                AdministrativeFee::updateOrCreate(
                    [
                        'educational_level_id' => $levelModels[$levelName]->id,
                        'name' => $fee['name'],
                    ],
                    [
                        'amount' => $fee['amount'],
                        'sort_order' => $fee['sort_order'],
                    ]
                );
            }
        }
        $this->command->info('  ✅ Biaya administrasi dibuat untuk semua jenjang.');

        // ─────────────────────────────────────────────
        // 3. Tahun Ajaran & Gelombang
        // ─────────────────────────────────────────────
        $this->command->info('📅 Memastikan tahun ajaran & gelombang...');

        $ay = AcademicYear::where('is_active', true)->first();
        if (!$ay) {
            $ay = AcademicYear::updateOrCreate(
                ['name' => '2025/2026'],
                ['is_active' => true]
            );
        }

        $wave = RegistrationWave::where('academic_year_id', $ay->id)->where('is_active', true)->first();
        if (!$wave) {
            $wave = RegistrationWave::updateOrCreate(
                ['academic_year_id' => $ay->id, 'name' => 'Gelombang 1'],
                [
                    'start_date' => now()->subMonths(2),
                    'end_date' => now()->addMonths(3),
                    'is_active' => true,
                ]
            );
        }

        $this->command->info("  ✅ Tahun Ajaran: {$ay->name}, Gelombang: {$wave->name}");

        // ─────────────────────────────────────────────
        // 4. Jadwal Ujian per Unit
        // ─────────────────────────────────────────────
        $this->command->info('📝 Membuat jadwal ujian...');

        $units = ['SMP', 'SMA Reguler', 'SMA Tahfidz', 'SMK TKJ', 'SMK PBS', 'SMK Kuliner'];
        $examSchedules = [];

        foreach ($units as $i => $unit) {
            $schedule = ExamSchedule::updateOrCreate(
                [
                    'academic_year_id' => $ay->id,
                    'unit' => $unit,
                    'name' => "Ujian Masuk {$unit} - Sesi 1",
                ],
                [
                    'date' => now()->addMonths(1)->addDays($i),
                    'time_start' => '08:00:00',
                    'time_end' => '11:00:00',
                    'quota' => 200,
                ]
            );
            $examSchedules[$unit] = $schedule;

            $schedule2 = ExamSchedule::updateOrCreate(
                [
                    'academic_year_id' => $ay->id,
                    'unit' => $unit,
                    'name' => "Ujian Masuk {$unit} - Sesi 2",
                ],
                [
                    'date' => now()->addMonths(1)->addDays($i + 7),
                    'time_start' => '13:00:00',
                    'time_end' => '16:00:00',
                    'quota' => 200,
                ]
            );
        }
        $this->command->info('  ✅ 12 jadwal ujian dibuat (2 sesi × 6 unit).');

        // ─────────────────────────────────────────────
        // 5. Dummy Siswa (1000 students)
        // ─────────────────────────────────────────────
        $this->command->info('👨‍🎓 Membuat 1000 data siswa dummy...');

        $tujuanMasukOptions = ['SMP', 'SMA Reguler', 'SMA Tahfidz', 'SMK TKJ', 'SMK PBS', 'SMK Kuliner'];
        // Build a name->id map from the database
        $levelIdMap = EducationalLevel::pluck('id', 'name')->all();

        $agamaOptions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'];
        $jenisKelamin = ['Laki-laki', 'Perempuan'];
        $pendidikanOptions = ['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'];
        $pekerjaanOptions = ['PNS', 'TNI/Polri', 'Wiraswasta', 'Karyawan Swasta', 'Petani', 'Nelayan', 'Buruh', 'Guru', 'Dokter', 'Pedagang', 'Ibu Rumah Tangga', 'Tidak Bekerja'];
        $sumberInfoOptions = ['Media Sosial', 'Teman/Keluarga', 'Website Resmi', 'Brosur', 'Guru Sekolah', 'Pameran Pendidikan', 'Radio/TV'];
        $kebutuhanKhususOptions = ['Tidak Ada', 'Tidak Ada', 'Tidak Ada', 'Tidak Ada', 'Tidak Ada', 'Tunanetra', 'Tunarungu', 'Tunagrahita', 'Autisme', 'Lainnya'];

        $provinsiList = ['Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'DKI Jakarta', 'Banten', 'DI Yogyakarta'];
        $kabupatenMap = [
            'Jawa Barat' => ['Bandung', 'Bogor', 'Bekasi', 'Depok', 'Cimahi', 'Tasikmalaya', 'Garut', 'Sukabumi', 'Karawang', 'Cianjur'],
            'Jawa Tengah' => ['Semarang', 'Solo', 'Magelang', 'Pekalongan', 'Tegal', 'Purwokerto', 'Kudus', 'Salatiga'],
            'Jawa Timur' => ['Surabaya', 'Malang', 'Sidoarjo', 'Gresik', 'Mojokerto', 'Kediri', 'Jember', 'Banyuwangi'],
            'DKI Jakarta' => ['Jakarta Selatan', 'Jakarta Timur', 'Jakarta Barat', 'Jakarta Utara', 'Jakarta Pusat'],
            'Banten' => ['Tangerang', 'Tangerang Selatan', 'Serang', 'Cilegon', 'Pandeglang'],
            'DI Yogyakarta' => ['Sleman', 'Bantul', 'Gunung Kidul', 'Kulon Progo', 'Yogyakarta'],
        ];

        $kecamatanSamples = ['Cibiru', 'Coblong', 'Sukasari', 'Antapani', 'Arcamanik', 'Cidadap', 'Cinambo',
            'Gedebage', 'Kiaracondong', 'Lengkong', 'Mandalajati', 'Panyileukan', 'Rancasari', 'Ujungberung',
            'Bandung Kidul', 'Bandung Kulon', 'Bandung Wetan', 'Batununggal', 'Bojongloa', 'Cicendo',
            'Cibeunying', 'Regol', 'Sumur Bandung', 'Babakan Ciparay', 'Buahbatu', 'Margacinta',
            'Cibaduyut', 'Dago', 'Setiabudi', 'Lembang'];

        $asalSekolahList = [
            'SD Negeri 1', 'SD Negeri 2', 'SD Negeri 3', 'SD Negeri 4', 'SD Negeri 5',
            'SD Negeri 6', 'SD Negeri 7', 'SD Negeri 8', 'SD Negeri 9', 'SD Negeri 10',
            'SDN Ciparay 1', 'SDN Pasirkaliki 1', 'SDN Sukajadi 3', 'SDN Merdeka',
            'SD Islam Terpadu Al-Ikhlas', 'SD Islam Terpadu An-Nuur', 'SD IT Al-Falah',
            'SD Muhammadiyah 1', 'SD Muhammadiyah 2', 'SD Muhammadiyah 3',
            'SDIT Auliya', 'SDIT Cendekia', 'SDIT Harapan Bangsa',
            'MI Negeri 1', 'MI Negeri 2', 'MI Al-Hikmah', 'MI Darul Ulum',
            'SMP Negeri 1', 'SMP Negeri 2', 'SMP Negeri 3', 'SMP Negeri 4', 'SMP Negeri 5',
            'SMP Negeri 6', 'SMP Negeri 7', 'SMP Negeri 8', 'SMP Negeri 9', 'SMP Negeri 10',
            'SMP Islam Terpadu Al-Furqan', 'SMP IT Al-Falah', 'SMP Muhammadiyah 1',
            'MTs Negeri 1', 'MTs Negeri 2', 'MTs Al-Inayah', 'MTs Darul Hikmah',
        ];

        $alasanMemilih = [
            'Sekolah ini memiliki reputasi yang sangat baik di bidang akademik dan non-akademik, sehingga saya merasa yakin untuk mendaftar di sini.',
            'Saya tertarik dengan program unggulan dan kurikulum yang diterapkan di sekolah ini karena sesuai dengan minat dan bakat saya.',
            'Lokasi sekolah yang strategis dan fasilitas yang lengkap menjadi pertimbangan utama saya dalam memilih sekolah ini.',
            'Banyak alumni dari sekolah ini yang berhasil melanjutkan ke perguruan tinggi negeri terbaik di Indonesia.',
            'Saya ingin mengembangkan kemampuan di bidang teknologi dan informasi yang menjadi fokus utama jurusan ini.',
            'Program tahfidz yang ditawarkan sangat menarik karena saya ingin menghafal Al-Quran sambil belajar ilmu pengetahuan.',
            'Orang tua saya menyarankan sekolah ini karena kualitas pengajarannya yang terbukti menghasilkan lulusan berkompeten.',
            'Saya ingin belajar di lingkungan yang islami dengan nilai-nilai karakter yang kuat dan disiplin yang baik.',
            'Fasilitas laboratorium dan workshop yang lengkap sangat mendukung untuk kegiatan belajar praktik.',
            'Sekolah ini dikenal memiliki program magang dan kerjasama industri yang sangat baik untuk masa depan karir.',
            'Saya sangat tertarik dengan program kuliner di sekolah ini karena memasak adalah passion saya sejak kecil.',
            'Rekomendasi dari kakak kelas yang sudah bersekolah di sini membuat saya semakin yakin untuk mendaftar.',
            'Sekolah ini memiliki program beasiswa yang menarik dan lingkungan belajar yang kondusif.',
            'Saya ingin menjadi ahli di bidang jaringan komputer dan sekolah ini memiliki kurikulum terbaik untuk itu.',
            'Kualitas guru dan pembimbing di sekolah ini sangat profesional dan berpengalaman di bidangnya masing-masing.',
        ];

        $paymentStatuses = ['pending', 'verified', 'verified', 'verified', 'rejected']; // weighted toward verified
        $registrationStatuses = ['proses', 'proses', 'lulus', 'lulus', 'lulus', 'tidak_lulus'];

        $hashedPassword = Hash::make('password');
        $batchSize = 50;
        $studentCount = 0;

        for ($batch = 0; $batch < 20; $batch++) {
            $users = [];
            $registrations = [];

            for ($i = 0; $i < $batchSize; $i++) {
                $studentCount++;
                $gender = $faker->randomElement($jenisKelamin);
                $isMale = $gender === 'Laki-laki';
                $firstName = $isMale ? $faker->firstNameMale() : $faker->firstNameFemale();
                $lastName = $faker->lastName();
                $fullName = $firstName . ' ' . $lastName;
                $name = strtolower(str_replace(' ', '', $firstName)) . $studentCount;

                $tujuan = $faker->randomElement($tujuanMasukOptions);
                $provinsi = $faker->randomElement($provinsiList);
                $kabupaten = $faker->randomElement($kabupatenMap[$provinsi]);

                // Determine appropriate asal sekolah based on tujuan
                if ($tujuan === 'SMP') {
                    // SMP students come from SD/MI
                    $asalSekolah = $faker->randomElement(array_filter($asalSekolahList, fn($s) => str_starts_with($s, 'SD') || str_starts_with($s, 'MI')));
                } else {
                    // SMA/SMK students come from SMP/MTs
                    $asalSekolah = $faker->randomElement(array_filter($asalSekolahList, fn($s) => str_starts_with($s, 'SMP') || str_starts_with($s, 'MTs')));
                }
                $asalSekolah = $asalSekolah . ' ' . $kabupaten;

                $user = User::create([
                    'name' => $name,
                    'full_name' => $fullName,
                    'email' => "siswa{$studentCount}@ppdb.com",
                    'whatsapp_number' => '08' . $faker->numerify('##########'),
                    'password' => $hashedPassword,
                    'asal_sekolah' => $asalSekolah,
                    'educational_level_id' => $levelIdMap[$tujuan],
                    'alasan_memilih' => $faker->randomElement($alasanMemilih),
                    'sumber_informasi' => $faker->randomElement($sumberInfoOptions),
                    'role' => 'siswa',
                ]);

                // Determine payment & registration status (progressive)
                $paymentStatus = $faker->randomElement($paymentStatuses);
                $status = ($paymentStatus === 'verified') ? $faker->randomElement($registrationStatuses) : 'proses';

                // Assign exam schedule if payment verified
                $examScheduleId = null;
                if ($paymentStatus === 'verified') {
                    $schedule = ExamSchedule::where('unit', $tujuan)
                        ->where('academic_year_id', $ay->id)
                        ->inRandomOrder()
                        ->first();
                    $examScheduleId = $schedule?->id;
                }

                // Birth date based on tujuan (SMP: 11-13 y/o, SMA/SMK: 14-16 y/o)
                $birthYear = ($tujuan === 'SMP')
                    ? $faker->numberBetween(2012, 2014)
                    : $faker->numberBetween(2009, 2011);

                Registration::create([
                    'user_id' => $user->id,
                    'academic_year_id' => $ay->id,
                    'registration_wave_id' => $wave->id,
                    'nama_panggilan' => $firstName,
                    'anak_ke' => $faker->numberBetween(1, 5),
                    'dari_saudara' => $faker->numberBetween(1, 6),
                    'alamat' => $faker->streetAddress(),
                    'provinsi' => $provinsi,
                    'kabupaten' => $kabupaten,
                    'kecamatan' => $faker->randomElement($kecamatanSamples),
                    'kebutuhan_khusus' => $faker->randomElement($kebutuhanKhususOptions),
                    'tempat_lahir' => $kabupaten,
                    'tanggal_lahir' => $faker->dateTimeBetween("{$birthYear}-01-01", "{$birthYear}-12-31")->format('Y-m-d'),
                    'agama' => $faker->randomElement($agamaOptions),
                    'jenis_kelamin' => $gender,
                    'nama_ayah' => $faker->name('male'),
                    'nama_ibu' => $faker->name('female'),
                    'pekerjaan_ayah' => $faker->randomElement(array_filter($pekerjaanOptions, fn($p) => $p !== 'Ibu Rumah Tangga')),
                    'pekerjaan_ibu' => $faker->randomElement($pekerjaanOptions),
                    'pendidikan_ayah' => $faker->randomElement($pendidikanOptions),
                    'pendidikan_ibu' => $faker->randomElement($pendidikanOptions),
                    'penghasilan_ayah' => $faker->randomElement([1500000, 2000000, 2500000, 3000000, 3500000, 4000000, 5000000, 6000000, 7500000, 10000000, 15000000, 20000000]),
                    'penghasilan_ibu' => $faker->randomElement([0, 0, 0, 1000000, 1500000, 2000000, 2500000, 3000000, 4000000, 5000000]),
                    'payment_status' => $paymentStatus,
                    'status' => $status,
                    'exam_schedule_id' => $examScheduleId,
                    'reregistration_deadline' => ($status === 'lulus') ? now()->addMonths(1)->format('Y-m-d') : null,
                ]);
            }

            $this->command->info("  📦 Batch " . ($batch + 1) . "/20 — {$studentCount} siswa dibuat");
        }

        // ─────────────────────────────────────────────
        // Summary
        // ─────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('  ✅ DUMMY DATA BERHASIL DIBUAT!');
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('  📚 Jenjang     : 6 jenjang pendidikan');
        $this->command->info('  💰 Biaya       : ' . AdministrativeFee::count() . ' item biaya administrasi');
        $this->command->info('  📝 Jadwal Ujian: ' . ExamSchedule::where('academic_year_id', $ay->id)->count() . ' sesi');
        $this->command->info('  👨‍🎓 Siswa       : 1000 siswa dengan data registrasi');
        $this->command->newLine();

        // Distribution summary
        foreach ($tujuanMasukOptions as $t) {
            $count = User::where('role', 'siswa')->where('educational_level_id', $levelIdMap[$t])->count();
            $this->command->info("  📊 {$t}: {$count} siswa");
        }
        $this->command->newLine();
    }
}
