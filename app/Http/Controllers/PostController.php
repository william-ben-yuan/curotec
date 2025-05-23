<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\PostStoreRequest;
use App\Http\Requests\Posts\PostUpdateRequest;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller for managing posts.
 *
 * ## Architectural Decisions & Patterns
 * - Uses Service and Repository patterns for business logic and data access.
 * - All validation is handled via Form Requests.
 * - Returns Inertia responses for SPA navigation.
 *
 * ## API Endpoints
 * - GET    /posts           index()   : List posts (with filters, sorting, pagination)
 * - GET    /posts/create    create()  : Show create form
 * - POST   /posts           store()   : Create a new post
 * - GET    /posts/{post}    show()    : Show a single post
 * - GET    /posts/{post}/edit edit()  : Show edit form
 * - PUT    /posts/{post}    update()  : Update a post
 * - DELETE /posts/{post}    destroy() : Delete a post
 * 
 * ## Response Formats
 * - All endpoints return Inertia responses with props for Vue components.
 * - Example for index():
 *  [
 *    'posts' => [ ...paginated posts... ],
 *    'filters' => [ ...applied filters... ],
 *    'users' => [ ...list of users... ]
 *  ]
 *
 */
class PostController extends Controller
{
    protected $postService;

    /**
     * Inject the PostService.
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of posts.
     * 
     * @param Request $request Query parameters:
     *  - string|null $search     Search term
     *  - string|null $status     Filter by post status (e.g. published, draft)
     *  - int|null    $user_id    Filter by author
     *  - string|null $sort       Column to sort by (default: created_at)
     *  - string|null $direction  Sort direction: asc|desc (default: desc)
     *  - int|null    $per_page   Items per page (default: 10)
     *  - string|null $dateFrom   Filter by created_at >= this date (Y-m-d)
     *  - string|null $dateTo     Filter by created_at <= this date (Y-m-d)
     * 
     * @return Response
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'status', 'user_id', 'sort', 'direction', 'per_page', 'dateFrom', 'dateTo']);
        $posts = $this->postService->getAllPosts($filters);

        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'filters' => $filters,
            'users' => User::select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new post.
     * 
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Posts/Create');
    }

    /**
     * Store a newly created post in storage.
     * 
     * @param \App\Http\Requests\Posts\PostStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(PostStoreRequest $request): RedirectResponse
    {
        $post = $this->postService->createPost($request->validated());

        return redirect()->route('posts.show', $post)->with('message', 'Post criado com sucesso!');
    }

    /**
     * Display the specified post.
     * 
     * @param \App\Models\Post $post
     * @return Response
     */
    public function show(Post $post): Response
    {
        $post = $this->postService->getPost($post->id);

        return Inertia::render('Posts/Show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified post.
     * 
     * @param \App\Models\Post $post
     * @return Response
     */
    public function edit(Post $post): Response
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified post in storage.
     * 
     * @param \App\Http\Requests\Posts\PostUpdateRequest $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(PostUpdateRequest $request, Post $post): RedirectResponse
    {
        $this->postService->updatePost($post, $request->validated());

        return redirect()->route('posts.show', $post)->with('message', 'Post atualizado com sucesso!');
    }

    /**
     * Remove the specified post from storage.
     * 
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post): RedirectResponse
    {
        $this->postService->deletePost($post);

        return redirect()->route('posts.index')->with('message', 'Post excluído com sucesso!');
    }
}
