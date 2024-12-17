<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Http\Requests\DeletePostRequest;


class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $data = $this->postService->getPosts($request);
        return response()->json($data);
    }

    public function destroy(DeletePostRequest $request)
    {
        $id = $request->validated()['id'];
    
        $result = $this->postService->deletePost($id);
    
        if (!$result) {
            return response()->json(['error' => 'Post not found'], 404);
        }
    
        return response()->json(true);
    }
}
