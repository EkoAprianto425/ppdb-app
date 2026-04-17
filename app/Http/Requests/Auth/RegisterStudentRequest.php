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
            'tujuan_masuk'     => ['required', 'in:SMP,SMA,SMK TKJ,SMK PBS,SMK Kuliner'],
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
            'tujuan_masuk.required'     => 'Pilihan tujuan masuk wajib dipilih.',
            'tujuan_masuk.in'           => 'Tujuan masuk tidak valid.',
            'alasan_memilih.required'   => 'Alasan memilih sekolah wajib diisi.',
            'alasan_memilih.min'        => 'Alasan memilih minimal 20 karakter.',
            'sumber_informasi.required' => 'Sumber informasi wajib diisi.',
        ];
    }
}
