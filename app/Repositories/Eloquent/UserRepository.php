<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
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

        return $query->withQueryString()->paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByIdWithPosts(int $id): ?User
    {
        return User::with('posts')->find($id);
    }
}
