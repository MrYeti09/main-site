<?php

namespace Viaativa\Viaroot\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\MenuItem;

class AdminPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if(!is_null($user)) {
            $user_role_id = $user->role_id;
            $route_name = $request->route()->getName();
            $route_uri = $request->route()->uri();
            $menuItem = MenuItem::where('route', $route_name)
                ->orWhere('url', "{$route_uri}")
                ->orWhere('url', "/{$route_uri}")
                ->first();

            if (is_null($menuItem) ||
                is_null($menuItem->permissions) ||
                (
                    $menuItem
                    && (!count($menuItem->permissions)
                        || in_array($user_role_id, $menuItem->permissions)
                    )
                )
            ) {
                return $next($request);
            }


            abort(419);
        } else {
            return $next($request);
        }

    }
}
