<?php

use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    Post::query()->truncate();
    User::query()->truncate();
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('filters posts by author', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Post::factory()->create(['user_id' => $user1->id, 'title' => 'Post User 1']);
    Post::factory()->create(['user_id' => $user2->id, 'title' => 'Post User 2']);

    $response = $this->get(route('posts.index', ['user_id' => $user1->id]));
    $response->assertStatus(200);

    $inertia = $response->original->getData();
    $posts = $inertia['page']['props']['posts']['data'] ?? [];

    expect(collect($posts)->pluck('title'))->toContain('Post User 1');
    expect(collect($posts)->pluck('title'))->not->toContain('Post User 2');
});

it('filters posts by status', function () {
    Post::factory()->create(['status' => 'published', 'title' => 'Published Post']);
    Post::factory()->create(['status' => 'draft', 'title' => 'Draft Post']);

    $response = $this->get(route('posts.index', ['status' => 'published']));
    $response->assertStatus(200);

    $inertia = $response->original->getData();
    $posts = $inertia['page']['props']['posts']['data'] ?? [];

    expect(collect($posts)->pluck('title'))->toContain('Published Post');
    expect(collect($posts)->pluck('title'))->not->toContain('Draft Post');
});

it('filters posts by search', function () {
    Post::factory()->create(['title' => 'Unique Search Title']);
    Post::factory()->create(['title' => 'Another Post']);

    $response = $this->get(route('posts.index', ['search' => 'Unique Search']));
    $response->assertStatus(200);

    $inertia = $response->original->getData();
    $posts = $inertia['page']['props']['posts']['data'] ?? [];

    expect(collect($posts)->pluck('title'))->toContain('Unique Search Title');
    expect(collect($posts)->pluck('title'))->not->toContain('Another Post');
});

it('orders posts by title ascending', function () {
    Post::factory()->create(['title' => 'B Title']);
    Post::factory()->create(['title' => 'A Title']);

    $response = $this->get(route('posts.index', ['sort' => 'title', 'direction' => 'asc']));
    $response->assertStatus(200);

    $inertia = $response->original->getData();
    $posts = $inertia['page']['props']['posts']['data'] ?? [];

    expect($posts[0]['title'])->toBe('A Title');
    expect($posts[1]['title'])->toBe('B Title');
});

it('orders posts by title descending', function () {
    Post::factory()->create(['title' => 'B Title']);
    Post::factory()->create(['title' => 'A Title']);

    $response = $this->get(route('posts.index', ['sort' => 'title', 'direction' => 'desc']));
    $response->assertStatus(200);

    $inertia = $response->original->getData();
    $posts = $inertia['page']['props']['posts']['data'] ?? [];

    expect($posts[0]['title'])->toBe('B Title');
    expect($posts[1]['title'])->toBe('A Title');
});

it('filters posts by date range', function () {
    Post::factory()->create([
        'title' => 'Old Post',
        'created_at' => now()->subDays(10)->startOfDay(),
    ]);
    Post::factory()->create([
        'title' => 'Recent Post',
        'created_at' => now()->endOfDay(),
    ]);

    $from = now()->subDays(5)->toDateString();
    $to = now()->addDay()->toDateString();

    $response = $this->get(route('posts.index', [
        'dateFrom' => $from,
        'dateTo' => $to,
    ]));
    $response->assertStatus(200);

    $inertia = $response->original->getData();
    $posts = $inertia['page']['props']['posts']['data'] ?? [];

    expect(collect($posts)->pluck('title'))->toContain('Recent Post');
    expect(collect($posts)->pluck('title'))->not->toContain('Old Post');
});

it('paginates posts', function () {
    Post::factory()->count(15)->create();

    $response = $this->get(route('posts.index', ['per_page' => 10, 'page' => 2]));
    $response->assertStatus(200);

    $inertia = $response->original->getData();
    $posts = $inertia['page']['props']['posts']['data'] ?? [];

    expect(count($posts))->toBe(5); // 15 posts, 10 na primeira página, 5 na segunda
});

it('creates a post', function () {
    $data = [
        'title' => 'New Post',
        'content' => 'Post content',
        'status' => 'draft',
    ];

    $response = $this->post(route('posts.store'), $data);
    $response->assertRedirect();

    $this->assertDatabaseHas('posts', [
        'title' => 'New Post',
        'content' => 'Post content',
        'status' => 'draft',
    ]);
});

it('shows a post', function () {
    $post = Post::factory()->create(['title' => 'Show Me']);

    $response = $this->get(route('posts.show', $post->id));
    $response->assertStatus(200);

    $inertia = $response->original->getData();
    $shownPost = $inertia['page']['props']['post'] ?? null;

    expect($shownPost)->not->toBeNull();
    expect($shownPost['title'])->toBe('Show Me');
});

it('updates a post', function () {
    $post = Post::factory()->create(['title' => 'Old Title']);

    $response = $this->put(route('posts.update', $post->id), [
        'title' => 'Updated Title',
        'content' => $post->content,
        'status' => $post->status,
    ]);
    $response->assertRedirect();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Title',
    ]);
});

it('deletes a post', function () {
    $post = Post::factory()->create();

    $response = $this->delete(route('posts.destroy', $post->id));
    $response->assertRedirect();

    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});
