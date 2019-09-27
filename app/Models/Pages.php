<?php

/**
 * Pages Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Pages
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;


class Pages extends Model
{
	use Translatable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';

    public $translatedAttributes = ['name', 'content'];

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

    public function getUpdatedAtAttribute(){
        return date(PHP_DATE_FORMAT.' H:i:s',strtotime($this->attributes['updated_at']));
    }
}
