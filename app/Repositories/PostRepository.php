<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

class PostRepository extends BaseRepository
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

    protected function applyCommentFilter(Builder $query, ?string $commentFilter)
    {
        if (!empty($commentFilter)) {
            $query->whereHas('comments', function($q) use ($commentFilter) {
                $q->where('content', 'LIKE', "%{$commentFilter}%");
            });
        }
    }
}
