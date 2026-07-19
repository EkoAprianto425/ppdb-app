<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountApplication extends Model
{
    protected $fillable = [
        'registration_id',
        'discount_id',
        'employee_status',
        'status',
        'document_path',
        'notes',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
