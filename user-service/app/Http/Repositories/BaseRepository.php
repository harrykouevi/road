<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Contracts\BaseRepositoryInterface;
// use App\Http\Repositories\Contracts\CriteriaInterface ;
// CriteriaInterface
use Illuminate\Database\Eloquent\Model ;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Repository\Events\RepositoryEntityUpdating;
use Prettus\Validator\Contracts\ValidatorInterface;
use Illuminate\Support\Arr;
use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository ;
use Exception;


// copy of InfyOm\Generator\Common;
abstract class BaseRepository extends PrettusBaseRepository
{
    public function findWithoutFail($id, $columns = ['*'])
    {
        try {
            return $this->find($id, $columns);
        } catch (Exception $e) {
            return Null;
        }
    }

    public function create(array $attributes)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model = parent::create($attributes);
        $this->skipPresenter($temporarySkipPresenter);

        $model = $this->updateRelations($model, $attributes);
        $model->save();

        return $this->parserResult($model);
    }

    public function update(array $attributes, $id)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model = parent::update($attributes, $id);
        $this->skipPresenter($temporarySkipPresenter);

        $model = $this->updateRelations($model, $attributes);
        $model->save();

        return $this->parserResult($model);
    }

    public function updateRelations($model, $attributes)
    {
        foreach ($attributes as $key => $val) {
            if (isset($model) &&
                method_exists($model, $key) &&
                is_a(@$model->$key(), 'Illuminate\Database\Eloquent\Relations\Relation')
            ) {
                $methodClass = get_class($model->$key($key));
                switch ($methodClass) {
                    case 'Illuminate\Database\Eloquent\Relations\BelongsToMany':
                        $new_values = Arr::get($attributes, $key, []);
                        if (array_search('', $new_values) !== false) {
                            unset($new_values[array_search('', $new_values)]);
                        }
//                        if(count(array_values($new_values)) === 0){
//                            $pivot = $model->$key()->detach(array_values($new_values));
//                        }else{
                        $pivot = $model->$key()->sync(array_values($new_values));
                        //}
                        if ((count($pivot['attached']) + count($pivot['detached']) + count($pivot['updated'])) > 0) {
                            $model->touch();
                        }

                        break;
                    case 'Illuminate\Database\Eloquent\Relations\BelongsTo':
                        $model_key = $model->$key()->getQualifiedForeignKeyName();
                        $new_value = Arr::get($attributes, $key, null);
                        $new_value = $new_value == '' ? null : $new_value;
                        $model->$model_key = $new_value;
                        break;
                    case 'Illuminate\Database\Eloquent\Relations\HasOne':
                        break;
                    case 'Illuminate\Database\Eloquent\Relations\HasOneOrMany':
                        break;
                    case 'Illuminate\Database\Eloquent\Relations\HasMany':
                        $new_values = Arr::get($attributes, $key, []);
                        if (array_search('', $new_values) !== false) {
                            unset($new_values[array_search('', $new_values)]);
                        }

                        list($temp, $model_key) = explode('.', $model->$key($key)->getQualifiedForeignKeyName());

                        foreach ($model->$key as $rel) {
                            if (!in_array($rel->id, $new_values)) {
                                $rel->$model_key = null;
                                $rel->save();
                            }
                            unset($new_values[array_search($rel->id, $new_values)]);
                        }

                        if (count($new_values) > 0) {
                            $related = get_class($model->$key()->getRelated());
                            foreach ($new_values as $val) {
                                $rel = $related::find($val);
                                $rel->$model_key = $model->id;
                                $rel->save();
                            }
                        }
                        break;
                }
            }
        }

        return $model;
    }

    /**
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     *
     * @return int
     */
    public function updateWhereIn(array $attributes,string $column,array $values)
    {
        $this->applyScope();

        if (!is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $model = $this->model->newInstance();
            $model->setRawAttributes([]);
            $model->setAppends([]);
            if ($this->versionCompare($this->app->version(), "5.2.*", ">")) {
                $attributes = $model->forceFill($attributes)->makeVisible($this->model->getHidden())->toArray();
            } else {
                $model->forceFill($attributes);
                $model->makeVisible($this->model->getHidden());
                $attributes = $model->toArray();
            }

            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $this->model->whereIn($column, $values);

        event(new RepositoryEntityUpdating($this, $this->model->getModel()));

        $updated = $this->model->update($attributes);

        event(new RepositoryEntityUpdated($this, $this->model->getModel()));

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        return $updated;
    }


    /**
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     *
     * @return int
     */
    public function updateWhere(array $attributes,array $where)
    {
        $this->applyScope();

        if (!is_null($this->validator)) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $model = $this->model->newInstance();
            $model->setRawAttributes([]);
            $model->setAppends([]);
            if ($this->versionCompare($this->app->version(), "5.2.*", ">")) {
                $attributes = $model->forceFill($attributes)->makeVisible($this->model->getHidden())->toArray();
            } else {
                $model->forceFill($attributes);
                $model->makeVisible($this->model->getHidden());
                $attributes = $model->toArray();
            }

            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $this->applyConditions($where);

        event(new RepositoryEntityUpdating($this, $this->model->getModel()));

        $updated = $this->model->update($attributes);

        event(new RepositoryEntityUpdated($this, $this->model->getModel()));

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        return $updated;
    }
}