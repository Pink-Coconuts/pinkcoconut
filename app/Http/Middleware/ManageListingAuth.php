<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use App\Models\Rooms;
use Illuminate\Support\Facades\Auth;

class ManageListingAuth
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
        $user_id = Auth::user()->id; 

        //uri room id check if 2uri in numeric or not
        @$room_id = (is_numeric(@$request->segment(2))) ? @$request->segment(2) : @$request->segment(3); 

        $room = Rooms::find($room_id);
        if($room !=''){           
        if($room->user_id == $user_id){
            return $next($request); 
        }else{
            return json_encode(['redirect' => url('rooms')]);
        }
        }
    }

}
