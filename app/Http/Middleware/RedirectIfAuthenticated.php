<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $redirect_to = 'dashboard';

        if($guard == 'admin')
        {
            $redirect_to = ADMIN_URL.'/dashboard';
        }
        $is_admin_path = $request->segment(1) == ADMIN_URL;

        if (Auth::guard($guard)->check() && ($guard != 'admin' || $is_admin_path)) {
            return redirect($redirect_to);
        }
        else if($guard == 'admin' && !$is_admin_path)
        {
            return redirect('about/'.$request->segment(1));
        }

        return $next($request);
    }
}
