<?php

/**
 * HostExperienceCalendar Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    HostExperienceCalendar
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostExperienceCalendar extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_experience_calendar';

    protected $fillable = ['host_experience_id', 'date', 'price', 'status'];

    public $timestamps = true;

    public function scopeNotAvailable($query, $number_of_guests)
    {
        return $query->where('status', 'Not available')->where(function($inner_query) use($number_of_guests)
            {
                $inner_query->where('source', 'Calendar');
                $inner_query->orWhereRaw('`host_experience_calendar`.`spots_booked` >= '.$number_of_guests);
            }
        );
    }

    public function host_experience()
    {
    	$this->belongsTo('App\Models\HostExperiences','host_experience_id','id');
    }

    public function getSpotsLeftAttribute()
    {
    	$spots_booked  = @$this->attributes['spots_booked'];
	    $total_spots = $this->host_experience->number_of_guests;
	    $spots_left = ($total_spots - $spots_booked);
	    $spots_left = $spots_left > 0 ? $spots_left : 0;
    	return $spots_left;
    }

    public function getSpotsArrayAttribute()
    {
        $spots_array = explode(',', @$this->attributes['spots']);
        $spots_array = array_map('intval', $spots_array);
        return $spots_array;
    }
}
