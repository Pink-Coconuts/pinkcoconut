<?php

/**
 * Amenities Type Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Amenities Type
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;
class AmenitiesType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'amenities_type';

    public $timestamps = false;

    // Get all Active status records
    public static function active_all()
    {
    	return AmenitiesType::whereStatus('Active')->get();
    }
    public function getNameAttribute()
    {

        if(Request::segment(1)==ADMIN_URL){ 

        return $this->attributes['name'];

        }
        $default_lang = Language::where('default_language',1)->first()->value;

        $lang = Language::whereValue((Session::get('language')) ? Session::get('language') : $default_lang)->first()->value;

        if($lang == 'en')
            return $this->attributes['name'];
        else {
            $name = @AmenitiesTypeLang::where('amenities_type_id', $this->attributes['id'])->where('lang_code', $lang)->first()->name;
            if($name)
                return $name;
            else
                return $this->attributes['name'];
        }
    }
   
    public function getDescriptionAttribute()
    {
        if(Request::segment(1)==ADMIN_URL){ 

   return $this->attributes['description'];

    }
        $default_lang = Language::where('default_language',1)->first()->value;

        $lang = Language::whereValue((Session::get('language')) ? Session::get('language') : $default_lang)->first()->value;

        if($lang == 'en')
            return $this->attributes['description'];
        else {
            $name = @AmenitiesTypeLang::where('amenities_type_id', $this->attributes['id'])->where('lang_code', $lang)->first()->description;
            if($name)
                return $name;
            else
                return $this->attributes['description'];
        }
    }
}
