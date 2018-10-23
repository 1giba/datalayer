<?php

namespace OneGiba\DataLayer;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use OneGiba\DataLayer\Contracts\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    /**
     * @const string
     */
    const QUERY_STRING_ATTR = 'attr';
    /**
     * @const string
     */
    const QUERY_STRING_SORT = 'sort';

    /**
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $model;

    /**
     * LIKE clausule
     *
     * @var array
     */
    protected $partials = [];

    /**
     * Allowed sort fields
     *
     * @var array
     */
    protected $allowedSorts = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**     * @var array
     */
    protected $customFilters = [];

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
     * Basic search
     *
     * @param int $resourceId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $resourceId): ?Model
    {
        return $this->query()->find($resourceId);
    }

    /**
     * { @inheritdoc }
     */
    public function first(array $conditions = []): ?Model
    {
        $model = $this->query();

        foreach ($conditions as $column => $value) {
            $model = $model->where($column, $value);
        }

        return $model->first();
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

    /**
     * Filtros com LIKE
     *
     * @param string $attribute
     * @return $this
     */
    public function addPartialSearch(string $attribute): self
    {
        if (! in_array($attribute, $this->partials)) {
            $this->partials[] = $attribute;
        }

        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function filter(array $params = []): self
    {
        foreach ($params as $param => $value) {
            if (is_null($value)) {
                continue;
            }

            if ($param === self::QUERY_STRING_ATTR) {
                $this->selectFields($value);
                continue;
            }

            if ($param === self::QUERY_STRING_SORT) {
                $this->model = $this->applySorting($value);
                continue;
            }

            if (isset($this->customFilters[$param])) {
                $this->model = $this->applyCustomFilters($param, $value);
                continue;
            }

            if (isset($this->attributes[$param])) {
                $param = $this->attributes[$param];
            }

            if (is_array($value)) {
                $this->model = $this->query()->whereIn($param, $value);
                continue;
            }

            $values = explode(',', $value);
            if (count($values) === 1) {
                if (in_array($param, $this->partials)) {
                    $this->model = $this->query()->where($param, 'ILIKE', '%' . $value . '%');
                    continue;
                }
                $this->model = $this->query()->where($param, $value);
                continue;
            }

            $this->model = $this->query()->whereIn($param, $values);
        }

        return $this;
    }

    /**
     * @param string $attributes
     * @return $this
     */
    public function selectFields(string $attributes): self
    {
        $this->model = $this->query()
            ->select(explode(',', $attributes));
        return $this;
    }

    /**
     * @param string $sortFields
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySorting(string $sortFields): Builder
    {
        $model  = $this->query();
        $fields = explode(',', $sortFields);
        foreach ($fields as $field) {
            $hasPrefix      = substr($field, 0, 1) === '-';
            $field          = $hasPrefix ? substr($field, 1) : $field;
            $orientation    = $hasPrefix ? 'DESC' : 'ASC';

            if (count($this->allowedSorts) > 0 &&
                ! in_array($field, $this->allowedSorts)) {
                continue;
            }
            $model = $model->orderBy($field, $orientation);
        }
        return $model;
    }

    /**
     * @param array $relationships
     * @return $this
     */
    public function with(array $relationships): self
    {
        $this->model = $this->query()->with($relationships);

        return $this;
    }

    /**
     * @return void
     */
    public function debug()
    {
        dd($this->query()->toSql());
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function allowedSorts(array $fields): self
    {
        $this->allowedSorts = $fields;

        return $this;
    }

    /**
     * Referencia o campo
     *
     * @param string $alias
     * @param string $fieldname
     * @return $this
     */
    public function refer(string $alias, string $fieldname): self
    {
        $this->attributes[$alias] = $fieldname;

        return $this;
    }

    /**
     * Adiciona filtros customizados
     *
     * @param array $filters
     * @return $this
     */
    public function addCustomFilters(array $filters): self
    {
        $this->customFilters = $filters;

        return $this;
    }

    /**
     * @param mixed $param
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyCustomFilters($param, $value): Builder
    {
        if (!isset($this->customFilters[$param][$value])) {
            return $this->query();
        }

        return $this->query()->whereRaw($this->customFilters[$param][$value]);
    }
}
