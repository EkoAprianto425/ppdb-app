<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'full_name',
        'email',
        'whatsapp_number',
        'password',
        'asal_sekolah',
        'educational_level_id',
        'alasan_memilih',
        'sumber_informasi',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN_SMP = 'admin_smp';
    const ROLE_ADMIN_SMA = 'admin_sma';
    const ROLE_ADMIN_SMK = 'admin_smk';
    const ROLE_ADMIN_ADM = 'admin_administrasi';
    const ROLE_SISWA = 'siswa';

    public function isAdmin(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN_SMP,
            self::ROLE_ADMIN_SMA,
            self::ROLE_ADMIN_SMK,
            self::ROLE_ADMIN_ADM,
            self::ROLE_SUPER_ADMIN
        ]);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Relationship to the educational level (jenjang pendidikan).
     */
    public function educationalLevel()
    {
        return $this->belongsTo(EducationalLevel::class);
    }

    /**
     * Get the unit name based on admin role or student choice.
     * Returns the level name from the joined educational_levels table.
     */
    public function getUnit(): ?string
    {
        if ($this->role === self::ROLE_ADMIN_SMP) return 'SMP';
        if ($this->role === self::ROLE_ADMIN_SMA) return 'SMA';
        if (in_array($this->role, [self::ROLE_ADMIN_SMK])) return 'SMK';
        
        return $this->educationalLevel?->name;
    }

    /**
     * Get the list of educational level IDs managed by this user.
     */
    public function getManagedLevelIds(): array
    {
        if ($this->isSuperAdmin() || $this->role === self::ROLE_ADMIN_ADM) {
            return EducationalLevel::pluck('id')->toArray();
        }

        $unit = $this->getUnit();
        return EducationalLevel::where('parent_unit', $unit)
            ->orWhere('name', $unit)
            ->pluck('id')
            ->toArray();
    }

    public function registration()
    {
        return $this->hasOne(Registration::class);
    }
}
