<?php

/**
 * Metas Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Metas
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
use Session;

class Metas extends Model
{
	use Translatable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'metas';

    public $timestamps = false;

    public $translatedAttributes = ['title', 'description', 'keywords'];

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
    
    public static function active_all(){
        return Metas::whereStatus('Active')->get();
    }
}
