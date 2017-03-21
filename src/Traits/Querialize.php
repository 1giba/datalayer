<?php

namespace OneGiba\DataLayer\Traits;

use Illuminate\Database\Eloquent\Model;
use OneGiba\DataLayer\Queries\Query;

trait Querialize
{
    /**
     * Make query
     *
     * @param  mixed $mixed
     * @return \OneGiba\DataLayer\Queries\Query
     */
    public function query($mixed)
    {
        if ($mixed instanceof Model) {
            $mixed = $mixed->newQuery();
        }

        return new Query($mixed, $this);
    }
}
