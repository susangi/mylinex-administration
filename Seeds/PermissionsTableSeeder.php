<?php

namespace Administration\Seeds;

use Administration\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['id' => 1, 'name' => 'permission create', 'guard_name' => 'web'],
            ['id' => 2, 'name' => 'permissions edit', 'guard_name' => 'web'],
            ['id' => 3, 'name' => 'permissions delete', 'guard_name' => 'web'],

            ['id' => 4, 'name' => 'roles create', 'guard_name' => 'web'],
            ['id' => 5, 'name' => 'roles edit', 'guard_name' => 'web'],
            ['id' => 6, 'name' => 'roles delete', 'guard_name' => 'web'],

            ['id' => 7, 'name' => 'users create', 'guard_name' => 'web'],
            ['id' => 8, 'name' => 'users edit', 'guard_name' => 'web'],
            ['id' => 9, 'name' => 'users delete', 'guard_name' => 'web'],

            ['id' => 10, 'name' => 'reset password', 'guard_name' => 'web'],


            ['id' => 11, 'name' => 'activity log view', 'guard_name' => 'web'],
        ];

        Permission::insert($permissions);
    }
}
