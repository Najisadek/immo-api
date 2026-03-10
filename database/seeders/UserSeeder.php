<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->admin()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.site',
            'password' => Hash::make('password'),
        ]);
    }
}
