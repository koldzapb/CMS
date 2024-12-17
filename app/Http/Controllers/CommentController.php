<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\DeleteCommentRequest;


class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $data = $this->commentService->getComments($request);
        return response()->json($data);
    }
    

     public function store(CreateCommentRequest $request)
     {
         $validated = $request->validated();
         $comment = $this->commentService->createComment($validated['post_id'], $validated['content']);
         return response()->json($comment, 201);
     }

    public function destroy(DeleteCommentRequest $request)
    {
        $id = $request->route('id');

        $result = $this->commentService->deleteComment($id);

        if (!$result) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        return response()->json(true);
    }
}
