<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\CommentRepository;

class CommentService implements CommentServiceInterface
{
    protected $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getComments(
        array $filters = [], 
        $sort = null, 
        $direction = 'asc', 
        $limit = 10, 
        $page = 1, 
        $with = null
    ) {
        $query = $this->repository->queryComments($filters, $sort, $direction);
        $total = $query->count();
        $query = $this->repository->applyPagination($query, $page, $limit);

        $comments = $query->get();

        if ($with === 'post') {
            $comments->load('post');
        }

        return [
            'result' => $comments,
            'count' => $total
        ];
    }

    public function createComment($postId, $content)
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

    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return false;
        }

        $comment->delete();
        return true;
    }
}
