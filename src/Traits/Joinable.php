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
        $model = $this->query();
        if (count($relationships) === 1) {
            $model = $model->join(
                $table,
                array_keys($relationships)[0],
                '=',
                array_values($relationships)[0]
            );
        } else {
            $model = $model->join($table, function($join) use ($relationships) {
                foreach ($relationships as $primary => $foreign) {
                    $join->on($table, $primary, '=', $foreign);
                }
            });
        }
        $this->model = $model;
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
        $model = $this->query();
        if (count($relationships) === 1) {
            $model = $model->leftJoin(
                $table,
                array_keys($relationships)[0],
                '=',
                array_values($relationships)[0]
            );
        } else {
            $model = $model->leftJoin($table, function($join) use ($relationships) {
                foreach ($relationships as $primary => $foreign) {
                    $join->on($table, $primary, '=', $foreign);
                }
            });
        }
        $this->model = $model;
        return $this;
    }

    /**
     * @param array $relationships
     * @return $this
     */
    public function with(array $relationships): self
    {
        $this->model = $this->query()->with($relationships);

        return $this;
    }
}
