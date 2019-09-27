<?php

/**
 * Admin Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Admin
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use DB;

class Admin extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    use EntrustUserTrait { restore as private restoreA; }
    use SoftDeletes { restore as private restoreB; }

    public function restore()
    {
        $this->restoreA();
        $this->restoreB();
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['deleted_at'];

    // Update Admin Role
    public static function update_role($user_id, $role_id)
    {
        return DB::table('role_user')->where('user_id', $user_id)->update(['role_id' => $role_id]);
    }
}
