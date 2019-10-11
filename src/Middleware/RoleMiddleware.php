<?php

namespace AppsLab\Acl\Middleware;

use AppsLab\Acl\Exceptions\UnauthorizedException;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if (Auth::guest()){
            throw UnauthorizedException::unauthorizedTo("You are not logged in");
        }

        $roles = is_array($roles) ? $roles : explode("|", $roles);

        foreach ($roles as $role){
            if ($request->user()->hasRole($role)){
                return $next($request);
            }
        }

        throw UnauthorizedException::unauthorizedTo("User role not found");
    }
}