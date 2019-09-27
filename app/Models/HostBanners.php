<?php

/**
 * Host Banners Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Host Banners
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;

class HostBanners extends Model
{
    use Translatable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_banners';

    public $timestamps = false;

    public $appends = ['image_url', 'link_url'];

    public $translatedAttributes = ['title', 'description', 'link_title'];

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
            return $src = url('/').'/images/host_banners/'.$this->attributes['image'];
        }
        else
        {
            $options['secure']=TRUE;
            // $options['width']=1500;
            // $options['height']=800;
            $options['crop']    = 'fill';
            return $src=\Cloudder::show($this->attributes['image'],$options);
        }
    }

    public function getLinkUrlAttribute()
    {
        return url('/').$this->attributes['link'];
    }
    
}
