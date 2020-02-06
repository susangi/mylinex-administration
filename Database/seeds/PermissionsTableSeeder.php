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
            ['id' => 1, 'name' => 'permission index', 'guard_name' => 'web'],
            ['id' => 2, 'name' => 'permission create', 'guard_name' => 'web'],
            ['id' => 3, 'name' => 'permissions edit', 'guard_name' => 'web'],
            ['id' => 4, 'name' => 'permissions delete', 'guard_name' => 'web'],

            ['id' => 5, 'name' => 'roles index', 'guard_name' => 'web'],
            ['id' => 6, 'name' => 'roles create', 'guard_name' => 'web'],
            ['id' => 7, 'name' => 'roles edit', 'guard_name' => 'web'],
            ['id' => 8, 'name' => 'roles delete', 'guard_name' => 'web'],

            ['id' => 9, 'name' => 'users index', 'guard_name' => 'web'],
            ['id' => 10, 'name' => 'users create', 'guard_name' => 'web'],
            ['id' => 11, 'name' => 'users edit', 'guard_name' => 'web'],
            ['id' => 12, 'name' => 'users delete', 'guard_name' => 'web'],

            ['id' => 13, 'name' => 'reset password', 'guard_name' => 'web'],

            ['id' => 14, 'name' => 'activity log view', 'guard_name' => 'web'],

            ['id' => 15, 'name' => 'docs index', 'guard_name' => 'web'],
            ['id' => 16, 'name' => 'docs create', 'guard_name' => 'web'],
            ['id' => 17, 'name' => 'docs edit', 'guard_name' => 'web'],
            ['id' => 18, 'name' => 'docs delete', 'guard_name' => 'web'],

            ['id' => 19, 'name' => 'changelog index', 'guard_name' => 'web'],
            ['id' => 20, 'name' => 'changelog create', 'guard_name' => 'web'],
            ['id' => 21, 'name' => 'changelog edit', 'guard_name' => 'web'],
            ['id' => 22, 'name' => 'changelog delete', 'guard_name' => 'web'],
        ];

        Permission::insert($permissions);
    }
}
