<?php

namespace Database\Seeders;

use App\Enums\SystemRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole(SystemRoleEnum::ADMIN->value);

        // Operator
        $admin = User::factory()->create([
            'name' => 'Operator',
            'email' => 'operator@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole(SystemRoleEnum::OPERATOR->value);
    }
}
