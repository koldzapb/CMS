<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\Request;

class PostService
{
    protected $filterable = ['id','topic','created_at','updated_at'];

    public function getPosts(Request $request)
    {
        $query = Post::query();

        if ($request->has('comment')) {
            $commentFilter = $request->get('comment');
            $query->whereHas('comments', function($q) use ($commentFilter) {
                $q->where('content', 'LIKE', "%{$commentFilter}%");
            });
        }

        foreach ($this->filterable as $field) {
            if ($request->has($field)) {
                if ($field === 'id') {
                    $query->where($field, $request->get($field));
                } else {
                    $query->where($field, 'LIKE', '%'.$request->get($field).'%');
                }
            }
        }

        if ($request->has('sort') && in_array($request->sort, $this->filterable)) {
            $direction = $request->get('direction', 'asc');
            $query->orderBy($request->sort, $direction);
        }

        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $total = $query->count();
        $posts = $query->skip(($page-1)*$limit)->take($limit)->get();

        if ($request->has('with') && $request->with === 'comments') {
            $posts->load('comments');
        }

        return [
            'result' => $posts,
            'count' => $total
        ];
    }

    public function deletePost($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return false;
        }

        $post->comments()->delete();
        $post->delete();

        return true;
    }
}
