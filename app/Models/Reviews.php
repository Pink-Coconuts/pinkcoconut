<?php

/**
 * Reviews Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Reviews
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reviews;
use Session;

class Reviews extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reviews';
    protected $appends =['date_fy'];
    // Join with users table
    public function users()
    {
      return $this->belongsTo('App\Models\User','user_to','id');
    }

    // Join with users table
    public function users_from()
    {
      return $this->belongsTo('App\Models\User','user_from','id');
    }

    // Join with reservation table
    public function reservation()
    {
      return $this->belongsTo('App\Models\Reservation','reservation_id','id');
    }

    // Get updated_at date in fy format
    public function getDateFyAttribute()
    {
        // return date('F Y', strtotime($this->attributes['updated_at']));
        return date(PHP_DATE_FORMAT, strtotime($this->attributes['updated_at']));
    }

    // Check give record is Hidden review or not
    public function getHiddenReviewAttribute()
    {
        $reservation_id = $this->attributes['reservation_id'];
        $user_from = $this->attributes['user_from'];
        $user_to = $this->attributes['user_to'];
        $check = Reviews::where(['user_from'=>$user_to, 'user_to'=>$user_from, 'reservation_id'=>$reservation_id])->get();
        if($check->count())
            return false;
        else
            return true;
    }

    public function getCreatedAtAttribute(){
        return date(PHP_DATE_FORMAT.' H:i:s',strtotime($this->attributes['created_at']));
    }

    public function getUpdatedAtAttribute(){ 
        return date(PHP_DATE_FORMAT.' H:i:s',strtotime($this->attributes['updated_at']));
    }
}
