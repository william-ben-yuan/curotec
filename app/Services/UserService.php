<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(array $filters): LengthAwarePaginator
    {
        return $this->userRepository->getAllWithFilters($filters);
    }

    public function getUserWithPosts(int $id): ?User
    {
        return $this->userRepository->findByIdWithPosts($id);
    }
}
