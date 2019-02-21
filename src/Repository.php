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

abstract class Repository implements RepositoryInterface
{
    use Searchable,
        Conditionable,
        Joinable;

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
        return $this->getQuery()->find($resourceId, $this->selectedFields);
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
    public function first()
    {
        $results = $this->getQuery()->first();
        $this->resetQuery();
        return $results;
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
        return $this->getQuery()->create($fillable);
    }

    /**
     * { @inheritdoc }
     */
    public function update(array $fillable, int $resourceId)
    {
        $resource = $this->getQuery()->find($resourceId);
        if ($respource instanceof Model) {
            $resource->fill($fillable)->save();
        }
        return $resource;
    }

    /**
     * { @inheritdoc }
     */
    public function delete(int $resourceId): int
    {
        return $this->getQuery()->destroy($resourceId);
    }

    /**
     * { @inheritdoc }
     */
    public function count(): int
    {
        return $this->getQuery()
            ->count();
    }

    /**
     * { @inheritdoc }
     */
    public function sum(string $column)
    {
        return $this->getQuery()
            ->sum($column);
    }

    /**
     * { @inheritdoc }
     */
    public function max(string $column)
    {
        return $this->getQuery()
            ->max($column);
    }

    /**
     * { @inheritdoc }
     */
    public function min(string $column)
    {
        return $this->getQuery()
            ->min($column);
    }

    /**
     * { @inheritdoc }
     */
    public function avg(string $column)
    {
        return $this->getQuery()
            ->avg($column);
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

        return $this;
    }
}
