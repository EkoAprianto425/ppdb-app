<?php

namespace App\Support;

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

    // ─── Educational Levels ───────────────────────────────────────────────────

    public static function educationalLevels(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('educational_levels_all', self::TTL_STATIC, fn () =>
            \App\Models\EducationalLevel::orderBy('sort_order')->get()
        );
    }

    public static function educationalLevelsActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('educational_levels_active', self::TTL_STATIC, fn () =>
            \App\Models\EducationalLevel::where('is_active', true)->orderBy('sort_order')->get()
        );
    }

    public static function forgetEducationalLevels(): void
    {
        Cache::forget('educational_levels_all');
        Cache::forget('educational_levels_active');
    }

    // ─── Administrative Fees ──────────────────────────────────────────────────

    public static function administrativeFees(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('administrative_fees', self::TTL_STATIC, fn () =>
            \App\Models\AdministrativeFee::with('level')->orderBy('educational_level_id')->orderBy('sort_order')->get()
        );
    }

    public static function administrativeFeesGrouped(): \Illuminate\Support\Collection
    {
        return self::administrativeFees()->groupBy('educational_level_id');
    }

    public static function forgetAdministrativeFees(): void
    {
        Cache::forget('administrative_fees');
    }

    // ─── Information Sources ──────────────────────────────────────────────────

    public static function informationSourcesActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('information_sources_active', self::TTL_STATIC, fn () =>
            \App\Models\InformationSource::where('is_active', true)->orderBy('name')->get()
        );
    }

    public static function forgetInformationSources(): void
    {
        Cache::forget('information_sources_active');
    }

    // ─── School Reasons ───────────────────────────────────────────────────────

    public static function schoolReasonsActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('school_reasons_active', self::TTL_STATIC, fn () =>
            \App\Models\SchoolReason::where('is_active', true)->orderBy('name')->get()
        );
    }

    public static function forgetSchoolReasons(): void
    {
        Cache::forget('school_reasons_active');
    }

    // ─── Academic Year ────────────────────────────────────────────────────────

    public static function activeAcademicYear(): ?\App\Models\AcademicYear
    {
        return Cache::remember('academic_year_active', self::TTL_ACTIVE, fn () =>
            \App\Models\AcademicYear::where('is_active', true)->first()
        );
    }

    public static function forgetAcademicYear(): void
    {
        Cache::forget('academic_year_active');
    }

    // ─── Registration Wave ────────────────────────────────────────────────────

    public static function activeRegistrationWave(): ?\App\Models\RegistrationWave
    {
        return Cache::remember('registration_wave_active', self::TTL_ACTIVE, fn () =>
            \App\Models\RegistrationWave::where('is_active', true)->first()
        );
    }

    public static function forgetRegistrationWave(): void
    {
        Cache::forget('registration_wave_active');
    }

    // ─── Region (Sekolah table) ───────────────────────────────────────────────

    public static function regionProvinsi(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('region_provinsi', self::TTL_REGION, fn () =>
            \App\Models\Sekolah::select('propinsi', 'kode_prop')
                ->whereNotNull('propinsi')
                ->distinct()
                ->orderBy('propinsi')
                ->get()
        );
    }

    public static function regionKabupaten(string $kode_prop): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember("region_kab_{$kode_prop}", self::TTL_REGION, fn () =>
            \App\Models\Sekolah::where('kode_prop', $kode_prop)
                ->whereNotNull('kabupaten_kota')
                ->select('kabupaten_kota', 'kode_kab_kota')
                ->distinct()
                ->orderBy('kabupaten_kota')
                ->get()
        );
    }

    public static function regionKecamatan(string $kode_kab_kota): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember("region_kec_{$kode_kab_kota}", self::TTL_REGION, fn () =>
            \App\Models\Sekolah::where('kode_kab_kota', $kode_kab_kota)
                ->whereNotNull('kecamatan')
                ->select('kecamatan', 'kode_kec')
                ->distinct()
                ->orderBy('kecamatan')
                ->get()
        );
    }

    public static function regionSekolah(string $kode_kec): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember("region_sekolah_{$kode_kec}", self::TTL_REGION, fn () =>
            \App\Models\Sekolah::where('kode_kec', $kode_kec)
                ->whereNotNull('sekolah')
                ->whereIn('bentuk', ['SD', 'SMP', 'SDLB', 'SLB', 'SMPLB'])
                ->select('sekolah', 'propinsi', 'kabupaten_kota', 'kecamatan')
                ->distinct()
                ->orderBy('sekolah')
                ->get()
        );
    }

    public static function regionLookup(string $field, string $column, string $kode): ?string
    {
        return Cache::remember("region_lookup_{$column}_{$kode}", self::TTL_REGION, fn () =>
            \App\Models\Sekolah::where($column, $kode)->value($field)
        );
    }
}
