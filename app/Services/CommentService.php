<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\CommentRepository;
use App\Dto\CommentSearchByCriteriaDto;

class CommentService implements CommentServiceInterface
{
    protected $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getComments(CommentSearchByCriteriaDto $dto): array
    {
        $query = $this->repository->queryComments($dto->filters, $dto->sort, $dto->direction);
        $total = $query->count();
        $query = $this->repository->applyPagination($query, $dto->page, $dto->limit);

        $comments = $query->get();

        if ($dto->with === 'post') {
            $comments->load('post');
        }

        return [
            'result' => $comments,
            'count' => $total
        ];
    }

    public function createComment(int $postId, string $content): Comment
    {
        $content = strtolower($content);
        $words = explode(' ', $content);
        sort($words);
        $abbr = '';
        foreach ($words as $w) {
            $abbr .= $w[0];
        }

        return Comment::create([
            'post_id' => $postId,
            'content' => implode(' ', $words),
            'abbreviation' => $abbr
        ]);
    }

    public function deleteComment(int $id): bool
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return false;
        }

        $comment->delete();
        return true;
    }
}
