<?php

/**
 * User Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    User
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Start\Helpers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Helper\FacebookHelper;
use Auth;
use Validator;
use App\Models\User;
use App\Models\ProfilePicture;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Timezone;
use App\Models\PasswordResets;
use App\Models\Messages;
use App\Models\PayoutPreferences;
use App\Models\Rooms;
use App\Models\Payouts;
use App\Models\Reviews;
use App\Models\Reservation;
use App\Models\UsersVerification;
use App\Models\Wishlists;
use App\Models\ReferralSettings;
use App\Models\Referrals;
use App\Models\SessionModel;
use App\Models\UsersPhoneNumbers;
use App\Models\Language;
use App\Models\PaymentGateway;
use App\Models\HostExperiences;
use App\Models\UsersVerificationDocuments;
use Socialite;  // This package have all social media API integration
use Mail;
use DateTime;
use Hash;
use Excel;
use DB;
use Image;
use Session;
use File;
use Google_Client;
use App\Http\Controllers\EmailController;

// Facebook API
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Http\Controllers\Auth\PasswordController;
use Http\Controllers\Auth\AuthController;

class UserController extends Controller
{
    protected $helper; // Global variable for Helpers instance
    private $fb;    // Global variable for FacebookHelper instance
    
    public function __construct(FacebookHelper $fb)
    {
        $this->fb = $fb;
        $this->helper = new Helpers;
    }
    /**
     * Facebook User Registration and Login
     *
     * @return redirect     to dashboard page
     */
    public function facebookAuthenticate(EmailController $email_controller, Request $request)
    {
        if($request->error_code == 200){
            // $this->helper->flash_message('danger', $request->error_description); // Call flash message function
            return redirect('login'); // Redirect to login page
        }

        $this->fb->generateSessionFromRedirect(); // Generate Access Token Session After Redirect from Facebook

        $response = $this->fb->getData(); // Get Facebook Response

        if($response == 'Failed')
        {
            return redirect('login');
        }
        
        $userNode = $response->getGraphUser(); // Get Authenticated User Data
        
        // $email = ($userNode->getProperty('email') == '') ? $userNode->getId().'@fb.com' : $userNode->getProperty('email');
        $email = $userNode->getProperty('email');
        $fb_id = $userNode->getId(); 

        $user = User::user_facebook_authenticate($email, $fb_id); // Check Facebook User Email Id is exists

        if($user->count() > 0 )  // If there update Facebook Id
        {
            $user = User::user_facebook_authenticate($email, $fb_id)->first();

            $user->fb_id  = $userNode->getId();

            $user->save();  // Update a Facebook id

            $user_id = $user->id; // Get Last Updated Id
        }
        else // If not create a new user without Password
        {
            $user = User::user_facebook_authenticate($email, $fb_id)->withTrashed();

            if($user->count() > 0)
            {
                /*$data['title'] = 'Disabled ';
                return view('users.disabled', $data);*/
                return redirect('user_disabled');
            }

            $user = new User;

            // New user data
            $user->first_name   =   $userNode->getFirstName();
            $user->last_name    =   $userNode->getLastName();
            $user->email        =   $email;
            $user->fb_id        =   $userNode->getId();

            if($email == ''){
                $user = array(
                    'first_name'   =>   $userNode->getFirstName(),
                    'last_name'    =>   $userNode->getLastName(),
                    'email'        =>   $email,
                    'fb_id'        =>   $userNode->getId(),
                );
                Session::put('fb_user_data', $user); 
                return redirect('users/signup_email'); 
            }
            $user->status = 'Active';//user activated
            $user->save(); // Create a new user

            $user_id = $user->id; // Get Last Insert Id

            $user_pic = new ProfilePicture;

            $user_pic->user_id      =   $user_id;
            $user_pic->src          =   "https://graph.facebook.com/".$userNode->getId()."/picture?type=large";
            $user_pic->photo_source =   'Facebook';

            $user_pic->save(); // Save Facebook profile picture


            $user_verification = new UsersVerification;

            $user_verification->user_id      =   $user->id;
            
            $user_verification->facebook      =  'yes';

            $user_verification->save();  // Create a users verification record

            $email_controller->welcome_email_confirmation($user);

            if(Session::get('referral')) {
                $referral_settings = ReferralSettings::first();

                $referral_check = Referrals::whereUserId(Session::get('referral'))->sum('creditable_amount');

                // if($referral_check < $referral_settings->value(1)) {
                    $referral = new Referrals;

                    $referral->user_id                = Session::get('referral');
                    $referral->friend_id              = $user->id;
                    $referral->friend_credited_amount = $referral_settings->value(4);
                    $referral->if_friend_guest_amount = ($referral_check < $referral_settings->value(1)) ? $referral_settings->value(2) : 0;
                    $referral->if_friend_host_amount  = ($referral_check < $referral_settings->value(1)) ? $referral_settings->value(3) : 0;
                    $referral->creditable_amount      = ($referral_check < $referral_settings->value(1)) ? ($referral_settings->value(2) + $referral_settings->value(3)) : 0;
                    $referral->currency_code          = $referral_settings->value(5, 'code');

                    $referral->save();

                    Session::forget('referral');
                // }
            }
        }

        $users = User::where('id', $user_id)->first();
        
        if(@$users->status != 'Inactive')
        {
            if(Auth::guard()->loginUsingId($user_id)) // Login without using User Id instead of Email and Password
            {
                 if(Session::get('ajax_redirect_url'))
                return redirect()->intended(Session::get('ajax_redirect_url')); // Redirect to ajax url 
                else
                return redirect()->intended('dashboard'); // Redirect to dashboard page
            }
            else
            {
                $this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
                return redirect('login'); // Redirect to login page
            }
        }
        else // Call Disabled view file for Inactive user
        {
            /*$data['title'] = 'Disabled ';
            return view('users.disabled', $data);*/
            return redirect('user_disabled');
        } 
    }

    public function signup_email(){
        $fb_user_data = Session::get('fb_user_data'); 
        $linkedin_user_data = Session::get('linkedin_user_data');

        if(!$fb_user_data && !$linkedin_user_data){
            return redirect('signup_login');
        }
        $data['user'] = ($fb_user_data)?$fb_user_data:$linkedin_user_data; 
        $data['title'] = 'Log In / Sign Up'; 
        return view('home/signup_email', $data); 
    }

    public function finish_signup_email(Request $request, EmailController $email_controller){
        // Email signup validation rules
         $rules = array(
            'first_name'      => 'required|max:255',
            'last_name'       => 'required|max:255',
            'email'           => 'required|max:255|email|unique:users',
            'birthday_day'    => 'required',
            'birthday_month'  => 'required',
            'birthday_year'   => 'required',
        // 'agree_tac'       => 'required',  
        );

        // Email signup validation custom messages
        $messages = array(
            'required'                => ':attribute '.trans('messages.login.field_is_required').'', 
            'birthday_day.required'   => trans('messages.profile.birth_date_required'),
            'birthday_month.required' => trans('messages.profile.birth_date_required'),
            'birthday_year.required'  => trans('messages.profile.birth_date_required'),
        );

        // Email signup validation custom Fields name
        $niceNames = array(
            'first_name'      => trans('messages.login.first_name'),
            'last_name'       => trans('messages.login.last_name'),
            'email'           => trans('messages.login.email'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames); 
        $date_check="";
        if(@$request->birthday_month!='' && @$request->birthday_day!='' && @$request->birthday_year!='')
        $date_check=checkdate($request->birthday_month,$request->birthday_day,$request->birthday_year);
        
        if ($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
        }
        else
        {
            if($date_check != "true")
             return back()->withErrors(['birthday_day' => trans('messages.login.invalid_dob'), 'birthday_month' => trans('messages.login.invalid_dob'), 'birthday_year' => trans('messages.login.invalid_dob')])->withInput();
         
            if(time() < strtotime($request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day)){
                return back()->withErrors(['birthday_day' => trans('messages.login.invalid_dob'), 'birthday_month' => trans('messages.login.invalid_dob'), 'birthday_year' => trans('messages.login.invalid_dob')])->withInput(); // Form calling with Errors and Input values
            }
            $from = new DateTime($request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day);
            $to   = new DateTime('today');
            $age = $from->diff($to)->y; 
            if($age < 18){
                return back()->withErrors(['birthday_day' => trans('messages.login.below_age'), 'birthday_month' => trans('messages.login.below_age'), 'birthday_year' =>trans('messages.login.below_age')])->withInput(); // Form calling with Errors and Input values
            }

            $user = new User;

            $user->first_name   =   $request->first_name;
            $user->last_name    =   $request->last_name;
            $user->email        =   $request->email;
            $user->fb_id        =   $request->fb_id;
            $user->dob          =   $request->birthday_year.'-'.
                                    $request->birthday_month.'-'.
                                    $request->birthday_day; // Date format - Y-m-d
            $user->status        =   'Active';
            $user->save();  // Create a new user

            $user_pic = new ProfilePicture;

            $user_pic->user_id      =   $user->id;
            $user_pic->src          =   "https://graph.facebook.com/".$request->fb_id."/picture?type=large";
            $user_pic->photo_source =   'Facebook';

            $user_pic->save();  // Create a profile picture record

            $user_verification = new UsersVerification;

            $user_verification->user_id      =   $user->id;
            $user_verification->facebook     =   'yes';
            $user_verification->fb_id        =   $request->fb_id;

            $user_verification->save();  // Create a users verification record

            $email_controller->welcome_email_confirmation($user);

            if(Session::get('referral')) {

            $referral_settings = ReferralSettings::first();

            $referral_check = Referrals::whereUserId(Session::get('referral'))->get()->sum('creditable_amount');
                // if($referral_check < $referral_settings->value(1)) {
                    $referral = new Referrals;

                    $referral->user_id                = Session::get('referral');
                    $referral->friend_id              = $user->id;
                    $referral->friend_credited_amount = $referral_settings->value(4);
                    $referral->if_friend_guest_amount = ($referral_check < $referral_settings->value(1)) ? $referral_settings->value(2) : 0;
                    $referral->if_friend_host_amount  = ($referral_check < $referral_settings->value(1)) ? $referral_settings->value(3) : 0;
                    $referral->creditable_amount      = ($referral_check < $referral_settings->value(1)) ? ($referral_settings->value(2) + $referral_settings->value(3)) : 0;
                    $referral->currency_code          = $referral_settings->value(5, 'code');

                    $referral->save();

                    Session::forget('referral');
                // }
            }

            Session::forget('fb_user_data');

            if(Auth::guard()->loginUsingId($user->id))
            {
                $this->helper->flash_message('success', trans('messages.login.reg_successfully')); // Call flash message function
                 if(Session::get('ajax_redirect_url'))
                return redirect()->intended(Session::get('ajax_redirect_url')); // Redirect to ajax url 
                else
                return redirect()->intended('dashboard'); // Redirect to dashboard page
            }
            else
            {
                $this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
                return redirect('login'); // Redirect to login page
            }
        }
    }

    public function finish_signup_linkedin_email(Request $request, EmailController $email_controller){
        // Email signup validation rules
         $rules = array(
            'first_name'      => 'required|max:255',
            'last_name'       => 'required|max:255',
            'email'           => 'required|max:255|email|unique:users',
            'birthday_day'    => 'required',
            'birthday_month'  => 'required',
            'birthday_year'   => 'required',
        // 'agree_tac'       => 'required',  
        );

        // Email signup validation custom messages
        $messages = array(
            'required'                => ':attribute '.trans('messages.login.field_is_required').'', 
            'birthday_day.required'   => trans('messages.profile.birth_date_required'),
            'birthday_month.required' => trans('messages.profile.birth_date_required'),
            'birthday_year.required'  => trans('messages.profile.birth_date_required'),
        );

        // Email signup validation custom Fields name
        $niceNames = array(
            'first_name'      => trans('messages.login.first_name'),
            'last_name'       => trans('messages.login.last_name'),
            'email'           => trans('messages.login.email'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames); 
        $date_check="";
        if(@$request->birthday_month!='' && @$request->birthday_day!='' && @$request->birthday_year!='')
        $date_check=checkdate($request->birthday_month,$request->birthday_day,$request->birthday_year);
        
        if ($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
        }
        else
        {
            if($date_check != "true")
             return back()->withErrors(['birthday_day' => trans('messages.login.invalid_dob'), 'birthday_month' => trans('messages.login.invalid_dob'), 'birthday_year' => trans('messages.login.invalid_dob')])->withInput();
         
            if(time() < strtotime($request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day)){
                return back()->withErrors(['birthday_day' => trans('messages.login.invalid_dob'), 'birthday_month' => trans('messages.login.invalid_dob'), 'birthday_year' => trans('messages.login.invalid_dob')])->withInput(); // Form calling with Errors and Input values
            }
            $from = new DateTime($request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day);
            $to   = new DateTime('today');
            $age = $from->diff($to)->y; 
            if($age < 18){
                return back()->withErrors(['birthday_day' => trans('messages.login.below_age'), 'birthday_month' => trans('messages.login.below_age'), 'birthday_year' =>trans('messages.login.below_age')])->withInput(); // Form calling with Errors and Input values
            }

            $new_user = new User;
            // New user data
            $new_user->first_name   =   $request->first_name;
            $new_user->last_name    =   $request->lastName;
            $new_user->email        =   $request->email;
            $new_user->linkedin_id  =   $request->linkedin_id;
            $new_user->status       =   "Active" ;

            $new_user->save(); // Create a new user

            $user_id = $new_user->id; // Get Last Insert Id

            $user_pic = new ProfilePicture;

            $user_pic->user_id      =   $user_id;
            $user_pic->src          =   $request->profile_pic;
            $user_pic->photo_source =   'LinkedIn';

            $user_pic->save(); // Save Google profile picture

            $user_verification = new UsersVerification;

            $user_verification->user_id      =   $user_id;
            $user_verification->linkedin     =  'yes';

            $user_verification->save();  // Create a users verification record

            $email_controller->welcome_email_confirmation($new_user);

            if(Session::get('referral')) 
            {
                
            $referral_settings = ReferralSettings::first();

            $referral_check = Referrals::whereUserId(Session::get('referral'))->sum('creditable_amount');

            if($referral_check < $referral_settings->value(1)) 
                {
                    $referral = new Referrals;

                    $referral->user_id                = Session::get('referral');
                    $referral->friend_id              = $user_id;
                    $referral->friend_credited_amount = $referral_settings->value(4);
                    $referral->if_friend_guest_amount = $referral_settings->value(2);
                    $referral->if_friend_host_amount  = $referral_settings->value(3);
                    $referral->creditable_amount      = $referral_settings->value(2) + $referral_settings->value(3);
                    $referral->currency_code          = $referral_settings->value(5, 'code');

                    $referral->save();

                    Session::forget('referral');
                 }
            }

            if(Auth::guard()->loginUsingId($new_user->id))
            {
                $this->helper->flash_message('success', trans('messages.login.reg_successfully')); // Call flash message function
                 if(Session::get('ajax_redirect_url'))
                return redirect()->intended(Session::get('ajax_redirect_url')); // Redirect to ajax url 
                else
                return redirect()->intended('dashboard'); // Redirect to dashboard page
            }
            else
            {
                $this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
                return redirect('login'); // Redirect to login page
            }
        }
    }

    /**
     * Create a new Email signup user
     *
     * @param array $request    Post method inputs
     * @return redirect     to dashboard page
     */
    public function create(Request $request, EmailController $email_controller)
    {
        // Email signup validation rules
         $rules = array(
        'first_name'      => 'required|max:255',
        'last_name'       => 'required|max:255',
        'email'           => 'required|max:255|email|unique:users',
        'password'        => 'required|min:6',
        'birthday_day'    => 'required',
        'birthday_month'  => 'required',
        'birthday_year'   => 'required',
        // 'agree_tac'       => 'required',  
        );

        // Email signup validation custom messages
        $messages = array(
        //'required'                => ':attribute is required.',
        // 'birthday_day.required'   => 'Select your birth date to continue.',
        // 'birthday_month.required' => 'Select your birth date to continue.',
        // 'birthday_year.required'  => 'Select your birth date to continue.',
        );

        // Email signup validation custom Fields name
        $niceNames = array(
        'first_name'      => trans('messages.login.first_name'),
        'last_name'       => trans('messages.login.last_name'),
        'email'           => trans('messages.login.email_address'),
        'password'        => trans('messages.login.password'),
        'birthday_month'  => trans('messages.login.birthday').' '.trans('messages.header.month'),
        'birthday_day'    => trans('messages.login.birthday').' '.trans('messages.header.day'),
        'birthday_year'   => trans('messages.login.birthday').' '.trans('messages.header.year'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames); 
        $date_check="";
        if(@$request->birthday_month!='' && @$request->birthday_day!='' && @$request->birthday_year!='')
        $date_check=checkdate($request->birthday_month,$request->birthday_day,$request->birthday_year);
        

        if ($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput()->with('error_code', 1); // Form calling with Errors and Input values
        }
        else
        {
            if($date_check != "true")
             return back()->withErrors(['birthday_day' => trans('messages.login.invalid_dob'), 'birthday_month' => trans('messages.login.invalid_dob'), 'birthday_year' => trans('messages.login.invalid_dob')])->withInput()->with('error_code', 1);
         
            if(time() < strtotime($request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day)){
                // $this->helper->flash_message('danger', trans('messages.login.invalid_dob')); // Call flash message function
                // return back()->withErrors($validator)->withInput()->with('error_code', 1); // Form calling with Errors and Input values

                return back()->withErrors(['birthday_day' => trans('messages.login.invalid_dob'), 'birthday_month' => trans('messages.login.invalid_dob'), 'birthday_year' => trans('messages.login.invalid_dob')])->withInput()->with('error_code', 1); // Form calling with Errors and Input values

            }
            $from = new DateTime($request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day);
            $to   = new DateTime('today');
            $age = $from->diff($to)->y; 
            if($age < 18){
                return back()->withErrors(['birthday_day' => trans('messages.login.below_age'), 'birthday_month' => trans('messages.login.below_age'), 'birthday_year' => trans('messages.login.below_age')])->withInput()->with('error_code', 1); // Form calling with Errors and Input values
            }

            //get timezone from ip address
            $ip = $_SERVER['REMOTE_ADDR'] ;  //$_SERVER['REMOTE_ADDR']
            $ipInfo = @file_get_contents('http://ip-api.com/json/'.$ip);           
            $ipInfo = json_decode($ipInfo);
                    
            if(!empty($ipInfo) && @$ipInfo->timezone !='')
            {
                $timezone = $ipInfo->timezone;
            }
            else
            {
                $timezone = 'UTC';
            }

            $user = new User;

            $user->first_name   =   $request->first_name;
            $user->last_name    =   $request->last_name;
            $user->email        =   $request->email;
            $user->password     =   bcrypt($request->password);
            $user->dob          =   $request->birthday_year.'-'.
                                    $request->birthday_month.'-'.
                                    $request->birthday_day; // Date format - Y-m-d
            $user->timezone     =   $timezone;

            $email_language = Language::active()->where('default_language','1')->first();
            if($email_language){
                $user->email_language = $email_language->value;    
            }
            else{
                $user->email_language = '';       
            }

            $user->save();  // Create a new user

            $user_pic = new ProfilePicture;

            $user_pic->user_id      =   $user->id;
            $user_pic->src          =   "";
            $user_pic->photo_source =   'Local';

            $user_pic->save();  // Create a profile picture record

            $user_verification = new UsersVerification;

            $user_verification->user_id      =   $user->id;

            $user_verification->save();  // Create a users verification record

            $email_controller->welcome_email_confirmation($user);

            if(Session::get('referral')) {

            $referral_settings = ReferralSettings::first();

            $referral_check = Referrals::whereUserId(Session::get('referral'))->get()->sum('creditable_amount');

            // if($referral_check < $referral_settings->value(1)) {
                $referral = new Referrals;

                $referral->user_id                = Session::get('referral');
                $referral->friend_id              = $user->id;
                $referral->friend_credited_amount = $referral_settings->value(4);
                $referral->if_friend_guest_amount = ($referral_check < $referral_settings->value(1)) ? $referral_settings->value(2) : 0;
                $referral->if_friend_host_amount  = ($referral_check < $referral_settings->value(1)) ? $referral_settings->value(3) : 0;
                $referral->creditable_amount      = ($referral_check < $referral_settings->value(1)) ? ($referral_settings->value(2) + $referral_settings->value(3)) : 0;
                $referral->currency_code          = $referral_settings->value(5, 'code');

                $referral->save();

                Session::forget('referral');
            // }
            }
            
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            {
                $this->helper->flash_message('success', trans('messages.login.reg_successfully')); // Call flash message function
                 if(Session::get('ajax_redirect_url'))
                return redirect()->intended(Session::get('ajax_redirect_url')); // Redirect to ajax url 
                else
                return redirect()->intended('dashboard'); // Redirect to dashboard page
            }
            else
            {
                $this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
                return redirect('login'); // Redirect to login page
            }
        }
    }
    
    /**
     * Email users Login authentication
     *
     * @param array $request    Post method inputs
     * @return redirect     to dashboard page
     */
    public function authenticate(Request $request)
    {
        // Email login validation rules
        $rules = array(
        'login_email'           => 'required|email',
        'login_password'        => 'required'
        );

        // Email login validation custom messages
        $messages = array(
        'required'        => ':attribute '.trans('messages.login.field_is_required').'', 
        );  

        // Email login validation custom Fields name
        $niceNames = array(
        'login_email'     => trans('messages.login.email'),
        'login_password'  => trans('messages.login.password'),
        );

        // set the remember me cookie if the user check the box
        $remember = ($request->remember_me) ? true : false;

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames);

        if ($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput()->with('error_code', 5); // Form calling with Errors and Input values
        }
        else
        {
            // Get user status
            $users = User::where('email', $request->login_email)->first();

            
            // Check user is active or not
            if(@$users->status != 'Inactive')
            {
                if(Auth::attempt(['email' => $request->login_email, 'password' => $request->login_password], $remember))
                {
                    if(Session::get('ajax_redirect_url'))
                    return redirect()->intended(Session::get('ajax_redirect_url')); // Redirect to ajax url 
                    else
                    return redirect()->intended('dashboard'); // Redirect to dashboard page
                }
                else
                {
                    $this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
                    return redirect('login'); // Redirect to login page
                } 
            }
            else    // Call Disabled view file for Inactive user
            {
                /*$data['title'] = 'Disabled ';
                return view('users.disabled', $data);*/
                return redirect('user_disabled');
            }
        }
    }



    /**
     * Google User redirect to Google Authentication page
     *
     * @return redirect     to Google page
     */
    public function googleLogin()
    {
     

    
       
    }

    /**
     * Google User Registration and Login
     *
     * @return redirect     to dashboard page
     */
    public function googleAuthenticate(EmailController $email_controller, Request $request)
    {
                $request = request();

            try
            {

                $client = new Google_Client(['client_id' => google_client_id]);  
              
                // Specify the CLIENT_ID of the app that accesses the backend

                $payload = $client->verifyIdToken($request->idtoken);


            if ($payload) 
            {
                $google_id = $payload['sub'];
            } 
            else
            {
                $this->helper->flash_message('danger', 'invalid_token'); 
                // Call flash message function
                return redirect('login'); 
                // Redirect to login page
            }


            }

            catch(\Exception $e)
            {

                $this->helper->flash_message('danger', $e->getMessage()); // Call flash message function
                return redirect('login'); // Redirect to login page
           
            }


        if($request->connect == 'yes') {
            return redirect('googleConnect/'.$google_id);
        }

        $firstName = $payload['given_name'];
        $lastName =  @$payload['family_name'];
        
        $email = ($payload['email'] == '') ? $google_id.'@gmail.com' : $payload['email'];
        
        /*$user = User::where('email',$email); */// Check Google User Email Id is exists
        $user = User::where('email',$email)->orWhere('google_id',$google_id); 

        if($user->count() > 0 )  // If there update Google Id
        {
            /*$user = User::where('email',$email)->first();*/
            $user = User::where('email',$email)->orWhere('google_id',$google_id)->first(); 

            $user->google_id  = $google_id;

            $user->save();  // Update a Google id

            $user_id = $user->id; // Get Last Updated Id
        }
        else // If not create a new user without Password
        {
            $user = User::where('email', $email)->withTrashed();

            if($user->count() > 0)
            {                
                return redirect('user_disabled');
            }
            
            $user = new User;

            // New user data
            $user->first_name   =   $firstName;
            $user->last_name    =   $lastName;
            $user->email        =   $email;
            $user->google_id    =   $google_id;
            $user->status       =   'Active';//user activated

             $email_language = Language::active()->where('default_language','1')->first();
            if($email_language){
                $user->email_language = $email_language->value;    
            }
            else{
                $user->email_language = '';       
            }

            $user->save(); // Create a new user

            $user_id = $user->id; // Get Last Insert Id

            $user_pic = new ProfilePicture;

            $user_pic->user_id      =   $user_id;
            $user_pic->src          =   $payload['picture'];
            $user_pic->photo_source =   'Google';

            $user_pic->save(); // Save Google profile picture

            $user_verification = new UsersVerification;

            $user_verification->user_id      =   $user->id;

            $user_verification->google       =   'yes';

            $user_verification->save();  // Create a users verification record

            $email_controller->welcome_email_confirmation($user);

            if(Session::get('referral')) {
                
            $referral_settings = ReferralSettings::first();

            $referral_check = Referrals::whereUserId(Session::get('referral'))->sum('creditable_amount');

            // if($referral_check < $referral_settings->value(1)) {
                $referral = new Referrals;

                $referral->user_id                = Session::get('referral');
                $referral->friend_id              = $user->id;
                $referral->friend_credited_amount = $referral_settings->value(4);
                $referral->if_friend_guest_amount = ($referral_check < $referral_settings->value(1)) ? $referral_settings->value(2) : 0;
                $referral->if_friend_host_amount  = ($referral_check < $referral_settings->value(1)) ? $referral_settings->value(3) : 0;
                $referral->creditable_amount      = ($referral_check < $referral_settings->value(1)) ? ($referral_settings->value(2) + $referral_settings->value(3)) : 0;
                $referral->currency_code          = $referral_settings->value(5, 'code');

                $referral->save();

                Session::forget('referral');
            // }
            }
        }

        $users = User::where('id', $user_id)->first();
        
        if(@$users->status != 'Inactive')
        {
            if(Auth::guard()->loginUsingId($user_id)) // Login without using User Id instead of Email and Password
            {
                 if(Session::get('ajax_redirect_url'))
                return redirect()->intended(Session::get('ajax_redirect_url')); // Redirect to ajax url 
                else
                return redirect()->intended('dashboard'); // Redirect to dashboard page
            }
            else
            {
                $this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
                return redirect('login'); // Redirect to login page
            } 
        }
        else // Call Disabled view file for Inactive user
        {
            /*$data['title'] = 'Disabled ';
            return view('users.disabled', $data);*/
            return redirect('user_disabled');
        }
    }

    public function user_disabled(){
        $data['title'] = 'Disabled ';
        return view('users.disabled', $data);
    }

    /**
     * Load Dashboard view file
     *
     * @return dashboard view file
     */
    public function dashboard()
    {        
        // $session_id=Session::getId();
        $sess=Session::getId();
       
        if($sess != '')
        {
            DB::table('sessions')->where('id', Session::getId())->update(array('user_id' => Auth::user()->id)); 
        }else{
            DB::table('sessions')->where('id', Session::getId())->update(array('user_id' => Auth::user()->id));   
        }       

        $data['user_id'] = $user_id = Auth::user()->id;

        $data['all_message']  = Messages::whereIn('id', function($query)
                {
                    $query->select(DB::raw('max(id)'))
                    ->from('messages')->groupby('reservation_id');
                })->with(['user_details' => function($query) {
                                $query->with('profile_picture');
                            }])->with(['reservation' => function($query) {
                                $query->with('currency');
                            }])->with('rooms_address')->where('user_to', $data['user_id'])->where('read','0')->orderBy('id','desc')->get();
        $data['unread'] = $unread  =  Messages::whereIn('id', function($query) use($user_id)
                            {   
                                $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                            })->with(['user_details' => function($query) {
                                $query->with('profile_picture');
                            }])->with(['reservation' => function($query) {
                                $query->with('currency');
                            }])->with('rooms_address')->where('read','0')->orderBy('id','desc')->get();                            

        $data['unread'] = $unread->reject(function ($unread) {
            return @$unread->reservation->status == 'Pending' || @$unread->reservation->status == 'Inquiry';
        });

        $listed=Rooms::where('user_id','=', Auth::user()->id)->where('status','=','Listed');

        if($listed->count() > 0){

        $data['user'] = $user = Auth::user();
        $currentMonth = date('m');
        $currentYear = date('Y');
        //future payouts
        $data['host_future_payout']= payouts::join('reservation', function($join) {
        $join->on('reservation.id', '=', 'payouts.reservation_id');
        })
        ->select('payouts.amount','reservation.nights','reservation.room_id','payouts.currency_code')
        ->where('payouts.user_id','=',Auth::user()->id)
        ->where('payouts.user_type','host')
        ->where('payouts.status','=','Future')
        ->whereRaw('MONTH(payouts.created_at) = ?',[$currentMonth])->get();
       //dd($data['host_future_payout']);
      $future_payout=0;
      $future_nights=0;
      $total_rooms = count($data['host_future_payout']);
     

    foreach(@$data['host_future_payout'] as $future_payouts)
     {   
        $future_payout+=@$future_payouts->amount;
        $future_nights+=@$future_payouts->nights;      
        
     }

   

       

 $data['host_completed_payout']= payouts::join('reservation', function($join) {
        $join->on('reservation.id', '=', 'payouts.reservation_id');
        })
        ->select('payouts.amount','reservation.nights','payouts.currency_code')
        ->where('payouts.user_id','=',Auth::user()->id)->where('payouts.user_type','host')->where('payouts.status','=','Completed')
        ->whereRaw('MONTH(payouts.updated_at) = ?',[$currentMonth])->get();
      
      $completed_payout=0;
      $completed_nights=0;
      $total_rooms1 = count($data['host_completed_payout']);
        foreach(@$data['host_completed_payout'] as $completed_payouts)
     {   
        $completed_payout+=@$completed_payouts->amount;
        $completed_nights+=@$completed_payouts->nights;

     }    
    
        //current year earnings
        $data['host_year_payout']=payouts::select('payouts.amount','payouts.currency_code')->where('user_id','=',Auth::user()->id)->where('payouts.user_type','host')->where('status','=','Completed')
        ->whereRaw('YEAR(updated_at) = ?',[$currentYear])->get();


       $total_payout=0;
        foreach(@$data['host_year_payout'] as $total_payouts)
     {   
        $total_payout+=@$total_payouts->amount;
       

     }
    $data['total_payout']=$total_payout; 
    $data['future_payouts']=$future_payout;
     $data['future_nights']=$future_nights;
     $data['completed_payout']=$completed_payout;
     $data['completed_nights']=$completed_nights;
     $data['total_rooms'] = $total_rooms+$total_rooms1;


        /*$data['pending']  = Messages::whereIn('id', function($query)
        {
        $query->select(DB::raw('max(id)'))
        ->from('messages')->groupby('reservation_id');
        })->with(['user_details' => function($query) {
        $query->with('profile_picture');
        }])->with(['reservation' => function($query) {
        $query->with('currency');
        }])->with('rooms_address')->where('user_to', $data['user_id'])->orderBy('id','desc')->get();*/
        $data['pending']  = Messages::whereIn('id', function($query) use($user_id)
                            {
                                $query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
                            })->with(['user_details' => function($query) {
                                $query->with('profile_picture');
                            },'reservation'=>function($query){
                                $query->with('currency');
                            }])->whereHas('reservation' ,function($query) {
                                // $query->where('status','Pending');
                            })->with('rooms_address')->orderBy('id','desc')->get();

        // dd($data['pending']);

       $data['currency_symbol'] = Currency::first()->symbol;
        $pending_counts=0;
        $notification_count1=0;

        foreach(@$data['pending'] as $ct)
        {   if($ct->host_check ==1 && (@$ct->reservation->status=='Pending' || $ct->reservation->status=='Inquiry'))        
       @$pending_counts+=1;
        }

        foreach(@$data['all_message'] as $cts)
        {   
            if((@$cts->reservation->status!='Pending' && @$cts->reservation->status!='Inquiry' ))        
            {              
                    @$notification_count1+=1;

            }
                           
        }
       
        $data['pending_count']=$pending_counts;
        $data['notification_count']=count($data['unread']);
        $data['result'] = ReferralSettings::first();

        return view('users.host_dashboard', $data);
        }
       
        return view('users.guest_dashboard', $data);
    }



    /**
     * Load Forgot Password View and Send Reset Link
     *
     * @return view forgot password page / send mail to user
     */
    public function forgot_password(Request $request, EmailController $email_controller)
    {   
        if(!$_POST)
        {
            return view('home.forgot_password');
        }
        else
        {
            // Email validation rules
            $rules = array(
            'email'           => 'required|email|exists:users,email'
            );

            // Email validation custom messages
            $messages = array(
            'required'        => ':attribute '.trans('messages.login.field_is_required').'', 
            'exists'          => 'No account exists for this email.'
            );

            // Email validation custom Fields name
            $niceNames = array(
            'email'           => 'Email'
            );

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($niceNames);

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput()->with('error_code', 4); // Form calling with Errors and Input values
            }
            else
            {  //dd($request->email);
              
                $user = User::whereEmail($request->email)->first();
                //dd($user);
                if($user != ''){
                $email_controller->forgot_password($user);

                $this->helper->flash_message('success', trans('messages.login.reset_link_sent',['email'=>$user->email])); // Call flash message function
                return redirect('login');
                 }else{
                    $this->helper->flash_message('error', trans('messages.profile.account_disabled')); // Call flash message function
                return back();
                  
                 }
            }
        }
    }

    /**
     * Set Password View and Update Password
     *
     * @param array $request Input values
     * @return view set_password / redirect to Login
     */
    public function set_password(Request $request)
    {
        if(!$_POST)
        {
            $password_resets = PasswordResets::whereToken($request->secret);
            
            if($password_resets->count())
            {
                $password_result = $password_resets->first();

                $datetime1 = new DateTime();
                $datetime2 = new DateTime($password_result->created_at);
                $interval  = $datetime1->diff($datetime2);
                $hours     = $interval->format('%h');

                if($hours >= 1)
                {
                    // Delete used token from password_resets table
                    $password_resets->delete();

                    $this->helper->flash_message('error', trans('messages.login.token_expired')); // Call flash message function
                    return redirect('login');
                }

                $data['result'] = User::whereEmail($password_result->email)->first();
                $data['token']  = $request->secret;

                return view('home.set_password', $data);
            }
            else
            {
                $this->helper->flash_message('error', trans('messages.login.invalid_token')); // Call flash message function
                return redirect('login');
            }
        }
        else
        {
            // Password validation rules
            $rules = array(
            'password'              => 'required|min:6|max:30',
            'password_confirmation' => 'required|same:password'
            );

            // Password validation custom Fields name
            $niceNames = array(
            'password'              => 'New Password',
            'password_confirmation' => 'Confirm Password'
            );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames);

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput()->with('error_code', 3); // Form calling with Errors and Input values
            }
            else
            {
                // Delete used token from password_resets table
                $password_resets = PasswordResets::whereToken($request->token)->delete();

                $user = User::find($request->id);

                $user->password = bcrypt($request->password);

                $user->save(); // Update Password in users table

                $this->helper->flash_message('success', trans('messages.login.pwd_changed')); // Call flash message function
                return redirect('login');
            }
        }
    }

    /**
     * Load Edit profile view file with user dob, timezone and country
     *
     * @return edit profile view file
     */
    public function edit()
    {
        $data['timezone'] = Timezone::get()->pluck('name', 'value');
        $data['country'] = Country::get()->pluck('long_name', 'short_name');
        $data['languages'] = Language::active()->get();
        $data['email_languages'] = Language::active()->pluck('name','value')->toArray();
        $data['email_default_language'] = Auth::user()->user_email_language;
        $dob=date('Y-m-d',$this->helper->custom_strtotime(Auth::user()->dob));
        $data['dob'] = explode('-', $dob);

        $data['known_languages'] = explode(',', Auth::user()->languages);
        $data['known_languages_name'] = explode(',', Auth::user()->languages_name);
        $data['country_phone_codes'] = Country::get(); 

        if(old()) {
            $data['known_languages'] = old('language') ?: array();
            $old_languages = old('language') ? old('language') : [];
            $data['known_languages_name'] = Language::whereIn('id', $old_languages)->pluck('name') ?: array();
        }

        $data['time_zone'] = Auth::user()->timezone;
        if(empty($data['time_zone'])){ $data['time_zone'] = 'UTC'; }
        $ip = $_SERVER['REMOTE_ADDR'] ;  //$_SERVER['REMOTE_ADDR']
        $ipInfo = @file_get_contents('http://ip-api.com/json/'.$ip);      
        $ipInfo = json_decode($ipInfo);
        if($data['time_zone'] == 'UTC')
        {
             if(!empty($ipInfo) && @$ipInfo->timezone !='')
            {                  
                $data['time_zones'] = $ipInfo->timezone;               
                 
            }
        }else{
                $data['time_zones'] = $data['time_zone'] ;
            }                  
            

        return view('users.edit', $data);
    }

    /**
     * Update edit profile page data
     *
     * @return redirect     to Edit profile
     */
    public function update(Request $request, EmailController $email_controller)
    {

        // Email signup validation rules
         $rules = array(
        'first_name'      => 'required|max:255',
        'last_name'       => 'required|max:255',
        'gender'          => 'required',
        'email'           => 'required|max:255|email|unique:users,email,'.Auth::user()->id,
        'birthday_day'    => 'required',
        'birthday_month'  => 'required',
        'birthday_year'   => 'required',
        // 'agree_tac'       => 'required',  
        );

        // Email signup validation custom messages
        $messages = array(
        //'required'                => ':attribute is required.',
        'birthday_day.required'   => trans('messages.profile.birth_date_required'),
        //'birthday_month.required' => trans('messages.name.key'),
        //'birthday_year.required'  => trans('messages.name.key'),
        );

        // Email signup validation custom Fields name
        $niceNames = array(
        'first_name'      => trans('messages.login.first_name'),
        'last_name'       => trans('messages.login.last_name'),
        'gender'          => trans('messages.profile.gender'),
        'email'           => trans('messages.login.email_address'),
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($niceNames); 

        if ($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
        }

        if(time() < strtotime($request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day)){
            // $this->helper->flash_message('danger', trans('messages.login.invalid_dob')); // Call flash message function
            return back()->withErrors(['birthday_day' => trans('messages.login.invalid_dob')])->withInput(); // Form calling with Errors and Input values
            // return back(); // Form calling with Errors and Input values
        }
        $from = new DateTime($request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day);
        $to   = new DateTime('today');
        $age = $from->diff($to)->y; 
        if($age < 18){
            return back()->withErrors(['birthday_day' => 'You must be 18 or older.'])->withInput(); // Form calling with Errors and Input values
        }

        $user = User::find($request->id);

        $new_email = ($user->email != $request->email) ? 'yes' : 'no';

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->gender = $request->gender;
        $user->dob = $request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day;
        $user->email = $request->email;
        $user->live = $request->live;
        $user->about = $request->about;
        $user->school = $request->school;
        $user->work = $request->work;
        $user->timezone = $request->timezone;
        if($new_email=='yes'){ $user->status=NULL; }
        if($request->language)
        { 
            $user_language=array();
            foreach ($request->language as $key) {
             $user_language[]=trim($key);
             }
            $user_languages=implode(",", $user_language);
        }
        else
            $user_languages='';

       $user->languages   = $user_languages;

       if($request->user_email_language){
            $user->email_language = $request->user_email_language;
        }


        $user->save(); // Update user profile details

        if($new_email == 'yes')
        {
            $email_controller->change_email_confirmation($user);

            //Update UsersVerification email status
            $user_verification = UsersVerification::find($request->id);

            $user_verification->email        =   'No';

            $user_verification->save();

            // // unlisted the host rooms when email verification process

            // $rooms_unlisted = Rooms::where('user_id', '=', $request->id)->first();

            // $rooms_unlisted->status        =   'unlisted';

            // $rooms_unlisted->save();



            $this->helper->flash_message('success', trans('messages.profile.confirm_link_sent',['email'=>$user->email])); // Call flash message function
        }
        else
        {
            $this->helper->flash_message('success', trans('messages.profile.profile_updated')); // Call flash message function
        }

        return redirect('users/edit');
    }

    /**
     * Get Users Phone Numbers
     *
     * @return users_phone_numbers
     */

    public function get_users_phone_numbers(){
        $user_id = Auth::user()->id; 
        $users_phone_numbers = UsersPhoneNumbers::where('user_id', $user_id)->get();
        return $users_phone_numbers->toJson(); 
    }

    /**
     * Update Users Phone Numbers
     *
     * @return users_phone_numbers
     */

    public function update_users_phone_number(Request $request){

        $request_id = $request->id ? $request->id : NULL;
        $rules = array(
            'phone_number'   => 'required|regex:/^[0-9]+$/|min:6|unique:users_phone_numbers', //required|regex:/^[0-9]+$/|min:6
        );
        $niceNames = array(
            'phone_number'   => 'Phone Number',
        );

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($niceNames); 

        if ($validator->fails()) 
        {
            $validation_result = $validator->messages()->toArray();
            return ['status' => 'Failed' , 'message' => $validation_result['phone_number'][0]];
        }

        $user_id = Auth::user()->id; 

        if($request->id != ''){
            $phone_number = UsersPhoneNumbers::find($request->id); 
        }else{
            $phone_number = new UsersPhoneNumbers(); 
        }

        $otp = mt_rand(1000, 9999);

        $phone_number->user_id       = $user_id;
        $phone_number->phone_number  = $request->phone_number; 
        $phone_number->phone_code    = $request->phone_code; 
        $phone_number->otp           = $otp;
        $phone_number->status        = 'Pending';

        $message_response = $this->send_nexmo_message($phone_number->phone_number_nexmo, $phone_number->verification_message_text); 

        if($message_response['status'] == 'Failed'){
            return ['status' => 'Failed', 'message' => $message_response['message']];
        }

        $phone_number->save(); 

        $users_phone_numbers = UsersPhoneNumbers::where('user_id', $user_id)->get();

        return ['status' => 'Success', 'users_phone_numbers' => $users_phone_numbers]; 
    }

    /**
     * Verify Users Phone Numbers
     *
     * @return users_phone_numbers
     */

    public function verify_users_phone_number(Request $request){

        $user_id = Auth::user()->id; 

        if($request->id == ''){
            return ['status' => 'Failed', 'message' => trans('messages.profile.phone_number_not_found')]; 
        }

        $phone_number = UsersPhoneNumbers::find($request->id); 

        if($phone_number->otp != $request->otp){
            return ['status' => 'Failed', 'message' => trans('messages.profile.otp_wrong_message')]; 
        }else{
            $phone_number->status = 'Confirmed'; 
            $phone_number->save(); 
        }
        $users_phone_numbers = UsersPhoneNumbers::where('user_id', $user_id)->get();
        return ['status' => 'Success', 'users_phone_numbers' => $users_phone_numbers]; 
    }

    public function remove_users_phone_number(Request $request){
        $user_id = Auth::user()->id; 

        if($request->id == ''){
            return ['status' => 'Failed', 'message' => trans('messages.profile.phone_number_not_found')]; 
        }

        UsersPhoneNumbers::find($request->id)->delete(); 

        $users_phone_numbers = UsersPhoneNumbers::where('user_id', $user_id)->get();
        return ['status' => 'Success', 'users_phone_numbers' => $users_phone_numbers]; 
    }

    public function send_nexmo_message($to, $message){
        $url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
            [
              'api_key' =>  NEXMO_KEY,
              'api_secret' => NEXMO_SECRET,
              'to' => $to,
              'from' => NEXMO_FROM,
              'text' => $message,
              'type'=> 'unicode',
            ]
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        $response_data = json_decode($response, true);

        $status = 'Failed';
        $status_message = trans('messages.errors.internal_server_error');

        if(@$response_data['messages']){
            foreach ( $response_data['messages'] as $message ) {
                if ($message['status'] == 0) {
                  $status = 'Success';
                } else {
                  $status = 'Failed'; 
                  $status_message = $message['error-text'];
                }
            }
        }

        return array('status' => $status, 'message' => $status_message);
    }

    /**
     * Confirm email for new email update
     *
     * @param array $request Input values
     * @return redirect to dashboard
     */
    public function confirm_email(Request $request)
    {

        $password_resets = PasswordResets::whereToken($request->code);
        
        if($password_resets->count() && Auth::user()->email == $password_resets->first()->email)
        {
            $password_result = $password_resets->first();

            $datetime1 = new DateTime();
            $datetime2 = new DateTime($password_result->created_at);
            $interval  = $datetime1->diff($datetime2);
            $hours     = $interval->format('%h');

            if($hours >= 1)
            {
                // Delete used token from password_resets table
                $password_resets->delete();

                $this->helper->flash_message('error', trans('messages.login.token_expired')); // Call flash message function
                return redirect('login');
            }

            $data['result'] = User::whereEmail($password_result->email)->first();
            $data['token']  = $request->code;

            $user = User::find($data['result']->id);

            $user->status = "Active";

            $user->save();

            $user_verification = UsersVerification::find($data['result']->id);

            $user_verification->email        =   'yes';

            $user_verification->save();  // Create a users verification record

            // Delete used token from password_resets table
            $password_resets->delete();

            $this->helper->flash_message('success', trans('messages.profile.email_confirmed')); // Call flash message function
            return redirect('dashboard');
        }
        else
        {
            //if (Auth::guest())
                $this->helper->flash_message('error', trans('messages.login.invalid_token')); // Call flash message function
            return redirect('dashboard');
        }
    }

    /**
     * User Profile Page
     *
     * @return view profile page
     */
    public function show(Request $request)
    {
        $data['result'] = @User::find($request->id);       
        if(!$data['result']) abort('404');
        $data['reviews_from_guests'] = Reviews::where(['user_to'=>$request->id, 'review_by'=>'guest']);
        $data['reviews_from_hosts'] = Reviews::where(['user_to'=>$request->id, 'review_by'=>'host']);

        $data['reviews_count'] = $data['reviews_from_guests']->count() + $data['reviews_from_hosts']->count();

        $wish_list = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms','host_experiences']);
            }, 'profile_picture'])->where('user_id', $request->id)
        ->where('privacy','0');

        if(@Auth::user()->id == $request->id)
        $wish_list = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms','host_experiences']);
            }, 'profile_picture'])->where('user_id', $request->id);

        $data['wishlists'] =$wish_list->orderBy('id', 'desc')->get();
        
        $data['title'] = $data['result']->first_name."'s Profile ";

        $data['rooms'] = Rooms::whereHas('users', function ($query) {
                                $query->where('status', 'Active');
                            })
                            ->where('status', 'Listed')
                            ->where('user_id',$request->id)
                            ->get();
                            
        $data['host_experiences'] = HostExperiences::profilePage()->where('user_id',$request->id)->get();
        //dd($data);
        return view('users.profile', $data);
    }

    /**
     * User Account Security Page
     *
     * @param array $request Input values
     * @return view security page
     */
    public function security(Request $request)
    {
        return view('account.security');
    }

    /**
     * User Change Password
     *
     * @param array $request Input values
     * @return redirect     to Security page
     */
    public function change_password(Request $request)
    {
        // Password validation rules
        $user = Auth::user();
        $rules = array(
        'old_password'          => 'required',
        'new_password'          => 'required|min:6|max:30|different:old_password',
        'password_confirmation' => 'required|same:new_password|different:old_password'
        );

        // Password validation custom Fields name
        $niceNames = array(
        'old_password'          => 'Old Password',
        'new_password'          => 'New Password',
        'password_confirmation' => 'Confirm Password'
        );

        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($niceNames);

        if ($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput()->with('error_code', 2); // Form calling with Errors and Input values
        }
        else
        {
            $user = User::find(Auth::user()->id);

            if(!Hash::check($request->old_password, $user->password))
            {
                return back()->withInput()->withErrors(['old_password' => trans('messages.profile.pwd_not_correct')]);
            }

            $user->password = bcrypt($request->new_password);

            $user->save(); // Update password

            $this->helper->flash_message('success', trans('messages.profile.pwd_updated')); // Call flash message function
            return redirect('users/security');
        }
    }

    /**
     * Add a Payout Method and Load Payout Preferences File
     *
     * @param array $request Input values
     * @return redirect to Payout Preferences page and load payout preferences view file
     */
    public function payout_preferences(Request $request, EmailController $email_controller)
    {
        if(!$request->address1)
        { 
            $data['payouts'] = PayoutPreferences::where('user_id', Auth::user()->id)->orderBy('id','desc')->get();
            $data['country']   = Country::all()->pluck('long_name','short_name');
            $data['stripe_data'] = PaymentGateway::where('site', 'Stripe')->get();             
            $data['country_list'] = Country::getPayoutCoutries();
            $data['iban_supported_countries'] = Country::getIbanRequiredCountries();
            $data['country_currency'] = $this->helper->getStripeCurrency();
            $data['mandatory']         = PayoutPreferences::getAllMandatory();
            $data['branch_code_required'] = Country::getBranchCodeRequiredCountries();
            return view('account/payout_preferences', $data);
        }
        else
        {
            $country_data = Country::where('short_name', $request->country)->first();

            if (!$country_data) {
                $message = trans('messages.lys.service_not_available_country');
               $this->helper->flash_message('error', $message); // Call flash message function
               return back();
            }
            $payout     =   new PayoutPreferences;

            $payout->user_id       = Auth::user()->id;
            $payout->address1      = $request->address1;
            $payout->address2      = $request->address2;
            $payout->city          = $request->city;
            $payout->state         = $request->state;
            $payout->postal_code   = $request->postal_code;
            $payout->country       = $request->country;
            $payout->payout_method = $request->payout_method;
            $payout->paypal_email  = $request->paypal_email;
            $payout->currency_code = PAYPAL_CURRENCY_CODE;
            
            if($request->payout_method == 'Stripe') {
                $stripe_credentials = PaymentGateway::where('site', 'Stripe')->pluck('value','name');
                \Stripe\Stripe::setApiKey($stripe_credentials['secret']);
                \Stripe\Stripe::setClientId($stripe_credentials['client_id']);
                $oauth_url = \Stripe\OAuth::authorizeUrl([
                    'response_type'    => 'code',
                    'scope'    => 'read_write',
                    'redirect_uri'  => url('users/stripe_payout_preferences'),
                ]);

                Session::put('payout_preferences_data', $payout);
                return redirect($oauth_url);
            }

            $payout->save();

            $payout_check = PayoutPreferences::where('user_id', Auth::user()->id)->where('default','yes')->get();

            if($payout_check->count() == 0)
            {
                $payout->default = 'yes';
                $payout->save();
            }

            $email_controller->payout_preferences($payout->id);

            $this->helper->flash_message('success', trans('messages.account.payout_updated')); // Call flash message function
            return redirect('users/payout_preferences/'.Auth::user()->id);
        }
    }

    // stripe account creation
    public function update_payout_preferences(Request $request, EmailController $email_controller, $id)
    {        
        // dd($id);
        $country_data = Country::where('short_name', $request->country)->first();
        $user_data = User::where('id', $id)->first();
        // dd($user_data->dob);
        if($user_data->dob=="")
        {
            $message = trans('messages.lys.date_of_birth');
            $this->helper->flash_message('error', $message); // Call flash message function
            return redirect('users/edit');
        } 
        if (!$country_data) {
            $message = trans('messages.lys.service_not_available_country');
            $this->helper->flash_message('error', $message); // Call flash message function
            return back();
        }

        /*** required field validation --start-- ***/        
        $country = $request->country;
        
        $rules = array(
            'country' =>    'required',
            'currency' =>    'required',            
            'account_number' =>    'required',
            'holder_name' =>    'required',            
            'stripe_token'  => 'required',
            'address1'  => 'required',
            'city'  => 'required',            
            'postal_code'  => 'required',
            'document' => 'required|mimes:png,jpeg,jpg',

        ); 

        $user_id = Auth::user()->id; 

        $user  = User::find($user_id);

        // custom required validation for Japan country       
        if($country == 'JP')
        {

            $rules['phone_number'] = 'required';
            $rules['bank_name'] = 'required';
            $rules['branch_name'] = 'required';
            $rules['address1'] = 'required';
            $rules['kanji_address1'] = 'required';
            $rules['kanji_address2'] = 'required';
            $rules['kanji_city'] = 'required';
            $rules['kanji_state'] = 'required';
            $rules['kanji_postal_code'] = 'required';

            if(!$user->gender)
            {
                $rules['gender'] = 'required|in:male,female';
            }
        
        }
        // custom required validation for US country      
        else if($country == 'US')
        {
            $rules['ssn_last_4'] = 'required|digits:4';
        }

        $nice_names = array(
            'payout_country' =>    trans('messages.account.country'),
            'currency' =>    trans('messages.account.currency'),
            'routing_number' =>    trans('messages.account.routing_number'),
            'account_number' =>    trans('messages.account.account_number'),
            'holder_name' =>    trans('messages.account.holder_name'),
            'additional_owners' => trans('messages.account.additional_owners'),
            'business_name' => trans('messages.account.business_name'),
            'business_tax_id' => trans('messages.account.business_tax_id'),
            'holder_type' =>    trans('messages.account.holder_type'),
            'stripe_token' => 'Stripe Token', 
            'address1'  => trans('messages.account.address'),
            'city'  => trans('messages.account.city'),
            'state'  => trans('messages.account.state'),
            'postal_code'  => trans('messages.account.postal_code'),
            'document'  => trans('messages.account.legal_document'),
            'ssn_last_4'  => trans('messages.account.ssn_last_4'),            
        );
        $messages   = array('required'=>':attribute is required.');

        $validator  = Validator::make($request->all(), $rules, $messages);
        $validator->setAttributeNames($nice_names); 
         
        if($validator->fails()) 
        {
          return back()->withErrors($validator)->withInput();        
                            
        }        
        /*** required field validation --end-- ***/

        
        $stripe_data    = PaymentGateway::where('site', 'Stripe')->pluck('value','name');  

        \Stripe\Stripe::setApiKey($stripe_data['secret']);    


        $account_holder_type = 'individual';

        // create account token use to create account 
        if($request->country != 'JP')
        {
            $legal_entity = array(
                  "first_name" => $user->first_name,
                  "last_name" => $user->last_name,
                  "type" => $account_holder_type,
                  "address" => array(
                    "line1" => @$request->address1,
                    "line2" => @$request->address2 ? @$request->address2  : null,
                    "city" => @$request->city,
                    "country" => @$request->country,
                    "state" => @$request->state ? @$request->state : null,
                    "postal_code" => @$request->postal_code,
                    ),
                  "dob" => array(
                    "day" => @$user->dob_array[2],
                    "month" => @$user->dob_array[1],
                    "year" => @$user->dob_array[0],
                    ),
                );
            if($request->country == 'US')
                $legal_entity['ssn_last_4'] = $request->ssn_last_4;
        }
        else{
                // for Japan country //
                $address_kana = array(
                    'line1'         => $request->address1,
                    'town'         => $request->address2,
                    'city'          => $request->city,
                    'state'         => $request->state,
                    'postal_code'   => $request->postal_code,
                     'country'       => $request->country,
                    );
                $address_kanji = array(
                    'line1'         => $request->kanji_address1,
                    'town'         => $request->kanji_address2,
                    'city'          => $request->kanji_city,
                    'state'         => $request->kanji_state,
                    'postal_code'   => $request->kanji_postal_code,
                    'country'       => $request->country,
                    );
                $legal_entity = array(
                    "first_name_kana" => $user->first_name,
                    "last_name_kana" => $user->last_name,
                    "first_name_kanji" => $user->first_name,
                    "last_name_kanji" => $user->last_name,
                    "type" => $account_holder_type,
                    "address" => array(
                        "line1" => @$request->address1,
                        "line2" => @$request->address2 ? @$request->address2  : null,
                        "city" => @$request->city,
                        "country" => @$request->country,
                        "state" => @$request->state ? @$request->state : null,
                        "postal_code" => @$request->postal_code,
                        ),
                    "address_kana" => $address_kana,
                    "address_kanji" => $address_kanji,
                    "phone_number" => @$request->phone_number ? $request->phone_number : null,
                );
        }
         // verification document upload for stripe account --start-- //
        $document = $request->file('document');
        if($request->document) {
            $extension =   $document->getClientOriginalExtension();
            $filename  =   $user_id.'_user_document_'.time().'.'.$extension;
            $filenamepath = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$user_id.'/uploads';
                                
            if(!file_exists($filenamepath))
            {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$user_id.'/uploads', 0777, true);
            }
            $success   =   $document->move('images/users/'.$user_id.'/uploads/', $filename);
            if($success)
            {
                $document_path = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$user_id.'/uploads/'.$filename;
                
                try
                {
                    $stripe_file_details = \Stripe\FileUpload::create(
                      array(
                        "purpose" => "identity_document",
                        "file" => fopen($document_path, 'r')
                      )
                    );
                    $legal_entity['verification'] = array(
                            "document" => $stripe_file_details->id
                        );
                    $stripe_document = $stripe_file_details->id;
                }
                catch(\Exception $e)
                {   
                    $this->helper->flash_message('error', $e->getMessage());
                    return back();
                }

            }
            
        }  
         // verification document upload for stripe account --end-- //

        //create token use user details 
        try
        {
            $new_token = \Stripe\Token::create([
              "account" => [
                "legal_entity" => $legal_entity,
                "tos_shown_and_accepted" => true
              ]
            ]);
        } catch(\Exception $e){
            $this->helper->flash_message('error', $e->getMessage());
            return back();
        }

        /*** create stripe account ***/
        try
        {
                $recipient = \Stripe\Account::create(array(
                  "country" => strtolower($request->country),
                   "payout_schedule" => array(
                            "interval" => "manual"
                        ), /*
                  "tos_acceptance" => array(
                        "date" => time(),
                        "ip"    => $_SERVER['REMOTE_ADDR']
                    ),*/
                  "type"    => "custom",
                  "account_token" => $new_token->id,
                ));
        }
        catch(\Exception $e)
        {
            $this->helper->flash_message('error', $e->getMessage());
            return back();
        }
         $recipient->email = Auth::user()->email;

        // create external account using stripe token --start-- //

        try{
            $recipient->external_accounts->create(array(
                "external_account" => $request->stripe_token,
            ));
        }catch(\Exception $e){   
            $this->helper->flash_message('error', $e->getMessage());
            return back();
        }
        $recipient->save();

        // store payout preference data to payout_preference table --start-- //
        $payout_preference = new PayoutPreferences;
        $payout_preference->user_id = $user_id;
        $payout_preference->country = $request->country;
        $payout_preference->currency_code = $request->currency;
        $payout_preference->routing_number = $request->routing_number;
        $payout_preference->account_number = $request->account_number;
        $payout_preference->holder_name = $request->holder_name;
        $payout_preference->holder_type = $account_holder_type;
        $payout_preference->paypal_email = @$recipient->id;

        $payout_preference->address1 = @$request->address1;
        $payout_preference->address2 = @$request->address2;
        $payout_preference->city = @$request->city;
        
        $payout_preference->state = @$request->state;
        $payout_preference->postal_code = @$request->postal_code;
        $payout_preference->document_id = $stripe_document;                    
        $payout_preference->document_image =@$filename; 
        $payout_preference->phone_number =@$request->phone_number ? $request->phone_number : ''; 
        $payout_preference->branch_code =@$request->branch_code ? $request->branch_code : ''; 
        $payout_preference->bank_name =@$request->bank_name ? $request->bank_name : ''; 
        $payout_preference->branch_name =@$request->branch_name ? $request->branch_name : ''; 

        $payout_preference->ssn_last_4 = @$request->country == 'US' ? $request->ssn_last_4 : '';
        $payout_preference->payout_method = 'Stripe';

        $payout_preference->address_kanji = @$address_kanji ? json_encode(@$address_kanji) : json_encode([]);

        $payout_preference->save(); 

        if($request->gender)
        {
            $user->gender = $request->gender; 
            $user->save();
        }

        $payout_check = PayoutPreferences::where('user_id', Auth::user()->id)->where('default','yes')->get();

        if($payout_check->count() == 0)
        {
            $payout_preference->default = 'yes'; // set default payout preference when no default
            $payout_preference->save();
        }
        // store payout preference data to payout_preference table --end-- //

        $email_controller->payout_preferences($payout_preference->id); // send payout preference updated email to host user.
        $this->helper->flash_message('success', trans('messages.account.payout_updated'));
        return back(); 

    }

    public function stripe_payout_preferences(Request $request) {
        $stripe_credentials = PaymentGateway::where('site', 'Stripe')->pluck('value','name');
        \Stripe\Stripe::setApiKey($stripe_credentials['secret']);
        \Stripe\Stripe::setClientId($stripe_credentials['client_id']);
        try {
            $response = \Stripe\OAuth::token([
                'client_secret' => $stripe_credentials['secret'],
                'code'          => $request->code,
                'grant_type'    => 'authorization_code'
            ]);
        }
        catch (\Exception $e)
        {
            $oauth_url = \Stripe\OAuth::authorizeUrl([
                'response_type'    => 'code',
                'scope'    => 'read_write',
                'redirect_uri'  => url('users/stripe_payout_preferences'),
            ]);
            return redirect($oauth_url);
        }
        $session_payout_data = Session::get('payout_preferences_data');
        if(!$session_payout_data || !@$response['stripe_user_id']) {
            return redirect('users/payout_preferences/'.Auth::user()->id);
        }
        $session_payout_data->paypal_email = @$response['stripe_user_id'];
        $session_payout_data->payout_method = "Stripe";
        $session_payout_data->save();

        $payout_check = PayoutPreferences::where('user_id', Auth::user()->id)->where('default','yes')->get();

        if($payout_check->count() == 0)
        {
            $session_payout_data->default = 'yes';
            $session_payout_data->save();
        }
        
        Session::forget('payout_preferences_data');
        $this->helper->flash_message('success', trans('messages.account.payout_updated')); // Call flash message function
        return redirect('users/payout_preferences/'.Auth::user()->id);
    }   

    /**
     * Delete Payouts Default Payout Method
     *
     * @param array $request Input values
     * @return redirect to Payout Preferences page
     */
    public function payout_delete(Request $request, EmailController $email_controller)
    {
        $payout = PayoutPreferences::find($request->id);
        if ($payout==null) {
            return redirect('users/payout_preferences/'.Auth::user()->id);
        }
        if($payout->default == 'yes')
        {
            $this->helper->flash_message('error', trans('messages.account.payout_default')); // Call flash message function
            return redirect('users/payout_preferences/'.Auth::user()->id);
        }
        else
        {
            $payout->delete();

            $email_controller->payout_preferences($payout->id, 'delete');

            $this->helper->flash_message('success', trans('messages.account.payout_deleted')); // Call flash message function
            return redirect('users/payout_preferences/'.Auth::user()->id);
        }
    }

    /**
     * Update Payouts Default Payout Method
     *
     * @param array $request Input values
     * @return redirect to Payout Preferences page
     */
    public function payout_default(Request $request, EmailController $email_controller)
    {
        $payout = PayoutPreferences::find($request->id);

        if($payout->default == 'yes')
        {
            $this->helper->flash_message('error', trans('messages.account.payout_already_defaulted')); // Call flash message function
            return redirect('users/payout_preferences/'.Auth::user()->id);
        }
        else
        {
            $payout_all = PayoutPreferences::where('user_id',Auth::user()->id)->update(['default'=>'no']);

            $payout->default = 'yes';
            $payout->save();

            $email_controller->payout_preferences($payout->id, 'default_update');

            $this->helper->flash_message('success', trans('messages.account.default').' '.trans('messages.account.payout_updated')); // Call flash message function
            return redirect('users/payout_preferences/'.Auth::user()->id);
        }
    }

    /**
     * Load Transaction History Page
     *
     * @param array $request Input values
     * @return view Transaction History
     */
    public function transaction_history(Request $request)
    {
        //rooms name changed using language based (dropdown) 
        $list = Rooms::where('user_id', Auth::user()->id)->where('status','Listed')->orWhere('status','Unlisted')->get();
        $data['lists']=$list->pluck('name','id');
        $data['payout_methods'] = PayoutPreferences::where('user_id', Auth::user()->id)->pluck('paypal_email','id');

        $data['from_month'] = [];
        $data['to_month'] = [];
        $data['payout_year'] = [];

        for($i=1; $i<=12; $i++)
            $data['from_month'][$i] = trans('messages.lys.'.date("F", mktime(0, 0, 0, $i, 10)));
            //$data['from_month'][$i] = trans('messages.profile.from').': '.trans('messages.lys.'.date("F", mktime(0, 0, 0, $i, 10)));

        for($i=1; $i<=12; $i++)
            $data['to_month'][$i] =  trans('messages.lys.'.date("F", mktime(0, 0, 0, $i, 10)));

        $user_year = Auth::user()->since;

        for($i=date('Y'); $i>=$user_year; $i--)
            $data['payout_year'][$i] = $i;  
        return view('account.transaction_history', $data);
    }

    /**
     * Ajax Transaction History
     *
     * @param array $request Input values
     * @return json Payouts data
     */
    public function result_transaction_history(Request $request)
    {
        $data  = $request;

        $data  = json_decode($data['data']);
        
        $transaction = $this->transaction_result($data);

        $transaction_result = $transaction->paginate(10)->toJson();

        echo $transaction_result;
    }

    /**
     * Export Transaction History CSV file
     *
     * @param array $request Input values
     * @return file Exported CSV File
     */
   public function transaction_history_csv(Request $request)
    {
        $data  = $request;
        $limit = $request->page.'0';
        $offset = ($request->page-1).'0';

        $transaction = $this->transaction_result($data);

        $transaction = $transaction->skip($offset)->take($limit)->get();
        $transaction = $transaction->toArray();

        

        for($i=0; $i<count($transaction); $i++)
        {
            unset($transaction[$i]['id']); unset($transaction[$i]['reservation_id']);unset($transaction[$i]['room_id']);unset($transaction[$i]['user_id']); unset($transaction[$i]['status']); unset($transaction[$i]['penalty_id']);
            unset($transaction[$i]['penalty_amount']);unset($transaction[$i]['created_at']);unset($transaction[$i]['updated_at']);    
            unset($transaction[$i]['correlation_id']); unset($transaction[$i]['currency_symbol']);  

            $transaction[$i]['Date'] = $transaction[$i]['date'];
            $transaction[$i]['Type'] = ($transaction[$i]['user_type'] == 'guest')? trans('messages.account.refund') : trans('messages.account.payout');
            if($request->type != 'future-transactions')
                $transaction[$i]['Details'] = $transaction[$i]['account']!="" ? trans('messages.account.transfer_to')." ".$transaction[$i]['account'] : "";
            $transaction[$i]['Currency_code'] = $transaction[$i]['currency_code'];            
            $transaction[$i]['Amount'] = $transaction[$i]['amount']!=0 ? $transaction[$i]['amount'] : "0";
            
            unset($transaction[$i]['user_type']);
            unset($transaction[$i]['account']);  
            unset($transaction[$i]['amount']);  
            unset($transaction[$i]['currency_code']); 
            unset($transaction[$i]['date']);   
            unset($transaction[$i]['spots']);   
            unset($transaction[$i]['list_type']);   

           

            /*$transaction[$i] = array_only($transaction[$i], ['type','date','account','currency_code','amount']);*/
        }

    

        Excel::create(strtolower($data->type).'-history', function($excel) use($transaction) {
            $excel->sheet('sheet1', function($sheet) use($transaction) {
                $sheet->fromArray($transaction);
            });
        })->export('csv');
    }




    /**
     * Transactio History Result
     *
     * @param array $data Payouts detail
     * @return array Payouts data
     */
    public function transaction_result($data)
    {
        $type          = @$data->type;
        $payout_method = @$data->payout_method;
        $listing       = @$data->listing;
        $year          = @$data->year;
        $start_month   = @$data->start_month;
        $end_month     = @$data->end_month;

        if($type == 'completed-transactions')
            $status = 'Completed';
        else if($type == 'future-transactions')
            $status = 'Future';

        $where['user_id'] = Auth::user()->id;
        $where['status']  = $status;

        if($payout_method)
            $where['account'] = PayoutPreferences::find($payout_method)->paypal_email;

        if($listing)
            $where['room_id'] = $listing;

        if($status == 'Completed')
            $transaction = Payouts::where($where)->whereYear('updated_at', '=', $year)->whereMonth('updated_at', '>=', $start_month)->whereMonth('updated_at', '<=', $end_month);
        else if($status == 'Future')
            $transaction = Payouts::whereHas('reservation', function ($query) {
                // $query->where('transaction_id','!=', '')->orWhere('coupon_code','!=', '');
            })->where($where);

        return $transaction;
    }

    /**
     * Load Reviews for both Guest and Host with Previous reviews
     *
     * @param array $request Input values
     * @return view User Reviews file
     */
    public function reviews(Request $request)
    {
        $link_array = explode('/',url()->previous());
        $page = end($link_array);
        if($page=='trips_review')
            return redirect('trips/current');

       else if($page=='reservations_review')
            return redirect('my_reservations');

        $data['reviews_about_you'] = Reviews::where('user_to', Auth::user()->id)->orderBy('id', 'desc')->get();
        $data['reviews_by_you'] = Reviews::where('user_from', Auth::user()->id)->orderBy('id', 'desc')->get();

        $data['reviews_to_write'] = Reservation::with(['reviews'])->whereRaw('DATEDIFF(now(),checkout) <= 14')->whereRaw('DATEDIFF(now(),checkout) >= 1')->where(['status'=>'Accepted'])->where(function($query) {
                return $query->where('user_id', Auth::user()->id)->orWhere('host_id', Auth::user()->id);
            })->get();

        $data['expired_reviews'] = Reservation::with(['reviews'])->whereRaw('DATEDIFF(now(),checkout) > 14')->where(function($query) {
                return $query->where('user_id', Auth::user()->id)->orWhere('host_id', Auth::user()->id);
            })->get();

        $data['reviews_to_write_count'] = 0;

        for($i=0; $i<$data['reviews_to_write']->count(); $i++) {
            if($data['reviews_to_write'][$i]->review_days > 0 && $data['reviews_to_write'][$i]->reviews->count() < 2) {
                if($data['reviews_to_write'][$i]->reviews->count() == 0)
                    $data['reviews_to_write_count'] += 1;
                for($j=0; $j<$data['reviews_to_write'][$i]->reviews->count(); $j++) {
                    if(@$data['reviews_to_write'][$i]->reviews[$j]->user_from != Auth::user()->id)
                        $data['reviews_to_write_count'] += 1;
                }
            }
        }

        $data['expired_reviews_count'] = 0;

        for($i=0; $i<$data['expired_reviews']->count(); $i++) {
            if($data['expired_reviews'][$i]->review_days <= 0 && $data['expired_reviews'][$i]->reviews->count() < 2) {
                if($data['expired_reviews'][$i]->reviews->count() == 0)
                    $data['expired_reviews_count'] += 1;
                for($j=0; $j<$data['expired_reviews'][$i]->reviews->count(); $j++) {
                    if(@$data['expired_reviews'][$i]->reviews[$j]->user_from != Auth::user()->id)
                        $data['expired_reviews_count'] += 1;
                }
            }
        }
        
        return view('users.reviews', $data);
    }

    /**
     * Edit Reviews for both Guest and Host
     *
     * @param array $request Input values
     * @return json success and review_id
     */
    public function reviews_edit(Request $request, EmailController $email_controller)
    {
        
        $data['result'] = $reservation_details = Reservation::find($request->id);
        //if check reservation details
        if(!empty($reservation_details))
        {
            if(Auth::user()->id == $reservation_details->user_id) {
                $reviews_check = Reviews::where(['reservation_id'=>$request->id, 'review_by'=>'guest'])->get();
                $data['review_id'] = ($reviews_check->count()) ? $reviews_check[0]->id : '';
            }
            else {
                $reviews_check = Reviews::where(['reservation_id'=>$request->id, 'review_by'=>'host'])->get();
                $data['review_id'] = ($reviews_check->count()) ? $reviews_check[0]->id : '';
            }

            if(!$request->data) {
                if($reservation_details->user_id == Auth::user()->id)
                    return view('users.reviews_edit_guest', $data);
                else if($reservation_details->host_id == Auth::user()->id)
                    return view('users.reviews_edit_host', $data);
                else
                    abort('404');
            }
            else {
                $data  = $request;
                $data  = json_decode($data['data']);

                if($data->review_id == '')
                    $reviews = new Reviews;
                else
                    $reviews = Reviews::find($data->review_id);

                $reviews->reservation_id = $reservation_details->id;
                $reviews->room_id = $reservation_details->room_id;

                if($reservation_details->user_id == Auth::user()->id) {
                    $reviews->user_from = $reservation_details->user_id;
                    $reviews->user_to = $reservation_details->host_id;
                    $reviews->review_by = 'guest';
                }
                else if($reservation_details->host_id == Auth::user()->id) {
                    $reviews->user_from = $reservation_details->host_id;
                    $reviews->user_to = $reservation_details->user_id;
                    $reviews->review_by = 'host';
                }

                foreach($data as $key=>$value) {
                    if($key != 'section' && $key != 'review_id') {
                        $reviews->$key = $value;
                    }
                }
                //echo "<pre>";print_r($reviews->toArray()); die;
                $reviews->save();

                $check = Reviews::whereReservationId($request->id)->get();

                if($check->count() == 1) {
                    if($data->section == 'guest' || $data->section == 'host_details'){
                        $type = ($check[0]->review_by == 'guest') ? 'host' : 'guest';
                        $email_controller->wrote_review($check[0]->id, $type);
                    }
                }
                else {
                    if($data->section == 'guest' || $data->section == 'host_details'){
                        $type = ($check[1]->review_by == 'guest') ? 'host' : 'guest';
                        $email_controller->read_review($check[0]->id, 1);
                        $email_controller->read_review($check[1]->id, 2);
                    }
                }
                
                return json_encode(['success'=>true, 'review_id'=>$reviews->id]);
            }
        }
        else{
            abort('404');
        }
    }


    /**
     * Edit Reviews for both Guest and Host
     *
     * @param array $request Input values
     * @return json success and review_id
     */
    public function host_experience_reviews_edit(Request $request, EmailController $email_controller)
    {

        
        $data['result'] = $reservation_details = Reservation::find($request->id);
        //if check reservation details
        if(!empty($reservation_details))
        {
            if(Auth::user()->id == $reservation_details->user_id) {
                $reviews_check = Reviews::where(['reservation_id'=>$request->id, 'review_by'=>'guest'])->get();
                $data['review_id'] = ($reviews_check->count()) ? $reviews_check[0]->id : '';
            }
            else {
                $reviews_check = Reviews::where(['reservation_id'=>$request->id, 'review_by'=>'host'])->get();
                $data['review_id'] = ($reviews_check->count()) ? $reviews_check[0]->id : '';
            }

            if(!$request->data) {
                if($reservation_details->user_id == Auth::user()->id)
                    return view('users.exp_reviews_edit_guest', $data);
                else if($reservation_details->host_id == Auth::user()->id)
                    return view('users.exp_reviews_edit_host', $data);
                else
                    abort('404');
            }
            else {

                $data  = $request;
                $data  = json_decode($data['data']);
                if($data->review_id == '')
                    $reviews = new Reviews;
                else
                    $reviews = Reviews::find($data->review_id);

                $reviews->reservation_id = $reservation_details->id;
                $reviews->room_id = $reservation_details->room_id;
                $reviews->list_type = $reservation_details->list_type;

                if($reservation_details->user_id == Auth::user()->id) {
                    $reviews->user_from = $reservation_details->user_id;
                    $reviews->user_to = $reservation_details->host_id;
                    $reviews->review_by = 'guest';
                    $reviews->comments = $data->improve_comments;
                    $reviews->rating = $data->rating;
                }
                else if($reservation_details->host_id == Auth::user()->id) {
                    $reviews->user_from = $reservation_details->host_id;
                    $reviews->user_to = $reservation_details->user_id;
                    $reviews->review_by = 'host';
                    $reviews->comments = $data->private_feedback;
                    $reviews->rating = $data->cleanliness;
                }
                
                
                
                
                $reviews->save();

                $check = Reviews::whereReservationId($request->id)->get();

                if($check->count() == 1) {
                    if($data->section == 'guest' || $data->section == 'host_details'){
                        $type = ($check[0]->review_by == 'guest') ? 'host' : 'guest';
                        $email_controller->wrote_review($check[0]->id, $type);
                    }
                }
                else {
                    $email_controller->read_review($check[0]->id, 1);
                    if($data->section == 'guest' || $data->section == 'host_details'){
                        $type = ($check[1]->review_by == 'guest') ? 'host' : 'guest';

                        $email_controller->read_review($check[0]->id, 1);
                        $email_controller->read_review($check[1]->id, 2);
                    }
                }
                
                return json_encode(['success'=>true, 'review_id'=>$reviews->id]);
            }
        }
        else{
            abort('404');
        }
    }

    /**
     * Load User Media page
     *
     * @return view User Media file
     */
    public function media()
    {
        $data['result'] = User::find(Auth::user()->id);

        return view('users.media', $data);
    }
    /**
     * User Profile Image Upload
     *
     * @param array $request Input values
     * @return redirect to User Media Page
     */
    public function image_upload(Request $request)
    {
        $image  =   $request->file('profile_pic');

        if($image) {
            $extension      =   $image->getClientOriginalExtension();
            $filename       =   'profile_pic_' . time() . '.' . $extension;
            $imageRealPath  =   $image->getRealPath();       
            $filesize       =   $image->getSize(); // get image file size

            $extension=strtolower($extension);        

            if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png' && $extension != 'gif' ) {
                $this->helper->flash_message('error', trans('messages.profile.cannot_upload')); // Call flash message function
                return back();
            }

            // check if uploaded image size is less than 5 MB
            if($filesize > (5 * 1024 * 1024)){
                $this->helper->flash_message('error', trans('messages.profile.image_size_exceeds_5mb')); // Call flash message function
                return back();
            }

            if(UPLOAD_DRIVER=='cloudinary')
            {
                try 
                {
                    $last_src=DB::table('profile_picture')->where('user_id',$request->user_id)->first()->src;
                    \Cloudder::upload($request->file('profile_pic'));
                    $c=\Cloudder::getResult(); 
                    $filename=$c['public_id'];
                    if($last_src!="") \Cloudder::destroy($last_src);
                }
                catch (\Exception $e) 
                {
                    $this->helper->flash_message('error', $e->getMessage()); // Call flash message function
                    return back();
                }
            }
            else
            {
                $img = Image::make($imageRealPath)->orientate();
                $path = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$request->user_id;            
                if(!file_exists($path)) {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$request->user_id, 0777, true);
                }
                $success = $img->save('images/users/'.$request->user_id.'/'.$filename);

                $compress_success = $this->helper->compress_image('images/users/'.$request->user_id.'/'.$filename, 'images/users/'.$request->user_id.'/'.$filename, 80);
                //change compress image in 510*510
                $compress_success = $this->helper->compress_image('images/users/'.$request->user_id.'/'.$filename, 'images/users/'.$request->user_id.'/'.$filename, 80,510,510);
                //end change
                if(!$success) {
                    $this->helper->flash_message('error', trans('messages.profile.cannot_upload')); // Call flash message function
                    return back();
                }
            }

            $user_pic = ProfilePicture::find($request->user_id);

            $user_pic->user_id      =   $request->user_id;
            $user_pic->src          =   $filename;
            $user_pic->photo_source =   'Local';

            $user_pic->save();  // Update a profile picture record

            $this->helper->flash_message('success', trans('messages.profile.picture_uploaded')); // Call flash message function
            return redirect('users/edit/media');
        }
    }
    public function remove_images(Request $request)
    {
        $user_pic = ProfilePicture::find($request->user_id);

        $user_pic->user_id      =   $request->user_id;
        $user_pic->src          =   '';
        $user_pic->photo_source =   'Local';

        $user_pic->save();

        return json_encode(['success' => 'true','profile_pic_src' => $user_pic->src,'original_src' => $user_pic->getOriginal('src')]);

    }
    /**
     * Send New Confirmation Email
     *
     * @param array $request Input values
     * @param array $email_controller Instance of EmailController
     * @return redirect to Dashboard
     */
    public function request_new_confirm_email(Request $request, EmailController $email_controller)
    {
        $user = User::find(Auth::user()->id);

        $email_controller->new_email_confirmation($user);

        $this->helper->flash_message('success', trans('messages.profile.new_confirm_link_sent',['email'=>$user->email])); // Call flash message function
        if($request->redirect == 'verification')
            return redirect('users/edit_verification');
        else
            return redirect('dashboard');
    }

    public function verification(Request $request)
    {
        $data['fb_url'] = $this->fb->getUrlConnect();

        return view('users.verification', $data);
    }

    public function facebookConnect(Request $request)
    {
        if($request->error_code == 200){
         //   $this->helper->flash_message('danger', $request->error_description); // Call flash message function
             return redirect('users/edit_verification'); // Redirect to edit_verification page
        }
        
        $this->fb->generateSessionFromRedirect(); // Generate Access Token Session After Redirect from Facebook

        $response = $this->fb->getData(); // Get Facebook Response

        $userNode = $response->getGraphUser(); // Get Authenticated User Data

        $fb_id = $userNode->getId();

        $verification = UsersVerification::find(Auth::user()->id);

        $verification->facebook = 'yes';
        $verification->fb_id = $fb_id;

        $verification->save();

        $this->helper->flash_message('success', trans('messages.profile.connected_successfully', ['social'=>'Facebook'])); // Call flash message function
        return redirect('users/edit_verification');
    }

    public function facebookDisconnect(Request $request)
    {
        $verification = UsersVerification::find(Auth::user()->id);

        $verification->facebook = 'no';
        $verification->fb_id = '';

        $verification->save();

        $this->helper->flash_message('danger', trans('messages.profile.disconnected_successfully', ['social'=>'Facebook'])); // Call flash message function
        return redirect('users/edit_verification');
    }

    /**
     * Google User redirect to Google Authentication page
     *
     * @return redirect     to Google page
     */
    public function googleLoginVerification()
    {
        Session::put('verification', 'yes');
        
    }

    public function googleConnect(Request $request)
    {
        $google_id = $request->id;

        $verification = UsersVerification::find(Auth::user()->id);

        $verification->google = 'yes';
        $verification->google_id = $google_id;

        $verification->save();

        $this->helper->flash_message('success', trans('messages.profile.connected_successfully', ['social'=>'Google'])); // Call flash message function
        return redirect('users/edit_verification');
    }

    public function googleDisconnect(Request $request)
    {
        $verification = UsersVerification::find(Auth::user()->id);

        $verification->google = 'no';
        $verification->google_id = '';

        $verification->save();

        $this->helper->flash_message('danger', trans('messages.profile.disconnected_successfully', ['social'=>'Google'])); // Call flash message function
        return redirect('users/edit_verification');
    }

    /**
     * LinkedIn User redirect to LinkedIn login page
     *
     * @return redirect     to LinkedIn page
     */
          public function redirectToLinkedin()
    {
        return Socialite::driver('linkedin')->redirect();
    }

     /**
     * LinkedIn User redirect to LinkedIn Authentication page
     *
     * @return redirect     to LinkedIn page
     */
    public function linkedinLoginVerification()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    public function linkedinConnect(Request $request, EmailController $email_controller)
    {
        if ($request->get('error')) {
            // $this->helper->flash_message($request->get('error'), $request->get('error_description'));
            if(Auth::check()) 
                return redirect('users/edit_verification');
            else
                return redirect('login');
        }
        if(Auth::check()) 
        { 
            $verification = UsersVerification::find(Auth::user()->id);
            if ($verification->linkedin == 'yes') {
                $this->helper->flash_message($request->get('Connected'), trans('messages.profile.already_connected'));
                return redirect('users/edit_verification');
            }

            try {
                $userNode = Socialite::driver('linkedin')->user();
            }
            catch (\Exception $e) {
                return redirect('login');
            }
            
            $linkedin_id = $userNode->getId();

            
            $verification->linkedin = 'yes';
            $verification->linkedin_id = $linkedin_id;

            $verification->save();

            $this->helper->flash_message('success', trans('messages.profile.connected_successfully', ['social'=>'LinkedIn'])); // Call flash message function
             return redirect('users/edit_verification');
        }
        else
        {

            $user = Socialite::driver('linkedin')->user();
            //find already user
                $find=User::where('linkedin_id',$user->id)
                    ->orWhere('email',$user->email)->first(); 
          if($find=='')
            {       
                $new_user = new User;
                // New user data
                $new_user->first_name   =   $user->user['firstName'];
                $new_user->last_name    =   $user->user['lastName'];
                $new_user->email        =   $user->email;
                $new_user->linkedin_id  =   $user->id;
                if($user->email == ''){
                   $user = array(
                        'first_name'   =>   $user->user['firstName'],
                        'last_name'    =>   $user->user['lastName'],
                        'email'        =>   $user->email,
                        'linkedin_id'  =>   $user->id,
                        'profile_pic'  =>   $user->avatar_original ? $user->avatar_original : '',
                    );
                    Session::put('linkedin_user_data', $user); 
                    return redirect('users/signup_email'); 
                }
                $new_user->status       =   "Active" ;

                $new_user->save(); // Create a new user

                $user_id = $new_user->id; // Get Last Insert Id

                $user_pic = new ProfilePicture;

                $user_pic->user_id      =   $user_id;
                $user_pic->src          =   $user->avatar_original ? $user->avatar_original : '';
                $user_pic->photo_source =   'LinkedIn';

                $user_pic->save(); // Save Google profile picture

                $user_verification = new UsersVerification;

                $user_verification->user_id      =   $user_id;
                $user_verification->linkedin     =  'yes';

                $user_verification->save();  // Create a users verification record

                $email_controller->welcome_email_confirmation($new_user);

                    if(Session::get('referral')) 
                    {
                        
                    $referral_settings = ReferralSettings::first();

                    $referral_check = Referrals::whereUserId(Session::get('referral'))->sum('creditable_amount');

                    // if($referral_check < $referral_settings->value(1)) 
                    //     {
                            $referral = new Referrals;

                            $referral->user_id                = Session::get('referral');
                            $referral->friend_id              = $user_id;
                            $referral->friend_credited_amount = $referral_settings->value(4);
                            $referral->if_friend_guest_amount = ($referral_check < $referral_settings->value(1)) ? $referral_settings->value(2) : 0;
                            $referral->if_friend_host_amount  = ($referral_check < $referral_settings->value(1)) ? $referral_settings->value(3) : 0;
                            $referral->creditable_amount      = ($referral_check < $referral_settings->value(1)) ? ($referral_settings->value(2) + $referral_settings->value(3)) : 0;
                            $referral->currency_code          = $referral_settings->value(5, 'code');

                            $referral->save();

                            Session::forget('referral');
                         }
                    // }
            }     
            else
            {
                $user_id=$find->id;
            } 
        }

        $users = User::where('id', $user_id)->first();
        
        if(@$users->status != 'Inactive')
        {
            if(Auth::guard()->loginUsingId($user_id)) // Login without using User Id instead of Email and Password
            {
                if(Session::get('ajax_redirect_url'))
                return redirect()->intended(Session::get('ajax_redirect_url')); // Redirect to ajax url 
                else
                return redirect()->intended('dashboard'); // Redirect to dashboard page
            }
            else
            {
                $this->helper->flash_message('danger', trans('messages.login.login_failed')); // Call flash message function
                return redirect('login'); // Redirect to login page
            } 
        }
        else // Call Disabled view file for Inactive user
        {
            /*$data['title'] = 'Disabled ';
            return view('users.disabled', $data);*/
            return redirect('user_disabled');
        }
                // return redirect('auth/linkedin');
            
        
       
    }

    public function linkedinDisconnect(Request $request)
    {
        $verification = UsersVerification::find(Auth::user()->id);

        $verification->linkedin = 'no';
        $verification->linkedin_id = '';

        $verification->save();

        $this->helper->flash_message('danger', trans('messages.profile.disconnected_successfully', ['social'=>'LinkedIn'])); // Call flash message function
        return redirect('users/edit_verification');
    }

    public function get_verification_documents(Request $request) {
        $id_documents = UsersVerificationDocuments::whereType('id_document')->where('user_id', Auth::user()->id)->get();
        $user = User::find(Auth::user()->id);

        return json_encode(array('id_documents' => $id_documents,'id_verification_status' => $user->id_document_verification_status));

    }

    // delete document
    public function delete_document(Request $request) {

        $doc = UsersVerificationDocuments::where('id',$request->image_id)->where('user_id', Auth::user()->id)->first();
        $user = User::find(Auth::user()->id);
        $old_verification_status = $user->verification_status;
        if ($doc != NULL) {
            $doc->delete();
        }
        else{
            return json_encode(['success' => 'true', 'id_verification_status' => $user->id_document_verification_status, 'refresh' => $old_verification_status == 'Verified'?'true':'false']);
        }

        $this->update_verification_status(Auth::user()->id,$request->type);
        $user = User::find(Auth::user()->id);

        return json_encode(['success' => 'true', 'id_verification_status' => $user->id_document_verification_status, 'refresh' => $old_verification_status == 'Verified'?'true':'false']);
    }
    // upload multiple photos ajax call
    public function upload_verification_documents(Request $request) {
        $error = array();
        $user_id = @Auth::user()->id;
        if ($request->type == 'id_document') {

            if ($request->file == NULL) {
                return ['success' => 'false'];
            }
            foreach ($request->file as $key => $value) {
                $uploaded_file = $value;
                $extension = $uploaded_file->getClientOriginalExtension();
                $original_name = $uploaded_file->getClientOriginalName();
                if (in_array($extension, ['png', 'jpg', 'jpeg', 'PNG', 'JPG', 'JPEG', 'pdf','PDF'])) {

                    $dir_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user_id.'/documents/';

                    if (!file_exists($dir_name)) {
                        mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user_id.'/documents/', 0777, true);
                    }

                    $name = pathinfo($original_name, PATHINFO_FILENAME);

                    $name = $this->helper->remove_special_chars($name);
                    $file_name = 'id_document_' . time() . $name . '.' . $extension;

                    if (UPLOAD_DRIVER == 'cloudinary') {
                        $c = $this->helper->cloud_upload($uploaded_file);
                        if ($c['status'] != "error") {
                            $file_name = $c['message']['public_id'];
                        } else {
                            $this->update_verification_status(Auth::user()->id,$request->type);
                            $user = User::find($user_id);
                            return response()->json([
                                'success' => 'false',
                                'id_verification_status' => $user->id_document_verification_status,
                                'error' => [
                                    'error_description' => $c['message'],
                                    'status_code' => "0",

                                ],
                            ]);
                        }
                    } else {
                        $success = $uploaded_file->move('images/users/' . $user_id.'/documents', $file_name);
                        if (!$success) {
                            return back()->withError('Could not upload Id Proof Document');
                        }
                    }

                    $photos = new UsersVerificationDocuments;
                    /*if($photos->where('user_id',$user_id)->count() > 0)
                    {
                    UsersVerificationDocuments::where('user_id', $user_id)->update(['name' => $file_name,'user_id' => $user_id, 'type' => 'id_document']);
                    }

                    else {*/
                    $photos->user_id = $user_id;
                    $photos->name = $file_name;
                    $photos->type = 'id_document';
                    $photos->save();

                    /*}*/

                } else {
                    $error = array('error_title' => ' Photo Error', 'error_description' => 'The file must be a file of type: jpg, png, jpeg.');
                }
            }
            $this->update_verification_status(Auth::user()->id,$request->type);
        }
        
        else{

        }

        $id_documents = UsersVerificationDocuments::whereType('id_document')->where('user_id', Auth::user()->id)->get();
        $user = User::find($user_id);

        return json_encode(['success' => count($error) ? 'false' : 'true', 'id_documents' => $id_documents, 'id_verification_status' => $user->id_document_verification_status, 'error' => $error]);

    }

    /*
           *Update the verification status and photos status
           * Status - No,Pending,Verified,Resubmit
           * @param  Post method inputs
           * @return Response in Json
    */
    public function update_verification_status($user_id,$type) {
        $user = User::find($user_id);
        if($user) {
            $docs = UsersVerificationDocuments::whereType($type)->where('user_id', $user_id)->get();
            if (count($docs)>0) {
                $user->verification_status = 'Pending';
            }else{
                $user->verification_status = 'No';
            }
            $user->save();
            $type = $type;
            UsersVerificationDocuments::whereType($type)->where('user_id', $user_id)->update(['status' => 'Pending']);
        }
        return true;
    }


}
