<?php

namespace OneGiba\DataLayer\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use DB;

trait Searchable
{
    /**
     * @const array
     */
    protected $allFields = ['*'];

    /**
     * Show fields in query
     *
     * @param array $fields
     * @return $this
     */
    public function showFields(array $fields): self
    {
        $query = $this->getQuery();
        $this->selectedFields = [];
        foreach ($fields as $field) {
            $this->selectedFields[] = DB::raw($field);
        }
        if (empty($this->selectedFields)) {
            $this->selectedFields = $this->allFields;
        }
        $this->query = $query->select($this->selectedFields);
        return $this;
    }

    /**
     * @param array $sortFields
     * @return $this
     */
    public function groupBy(...$args): self
    {
        if (empty($args)) {
            return $this;
        }

        $query = $this->getQuery();
        if (is_array($args[0])) {
            $groups = array_map(function ($group) {
                return DB::raw($group);
            }, $args[0]);

            $query = $query->groupBy($groups);
        } else {
            $query = $query->groupBy(DB::raw($args[0]));
        }
        $this->query = $query;
        return $this;
    }

    /**
     * @param string $aggregation
     * @param string $operator
     * @param mixed $value
     * @return $this
     */
    public function having(string $aggregation, string $operator, $value): self
    {
        $query = $this->getQuery();
        $this->query = $query->having(
            DB::raw($aggregation),
            $operator,
            DB::raw($value)
        );
        return $this;
    }

    /**
     * @return $this
     */
    public function distinct(): self
    {
        $query = $this->getQuery();
        $this->query = $query->distinct();
        return $this;
    }

    /**
     * @param int $max
     * @param int $offset
     * @return $this
     */
    public function limit(int $max, int $offset = 0): self
    {
        $query = $this->getQuery();
        $this->query = $query->limit($max)->offset($offset);
        return $this;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function offest(int $quantity): self
    {
        $query = $this->getQuery();
        $this->query = $query->offest($quantity);
        return $this;
    }

    /**
     * @param string $sortField
     * @return $this
     */
    public function sortAscending(string $sortField): self
    {
        $query = $this->getQuery();
        $this->query = $query->orderBy(DB::raw($sortField), 'ASC');
        return $this;
    }

    /**
     * @param string $sortField
     * @return $this
     */
    public function sortDescending(string $sortField): self
    {
        $query = $this->getQuery();
        $this->query = $query->orderBy(DB::raw($sortField), 'DESC');
        return $this;
    }
}
