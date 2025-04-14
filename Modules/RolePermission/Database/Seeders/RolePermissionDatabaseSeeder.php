<?php

namespace Modules\RolePermission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolePermissionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Role::create([
            'name' => 'admin',
            'display_name' => 'all permissions',
            'guard_name' => 'api'
        ]);
        Role::create([
            'name' => 'employee',
            'display_name' => 'employee permissions',
            'guard_name' => 'api'
        ]);
        Role::create([
            'name' => 'manager',
            'display_name' => 'manager permissions',
            'guard_name' => 'api'
        ]);
        $permissions = [
            'create_employee', 'update_employee', 'delete_employee', 'show_employee',
            'create_department', 'update_department', 'delete_department', 'show_department',
            'assign_roles', 'show_permissions', 'add_permissions', 'add_roles'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $employee = Role::firstOrCreate(['name' => 'employee']);

        $admin->givePermissionTo(Permission::all());
        $manager->givePermissionTo(['update_employee','show_employee','show_department']);
        // $employee->givePermissionTo(['show_employee']);
        // $this->call("OthersTableSeeder");
    }
}
