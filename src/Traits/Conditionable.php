<?php

namespace OneGiba\DataLayer\Traits;

use Closure;

trait Conditionable
{
    /**
     * @var \Closure|null
     */
    protected $scopeQuery = null;

    /**
     * @var string
     */
    protected $equals = '=';

    /**
     * Set Equals
     *
     * @param string $equals
     * @return $this
     */
    public function setEquals(string $equals): self
    {
        $this->equals = $equals;

        return $this;
    }

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

        $model = is_array($value) ?
            $model->whereIn($column, $value) :
            $model->where($column, $this->equals, $value);

        $this->model = $model;
        return $this;
    }

    /**
     * Query scope
     *
     * @param \Closure $scope
     * @return $this
     */
    public function setScope(Closure $scope): self
    {
        $this->scopeQuery = $scope;

        return $this;
    }

    /**
     * Reset query scope
     *
     * @return $this
     */
    public function resetScope(): self
    {
        $this->scopeQuery = null;

        return $this;
    }

    /**
     * Apply query scope
     *
     * @return $this
     */
    public function applyScope(): self
    {
        if ($this->scopeQuery !== null && is_callable($this->scopeQuery)) {
            $model = $this->query();
            $callback = $this->scopeQuery;
            $model = $callback($model);
        }

        return $this;
    }
}
