<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Types\RoleType;
use App\Types\PermissionType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Reset cached roles and permissions
         app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        Permission::firstOrCreate(['name' => PermissionType::CAN_CREATE_USER]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_UPDATE_USER]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_DELETE_USER]);

        Permission::firstOrCreate(['name' => PermissionType::CAN_CREATE_CATEGORY]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_UPDATE_CATEGORY]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_DELETE_CATEGORY]);

        Permission::firstOrCreate(['name' => PermissionType::CAN_CREATE_CATEGORY_FILTER]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_UPDATE_CATEGORY_FILTER]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_DELETE_CATEGORY_FILTER]);

        Permission::firstOrCreate(['name' => PermissionType::CAN_CREATE_TAG]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_UPDATE_TAG]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_DELETE_TAG]);

        Permission::firstOrCreate(['name' => PermissionType::CAN_CREATE_PRODUCT]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_UPDATE_PRODUCT]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_DELETE_PRODUCT]);

        Permission::firstOrCreate(['name' => PermissionType::CAN_CREATE_BILLBOARD]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_UPDATE_BILLBOARD]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_DELETE_BILLBOARD]);

        Permission::firstOrCreate(['name' => PermissionType::CAN_DESTROY_ORDER]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_RESTORE_ORDER]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_FORCE_DELETE_ORDER]);

        Permission::firstOrCreate(['name' => PermissionType::CAN_CREATE_PRODUCT_GROUP]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_UPDATE_PRODUCT_GROUP]);
        Permission::firstOrCreate(['name' => PermissionType::CAN_DELETE_PRODUCT_GROUP]);

        // Roles
        $adminRole = Role::firstOrCreate(['name' => RoleType::ADMIN]);
        $managerRole = Role::firstOrCreate(['name' => RoleType::MANAGER]);
        $assistantRole = Role::firstOrCreate(['name' => RoleType::ASSISTANT]);

        // gets all permissions via Gate::before rule; see AuthServiceProvider for $adminRole
        // Defining permissions to role
        $managerRole->givePermissionTo([
            PermissionType::CAN_CREATE_CATEGORY,
            PermissionType::CAN_UPDATE_CATEGORY,
            PermissionType::CAN_DELETE_CATEGORY,

            PermissionType::CAN_CREATE_CATEGORY_FILTER,
            PermissionType::CAN_UPDATE_CATEGORY_FILTER,
            PermissionType::CAN_DELETE_CATEGORY_FILTER,

            PermissionType::CAN_CREATE_TAG,
            PermissionType::CAN_UPDATE_TAG,
            PermissionType::CAN_DELETE_TAG,

            PermissionType::CAN_CREATE_PRODUCT,
            PermissionType::CAN_UPDATE_PRODUCT,
            PermissionType::CAN_DELETE_PRODUCT,

            PermissionType::CAN_CREATE_BILLBOARD,
            PermissionType::CAN_UPDATE_BILLBOARD,
            PermissionType::CAN_DELETE_BILLBOARD,

            PermissionType::CAN_CREATE_PRODUCT_GROUP,
            PermissionType::CAN_UPDATE_PRODUCT_GROUP,
            PermissionType::CAN_DELETE_PRODUCT_GROUP,
        ]);

        $assistantRole->givePermissionTo([
            PermissionType::CAN_CREATE_TAG,
            PermissionType::CAN_CREATE_PRODUCT,
            PermissionType::CAN_CREATE_BILLBOARD,
            PermissionType::CAN_CREATE_PRODUCT_GROUP,
        ]);
    }
}
