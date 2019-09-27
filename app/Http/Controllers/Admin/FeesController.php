<?php

/**
 * Fees Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Fees
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Fees;
use App\Models\Currency;
use App\Http\Start\Helpers;
use App\Http\Helper\PaymentHelper;
use Validator;

class FeesController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers
    protected $payment_helper;

    public function __construct()
    {
        $this->helper = new Helpers;
        $this->payment_helper = new PaymentHelper;
    }

    /**
     * Load View and Update Fees Data
     *
     * @return redirect     to fees
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = Fees::get();
           
            if($data['result'][13]->value !='')
            {
                $data['airportfee_currency'] = Currency::where('code',$data['result'][13]->value)->first()->id;
            }
            else
            {
                $data['airportfee_currency'] = Currency::where('default_currency','1')->first()->id;
            }

            if($data['result'][3]->value !='')
            {
                $data['penalty_currency'] = Currency::where('code',$data['result'][3]->value)->first()->id;
            }
            else
            {
                $data['penalty_currency'] = Currency::where('default_currency','1')->first()->id;
            }

            if($data['result'][8]->value !='')
            {
                $data['currency_fee'] = Currency::where('code',$data['result'][8]->value)->first()->id;
            }
            else
            {
                $data['currency_fee'] = Currency::where('default_currency','1')->first()->id;
            }
            /*HostExperiencePHPCommentStart*/
            if($data['result'][11]->value !='')
            {
                $data['expr_currency_fee'] = Currency::where('code',$data['result'][11]->value)->first()->id;
            }
            else
            {
                $data['expr_currency_fee'] = Currency::where('default_currency','1')->first()->id;
            }
            /*HostExperiencePHPCommentEnd*/
            $data['currency'] = Currency::where('status','Active')->pluck('code', 'id');

            return view('admin.fees', $data);
        }
        else if($request->submit)
        {
            $currency_code = Currency::where('id',$request->currency_fee)->first()->code;
            $min_amount = $this->payment_helper->currency_convert('USD', $currency_code, 1);

            // Fees Validation Rules
            $rules = array(
                    'service_fee' => 'required|numeric',
                    'host_fee' => 'required|numeric',
                    'min_service_fee' => 'required|numeric|min:'.$min_amount,
                    'currency_fee'  => 'required'
                    );

            // Fees Validation Custom Names
            $niceNames = array(
                        'service_fee' => 'Service Fee',
                        'host_fee' => 'Host Fee',
                        'min_service_fee' => 'Minimum Service Fee',
                        'currency_fee'  => 'Currency'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                Fees::where(['name' => 'service_fee'])->update(['value' => $request->service_fee]);
                Fees::where(['name' => 'host_fee'])->update(['value' => $request->host_fee]);
                Fees::where(['name' => 'min_service_fee'])->update(['value' => $request->min_service_fee]);
                
                $currency_code = Currency::where('id',$request->currency_fee)->first()->code;
                Fees::where(['name' => 'fees_currency'])->update(['value' => $currency_code]);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
            
                return redirect(ADMIN_URL.'/fees');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/fees');
        }
    }

    public function airport_fee(Request $request){
        if($request->submit)
        {
            $currency_code = Currency::where('id',$request->airportfee_currency)->first()->code;
            // Fees Validation Rules
            $rules = array(
                    'airport_fee' => 'required|numeric',
                    'airportfee_currency'  => 'required'
                    );

            // Fees Validation Custom Names
            $niceNames = array(
                        'airport_fee' => 'Airport Fee',                       
                        'airportfee_currency'  => 'Currency'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                Fees::where(['name' => 'airport_fee'])->update(['value' => $request->airport_fee]);
                Fees::where(['name' => 'airport_fee_currency'])->update(['value' => $currency_code]);
                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/fees');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/fees');
        }

    }

    public function host_service_fees(Request $request)
    {
        if($request->submit)
        {
            $currency_code = Currency::where('id',$request->expr_currency_fee)->first()->code;
            $min_amount = $this->payment_helper->currency_convert('USD', $currency_code, 1);
            // Fees Validation Rules
            $rules = array(
                    'host_service_fees' => 'required|numeric',
                    'expr_min_service_fee' => 'required|numeric|min:'.$min_amount,
                    'expr_currency_fee'  => 'required'
                    );

            // Fees Validation Custom Names
            $niceNames = array(
                        'host_service_fees' => 'Host Experience Service Fee',
                        'expr_min_service_fee' => 'Host Experience Minimum Service Fee',
                        'expr_currency_fee'  => 'Currency'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                Fees::where(['name' => 'experience_service_fee'])->update(['value' => $request->host_service_fees]);
                Fees::where(['name' => 'expr_min_service_fee'])->update(['value' => $request->expr_min_service_fee]);

                $currency_code = Currency::where('id',$request->expr_currency_fee)->first()->code;
                Fees::where(['name' => 'expr_fees_currency'])->update(['value' => $currency_code]);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
            
                return redirect(ADMIN_URL.'/fees');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/fees');
        }
    }

    public function host_penalty_fees(Request $request)
    {
        if($request->submit)
        {
            $rules = [];

            if($request->penalty_mode == 1)
            {
                // Fees Validation Rules
                $rules = array(
                    'penalty_currency'  => 'required',
                    'before_seven_days' => 'required|numeric',
                    'after_seven_days'  => 'required|numeric',
                    'cancel_limit'      => 'required|numeric'
                    );
            }

            // Fees Validation Custom Names
            $niceNames = array(
                        'penalty_currency'  => 'Currency',
                        'before_seven_days' => 'Cancel Before Seven days',
                        'after_seven_days'  => 'Cancel After Seven days',
                        'cancel_limit'      => 'Cancel Limit'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {   
                $currency_code = Currency::where('id',$request->penalty_currency)->first()->code;

                Fees::where(['name' => 'host_penalty'])->update(['value' => $request->penalty_mode]);
                    
                if($request->penalty_mode == 1)
                {
                    Fees::where(['name' => 'currency'])->update(['value' => $currency_code]);
                    Fees::where(['name' => 'before_seven_days'])->update(['value' => $request->before_seven_days]);
                    Fees::where(['name' => 'after_seven_days'])->update(['value' => $request->after_seven_days]);
                    Fees::where(['name' => 'cancel_limit'])->update(['value' => $request->cancel_limit]);
                }

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/fees');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/fees');
        }
    }
}
