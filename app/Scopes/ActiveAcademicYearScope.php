<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Models\AcademicYear;

class ActiveAcademicYearScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $activeYearId = AcademicYear::where('is_active', true)->value('id');

        if ($activeYearId) {
            $builder->where($model->getTable() . '.academic_year_id', $activeYearId);
        }
    }
}
