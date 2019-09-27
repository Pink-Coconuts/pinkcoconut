<?php

/**
 * AppService Provider
 *
 * @package     Makent
 * @subpackage  Provider
 * @category    Service
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Config;
use Schema;
use Validator;
use App\Models\SiteSettings;
use View;
use App\Http\Helper\FacebookHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Configuration Setup for Social Media Services
        if(env('DB_DATABASE') != '') {
        if(Schema::hasTable('api_credentials'))
        {
            $google_result = DB::table('api_credentials')->where('site','Google')->get();
            $linkedin_result = DB::table('api_credentials')->where('site','LinkedIn')->get();
            $fb_result = DB::table('api_credentials')->where('site','Facebook')->get();
            $google_map_result = DB::table('api_credentials')->where('site','GoogleMap')->get();
            $nexmo_result = DB::table('api_credentials')->where('site','Nexmo')->get();
            $cloudinary_result = DB::table('api_credentials')->where('site','Cloudinary')->get();
        
            Config::set(['services.google' => [
                    'client_id' => $google_result[0]->value,
                    'client_secret' => $google_result[1]->value,
                    'redirect' => url('/googleAuthenticate'),
                    ]
                    ]);
            Config::set(['services.linkedin' => [
                    'client_id' => $linkedin_result[0]->value,
                    'client_secret' => $linkedin_result[1]->value,
                    'redirect' => url('/linkedinConnect'),
                    ]
                    ]);

            Config::set(['facebook' => [
                    'client_id' => $fb_result[0]->value,
                    'client_secret' => $fb_result[1]->value,
                    'redirect' => url('/facebookAuthenticate'),
                    ]
                    ]);
            /*Set Cloudinary configuration*/
            Config::set(['cloudder' => [
                'cloudName' => $cloudinary_result[0]->value,
                'apiKey' => $cloudinary_result[1]->value,
                'apiSecret' => $cloudinary_result[2]->value,
                'baseUrl' => $cloudinary_result[3]->value.$cloudinary_result[0]->value,
                'secureUrl' => $cloudinary_result[4]->value.$cloudinary_result[0]->value,
                'apiBaseUrl' => $cloudinary_result[5]->value.$cloudinary_result[0]->value,
                ]
            ]);
            Config::set('cloudder.scaling', array());

             View::share('google_client_id', $google_result[0]->value);
             define('google_client_id', $google_result[0]->value);
            
            View::share('map_key', $google_map_result[0]->value);
           
            define('MAP_KEY', $google_map_result[0]->value);
            $fb = new FacebookHelper;
            View::share('fb_url',url('/facebooklogin'));
            
            View::share('map_server_key', $google_map_result[1]->value);
            define('MAP_SERVER_KEY', $google_map_result[1]->value);

            if(count($nexmo_result) > 2){
                View::share('nexmo_key', $nexmo_result[0]->value);
                View::share('nexmo_secret', $nexmo_result[1]->value);
                View::share('nexmo_from', $nexmo_result[2]->value);

                define('NEXMO_KEY', $nexmo_result[0]->value);
                define('NEXMO_SECRET', $nexmo_result[1]->value);
                define('NEXMO_FROM', $nexmo_result[2]->value);
            }

        }
        }

        // Custom Validation for CreditCard is Expired or Not
        Validator::extend('expires', function($attribute, $value, $parameters, $validator) 
        {
            $input    = $validator->getData();

            $expiryDate = gmdate('Ym', gmmktime(0, 0, 0, (int) array_get($input, $parameters[0]), 1, (int) array_get($input, $parameters[1])));
            
            return ($expiryDate >= gmdate('Ym')) ? true : false;
        });

        // Custom Validation for CreditCard is Valid or Not
        Validator::extend('validateluhn', function($attribute, $value, $parameters) 
        {

            if((is_numeric($value)))
            {

            $str = '';
            foreach (array_reverse(str_split($value)) as $i => $c) 
            {
                $str .= $i % 2 ? $c * 2 : $c;

            }

            return array_sum(str_split($str)) % 10 === 0;
            }
            else {

                return false;
            }

            
        });

        // Custom Validation for File Extension
        Validator::extend('extensionval', function($attribute, $value, $parameters) 
        {
            $ext = strtolower($value->getClientOriginalExtension());
            if($ext =='jpg' || $ext == 'jpeg' || $ext =='png'){
                return true;
            }
            else{
                return false;
            }
        });

        // Custom Validation for Min field may not be greater than max field value
        Validator::extend('maxmin', function($attribute, $value, $parameters) 
        {
            $maximum_value =  @$parameters[0] ? $parameters[0]-0 : null;
            if($maximum_value != null && $value > $maximum_value) {
                return false;
            }
            return true;
        });

        // Custom Validation for Min field may not be greater than max field value for Minimum and maximum price calculation
        Validator::extend('maxminstrict', function($attribute, $value, $parameters) 
        {
            $maximum_value =  @$parameters[0] ? $parameters[0]-0 : null;

            if($value > $maximum_value) {
                return false;
            }
            return true;
        });

        if(env('DB_DATABASE') != '') {
        // Configuration Setup for Email Settings
        if(Schema::hasTable('email_settings'))
        {
            $result = DB::table('email_settings')->get();
                   
            Config::set([
                    'mail.driver'     => $result[0]->value,
                    'mail.host'       => $result[1]->value,
                    'mail.port'       => $result[2]->value,
                    'mail.from'       => ['address' => $result[3]->value,
                                          'name'    => $result[4]->value ],
                    'mail.encryption' => $result[5]->value,
                    'mail.username'   => $result[6]->value,
                    'mail.password'   => $result[7]->value
                                    
                    ]);

            if($result[0]->value=='mailgun'){

            Config::set([
                    'services.mailgun.domain'     => $result[8]->value,
                    'services.mailgun.secret'       => $result[9]->value,
                    ]);
           }

            

            Config::set([
                 
                    'laravel-backup.notifications.mail.from' => $result[3]->value,
                    'laravel-backup.notifications.mail.to'   => $result[3]->value

                    ]);
        }
        if(Schema::hasTable('site_settings'))
        {
            $site_settings = SiteSettings::all();
            $customer=$site_settings[21]->value;

            View::share('customer_support', $customer);

              Config::set([
                 
                    'laravel-backup.backup.name'             => $site_settings[0]->value,
                    'laravel-backup.monitorBackups.name'     => $site_settings[0]->value,
                  
                    ]);
            //
           // $contact=$site_settings->

            // Config::set([
            //         'swap.providers.yahoo_finance'  => ($site_settings[6]->value == 'yahoo_finance') ? true : false,
            //         'swap.providers.google_finance' => ($site_settings[6]->value == 'google_finance') ? true : false,
            //         ]);

            Config::set([
                'swap.providers' => [
                    "google_finance" => true
                ]
            ]);
        } 

        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Illuminate\Support\Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage($pageName);
              
            return new \Illuminate\Pagination\LengthAwarePaginator($this->forPage($page, $perPage), $total ?: $this->count(), $perPage, $page, [
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]);
        });
    }
}
