<?php

/**
 * Rooms Steps Status Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Rooms Steps Status
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomsStepsStatus extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms_steps_status';

    public $timestamps = false;

    protected $primaryKey = 'room_id';
    
    public function setAttribute($attribute, $value)
    {
        if($attribute != 'id')
        {
            $this->attributes[$attribute] = $value.'';
        }
    }
}
