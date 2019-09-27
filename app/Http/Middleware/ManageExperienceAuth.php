<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use App\Models\HostExperiences;
use Illuminate\Support\Facades\Auth;

class ManageExperienceAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     /**
     * The Guard implementation.
     *
     * @var Guard
     */

    public function handle($request, Closure $next) 
    {
        $user_id = Auth::user()->id; 
        $host_experience_id = $request->segment(3);
        $host_experience = HostExperiences::authUser()->find($host_experience_id);
        if($host_experience){           
                return $next($request); 
        }
        else{
            if($request->ajax())
                response(array(503, url('404')));
            else
                abort(404);
        }
    }

}
