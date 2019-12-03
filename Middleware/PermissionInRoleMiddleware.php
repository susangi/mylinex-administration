<?php

namespace Administration\Middleware;

use Closure;
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
    public function handle($request, Closure $next, $permission)
    {
        if (app('auth')->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        if (app('auth')->user()->hasAnyAccess($permissions)) {
            return $next($request);
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
