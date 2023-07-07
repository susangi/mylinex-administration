<?php

namespace Administration\Repositories;

use Administration\Models\Menu;
use Administration\Models\Role;
use Administration\Models\User;
use Administration\Services\PasswordPolicyService;
use App\Exceptions\ExistUserException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function loadPageData()
    {
        $roles = Role::all()->pluck('name', 'name');
        $menu = Menu::query();
        $type = env('APP_TYPE');
        if ($type !== 'ALL') {
            $menu->where('type', '=', $type);
        }
        $landing_page = $menu->where('url', '<>', null)->get()->pluck('title', 'id');

        return ['roles' => $roles, 'landing_page' => $landing_page];
    }

    public function store(array $request)
    {
        $is_api = $request['is_api'] == 'on' ? 1 : 0;
        if (User::whereName($request['name'])->withTrashed()->count() > 0) {
            throw new ExistUserException('User name already exists.');
        }

        $exist_user = User::whereEmail($request['email'])->withTrashed()->first();
        if (!empty($exist_user)) {
            if (User::whereName($request['name'])->count() > 0) {
                throw new ExistUserException('User name already exists.');
            }
            if ($exist_user->trashed()) {
                $exist_user->name = $request['name'];
                $exist_user->updated_by = Auth::user()->id;
                $exist_user->landing_page = $request['landing_page'];
                $exist_user->is_api = $is_api;
                $exist_user->save();
                $exist_user->syncRoles($request['role']);
                $exist_user->restore();

                $pc = new PasswordPolicyService($exist_user);
                $pc->passwordChangeProcess($request['password']);

                return $exist_user;
            }
            throw new ExistUserException('User already exits.');
        } else {
            $user = User::create(
                [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'landing_page' => $request['landing_page'],
                    'password' => Hash::make($request['password']),
                    'created_by' => Auth::user()->id,
                    'is_api' => $is_api
                ]
            );

            $pc = new PasswordPolicyService($user);
            $pc->passwordChangeProcess($request['password']);

            $user->syncRoles($request->role);
            return $exist_user;
        }
    }
}
