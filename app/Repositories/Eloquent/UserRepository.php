<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all users with optional filters.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAllWithFilters(array $filters): LengthAwarePaginator
    {
        $query = User::query();

        // Apply search filter
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        // Apply sorting
        if (isset($filters['sort'])) {
            $sort = $filters['sort'];
            $direction = $sort[0] === '-' ? 'desc' : 'asc';
            $field = $sort[0] === '-' ? substr($sort, 1) : $sort;
            $query->orderBy($field, $direction);
        } else {
            $query->orderBy('name');
        }

        // Load post counts
        $query->withCount('posts');

        // Apply pagination
        $perPage = $filters['per_page'] ?? 10;

        return $query->paginate($perPage);
    }

    /**
     * Get a user by its ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Get a user by its ID with posts.
     *
     * @param int $id
     * @return User|null
     */
    public function findByIdWithPosts(int $id): ?User
    {
        return User::with('posts')->find($id);
    }
}
