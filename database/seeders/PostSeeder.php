<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Post::insert([
            ['topic' => 'my first topic', 'created_at' => now(), 'updated_at' => now()],
            ['topic' => 'laravel tips', 'created_at' => now(), 'updated_at' => now()],
            ['topic' => 'php 8 features', 'created_at' => now(), 'updated_at' => now()],
            ['topic' => 'mysql tricks', 'created_at' => now(), 'updated_at' => now()],
            ['topic' => 'web dev news', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
