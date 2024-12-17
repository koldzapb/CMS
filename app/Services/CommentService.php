<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentService
{
    protected $filterable = ['id', 'post_id', 'content', 'abbreviation', 'created_at', 'updated_at'];

    public function getComments(Request $request)
    {
        $query = Comment::query();

        foreach ($this->filterable as $field) {
            if ($request->has($field)) {
                if (in_array($field, ['id', 'post_id'])) {
                    $query->where($field, $request->get($field));
                } else {
                    $query->where($field, 'LIKE', '%'.$request->get($field).'%');
                }
            }
        }

        if ($request->has('sort') && in_array($request->get('sort'), $this->filterable)) {
            $direction = $request->get('direction', 'asc');
            $query->orderBy($request->get('sort'), $direction);
        }

        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);

        $total = $query->count();
        $comments = $query->skip(($page-1)*$limit)->take($limit)->get();

        if ($request->has('with') && $request->get('with') === 'post') {
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
