<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Dto\CommentSearchByCriteriaDto;
use App\Models\Comment;

interface CommentServiceInterface
{
    public function getComments(CommentSearchByCriteriaDto $dto): array;
    public function createComment(int $postId, string $content): Comment;
    public function deleteComment(int $id): bool;
}
