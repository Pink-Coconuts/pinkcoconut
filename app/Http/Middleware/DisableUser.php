<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\Models\Rooms;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
class DisableUser
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
     /*Route::group(['middleware' => 'manage_listing_auth'] , function(){ });*/

    public function handle($request, Closure $next)
    {
          $user_token  = JWTAuth::parseToken()->authenticate();
        if($user_token->status=='Inactive')
        {
               return response()->json([
                                        'success_message'   =>  'This user in Inactive status please contact admin ',

                                        'status_code'       =>  '0'
                                     ]); 
        }
       return $next($request); 
    }

}
