<?php

/**
 * Payment Gateway Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Payment Gateway
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Http\Start\Helpers;
use Validator;

class PaymentGatewayController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update Payment Gateway Data
     *
     * @return redirect     to payment_gateway
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = PaymentGateway::get();

            return view('admin.payment_gateway', $data);
        }
        else if($request->submit)
        {
            // Payment Gateway Validation Rules
            $rules = array(
                    'paypal_username'  => 'required',
                    'paypal_password'  => 'required',
                    'paypal_signature' => 'required',
                    'paypal_client' => 'required',
                    'paypal_secret' => 'required',
                    'stripe_publish' => 'required',
                    'stripe_secret' => 'required',
                    'stripe_client_id' => 'required',
                    );

            // Payment Gateway Validation Custom Names
            $niceNames = array(
                        'paypal_username'  => 'PayPal Username',
                        'paypal_password'  => 'PayPal Password',
                        'paypal_signature' => 'PayPal Signature',
                        'paypal_client' => 'PayPal Client Id',
                        'paypal_secret' => 'PayPal Secret key',
                        'stripe_publish' => 'Stripe Publish key',
                        'stripe_secret' => 'Stripe Secret key',
                        'stripe_client_id' => 'Stripe Client Id',
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                PaymentGateway::where(['name' => 'username', 'site' => 'PayPal'])->update(['value' => $request->paypal_username]);

                PaymentGateway::where(['name' => 'password', 'site' => 'PayPal'])->update(['value' => $request->paypal_password]);

                PaymentGateway::where(['name' => 'signature', 'site' => 'PayPal'])->update(['value' => $request->paypal_signature]);

                PaymentGateway::where(['name' => 'mode', 'site' => 'PayPal'])->update(['value' => $request->paypal_mode]);
                //paypal client and secret filed update 
                PaymentGateway::where(['name' => 'client', 'site' => 'PayPal'])->update(['value' => $request->paypal_client]);
                PaymentGateway::where(['name' => 'secret', 'site' => 'PayPal'])->update(['value' => $request->paypal_secret]);

                PaymentGateway::where(['name' => 'publish', 'site' => 'Stripe'])->update(['value' => $request->stripe_publish]);
                PaymentGateway::where(['name' => 'secret', 'site' => 'Stripe'])->update(['value' => $request->stripe_secret]);
                PaymentGateway::where(['name' => 'client_id', 'site' => 'Stripe'])->update(['value' => $request->stripe_client_id]);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
            
                return redirect(ADMIN_URL.'/payment_gateway');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/payment_gateway');
        }
    }
}
