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
    public function first()
    {
        $object = $this->getQuery()->first();
        $this->reset();
        return $object;
    }

    /**
     * { @inheritdoc }
     */
    public function fetch(): Collection
    {
        $collection = $this->getQuery()->get();
        $this->reset();
        return $collection;
    }

    /**
     * { @inheritdoc }
     */
    public function paginate(int $perPage = 50): LengthAwarePaginator
    {
        $results = $this->getQuery()->paginate($perPage);
        $this->reset();
        return $results;
    }

    /**
     * { @inheritdoc }
     */
    public function create(array $fillable)
    {
        $this->reset();
        return $this->getQuery()->create($fillable);
    }

    /**
     * { @inheritdoc }
     */
    public function update(array $fillable, int $resourceId)
    {
        $this->reset();
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
        $this->reset();
        return $this->getQuery()->destroy($resourceId);
    }

    /**
     * { @inheritdoc }
     */
    public function count(): int
    {
        $result = $this->getQuery()
            ->count();
        $this->reset();
        return $result;
    }

    /**
     * { @inheritdoc }
     */
    public function sum(string $column)
    {
        $result = $this->getQuery()
            ->sum($column);
        $this->reset();
        return $result;
    }

    /**
     * { @inheritdoc }
     */
    public function max(string $column)
    {
        $result = $this->getQuery()
            ->max($column);
        $this->reset();
        return $result;
    }

    /**
     * { @inheritdoc }
     */
    public function min(string $column)
    {
        $result = $this->getQuery()
            ->min($column);
        $this->reset();
        return $result;
    }

    /**
     * { @inheritdoc }
     */
    public function avg(string $column)
    {
        $result = $this->getQuery()
            ->avg($column);
        $this->reset();
        return $result;
    }

    /**
     * Reset Builder
     *
     * @return $this
     */
    public function reset(): self
    {
        if ($this->query instanceof Builder) {
            $this->query = $this->model->newQuery();
        }

        return $this;
    }
}
