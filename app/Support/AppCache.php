<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Centralized cache layer untuk data master yang jarang berubah.
 * TTL disesuaikan dengan frekuensi perubahan masing-masing data.
 */
class AppCache
{
    const TTL_STATIC  = 3600;   // 1 jam — data sangat stabil (level, fee, source, reason)
    const TTL_ACTIVE  = 1800;   // 30 menit — data aktif yg bisa toggle (wave, academic year)
    const TTL_REGION  = 86400;  // 24 jam — data wilayah tidak pernah berubah

    /**
     * Wrapper aman: jika cache return tipe yang salah (misal __PHP_Incomplete_Class
     * dari cache lama), hapus dan fetch ulang dari DB.
     */
    private static function safeRemember(string $key, int $ttl, callable $query): mixed
    {
        $cached = Cache::get($key);

        if ($cached !== null && !($cached instanceof EloquentCollection) && !($cached instanceof Collection) && !is_string($cached) && !is_null($cached)) {
            Cache::forget($key);
            $cached = null;
        }

        if ($cached === null) {
            $cached = $query();
            Cache::put($key, $cached, $ttl);
        }

        return $cached;
    }

    // ─── Educational Levels ───────────────────────────────────────────────────

    public static function educationalLevels(): EloquentCollection
    {
        $result = self::safeRemember('educational_levels_all', self::TTL_STATIC, fn () =>
            \App\Models\EducationalLevel::orderBy('sort_order')->get()
        );
        return $result instanceof EloquentCollection ? $result : \App\Models\EducationalLevel::orderBy('sort_order')->get();
    }

    public static function educationalLevelsActive(): EloquentCollection
    {
        $result = self::safeRemember('educational_levels_active', self::TTL_STATIC, fn () =>
            \App\Models\EducationalLevel::where('is_active', true)->orderBy('sort_order')->get()
        );
        return $result instanceof EloquentCollection ? $result : \App\Models\EducationalLevel::where('is_active', true)->orderBy('sort_order')->get();
    }

    public static function forgetEducationalLevels(): void
    {
        Cache::forget('educational_levels_all');
        Cache::forget('educational_levels_active');
    }

    // ─── Administrative Fees ──────────────────────────────────────────────────

    public static function administrativeFees(): EloquentCollection
    {
        $result = self::safeRemember('administrative_fees', self::TTL_STATIC, fn () =>
            \App\Models\AdministrativeFee::with('level')->orderBy('educational_level_id')->orderBy('sort_order')->get()
        );
        return $result instanceof EloquentCollection ? $result : \App\Models\AdministrativeFee::with('level')->orderBy('educational_level_id')->orderBy('sort_order')->get();
    }

    public static function administrativeFeesGrouped(): Collection
    {
        return self::administrativeFees()->groupBy('educational_level_id');
    }

    public static function forgetAdministrativeFees(): void
    {
        Cache::forget('administrative_fees');
    }

    // ─── Information Sources ──────────────────────────────────────────────────

    public static function informationSourcesActive(): EloquentCollection
    {
        $result = self::safeRemember('information_sources_active', self::TTL_STATIC, fn () =>
            \App\Models\InformationSource::where('is_active', true)->orderBy('name')->get()
        );
        return $result instanceof EloquentCollection ? $result : \App\Models\InformationSource::where('is_active', true)->orderBy('name')->get();
    }

    public static function forgetInformationSources(): void
    {
        Cache::forget('information_sources_active');
    }

    // ─── School Reasons ───────────────────────────────────────────────────────

    public static function schoolReasonsActive(): EloquentCollection
    {
        $result = self::safeRemember('school_reasons_active', self::TTL_STATIC, fn () =>
            \App\Models\SchoolReason::where('is_active', true)->orderBy('name')->get()
        );
        return $result instanceof EloquentCollection ? $result : \App\Models\SchoolReason::where('is_active', true)->orderBy('name')->get();
    }

    public static function forgetSchoolReasons(): void
    {
        Cache::forget('school_reasons_active');
    }

    // ─── Academic Year ────────────────────────────────────────────────────────

    public static function activeAcademicYear(): ?\App\Models\AcademicYear
    {
        $result = Cache::get('academic_year_active');
        if ($result !== null && !($result instanceof \App\Models\AcademicYear)) {
            Cache::forget('academic_year_active');
            $result = null;
        }
        if ($result === null) {
            $result = \App\Models\AcademicYear::where('is_active', true)->first();
            Cache::put('academic_year_active', $result, self::TTL_ACTIVE);
        }
        return $result;
    }

