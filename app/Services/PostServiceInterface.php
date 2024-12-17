<?php

namespace App\Services;

use Illuminate\Http\Request;

interface PostServiceInterface
{
    public function getPosts(array $filters = [], $sort = null, $direction = 'asc', $limit = 10, $page = 1, $with = null, $commentFilter = null);
    public function deletePost($id);
}
