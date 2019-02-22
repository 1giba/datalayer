<?php

namespace OneGiba\DataLayer\Traits;

trait Massble
{
    /**
     * Mass update
     *
     * @return bool
     */
    public function massUpdate(array $fillable): bool
    {
        $isUpdated = $this->getQuery()->update($fillable);
        $this->resetQuery();
        return $isUpdated;
    }

    /**
     * Mass update
     *
     * @return bool
     */
    public function massDelete(): bool
    {
        $isDeleted = $this->getQuery()->delete();
        $this->resetQuery();
        return $isDeleted;
    }

    /**
     * Force delete
     *
     * @return bool
     */
    public function forceDelete(): bool
    {
        $isDeleted = $this->getQuery()->forceDelete();
        $this->resetQuery();
        return $isDeleted;
    }
}