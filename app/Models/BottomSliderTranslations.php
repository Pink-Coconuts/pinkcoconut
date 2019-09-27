<?php

/**
 * Bottom Slider Translations Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Bottom Slider Translations
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BottomSliderTranslations extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];

    public function language() {
    	return $this->belongsTo('App\Models\Language','locale','value');
    }    
}
