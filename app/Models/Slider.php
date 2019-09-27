<?php

/**
 * Slider Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Slider
 * @author      Trioangle Product Team
 * @version     0.8
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'slider';

    public $timestamps = false;

    public $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        $photo_src=explode('.',$this->attributes['image']);
        if(count($photo_src)>1)
        {
            return url('/').'/images/slider/'.$this->attributes['image'];
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
