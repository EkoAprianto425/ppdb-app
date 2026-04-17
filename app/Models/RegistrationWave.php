<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasActiveAcademicYear;

class RegistrationWave extends Model
{
    use HasActiveAcademicYear;

    protected $fillable = ['academic_year_id', 'name', 'start_date', 'end_date', 'is_active'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
