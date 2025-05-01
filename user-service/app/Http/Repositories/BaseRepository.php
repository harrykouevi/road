<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Contracts\BaseRepositoryInterface;
use App\Http\Repositories\Contracts\CriteriaInterface ;
use Illuminate\Database\Eloquent\Model ;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model  $model;
    protected array $criteria = [];
    protected bool $skipCriteria = false;

    public function __construct()
    {
        $this->model = app($this->Model());
    }

    abstract public function Model(): string;

    public function pushCriteria(CriteriaInterface $criteria): static
    {
        $this->criteria[] = $criteria;
        return $this;
    }

    public function getByCriteria(CriteriaInterface $criteria)
    {
        return $criteria->apply($this->model->newQuery(), $this);
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function skipCriteria(bool $status = true): static
    {
        $this->skipCriteria = $status;
        return $this;
    }

    public function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            return $this;
        }

        foreach ($this->criteria as $criteria) {
            $this->model = $criteria->apply($this->model, $this);
        }

        return $this;
    }

    // Overridden read-only methods using criteria
    public function all(array $columns = ['*'])
    {
        $this->applyCriteria();
        return $this->model->select($columns)->get();
    }

    public function paginate(int $perPage = 15)
    {
        $this->applyCriteria();
        return $this->model->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }


    public function findBy(array $criteria)
    {
        $this->applyCriteria();
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, $value);
        }

        return $query->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $record = $this->find($id);
        return $record->delete();
    }
}
