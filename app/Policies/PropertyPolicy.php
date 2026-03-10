<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

final class PropertyPolicy
{
    public function viewAny(): bool
    {
        return true;
    }

    public function view(User $user, Property $property): bool
    {
        if ($property->is_published) {
            return true;
        }

        return $user->canManageProperty($property);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    public function update(User $user, Property $property): bool
    {
        return $user->canManageProperty($property);
    }

    public function delete(User $user, Property $property): bool
    {
        return $user->canManageProperty($property);
    }

    public function uploadImage(User $user, Property $property): bool
    {
        return $user->canManageProperty($property);
    }

    public function togglePublish(User $user, Property $property): bool
    {
        return $user->canManageProperty($property);
    }
}
