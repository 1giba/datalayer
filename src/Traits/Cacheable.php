<?php

namespace OneGiba\DataLayer\Traits;

use Illuminate\Contracts\Cache\Repository as Cache;
use OneGiba\DataLayer\Traits\WithCachingMethods;

trait Cacheable
{
    use WithCachingMethods;

    /**
     * @var \Illuminate\Contracts\Cache\Repository|null
     */
    protected $cache = null;

    /**
     * @var bool
     */
    protected $isCached = false;

    /**
     * Get expiration time (In minutes)
     *
     * @return int
     */
    abstract public function getExpirationTime(): int;

    /**
     * Get tag
     *
     * @return string|null
     */
    public function getTag(): ?string
    {
        return null;
    }

    /**
     * Get cache
     *
     * @return $this
     */
    public function cached(): self
    {
        $this->isCached = true;

        return $this;
    }

    /**
     * Get cache
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function getCache(): Cache
    {
        if ($this->cache === null) {
            $this->cache = app(Cache::class);
        }

        $tag = $this->getTag();
        if ($tag !== null) {
            $this->cache = $this->cache->tags($tag);
        }

        return $this->cache;
    }

    /**
     * Generate key cache to all methods
     *
     * @return string
     */
    public function generateHash(): string
    {
        $sql = $this->getQuery()->toSql();
        $max = substr_count($sql, '?');
        if ($max > 0) {
            $bindings = [];
            for ($count = 0; $count < $max; $count++) {
                $bindings[] = '?';
            }
            $sql = str_replace(
                $bindings,
                $this->getQuery()->getBindings(),
                $sql
            );
        }
        return md5($this->getQuery()->getConnection()->getDatabaseName() . '|' . $sql);
    }

    /**
     * Generate key cache to fetchAll method
     *
     * @return string
     */
    public function generateFetchAllHash(): string
    {
        return md5($this->getQuery()->getConnection()->getDatabaseName() . '|SELECT * FROM ' . $this->model->getTable());
    }
}
