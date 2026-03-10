<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\{SoftDeletes, Factories\HasFactory, Relations\HasMany};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\UserRole;

final class User extends Authenticatable
{
    use HasApiTokens;
    use SoftDeletes;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isAgent(): bool
    {
        return $this->role === UserRole::Agent;
    }

    public function isGuest(): bool
    {
        return $this->role === UserRole::Guest;
    }

    public function canManageProperty(Property $property): bool
    {
        return $this->isAdmin() || $this->id === $property->user_id;
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }
}
