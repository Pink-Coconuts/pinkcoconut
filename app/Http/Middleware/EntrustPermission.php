<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class EntrustPermission
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, $permission)
    {
        $auth_user = auth()->guard('admin')->user();
        
        if(!$auth_user->can([$permission]))
        {
            abort(403);
        }

        return $next($request);
    }
}
