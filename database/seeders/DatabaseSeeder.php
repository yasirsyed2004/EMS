<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Department\Database\Seeders\DepartmentDatabaseSeeder;
use Modules\Employee\Database\Seeders\EmployeeDatabaseSeeder;
use Modules\RolePermission\Database\Seeders\RolePermissionDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DepartmentDatabaseSeeder::class,
            RolePermissionDatabaseSeeder::class,
            UserDatabaseSeeder::class,
            EmployeeDatabaseSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => 'Admin@123'
        // ]);
        // Role::create([
        //     'name' => 'Admin',
        //     'display_name' => 'all permissions',
        //     'guard_name' => 'api'
        // ]);
        // Role::create([
        //     'name' => 'Super Admin',
        //     'display_name' => 'all permissions',
        //     'guard_name' => 'api'
        // ]);
    }
}
