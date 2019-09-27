<?php

/**
 * UsersPhoneNumbers Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    UsersPhoneNumbers
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Country;

class UsersPhoneNumbers extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_phone_numbers';

    protected $primaryKey = 'id';

    protected $appends = ['phone_number_full', 'phone_number_protected', 'phone_number_nexmo', 'default_phone_code', 'phone_number_status_message', 'verification_message_text']; 

    public $timestamps = false;

    // Get phone_number_full Attribute
    public function getPhoneNumberFullAttribute(){
        return '+'.$this->attributes['phone_code'].$this->attributes['phone_number'];
    }

    // Get phone_number_protected Attribute
    public function getPhoneNumberProtectedAttribute(){
        $phone_code = '+'.$this->attributes['phone_code']; 
        $phone_number = $this->attributes['phone_number'];
        if($phone_number != ''){
            $repeat_length = strlen($phone_number)-3 > 0 ? strlen($phone_number)-3 : strlen($phone_number);
            $replace = str_repeat('* ',$repeat_length);
            $phone_number_protected = $phone_code.substr_replace($phone_number, $replace, 0, -3); 
            return $phone_number_protected;
        }else{
            return '';
        }        
    }

    // Get phone_number_nexmo Attribute
    public function getPhoneNumberNexmoAttribute(){
        return $this->attributes['phone_code'].$this->attributes['phone_number'];
    }

    // Get phone_number_status_message Attribute
    public function getPhoneNumberStatusMessageAttribute(){
        return trans('messages.profile.'.$this->attributes['status']);
    }

    // Get verification_message_text Attribute
    public function getVerificationMessageTextAttribute(){
        return SITE_NAME.' '.trans('messages.profile.security_code').': '.$this->attributes['otp'] .'. '.trans('messages.profile.use_this_to_verify');
    }

    public function getDefaultPhoneCodeAttribute(){
        // return $this->attributes['phone_code']  != '' ? $this->attributes['phone_code'] : '91';
        return '93'; 
    }
}
