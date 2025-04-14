<?php

namespace Modules\Department\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Department\Entities\Department;


class DepartmentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Department::create(['name' => 'Human Resources']);
        Department::create(['name' => 'IT']);
        // $this->call("OthersTableSeeder");
    }
}
