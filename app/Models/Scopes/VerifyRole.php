<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class VerifyRole implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
    
         //Agregue esta validacion y dejo de fallar
        if (!auth()->check()) {
            return;  
        }
        if (auth()->user()->hasRole('Doctor')) {
            $builder->whereHas('doctor', function ($builder) {
                $builder->where('user_id', auth()->id());
            });
        }

        if (auth()->user()->hasRole('Paciente')) {
            $builder->whereHas('patient', function ($builder) {
                $builder->where('user_id', auth()->id());
            });
        }
    }
}
