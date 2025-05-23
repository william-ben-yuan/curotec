<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller for managing users.
 *
 * ## Architectural Decisions & Patterns
 * - Uses Service and Repository patterns for business logic and data access.
 * - Returns Inertia responses for SPA navigation.
 *
 * ## API Endpoints
 * - GET    /users           index()   : List users (with filters, sorting, pagination)
 * - GET    /users/{user}    show()    : Show a single user with posts
 *
 * ## Response Formats
 * - All endpoints return Inertia responses with props for Vue components.
 * - Example for index():
 *   [
 *     'users' => [ ...paginated users... ],
 *     'filters' => [ ...applied filters... ]
 *   ]
 */
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users.
     * 
     * @param Request $request Query parameters:
     * - search: string (full-text search)
     * - sort: string (field)
     * - per_page: int
     * 
     * @return Response
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'sort', 'per_page']);
        $users = $this->userService->getAllUsers($filters);

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $filters,
        ]);
    }

    /**
     * Display the specified user.
     * 
     * @param User $user The user to display.
     * @return Response
     */
    public function show(User $user): Response
    {
        $user = $this->userService->getUserWithPosts($user->id);

        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }
}
