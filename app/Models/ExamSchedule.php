<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasActiveAcademicYear;

class ExamSchedule extends Model
{
    use HasActiveAcademicYear;

    protected $fillable = [
        'academic_year_id',
        'unit',
        'name',
        'date',
        'time_start',
        'time_end',
        'quota',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function getAvailableQuotaAttribute(): int
    {
        return $this->quota - $this->registrations()->count();
    }
}
