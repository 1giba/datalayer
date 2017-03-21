<?php

namespace OneGiba\DataLayer\Traits;

use Illuminate\Database\Eloquent\Model;
use OneGiba\DataLayer\Queries\Query;

trait ActionalizeQueries
{
    /**
     * Get first row
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first()
    {
        return $this->builder->first();
    }

    /**
     * Get all rows
     *
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        return $this->builder->get();
    }

    /**
     * Paginate rows
     *
     * @param integer $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage)
    {
        return $this->builder->paginate($perPage);
    }

    /**
     * Show sql
     *
     * @return string
     */
    public function sql()
    {
        return $this->builder->toSql();
    }
}
