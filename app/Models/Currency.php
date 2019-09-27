<?php

/**
 * Currency Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Currency
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use JWTAuth;
use DB;

class Currency extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'currency';

    public $timestamps = false;

    protected $appends = ['original_symbol'];

    // Get default currency symbol if session is not set
    public function getSymbolAttribute()
    {

        $default_currency = DB::table('currency')->where('default_currency', 1)->first()->symbol;

        if( ( request()->segment(1) == 'api' || strlen(request()->token) > 25 ) && request()->segment(1) !='users' )
        {
            
            $currency_symbol = $default_currency;

            if (request('token')) {
                $user_details = JWTAuth::parseToken()->authenticate();
                if($user_details->currency_code){
                    $currency_symbol = DB::table('currency')->where('code', $user_details->currency_code)->first()->symbol;
                }
            } 

            return $currency_symbol;
        }
        else{

            if(Session::get('symbol'))
               return Session::get('symbol');
            else
               return $default_currency;
       }
    }

    // Get default currency symbol if session is not set
    public function getSessionCodeAttribute()
    {

        $default_currency = DB::table('currency')->where('default_currency', 1)->first()->code;

        if(request()->segment(1) == 'api' || strlen(request()->token) > 25 ){
            
            $currency_code = $default_currency;

            if (request('token')) {
                $user_details = JWTAuth::parseToken()->authenticate();
                if($user_details->currency_code){
                    $currency_code = $user_details->currency_code;
                }
            } 


            return $currency_code;
        }
        else{

            if(Session::get('currency'))
               return Session::get('currency');
            else
               return $default_currency;

        }
    }

    // Get symbol by where given code
    public static function original_symbol($code)
    {
    	$symbol = DB::table('currency')->where('code', $code)->first()->symbol;
    	return $symbol;
    }

    // Get currenct record symbol
    public function getOriginalSymbolAttribute()
    {
        $symbol = $this->attributes['symbol'];
        return $symbol;
    }
}
