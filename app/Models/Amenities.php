<?php

/**
 * Amenities Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Amenities
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use session;
use Request;
class Amenities extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'amenities';

    public $timestamps = false;
    protected $appends = ['image_name'];



    // Get all Active status records
    public static function active_all()
    {
    	return Amenities::whereStatus('Active')->get();
    }

    public function amenities_type()
    {
        return $this->belongsTo('App\Models\AmenitiesType', 'type_id', 'id');
    }

    public function scopeActive($query)
    {
        $query = $query->whereStatus('Active');
        return $query;
    }

    public function scopeActiveType($query)
    {
        $query = $query->with(['amenities_type'])->whereHas('amenities_type', function($query){
            $query->where('status', 'Active');
        });

        return $query;
    }

    // Get Selected All Amenities Data
    public static function selected($room_id)
    {

        $lang = Language::whereValue((Session::get('language')) ? Session::get('language') : @$default_lang)->first()->value;



      if($lang=="en"){


        $result = DB::select("select amenities.name as name,amenities.type_id,amenities.id as id,amenities.status, amenities.icon, rooms.id as status from amenities right join rooms on find_in_set(amenities.id, rooms.amenities) and rooms.id = $room_id left join amenities_type on amenities.type_id = amenities_type.id where  type_id !=4 and amenities.status='Active' and amenities_type.status = 'Active'");
      }
      else{

       
        $result = DB::select("select amenities.name as name,amenities.type_id,amenities.id as id,amenities.status, amenities.icon, rooms.id as status,(select amenities_lang.name from amenities_lang where amenities_lang.amenities_id = amenities.id and lang_code='$lang') as namelang from amenities right join rooms on find_in_set(amenities.id, rooms.amenities) and rooms.id = $room_id left join amenities_type on amenities.type_id = amenities_type.id where  type_id !=4 and amenities.status='Active' and amenities_type.status = 'Active'");
          
          


          }
        return $result;
    }

    // Get Selected Security Amenities Data
    public static function selected_security($room_id)
    {
        $lang = Language::whereValue((Session::get('language')) ? Session::get('language') : @$default_lang)->first()->value;

        if($lang=="en"){
            $result = DB::select("select amenities.name as name,amenities.type_id,amenities.id as id,amenities.status, amenities.icon, rooms.id as status from amenities right join rooms on find_in_set(amenities.id, rooms.amenities) and rooms.id = $room_id left join amenities_type on amenities.type_id = amenities_type.id where type_id = 4 and amenities.status='Active' and amenities_type.status = 'Active'");
        }
        else{       
            $result = DB::select("select amenities.name as name,amenities.type_id,amenities.id as id,amenities.status, amenities.icon, rooms.id as status ,(select amenities_lang.name from amenities_lang where amenities_lang.amenities_id = amenities.id and lang_code='$lang') as namelang from amenities right join rooms on find_in_set(amenities.id, rooms.amenities) and rooms.id = $room_id left join amenities_type on amenities.type_id = amenities_type.id where type_id = 4 and amenities.status='Active' and amenities_type.status = 'Active'");
        }
        return $result;
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
            $name = @AmenitiesLang::where('amenities_id', $this->attributes['id'])->where('lang_code', $lang)->first()->name;
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
            $name = @AmenitiesLang::where('amenities_id', $this->attributes['id'])->where('lang_code', $lang)->first()->description;
            if($name)
                return $name;
            else
                return $this->attributes['description'];
        }
    }
    // Get Image Name Attribute
    public function getImageNameAttribute() {
        $site_settings_url = @SiteSettings::where('name', 'site_url')->first()->value;
        $url = \App::runningInConsole() ? $site_settings_url : url('/');
        @$photo_src = explode('.', $this->attributes['icon']);

        if (count($photo_src) > 1) {

            $name = $this->attributes['icon'];
            return $url . '/images/amenities/' . $name;

        } else {
            $options['secure'] = TRUE;
            /*$options['width'] = 450;
            $options['height'] = 250;*/
            $options['crop'] = 'fill';
            return $src = \Cloudder::show($this->attributes['icon'], $options);
        }

    }



}
