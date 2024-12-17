<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Helpers\CommentContentHelper;
use App\Models\Comment;
use App\Models\Post;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::all('id')->pluck('id')->toArray();

        $randomWords = "Cool,Strange,Funny,Laughing,Nice,Awesome,Great,Horrible,Beautiful,PHP,Vegeta,Italy,Joost";
        $words = explode(',', $randomWords);

        $combinations = CommentContentHelper::generateCombinations($words);

        $chunks = [];
        $now = now();

        foreach ($combinations as $content) {
            $chunks[] = [
                'post_id' => $posts[array_rand($posts)],
                'content' => $content,
                'abbreviation' => CommentContentHelper::makeAbbreviation($content),
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        $chunked = array_chunk($chunks, 1000);
        foreach ($chunked as $c) {
            Comment::insert($c);
        }
    }
}
