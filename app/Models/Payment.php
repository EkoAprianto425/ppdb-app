<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'registration_id',
        'fee_type',
        'amount',
        'va_number',
        'va_ref',
        'payment_method',
        'va_bank',
        'paid_amount',  // Nominal yang benar-benar dibayarkan (cash, VA BTN, VA BCA)
        'status',
        'admin_note',
        'verified_by',
        'verified_at',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    const METHOD_VA = 'va';
    const METHOD_VA_BCA = 'va_bca';
    const METHOD_MANUAL = 'manual';
    const METHOD_CASH = 'cash'; // Bayar tunai di sekolah

    const BANK_BTN = 'btn';
    const BANK_BCA = 'bca';

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
