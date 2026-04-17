<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['name', 'is_active'];

    public function waves()
    {
        return $this->hasMany(RegistrationWave::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
