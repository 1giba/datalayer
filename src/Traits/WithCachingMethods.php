<?php

namespace OneGiba\DataLayer\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait WithCachingMethods
{
    /**
     * { @inheritdoc }
     */
    public function findById(int $resourceId)
    {
        if ($this->isCached === true) {
            $this->isCached = false;
            return $this->getCache()->remember(
                $this->generateHash(),
                $this->getExpirationTime(),
                function() use ($resourceId) {
                    return parent::findById($resourceId);
                }
            );
        }

        return parent::findById($resourceId);
    }

    /**
     * { @inheritdoc }
     */
    public function fetchAll(): Collection
    {
        $selectedFields = $this->selectedFields;
        if ($this->isCached === true) {
            $this->isCached = false;
            return $this->getCache()->remember(
                $this->generateFetchAllHash(),
                $this->getExpirationTime(),
                function() use ($selectedFields) {
                    return parent::all($selectedFields);
                }
            );
        }
        return parent::all($selectedFields);
    }

    /**
     * { @inheritdoc }
     */
    public function first()
    {
        if ($this->isCached === true) {
            $this->isCached = false;
            return $this->getCache()->remember(
                $this->generateHash(),
                $this->getExpirationTime(),
                function() {
                    return parent::first();
                }
            );
        }
        return parent::first();
    }

    /**
     * { @inheritdoc }
     */
    public function fetch(): Collection
    {
        if ($this->isCached === true) {
            $this->isCached = false;
            return $this->getCache()->remember(
                $this->generateHash(),
                $this->getExpirationTime(),
                function() {
                    return parent::fetch();
                }
            );
        }

        return parent::fetch();
    }

    /**
     * { @inheritdoc }
     */
    public function paginate(int $perPage = 50): LengthAwarePaginator
    {
        if ($this->isCached === true) {
            $this->isCached = false;
            return $this->getCache()->remember(
                $this->generateHash(),
                $this->getExpirationTime(),
                function() use ($perPage) {
                    return parent::paginate($perPage);
                }
            );
        }
        return parent::paginate($perPage);
    }

    /**
     * { @inheritdoc }
     */
    public function create(array $fillable)
    {
        $object = parent::create($fillable);
        if ($object !== null) {
            $this->getCache()
                ->flush();
        }
        return $object;
    }

    /**
     * { @inheritdoc }
     */
    public function update(array $fillable, int $resourceId)
    {
        $object = parent::update($fillable, $resourceId);
        if ($object !== null) {
            $this->getCache()
                ->flush();
        }
        return $object;
    }

    /**
     * { @inheritdoc }
     */
    public function delete(int $resourceId): int
    {
        $result = parent::delete($resourceId);
        if ($result > 0) {
            $this->getCache()
                ->flush();
        }
        return $result;
    }

    /**
     * { @inheritdoc }
     */
    public function count(): int
    {
        if ($this->isCached === true) {
            $this->isCached = false;
            return $this->getCache()->remember(
                $this->generateHash(),
                $this->getExpirationTime(),
                function() {
                    return parent::count();
                }
            );
        }
        return parent::count();
    }

    /**
     * { @inheritdoc }
     */
    public function sum(string $column)
    {
        if ($this->isCached === true) {
            $this->isCached = false;
            return $this->getCache()->remember(
                $this->generateHash(),
                $this->getExpirationTime(),
                function() use ($column) {
                    return parent::sum($column);
                }
            );
        }
        return parent::sum($column);
    }

    /**
     * { @inheritdoc }
     */
    public function max(string $column)
    {
        if ($this->isCached === true) {
            $this->isCached = false;
            return $this->getCache()->remember(
                $this->generateHash(),
                $this->getExpirationTime(),
                function() use ($column) {
                    return parent::max($column);
                }
            );
        };
        return parent::max($column);
    }

    /**
     * { @inheritdoc }
     */
    public function min(string $column)
    {
        if ($this->isCached === true) {
            $this->isCached = false;
            return $this->getCache()->remember(
                $this->generateHash(),
                $this->getExpirationTime(),
                function() use ($column) {
                    return parent::min($column);
                }
            );
        }
    }

    /**
     * { @inheritdoc }
     */
    public function avg(string $column)
    {
        if ($this->isCached === true) {
            $this->isCached = false;
            return $this->getCache()->remember(
                $this->generateHash(),
                $this->getExpirationTime(),
                function() use ($column) {
                    return parent::avg($column);
                }
            );
        }
        return parent::avg($column);
    }
}
