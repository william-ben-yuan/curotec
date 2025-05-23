<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAllPosts(array $filters): LengthAwarePaginator
    {
        return $this->postRepository->getAllWithFilters($filters);
    }

    public function createPost(array $data): Post
    {
        // Process data before creating the post
        $postData = [
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? $data['published_at'] ?? now() : null,
        ];

        return $this->postRepository->create($postData);
    }

    public function getPost(int $id): ?Post
    {
        return $this->postRepository->findById($id);
    }

    public function updatePost(Post $post, array $data): bool
    {
        // Process data before updating the post
        $postData = [
            'title' => $data['title'] ?? $post->title,
            'content' => $data['content'] ?? $post->content,
            'status' => $data['status'] ?? $post->status,
            'published_at' => $data['status'] === 'published' ? $data['published_at'] ?? now() : null,
        ];

        return $this->postRepository->update($post, $postData);
    }

    public function deletePost(Post $post): bool
    {
        return $this->postRepository->delete($post);
    }
}
