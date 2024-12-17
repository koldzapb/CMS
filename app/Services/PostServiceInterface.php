<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Dto\PostSearchByCriteriaDto;

interface PostServiceInterface
{
    public function getPosts(PostSearchByCriteriaDto $dto): array;
    public function deletePost(int $id): bool;
}
