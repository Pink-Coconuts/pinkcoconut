<?php

/**
 * ReferralSettings Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    ReferralSettings
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ReferralSettings;
use App\Models\Currency;
use App\Http\Start\Helpers;
use Validator;

class ReferralSettingsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update ReferralSettings Data
     *
     * @return redirect     to referral_settings
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = ReferralSettings::get();
            
            $data['currency'] = Currency::where('status','Active')->pluck('code', 'code');

            return view('admin.referral_settings', $data);
        }
        else if($request->submit)
        {
            // ReferralSettings Validation Rules
            $rules = array(
                    'per_user_limit' => 'required|numeric',
                    'if_friend_guest_credit' => 'required|numeric',
                    'if_friend_host_credit' => 'required|numeric',
                    'new_referral_user_credit' => 'required|numeric',
                    );

            // ReferralSettings Validation Custom Names
            $niceNames = array(
                        'per_user_limit' => 'Per User Credit Limit',
                        'if_friend_guest_credit' => 'If Friend Guest',
                        'if_friend_host_credit' => 'If Friend Host',
                        'new_referral_user_credit' => 'Friend Travel Credit',
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                ReferralSettings::where(['name' => 'per_user_limit'])->update(['value' => $request->per_user_limit]);
                ReferralSettings::where(['name' => 'if_friend_guest_credit'])->update(['value' => $request->if_friend_guest_credit]);
                ReferralSettings::where(['name' => 'if_friend_host_credit'])->update(['value' => $request->if_friend_host_credit]);
                ReferralSettings::where(['name' => 'new_referral_user_credit'])->update(['value' => $request->new_referral_user_credit]);
                ReferralSettings::where(['name' => 'currency_code'])->update(['value' => $request->currency_code]);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/referral_settings');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/referral_settings');
        }
    }
}
