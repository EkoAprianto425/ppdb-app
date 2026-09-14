<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiBankLog extends Model
{
    protected $fillable = [
        'source',
        'level',
        'event',
        'va_number',
        'ip',
        'amount',
        'ref',
        'message',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'amount'  => 'float',
    ];

    // Source constants
    const SOURCE_INQUIRY  = 'VA_INQUIRY';
    const SOURCE_NOTIFY   = 'VA_NOTIFY';
    const SOURCE_BTN      = 'BTN_CALLBACK';

    // Level constants
    const LEVEL_INFO    = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR   = 'error';

    /**
     * Helper: tulis log ke DB.
     */
    public static function write(
        string $source,
        string $level,
        string $event,
        array  $context = []
    ): void {
        try {
            self::create([
                'source'    => $source,
                'level'     => $level,
                'event'     => $event,
                'va_number' => $context['va_number'] ?? null,
                'ip'        => $context['ip'] ?? null,
                'amount'    => $context['amount'] ?? null,
                'ref'       => $context['ref'] ?? null,
                'message'   => $context['message'] ?? null,
                'payload'   => $context['payload'] ?? null,
            ]);
        } catch (\Throwable $e) {
            // Fallback ke file log agar tidak matikan request
            \Illuminate\Support\Facades\Log::error('ApiBankLog::write failed: ' . $e->getMessage());
        }
    }
}
