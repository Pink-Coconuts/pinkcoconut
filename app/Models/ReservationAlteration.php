<?php

/**
 * Reservation Alteration Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Reservation Alteration
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationAlteration extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reservation_alteration';

    public $timestamps = false;
}
