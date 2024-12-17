<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostServiceInterface;
use App\Dto\PostSearchByCriteriaDto;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\DeletePostRequest;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request): JsonResponse
    {
        $filterable = ['id','topic','created_at','updated_at'];
        $filters = [];
        foreach ($filterable as $field) {
            if ($request->has($field)) {
                $filters[$field] = $request->get($field);
            }
        }

        $dto = new PostSearchByCriteriaDto(
            $filters,
            $request->get('sort'),
            $request->get('direction', 'asc'),
            $request->get('limit', 10),
            $request->get('page', 1),
            $request->get('with'),
            $request->get('comment')
        );

        $data = $this->postService->getPosts($dto);
        return response()->json($data);
    }

    public function destroy(DeletePostRequest $request): JsonResponse
    {
        $id = $request->route('id');

        $result = $this->postService->deletePost($id);

        if (!$result) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        return response()->json(true);
    }
}
