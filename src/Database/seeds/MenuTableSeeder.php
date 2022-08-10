<?php

namespace Database\Seeders;

use Administration\Models\Menu;
use Administration\Models\Permission;
use Administration\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $root = Menu::firstOrCreate(['title' => 'Administration']);

        $child = $root->children()->firstOrCreate(['title' => 'Menu', 'url' => 'menu.index']);
        $child->permissions()->saveMany([
             Permission::firstOrCreate(['name' => 'menu index' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'menu create' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'menu show' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'menu edit' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'menu delete' ,'guard_name' => 'web']),
        ]);

        $child1 = $root->children()->firstOrCreate(['title' => 'Roles', 'url' => 'roles.index']);
        $child1->permissions()->saveMany([
             Permission::firstOrCreate(['name' => 'roles index' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'roles create' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'roles show' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'roles edit' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'roles delete' ,'guard_name' => 'web']),
        ]);

        $child2 = $root->children()->firstOrCreate(['title' => 'Users', 'url' => 'users.index']);
        $child2->permissions()->saveMany([
             Permission::firstOrCreate(['name' => 'users index' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'users create' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'users show' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'users edit' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'users delete' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'reset password' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'reset attempts' ,'guard_name' => 'web']),
        ]);

        $child3 = $root->children()->firstOrCreate(['title' => 'Permissions', 'url' => 'permissions.index']);
        $child3->permissions()->saveMany([
             Permission::firstOrCreate(['name' => 'permissions index' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'permissions create' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'permissions show' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'permissions edit' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'permissions delete' ,'guard_name' => 'web']),
        ]);

        $child4 = $root->children()->firstOrCreate(['title' => 'Activity Logs', 'url' => 'activity-logs.index']);
        $child4->permissions()->saveMany([
             Permission::firstOrCreate(['name' => 'activity-logs index' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'activity-logs create' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'activity-logs show' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'activity-logs edit' ,'guard_name' => 'web']),
             Permission::firstOrCreate(['name' => 'activity-logs delete','guard_name' => 'web']),
        ]);


        $root1 = Menu::firstOrCreate(['title' => 'Documentation']);

        $child = $root1->children()->firstOrCreate(['title' => 'Articles', 'url' => 'doc.index']);
        $child->permissions()->saveMany([
            Permission::firstOrCreate(['name' => 'doc index' ,'guard_name' => 'web']),
            Permission::firstOrCreate(['name' => 'doc create' ,'guard_name' => 'web']),
            Permission::firstOrCreate(['name' => 'doc show' ,'guard_name' => 'web']),
            Permission::firstOrCreate(['name' => 'doc edit' ,'guard_name' => 'web']),
            Permission::firstOrCreate(['name' => 'doc delete','guard_name' => 'web']),
        ]);

        $child = $root1->children()->firstOrCreate(['title' => 'Change Log', 'url' => 'changelog.index']);
        $child->permissions()->saveMany([
            Permission::firstOrCreate(['name' => 'changelog index' ,'guard_name' => 'web']),
            Permission::firstOrCreate(['name' => 'changelog create' ,'guard_name' => 'web']),
            Permission::firstOrCreate(['name' => 'changelog show' ,'guard_name' => 'web']),
            Permission::firstOrCreate(['name' => 'changelog edit' ,'guard_name' => 'web']),
            Permission::firstOrCreate(['name' => 'changelog delete','guard_name' => 'web']),
        ]);
    }
}
