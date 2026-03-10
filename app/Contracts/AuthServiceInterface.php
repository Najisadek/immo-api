<?php

namespace App\Contracts;

use App\DTOs\{LoginDTO, RegisterDTO};
use Illuminate\Support\Collection;
use App\Models\User;

interface AuthServiceInterface
{
    public function register(RegisterDTO $dto): array;

    public function login(LoginDTO $dto): array;

    public function logout(User $user): void;

    public function getAuthenticatedUser(User $user): User;

    public function getAllUsers(): Collection;

    public function getUserById(int $id): ?User;

    public function deleteUser(int $id, User $admin): bool;
}
