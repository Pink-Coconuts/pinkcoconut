<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use App;
use App\Models\Pages;
use App\Models\Language;
use View;

class Locale
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
        $locale = Language::translatable()->where('default_language', '=', '1')->first()->value;
        $session_language = Language::translatable()->where('value', '=', Session::get('language'))->first();

        if ($session_language) {
            $locale = $session_language->value;
        }

        App::setLocale($locale);
        Session::put('language', $locale);

        $company_pages = Pages::select('id', 'url', 'name')->where('under', 'company')->where('status', '=', 'Active')->get();
        $discover_pages = Pages::select('id', 'url', 'name')->where('under', 'discover')->where('status', '=', 'Active')->get();
        $hosting_pages = Pages::select('id', 'url', 'name')->where('under', 'hosting')->where('status', '=', 'Active')->get();

        View::share('company_pages', $company_pages);
        View::share('discover_pages', $discover_pages);
        View::share('hosting_pages', $hosting_pages);
        
        return $next($request);
    }
}
