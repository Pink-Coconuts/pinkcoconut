<?php

/**
 * PayoutPreferences Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    PayoutPreferences
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use DateTime;
use DateTimeZone;
use Config;
use Auth;
use JWTAuth;

class PayoutPreferences extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payout_preferences';

    public $appends = ['updated_time', 'updated_date','deleted_time '];

    // Get Updated time for Payout Information
    public function getUpdatedTimeAttribute()
    {    //Get currenct url 
        /*$route=@Route::getCurrentRoute();

        if($route)
        {

         $api_url = @$route->getPath();

        }
        else
        {

        $api_url = '';

        }
        $url_array=explode('/',$api_url);

           //check the url is web or mobile
        if(@$url_array['0']=='api')*/
        if(request()->segment(1) == 'api')
        { 

          $new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));

          $new_str->setTimeZone(new DateTimeZone(JWTAuth::parseToken()->authenticate()->timezone));

          $datemonth = date(PHP_DATE_FORMAT, strtotime($this->attributes['updated_at']));
          return $datemonth.' at '.$new_str->format('H:i');
        }
        else
        { 

          $new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));

          $new_str->setTimeZone(new DateTimeZone(Auth::user()->timezone));
          $datemonth = date(PHP_DATE_FORMAT, strtotime($this->attributes['updated_at']));
          return $datemonth.' at '.$new_str->format('H:i');

        }


       
    }

    // Get Updated date for Payout Information
    public function getUpdatedDateAttribute()
    {
         //Get currenct url 
        /*$route=@Route::getCurrentRoute();

        if($route)
        {

         $api_url = @$route->getPath();

        }
        else
        {

        $api_url = '';

        }
        $url_array=explode('/',$api_url);

           //check the url is web or mobile
        if(@$url_array['0']=='api')*/
        if(request()->segment(1) == 'api')
        { 
           $new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));

           $new_str->setTimeZone(new DateTimeZone(JWTAuth::parseToken()->authenticate()->timezone));

           $datemonth = date(PHP_DATE_FORMAT, strtotime($this->attributes['updated_at']));
           return $datemonth;
        }
        else
        {  
            $new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));

           $new_str->setTimeZone(new DateTimeZone(Auth::user()->timezone));

           $datemonth = date(PHP_DATE_FORMAT, strtotime($this->attributes['updated_at']));
           return $datemonth;

        }
        
    }

    // get mandatory field for create stripe token
    public static function getMandatory($country='US')
    {
      $mandatory = [];
      $mandatory['AT'] = array( 'iban'            => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['AU'] = array( 'bsb'             => 'required',
                                'account_number'  => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['BE'] = array( 'iban'                => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['CA'] = array( 'transit_number'      => 'required',
                                'account_number'      => 'required',
                                'institution_number'  => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['GB'] = array('sort_code' => 'required','account_number' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['HK'] = array('clearing_code' => 'required','account_number' => 'required','branch_code' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['JP'] = array('bank_code' => 'required','account_number' => 'required','branch_code' => 'required','bank_name' => 'required','branch_name' => 'required','account_owner_name' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['NZ'] = array('routing_number' => 'required','account_number' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['SG'] = array('bank_code' => 'required','account_number' => 'required','branch_code' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['US'] = array('routing_number' => 'required','account_number' => 'required', 'account_holder_name' => 'required', 'currency' => 'required','ssn_last_4' => 'required');
      $mandatory['CH'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['DE'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['DK'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['ES'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['FI'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['FR'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['IE'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['IT'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['LU'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['NL'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['NO'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['PT'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');
      $mandatory['SE'] = array('iban' => 'required', 'account_holder_name' => 'required', 'currency' => 'required');      
      
      return @$mandatory[$country] ? @$mandatory[$country] : NULL;
    }
    

    // get mandatory field for create stripe token
    public static function getAllMandatory()
    {
      $mandatory = [];
      $mandatory['AT'] = array('IBAN');
      $mandatory['AU'] = array('BSB','Account Number');
      $mandatory['BE'] = array('IBAN');
      $mandatory['CA'] = array('Transit Number','Account Number','Institution Number');
      $mandatory['GB'] = array('Sort Code','Account Number');
      $mandatory['HK'] = array('Clearing Code','Account Number','Branch Code');
      $mandatory['JP'] = array('Bank Code','Account Number','Branch Code','Bank Name','Branch Name','Account Owner Name ');
      $mandatory['NZ'] = array('Routing Number','Account Number');
      $mandatory['SG'] = array('Bank Code','Account Number','Branch Code');
      $mandatory['US'] = array('Routing Number','Account Number');
      $mandatory['CH'] = array('IBAN');
      $mandatory['DE'] = array('IBAN');
      $mandatory['DK'] = array('IBAN');
      $mandatory['ES'] = array('IBAN');
      $mandatory['FI'] = array('IBAN');
      $mandatory['FR'] = array('IBAN');
      $mandatory['IE'] = array('IBAN');
      $mandatory['IT'] = array('IBAN');
      $mandatory['LU'] = array('IBAN');
      $mandatory['NL'] = array('IBAN');
      $mandatory['NO'] = array('IBAN');
      $mandatory['PT'] = array('IBAN');
      $mandatory['SE'] = array('IBAN');
      return $mandatory;
    }
    

    
    // Join with users table
    public function users()
    {
      return $this->belongsTo('App\Models\User','user_id','id');
    }
}
