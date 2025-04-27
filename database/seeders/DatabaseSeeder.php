<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'admin',
        ]);
        Role::create([
            'name' => 'customer',
        ]);

        User::create([
            'email' => 'admin@gmail.com',
            'role_id' => 1,
            'password' => bcrypt('admin'),
        ]);
    }
}
