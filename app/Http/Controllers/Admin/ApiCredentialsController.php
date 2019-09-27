<?php

/**
 * Api Credentials Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Api Credentials
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ApiCredentials;
use App\Http\Start\Helpers;
use Validator;

class ApiCredentialsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update Api Credentials
     *
     * @return redirect     to api_credentials
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = ApiCredentials::get();

            return view('admin.api_credentials', $data);
        }
        else if($request->submit)
        {
            // Api Credentials Validation Rules
            $rules = array(
                    'facebook_client_id'     => 'required',
                    'facebook_client_secret' => 'required',
                    'google_client_id'       => 'required',
                    'google_client_secret'   => 'required',
                    'google_map_key'         => 'required',
                    'google_map_server_key'  => 'required',
                    'linkedin_client_id'     => 'required',
                    'linkedin_client_secret' => 'required',
                    'nexmo_api'              => 'required',
                    'nexmo_secret'           => 'required',
                    'nexmo_from'             => 'required',
                    'cloud_name'             => 'required',
                    'cloud_key'              => 'required',
                    'cloud_secret'           => 'required',
                    'cloud_base_url'         => 'required',
                    'cloud_secure_url'       => 'required',
                    'cloud_api_url'          => 'required',
                    );

            // Api Credentials Validation Custom Names
            $niceNames = array(
                        'facebook_client_id'     => 'Facebook Client ID',
                        'facebook_client_secret' => 'Facebook Client Secret',
                        'google_client_id'       => 'Google Client ID',
                        'google_client_secret'   => 'Google Client Secret',
                        'google_map_key'         => 'Google Map Browser Key',
                        'google_map_server_key'  => 'Google Map Server Key',
                        'linkedin_client_id'     => 'LinkedIn Client ID',
                        'linkedin_client_secret' => 'LinkedIn Client Secret',
                        'cloud_name'             => 'Cloudinary Name',
                        'cloud_key'              => 'Cloudinary Key',
                        'cloud_secret'           => 'Cloudinary Secret',
                        'cloud_base_url'         => 'Cloudinary BaseUrl',
                        'cloud_secure_url'       => 'Cloudinary SecureUrl',
                        'cloud_api_url'          => 'Cloudinary ApiUrl',
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                ApiCredentials::where(['name' => 'client_id', 'site' => 'Facebook'])->update(['value' => $request->facebook_client_id]);
                ApiCredentials::where(['name' => 'client_secret', 'site' => 'Facebook'])->update(['value' => $request->facebook_client_secret]);

                ApiCredentials::where(['name' => 'client_id', 'site' => 'Google'])->update(['value' => $request->google_client_id]);
                ApiCredentials::where(['name' => 'client_secret', 'site' => 'Google'])->update(['value' => $request->google_client_secret]);

                ApiCredentials::where(['name' => 'key', 'site' => 'GoogleMap'])->update(['value' => $request->google_map_key]);
                ApiCredentials::where(['name' => 'server_key', 'site' => 'GoogleMap'])->update(['value' => $request->google_map_server_key]);

                ApiCredentials::where(['name' => 'client_id', 'site' => 'LinkedIn'])->update(['value' => $request->linkedin_client_id]);
                ApiCredentials::where(['name' => 'client_secret', 'site' => 'LinkedIn'])->update(['value' => $request->linkedin_client_secret]);

                ApiCredentials::where(['name' => 'key', 'site' => 'Nexmo'])->update(['value' => $request->nexmo_api]);
                ApiCredentials::where(['name' => 'secret', 'site' => 'Nexmo'])->update(['value' => $request->nexmo_secret]);
                ApiCredentials::where(['name' => 'from', 'site' => 'Nexmo'])->update(['value' => $request->nexmo_from]);
                
                ApiCredentials::where(['name' => 'cloudinary_name', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_name]);
                ApiCredentials::where(['name' => 'cloudinary_key', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_key]);
                ApiCredentials::where(['name' => 'cloudinary_secret', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_secret]);
                ApiCredentials::where(['name' => 'cloud_base_url', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_base_url]);
                ApiCredentials::where(['name' => 'cloud_secure_url', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_secure_url]);
                ApiCredentials::where(['name' => 'cloud_api_url', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_api_url]);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/api_credentials');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/api_credentials');
        }
    }
}
