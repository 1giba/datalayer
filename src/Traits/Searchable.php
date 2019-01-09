<?php

namespace OneGiba\DataLayer\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use DB;

trait Searchable
{
    /**
     * @const array
     */
    const SELECT_ALL_FIELDS = ['*'];

    /**
     * @var array
     */
    protected $selectedFields = self::SELECT_ALL_FIELDS;

    /**
     * Basic search
     *
     * @param int $resourceId
     * @param array $columns
     * @return mixed
     */
    public function find(int $resourceId)
    {
        return $this->query()->find($resourceId, $this->selectedFields);
    }

    /**
     * All rows
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        return $this->model->all($this->selectedFields);
    }

    /**
     * Show fields in query
     *
     * @param array $fields
     * @return $this
     */
    public function select(array $fields): self
    {
        $model = $this->query();
        $this->selectedFields = [];
        foreach ($fields as $field) {
            $this->selectedFields[] = DB::raw($field);
        }
        if (empty($this->selectedFields)) {
            $this->selectedFields = self::SELECT_ALL_FIELDS;
        }
        $this->model = $model->select($this->selectedFields);
        return $this;
    }

    /**
     * @return $this
     */
    public function orderBy(...$args): self
    {
        if (empty($args)) {
            return $this;
        }

        $model = $this->query();
        if (count($args) === 2) {
            list($column, $orientation) = $args;
            $model = $model->orderBy(DB::raw($column), $orientation);
        } elseif (is_array($args[0])) {
            foreach ($args as $sortField) {
                list($column, $orientation) = $sortField;
                if (! $orientation ||
                    !in_array(strtoupper($orientation), ['ASC', 'DESC'])) {
                    $orientation = 'ASC';
                }
                $model = $model->orderBy(DB::raw($column), $orientation);
            }
        } else {
            $model = $model->orderBy(DB::raw($args[0]));
        }
        $this->model = $model;
        return $this;
    }

    /**
     * @param array $sortFields
     * @return $this
     */
    public function groupBy(...$args): self
    {
        if (empty($args)) {
            return $this;
        }

        $model = $this->query();
        if (is_array($args[0])) {
            $groups = array_map(function ($group) {
                return DB::raw($group);
            }, $args[0]);

            $model = $model->groupBy($groups);
        } else {
            $model = $model->groupBy(DB::raw($args[0]));
        }
        $this->model = $model;
        return $this;
    }

    /**
     * @param string $aggregation
     * @param string $operator
     * @param mixed $value
     * @return $this
     */
    public function having(string $aggregation, string $operator, $value): self
    {
        $model = $this->query();
        $this->model = $model->having(
            DB::raw($aggregation),
            $operator,
            $value
        );
        return $this;
    }

    /**
     * @return $this
     */
    public function distinct(): self
    {
        $model = $this->query();
        $this->model = $model->distinct();
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function take(int $limit): self
    {
        $model = $this->query();
        $this->model = $model->take($limit);
        return $this;
    }
}
