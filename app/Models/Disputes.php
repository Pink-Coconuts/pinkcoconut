<?php
/**
 * Disputes Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Disputes
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use JWTAuth;
use Auth;
use Session;
class Disputes extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'disputes';

    protected $appends = ['user_or_dispute_user', 'created_at_view', 'inbox_subject', 'status_show', 'user_name','original_currency_code'];

    //public $timestamps = false;

    public $user_or_dispute_user = '';

    public function reservation()
    {
    	return $this->belongsTo('App\Models\Reservation', 'reservation_id', 'id');
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function dispute_user()
    {
        return $this->belongsTo('App\Models\User', 'dispute_user_id', 'id');   
    }

    public function currency()
    {
    	return $this->belongsTo('App\Models\Currency', 'currency_code', 'code');	
    }

    public function dispute_messages()
    {
    	return $this->hasMany('App\Models\DisputeMessages', 'dispute_id', 'id');
    }

    public function dispute_documents()
    {
    	return $this->hasMany('App\Models\DisputeDocuments', 'dispute_id', 'id');
    }

    public function getAmountAttribute()
    {
        return $this->currency_calc('amount');
    }

    public function getDisputeAmountAttribute()
    {
        return $this->currency_calc('final_dispute_amount');
    }

    public function scopeUserBased($query, $user_id = null)
    {
    	$user_id = $user_id ?: @Auth::user()->id;

    	$query = $query->with(['reservation' => function($query){
                $query->with(['rooms']);
            }])
    		->whereHas('reservation', function($query) use($user_id){
				$query->userRelated($user_id);
			});
		return $query;
    }

    public function scopeReservationBased($query, $reservation_id)
    {
        $query = $query->where('reservation_id', $reservation_id);
        return $query;
    }

    public function scopeStatus($query, $status = '')
    {
        if($status != '')
        {
            $query = $query->where('status', $status);
        }

        return $query;
    }

    public function scopeUsers($query)
    {
        $query = $query->with(['user' => function($query){
            $query->with(['profile_picture']);
        }]);
    }

    public function scopeDisputeUser($query)
    {
        $query = $query->with(['dispute_user' => function($query){
            $query->with(['profile_picture']);
        }]);
    }

    public function scopeReceivedUnreadMessages($query)
    {
        $query = $query->with(['dispute_messages' => function($query){
            $query->userReceived()->unread();
        }]);
        return $query;
    }

    // Calculation for current currency conversion of given price field
    public function currency_calc($field)
    {
      
      /*$route=@Route::getCurrentRoute();
      if($route){
        $api_url = @$route->getPath();
      }else{
        $api_url = '';
      }

      $url_array=explode('/',$api_url);
        //Api currency conversion
      if(@$url_array['0']=='api')*/
    if(request()->segment(1) == 'api')
      { 
       $user_details = JWTAuth::parseToken()->authenticate(); 
            
       $rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;

       $usd_amount = $this->attributes[$field] / $rate;

       $api_currency = $user_details->currency_code; 

       $default_currency = Currency::where('default_currency',1)->first()->code;

       $session_rate = Currency::whereCode($user_details->currency_code!=null?$user_details->currency_code :$default_currency)->first()->rate;
              
               return round($usd_amount * $session_rate);
               
      }
      else
      {

        $rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;

        $usd_amount = @$this->attributes[$field] / @$rate;

        $default_currency = Currency::where('default_currency',1)->first()->code;

        $session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->rate;

        return round($usd_amount * $session_rate);
      }

    }

    public function getOriginalCurrencyCodeAttribute()
    {
        return $this->attributes['currency_code'];
    }

    // Get default currency code if session is not set
    public function getCurrencyCodeAttribute()
    {
        if(request()->segment(1)  == 'api')
        {
          $user_details = JWTAuth::parseToken()->authenticate(); 
          return $user_details->currency_code;
        }
        if(Session::get('currency'))
           return Session::get('currency');
        else
           return DB::table('currency')->where('default_currency', 1)->first()->code;
    }

    /*
    * To get the Current User relation to this dispute
    */
    public function getUserOrDisputeUserAttribute()
    {
        if(@$this->user_or_dispute_user)
        {
            return @$this->user_or_dispute_user;
        }

        $user_or_dispute_user = '';
        $current_user_id = @Auth::user()->id;
        if(request()->segment(1) == ADMIN_URL)
        {
            $user_or_dispute_user = '';
        }
        else if($this->attributes['user_id'] == $current_user_id)
        {
            $user_or_dispute_user = 'User';
        }
        elseif($this->attributes['dispute_user_id'] == $current_user_id)
        {
            $user_or_dispute_user = 'DisputeUser';
        }
        
        return $user_or_dispute_user;
    }

    public function set_user_or_dispute_user($user_or_dispute_user = 'User')
    {
        $this->user_or_dispute_user = $user_or_dispute_user;
    }

    public function getUserNameAttribute(){
        return $this->user->first_name;
    }



    public function getCreatedAtViewAttribute()
    {
        return date(PHP_DATE_FORMAT,strtotime($this->attributes['created_at']));
    }

    public function getFinalDisputeDataAttribute()
    {
        $amount = $this->amount;
        $amount_message = $this->dispute_messages()->withAmount()->lastFirst()->first();

        $final_dispute_data = ['user_to' => 'DisputeUser', 'amount' => $amount];
        if($amount_message)
        {
            $user_to = $this->attributes['user_id'] == $amount_message->user_to ? 'User' : 'DisputeUser';
            $final_dispute_data = ['user_to' => $user_to, 'amount' => $amount_message->amount];
        }

        return collect($final_dispute_data);
    }

    public function getInboxSubjectAttribute()
    {
        $user_or_dispute_user = $this->getUserOrDisputeUserAttribute();
        $currency = $this->currency;
        $amount = html_entity_decode($currency->symbol).$this->amount;
        $final_dispute_data = $this->final_dispute_data;

        $input_message_data['amount']   = html_entity_decode($currency->symbol).$final_dispute_data->get('amount');

        if($user_or_dispute_user == 'User')
        {
            $input_message_data['first_name']   = $this->dispute_user->first_name;
        }
        else
        {
            $input_message_data['first_name']   = $this->user->first_name;
        }

        if($this->attributes['status'] == 'Open' || $this->attributes['status'] == 'Processing')
        {
            if($final_dispute_data->get('user_to') == 'DisputeUser')
            {
                if($user_or_dispute_user == 'User')
                {
                    $input_message_data['message']   = 'you_requested_dispute_amount_from';
                }
                else
                {
                    $input_message_data['message']   = 'user_requested_dispute_amount_from';
                }
            }
            else if($final_dispute_data->get('user_to') == 'User')
            {
                if($user_or_dispute_user == 'User')
                {
                    $input_message_data['message']   = 'user_offered_dispute_amount_to';
                }
                else
                {
                    $input_message_data['message']   = 'you_offered_dispute_amount_to';
                }
            }
        }
        if($this->attributes['status'] == 'Closed')
        {
            if($final_dispute_data->get('user_to') == 'DisputeUser')
            {
                if($user_or_dispute_user == 'User')
                {
                    $input_message_data['message']   = 'user_accepted_dispute_amount_for';
                }
                else
                {
                    if($this->attributes['dispute_by'] == 'Guest')
                    {
                        $input_message_data['message']   = 'you_accepted_dispute_amount_for';
                    }
                    else
                    {
                        $input_message_data['message']   = 'you_paid_dispute_amount_for';   
                    }
                }
            }
            else if($final_dispute_data->get('user_to') == 'User')
            {
                if($user_or_dispute_user == 'User')
                {
                    $input_message_data['message']   = 'you_accepted_dispute_amount_for';
                }
                else
                {
                    $input_message_data['message']   = 'user_accepted_dispute_amount_for';
                }
            }            
        }
        return trans('messages.disputes.'.@$input_message_data['message'], $input_message_data);
    }

    public function getStatusShowAttribute()
    {
        return trans('messages.disputes.'.$this->attributes['status']);
    }

    public function getMaximumDisputeAmountAttribute()
    {
        $dispute_by = $this->attributes['dispute_by'];
        $reservation = $this->reservation;

        $maximum_dispute_amount = 0;

        if($dispute_by == 'Guest')
        {
            $maximum_dispute_amount = $reservation->maximum_guest_dispute_amount;
        }
        else
        {
            $maximum_dispute_amount = $reservation->maximum_host_dispute_amount;
        }

        return $maximum_dispute_amount;
    }

    public function scopeUserConversation($query)
    {
        $query = $query->with(['dispute_messages'=> function($query){
            $query->userConversation();
        }]);

        return $query;
    }

    public function can_dispute_accept_form_show()
    {
        $user_or_dispute_user = $this->getUserOrDisputeUserAttribute();
        return 
            (
                (
                    (($this->final_dispute_data->get('user_to') == $user_or_dispute_user) && $this->attributes['payment_status'] == null) || 
                    ($this->dispute_by == 'Host' && $user_or_dispute_user == 'DisputeUser' && $this->attributes['payment_status'] == 'Pending')
                ) && 
                ($this->attributes['status'] == 'Open' || $this->attributes['status'] == 'Processing')
            );
    }

    public function is_pay()
    {
        $user_or_dispute_user = $this->getUserOrDisputeUserAttribute();
        return ($this->attributes['dispute_by'] == 'Host' && $user_or_dispute_user == 'DisputeUser');
    }
}


