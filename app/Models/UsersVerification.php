<?php

/**
 * UserVerification Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    UserVerification
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UsersPhoneNumbers;

class UsersVerification extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_verification';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    public $appends = ['verified_count', 'phone_number'];

    // Check to show Verification Panel
    public function show()
    {
        if($this->attributes['email'] == 'no' && $this->attributes['facebook'] == 'no' && $this->attributes['google'] == 'no' && $this->attributes['linkedin'] == 'no'  && $this->getPhoneNumberAttribute() == 'no')
            return false;
        else
            return true;
    }

    public function getPhoneNumberAttribute(){
        $verfied_phone_numbers_count = UsersPhoneNumbers::where('user_id', $this->attributes['user_id'])->where('status', 'Confirmed')->count(); 
        if($verfied_phone_numbers_count > 0){
            return 'yes'; 
        }else{
            return 'no';
        }
    }

    // Verifications Count
    public function verified_count()
    {
        $i = 0;

        if($this->attributes['email'] == 'yes')
            $i += 1;
        if($this->attributes['facebook'] == 'yes') 
            $i += 1;
        if($this->attributes['google'] == 'yes') 
            $i += 1;
        if($this->attributes['linkedin'] == 'yes') 
            $i += 1;
        if($this->getPhoneNumberAttribute() =='yes')
            $i +=1; 

        return $i;
    }

    // Verifications Count
    public function getVerifiedCountAttribute()
    {
        $i = 0;

    	if($this->attributes['email'] == 'yes')
            $i += 1;
        if($this->attributes['facebook'] == 'yes') 
            $i += 1;
        if($this->attributes['google'] == 'yes') 
            $i += 1;
        if($this->attributes['linkedin'] == 'yes') 
            $i += 1;
        if($this->getPhoneNumberAttribute() =='yes')
            $i +=1; 

        return $i;
    }
}
