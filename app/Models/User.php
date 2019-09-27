<?php

/**
 * User Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    User
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
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Messages;
use App\Models\Language;
use DateTime;
use Session;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'dob'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['dob_dmy','age','full_name', 'primary_phone_number_protected', 'primary_phone_number', 'languages_name','user_currency_code','since','user_email_language'];

    protected $dates = ['deleted_at'];

    public function setFirstNameAttribute($input){
         $this->attributes['first_name'] = strip_tags($input);
    }
    public function setLastNameAttribute($input){
         $this->attributes['last_name'] = strip_tags($input);
    }

      public function getUserEmailLanguageAttribute(){

        if(@$this->attributes['email_language']){
            return @$this->attributes['email_language'];
        }
        else{
            return Language::active()->where('default_language','1')->first()->value;
        }
    }


    // Join with profile_picture table
    public function profile_picture()
    {
        return $this->belongsTo('App\Models\ProfilePicture','id','user_id');
    }
    // get date of birth
    public function getDobArrayAttribute(){
        $dob_array = explode('-', @$this->attributes['dob']);
        return $dob_array;
    }
    // Join with users_verification table
    public function users_verification()
    {
        return $this->belongsTo('App\Models\UsersVerification','id','user_id');
    }

    // Join with users_phone_numbers table
    public function users_phone_numbers()
    {
        return $this->hasMany('App\Models\UsersPhoneNumbers','user_id','id');
    }

    // Join with saved_wishlists table
    public function saved_wishlists()
    {
        return $this->belongsTo('App\Models\SavedWishlists','id','user_id');
    }

    // Join with wishlists table
    public function wishlists()
    {
        return $this->belongsTo('App\Models\Wishlists','id','user_id');
    }

    // Join with referrals table
    public function referrals()
    {
        return $this->belongsTo('App\Models\Referrals','id','user_id');
    }

    // Inbox unread message count
    public function inbox_count()
    {
        return Messages::where('user_to', $this->attributes['id'])->where('read', '0')->where('archive','0')->groupby('reservation_id')->get()->count();
    }

    // Join with reviews table
    public function reviews()
    {
        return $this->hasMany('App\Models\Reviews','user_to','id');
    }

    // Get status Active users count
    public static function count()
    {
        return DB::table('users')->whereStatus('Active')->count();
    }

    // Convert y-m-d date of birth date into d-m-y
    public function getDobDmyAttribute()
    {
        if(@$this->attributes['dob'] != '0000-00-00')
            return date(PHP_DATE_FORMAT, strtotime(@$this->attributes['dob']));
        else
            return '';
    }

    public function getDobAttribute()
    {
        if(@$this->attributes['dob'] != '0000-00-00')
            return date(PHP_DATE_FORMAT, strtotime(@$this->attributes['dob']));
        else
            return '';
    }

    public function getAgeAttribute()
    {
        $dob = @$this->attributes['dob'];
        if(!empty($dob) && $dob != '0000-00-00')
        {
            $birthdate = new DateTime($dob);
            $today   = new DateTime('today');
            $age = $birthdate->diff($today)->y;
            return $age;
        }
        else
        {
            return 0;
        }
    }

    public function getPrimaryPhoneNumberProtectedAttribute(){
        $primary_phone_number_protected = ''; 
        $users_phone_numbers = UsersPhoneNumbers::where('user_id', $this->attributes['id'])->where('status', 'Confirmed')->first(); 
        return @$users_phone_numbers->phone_number_protected;
    }

    public function getPrimaryPhoneNumberAttribute(){
        $primary_phone_number = ''; 
        $users_phone_numbers = UsersPhoneNumbers::where('user_id', $this->attributes['id'])->where('status', 'Confirmed')->first(); 
        return @$users_phone_numbers->phone_number_full;
    }

    public function getSinceAttribute()
    {
        return date('Y', strtotime($this->attributes['created_at']));
    }

    public function getFullNameAttribute()
    {
        return ucfirst(@$this->attributes['first_name']).' '.ucfirst(@$this->attributes['last_name']);
    }

    public function getFirstNameAttribute()
    {
        return ucfirst($this->attributes['first_name']);
    }

    public function getLastNameAttribute()
    {
        return ucfirst($this->attributes['last_name']);
    }

    // get user currency code 
    public function getUserCurrencyCodeAttribute()
    {        
        if(@$this->attributes['currency_code']!=null){
            return @$this->attributes['currency_code'];
        }
        else{
            return DB::table('currency')->where('default_currency', 1)->first()->code;
        }
    }

    // get user currency code 
    public function getCurrencyCodeAttribute()
    {        
        if(@$this->attributes['currency_code']!=null){
            return @$this->attributes['currency_code'];
        }
        else{
            return DB::table('currency')->where('default_currency', 1)->first()->code;
        }
    }

    public static function user_facebook_authenticate($email, $fb_id){
        $user = User::where(function($query) use($email, $fb_id){
            $query->where('email', $email)->orWhere('fb_id', $fb_id);
        });
        return $user;
    }

    public static function clearUserSession($user_id){
        $session_id = Session::getId();

        $sessions = DB::table('sessions')->where('user_id', $user_id)->where('id', '!=', $session_id)->delete();

        $current_session = DB::table('sessions')->where('id', $session_id)->first();
        if($current_session){
            $current_session_data = unserialize(base64_decode($current_session->payload));
            foreach ($current_session_data as $key => $value) {
                if('login_user_' == substr($key, 0, 11)){
                    if(Session::get($key) == $user_id){
                        Session::forget($key);
                        Session::save(); 
                        DB::table('sessions')->where('id', $session_id)->update(array('user_id' => NULL));;
                    }
                }
            }
        }
        return true;
    }

    public function getLanguagesNameAttribute()
    {
        $languages = explode(',', $this->attributes['languages']);
        $languages_name = '';
        if($this->attributes['languages']) {
        foreach($languages as $row) {
                $languages = Language::find($row);
                if($languages){
                    $languages_name .= $languages->name.',';
                }
        }
        }
        return rtrim($languages_name,',');
    }

    public function getCreatedAtAttribute(){
        return date(PHP_DATE_FORMAT.' H:i:s',strtotime($this->attributes['created_at']));
    }

    public function getUpdatedAtAttribute(){
        if($this->attributes['updated_at']=="0000-00-00 00:00:00")
        {
            return date(PHP_DATE_FORMAT.' H:i:s',strtotime($this->attributes['created_at']));
        }
        else
        {
            return date(PHP_DATE_FORMAT.' H:i:s',strtotime($this->attributes['updated_at']));    
        }
        
    }

    public function dispute_messages()
    {
        return DisputeMessages::userReceived($this->attributes['id'])->unread()->groupby('dispute_id')->get();
    }

    public function getDisputeMessagesCountAttribute()
    {
        return $this->dispute_messages()->count();
    }

    public function getVerificationStatusAttribute(){
        $id_documents = @UsersVerificationDocuments::whereType('id_document')->where('user_id', $this->attributes['id'])->get()->count();
        // dd($id_documents);
        if($id_documents > 0){
            $status = UsersVerificationDocuments::where('user_id', $this->attributes['id'])->first();
            $verification_status = $status->user_verification_status;
        }
        else{
            $verification_status = 'Connect';
        }
        return $verification_status;
    }

    // Get Verification status of the user
    public function getOriginalVerificationStatusAttribute(){
        return $this->attributes['verification_status'];
    }

    // Get ID Verification status of the User
    public function getIdDocumentVerificationStatusAttribute(){
        $id_documents = @UsersVerificationDocuments::whereType('id_document')->where('user_id', $this->attributes['id'])->first();
        if($id_documents != '')
            $verification_status = $id_documents->id_document_verification_status;
        else
            $verification_status = '';
        return $verification_status;
    }

    // Get Resubmit message from admin for ID document Verification
    public function getIdResubmitReasonAttribute(){
        $reason = Messages::where('user_to',$this->attributes['id'])->where('user_from',$this->attributes['id'])->where('message_type',13)->orderBy('id', 'desc')->first();
        if($reason != null){
            $reason_message = $reason->message;
        }
        else{
            $reason_message = '';
        }
        return $reason_message;
    }

}
