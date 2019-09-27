<?php
/**
 * DisputeDocuments Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    DisputeDocuments
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisputeDocuments extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dispute_documents';

    //public $timestamps = false;

    public function getFileUrlAttribute()
    {
    	$photo_src=explode('.',$this->attributes['file']);
        if(count($photo_src)>1)
        {
            $name = $this->attributes['file'];
            return url('/').'/images/disputes/'.$this->attributes['dispute_id'].'/'.$name;
        }
        else
        {
            $options['secure']=TRUE;
            $options['crop']='fill';
            return $src=\Cloudder::show($this->attributes['file'],$options);
        }
    }
}

