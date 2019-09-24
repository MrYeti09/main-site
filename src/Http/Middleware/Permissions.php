<?php

namespace Viaativa\Viaroot\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $user = Auth::user();
        if($user != null) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }
        return abort(404);
    }
}