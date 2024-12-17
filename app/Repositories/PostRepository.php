<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

class PostRepository
{
    protected $filterable = ['id','topic','created_at','updated_at'];

    /**
     *
     * @param array $filters
     * @param string|null $sort
     * @param string $direction
     * @param string|null $commentFilter
     * @return Builder
     */
    public function queryPosts(
        array $filters = [], 
        $sort = null, 
        $direction = 'asc', 
        $commentFilter = null
    ): Builder {
        $query = Post::query();

        $this->applyFilters($query, $filters);
        $this->applyCommentFilter($query, $commentFilter);
        $this->applySorting($query, $sort, $direction);

        return $query;
    }

    protected function applyFilters(Builder $query, array $filters = [])
    {
        foreach ($filters as $field => $value) {
            if (in_array($field, $this->filterable)) {
                if ($field === 'id') {
                    $query->where($field, $value);
                } else {
                    $query->where($field, 'LIKE', '%' . $value . '%');
                }
            }
        }
    }

    /**
     * 
     * @param Builder $query
     * @param string|null $commentFilter
     */
    protected function applyCommentFilter(Builder $query, ?string $commentFilter)
    {
        if (!empty($commentFilter)) {
            $query->whereHas('comments', function($q) use ($commentFilter) {
                $q->where('content', 'LIKE', "%{$commentFilter}%");
            });
        }
    }

    protected function applySorting(Builder $query, $sort = null, $direction = 'asc')
    {
        if ($sort && in_array($sort, $this->filterable)) {
            $query->orderBy($sort, $direction);
        }
    }

    public function applyPagination(Builder $query, int $page = 1, int $limit = 10): Builder
    {
        return $query->skip(($page - 1) * $limit)->take($limit);
    }
}
