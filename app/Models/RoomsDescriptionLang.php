<?php

/**
 * language description Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    language description
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomsDescriptionLang extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms_description_lang';

    public $timestamps = false;


    protected $appends = ['name_original','summary_original','steps_count'];


    // Join with rooms_address table
    public function language()
    {
        return $this->belongsTo('App\Models\Language','lang_code','value');
    }

   public function getNameOriginalAttribute()
    {
        return $this->attributes['name'];
    }
      public function getSummaryOriginalAttribute()
    {
        return $this->attributes['summary'];
    }
    // Get steps_count using sum of rooms_steps_status
    public function getStepsCountAttribute()
    {
        $result = RoomsStepsStatus::find($this->attributes['room_id']);

        return 6 - (@$result->basics + @$result->description + @$result->location + @$result->photos + @$result->pricing + @$result->calendar);
    }
}
