<?php

/**
 * HostExperienceProvides Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    HostExperienceProvides
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostExperienceProvides extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_experience_provides';

    public $timestamps = false;

    public function newQuery($ordered = true)
    {
        $query = parent::newQuery();

        if (empty($ordered)) {
            return $query;
        }

        return $query->orderBy('host_experience_provide_item_id', 'asc');
    }

    public function provide_item()
    {
    	return $this->belongsTo('App\Models\HostExperienceProvideItems','host_experience_provide_item_id','id');
    }
}
