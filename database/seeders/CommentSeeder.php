<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //public function run()
    $posts = \App\Models\Post::all('id')->pluck('id')->toArray();
    $randomWords = "Cool,Strange,Funny,Laughing,Nice,Awesome,Great,Horrible,Beautiful,PHP,Vegeta,Italy,Joost";
    $words = explode(',', $randomWords);

    $combinations = [];
    $length = count($words);
    for ($i = 1; $i < (1 << $length); $i++) {
        $subset = [];
        for ($j = 0; $j < $length; $j++) {
            if ($i & (1 << $j)) {
                $subset[] = strtolower($words[$j]);
            }
        }
        sort($subset);
        $combinations[] = implode(' ', $subset);
    }

    function makeAbbreviation($contentStr) {
        $wordsArray = explode(' ', $contentStr);
        $abbr = '';
        foreach ($wordsArray as $w) {
            $abbr .= $w[0];
        }
        return $abbr;
    }

    $chunks = [];
    $now = now();
    foreach ($combinations as $content) {
        $chunks[] = [
            'post_id' => $posts[array_rand($posts)], 
            'content' => $content,
            'abbreviation' => makeAbbreviation($content),
            'created_at' => $now,
            'updated_at' => $now
        ];
    }

    // Ubaci u bazu (bulk insert)
    // Možeš ubacivati u segmentima da ne opteretiš MySQL.
    $chunked = array_chunk($chunks, 1000);
    foreach ($chunked as $c) {
        \App\Models\Comment::insert($c);
    }

    }
}
