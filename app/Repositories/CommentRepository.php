<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;

class CommentRepository extends BaseRepository
{
    protected $filterable = ['id', 'post_id', 'content', 'abbreviation', 'created_at', 'updated_at'];

    public function queryComments(array $filters = [], $sort = null, $direction = 'asc')
    {
        $query = Comment::query();

        $this->applyFilters($query, $filters);
        $this->applySorting($query, $sort, $direction);

        return $query;
    }
}
