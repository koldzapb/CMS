<?php

namespace App\Services;

use Illuminate\Http\Request;

interface CommentServiceInterface
{
    public function getComments(
        array $filters = [], 
        $sort = null, 
        $direction = 'asc', 
        $limit = 10, 
        $page = 1, 
        $with = null
    );
    public function createComment($postId, $content);
    public function deleteComment($id);
}
