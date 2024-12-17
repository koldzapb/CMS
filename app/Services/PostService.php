<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Dto\PostSearchByCriteriaDto;
use App\Models\Post;

class PostService implements PostServiceInterface
{
    protected $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPosts(PostSearchByCriteriaDto $dto): array
    {
        $query = $this->repository->queryPosts($dto->filters, $dto->sort, $dto->direction, $dto->commentFilter);

        $total = $query->count();
        $query = $this->repository->applyPagination($query, $dto->page, $dto->limit);

        $posts = $query->get();

        if ($dto->with === 'comments') {
            $posts->load('comments');
        }

        return [
            'result' => $posts,
            'count' => $total
        ];
    }

    public function deletePost(int $id): bool
    {
        $post = Post::find($id);
        if (!$post) {
            return false;
        }

        $post->comments()->delete();
        $post->delete();

        return true;
    }
}