    public static function forgetAcademicYear(): void
    {
        Cache::forget('academic_year_active');
    }

    // ─── Registration Wave ────────────────────────────────────────────────────

    public static function activeRegistrationWave(): ?\App\Models\RegistrationWave
    {
        $result = Cache::get('registration_wave_active');
        if ($result !== null && !($result instanceof \App\Models\RegistrationWave)) {
            Cache::forget('registration_wave_active');
            $result = null;
        }
        if ($result === null) {
            $result = \App\Models\RegistrationWave::where('is_active', true)->first();
            Cache::put('registration_wave_active', $result, self::TTL_ACTIVE);
        }
        return $result;
    }

    public static function forgetRegistrationWave(): void
    {
        Cache::forget('registration_wave_active');
    }

    // ─── Region (Sekolah table) ───────────────────────────────────────────────

    public static function regionProvinsi(): EloquentCollection
    {
        $result = self::safeRemember('region_provinsi', self::TTL_REGION, fn () =>
            \App\Models\Sekolah::select('propinsi', 'kode_prop')
                ->whereNotNull('propinsi')
                ->distinct()
                ->orderBy('propinsi')
                ->get()
        );
        return $result instanceof EloquentCollection ? $result : \App\Models\Sekolah::select('propinsi', 'kode_prop')->whereNotNull('propinsi')->distinct()->orderBy('propinsi')->get();
    }

    public static function regionKabupaten(string $kode_prop): EloquentCollection
    {
        $result = self::safeRemember("region_kab_{$kode_prop}", self::TTL_REGION, fn () =>
            \App\Models\Sekolah::where('kode_prop', $kode_prop)
                ->whereNotNull('kabupaten_kota')
                ->select('kabupaten_kota', 'kode_kab_kota')
                ->distinct()
                ->orderBy('kabupaten_kota')
                ->get()
        );
        return $result instanceof EloquentCollection ? $result : \App\Models\Sekolah::where('kode_prop', $kode_prop)->whereNotNull('kabupaten_kota')->select('kabupaten_kota', 'kode_kab_kota')->distinct()->orderBy('kabupaten_kota')->get();
    }

    public static function regionKecamatan(string $kode_kab_kota): EloquentCollection
    {
        $result = self::safeRemember("region_kec_{$kode_kab_kota}", self::TTL_REGION, fn () =>
            \App\Models\Sekolah::where('kode_kab_kota', $kode_kab_kota)
                ->whereNotNull('kecamatan')
                ->select('kecamatan', 'kode_kec')
                ->distinct()
                ->orderBy('kecamatan')
                ->get()
        );
        return $result instanceof EloquentCollection ? $result : \App\Models\Sekolah::where('kode_kab_kota', $kode_kab_kota)->whereNotNull('kecamatan')->select('kecamatan', 'kode_kec')->distinct()->orderBy('kecamatan')->get();
    }

    public static function regionSekolah(string $kode_kec): EloquentCollection
    {
        $result = self::safeRemember("region_sekolah_{$kode_kec}", self::TTL_REGION, fn () =>
            \App\Models\Sekolah::where('kode_kec', $kode_kec)
                ->whereNotNull('sekolah')
                ->whereIn('bentuk', ['SD', 'SMP', 'SDLB', 'SLB', 'SMPLB'])
                ->select('sekolah', 'propinsi', 'kabupaten_kota', 'kecamatan')
                ->distinct()
                ->orderBy('sekolah')
                ->get()
        );
        return $result instanceof EloquentCollection ? $result : \App\Models\Sekolah::where('kode_kec', $kode_kec)->whereNotNull('sekolah')->whereIn('bentuk', ['SD', 'SMP', 'SDLB', 'SLB', 'SMPLB'])->select('sekolah', 'propinsi', 'kabupaten_kota', 'kecamatan')->distinct()->orderBy('sekolah')->get();
    }

    public static function regionLookup(string $field, string $column, string $kode): ?string
    {
        $result = Cache::get("region_lookup_{$column}_{$kode}");
        if ($result !== null && !is_string($result)) {
            Cache::forget("region_lookup_{$column}_{$kode}");
            $result = null;
        }
        if ($result === null) {
            $result = \App\Models\Sekolah::where($column, $kode)->value($field);
            Cache::put("region_lookup_{$column}_{$kode}", $result, self::TTL_REGION);
        }
        return $result;
    }
}
