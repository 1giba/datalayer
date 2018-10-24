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
    public function query(): Builder
    {
        if (! $this->model instanceof Builder) {
            return $this->model->newQuery();
        }

        return $this->model;
    }

    /**
     * { @inheritdoc }
     */
    public function first(array $columns = ['*']): ?Model {
        return $this->query()
            ->first($columns);
    }

    /**
     * { @inheritdoc }
     */
    public function get(): Collection
    {
        return $this->query()
            ->get();
    }

    /**
     * { @inheritdoc }
     */
    public function paginate(int $perPage = 50): LengthAwarePaginator
    {
        return $this->query()
            ->paginate($perPage);
    }

    /**
     * { @inheritdoc }
     */
    public function create(array $fillable): ?Model
    {
        return $this->model->create($fillable);
    }

    /**
     * { @inheritdoc }
     */
    public function update(array $fillable, int $resourceId)
    {
        return ! $resource->fill($fillable)->save() ?? $resource;
    }

    /**
     * { @inheritdoc }
     */
    public function delete(int $resourceId): int
    {
        return $this->model->destroy($resourceId);
    }
}
