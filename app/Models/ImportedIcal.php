<?php

/**
 * Imported iCal Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Imported iCal
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportedIcal extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'imported_ical';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['room_id', 'url', 'name', 'last_sync'];
}
