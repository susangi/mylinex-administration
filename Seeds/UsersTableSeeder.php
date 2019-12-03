<?php
namespace Administration\Seeds;

use Administration\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users =[
            ['id'=>1,'name'=>'Super Admin','email'=>'super@mylinex.com','password'=>Hash::make('super@mylinex.com')],
            ['id'=>2,'name'=>'System Admin','email'=>'admin@mylinex.com','password'=>Hash::make('admin@mylinex.com')],
        ];

        User::insert($users);

        User::find(1)->assignRole('Super Admin');
        User::find(2)->assignRole('Admin');
    }
}
