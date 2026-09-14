<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SidigsRecord extends Model
{
    protected $fillable = ['registration_id', 'status', 'response_payload', 'student_username', 'student_password', 'wali_username', 'wali_password'];
    protected $casts = ['response_payload' => 'array'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
