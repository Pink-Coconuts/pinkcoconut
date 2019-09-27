<?php

/**
 * ReferralSettings Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    ReferralSettings
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class ReferralSettings extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'referral_settings';

    public $timestamps = false;

    public static function value($id, $currency = 'symbol')
    {
    	$result = ReferralSettings::find($id);

        if($result->name != 'currency_code') {
            $amount           = $result->value;

            $currency_code    = ReferralSettings::whereName('currency_code')->first()->value;

            $rate             = Currency::whereCode($currency_code)->first()->rate;

            $usd_amount       = $amount / $rate;

            $default_currency = Currency::where('default_currency',1)->first()->code;

            $session_rate     = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->rate;

            return round($usd_amount * $session_rate);
        }
        else {
            $default_currency = Currency::where('default_currency',1)->first()->code;

            $currency_code    = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->$currency;
            
            return $currency_code;
        }
    }
}
