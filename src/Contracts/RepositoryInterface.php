<?php

namespace OneGiba\DataLayer\Contracts;

interface RepositoryInterface
{
    /**
     * Get all rows
     *
     * @param array   $columns
     * @param array   $sortableFields
     * @param integer $limit
     * @return \Illuminate\Support\Collection
     */
    public function findAll(
        array $columns = ['*'],
        array $sortableFields = ['id'],
        $limit = 0
    );

    /**
     * Get the first row
     *
     * @param array $conditions
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findFirst(array $conditions = [], $columns = ['*']);

    /**
     * Find by ID
     *
     * @param integer $resourceId
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById($resourceId, $columns = ['*']);

    /**
     * Find by...
     *
     * @param array $conditions
     * @param array $columns
     * @param array $sortableFields
     * @param integer $limit
     * @return \Illuminate\Support\Collection
     */
    public function findBy(
        array $conditions,
        array $columns = ['*'],
        array $sortableFields = ['id'],
        $limit = 0
    );

    /**
     * Paginate results
     *
     * @param integer $perPage
     * @param array $conditions
     * @param array $columns
     * @param array $sortableFields
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(
        $perPage,
        array $conditions = [],
        array $columns = ['*'],
        array $sortableFields = ['id']
    );

    /**
     * Create a record
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Update data
     *
     * @param array   $data
     * @param integer $resourceId
     * @return boolean
     */
    public function update(array $data, $resourceId);

    /**
     * Delete row
     *
     * @param mixed $resourceId
     * @return integer
     */
    public function delete($resourceId);
}
