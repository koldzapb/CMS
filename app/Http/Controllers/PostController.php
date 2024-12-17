<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    $query = \App\Models\Post::query();

    // Ako postoji ?comment=keyword
    if ($request->has('comment')) {
        $commentFilter = $request->get('comment');
        $query->whereHas('comments', function($q) use ($commentFilter) {
            $q->where('content', 'LIKE', "%{$commentFilter}%");
        });
    }

    // Ostalo filtriranje po poljima Post modela (topic, id, timestamps)
    $filterable = ['id','topic','created_at','updated_at'];
    foreach ($filterable as $field) {
        if ($request->has($field)) {
            if ($field === 'id') {
                $query->where($field, $request->get($field));
            } else {
                $query->where($field, 'LIKE', '%'.$request->get($field).'%');
            }
        }
    }

    // Sortiranje
    if ($request->has('sort') && in_array($request->sort, $filterable)) {
        $direction = $request->get('direction', 'asc');
        $query->orderBy($request->sort, $direction);
    }

    // Paginacija
    $limit = $request->get('limit', 10);
    $page = $request->get('page', 1);

    $total = $query->count();
    $posts = $query->skip(($page-1)*$limit)->take($limit)->get();

    // Include relations ?with=comments
    if ($request->has('with') && $request->with === 'comments') {
        $posts->load('comments');
    }

    return response()->json([
        'result' => $posts,
        'count' => $total
    ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = \App\Models\Post::find($id);
    if (!$post) {
        return response()->json(['error' => 'Post not found'], 404);
    }
  
    $post->comments()->delete();
    $post->delete();

    return response()->json(true);
    }
}
