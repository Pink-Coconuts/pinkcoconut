<?php

/**
 * Admin Users Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Admin Users
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\AdminusersDataTable;
use App\Models\Admin;
use App\Models\Role;
use App\Http\Start\Helpers;
use Validator;

class AdminusersController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Admin Users
     *
     * @param array $dataTable  Instance of AdminuserDataTable
     * @return datatable
     */
    public function index(AdminusersDataTable $dataTable)
    {
        return $dataTable->render('admin.admin_users.view');
    }

    /**
     * Add a New Admin User
     *
     * @param array $request  Input values
     * @return redirect     to Admin Users view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            // Get Roles for Dropdown
            $data['roles'] = Role::all()->pluck('name','id');

            return view('admin.admin_users.add', $data);
        }
        else if($request->submit)
        {
            // Add Admin User Validation Rules
            $rules = array(
                    'username'   => 'required|unique:admin',
                    'email'      => 'required|email|unique:admin',
                    'password'   => 'required',
                    'role'       => 'required',
                    'status'     => 'required'
                    );

            // Add Admin User Validation Custom Names
            $niceNames = array(
                        'username'   => 'Username',
                        'email'      => 'Email',
                        'password'   => 'Password',
                        'role'       => 'Role',
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
                $admin = new Admin;

                $admin->username = $request->username;
                $admin->email    = $request->email;
                $admin->password = bcrypt($request->password);
                $admin->status   = $request->status;

                $admin->save();

                $admin->attachRole($request->role); // Insert Role Id to role_user table

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/admin_users');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/admin_users');
        }
    }

    /**
     * Update Admin User Details
     *
     * @param array $request    Input values
     * @return redirect     to Admin Users View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
            $data['result']  = Admin::find($request->id);
            
            $data['roles']   = Role::all()->pluck('name', 'id');
            
            $data['role_id'] = Role::role_user($request->id)->role_id;

            return view('admin.admin_users.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Admin User Validation Rules
            $rules = array(
                    'username'   => 'required|unique:admin,username,'.$request->id,
                    'email'      => 'required|email|unique:admin,email,'.$request->id,
                    'role'       => 'required',
                    'status'     => 'required'
                    );

            // Edit Admin User Validation Custom Fields Name
            $niceNames = array(
                        'username'   => 'Username',
                        'email'      => 'Email',
                        'role'       => 'Role',
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
                if($request->status == 'Inactive'){
                    $activeAdminUsers = Admin::where('status' , 'Active')->where('id' , '!=', $request->id)->get();
                    if($activeAdminUsers->count() < 1){
                        $this->helper->flash_message('danger', 'Status Cannot be Updated. Because it is the only one admin account'); // Call flash message function
                        return redirect(ADMIN_URL.'/edit_admin_user/'.$request->id);
                    }
                }
                $admin = Admin::find($request->id);

                $admin->username = $request->username;
                $admin->email    = $request->email;
                $admin->status   = $request->status;
                
                if($request->password != '')
                    $admin->password = bcrypt($request->password);

                $admin->save();

                Admin::update_role($request->id, $request->role);   // Update Role Id to role_user table

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/admin_users');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/admin_users');
        }
    }

    /**
     * Delete Admin User
     *
     * @param array $request    Input values
     * @return redirect     to Admin Users View
     */
    public function delete(Request $request)
    {
        $activeAdminUsers = Admin::where(['status' => 'Active'])->where('id' , '!=', $request->id)->get();
        if($activeAdminUsers->count() < 1){
            $this->helper->flash_message('danger', 'User cannot be deleted. Because it is the only one admin account'); // Call flash message function
            return redirect(ADMIN_URL.'/admin_users');
        }
        Admin::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect(ADMIN_URL.'/admin_users');
    }
}
