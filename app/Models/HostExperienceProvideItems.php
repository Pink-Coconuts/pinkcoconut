<?php

/**
 * HostExperienceProvideItems Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    HostExperienceProvideItems
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostExperienceProvideItems extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_experience_provide_items';

    public $timestamps = true;

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        $url = '';
        if($this->attributes['image'])
        {
            $photo_src=explode('.',$this->attributes['image']);
            if(count($photo_src)>1)
            {
                $url = url('images/host_experiences/provide_items/'.$this->attributes['image']);
            }
            else
            {
                $options['secure']=TRUE;
                $options['width']=16;
                $options['height']=19;
                $url =\Cloudder::show($this->attributes['image'],$options);
            }
        }
        return $url;
    }
    
    public function scopeActive($query)
    {
    	$query = $query->where('status', 'Active');
    	return $query;
    }
}
