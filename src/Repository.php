<?php

namespace OneGiba\DataLayer;

use Illuminate\Database\Eloquent\Model;
use OneGiba\DataLayer\Contracts\RepositoryInterface;
use OneGiba\DataLayer\Traits\Querialize;
use OneGiba\DataLayer\Traits\MakesErrorResponses;
use Exception;

abstract class Repository implements RepositoryInterface
{
    use Querialize, MakesErrorResponses;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

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
    ) {
        $collection = $this->query($this->model)
            ->select($columns)
            ->orderBy($sortableFields)
            ->limit($limit)
            ->get();

        if ($collection->isEmpty()) {
            $this->throwErrorNotFound([
                'method'         => __METHOD__,
                'columns'        => $columns,
                'sortableFields' => $sortableFields,
                'limit'          => $limit,
            ]);
        }

        return $collection;
    }

    /**
     * Get the first row
     *
     * @param array $conditions
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \OneGiba\DataLayer\Exceptions\NotFoundException
     */
    public function findFirst(array $conditions = [], $columns = ['*'])
    {
        $model = $this->query($this->model)
            ->select($columns)
            ->apply($conditions)
            ->first();

        if (! $model) {
            $this->throwErrorNotFound([
                'method'     => __METHOD__,
                'conditions' => $conditions,
                'columns'    => $columns,
            ]);
        }

        return $model;
    }

    /**
     * Find by ID
     *
     * @param integer $resourceId
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \OneGiba\DataLayer\Exceptions\NotFoundException
     */
    public function findById($resourceId, $columns = ['*'])
    {
        $model = $this->model->find($resourceId, $columns);

        if (! $model) {
            $this->throwErrorNotFound([
                'method'     => __METHOD__,
                'resourceId' => $resourceId,
                'columns'    => $columns,
            ]);
        }

        return $model;
    }

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
    ) {
        $collection = $this->query($this->model)
            ->select($columns)
            ->apply($conditions)
            ->orderBy($sortableFields)
            ->get();

        if ($collection->isEmpty()) {
            $this->throwErrorNotFound([
                'method'         => __METHOD__,
                'conditions'     => $conditions,
                'columns'        => $columns,
                'sortableFields' => $sortableFields,
                'limit'          => $limit,
            ]);
        }

        return $collection;
    }

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
    ) {
        return $this->query($this->model)
            ->select($columns)
            ->apply($conditions)
            ->orderBy($sortableFields)
            ->paginate($perPage);
    }

    /**
     * Create a record
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \OneGiba\DataLayer\Exceptions\OnCreatingException
     */
    public function create(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (Exception $error) {
            $this->throwErrorOnCreating([
                'data' => $data,
            ], $error->getMessage());
        }
    }

    /**
     * Update data
     *
     * @param array   $data
     * @param integer $resourceId
     * @return boolean
     * @throws \OneGiba\DataLayer\Exceptions\OnUpdatingException
     */
    public function update(array $data, $resourceId)
    {
        $model = $this->findById($resourceId);

        try {
            return $model->fill($data)->save();
        } catch (Exception $error) {
            $this->throwErrorOnUpdating([
                'data'        => $data,
                'resource_id' => $resourceId,
            ], $error->getMessage());
        }
    }

    /**
     * Delete row
     *
     * @param mixed $resourceId
     * @return integer
     * @throws \OneGiba\DataLayer\Exceptions\OnDeletingException
     */
    public function delete($resourceId)
    {
        try {
            return $this->model->destroy($resourceId);
        } catch (Exception $error) {
            $this->throwErrorOnDeleting([
                'resource_id' => $resourceId,
            ], $error->getMessage());
        }
    }
}
