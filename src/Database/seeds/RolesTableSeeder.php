<?php
namespace Database\Seeders;

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
        Role::firstOrCreate(['id'=>1],['name'=>'Super Admin','guard_name'=>'web']);
        Role::firstOrCreate(['id'=>2],['name'=>'Admin','guard_name'=>'web']);

        $permissions =['users create','users edit','users delete','reset password'];

        Role::find(2)->syncPermissions($permissions);
    }
}
