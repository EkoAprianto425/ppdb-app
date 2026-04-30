<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'category',
        'name',
        'educational_level_id',
        'registration_wave_id',
        'amount',
        'spp_amount',
        'qty',
        'description',
        'apply_to',
        'require_document',
        'is_active',
    ];

    public function educationalLevel()
    {
        return $this->belongsTo(EducationalLevel::class);
    }

    public function registrationWave()
    {
        return $this->belongsTo(RegistrationWave::class);
    }
}
