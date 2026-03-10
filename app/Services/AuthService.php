<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\{Collection, Facades\Hash};
use Illuminate\Auth\AuthenticationException;
use App\Contracts\AuthServiceInterface;
use App\DTOs\{LoginDTO, RegisterDTO};
use App\Models\User;

class AuthService implements AuthServiceInterface
{
    public function register(RegisterDTO $dto): array
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(LoginDTO $dto): array
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new AuthenticationException('Les identifiants sont incorrects.');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function getAuthenticatedUser(User $user): User
    {
        return $user;
    }

    public function getAllUsers(): Collection
    {
        if (! auth()->user()->canManageUsers()) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Vous n\'avez pas l\'autorisation de gérer les utilisateurs.');
        }

        return User::all();
    }

    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }

    public function deleteUser(int $id, User $admin): bool
    {
        if (! $admin->canManageUsers()) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Vous n\'avez pas l\'autorisation de supprimer des utilisateurs.');
        }

        $user = User::findOrFail($id);

        if ($user->id === $admin->id) {
            throw new \InvalidArgumentException('Vous ne pouvez pas supprimer votre propre compte.');
        }

        return $user->delete();
    }
}
