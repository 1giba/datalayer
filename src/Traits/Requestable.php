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
     * Allowed filter fields
     *
     * @var array
     */
    protected $allowedFilters = [];

    /**
     * Allowed sort fields
     *
     * @var array
     */
    protected $allowedSorts = [];

    /**
     * @var string
     */
    protected $sortParam = 'sort';

    /**
     * @var array
     */
    protected $aliases = [];

    /**
     * @var string
     */
    protected $attributesParam = 'attrs';

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
        $query = $this->getQuery();
        foreach ($params as $param => $value) {
            if (is_null($value) || $param === 'page') {
                continue;
            }

            if ($param === $this->attributesParam) {
                $query = $this->selectFields($value);
                continue;
            }

            if ($param === $this->sortParam) {
                $query = $this->applySorting($value);
                continue;
            }

            if (isset($this->customFilters[$param])) {
                $query = $this->applyCustomFilters($param, $value);
                continue;
            }

            if (count($this->allowedFilters) > 0 &&
                ! in_array($param, $this->allowedFilters)) {
                continue;
            }

            if (isset($this->aliases[$param])) {
                $param = $this->aliases[$param];
            }

            $values = explode(',', $value);
            if (count($values) === 1) {
                $this->isEqual($param, $value);
                continue;
            }

            $this->isEqual($param, $values);
        }

        return $this;
    }

    /**
     * @param string $attributes
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function selectFields(string $attributes): Builder
    {
        $attrs = explode(',', $attributes);
        $results = [];
        foreach ($attrs as $attr) {
            if (in_array($attr, array_keys($this->aliases))) {
                $results[] = $this->aliases[$attr] . ' AS ' . $attr;
                continue;
            }
            $results[] = $attr;
        }

        return $this->showFields($results);
    }

    /**
     * @param string $sortFields
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySorting(string $sortFields): Builder
    {
        $query  = $this->getQuery();
        $fields = explode(',', $sortFields);
        foreach ($fields as $field) {
            $hasPrefix      = substr($field, 0, 1) === '-';
            $field          = $hasPrefix ? substr($field, 1) : $field;
            $orientation    = $hasPrefix ? 'DESC' : 'ASC';

            if (count($this->allowedSorts) > 0 &&
                ! in_array($field, $this->allowedSorts)) {
                continue;
            }
            $field = isset($this->aliases[$field])
                ? $this->aliases[$field]
                : $field;
            $query = $query->orderBy($field, $orientation);
        }
        return $query;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function allowedFilters(array $fields): self
    {
        $this->allowedFilters = $fields;

        return $this;
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
        $this->aliases[$alias] = $fieldname;

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
            return $this->getQuery();
        }

        return $this->getQuery()->whereRaw($this->customFilters[$param][$value]);
    }

    /**
     * @param string $param
     * @return $this
     */
    public function changeAttrsParam(string $param): self
    {
        $this->attributesParam = $param;

        return $this;
    }

    /**
     * @param string $sort
     * @return $this
     */
    public function changeSortParam(string $sort): self
    {
        $this->sortParam = $sort;

        return $this;
    }
}
