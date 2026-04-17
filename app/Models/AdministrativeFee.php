<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdministrativeFee extends Model
{
    protected $fillable = [
        'educational_level_id',
        'name',
        'amount',
        'sort_order',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(EducationalLevel::class, 'educational_level_id');
    }
}
