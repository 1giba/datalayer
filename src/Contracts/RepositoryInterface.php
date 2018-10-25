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
     * @param array $columns
     * @param array $conditions
     * @return mixed
     */
    public function first(array $columns = ['*']);

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
}
