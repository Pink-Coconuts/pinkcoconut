<?php

/**
 * HostExperienceCities Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    HostExperienceCities
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateTimeZone;

class HostExperienceCities extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_experience_cities';

    public $timestamps = true;

    public function scopeActive($query)
    {
    	$query = $query->where('status', 'Active');
    	return $query;
    }

    // Join with currency table
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','currency_code','code');
    }

    // Join with timezone table
    public function timezone_details()
    {
        return $this->belongsTo('App\Models\Timezone','timezone','id');
    }

    public function getTimezoneAbbrAttribute()
    {
        $timezone = $this->timezone_details->value;
        $dateTime = new DateTime(); 
        $dateTime->setTimeZone(new DateTimeZone($timezone)); 
        $timezone_abbr = $dateTime->format('T');

        return $timezone_abbr;
    }
}
