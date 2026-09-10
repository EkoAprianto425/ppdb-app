<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationalLevel extends Model
{
    protected $fillable = [
        'name',
        'parent_unit',
        'contact_whatsapp',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function fees(): HasMany
    {
        return $this->hasMany(AdministrativeFee::class, 'educational_level_id');
    }
}
