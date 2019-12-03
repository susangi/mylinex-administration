<?php
namespace Administration\Seeds;

use Administration\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles =[
            ['id'=>1,'name'=>'Super Admin','guard_name'=>'web'],
            ['id'=>2,'name'=>'Admin','guard_name'=>'web'],
        ];

        Role::insert($roles);

        $permissions =['users create','users edit','users delete','reset password'];

        Role::find(2)->syncPermissions($permissions);
    }
}
