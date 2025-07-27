<?php

namespace Database\Seeders;

use App\Enums\SystemRoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::query()->firstOrCreate(['name' => SystemRoleEnum::ADMIN->value]);
        Role::query()->firstOrCreate(['name' => SystemRoleEnum::OPERATOR->value]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
