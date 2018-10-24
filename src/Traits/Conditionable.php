<?php

namespace OneGiba\DataLayer\Traits;

use Closure;

trait Conditionable
{
    /**
     * Equals
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function where(string $column, $value): self
    {
        $model = $this->query();
        if (is_array($value)) {
            $model = $model->whereIn($column, $value);
        } else {
            $model->where($column, $value);
        }
        $this->model = $model;
        return $this;
    }

    /**
     * Different
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function whereNot(string $column, $value): self
    {
        $model = $this->query();
        if (is_array($value)) {
            $model = $model->whereNotIn($column, $value);
        } else {
            $model->where($column, '<>', $value);
        }
        $this->model = $model;
        return $this;
    }

    /**
     * Exact or parcial
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function whereLike(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->where($column, 'LIKE', $value);
        return $this;
    }

    /**
     * Don't contain
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function whereNotLike(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->where($column, 'NOT LIKE', $value);
        return $this;
    }

    /**
     * Between
     *
     * @param string $column
     * @param mixed  $from
     * @param mixed  $until
     * @return $this
     */
    public function whereBetween(string $column, $from, $until): self
    {
        $model = $this->query();
        $this->model = $model->whereBetween($column, $from, $until);
        return $this;
    }

    /**
     * Greater than
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function whereGreaterThan(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->where($column, '>', $value);
        return $this;
    }

    /**
     * Greater or equals than
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function whereGreaterOrEqualThan(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->where($column, '>=', $value);
        return $this;
    }

    /**
     * Less than
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function whereLessThan(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->where($column, '<', $value);
        return $this;
    }

    /**
     * Less or equals than
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function whereLessOrEqualThan(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->where($column, '<=', $value);
        return $this;
    }

    /**
     * IS NULL
     *
     * @param string $column
     * @return $this
     */
    public function whereIsNull(string $column): self
    {
        $model = $this->query();
        $this->model = $model->whereNull($column);
        return $this;
    }

    /**
     * IS NOT NULL
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function whereIsNotNull(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->whereNotNull($column);
        return $this;
    }

    /**
     * Grouped conditions
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function whereGroup(Closure $closure): self
    {
        $model = $this->query();
        $this->model = $model->where($closure);
        return $this;
    }

    /**
     * Equals
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orWhere(string $column, $value): self
    {
        $model = $this->query();
        if (is_array($value)) {
            $model = $model->orWhereIn($column, $value, 'or');
        } else {
            $model->orWhere($column, $value);
        }
        $this->model = $model;
        return $this;
    }

    /**
     * Different
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orWhereNot(string $column, $value): self
    {
        $model = $this->query();
        if (is_array($value)) {
            $model = $model->orWhereNotIn($column, $value, 'or');
        } else {
            $model->orWhere($column, '<>', $value);
        }
        $this->model = $model;
        return $this;
    }

    /**
     * Exact or parcial
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orWhereLike(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->orWhere($column, 'LIKE', $value);
        return $this;
    }

    /**
     * Don't contain
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orWhereNotLike(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->orWhere($column, 'NOT LIKE', $value);
        return $this;
    }

    /**
     * Between
     *
     * @param string $column
     * @param mixed  $from
     * @param mixed  $until
     * @return $this
     */
    public function orWhereBetween(string $column, $from, $until): self
    {
        $model = $this->query();
        $this->model = $model->orWhereBetween($column, $from, $until, 'or');
        return $this;
    }

    /**
     * Greater than
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orWhereGreaterThan(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->orWhere($column, '>', $value);
        return $this;
    }

    /**
     * Greater or equals than
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orWhereGreaterOrEqualThan(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->orWhere($column, '>=', $value);
        return $this;
    }

    /**
     * Less than
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orWhereLessThan(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->orWhere($column, '<', $value);
        return $this;
    }

    /**
     * Less or equals than
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orWhereLessOrEqualThan(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->orWhere($column, '<=', $value);
        return $this;
    }

    /**
     * IS NULL
     *
     * @param string $column
     * @return $this
     */
    public function orWhereIsNull(string $column): self
    {
        $model = $this->query();
        $this->model = $model->whereNull($column, 'or');
        return $this;
    }

    /**
     * IS NOT NULL
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orWhereIsNotNull(string $column, $value): self
    {
        $model = $this->query();
        $this->model = $model->whereNotNull($column, 'or');
        return $this;
    }
}
