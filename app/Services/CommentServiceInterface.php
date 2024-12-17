<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Dto\CommentSearchByCriteriaDto;

interface CommentServiceInterface
{
    public function getComments(CommentSearchByCriteriaDto $dto);
    public function createComment($postId, $content);
    public function deleteComment($id);
}
