<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $query = \App\Models\Comment::query();
    
        $filterable = ['id', 'post_id', 'content', 'abbreviation', 'created_at', 'updated_at'];
    
        // Filtriranje po kolone
        foreach ($filterable as $field) {
            if ($request->has($field)) {
                // id i post_id filtriraj tačno, ostale LIKE
                if (in_array($field, ['id', 'post_id'])) {
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
        $comments = $query->skip(($page-1)*$limit)->take($limit)->get();
    
        // Učitavanje relacija, npr. ?with=post da učita pripadajući post
        if ($request->has('with') && $request->with === 'post') {
            $comments->load('post');
        }
    
        return response()->json([
            'result' => $comments,
            'count' => $total
        ]);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request) {
            $validated = $request->validate([
                'post_id' => 'required|exists:posts,id',
                'content' => 'required|string',
            ]);
        
            // Generiši abbreviation na isti način kao kod seeder-a
            $content = strtolower($validated['content']);
            $words = explode(' ', $content);
            sort($words);
            $abbr = '';
            foreach ($words as $w) {
                $abbr .= $w[0];
            }
        
            $comment = \App\Models\Comment::create([
                'post_id' => $validated['post_id'],
                'content' => implode(' ', $words),
                'abbreviation' => $abbr
            ]);
        
            return response()->json($comment, 201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = \App\Models\Comment::find($id);
        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
    
        $comment->delete();
        return response()->json(true);
    }
}
