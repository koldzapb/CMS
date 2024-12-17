<?php

namespace App\Services;

use App\Repositories\PostRepository;

class PostService implements PostServiceInterface
{
    protected $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPosts(
        array $filters = [], 
        $sort = null, 
        $direction = 'asc', 
        $limit = 10, 
        $page = 1, 
        $with = null,
        $commentFilter = null
    ) {
        $query = $this->repository->queryPosts($filters, $sort, $direction, $commentFilter);

        $total = $query->count();
        $query = $this->repository->applyPagination($query, $page, $limit);
        $posts = $query->get();

        if ($with === 'comments') {
            $posts->load('comments');
        }

        return [
            'result' => $posts,
            'count' => $total
        ];
    }

    public function deletePost($id)
    {
        $post = \App\Models\Post::find($id);
        if (!$post) {
            return false;
        }

        $post->comments()->delete();
        $post->delete();

        return true;
    }
}
