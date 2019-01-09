<?php

namespace OneGiba\DataLayer\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    /**
     * First row
     *
     * @return mixed
     */
    public function first();

    /**
     * Get collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection;

    /**
     * Paginate data
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 50): LengthAwarePaginator;

    /**
     * New entry
     *
     * @param array $fillable
     * @return mixed
     */
    public function create(array $fillable);

    /**
     * Updates data
     *
     * @param array $fillable
     * @param int $resourceId
     * @return mixed
     */
    public function update(array $fillable, int $resourceId);

    /**
     * Removes data
     *
     * @param int $resourceId
     * @return int
     */
    public function delete(int $resourceId): int;

    /**
     * Rows count
     *
     * @return int
     */
    public function count(): int;

    /**
     * Sum field value
     *
     * @param string $column
     * @return mixed
     */
    public function sum(string $column);

    /**
     * Max field value
     *
     * @param string $column
     * @return mixed
     */
    public function max(string $column);
}
