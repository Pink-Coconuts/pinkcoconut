<?php

/**
 * Home Cities Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Home Cities
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;

class HomeCities extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'home_cities';

    public $timestamps = false;

    public $appends = ['image_url','search_url'];

    public function getImageUrlAttribute()
    {
        $photo_src=explode('.',$this->attributes['image']);
        if(count($photo_src)>1)
        {
            return $src = url('/').'/images/home_cities/'.$this->attributes['image'];
        }
        else
        {
            $options['secure']=TRUE;
            // $options['width']=1300;
            // $options['height']=600;
            $options['crop']    = 'fill';
            return $src=\Cloudder::show($this->attributes['image'],$options);
        }
    }
    public function getSearchUrlAttribute()
    {
               return url('/s?location='.$this->attributes['name'].'&source=ds');
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
            $name = @HomeCitiesLang::where('home_cities_id', $this->attributes['id'])->where('lang_code', $lang)->first()->name;
            if($name)
                return $name;
            else
                return $this->attributes['name'];
        }
    }
}
