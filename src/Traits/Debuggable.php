<?php

namespace OneGiba\DataLayer\Traits;

trait Debuggable
{
    /**
     * Show SQL
     *
     * @return void
     */
    public function debug()
    {
        dd($this->query()->toSql());
    }
}
