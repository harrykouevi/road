<?php

namespace App\Http\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface CriteriaInterface
{
    public function apply(Model $model, $repository);
}
