<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Types\RoleType;
use App\Types\PermissionType;
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
        // Permissions
        Permission::create(['name' => PermissionType::CAN_CREATE_USER]);
        Permission::create(['name' => PermissionType::CAN_UPDATE_USER]);
        Permission::create(['name' => PermissionType::CAN_DELETE_USER]);

        Permission::create(['name' => PermissionType::CAN_CREATE_CATEGORY]);
        Permission::create(['name' => PermissionType::CAN_UPDATE_CATEGORY]);
        Permission::create(['name' => PermissionType::CAN_DELETE_CATEGORY]);

        Permission::create(['name' => PermissionType::CAN_CREATE_TAG]);
        Permission::create(['name' => PermissionType::CAN_UPDATE_TAG]);
        Permission::create(['name' => PermissionType::CAN_DELETE_TAG]);

        Permission::create(['name' => PermissionType::CAN_CREATE_PRODUCT]);
        Permission::create(['name' => PermissionType::CAN_UPDATE_PRODUCT]);
        Permission::create(['name' => PermissionType::CAN_DELETE_PRODUCT]);

        // Roles
        $adminRole = Role::create(['name' => RoleType::ADMIN]);
        $managerRole = Role::create(['name' => RoleType::MANAGER]);
        $assistantRole = Role::create(['name' => RoleType::ASSISTANT]);

        // Defining permissions to role
        $adminRole->givePermissionTo([
            PermissionType::CAN_CREATE_USER,
            PermissionType::CAN_UPDATE_USER,
            PermissionType::CAN_DELETE_USER,
            PermissionType::CAN_CREATE_CATEGORY,
            PermissionType::CAN_UPDATE_CATEGORY,
            PermissionType::CAN_DELETE_CATEGORY,
            PermissionType::CAN_CREATE_TAG,
            PermissionType::CAN_UPDATE_TAG,
            PermissionType::CAN_DELETE_TAG,
            PermissionType::CAN_CREATE_PRODUCT,
            PermissionType::CAN_UPDATE_PRODUCT,
            PermissionType::CAN_DELETE_PRODUCT,
          ]);

        $managerRole->givePermissionTo([
            PermissionType::CAN_CREATE_CATEGORY,
            PermissionType::CAN_UPDATE_CATEGORY,
            PermissionType::CAN_DELETE_CATEGORY,
            PermissionType::CAN_CREATE_TAG,
            PermissionType::CAN_UPDATE_TAG,
            PermissionType::CAN_DELETE_TAG,
            PermissionType::CAN_CREATE_PRODUCT,
            PermissionType::CAN_UPDATE_PRODUCT,
            PermissionType::CAN_DELETE_PRODUCT,
        ]);

        $assistantRole->givePermissionTo([
            PermissionType::CAN_CREATE_TAG,
            PermissionType::CAN_CREATE_PRODUCT,
        ]);
    }
}
