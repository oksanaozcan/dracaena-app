<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Types\RoleType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Role::create(['name' => RoleType::ADMIN]);
      Role::create(['name' => RoleType::MANAGER]);
      Role::create(['name' => RoleType::ASSISTANT]);
    }
}
