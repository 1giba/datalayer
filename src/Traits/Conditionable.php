<?php

namespace OneGiba\DataLayer\Traits;

use Closure;

trait Conditionable
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder|null
     */
    protected $clausules;

    /**
     * Is equal
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function isEqual(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = is_array($value) ?
            $query->whereIn($column, $value, $operator) :
            $query->where($column, '=', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Is not equal
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function isNotEqual(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = is_array($value) ?
            $query->whereNotIn($column, $value, $operator) :
            $query->where($column, '<>', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Like
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function like(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->where($column, 'LIKE', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Not like
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function notLike(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->where($column, 'NOT LIKE', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Is greater Than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function isGreaterThan(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->where($column, '>', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Is greater than equal
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function isGreaterThanEqual(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->where($column, '>=', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Is less than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function isLessThan(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->where($column, '<', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Is less than equal
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function isLessThanEqual(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->where($column, '<=', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Between values
     *
     * @param string $column
     * @param mixed  $value1
     * @param mixed  $value2
     * @param string $operator
     * @return $this
     */
    public function between(string $column, $value1, $value2, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->whereBetween($column, [$value1, $value2], $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Is null
     *
     * @param string $column
     * @param string $operator
     * @return $this
     */
    public function isNull(string $column, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->whereNull($column, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Is not null
     *
     * @param string $column
     * @param string $operator
     * @return $this
     */
    public function isNotNull(string $column, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->whereNotNull($column, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Agroup clausules with 'AND'
     *
     * @param \Closure $closure
     * @return $this
     */
    public function clausules(Closure $closure): self
    {
        $this->clausules = $this->model->newModelQuery();
        call_user_func($closure, $this);
        $this->getQuery()->addNestedWhereQuery($this->clausules->getQuery());
        $this->clausules = null;
        return $this;
    }

    /**
     * Agroup clausules with 'OR'
     *
     * @param \Closure $closure
     * @return $this
     */
    public function orClausules(Closure $closure): self
    {
        $this->clausules = $this->model->newModelQuery();
        call_user_func($closure, $this);
        $this->getQuery()->addNestedWhereQuery($this->clausules->getQuery(), 'OR');
        $this->clausules = null;
        return $this;
    }

    /**
     * Or is equal
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orIsEqual(string $column, $value): self
    {
        return $this->isEqual($column, $value, 'OR');
    }

    /**
     * Or is not equal
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orIsNotEqual(string $column, $value): self
    {
        return $this->isNotEqual($column, $value, 'OR');
    }

    /**
     * Or like
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orLike(string $column, $value): self
    {
        return $this->like($column, $value, 'OR');
    }

    /**
     * Or not like
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orNotLike(string $column, $value): self
    {
        return $this->notLike($column, $value, 'OR');
    }

    /**
     * Or is greater than
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orIsGreaterThan(string $column, $value): self
    {
        return $this->isGreaterThen($column, $value, 'OR');
    }

    /**
     * Or is greater than equal
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orIsGreaterThanEqual(string $column, $value): self
    {
        return $this->isGreaterThenEqual($column, $value, 'OR');
    }

    /**
     * Or is less than
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orIsLessThan(string $column, $value): self
    {
        return $this->isLessThan($column, $value, 'OR');
    }

    /**
     * Or is less than equal
     *
     * @param string $column
     * @param mixed  $value
     * @return $this
     */
    public function orIsLessThanEqual(string $column, $value): self
    {
        return $this->isLessThanEqual($column, $value, 'OR');
    }

    /**
     * Or between
     *
     * @param string $column
     * @param mixed  $value1
     * @param mixed  $value2
     * @return $this
     */
    public function orBetween(string $column, $value1, $value2): self
    {
        return $this->between($column, $value1, $value2, 'OR');
    }

    /**
     * Or is null
     *
     * @param string $column
     * @return $this
     */
    public function orIsNull(string $column): self
    {
        return $this->isNull($column, 'OR');
    }

    /**
     * Or is not null
     *
     * @param string $column
     * @return $this
     */
    public function orIsNotNull(string $column): self
    {
        return $this->isNotNull($column, 'OR');
    }
}
