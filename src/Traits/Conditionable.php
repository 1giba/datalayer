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
     * @var array
     */
    protected $clausuleMethods = [
        'equal',
        'notEqual',
        'like',
        'notLike',
        'greaterThan',
        'greaterThanEqual',
        'lessThan',
        'lessThanEqual',
        'between',
        'isNull',
        'isNotNull',
    ];

    /**
     * Equal
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function equal(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = is_array($value) ?
            $query->whereIn($column, $value, $operator) :
            $query->where($column, '=', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Not equal
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function notEqual(string $column, $value, string $operator = 'AND'): self
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
     * Greater Than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function greaterThan(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->where($column, '>', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Greater Than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function greaterThanEqual(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->where($column, '>=', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Less Than
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function lessThan(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->where($column, '<', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Less Than Equal
     *
     * @param string $column
     * @param mixed  $value
     * @param string $operator
     * @return $this
     */
    public function lessThanEqual(string $column, $value, string $operator = 'AND'): self
    {
        $query = $this->clausules ?? $this->getQuery();
        $query = $query->where($column, '<=', $value, $operator);
        $this->clausules ? $this->clausules = $query  :  $this->query = $query;

        return $this;
    }

    /**
     * Less Than
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
     * Less Than
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
     * Less Than
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
     * Agroup clausules 'AND'
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
     * Agroup clausules 'OR'
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
     * Checking for `Or` clausules methods
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call(string $method, array $params = [])
    {
        $parts = explode('_', snake_case($method));
        if (isset($parts[0]) && $parts[0] === 'or') {
            $params[] = $parts[0];
            $caller = lcfirst(str_replace($parts[0], '', $method));
            if (in_array($caller, $this->clausuleMethods)) {
                return $this->{$caller}(...$params);
            }
        }
        return $this->{$method(...$params)};
    }
}
