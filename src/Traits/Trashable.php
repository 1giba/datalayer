<?php

namespace OneGiba\DataLayer\Traits;

trait Trashable
{
    /**
     * Add soft deleted records in query
     *
     * @return $this
     */
    public function addDeletedRecords(): self
    {
        $this->query = $this->getQuery()->withTrashed();
        return $this;
    }

    /**
     * Make query only with deleted records
     *
     * @return $this
     */
    public function onlyDeletedRecords(): self
    {
        $this->query = $this->getQuery()->onlyTrashed();
        return $this;
    }

    /**
     * Restore deleted records
     *
     * @return mixed
     */
    public function restoreyDeletedRecords(): mixed
    {
        $results = $this->getQuery()->restore();
        $this->resetQuery();
        return $results;
    }
}