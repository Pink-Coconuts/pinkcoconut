<?php

/**
 * Referrals Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Referrals
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Referrals extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'referrals';

    protected $appends = ['referrer_name', 'referee_name', 'signup_count', 'booking_count', 'listing_count'];
    
    public function getCreditedAmountAttribute()
    {
    	return $this->currency_calc('credited_amount');
    }

    public function getFriendCreditedAmountAttribute()
    {
        return $this->currency_calc('friend_credited_amount');
    }
   
    public function convert($remain)
    {
        
       $rate = Currency::whereCode(Session::get('currency'))->first()->rate;

        $usd_amount = $remain / $rate;

        $default_currency = Currency::where('default_currency',1)->first()->code;

        $session_rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;

        return round($usd_amount * $session_rate);

    }

    public function getIfFriendGuestAmountAttribute()
    {
    	return $this->currency_calc('if_friend_guest_amount');
    }

    public function getIfFriendGuestAmountOriginalAttribute()
    {
        return $this->attributes['if_friend_guest_amount'];
    }

    public function getIfFriendHostAmountOriginalAttribute()
    {
    	return $this->attributes['if_friend_host_amount'];
    }

     public function getIfFriendHostAmountAttribute()
    {
        return $this->currency_calc('if_friend_host_amount');
    }

    public function getCreditableAmountAttribute()
    {
    	return $this->currency_calc('creditable_amount');
    }

    // Join with currency table
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','currency_code','code');
    }

    // Join with users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    // Join with users table
    public function friend_users()
    {
        return $this->belongsTo('App\Models\User','friend_id','id');
    }

    public function getReferrerNameAttribute()
    {
        return User::find($this->attributes['user_id'])->first_name;
    }

    public function getRefereeNameAttribute()
    {
        return User::find($this->attributes['friend_id'])->first_name;
    }

    public function getSignupCountAttribute()
    {
        return Referrals::whereUserId($this->attributes['user_id'])->get()->count();
    }

    public function getBookingCountAttribute()
    {
        $count = 0;
        $result = Referrals::whereUserId($this->attributes['user_id'])->get();
        foreach($result as $row) {
            $reservation_count = Reservation::whereUserId($row->friend_id)->get()->count();
            if($reservation_count >= 1) {
                $count += 1;
            }
        }
        return $count;
    }

    public function getListingCountAttribute()
    {
        $count = 0;
        $result = Referrals::whereUserId($this->attributes['user_id'])->get();
        foreach($result as $row) {
            $listing_count = Rooms::whereUserId($row->friend_id)->get()->count();
            if($listing_count >= 1) {
                $count += 1;
            }
        }
        return $count;
    }

    public function booking_status($id)
    {
        $count = Reservation::whereUserId($id)->get()->count();

        return ($count) ? 'Yes' : 'No';
    }

    public function listing_status($id)
    {
        $count = Rooms::whereUserId($id)->get()->count();

        return ($count) ? 'Yes' : 'No';
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
}
