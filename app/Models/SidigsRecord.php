<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SidigsRecord extends Model
{
    protected $fillable = ['registration_id', 'status', 'response_payload'];
    protected $casts = ['response_payload' => 'array'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
