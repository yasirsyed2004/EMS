<?php

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Department\Entities\Department;
use Modules\Employee\Entities\Employee;
use Modules\User\Entities\User;
class EmployeeDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $department1 = Department::firstWhere('name', 'Human Resources');
        $department2 = Department::firstWhere('name', 'IT');

        $user1 = User::create([
            'name' => 'Employee',
            'email' => 'employee@example.com',
            'phone' => '03943849383',
            'password' => 'password',
        ]);
        $user1->assignRole('employee');

        $user2 = User::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'phone' => '03943829333',
            'password' => 'password',
        ]);
        $user2->assignRole('manager');

        Employee::create([
            'user_id' => $user1->id,
            'department_id' => $department1->id,
            'joining_date' => now()->subYears(2),
        ]);

        Employee::create([
            'user_id' => $user2->id,
            'department_id' => $department2->id,
            'joining_date' => now()->subYear(),
        ]);

        // $this->call("OthersTableSeeder");
    }
}
