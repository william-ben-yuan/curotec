<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    public function getAllWithFilters(array $filters): LengthAwarePaginator
    {
        $query = Post::query()
            ->with('user:id,name,email,avatar');

        // Apply search filter
        if (isset($filters['search'])) {
            $query->search($filters['search']);
        }

        // Apply status filter
        if (isset($filters['status'])) {
            $query->status($filters['status']);
        }

        // Apply user_id filter
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // Apply sorting
        if (isset($filters['sort'])) {
            $sort = $filters['sort'];
            $direction = $sort[0] === '-' ? 'desc' : 'asc';
            $field = $sort[0] === '-' ? substr($sort, 1) : $sort;
            $query->orderBy($field, $direction);
        } else {
            $query->latest();
        }

        // Apply pagination
        $perPage = $filters['per_page'] ?? 10;

        return $query->withQueryString()->paginate($perPage);
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function findById(int $id): ?Post
    {
        return Post::with('user')->find($id);
    }

    public function update(Post $post, array $data): bool
    {
        return $post->update($data);
    }

    public function delete(Post $post): bool
    {
        return $post->delete();
    }
}
