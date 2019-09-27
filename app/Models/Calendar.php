<?php

/**
 * Calendar Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Calendar
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use App\Models\RoomsPrice;
use App\Models\Currency;
use Session;
use JWTAuth;

class Calendar extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'calendar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['room_id', 'price', 'status', 'date', 'notes', 'source', 'spots_booked', 'is_shared'];

    protected $appends = ['session_currency_price'];


    // Get result of night price for current currency
    public function getPriceAttribute()
    {
        //return $this->currency_calc('price');
        return $this->attributes['price'];
    }

    public function getSessionCurrencyPriceAttribute(){
        return $this->currency_calc('price');
    }

    public function setSpotsBookedAttribute($value){
      if((@$this->attributes['spots_booked'] > 0 && @$this->attributes['is_shared'] == '') || @$this->attributes['spots_booked'] <= 0)
        @$this->attributes['is_shared'] = $this->rooms->is_shared;

      $this->attributes['spots_booked'] = $value;
    }

    public function rooms()
    {
      return $this->belongsTo('App\Models\Rooms','room_id','id');
    }

    public function getSpotsLeftAttribute()
    {
      $total_spots = $this->rooms->accommodates;
      $spots_left = $total_spots - $this->attributes['spots_booked'];
      return $spots_left;
    }

    public function scopeNotAvailable($query, $total_guests=1)
    {
      $query->whereStatus('Not available')->with(['rooms'])->whereHas('rooms', function($query) use($total_guests){
        $query->where(function($query) use($total_guests){
          $query->whereRaw('calendar.spots_booked +'.$total_guests.' > rooms.accommodates');
          $query->orWhere('calendar.is_shared', 'No');
          $query->orWhere('rooms.is_shared', 'No');
          $query->orWhere('calendar.source', 'Calendar');
        });
      });
    }

    public function scopeDaysNotAvailable($query, $days = array(), $total_guests=1)
    {
      return $query->whereIn('date', $days)->notAvailable($total_guests);
    }

    public function isNotAvailable($total_guests)
    {
      $rooms = $this->rooms;
      $is_not_available = ( 
                            $this->attributes['status'] == "Not available" && 
                            ( 
                              ($this->attributes['spots_booked']+$total_guests > $rooms->accommodates) ||
                              ($this->attributes['is_shared'] == 'No') ||
                              ($this->rooms->is_shared == 'No') ||
                              ($this->attributes['source'] == 'Calendar')
                            ) 
                          );
      return $is_not_available;
    }

    // Calculation for current currency conversion of given price field
    public function currency_calc($field)
    { 
        //get currenct url
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
           */
        $currency_code = RoomsPrice::where('room_id', $this->attributes['room_id'])->first()->currency_code;

        $rate = Currency::whereCode($currency_code)->first()->rate;

        $usd_amount = $this->attributes[$field] / $rate;

        $default_currency = Currency::where('default_currency',1)->first()->code;
         
          //Api currency conversion
          /*if(@$url_array['0']=='api')*/
          $code = 'USD';
          if(request()->segment(1) == 'api')
          { 
            try {
              if (!JWTAuth::parseToken()) {
                 $code =JWTAuth::parseToken()->authenticate()->currency_code;
              }

            } catch (\Exception $e) {
              
            }
           
            $session_rate = Currency::whereCode($code)->first()->rate; 
          }
          else
          { //web currency conversion
            $session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->rate;
          }
        
        return round($usd_amount * $session_rate);
    }

}
