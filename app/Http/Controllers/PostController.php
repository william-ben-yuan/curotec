<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\PostStoreRequest;
use App\Http\Requests\Posts\PostUpdateRequest;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of posts.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'user_id', 'sort', 'direction', 'per_page']);
        $posts = $this->postService->getAllPosts($filters);

        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'filters' => $filters,
            'users' => User::select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(PostStoreRequest $request)
    {
        $post = $this->postService->createPost($request->validated());

        return redirect()->route('posts.show', $post)->with('message', 'Post criado com sucesso!');
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        $post = $this->postService->getPost($post->id);

        return Inertia::render('Posts/Show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified post in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $this->postService->updatePost($post, $request->validated());

        return redirect()->route('posts.show', $post)->with('message', 'Post atualizado com sucesso!');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        $this->postService->deletePost($post);

        return redirect()->route('posts.index')->with('message', 'Post excluído com sucesso!');
    }
}
