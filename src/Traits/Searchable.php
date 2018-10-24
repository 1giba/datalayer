<?php

namespace OneGiba\DataLayer\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use DB;

trait Searchable
{
    /**
     * Basic search
     *
     * @param int $resourceId
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $resourceId, array $columns = ['*']): ?Model
    {
        return $this->query()->find($resourceId, $columns);
    }

    /**
     * All rows
     *
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
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
        $rawFields = [];
        foreach ($fields as $field) {
            $rawFields[] = DB::raw($field);
        }
        $this->model = $model->select($rawFields);
        return $this;
    }

    /**
     * @param array $sortFields
     * @return $this
     */
    public function orderBy(): self
    {
        $args = func_get_args();
        if (empty($args)) {
            return $this;
        }

        $model = $this->query();
        if (count($args) === 2) {
            list($column, $orientation) = $args;
            $model = $model->orderBy($column, $orientation);
        } elseif (is_array($args[0])) {
            foreach ($args as $sortField) {
                list($column, $orientation) = $sortField;
                if (! $orientation ||
                    !in_array(strtoupper($orientation), ['ASC', 'DESC'])) {
                    $orientation = 'ASC';
                }
                $model = $model->orderBy($column, $orientation);
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
    public function groupBy(): self
    {
        $args = func_get_args();
        if (empty($args)) {
            return $this;
        }

        $model = $this->query();
        if (is_array($args[0])) {
            $model = $model->groupBy($args[0]);
        } else {
            $this->model = $model->groupBy(DB::raw($args[0]));
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
}
