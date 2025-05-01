<?php

namespace  App\Http\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*']);

    public function paginate(int $perPage = 15);

    public function find($id);

    public function findBy(array $criteria);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function pushCriteria(CriteriaInterface $criteria): self;

    public function Model(): string;
}