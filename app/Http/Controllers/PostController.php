<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostServiceInterface;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $filterable = ['id','topic','created_at','updated_at'];
        
        $filters = [];
        foreach ($filterable as $field) {
            if ($request->has($field)) {
                $filters[$field] = $request->get($field);
            }
        }

        $sort = $request->get('sort');
        $direction = $request->get('direction', 'asc');

        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $with = $request->get('with');

        $commentFilter = $request->get('comment');

        $data = $this->postService->getPosts(
            $filters, 
            $sort, 
            $direction, 
            $limit, 
            $page, 
            $with, 
            $commentFilter
        );

        return response()->json($data);
    }

    public function destroy(DeletePostRequest $request)
    {
        $id = $request->route('id');

        $result = $this->commentService->deleteComment($id);

        if (!$result) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        return response()->json(true);
    }
}
