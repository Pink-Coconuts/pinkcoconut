<?php

/**
 * HostExperiencePhotos Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    HostExperiencePhotos
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostExperiencePhotos extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_experience_photos';

    public $timestamps = false;

    protected $appends = ['image_url','og_image'];

    public function getImageUrlAttribute()
    {
        $url = '';
        $filename = @$this->attributes['name'];
        if($filename)
        {
            $photo_src=explode('.',$filename);
            if(count($photo_src)>1)
            {
                $url = url('images/host_experiences/'.$this->attributes['host_experience_id'].'/'.$filename);
            }
            else
            {
                $options['secure']=TRUE;
                $options['width']=480;
                $options['height']=720;
                $url =\Cloudder::show($filename,$options);
            }
        }
    	return $url;
    }

    public function getOgImageAttribute()
    {
        $url = '';
        $filename = $this->attributes['name'];
        $picture_details = pathinfo($this->attributes['name']);

        $url = '';
        $filename = @$this->attributes['name'];
        if($filename)
        {
            $photo_src=explode('.',$filename);
            if(count($photo_src)>1)
            {
                $url = url('images/host_experiences/'.$this->attributes['host_experience_id'].'/'.@$picture_details['filename'].'_853x1280.'.@$picture_details['extension']);
                if(!file_exists($url))
                {
                    $url = url('images/host_experiences/'.$this->attributes['host_experience_id'].'/'.$filename);
                }
            }
            else
            {
                $options['secure']=TRUE;
                $options['width']=853;
                $options['height']=1280;
                $url =\Cloudder::show($filename,$options);
            }
        }
        return $url;
    }
}
