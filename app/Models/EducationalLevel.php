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
    ];

    public function fees(): HasMany
    {
        return $this->hasMany(AdministrativeFee::class, 'educational_level_id');
    }
}
