<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

trait ServiceTrait
{
    /**
     * Scope a query to get some relation of the model.
     *
     * @param Builder $query
     * @param array relations
     * @return Builder
     */
    private function getRelation(Builder $query , Array $relations=[]) : Builder
    {
        foreach($relations as $relation){
            if (!is_string($relation)) {
                throw new InvalidArgumentException('All elements in relations must be strings.');
            }
        }
        return $query->with($relations);
    }

}