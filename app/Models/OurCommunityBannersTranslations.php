<?php

/**
 * Our Community Banners Translations Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Our Community Banners Translations
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurCommunityBannersTranslations extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description'];

    public function language() {
    	return $this->belongsTo('App\Models\Language','locale','value');
    }    
}
