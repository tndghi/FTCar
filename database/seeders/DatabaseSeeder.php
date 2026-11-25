<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([CarSeeder::class]);

        if (env('ADMIN_EMAIL') && env('ADMIN_PASSWORD')) {
            $this->call([AdminUserSeeder::class]);
        }
    }
}
