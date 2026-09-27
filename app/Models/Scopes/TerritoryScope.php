<?php

namespace App\Models\Scopes;

use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TerritoryScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! Auth::hasUser()) {
            return;
        }

        /** @var User $user */
        $user = Auth::user();

        if (! $user->isOperator()) {
            return;
        }

        if ($user->village_id) {
            $builder->where($model->qualifyColumn('village_id'), $user->village_id);
        } elseif ($user->district_id) {
            $builder->whereIn(
                $model->qualifyColumn('village_id'),
                Village::where('district_id', $user->district_id)->select('id')
            );
        }
    }
}
