<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah';

    protected $fillable = [
        'sekolah_id',
        'kode_prop',
        'propinsi',
        'kode_kab_kota',
        'kabupaten_kota',
        'kode_kec',
        'kecamatan',
        'npsn',
        'sekolah',
        'bentuk',
        'status',
        'alamat_jalan',
        'lintang',
        'bujur',
    ];
}
