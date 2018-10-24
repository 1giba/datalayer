<?php

namespace OneGiba\DataLayer\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Requestable
{
    /**
     * LIKE clausule
     *
     * @var array
     */
    protected $partials = [];

    /**
     * Allowed sort fields
     *
     * @var array
     */
    protected $allowedSorts = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $customFilters = [];

    /**
     * Filtros com LIKE
     *
     * @param string $attribute
     * @return $this
     */
    public function addPartialSearch(string $attribute): self
    {
        if (! in_array($attribute, $this->partials)) {
            $this->partials[] = $attribute;
        }

        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function queryString(array $params = []): self
    {
        foreach ($params as $param => $value) {
            if (is_null($value)) {
                continue;
            }

            if ($param === 'attrs') {
                $this->model = $this->selectFields($value);
                continue;
            }

            if ($param === 'sort') {
                $this->model = $this->applySorting($value);
                continue;
            }

            if (isset($this->customFilters[$param])) {
                $this->model = $this->applyCustomFilters($param, $value);
                continue;
            }

            if (isset($this->attributes[$param])) {
                $param = $this->attributes[$param];
            }

            if (is_array($value)) {
                $this->model = $this->query()->whereIn($param, $value);
                continue;
            }

            $values = explode(',', $value);
            if (count($values) === 1) {
                if (in_array($param, $this->partials)) {
                    $this->model = $this->query()->where($param, 'ILIKE', '%' . $value . '%');
                    continue;
                }
                $this->model = $this->query()->where($param, $value);
                continue;
            }

            $this->model = $this->query()->whereIn($param, $values);
        }

        return $this;
    }

    /**
     * @param string $attributes
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function selectFields(string $attributes): Builder
    {
        return $this->query()
            ->select(explode(',', $attributes));
    }

    /**
     * @param string $sortFields
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySorting(string $sortFields): Builder
    {
        $model  = $this->query();
        $fields = explode(',', $sortFields);
        foreach ($fields as $field) {
            $hasPrefix      = substr($field, 0, 1) === '-';
            $field          = $hasPrefix ? substr($field, 1) : $field;
            $orientation    = $hasPrefix ? 'DESC' : 'ASC';

            if (count($this->allowedSorts) > 0 &&
                ! in_array($field, $this->allowedSorts)) {
                continue;
            }
            $model = $model->orderBy($field, $orientation);
        }
        return $model;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function allowedSorts(array $fields): self
    {
        $this->allowedSorts = $fields;

        return $this;
    }

    /**
     * Referencia o campo
     *
     * @param string $alias
     * @param string $fieldname
     * @return $this
     */
    public function refer(string $alias, string $fieldname): self
    {
        $this->attributes[$alias] = $fieldname;

        return $this;
    }

    /**
     * Adiciona filtros customizados
     *
     * @param array $filters
     * @return $this
     */
    public function addCustomFilters(array $filters): self
    {
        $this->customFilters = $filters;

        return $this;
    }

    /**
     * @param mixed $param
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyCustomFilters($param, $value): Builder
    {
        if (!isset($this->customFilters[$param][$value])) {
            return $this->query();
        }

        return $this->query()->whereRaw($this->customFilters[$param][$value]);
    }
}
