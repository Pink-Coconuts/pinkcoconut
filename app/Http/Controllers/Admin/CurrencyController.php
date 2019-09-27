<?php

/**
 * Currency Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Currency
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\CurrencyDataTable;
use App\Models\Currency;
use App\Models\RoomsPrice;
use App\Models\SiteSettings;

use App\Models\Fees;
/*HostExperiencePHPCommentStart*/
use App\Models\HostExperienceCities;
use App\Models\HostExperiences;
/*HostExperiencePHPCommentEnd*/
use App\Models\Referrals;
use App\Models\ReferralSettings;
use App\Models\Reservation;
use App\Models\User;
use App\Models\PayoutPreferences;
use App\Models\CouponCode;

use App\Http\Start\Helpers;
use Validator;

class CurrencyController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Currency
     *
     * @param array $dataTable  Instance of CurrencyDataTable
     * @return datatable
     */
    public function index(CurrencyDataTable $dataTable)
    {
        return $dataTable->render('admin.currency.view');
    }

    /**
     * Add a New Currency
     *
     * @param array $request  Input values
     * @return redirect     to Currency view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.currency.add');
        }
        else if($request->submit)
        {
            $rules = array(
                    'name'   => 'required|unique:currency',
                    'code'   => 'required|unique:currency',
                    'symbol' => 'required',
                    'rate'   => 'required|numeric|min:0.01',
                    'status' => 'required'
                    );

            $niceNames = array(
                        'name'   => 'Name',
                        'code'   => 'Code',
                        'symbol' => 'Symbol',
                        'rate'   => 'Rate',
                        'status' => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $currency = new Currency;

                $currency->name   = $request->name;
                $currency->code   = $request->code;
                $currency->symbol = $request->symbol;
                $currency->rate   = $request->rate;
                $currency->default_currency = '0';
                $currency->status = $request->status;

                $currency->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/currency');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/currency');
        }
    }
/**
     * Update Currency Details
     *
     * @param array $request    Input values
     * @return redirect     to Currency View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = Currency::find($request->id);
            if(!$data['result'])
            {
                $this->helper->flash_message('danger', 'Invalid ID'); // Call flash message function
                return redirect(ADMIN_URL.'/currency');
            }
            return view('admin.currency.edit', $data);
        }
        else if($request->submit)
        {
            $rules = array(
                    'name'   => 'required|unique:currency,name,'.$request->id,
                    'code'   => 'required|unique:currency,code,'.$request->id,
                    'symbol' => 'required',
                    'rate'   => 'required|numeric|min:0.01',
                    'status' => 'required'
                    );

            $niceNames = array(
                        'name'   => 'Name',
                        'code'   => 'Code',
                        'symbol' => 'Symbol',
                        'rate'   => 'Rate',
                        'status' => 'Status'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $currency = Currency::find($request->id);

                if($request->status == 'Inactive' || $request->code != $currency->code)
                {
                    $result= $this->canDestroy($currency->id, $currency->code);
                    if($result['status'] == 0)
                    {
                        $this->helper->flash_message('error',$result['message']);
                        return back();
                    }
                }

                $currency->name   = $request->name;
                $currency->code   = $request->code;
                $currency->symbol = $request->symbol;
                $currency->rate   = $request->rate;
                $currency->status = $request->status;
                try
                {
                    $currency->save();
                }
                catch(\Exception $e)
                {
                    $this->helper->flash_message('error','Sorry this currency is already in use. So cannot update the code.');
                    return back();
                }

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/currency');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/currency');
        }
    }

    /**
     * Delete Currency
     *
     * @param array $request    Input values
     * @return redirect     to Currency View
     */
    public function delete(Request $request)
    {
        $currency = Currency::find($request->id);
        $result= $this->canDestroy($currency->id, $currency->code);
        if($result['status'] == 0)
        {
            $this->helper->flash_message('error',$result['message']);
            return back();
        }
        try
        {
            Currency::find($request->id)->delete();
        }
        catch(\Exception $e)
        {
            $this->helper->flash_message('error','Sorry this currency is already in use. So cannot delete.');
            return back();
        }
        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect(ADMIN_URL.'/currency');
    }

    public function canDestroy($id, $code)
    {
        $fees_currency = Fees::where('name', 'currency')->first()->value == $code;
        /*HostExperiencePHPCommentStart*/
        $host_experience_cities = HostExperienceCities::where('currency_code', $code)->count();
        $host_experiences = HostExperiences::where('currency_code', $code)->count();
        /*HostExperiencePHPCommentEnd*/
        $referrals = Referrals::where('currency_code', $code)->count();
        $referral_settings_currency = ReferralSettings::where('name', 'currency_code')->first()->value == $code;
        $reservations = Reservation::where('currency_code', $code)->count();
        $users = User::where('currency_code', $code)->count();
        $payout_preferences = PayoutPreferences::where('currency_code', $code)->count();
        $coupon_code = CouponCode::where('currency_code', $code)->count();

        $active_currency_count = Currency::where('status', 'Active')->count();
        $is_default_currency = Currency::find($id)->default_currency;
        $paypal_currency = SiteSettings::where('name','paypal_currency')->first()->value;
        $is_rooms_currency = RoomsPrice::where('currency_code', $code)->count();

        $return  = ['status' => '1', 'message' => ''];
        if($active_currency_count < 1)
        {
            $return = ['status' => 0, 'message' => 'Sorry, Minimum one Active currency is required.'];
        }
        else if($is_default_currency == 1)
        {
            $return = ['status' => 0, 'message' => 'Sorry, This currency is Default Currency. So, change the Default Currency.'];
        }
        else if($paypal_currency == $code)
        {
            $return = ['status' => 0, 'message' => 'Sorry, This currency is Paypal Currency. So, change the Paypal Currency.'];
        }
        else if($is_rooms_currency > 0)
        {
            $return = ['status' => 0, 'message' => 'Sorry, Rooms have this Currency. So, Delete that Rooms or Change that Rooms Currency.'];   
        }
        else if($reservations > 0)
        {
            $return = ['status' => 0, 'message' => 'Sorry, Reservations have this Currency. So, Delete that Reservations or Change that Reservations Currency.'];   
        }
        else if($fees_currency) 
        {
            $return = ['status' => 0, 'message' => 'Sorry, This currency is used in Fees module. Please change the fees currency.'];   
        }
        else if($referral_settings_currency) 
        {
            $return = ['status' => 0, 'message' => 'Sorry, This currency is used in Referral Settings module. Please change the Referral Settings currency.'];   
        }
        /*HostExperiencePHPCommentStart*/
        else if($host_experience_cities) 
        {
            $return = ['status' => 0, 'message' => 'Sorry, Host Experience Cities have this Currency. So, Delete that Host Experience Cities Host Experience City Currency.'];   
        }
        else if($host_experiences) 
        {
            $return = ['status' => 0, 'message' => 'Sorry, Host Experiences have this Currency. So, Delete that Host Experiences or Change that Host Experiences Currency.'];   
        }
        /*HostExperiencePHPCommentEnd*/
        else if($referrals) 
        {
            $return = ['status' => 0, 'message' => 'Sorry, Referrals have this Currency. So, Delete that Referrals or Change that Referrals Currency.'];   
        }
        else if($users) 
        {
            $return = ['status' => 0, 'message' => 'Sorry, Users have this Currency. So, Delete that Users or Change that Users Currency.'];   
        }
        else if($payout_preferences) 
        {
            $return = ['status' => 0, 'message' => 'Sorry, Payout Preferences have this Currency. So, Delete that Payout Preferences or Change that Payout Preferences Currency.'];   
        }
        else if($coupon_code) 
        {
            $return = ['status' => 0, 'message' => 'Sorry, Coupon Code have this Currency. So, Delete that Coupon Code or Change that Coupon Code Currency.'];   
        }

        return $return;
    }

}
