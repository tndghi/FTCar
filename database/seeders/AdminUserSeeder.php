<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use InvalidArgumentException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Set a valid ADMIN_EMAIL in the environment before seeding.');
        }
        if (! $password || strlen($password) < 12) {
            throw new InvalidArgumentException('ADMIN_PASSWORD must contain at least 12 characters.');
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Showroom Admin'),
                'password' => $password,
                'role' => 'admin',
            ],
        );
    }
}