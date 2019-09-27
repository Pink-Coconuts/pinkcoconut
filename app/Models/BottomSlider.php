<?php

/**
 * Bottom Slider Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Bottom Slider
 * @author      Trioangle Product Team
 * @version     0.8
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
use Session;

class BottomSlider extends Model
{
    use Translatable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bottom_slider';

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
            return $src = url('/').'/images/bottom_slider/'.$this->attributes['image'];
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
}
