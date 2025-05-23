<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function getAllWithFilters(array $filters): LengthAwarePaginator;
    public function findById(int $id): ?User;
    public function findByIdWithPosts(int $id): ?User;
}
