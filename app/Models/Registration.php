<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasActiveAcademicYear;

class Registration extends Model
{
    use HasActiveAcademicYear;

    protected $fillable = [
        'user_id',
        'academic_year_id',
        'registration_wave_id',
        'nama_panggilan',
        'anak_ke',
        'dari_saudara',
        'alamat',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kebutuhan_khusus',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'jenis_kelamin',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'pendidikan_ayah',
        'pendidikan_ibu',
        'penghasilan_ayah',
        'penghasilan_ibu',
        'payment_proof',
        'payment_status',
        'status',
        'reregistration_deadline',
        'exam_schedule_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function registrationWave(): BelongsTo
    {
        return $this->belongsTo(RegistrationWave::class)->withoutGlobalScope(\App\Scopes\ActiveAcademicYearScope::class);
    }

    public function examSchedule(): BelongsTo
    {
        return $this->belongsTo(ExamSchedule::class)->withoutGlobalScope(\App\Scopes\ActiveAcademicYearScope::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
