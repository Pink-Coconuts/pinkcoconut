<?php

/**
 * Disputes Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Disputes
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\Reservation;
use App\Models\HostExperienceCalendar;
use App\Models\Payouts;
use App\Models\Messages;
use App\Models\Calendar;
use App\Models\HostPenalty;
use App\Models\Rooms;
use App\Models\Fees;
use App\Models\Currency;
use App\Models\Disputes;
use App\Models\DisputeMessages;
use App\Models\DisputeDocuments;
use App\Models\Country;
use App\Models\PaymentGateway;
use Auth;
use DB;
use Session;
use App\Http\Start\Helpers;
use App\Http\Helper\PaymentHelper;
use DateTime;
use Validator;
use Omnipay\Omnipay;

class DisputesController extends Controller
{
    /**
     * Load Current Trips page.
     *
     * @return view Current Trips File
     */
    protected $helper; // Global variable for Helpers instance
    
    protected $payment_helper; // Global variable for PaymentHelper instance

    protected $omnipay; // Global variable for Omnipay instance

    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper = new Helpers;
    }

    /**
     * Setup the Omnipay PayPal API credentials
     *
     * @param string $gateway  PayPal Payment Gateway Method as PayPal_Express/PayPal_Pro
     * PayPal_Express for PayPal account payments, PayPal_Pro for CreditCard payments
     */
    public function setup($gateway = 'PayPal_Express')
    {
        // Create the instance of Omnipay
        $this->omnipay  = Omnipay::create($gateway);

        if($gateway == 'Stripe')
        {
            // Get Stripe Credentials From payment_gateway table
            $stripe_credentials = PaymentGateway::where('site','Stripe')->pluck('value', 'name');
            $this->omnipay->setApiKey(@$stripe_credentials['secret']);
            return ;
        }

        // Get PayPal credentials from payment_gateway table
        $paypal_credentials = PaymentGateway::where('site', 'PayPal')->get();


        if($gateway == 'PayPal_Pro' || $gateway == 'PayPal_Express')
        { 
            $this->omnipay->setUsername($paypal_credentials[0]->value);
            $this->omnipay->setPassword($paypal_credentials[1]->value);
            $this->omnipay->setSignature($paypal_credentials[2]->value);
            $this->omnipay->setTestMode(($paypal_credentials[3]->value == 'sandbox') ? true : false);
            if($gateway == 'PayPal_Express')
                $this->omnipay->setLandingPage('Login');
        }
    }

    /**
    * To view the disputes related to the user
    * @param $request Illuminate\Http\Request
    * @return disputes view
    */
    public function index(Request $request)
    {
        $data['user_id'] = Auth::user()->id; 

        return view('disputes.view', $data);
    }

    /**
    * To get the disputes 
    * @param $request Illuminate\Http\Request
    * @return $result Array 
    */
    public function get_disputes(Request $request)
    {
        $status = $request->status;

        $disputes = Disputes::users()->receivedUnreadMessages()->disputeUser()->userBased()->orderBy('id','desc');
        
        // To calculate the count of disputes in each status
        $default_disputes_count = ['Open' => 0, 'Processing' => 0, 'Closed' => 0];
        $disputes_count = clone($disputes);
        $disputes_count = $disputes_count->select('status', DB::raw('count(*) as total'))
                        ->groupBy('status')->get()->pluck('total', 'status')->toArray();

        $final_disputes_count = array_merge($default_disputes_count, $disputes_count);
        $final_disputes_count['All'] = array_sum($final_disputes_count);
        
        // To get the disputes based on status
        $disputes = $disputes->status($status);
        $disputes_result =  $disputes->paginate(10)->toArray();

        $result = ['disputes_count' => $final_disputes_count, 'disputes_result' => $disputes_result];

        return $result;
    }

    /**
    * To view the disputes details 
    * @param $request Illuminate\Http\Request
    * @return disputes details view
    */
    public function details(Request $request)
    {
        $data['user_id'] = Auth::user()->id; 
        $dispute = Disputes::userBased()->userConversation()->with(['dispute_documents'])->where('id', $request->id);
        $dispute = $data['dispute'] = $dispute->first();
        
        if(!$dispute)
        {
            return redirect('disputes');
        }
        $data['country']          = Country::all()->pluck('long_name', 'short_name');

        DisputeMessages::where('dispute_id', $dispute->id)->userReceived()->update(['read' => '1']);
        
        return view('disputes.details', $data);
    }

    public function documents_slider(Request $request)
    {
        $data['user_id'] = Auth::user()->id; 
        $dispute = Disputes::userBased()->userConversation()->with(['dispute_documents'])->where('id', $request->id);
        $dispute = $data['dispute'] = $dispute->first();
        
        if(!$dispute)
        {
            return redirect('disputes');
        }

        return view('disputes.documents_slider', $data)->render();
    }

    /**
    * To add message to the dispute thread
    * @param $request Illuminate\Http\Request
    * @return disputes thread list item view
    */
    public function keep_talking(Request $request)
    {
        $dispute = Disputes::find($request->id);
        if(!$dispute)
        {
            return json_encode(['status' => 'danger']);
        }

        $user_id = @Auth::user()->id;
        $maximum_dispute_amount = $dispute->maximum_dispute_amount;

        $rules = [
            'message' => 'required',
            'amount' => 'integer|maximum_dispute_amount|min:1',
        ];
        $messages = [
            'amount.min' => trans('validation.min.numeric', ['min' => html_entity_decode($dispute->currency_code).'1']),
            'amount.integer' => trans('validation.numeric'),
            'amount.maximum_dispute_amount' => trans('messages.disputes.maximum_dispute_amount', ['payout_amount' => html_entity_decode($dispute->currency_code).$maximum_dispute_amount]),
        ];
        $attributes= [
            'amount' => trans('messages.account.amount'),
            'message' => trans_choice('messages.dashboard.message', 1),
        ];

        Validator::extend('maximum_dispute_amount', function($attribute, $value, $parameters, $validator) use($maximum_dispute_amount) {
            return ($value <= $maximum_dispute_amount);
        });

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        if($validator->fails())
        {
            $errors = $validator->messages();
            return json_encode(['status' => 'error', 'errors' => $errors]);
        }

        if($dispute->status == 'Closed')
        {
            $errors = $validator->messages();
            if($request->amount)
            {
                $errors->add('amount', trans('messages.disputes.dispute_closed_cant_add_amount'));
                return json_encode(['status' => 'error', 'errors' => $errors]);   
            }
        }

        if($dispute->status == 'Open')
        {
            $dispute->status = 'Processing';
            $dispute->save();
        }
        
        $dispute_message        = new DisputeMessages;
        $dispute_message->dispute_id    = $dispute->id;
        $dispute_message->message_by    = $dispute->reservation->host_or_guest;
        $dispute_message->message_for   = $dispute->reservation->host_or_guest == 'Host' ? 'Guest' : 'Host';
        $dispute_message->user_from     = $user_id;
        $dispute_message->user_to       = $dispute->reservation->host_or_guest == 'Host' ? $dispute->reservation->user_id : $dispute->reservation->host_id;
        $dispute_message->message       = $request->message;
        $dispute_message->currency_code = $dispute->currency_code;
        $dispute_message->amount        = @$request->amount;
        $dispute_message->save();

        $thread_list_item = view('disputes.thread_list_item', ['message' => $dispute_message])->render();
        return json_encode(['status' => 'success', 'content' => $thread_list_item]);
    }

    /**
    * To upload documents for the dispute
    * @param $request Illuminate\Http\Request
    * @return redirect to the dispute details
    */
    public function documents_upload(Request $request)
    {
        $dispute = Disputes::find($request->id);
        if(!$dispute)
        {
            return json_encode(['status' => 'danger']);
        }

        $user_id = @Auth::user()->id;
        $rules = [
            'documents' => 'required',
        ];
        $messages = ['extensionval' => trans('validation.mimes',['values'=>'jpeg,png'])];
        $attributes= [
            'documents' => trans('messages.disputes.documents'),
        ];

        if($request->documents)
        {            
            foreach(@$request->documents as $k => $v)
            {
                $rules['documents.'.$k] = 'mimes:jpeg,png|extensionval';
                $attributes['documents.'.$k] = 'documents';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        if($validator->fails())
        {
            $errors = $validator->messages();
            if($request->documents)
            {            
                foreach(@$request->documents as $k => $v)
                {
                    if($errors->first('documents.'.$k))
                    {
                        $errors->add('documents', $errors->first('documents.'.$k));
                    }
                }
            }

            return back()->withErrors($errors);
        }

        $documents = [];
        $file_path = 'images/disputes/';
        
        foreach($request->documents as $document)
        {
            $errors = $validator->messages();

            $file_name = 'dispute_document_'.time().str_random(4).'.'.$document->getClientOriginalExtension();
            
            if($document)
            {
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $c=$this->helper->cloud_upload($document);
                    if($c['status']!="error")
                    {
                        $file_name=$c['message']['public_id'];
                    }
                    else
                    {
                        $errors->add('documents', $c['message']);
                        return back()->withErrors($errors);
                    }
                }
                else
                {
                    try
                    {
                        $document->move($file_path, $file_name);
                    }
                    catch(\Exception $e)
                    {
                        $errors->add('documents', $e->getMessage());
                        return back()->withErrors($errors);
                    }
                }
            }
            $documents[] = $file_name;
        }

        $file_base_path = base_path($file_path.'/'.$dispute->id);
        if(!file_exists($file_base_path))
        {
            mkdir($file_base_path, 0777, true);
        }

        foreach($documents as $file_name)
        {
            if(UPLOAD_DRIVER !='cloudinary')
            {
                \File::move($file_path.'/'.$file_name, $file_path.'/'.$dispute->id.'/'.$file_name);
            }

            $dispute_document = new DisputeDocuments;
            $dispute_document->dispute_id = $dispute->id;
            $dispute_document->file     = $file_name;
            $dispute_document->uploaded_by     = $user_id;
            $dispute_document->save();
        }

        return back();
    }

    /**
    * To add message to the admin
    * @param $request Illuminate\Http\Request
    * @return disputes thread list item view
    */
    public function involve_site(Request $request)
    {
        $dispute = Disputes::find($request->id);
        if(!$dispute)
        {
            return json_encode(['status' => 'danger']);
        }

        $user_id = @Auth::user()->id;

        $rules = [
            'message' => 'required',
        ];
        $messages = [];
        $attributes= [
            'message' => trans_choice('messages.dashboard.message', 1),
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        if($validator->fails())
        {
            $errors = $validator->messages();

            return json_encode(['status' => 'error', 'errors' => $errors]);
        }

        $dispute_message        = new DisputeMessages;
        $dispute_message->dispute_id    = $dispute->id;
        $dispute_message->message_by    = $dispute->reservation->host_or_guest;
        $dispute_message->message_for   = 'Admin';
        $dispute_message->user_from     = $user_id;
        $dispute_message->user_to       = 0;
        $dispute_message->currency_code = $dispute->reservation->currency_code;
        $dispute_message->message       = $request->message;
        $dispute_message->save();

        $email_controller = new EmailController;
        $email_controller->dispute_admin_conversation($dispute_message->id);

        $thread_list_item = view('disputes.thread_list_item', ['message' => $dispute_message])->render();
        return json_encode(['status' => 'success', 'content' => $thread_list_item]);
    }

    /**
     * To accept the amount requested by the user
     *
     * @param $request Illuminate\Http\Request
     * @return result Json
     **/
    function accept_amount(Request $request)
    {
        $dispute = Disputes::find($request->id);
        if(!$dispute)
        {
            return json_encode(['status' => 'danger']);
        }
        if(!$dispute->can_dispute_accept_form_show())
        {   
            return json_encode(['status' => 'danger']);
        }

        $user_id = @Auth::user()->id;

        $rules = [
            'message' => 'required',
        ];
        $messages = [];
        $attributes= [
            'message' => trans_choice('messages.dashboard.message', 1),
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        if($validator->fails())
        {
            $errors = $validator->messages();
            return json_encode(['status' => 'error', 'errors' => $errors]);
        }

        if($request->payment == 'Pay')
        {
            return json_encode(['status' => 'show_popup', 'target' => 'dispute_payment_popup']);
        }

        $dispute_message                = new DisputeMessages;
        $dispute_message->dispute_id    = $dispute->id;
        $dispute_message->message_by    = $dispute->reservation->host_or_guest;
        $dispute_message->message_for   = $dispute->reservation->host_or_guest == 'Host' ? 'Guest' : 'Host';
        $dispute_message->user_from     = $user_id;
        $dispute_message->user_to       = $dispute->reservation->host_or_guest == 'Host' ? $dispute->reservation->user_id : $dispute->reservation->host_id;
        $dispute_message->currency_code = $dispute->reservation->currency_code;
        $dispute_message->message       = $request->message;
        $dispute_message->save();

        $dispute->final_dispute_amount  = $this->payment_helper->currency_convert($dispute->reservation->currency_code,$dispute->original_currency_code,$dispute->final_dispute_data->get('amount'));

        if($dispute->dispute_by == 'Host')
        {
            $dispute->payment_status = 'Pending';
        }
        else
        {
            $dispute->status = 'Closed';
        }
        $dispute->save();

        if($dispute->status == 'Closed')
        {
            $email_controller = new EmailController;
            $email_controller->dispute_closed($dispute->id);
        }

        $this->helper->flash_message('success', trans('messages.disputes.thanks_for_the_dispute_accept'));
        return json_encode(['status' => 'success']);
    }

    /**
    * To pay the dispute amount on accepting for security deposit
    * @param $request Illuminate\Http\Request
    * @return payment form view
    */
    public function pay_amount(Request $request)
    {
        $dispute = Disputes::find($request->id);
        if(!$dispute)
        {
            return json_encode(['status' => 'danger']);
        }
        if(!$dispute->can_dispute_accept_form_show() || !$dispute->is_pay())
        {   
            return json_encode(['status' => 'danger']);
        }

        if($request->payment_type =='cc')
        {

            $rules =    [
                'cc_number'        => 'required|numeric|digits_between:12,20|validateluhn',
                'cc_expire_month'  => 'required|expires:cc_expire_month,cc_expire_year',
                'cc_expire_year'   => 'required|expires:cc_expire_month,cc_expire_year',
                'cc_security_code' => 'required|integer|digits_between:1,5',
                'first_name'       => 'required',
                'last_name'        => 'required',
                'zip'              => 'required',
            ];

            $niceNames =    [
                'cc_number'        => 'Card number',
                'cc_expire_month'  => 'Expires',
                'cc_expire_year'   => 'Expires',
                'cc_security_code' => 'Security code',
                'first_name'       => 'First name',
                'last_name'        => 'Last name',
                'zip'              => 'Postal code',
            ];

            $messages =     [
                'expires'      => 'Card has expired',
                'validateluhn' => 'Card number is invalid'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($niceNames);

            if ($validator->fails()) 
            {
                $errors = $validator->messages();
                return json_encode(['status' => 'error', 'errors' => $errors]);
            }
        }

        $final_dispute_data = $dispute->final_dispute_data;
        $payment_data['paypal_price'] = $this->payment_helper->currency_convert($dispute->currency_code, PAYPAL_CURRENCY_CODE, $final_dispute_data->get('amount'));

        $payment_country = $request->country;
        $paypal_credentials = PaymentGateway::where('site', 'PayPal')->get();

        $payment_data['payment_type'] = $request->payment_type;
        $payment_data['country'] = $payment_country;
        $payment_data['message'] = $request->message;

        if($payment_data['paypal_price'] <= 0)
        {
            $payment_data['transaction_id'] = '';
            $this->complete_payment($dispute->id, $payment_data);

            $this->helper->flash_message('success', trans('messages.payments.payment_success')); // Call flash message function
            return json_encode(['status' => 'success']);
        }
        elseif($payment_data['payment_type'] == 'cc' || $payment_data['payment_type'] == 'paypal')
        {
            $purchaseData   =   [
                'testMode'  => ($paypal_credentials[3]->value == 'sandbox') ? true : false,
                'amount'    => $payment_data['paypal_price'],
                'description' => '',
                'currency'  => PAYPAL_CURRENCY_CODE,
                'returnUrl' => url('dispute_pay_amount_success/'.$dispute->id),
                'cancelUrl' => url('dispute_pay_amount_cancel/'.$dispute->id),
            ];
            if($payment_data['payment_type'] == 'cc')
            {
                $card   =   [
                    'number'          => $request->cc_number, 
                    'expiryMonth'     => $request->cc_expire_month, 
                    'expiryYear'      => $request->cc_expire_year, 
                    'cvv'             => $request->cc_security_code, 
                    'firstName'       => $request->first_name,
                    'lastName'        => $request->last_name,
                    'billingAddress1' => $payment_country,
                    'billingCountry'  => $payment_country,
                    'billingCity'     => $payment_country,
                    'billingState'    => $payment_country,
                    'billingPostcode' => $request->zip,
                ];
                $payment_data['first_name'] = $request->first_name;
                $payment_data['last_name'] = $request->last_name;
                $payment_data['postal_code'] = $request->zip;
                
                $stripe_credentials = PaymentGateway::where('site', 'Stripe')->pluck('value', 'name');
                \Stripe\Stripe::setApiKey(@$stripe_credentials["secret"]);
                try
                {
                    $token_response = \Stripe\Token::create(array(
                      "card" => array( 
                        "number" => $request->cc_number,
                        "exp_month" => $request->cc_expire_month,
                        "exp_year" => $request->cc_expire_year,
                        "cvc" => $request->cc_security_code,
                      )
                    ));
                    $token = $token_response->id;
                }
                catch(\Exception $e)
                {
                    $this->helper->flash_message('danger', $e->getMessage());
                    return back();
                }

                $purchaseData['token'] = $token;

                $this->setup('Stripe');

            }
            else
            {
                $this->setup();   
            }
            

            $response = $this->omnipay->purchase($purchaseData)->send();
            if ($response->isSuccessful())
            {
                $result = $response->getData();
                $payment_data['transaction_id'] = $payment_data['payment_type'] == 'cc' ? @$result['id'] : @$result['TRANSACTIONID'];
                
                $this->complete_payment($dispute->id, $payment_data);

                $this->helper->flash_message('success', trans('messages.payments.payment_success')); // Call flash message function
                return json_encode(['status' => 'success']);

            }
            elseif ($response->isRedirect()) 
            {
                Session::put('dispute_payment.'.$dispute->id, $payment_data);
                // Redirect to offsite payment gateway
                // $response->redirect();
                return json_encode(['status' => 'redirect' , 'redirect_to' => $response->getRedirecturl('/')]);
            }
            else 
            {  
                $this->helper->flash_message('error', $response->getMessage()); // Call flash message function
                return json_encode(['status' => 'danger']);
            }
        }
        else
        {
            return json_encode(['status' => 'danger']);
        }
    }

    /**
     * Callback function for Payment Success
     *
     * @param Illuminate\Http\Request $request
     * @return Redirect to Payment Success Page
     */
    public function pay_amount_success(Request $request)
    {
        $dispute = Disputes::where('id', $request->id)->first();
        $payment_data = Session::get('dispute_payment.'.@$dispute->id);

        if($dispute && @$dispute->payment_status != 'Completed' && $payment_data)
        {
            $this->setup();

            $final_dispute_data = $dispute->final_dispute_data;
            $paypal_price = $this->payment_helper->currency_convert($dispute->currency_code, PAYPAL_CURRENCY_CODE, $final_dispute_data->get('amount'));

            $transaction = $this->omnipay->completePurchase(array(
                'payer_id'              => $request->PayerID,
                'transactionReference'  => $request->token,
                'amount'                => $paypal_price,
                'currency'              => PAYPAL_CURRENCY_CODE
            ));

            $response = $transaction->send();
            $result = $response->getData();

            if(@$result['ACK'] == 'Success')
            {
                $payment_data['transaction_id'] =  @$result['PAYMENTINFO_0_TRANSACTIONID'];
                $payment_data['payment_type'] = 'paypal';
                $this->complete_payment($dispute->id, $payment_data);

                $this->helper->flash_message('success', trans('messages.payments.payment_success')); // Call flash message function
                return redirect('dispute_details/'.$request->id);
            }
            else
            {
                $this->helper->flash_message('error', $result['L_SHORTMESSAGE0']); // Call flash message function
                return redirect('dispute_details/'.$request->id);
            }
        }
        else
        {
            return redirect('disputes');
        }
    }

    /**
     * Callback function for Payment Failed
     *
     * @param Illuminate\Http\Request $request
     * @return Redirect to Payment page
     */
    public function pay_amount_cancel(Request $request)
    {   
        $this->helper->flash_message('error', trans('messages.payments.payment_cancelled')); // Call flash message function
        return redirect('dispute_details/'.$request->id);
    }

    /**
     * To update the payment status for dispute
     *
     * @return void
     * @author 
     **/
    function complete_payment($dispute_id, $payment_data = array())
    {
        $dispute                        = Disputes::where('id', $dispute_id)->first();
        $user_id                        = @Auth::user()->id;

        $dispute_message                = new DisputeMessages;
        $dispute_message->dispute_id    = $dispute->id;
        $dispute_message->message_by    = $dispute->reservation->host_or_guest;
        $dispute_message->message_for   = $dispute->reservation->host_or_guest == 'Host' ? 'Guest' : 'Host';
        $dispute_message->user_from     = $user_id;
        $dispute_message->user_to       = $dispute->reservation->host_or_guest == 'Host' ? $dispute->reservation->user_id : $dispute->reservation->host_id;
        $dispute_message->currency_code = $dispute->reservation->currency_code;
        $dispute_message->message       = @$payment_data['message'] ?: '';
        $dispute_message->save();

        $dispute->payment_status        = 'Completed';
        $dispute->paymode               = @$payment_data['payment_type'] == 'cc' ? 'Credit Card' : 'PayPal';
        $dispute->transaction_id        = @$payment_data['transaction_id'];
        $dispute->first_name            = @$payment_data['first_name'];
        $dispute->last_name             = @$payment_data['last_name'];
        $dispute->postal_code           = @$payment_data['postal_code'];
        $dispute->country               = @$payment_data['country'];
        $dispute->currency_code         = $dispute->reservation->currency_code;
        $dispute->final_dispute_amount  = $dispute->final_dispute_data->get('amount');
        $dispute->status                = 'Closed';
        $dispute->save();

        $email_controller = new EmailController;
        $email_controller->dispute_closed($dispute->id);
    }

    /**
    * To create a dispute on reservation
    * @param $request Illuminate\Http\Request
    * @return result Json
    */
    public function create_dispute(Request $request)
    {
        $reservation = Reservation::with(['currency'])->find($request->id);
        if(!@$reservation->can_apply_for_dispute)
        {
            return json_encode(['status' => 'danger']);
        }

        $maximum_dispute_amount = $reservation->maximum_dispute_amount;

        $rules = [
            'id' => 'required|exists:reservation,id',
            'subject' => 'required',
            'description' => 'required',
            'amount' => 'required|integer|maximum_dispute_amount|min:1',
            'documents' => 'required',
        ];
        $messages = [
            'amount.integer' => trans('validation.numeric'),
            'amount.maximum_dispute_amount' => trans('messages.disputes.maximum_dispute_amount', ['payout_amount' => html_entity_decode($reservation->currency->symbol).html_entity_decode($reservation->currency_code).' '.$maximum_dispute_amount]),
            'extensionval' => trans('validation.mimes',['values'=>'jpeg,png']),
        ];
        $attributes= [
            'subject' => trans('messages.disputes.dispute_reason'),
            'description' => trans('messages.lys.description'),
            'amount' => trans('messages.account.amount'),
            'documents' => trans('messages.disputes.documents'),
        ];

        if($request->documents)
        {            
            foreach(@$request->documents as $k => $v)
            {
                $rules['documents.'.$k] = 'mimes:jpeg,png|extensionval';
                $attributes['documents.'.$k] = 'documents';
            }
        }

        Validator::extend('maximum_dispute_amount', function($attribute, $value, $parameters, $validator) use($maximum_dispute_amount) {
            return ($value <= $maximum_dispute_amount);
        });

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        if($validator->fails())
        {
            $errors = $validator->messages();
            if($request->documents)
            {            
                foreach(@$request->documents as $k => $v)
                {
                    if($errors->first('documents.'.$k))
                    {
                        $errors->add('documents', $errors->first('documents.'.$k));
                    }
                }
            }

            return json_encode(['status' => 'error', 'errors' => $errors]);
        }

        $documents = [];
        $file_path = 'images/disputes/';
        //$file_base_path = base_path($file_path);
        $file_base_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/disputes/';

        if(!file_exists($file_base_path))
        {
            mkdir($file_base_path, 0777, true);
        }

        foreach($request->documents as $document)
        {
            $errors = $validator->messages();

            $file_name = 'dispute_document_'.time().str_random(4).'.'.$document->getClientOriginalExtension();
            
            if($document)
            {
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $c=$this->helper->cloud_upload($document);
                    if($c['status']!="error")
                    {
                        $file_name=$c['message']['public_id'];
                    }
                    else
                    {
                        $errors->add('documents', $c['message']);
                        return json_encode(['status' => 'error', 'errors' => $errors]);
                    }
                }
                else
                {
                    try
                    {
                        $document->move($file_path, $file_name);
                    }
                    catch(\Exception $e)
                    {
                        $errors->add('documents', $e->getMessage());
                        return json_encode(['status' => 'error', 'errors' => $errors]);   
                    }
                }
            }
            $documents[] = $file_name;
        }

        $user_id = Auth::user()->id;

        $dispute = new Disputes;
        $dispute->reservation_id    = $reservation->id;
        $dispute->dispute_by        = $reservation->host_or_guest;
        $dispute->user_id           = $user_id;
        $dispute->dispute_user_id   = $reservation->host_or_guest == 'Host' ? $reservation->user_id : $reservation->host_id;
        $dispute->subject           = $request->subject;
        $dispute->amount            = $request->amount;
        $dispute->currency_code     = $reservation->currency_code;
        $dispute->status            = 'Open';
        $dispute->save();

        $dispute_message        = new DisputeMessages;
        $dispute_message->dispute_id    = $dispute->id;
        $dispute_message->message_by    = $reservation->host_or_guest;
        $dispute_message->message_for   = $reservation->host_or_guest == 'Host' ? 'Guest' : 'Host';
        $dispute_message->user_from     = $user_id;
        $dispute_message->user_to       = $reservation->host_or_guest == 'Host' ? $reservation->user_id : $reservation->host_id;
        $dispute_message->message       = $request->description;
        $dispute_message->currency_code = $reservation->currency_code;
        $dispute_message->amount        = $request->amount;
        $dispute_message->save();

        /*$file_base_path = base_path($file_path.'/'.$dispute->id);
        if(!file_exists($file_base_path))
        {
            mkdir($file_base_path, 0777, true);
        }

        foreach($documents as $file_name)
        {
            if(UPLOAD_DRIVER !='cloudinary')
            {
                \File::move($file_path.'/'.$file_name, $file_path.'/'.$dispute->id.'/'.$file_name);
            }

            $dispute_document = new DisputeDocuments;
            $dispute_document->dispute_id = $dispute->id;
            $dispute_document->file     = $file_name;
            $dispute_document->save();
        }*/

        // create folder with dispute id to store uploaded documents
        //$file_base_path = base_path($file_path.'/'.$dispute->id);
        $file_to = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/disputes/' . $dispute->id.'/';
        if(!file_exists($file_to))
        {
            mkdir($file_to, 0777, true);
        }
        
        $file_from = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/disputes';
        
        foreach($documents as $file_name)
        {            
            if(UPLOAD_DRIVER !='cloudinary')
            {
                // move documents from images/disputes to images/disputes/<dispute_id>
                \File::move($file_from.'/'.$file_name, $file_to.$file_name);
            }

            $dispute_document = new DisputeDocuments;
            $dispute_document->dispute_id = $dispute->id;
            $dispute_document->file     = $file_name;
            $dispute_document->uploaded_by     = $user_id;
            $dispute_document->save();
        }

        $email_controller = new EmailController;
        $email_controller->dispute_requested($dispute->id);

        $this->helper->flash_message('success', trans('messages.disputes.dispute_created_successfully'));
        return json_encode(['status' => 'success' ]);
    }

    public function dispute_delete_document(Request $request)
    {
        $doc = DisputeDocuments::where('id',$request->document_id)->first();
        if ($doc != NULL) {
            $doc->delete();
            return json_encode(['success' => 'true']);
        }
        else{
            return json_encode(['success' => 'false']);
        }

    }
}
