<?php

/**
 * Coupon Code Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Coupon Code
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupon_code';

    public $timestamps = false;   

    // Convert y-m-d date into d-m-y
    public function getExpiredAtDmyAttribute()
    {
        if(@$this->attributes['expired_at'] != '0000-00-00')
            return date(PHP_DATE_FORMAT, strtotime(@$this->attributes['expired_at']));
        else
            return '';
    }
    // Convert y-m-d date into d-m-y
    public function getExpiredAtAttribute()
    {
        if(@$this->attributes['expired_at'] != '0000-00-00')
            return date(PHP_DATE_FORMAT, strtotime(@$this->attributes['expired_at']));
        else
            return '';
    }
}
