<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'full_name'        => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'whatsapp_number'  => ['required', 'string', 'max:20'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
            'asal_sekolah'     => ['required', 'string', 'max:255'],
            'educational_level_id' => ['required', 'exists:educational_levels,id'],
            'alasan_memilih'   => ['required', 'string', 'min:20', 'max:1000'],
            'sumber_informasi' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => 'Nama pembuat akun wajib diisi.',
            'full_name.required'        => 'Nama lengkap siswa wajib diisi.',
            'email.required'            => 'Email wajib diisi.',
            'email.unique'              => 'Email sudah terdaftar, gunakan email lain.',
            'whatsapp_number.required'  => 'Nomor WhatsApp wajib diisi.',
            'password.required'         => 'Password wajib diisi.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
            'asal_sekolah.required'     => 'Asal sekolah wajib diisi.',
            'educational_level_id.required' => 'Pilihan tujuan masuk wajib dipilih.',
            'educational_level_id.exists'   => 'Tujuan masuk tidak valid.',
            'alasan_memilih.required'   => 'Alasan memilih sekolah wajib diisi.',
            'alasan_memilih.min'        => 'Alasan memilih minimal 20 karakter.',
            'sumber_informasi.required' => 'Sumber informasi wajib diisi.',
        ];
    }
}
