<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
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
     */
    public function show(User $user)
    {
        $user = $this->userService->getUserWithPosts($user->id);

        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }
}
