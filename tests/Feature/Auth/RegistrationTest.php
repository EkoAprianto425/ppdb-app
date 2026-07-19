<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $level = \App\Models\EducationalLevel::create([
        'name' => 'SMP',
        'parent_unit' => 'SMP',
        'sort_order' => 1,
    ]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'full_name' => 'Test User Full Name',
        'email' => 'test@example.com',
        'whatsapp_number' => '081234567890',
        'password' => 'password',
        'password_confirmation' => 'password',
        'asal_sekolah' => 'SMP Al Hasra',
        'educational_level_id' => $level->id,
        'alasan_memilih' => 'Saya ingin belajar di sekolah yang bagus dan religius.',
        'sumber_informasi' => 'Website Sekolah',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
