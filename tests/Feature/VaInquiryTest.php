<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VaInquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_va_data_by_path_param(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'full_name' => 'Budi Santoso Complete',
        ]);

        $registration = Registration::create([
            'user_id' => $user->id,
            'nama_panggilan' => 'Budi',
            'anak_ke' => 1,
            'dari_saudara' => 2,
            'alamat' => 'Jl. Merdeka No. 1',
            'provinsi' => 'DKI Jakarta',
            'kabupaten' => 'Jakarta Pusat',
            'kecamatan' => 'Gambir',
            'kebutuhan_khusus' => 'Tidak Ada',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-01-01',
            'agama' => 'Islam',
            'jenis_kelamin' => 'L',
            'nama_ayah' => 'Ayah Budi',
            'nama_ibu' => 'Ibu Budi',
            'pekerjaan_ayah' => 'PNS',
            'pekerjaan_ibu' => 'Ibu Rumah Tangga',
            'pendidikan_ayah' => 'S1',
            'pendidikan_ibu' => 'SMA',
            'penghasilan_ayah' => 5000000,
            'penghasilan_ibu' => 0,
        ]);

        $payment = Payment::create([
            'registration_id' => $registration->id,
            'fee_type' => 'Formulir Pendaftaran',
            'amount' => 250000,
            'va_number' => '988123456789',
            'va_ref' => 'REF988123',
            'payment_method' => Payment::METHOD_VA,
            'status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->getJson('/api/va/988123456789');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'responseCode' => '2002500',
                'data' => [
                    'nama_siswa' => 'Budi Santoso Complete',
                    'fee_type' => 'Formulir Pendaftaran',
                    'va' => '988123456789',
                    'nominal' => 250000,
                ]
            ]);
    }

    public function test_can_fetch_va_data_by_post_inquiry(): void
    {
        $user = User::factory()->create([
            'name' => 'Siti Aminah',
            'full_name' => 'Siti Aminah',
        ]);

        $registration = Registration::create([
            'user_id' => $user->id,
            'nama_panggilan' => 'Siti',
            'anak_ke' => 1,
            'dari_saudara' => 2,
            'alamat' => 'Jl. Merdeka No. 2',
            'provinsi' => 'DKI Jakarta',
            'kabupaten' => 'Jakarta Selatan',
            'kecamatan' => 'Kebayoran',
            'kebutuhan_khusus' => 'Tidak Ada',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-02-02',
            'agama' => 'Islam',
            'jenis_kelamin' => 'P',
            'nama_ayah' => 'Ayah Siti',
            'nama_ibu' => 'Ibu Siti',
            'pekerjaan_ayah' => 'Swasta',
            'pekerjaan_ibu' => 'Wirausaha',
            'pendidikan_ayah' => 'S1',
            'pendidikan_ibu' => 'S1',
            'penghasilan_ayah' => 7000000,
            'penghasilan_ibu' => 3000000,
        ]);

        $payment = Payment::create([
            'registration_id' => $registration->id,
            'fee_type' => 'Uang Masuk',
            'amount' => 5000000,
            'va_number' => '988987654321',
            'va_ref' => 'REF988987',
            'payment_method' => Payment::METHOD_VA,
            'status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->postJson('/api/va/inquiry', [
            'va' => '988987654321'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'nama_siswa' => 'Siti Aminah',
                    'fee_type' => 'Uang Masuk',
                    'va' => '988987654321',
                    'nominal' => 5000000,
                ]
            ]);
    }

    public function test_returns_404_when_va_not_found(): void
    {
        $response = $this->getJson('/api/va/999999999999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'responseCode' => '4042512',
                'message' => 'Data Virtual Account tidak ditemukan.',
            ]);
    }
}
