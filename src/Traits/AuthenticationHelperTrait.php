<?php

namespace Administration\Traits;

use Administration\Models\Role;
use Administration\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

trait AuthenticationHelperTrait
{

    public function userData(): array
    {
        return [
            'name' => 'Super Admin',
            'email' => 'super@mylinex.com',
            'password' => Hash::make('Mylinex@1234'),
            'last_login' => Carbon::now()->toDateTimeString(),
            'password_changed_at' => Carbon::now()->toDateTimeString(),
            'login_attempts' => 0
        ];
    }

    public function createSuperUser()
    {
        Role::create(['name'=>'Super Admin','guard_name'=>'web']);
        $user = User::create($this->userData());
        $user->assignRole('Super Admin');

        return $user;
    }

    public function getLoginCredentials(): array
    {
        return [
            'email' => 'super@mylinex.com',
            'password' => 'Mylinex@1234',
        ];
    }

    public function getDatatableGetRequest()
    {
        $_REQUEST['draw'] = 1;
        $dataArr = [
            'draw' => 1,
            'columns' => [
                [
                    'data' => 0,
                    'name' => '',
                    'searchable' => true,
                    'orderable' => true,
                    'search' => [
                        'value' => '',
                        'regex' => false
                    ]
                ],
                [
                    'data' => 0,
                    'name' => '',
                    'searchable' => true,
                    'orderable' => true,
                    'search' => [
                        'value' => '',
                        'regex' => false
                    ]
                ]
            ],
            'order' => [
                [
                    'column' => 0,
                    'dir' => 'asc'
                ]
            ],
            'start' => 0,
            'length' => 10,
            'search' => [
                'value' => '',
                'regex' => false
            ]
        ];
        return http_build_query($dataArr);
    }
}
