<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommentServiceInterface;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\DeleteCommentRequest;
use App\Dto\CommentSearchByCriteriaDto;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentServiceInterface $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request): JsonResponse
    {
        $filterable = ['id','post_id','content','abbreviation','created_at','updated_at'];
        $filters = [];
        foreach ($filterable as $field) {
            if ($request->has($field)) {
                $filters[$field] = $request->get($field);
            }
        }

        $dto = new CommentSearchByCriteriaDto(
            $filters,
            $request->get('sort'),
            $request->get('direction', 'asc'),
            $request->get('limit', 10),
            $request->get('page', 1),
            $request->get('with')
        );

        $data = $this->commentService->getComments($dto);
        return response()->json($data);
    }

    public function store(CreateCommentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $comment = $this->commentService->createComment($validated['post_id'], $validated['content']);
        return response()->json($comment, 201);
    }

    public function destroy(DeleteCommentRequest $request): JsonResponse
    {
        $id = $request->route('id');

        $result = $this->commentService->deleteComment($id);

        if (!$result) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        return response()->json(true);
    }
}
