<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;

class CommentRepository
{
    protected $filterable = ['id', 'post_id', 'content', 'abbreviation', 'created_at', 'updated_at'];

    public function queryComments(array $filters = [], $sort = null, $direction = 'asc')
    {
        $query = Comment::query();
        
        $this->applyFilters($query, $filters);
        $this->applySorting($query, $sort, $direction);

        return $query;
    }

    /**
     * Primjenjuje filtriranje na query
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    protected function applyFilters(Builder $query, array $filters = [])
    {
        foreach ($filters as $field => $value) {
            if (in_array($field, $this->filterable)) {
                if (in_array($field, ['id', 'post_id'])) {
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
    public function applyPagination(Builder $query, int $page = 1, int $limit = 10)
    {
        return $query->skip(($page - 1) * $limit)->take($limit);
    }
}
