<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function getAllWithFilters(array $filters): LengthAwarePaginator;
    public function create(array $data): Post;
    public function findById(int $id): ?Post;
    public function update(Post $post, array $data): bool;
    public function delete(Post $post): bool;
}
