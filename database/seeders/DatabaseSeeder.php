<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create regular users with posts
        User::factory(10)
            ->has(Post::factory()->count(5)->published())
            ->has(Post::factory()->count(3)->draft())
            ->has(Post::factory()->count(2)->archived())
            ->create();
    }
}
