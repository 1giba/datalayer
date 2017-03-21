<?php

namespace OneGiba\DataLayer\Contracts;

use Closure;

interface QueryInterface
{
    /**
     * Load relations
     *
     * @param  array $relations
     * @return $this
     */
    public function with(array $relations);

    /**
     * Select columns
     *
     * @param  array $columns
     * @return $this
     */
    public function select(array $columns);

    /**
     * Applies filters
     *
     * @param  array $conditions
     * @return $this
     */
    public function apply(array $conditions);

    /**
     * Group By fields
     *
     * @param array $fields
     * @return $this
     */
    public function groupBy(array $fields);

    /**
     * Having
     *
     * @param string $havingClausules
     * @return $this
     */
    public function having($havingClausule);

    /**
     * Order by fields
     *
     * @param array $fields
     * @return $this
     */
    public function orderBy(array $fields);

    /**
     * Limits the number of rows
     *
     * @param integer $quantity
     * @return $this
     */
    public function limit($quantity);

    /**
     * Add scope
     *
     * @param Closure $scope
     * @return $this
     */
    public function scopeQuery(Closure $scope);
}
