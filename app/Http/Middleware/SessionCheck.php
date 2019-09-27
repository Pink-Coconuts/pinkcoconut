<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Contracts\View\Factory as ViewFactory;

use Session;
use App\Models\Currency;
use App\Models\Language;
use Schema;

class SessionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Schema::hasTable('currency')) {

        	$session_currency = session('currency');
        	$is_valid_currency = Currency::where('status', 'Active')->where('code', $session_currency)->first();
            if($is_valid_currency=='') {
        		$original_symbol = Currency::original_symbol(DEFAULT_CURRENCY);
        		Session::put('currency', DEFAULT_CURRENCY);
                Session::put('deleted_currency', $session_currency);
        		Session::put('symbol', $original_symbol);
                if($session_currency){
                    Session::put('previous_currency', $session_currency);
                    Session::put('search_currency', $request->previous_currency);
                }
        	}

        }
        return $next($request);
    }
}
