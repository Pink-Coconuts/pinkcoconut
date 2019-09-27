<?php

/**
 * RoomsPriceRules Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    RoomsPriceRules
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use App\Models\Currency;
use Session;

class RoomsPriceRules extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms_price_rules';

    protected $appends = ['period_text'];

    protected $fillable = ['period', 'discount'];

    public $timestamps = false;

    public function scopeType($query, $type) {
      $query = $query->where('type', $type);
      return $query;
    }

    public function getPeriodTextAttribute() {
        $period_text = '';
        if($this->attributes['type'] == 'length_of_stay') {
            $length_of_stay_options = Rooms::getLenghtOfStayOptions(true);
            $option = $length_of_stay_options[$this->attributes['period']];
            if($option) {
                $period_text = $option['text'];
            }
        }
        return $period_text;
    }

}
