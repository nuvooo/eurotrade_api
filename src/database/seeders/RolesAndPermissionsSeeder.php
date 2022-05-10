<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'admin_access']);
        $permission->assignRole($role);

        $role2 = Role::create(['name' => 'user']);
        $permission2 = Permission::create(['name' => 'user_access']);
        $permission2->assignRole($role2);

        $role3 = Role::create(['name' => 'superadmin']);
        $permission3 = Permission::create(['name' => 'superadmin_access']);
        $permission3->assignRole($role3);
    }
}
