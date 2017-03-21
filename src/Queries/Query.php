<?php

namespace OneGiba\DataLayer\Queries;

use Illuminate\Database\Eloquent\Builder;
use OneGiba\DataLayer\Contracts\QueryInterface;
use OneGiba\DataLayer\Contracts\RepositoryInterface;
use OneGiba\DataLayer\Contracts\PostgresInterface;
use OneGiba\DataLayer\Traits\ActionalizeQueries;
use OneGiba\DataLayer\Traits\Applies;
use Closure;

class Query implements QueryInterface
{
    use ActionalizeQueries,
        Applies;

    /**
     * @const string
     */
    const WHERE_GREATER_LOWER = '..';

    /**
     * @const string
     */
    const WHERE_GREATER_LOWER_EQUALS = '...';

    /**
     * @const string
     */
    const WHERE_LIKE = '%';

    /**
     * @const string
     */
    const WHERE_LIKE_OPERATOR_MYSQL = 'LIKE';

    /**
     * @const string
     */
    const WHERE_LIKE_OPERATOR_POSTGRES = 'ILIKE';

    /**
     * @const string
     */
    const WHERE_IS = null;

    /**
     * @const string
     */
    const WHERE_IS_NOT = '!';

    /**
     * @var \Illuminate\Database\Eloquent\Builder;
     */
    protected $builder;

    /**
     * @var \OneGiba\DataLayer\Contracts\RepositoryInterface
     */
    protected $repository;

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \OneGiba\DataLayer\Contracts\RepositoryInterface $repository
     * @return void
     */
    public function __construct(Builder $builder, RepositoryInterface $repository)
    {
        $this->builder    = $builder;
        $this->repository = $repository;
    }

    /**
     * Load relations
     *
     * @param  array $relations
     * @return $this
     */
    public function with(array $relations)
    {
        $this->builder = $this->builder->with($relations);

        return $this;
    }

    /**
     * Select columns
     *
     * @param  array $columns
     * @return $this
     */
    public function select(array $columns)
    {
        if (! empty($columns)) {
            $this->builder = $this->builder->select($columns);
        }

        return $this;
    }

    /**
     * Applies filters
     *
     * @param  array $conditions
     * @return $this
     */
    public function apply(array $conditions)
    {
        foreach ($conditions as $fieldname => $values) {
            if (is_array($values)) {
                $this->builder = $this->builder->whereIn($fieldname, $values);
                continue;
            }

            if (strpos($values, self::WHERE_GREATER_LOWER_EQUALS) !== false) {
                $this->builder = $this->interval($fieldname, $values, self::WHERE_GREATER_LOWER_EQUALS);
                continue;
            }

            if (strpos($values, self::WHERE_GREATER_LOWER) !== false) {
                $this->builder = $this->interval($fieldname, $values, self::WHERE_GREATER_LOWER);
                continue;
            }

            if (strpos($values, self::WHERE_LIKE) !== false) {
                $operator = $this->repository instanceof PostgresInterface
                    ? self::WHERE_LIKE_OPERATOR_POSTGRES
                    : self::WHERE_LIKE_OPERATOR_MYSQL;

                $this->builder = $this->builder->where($fieldname, $operator, $values);
                continue;
            }

            if ($values === self::WHERE_IS) {
                $this->builder = $this->builder->whereIsNull($fieldname);
                continue;
            }

            if ($values === self::WHERE_IS_NOT) {
                $this->builder = $this->builder->whereIsNotNull($fieldname);
                continue;
            }

            $this->builder = $this->builder->where($fieldname, $values);
        }

        return $this;
    }

    /**
     * Group by fields
     *
     * @param array $fields
     * @return $this
     */
    public function groupBy(array $fields)
    {
        if (! empty($fields)) {
            $this->builder = $this->builder->groupBy(...$fields);
        }

        return $this;
    }

    /**
     * Having
     *
     * @param string $havingClausules
     * @return $this
     */
    public function having($havingClausule)
    {
        if ($havingClausule) {
            $this->builder = $this->builder->havingRaw($havingClausule);
        }

        return $this;
    }

    /**
     * Order By fields
     *
     * @param array $fields
     * @return $this
     */
    public function orderBy(array $fields)
    {
        foreach ($fields as $field) {
            $fieldname   = str_replace(['+', '-'], '', $field);
            $orientation = strpos($field, '-') !== false ? 'DESC' : 'ASC';

            $this->builder = $this->builder->orderBy($fieldname, $orientation);
        }

        return $this;
    }

    /**
     * Limits the number of rows
     *
     * @param integer $quantity
     * @return $this
     */
    public function limit($quantity)
    {
        if ($quantity > 0) {
            $this->builder = $this->builder->take($quantity);
        }

        return $this;
    }

    /**
     * Add scope
     *
     * @param Closure $scope
     * @return $this
     */
    public function scopeQuery(Closure $scope)
    {
        if (is_callable($scope)) {
            $this->builder = $scope($this->builder);
        }

        return $this;
    }
}
