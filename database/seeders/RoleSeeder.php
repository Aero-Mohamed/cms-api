<?php

namespace Database\Seeders;

use App\Enums\SystemRoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->firstOrCreate(['name' => SystemRoleEnum::ADMIN->value]);
        Role::query()->firstOrCreate(['name' => SystemRoleEnum::OPERATOR->value]);
    }
}
