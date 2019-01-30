<?php

namespace OneGiba\DataLayer\Traits;

trait Joinable
{
    /**
     * Inner Join
     *
     * @param string $table
     * @param array  $relationships
     * @return $this
     */
    public function join(string $table, array $relationships): self
    {
        $query = $this->getQuery();
        if (count($relationships) === 1) {
            $query = $query->join(
                $table,
                array_keys($relationships)[0],
                '=',
                array_values($relationships)[0]
            );
        } else {
            $query = $query->join($table, function($join) use ($relationships) {
                foreach ($relationships as $primary => $foreign) {
                    $join->on($table, $primary, '=', $foreign);
                }
            });
        }
        $this->query = $query;
        return $this;
    }

    /**
     * Inner Join
     *
     * @param string $table
     * @param array  $relationships
     * @return $this
     */
    public function innerJoin(string $table, array $relationships): self
    {
        return $this->join($table, $relationships);
    }

    /**
     * Left Join
     *
     * @param string $table
     * @param array  $relationships
     * @return $this
     */
    public function leftJoin(string $table, array $relationships): self
    {
        $query = $this->getQuery();
        if (count($relationships) === 1) {
            $query = $query->leftJoin(
                $table,
                array_keys($relationships)[0],
                '=',
                array_values($relationships)[0]
            );
        } else {
            $query = $query->leftJoin($table, function($join) use ($relationships) {
                foreach ($relationships as $primary => $foreign) {
                    $join->on($table, $primary, '=', $foreign);
                }
            });
        }
        $this->query = $query;
        return $this;
    }

    /**
     * @param array $relationships
     * @return $this
     */
    public function with(array $relationships): self
    {
        $this->query = $this->getQuery()->with($relationships);

        return $this;
    }
}
