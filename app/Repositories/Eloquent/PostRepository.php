<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    /**
     * Get all posts with optional filters.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAllWithFilters(array $filters): LengthAwarePaginator
    {
        $query = Post::query()
            ->with('user:id,name,email');

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

        // Apply date range filters
        if (!empty($filters['dateFrom'])) {
            $query->where('created_at', '>=', Carbon::parse($filters['dateFrom'])->startOfDay());
        }
        if (!empty($filters['dateTo'])) {
            $query->where('created_at', '<=', Carbon::parse($filters['dateTo'])->endOfDay());
        }

        // Apply sorting
        $sort = $filters['sort'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        $query->orderBy($sort, $direction);

        // Apply pagination
        $perPage = $filters['per_page'] ?? 10;

        return $query->paginate($perPage);
    }

    /**
     * Get a post by its ID.
     *
     * @param int $id
     * @return Post|null
     */
    public function create(array $data): Post
    {
        return Post::create($data);
    }

    /**
     * Get a post by its ID.
     *
     * @param int $id
     * @return Post|null
     */
    public function findById(int $id): ?Post
    {
        return Post::with('user')->find($id);
    }

    /**
     * Update a post.
     *
     * @param Post $post
     * @param array $data
     * @return bool
     */
    public function update(Post $post, array $data): bool
    {
        return $post->update($data);
    }

    /**
     * Delete a post.
     *
     * @param Post $post
     * @return bool
     */
    public function delete(Post $post): bool
    {
        return $post->delete();
    }
}
