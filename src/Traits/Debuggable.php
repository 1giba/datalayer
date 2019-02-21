<?php

namespace OneGiba\DataLayer\Traits;

trait Debuggable
{
    /**
     * Show SQL from query
     *
     * @return string
     */
    public function printSql(): string
    {
        return $this->getQuery()->toSql();
    }
}
