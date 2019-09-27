<?php

/**
 * Payouts Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Payouts
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;

class Payouts extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payouts';

    public $appends = ['currency_symbol', 'date'];

    protected $fillable = ['user_id', 'reservation_id'];

    // Get Payout Amount
    public function getAmountAttribute()
    {
        return $this->currency_calc('amount');
    }

    // Get Date with new format
    public function getDateAttribute()
    {
        //return date('d-m-Y', strtotime($this->attributes['updated_at']));
        return date(PHP_DATE_FORMAT, strtotime($this->attributes['updated_at']));
    }

    // Get Date with new format
    public function getTotalPenaltyAmountAttribute()
    {
        $penalty_ids = explode(',', $this->attributes['penalty_id']) ?: array();
        $penalty_amts = explode(',', $this->attributes['penalty_amount']) ?: array();
        $penalty_currencies  = HostPenalty::select('currency_code')->whereIn('id', $penalty_ids)->get()->pluck('currency_code');

        $penalty_amount_converted = array();
        foreach($penalty_ids as $k => $id)
        {
            $penalty_amount_converted[] = $this->currency_convert(@$penalty_currencies[$k], '', @$penalty_amts[$k]);
        }
        return array_sum($penalty_amount_converted);
    }

    // Calculation for current currency conversion of given price field
    public function currency_calc($field)
    {
        $rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;

        $usd_amount = $this->attributes[$field] / $rate;

        $default_currency = Currency::where('default_currency',1)->first()->code;

        $session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->rate;

        return round($usd_amount * $session_rate);
    }

    // Calculation for current currency conversion of given amount
    public function currency_convert($from = '', $to = '', $price)
    {
      if($from == '')
      {
        if(Session::get('currency'))
           $from = Session::get('currency');
        else
           $from = Currency::where('default_currency', 1)->first()->code;
      }

      if($to == '')
      {
        if(Session::get('currency'))
           $to = Session::get('currency');
        else
           $to = Currency::where('default_currency', 1)->first()->code;
      }

      $rate = Currency::whereCode($from)->first()->rate;

      $usd_amount = $price / $rate;
      
      $session_rate = Currency::whereCode($to)->first()->rate;

      return ceil($usd_amount * $session_rate);
    }

    // Get default currency code if session is not set
    public function getCurrencyCodeAttribute()
    {
        if(Session::get('currency'))
           return Session::get('currency');
        else
           return DB::table('currency')->where('default_currency', 1)->first()->code;
    }

    // Get Currency Symbol
    public function getCurrencySymbolAttribute()
    {
        $default_currency = Currency::where('default_currency',1)->first()->code;

        return DB::table('currency')->where('code', (Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->symbol;
    }

    public function getSpotsArrayAttribute()
    {
        $spots_array = explode(',', @$this->attributes['spots']);
        $spots_array = array_map('intval', $spots_array);
        return $spots_array;
    }

   // Join to Reservation table
    public function reservation()
    {
        return $this->belongsTo('App\Models\Reservation','reservation_id','id');
    }

    // Join with users table
    public function users() {
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
