<?php

/**
 * Users Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Users
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\UsersDataTable;
use App\Models\User;
use App\Models\ProfilePicture;
use App\Models\UsersVerification;
use App\Models\Rooms;
use App\Models\Reservation;
use App\Models\Referrals;
use App\Models\SavedWishlists;
use App\Models\Wishlists;
use App\Models\HostExperiences;
use App\Models\PayoutPreferences;
use App\Models\UsersVerificationDocuments;
use App\Models\Messages;
use App\Http\Start\Helpers;
use App\Http\Controllers\EmailController;
use Validator;
use DB;
use Carbon\Carbon;

class UsersController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Users
     *
     * @param array $dataTable  Instance of UsersDataTable
     * @return datatable
     */
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('admin.users.view');
    }

    /**
     * Add a New User
     *
     * @param array $request  Input values
     * @return redirect     to Users view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.users.add');
        }
        else if($request->submit)
        {
            // Add User Validation Rules
            $rules = array(
                    'first_name' => 'required',
                    'last_name'  => 'required',
                    'email'      => 'required|email|unique:users',
                    //'phone_no'   => 'required|regex:/[0-9]{10}/|unique:users',
                    'password'   => 'required',
                    'dob'        => 'required',
                    'status'     => 'required'
                    );

            // Add User Validation Custom Names
            $niceNames = array(
                        'first_name' => 'First name',
                        'last_name'  => 'Last name',
                        'email'      => 'Email',
                        //'phone_no'   => 'Phone No',
                        'password'   => 'Password',
                        'dob'        => 'DOB',
                        'status'     => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $user = new User;

                $user->first_name = $request->first_name;
                $user->last_name  = $request->last_name;
                $user->email      = $request->email;
                //$user->phone_no   = $request->phone_no;
                $user->password   = bcrypt($request->password);
                $user->dob        = date('Y-m-d', $this->helper->custom_strtotime($request->dob));
                $user->status     = $request->status;

                $user->save();

                $user_pic = new ProfilePicture;

                $user_pic->user_id      =   $user->id;
                $user_pic->src          =   "";
                $user_pic->photo_source =   'Local';

                $user_pic->save();

                $users_verification = new UsersVerification;

                $users_verification->user_id      =   $user->id;
                $users_verification->email        =   "yes";

                $users_verification->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/users');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/users');
        }
    }

    /**
     * Update User Details
     *
     * @param array $request    Input values
     * @return redirect     to Users View
     */
    public function update(Request $request,EmailController $email_controller)
    {
        if(!$_POST)
        {
            $data['result'] = User::find($request->id);
            $data['id_documents'] = UsersVerificationDocuments::whereType('id_document')->where('user_id', $request->id)->get();
            $data['user_type'] = $request->type;

            return view('admin.users.edit', $data);
        }
        else if($request->submit)
        {
            $user = User::find($request->id);
            if(!$user) {
                $this->helper->flash_message('error', 'Invalid user.');
                return redirect(ADMIN_URL.'/users');
            }
            // Edit User Validation Rules
            $rules = array(
                    'first_name' => 'required',
                    'last_name'  => 'required',
                    'email'      => 'required|email|unique:users,email,'.$request->id,
                    'dob'        => 'required',
                    'status'     => 'required'
                    );

             if($user->verification_status != 'Connect'){
                $rules += array(
                    'id_document_verification_status'  => 'required'
                    );
            }

            if($request->id_document_verification_status == 'Resubmit'){
                $rules['id_resubmit_reason'] = 'required';
            }

            // Edit User Validation Custom Fields Name
            $niceNames = array(
                        'first_name' => 'First name',
                        'last_name'  => 'Last name',
                        'email'      => 'Email',
                        'dob'        => 'DOB',
                        'status'     => 'Status',
                        'id_document_verification_status'     => 'ID Document Status',
                        'id_resubmit_reason'     => 'Resubmit Reason',
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->errors()->first('dob')==null) {
                $today = new Carbon();
                $before_18_years = $today->subYears(18)->format('U');
                $date_of_birth = $this->helper->custom_strtotime($request->dob);
                if ($date_of_birth>$before_18_years) {
                    $validator->errors()->add('dob', 'DOB should be before 18 years');
                }
            }
            if (count($validator->errors())>0)
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $user = User::find($request->id);

                $user->first_name = $request->first_name;
                $user->last_name  = $request->last_name;
                $user->email      = $request->email;
                $user->dob        = date('Y-m-d', $this->helper->custom_strtotime($request->dob));
                $user->status     = $request->status;

                if($request->id_document_verification_status == 'Verified'){
                    $email_controller->document_verified($user);
                }

                if($request->id_document_verification_status == 'Resubmit' && $request->id_resubmit_reason != '' && ( $request->id_resubmit_reason != $user->id_resubmit_reason || $request->id_document_verification_status != $user->id_document_verification_status) ) {
                    // send resubmit message to user 
                    $message = new Messages;
                    $message->user_to = $request->id;
                    $message->user_from = $request->id;
                    $message->reservation_id = NULL;
                    $message->message_type = 13;
                    $message->message = $request->id_resubmit_reason;
                    $message->save();
                }

                if($user->id_document_verification_status != ''){
                    UsersVerificationDocuments::whereType('id_document')->where('user_id', $request->id)->update(['status' => $request->id_document_verification_status]);
                }

                if($user->verification_status != 'Connect'){
                    $verification_doc = UsersVerificationDocuments::where('user_id', $request->id)->first();
                    $user->verification_status = $verification_doc->user_verification_status;
                    
                }

                if($request->password != '')
                    $user->password = bcrypt($request->password);

                $user->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                if($request->password != '')
                {
                    User::clearUserSession($request->id);
                }
                return redirect(ADMIN_URL.'/users');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/users');
        }
    }

    /**
     * Delete User
     *
     * @param array $request    Input values
     * @return redirect     to Users View
     */
    public function delete(Request $request)
    {
        $check = Rooms::whereUserId($request->id)->count();
        $reservation_check = Reservation::where('user_id' , $request->id)->count();
        $referrals_check = Referrals::where('user_id', $request->id)->orWhere('friend_id', $request->id)->count();
        $host_experiences = HostExperiences::where('user_id', $request->id)->count();

        if($check) {
            $this->helper->flash_message('error', 'This user has some rooms. Please delete that rooms, before deleting this user.'); // Call flash message function
            return redirect(ADMIN_URL.'/users');
        }
        if($reservation_check) {
            $this->helper->flash_message('error', "This user has some reservations. We can't delete this user"); // Call flash message function
            return redirect(ADMIN_URL.'/users');
        }
        if($referrals_check) {
            $this->helper->flash_message('error', "This user has some referrals. We can't delete this user"); // Call flash message function
            return redirect(ADMIN_URL.'/users');   
        }
        if($host_experiences) {
            $this->helper->flash_message('error', "This user has some Host experiences. We can't delete this user"); // Call flash message function
            return redirect(ADMIN_URL.'/users');   
        }
        else {

            $exists_rnot = User::find($request->id);
            if(@$exists_rnot){
            SavedWishlists::where('user_id', $request->id)->delete();
            Wishlists::where('user_id', $request->id)->delete();
            PayoutPreferences::where('user_id', $request->id)->delete();
            User::find($request->id)->forceDelete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
                               }
           else{
             $this->helper->flash_message('error', 'This User Already Deleted.');
           }
       }

        return redirect(ADMIN_URL.'/users');
    }
}
