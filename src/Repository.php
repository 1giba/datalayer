<?php

namespace OneGiba\DataLayer;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use OneGiba\DataLayer\Contracts\RepositoryInterface;
use OneGiba\DataLayer\Traits\Searchable;
use OneGiba\DataLayer\Traits\Conditionable;
use OneGiba\DataLayer\Traits\Joinable;
use OneGiba\DataLayer\Traits\Massble;

abstract class Repository implements RepositoryInterface
{
    use Searchable,
        Conditionable,
        Joinable,
        Massble;

    /**
     * @var \Illuminate\Database\Eloquent\Builder|null
     */
    protected $query;

    /**
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $model;

    /**
     * @var array
     */
    protected $selectedFields = ['*'];

    /**
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return void
     */
    public function __construct(?Model $model = null)
    {
        $this->model = $model ?? app($this->model())->make();
    }

    /**
     * Returns model class name
     *
     * @return string
     */
    public abstract function model(): string;

    /**
     * Returns Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery(): Builder
    {
        if (! $this->query instanceof Builder) {
            $this->query = $this->model->newQuery();
        }

        return $this->query;
    }

    /**
     * { @inheritdoc }
     */
    public function findById(int $resourceId)
    {
        $resource = $this->getQuery()->find($resourceId, $this->selectedFields);
        $this->resetQuery();
        return $resource;
    }

    /**
     * { @inheritdoc }
     */
    public function fetchAll(): Collection
    {
        return $this->model->all($this->selectedFields);
    }

    /**
     * { @inheritdoc }
     */
    public function fetchFirst()
    {
        $resource = $this->getQuery()->first();
        $this->resetQuery();
        return $resource;
    }

    /**
     * { @inheritdoc }
     */
    public function fetch(): Collection
    {
        $results = $this->getQuery()->get();
        $this->resetQuery();
        return $results;
    }

    /**
     * { @inheritdoc }
     */
    public function paginate(int $perPage = 50): LengthAwarePaginator
    {
        $results = $this->getQuery()->paginate($perPage);
        $this->resetQuery();
        return $results;
    }

    /**
     * { @inheritdoc }
     */
    public function create(array $fillable)
    {
        $resource = $this->getQuery()->create($fillable);
        $this->resetQuery();
        return $resource;
    }

    /**
     * { @inheritdoc }
     */
    public function update(array $fillable, int $resourceId)
    {
        $resource = $this->getQuery()->find($resourceId);
        if ($resource instanceof Model) {
            $resource->fill($fillable)->save();
        }
        $this->resetQuery();
        return $resource;
    }

    /**
     * { @inheritdoc }
     */
    public function delete(int $resourceId): int
    {
        return $this->model->destroy($resourceId);
    }

    /**
     * { @inheritdoc }
     */
    public function count(): int
    {
        $numRows = $this->getQuery()->count();
        $this->resetQuery();
        return $numRows;

    }

    /**
     * { @inheritdoc }
     */
    public function sum(string $column)
    {
        $sum = $this->getQuery()->sum($column);
        $this->resetQuery();
        return $sum;
    }

    /**
     * { @inheritdoc }
     */
    public function max(string $column)
    {
        $max = $this->getQuery()->max($column);
        $this->resetQuery();
        return $max;
    }

    /**
     * { @inheritdoc }
     */
    public function min(string $column)
    {
        $min = $this->getQuery()->min($column);
        $this->resetQuery();
        return $min;
    }

    /**
     * { @inheritdoc }
     */
    public function avg(string $column)
    {
        $avg = $this->getQuery()->avg($column);
        $this->resetQuery();
        return $avg;
    }

    /**
     * Reset Builder
     *
     * @return $this
     */
    public function resetQuery(): self
    {
        if ($this->query instanceof Builder) {
            $this->query = $this->model->newQuery();
        }
        $this->selectedFields = ['*'];
        return $this;
    }

    /**
     * Delete by ids
     *
     * @param array $resourceIds
     * @return int
     */
    public function deleteAll(array $resourceIds): int
    {
        return $this->model->destroy($resourceIds);
    }
}
