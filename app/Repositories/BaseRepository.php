<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository
{
    /**
     *
     * @var array
     */
    protected $filterable = [];

    /**
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    protected function applyFilters(Builder $query, array $filters = [])
    {
        foreach ($filters as $field => $value) {
            if (in_array($field, $this->filterable)) {
                if ($field === 'id' || $field === 'post_id') {
                    $query->where($field, $value);
                } else {
                    $query->where($field, 'LIKE', "%{$value}%");
                }
            }
        }
    }

    /**
     *
     * @param Builder $query
     * @param string|null $sort
     * @param string $direction
     * @return void
     */
    protected function applySorting(Builder $query, $sort = null, $direction = 'asc')
    {
        if ($sort && in_array($sort, $this->filterable)) {
            $query->orderBy($sort, $direction);
        }
    }

    /**
     *
     * @param Builder $query
     * @param int $page
     * @param int $limit
     * @return Builder
     */
    public function applyPagination(Builder $query, int $page = 1, int $limit = 10): Builder
    {
        return $query->skip(($page - 1) * $limit)->take($limit);
    }
}
