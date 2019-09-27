<?php

/**
 * Our Community Banners Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Our Community Banners
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
use Session;

class OurCommunityBanners extends Model
{
    use Translatable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'our_community_banners';

    public $timestamps = false;

    public $appends = ['image_url'];

    public $translatedAttributes = ['title', 'description'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        if(Request::segment(1) == ADMIN_URL) {
            $this->defaultLocale = 'en';
        }
        else {
            $this->defaultLocale = Session::get('language');
        }
    }

    public function getImageUrlAttribute()
    {
        $photo_src=explode('.',$this->attributes['image']);
        if(count($photo_src)>1)
        {
            return url('/').'/images/our_community_banners/'.$this->attributes['image'];
        }
        else
        {
            $options['secure']=TRUE;
            // $options['width']=800;
            // $options['height']=1500;
        	$options['crop']    = 'fill';
            return $src=\Cloudder::show($this->attributes['image'],$options);
        }
    }

    public static function active_all(){
        return OurCommunityBanners::whereStatus('Active')->get();
    }
}
