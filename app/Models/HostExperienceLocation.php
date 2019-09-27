<?php

/**
 * HostExperienceLocation Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    HostExperienceLocation
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostExperienceLocation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_experience_location';

    public $timestamps = false;

    // Get country_name by using country code in Country table
    public function getCountryNameAttribute()
    {
        return Country::where('short_name',$this->attributes['country'])->first()->long_name;
    }
}
