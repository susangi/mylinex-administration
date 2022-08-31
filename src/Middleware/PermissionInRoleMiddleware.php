<?php

namespace Administration\Middleware;

use Administration\Models\Menu;
use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionInRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $permission
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }


        $menu = Menu::whereUrl($request->route()->getName())->first();
        if (!empty($menu)) {
            $permissions = $menu->permissions()->get()->pluck('name');

            $roleOrPermission = $permissions->push('Super Admin');
            $roleOrPermission = $permissions->push('Admin');

            $roleOrPermission = implode('|', $roleOrPermission->toArray());

            $rolesOrPermissions = is_array($roleOrPermission)
                ? $roleOrPermission
                : explode('|', $roleOrPermission);

            if (!Auth::user()->hasAnyRole($rolesOrPermissions) && !Auth::user()->hasAnyPermission($rolesOrPermissions)) {
                throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
            }

            return $next($request);
        } else {
            return $next($request);
        }
    }
}
