<?php
/**
 * DisputeMessages Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    DisputeMessages
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Auth;
use Session;

class DisputeMessages extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dispute_messages';

    protected $appends = ['sub_text'];
    //public $timestamps = false;

    public function scopeUnread($query)
    {
        $query = $query->where('read', '0');
        return $query;
    }

    public function getAmountAttribute()
    {
        return $this->currency_calc('amount');
    }

    public function scopeUserReceived($query, $user_id = null)
    {
        $user_id = $user_id ?: @Auth::user()->id;

        $query = $query->where('message_for', '!=', 'Admin')->where('user_to', $user_id);
        return $query;
    }

    public function scopeUserConversation($query, $user_id = null)
    {
    	$user_id = $user_id ?: @Auth::user()->id;

    	$query = $query->where(function($query) use($user_id){
            $query->where(function($query) use($user_id){
                $query->where('message_for', '!=', 'Admin')->orWhere('user_from', $user_id);
            })->where(function($query) use($user_id){
                $query->where('message_by', '!=', 'Admin')->orWhere('user_to', $user_id);
            });
        });
    	return $query;
    }

    public function scopeUserDetails($query, $user_type = '')
    {
        if($user_type == 'receiver')
        {
            $query = $query->with(['receiver_details'=> function($query){
                $query->with('profile_picture');
            }]);
        }
        else
        {
            $query = $query->with(['sender_details'=> function($query){
                $query->with('profile_picture');
            }]);
        }
    }

    public function sender_details()
    {
        return $this->belongsTo('App\Models\User', 'user_from', 'id');
    }
    
    public function receiver_details()
    {
        return $this->belongsTo('App\Models\User', 'user_to', 'id');
    }
    
    public function dispute()
    {
        return $this->belongsTo('App\Models\Disputes', 'dispute_id', 'id');
    }

    public function getMessageReceiverDetailsAttribute()
    {
        $message_receiver_details = [];
        $message_for = $this->attributes['message_for'];
        if($message_for != 'Admin')
        {
            $message_receiver_details = ['name' => $this->receiver_details->first_name, 'profile_picture' => $this->receiver_details->profile_picture->src];
        }
        else
        {
            $message_receiver_details = ['name' => 'Admin', 'profile_picture' => url('images/user_pic-50x50.png')];
        }

        return collect($message_receiver_details);
    }

    public function getMessageSenderDetailsAttribute()
    {
        $message_sender_details = [];
        $message_by = $this->attributes['message_by'];
        if($message_by != 'Admin')
        {
            $message_sender_details = ['name' => $this->sender_details->first_name, 'profile_picture' => $this->sender_details->profile_picture->src];
        }
        else
        {
            $message_sender_details = ['name' => 'Admin', 'profile_picture' => url('images/user_pic-50x50.png')];
        }

        return collect($message_sender_details);
    }

    public function getSenderOrReceiverAttribute()
    {
        $user_id        = @Auth::user()->id;
        $user_or_admin  = request()->segment(1) == ADMIN_URL ? 'Admin' : 'User';
        $user_from      = $this->attributes['user_from'];
        $user_to        = $this->attributes['user_to'];
        $message_by     = $this->attributes['message_by'];
        $message_for    = $this->attributes['message_for'];

        if($user_or_admin == 'Admin')
        {
            $sender_or_receiver = $message_by == 'Admin' ? 'Sender' : 'Receiver';
        }

        if($user_or_admin == 'User')
        {
            if($user_id == $user_from)
            {
                $sender_or_receiver = 'Sender';
            }
            else
            {
                $sender_or_receiver = 'Receiver';
            }

        }

        return $sender_or_receiver;
    }

    public function scopeWithAmount($query)
    {
        $query = $query->where('amount', '>', 0);
        return $query;
    }

    public function scopeLastFirst($query)
    {
        $query = $query->orderBy('id', 'desc');
        return $query;
    }

    public function getSubTextAttribute()
    {
        if($this->amount <= 0)
        {
            return '';
        }
        $user_or_dispute_user = $this->dispute->getUserOrDisputeUserAttribute();
        $currency = $this->dispute->currency;

        $user_to = $this->dispute->user_id == $this->user_to ? 'User' : 'DisputeUser';
        $final_dispute_data = collect(['user_to' => $user_to, 'amount' => $this->amount]);

        $input_message_data['amount']   = html_entity_decode($currency->original_symbol).$final_dispute_data->get('amount');

        if($user_or_dispute_user == 'User')
        {
            $input_message_data['first_name']   = $this->dispute->dispute_user->first_name;
        }
        else
        {
            $input_message_data['first_name']   = $this->dispute->user->first_name;
        }

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

        return trans('messages.disputes.'.@$input_message_data['message'], $input_message_data);
    }

    public function getAdminSubTextAttribute()
    {
        if($this->amount <= 0)
        {
            return '';
        }
        $user_or_dispute_user = $this->dispute->getUserOrDisputeUserAttribute();
        $currency = $this->dispute->currency;

        $user_to = $this->dispute->user_id == $this->user_to ? 'User' : 'DisputeUser';
        $final_dispute_data = collect(['user_to' => $user_to, 'amount' => $this->amount]);

        $input_message_data['amount']   = html_entity_decode($currency->original_symbol).$final_dispute_data->get('amount');

        if($user_or_dispute_user == 'User')
        {
            $input_message_data['first_name']   = $this->dispute->dispute_user->first_name;
            $input_message_data['firstname']    = $this->dispute->user->first_name;
        }
        else
        {
            $input_message_data['first_name']   = $this->dispute->user->first_name;
            $input_message_data['firstname']    = $this->dispute->dispute_user->first_name;
        }

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

        return trans('messages.disputes.admin_'.@$input_message_data['message'], $input_message_data);
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
}

