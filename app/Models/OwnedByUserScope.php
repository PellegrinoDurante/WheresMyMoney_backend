<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OwnedByUserScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('user_id', Auth::user()->id);
    }
}
