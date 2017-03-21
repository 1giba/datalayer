<?php

namespace OneGiba\DataLayer\Traits;

trait Applies
{
    /**
     * Apply the filter by range
     *
     * @param string $key
     * @param mixed  $value
     * @param string $wordkey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function interval($key, $value, $wordkey)
    {
        list($first, $second) = explode($wordkey, $value);
        $comparator = strlen($wordkey) === 3 ? '=' : '';

        if (! empty($first)) {
            $this->builder = $this->builder->where($key, '>' . $comparator, $first);
        }
        if (! empty($second)) {
            $this->builder = $this->builder->where($key, '<' . $comparator, $second);
        }

        return $this->builder;
    }
}
