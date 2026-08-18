<?php

namespace App\Traits;

use App\Scopes\ActiveAcademicYearScope;
use App\Models\AcademicYear;

trait HasActiveAcademicYear
{
    /**
     * Boot the trait for a model.
     *
     * @return void
     */
    public static function bootHasActiveAcademicYear()
    {
        // Apply global scope to filter only current academic year data
        static::addGlobalScope(new ActiveAcademicYearScope);

        // Auto-inject academic_year_id upon creation
        static::creating(function ($model) {
            if (empty($model->academic_year_id)) {
                $activeYearId = AcademicYear::where('is_active', true)->value('id');
                
                if ($activeYearId) {
                    $model->academic_year_id = $activeYearId;
                }
            }
        });
    }
}
