<?php 

/**
 * Host Experience Payment Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Host Experience Payment
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Omnipay\Omnipay;
use App\Models\HostExperiences;
use App\Models\HostExperienceCalendar;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Fees;
use App\Models\PaymentGateway;
use App\Models\Reservation;
use App\Models\HostExprienceCalendar;
use App\Models\ReservationGuestDetails;
use App\Models\Messages;
use App\Models\Payouts;
use App\Models\CouponCode;
use App\Models\Referrals;
use App\Models\AppliedTravelCredit;
use App\Models\HostPenalty;
use App\Models\User;
use App\Http\Helper\PaymentHelper;
use App\Http\Controllers\EmailController;
use App\Http\Start\Helpers;
use Validator;
use DateTime;
use Session;
use Auth;
use DB;
use JWTAuth;

class HostExperiencePaymentController extends Controller 
{
    protected $omnipay; // Global variable for Omnipay instance

    protected $payment_helper; // Global variable for Helpers instance

    protected $host_experience_id; // Global variable for Payment Data in session
    protected $scheduled_id; // Global variable for Payment Data in session
    protected $host_experience; // Global variable for HostExperiences instance
    protected $payment_data; // Global variable for Payment Data in session
    protected $base_url; // Global variable for the paymentbase url
    protected $base_view_path; // Global variable for the payment bsae path
    protected $view_data; // Global variable for the data for the views
    
    /**
     * Constructor to Set Global variables
     *
     *@param Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->host_experience_id = $request->host_experience_id;
        $this->host_experience    = HostExperiences::where('id', $this->host_experience_id)->first();

        if(!$this->host_experience)
        {
            $this->returnRedirect('/', $request->ajax());
        }

        if($this->host_experience->user_id  == @Auth::user()->id)
        {
            $this->returnRedirect('host/experiences', $request->ajax());
        }

        $this->scheduled_id = $request->scheduled_id;
        $this->payment_data = Session::get('experience_payment.'.$this->scheduled_id);

        if(!$this->payment_data)
        {
            $this->returnRedirect('experiences/'.$this->host_experience_id, $request->ajax());
        }
        $this->payment_helper = new PaymentHelper;
        $this->helper = new Helpers;
        $this->base_view_path = 'host_experiences/payment/';
        $this->base_url = url('experiences/'.$this->host_experience->id.'/book');
    }

    /**
     * To send redirect response based on the request type
     *
     * @param String $location Redirect location
     * @param Bool $isAjax Is the request type is Ajax or not
     * @return Response to redirect
     */
    public function returnRedirect($location, $isAjax=false)
    {
        if($isAjax)
        {
            $status = 503;
            $location  = url($location);
            $response = response(compact("status", "location"));
        }
        else
        {
            $response = redirect($location);
        }
        $response->send(); exit;
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
     * Load Payment view file
     *
     * @param Illuminate\Http\Request $request
     * @return Html Host Experience Payment view
     */
    public function index(Request $request)
    { 
        $this->payment_calculation($request);
        $this->view_data['host_experience'] = $this->host_experience;
        $this->view_data['payment_data'] = $this->payment_data;
        $this->view_data['base_view_path'] = $this->base_view_path;
        $this->view_data['base_url'] = $this->base_url;
        $this->view_data['scheduled_id'] = $this->scheduled_id;
        $this->view_data['host_experience_id'] = $this->host_experience_id;
        $this->view_data['card_type'] = 'cc';

        $payment_tabs = $this->view_data['payment_tabs'] = [
            [
                'tab' =>'guest-requirements',
                'name' => trans('experiences.payment.review_guest_requirement')
            ],
            [
                'tab' =>'cotravellers',
                'name' => trans('experiences.payment.who_is_coming')
            ],
            [
                'tab' =>'payment',
                'name' => trans('experiences.payment.confirm_and_pay')
            ],
        ];

        $current_tab = $this->view_data['current_tab'] = $request->tab;
        $this->view_data['current_tab_index'] = '';
        foreach($payment_tabs as $k => $tab)
        {
            if($tab['tab'] == $current_tab)
            {
                $this->view_data['current_tab_index'] = $k;
            }
        }
        if($this->view_data['current_tab_index'] === '')
        {
            return $this->returnRedirect('/', $request->ajax());
        }

        $this->view_data['user'] = User::find(Auth::user()->id);
        $this->view_data['countries'] = Country::all()->pluck('long_name','short_name');

        $this->view_data['title'] = $this->host_experience->title.' - '.SITE_NAME;
        if($request->is_mobile)
        	$this->view_data['is_mobile'] = $request->is_mobile;
        return view($this->base_view_path.'main', $this->view_data);
    }

    /**
     * Update the payment data Ajax
     *
     * @param Illuminate\Http\Request $request
     * @return Array [status, payment_data]
     */
    public function update_payment_data(Request $request)
    {
        $this->payment_data = $request->payment_data;
        $this->payment_calculation($request);
        $status = 200;
        $payment_data = $this->payment_data;

        return compact("status", "payment_data");
    }

    /**
     * Payment confirm and pay 
     *
     * @param Illuminate\Http\Request $request
     * @return Redirect to Trips/Payment page
     */
    public function complete_payment(Request $request)
    {
        $session_token = session('get_token');
        $paymode = $request->paymode;
        if($paymode == 'cc')
        {
            $rules =    [
                'cc_number'        => 'required|numeric|digits_between:12,20|validateluhn',
                'cc_expire_month'  => 'required|expires:cc_expire_month,cc_expire_year',
                'cc_expire_year'   => 'required|expires:cc_expire_month,cc_expire_year',
                'cc_security_code' => 'required|integer|digits_between:1,5',
                'first_name'       => 'required',
                'last_name'        => 'required',
                'postal_code'      => 'required',
            ];

            $niceNames =    [
                'cc_number'        => 'Card number',
                'cc_expire_month'  => 'Expires',
                'cc_expire_year'   => 'Expires',
                'cc_security_code' => 'Security code',
                'first_name'       => 'First name',
                'last_name'        => 'Last name',
                'postal_code'      => 'Postal code',
            ];

            $messages =     [
                'expires'      => 'Card has expired',
                'validateluhn' => 'Card number is invalid'
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            $validator->setAttributeNames($niceNames);
            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
        }

        $payment_data = $this->payment_calculation($request);
        $payment_country = $request->payment_country;
        $paypal_credentials = PaymentGateway::where('site', 'PayPal')->get();

        $this->payment_data['paymode'] = $paymode;
        $this->payment_data['country'] = $payment_country;

        if($payment_data['paypal_price'] <= 0)
        {
            $this->payment_data['transaction_id'] = '';
            $this->create_reservation();
            // API host experience change 
            $status_message = trans('messages.payments.payment_success');
            if($session_token){
                $result=array('success_message'=>$status_message,'status_code'=>'1');
                return view('json_response.json_response',array('result' =>json_encode($result)));
            }
            $this->helper->flash_message('success', $status_message); // Call flash message function
            return redirect('trips/current');
        }
        elseif($paymode == 'cc' || $paymode == 'paypal')
        {
            $payment_description    =   $this->host_experience->title.' '.$payment_data['date'].' '.$payment_data['number_of_guests'].' '.trans_choice('experiences.payment.guest_s', $payment_data['number_of_guests']);
            $purchaseData   =   [
                'testMode'  => ($paypal_credentials[3]->value == 'sandbox') ? true : false,
                'amount'    => $this->payment_data['paypal_price'],
                'description' => $payment_description,
                'currency'  => PAYPAL_CURRENCY_CODE,
                'returnUrl' => url($this->base_url.'/payment_success?scheduled_id='.$this->scheduled_id),
                'cancelUrl' => url($this->base_url.'/payment_cancel?scheduled_id='.$this->scheduled_id),
            ];
            if($paymode == 'cc')
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
                    'billingPostcode' => $request->postal_code,
                ];
                $this->payment_data['first_name'] = $request->first_name;
                $this->payment_data['last_name'] = $request->last_name;
                $this->payment_data['postal_code'] = $request->postal_code;
                
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
            $this->update_session_payment_data();
            try{
                $response = $this->omnipay->purchase($purchaseData)->send();
            }
            catch(\Exception $e)
            {
                $this->helper->flash_message('error', $e->getMessage());
                if($session_token){
                    return redirect($this->base_url.'/payment?scheduled_id='.$this->scheduled_id.'&is_mobile=Yes&tab=payment');
                }
                return redirect($this->base_url.'/payment?scheduled_id='.$this->scheduled_id);
            }
            
            if ($response->isSuccessful())
            {
                $result = $response->getData();
                $this->payment_data['transaction_id'] = $paymode == 'cc' ? @$result['id'] : @$result['TRANSACTIONID'];
                $this->create_reservation();
                $status_message = trans('messages.payments.payment_success');
                if($session_token){
                    $result=array('success_message'=>$status_message,'status_code'=>'1');
                    return view('json_response.json_response',array('result' =>json_encode($result)));
                }
                $this->helper->flash_message('success', $status_message); // Call flash message function
                return redirect('trips/current');

            }
            elseif ($response->isRedirect()) 
            {
                // Redirect to offsite payment gateway
                $response->redirect();
            }
            else 
            {  
                $this->helper->flash_message('error',$response->getMessage()); // Call flash message function
                if($session_token){
                    return redirect($this->base_url.'/payment?scheduled_id='.$this->scheduled_id.'&is_mobile=Yes&tab=payment');
                }
                return redirect($this->base_url.'/payment?scheduled_id='.$this->scheduled_id);            
            }
        }
        else
        {
            return back();
        }
    }

    /**
     * Callback function for Payment Success
     *
     * @param Illuminate\Http\Request $request
     * @return Redirect to Payment Success Page
     */
    public function payment_success(Request $request)
    {
        $session_token = session('get_token');
        if($this->payment_data['paymode'] == 'paypal')
        {
            $this->setup();
            $transaction = $this->omnipay->completePurchase(array(
                'payer_id'              => $request->PayerID,
                'transactionReference'  => $request->token,
                'amount'                => $this->payment_data['paypal_price'],
                'currency'              => PAYPAL_CURRENCY_CODE
            ));

            $response = $transaction->send();
            $result = $response->getData();

            if(@$result['ACK'] == 'Success')
            {
                $this->payment_data['transaction_id'] =  @$result['PAYMENTINFO_0_TRANSACTIONID'];
                $this->create_reservation();
                $status_message = trans('messages.payments.payment_success');
                if($session_token){
                    $result=array('success_message'=>$status_message,'status_code'=>'1');
                    return view('json_response.json_response',array('result' =>json_encode($result)));
                }
                $this->helper->flash_message('success', $status_message); // Call flash message function
                return redirect('trips/current');
            }
            else
            {
                $this->helper->flash_message('error', $result['L_SHORTMESSAGE0']); // Call flash message function
                if($session_token){
                    return redirect($this->base_url.'/payment?scheduled_id='.$this->scheduled_id.'&is_mobile=Yes&tab=payment');
                }
                return redirect($this->base_url.'/payment?scheduled_id='.$this->scheduled_id);
            }
        }
        else
        {
            return redirect('trips/current');
        }
    }

    /**
     * Callback function for Payment Failed
     *
     * @param Illuminate\Http\Request $request
     * @return Redirect to Payment page
     */
    public function payment_cancel(Request $request)
    {   
        $this->helper->flash_message('error', trans('messages.payments.payment_cancelled')); // Call flash message function
        if(session('get_token')){
            return redirect('experiences/book/'.$request->host_experience_id.'?scheduled_id='.$this->scheduled_id.'&is_mobile=Yes&tab=payment&host_experience_id='.$request->host_experience_id.'&token='.session('get_token'));
        }
        return redirect($this->base_url.'/payment?scheduled_id='.$this->scheduled_id);
    }

    /**
     * To store the payment details in reservation
     * 
     * @return App\Models\Reservation $reservation Created reservation 
     */
    public function create_reservation()
    {
        if(session('get_token'))
            $mobile_web_auth_user_id =  JWTAuth::toUser(Session::get('get_token'))->id;
        else
            $mobile_web_auth_user_id = Auth::user()->id;
        $payment_data = $this->payment_data;

        $reservation = new Reservation;
        $reservation->room_id           = $this->host_experience->id;
        $reservation->list_type         = 'Experiences';
        $reservation->host_id           = $this->host_experience->user_id;
        $reservation->user_id           = $mobile_web_auth_user_id;
        $reservation->checkin           = $payment_data['date'];
        $reservation->checkout          = $payment_data['date'];
        $reservation->start_time        = $payment_data['start_time'];
        $reservation->end_time          = $payment_data['end_time'];
        $reservation->number_of_guests  = $payment_data['number_of_guests'];
        $reservation->nights            = 1;
        $reservation->per_night         = $payment_data['price'];
        $reservation->subtotal          = $payment_data['subtotal'];
        $reservation->service           = $payment_data['service_fee'];
        $reservation->total             = $payment_data['total'];
        $reservation->currency_code     = $payment_data['currency_code'];
        $reservation->paypal_currency   = PAYPAL_CURRENCY_CODE;

        if($payment_data['paymode'] == 'cc')
        {
            $reservation->first_name   = @$payment_data['first_name'] ?: '';
            $reservation->last_name    = @$payment_data['last_name'] ?: '';
            $reservation->postal_code  = @$payment_data['postal_code'] ?: '';
        }
        if($payment_data['coupon_price'] > 0)
        {
          $reservation->coupon_code       = $payment_data['coupon_code'];
          $reservation->coupon_amount     = $payment_data['coupon_price'];
        }

        $reservation->base_per_night                = $payment_data['price'];

        $reservation->country          = $payment_data['country'];
        $reservation->cancellation     = 'Flexible';
        $reservation->transaction_id   = $payment_data['transaction_id'];
        $reservation->paymode          = $payment_data['paymode'] == 'cc' ? 'Credit Card' : 'Paypal';
        $reservation->type             = 'reservation';
        $reservation->status           = 'Accepted';
        $reservation->save();

        do
        {
            $code = $this->getCode(6, $reservation->id);
            $check_code = Reservation::where('code', $code)->get();
        }
        while(empty($check_code));
        
        $reservation->code = $code;
        $reservation->save();

        $spots = $this->calendar_update();

        $guest_details = new ReservationGuestDetails;
        $guest_details->reservation_id = $reservation->id;
        $guest_details->first_name = @$reservation->host_users->first_name;
        $guest_details->last_name = @$reservation->host_users->last_name;
        $guest_details->email = @$reservation->host_users->email;
        $guest_details->is_main = 'Yes';
        $guest_details->spot = @$spots[0];
        $guest_details->save();

        foreach ($payment_data['guest_details'] as $key => $guest_data) {
            $guest_details = new ReservationGuestDetails;
            $guest_details->reservation_id = $reservation->id;
            $guest_details->first_name = @$guest_data['first_name'];
            $guest_details->last_name = @$guest_data['last_name'];
            $guest_details->email = @$guest_data['email'];
            $guest_details->spot = @$spots[$key+1];
            $guest_details->save();
        }

        $this->payout_refund_processing($reservation, 0, $reservation->host_payout);

        $message = new Messages;
        $message->room_id        = $this->host_experience->id;
        $message->list_type      = 'Experiences';
        $message->reservation_id = $reservation->id;
        $message->user_to        = $reservation->host_id;
        $message->user_from      = $reservation->user_id;
        $message->message        = '';
        $message->message_type   = 2;
        $message->read           = 0;
        $message->save();

        $this->guest_refund_process($spots);


        $email_controller = new EmailController;
        $email_controller->experience_accepted($reservation->id);
        $email_controller->experience_booking_confirm_host($reservation->id);
        $email_controller->experience_booking_confirm_admin($reservation->id);

        Session::forget('experience_payment.'.$this->scheduled_id);
        Session::save();

        return $reservation;
    }

    /**
     * To update the calendar data
     *
     * @return Array $spots Booked spots for the current reservation
     */
    public function calendar_update()
    {
        $date = $this->payment_data['date'];
        $check_data  = [
            'host_experience_id' => $this->host_experience->id,
            'date'              => $this->payment_data['date'],
        ];
        $calendar               = HostExperienceCalendar::firstOrNew($check_data);
        $calendar->price        = $calendar->price > 0 ? $calendar->price : $this->host_experience->price_per_guest;
        $calendar->spots_booked = ($calendar->spots_booked - 0)+($this->payment_data['number_of_guests'] - 0);
        $calendar->source       = 'Reservation';
        $calendar->status       = 'Not available';
        $calendar->save();

        $spots                  = [];
        $calendar_spots_array   = $calendar->spots_array;
        $spots_booked           = $calendar->spots_booked;
        for($i = 1; $i <= $spots_booked; $i++)
        {
            if(!in_array($i, $calendar_spots_array))
            {
                $calendar_spots_array[] = $i;   
                $spots[] = $i;   
            }
        }
        $calendar_spots_array = array_filter($calendar_spots_array);
        asort($calendar_spots_array);
        $spots = array_filter($spots);
        asort($spots);

        $calendar->spots        = implode(',', $calendar_spots_array);
        $calendar->save();

        return $spots;
    }

    /**
     * To process the pending refunds fot the guests
     *
     * @param Array $spots Spots Booked for the current reservation
     */
    public function guest_refund_process($spots)
    {
        $payment_data = $this->payment_data;
        $host_experience = $this->host_experience;
        $cancelled_reservations = Reservation::where('checkin', @$payment_data['date'])
                                    ->where('room_id', $host_experience->id)
                                    ->where('status', 'Cancelled')
                                    ->where('cancelled_by', 'Guest')->get();
        $cancelled_reservations_ids = $cancelled_reservations->pluck('id');
        $pending_guest_refunds  = ReservationGuestDetails::whereIn('reservation_id', $cancelled_reservations_ids)
                                    ->where('refund_status', 'Pending')
                                    ->whereIn('spot', $spots)->get()->groupBy('reservation_id');

        foreach ($pending_guest_refunds as $key => $guest_details) {
            $reservation = Reservation::find($key);

            $spots_refund_count = count($guest_details);
            $service_fee_percent = @Fees::where('name','experience_service_fee')->first()->value;

            if($spots_refund_count != $reservation->number_of_guests)
            {
                $spots_already_refunded = ReservationGuestDetails::where('reservation_id', $key)->where('refund_status', 'Approved')->count();
                $total_spots = $spots_refund_count + $spots_already_refunded;
                $per_guest_amount= ($reservation->total - $reservation->coupon_amount)/$reservation->number_of_guests;
                $guest_refundable_subtotal = ($total_spots * $reservation->per_night);

                $guest_refundable_amount = round($total_spots * $per_guest_amount);
                $host_payout_amount = round($reservation->subtotal - $guest_refundable_subtotal);
            }
            else
            {
                $guest_refundable_amount = ($reservation->total - $reservation->coupon_amount);
                $host_payout_amount = 0;
            }
            
            $this_spots = $guest_details->pluck('spot')->toArray();
            $this->payout_refund_processing($reservation, $guest_refundable_amount, $host_payout_amount, $this_spots);
        }
    }

    /**
     * To process the payouts and refunds based on reservations
     *
     * @param App\Models\Reservation $reservation
     * @param Int $guest_refundable_amount 
     * @param Int $host_payout_amount 
     * @param Array $spots 
     */
    public static function payout_refund_processing($reservation, $guest_refundable_amount = 0, $host_payout_amount = 0, $spots = array())
    {
        $payment_helper  = new PaymentHelper;
        $guest_check_data = array(
            'user_id' => $reservation->user_id,
            'reservation_id' => $reservation->id,
        );
        $guest_payout = Payouts::firstOrNew($guest_check_data);
        
        $host_check_data = array(
            'user_id' => $reservation->host_id,
            'reservation_id' => $reservation->id,
        );
        $host_payout = Payouts::firstOrNew($host_check_data);
        if(@$host_payout->penalty_id != 0 && @$host_payout->penalty_id != '')
        {
            $penalty_id = explode(",",$host_payout->penalty_id);
            $penalty_amt = explode(",",$host_payout->penalty_amount);
            $i =0;
            foreach ($penalty_id as $row) 
            {
                $old_amt = HostPenalty::where('id',$row)->get();
                $upated_amt = $old_amt[0]->remain_amount + $penalty_amt[$i];
                HostPenalty::where('id',$row)->update(['remain_amount' => $upated_amt,'status' => 'Pending' ]); 
                $i++;
            }
        }
        if($guest_refundable_amount > 0)
        {
            if(!@$guest_payout->id)
            {
                $guest_payout->reservation_id = $reservation->id;
                $guest_payout->room_id        = $reservation->room_id;
                $guest_payout->list_type      = 'Experiences';
                $guest_payout->user_id        = $reservation->user_id;
                $guest_payout->user_type      = 'guest';
                $guest_payout->currency_code  = $reservation->currency_code;
                $guest_payout->status         = 'Future';
                $guest_payout->spots          = '';
                $guest_payout->save();
            }
            $guest_payout->currency_code  = $reservation->currency_code;
            $guest_payout->amount         = $guest_refundable_amount;
            $guest_payout->currency_code  = $reservation->currency_code;
            $guest_payout->save();

            $prev_spots = $guest_payout->spots_array;
            $updated_spots = array_merge($prev_spots, $spots);
            $updated_spots = array_filter($updated_spots);
            asort($updated_spots);

            $guest_payout->spots = implode(',', $updated_spots);
            $guest_payout->save();

            ReservationGuestDetails::where('reservation_id', $reservation->id)->whereIn('spot', $updated_spots)->update(['refund_status' => 'Approved']);
        }
        if($host_payout_amount > 0)
        {
            $penalty = HostPenalty::where('user_id',$reservation->host_id)->where('remain_amount','!=',0)->get();
            $penalty_result = $payment_helper->check_host_penalty($penalty,$host_payout_amount,$reservation->currency_code);
            $host_amount    = $penalty_result['host_amount'];
            $penalty_id     = $penalty_result['penalty_id'];
            $penalty_amount = $penalty_result['penalty_amount'];

            if(!@$host_payout->id)
            {
                $host_payout->reservation_id = $reservation->id;
                $host_payout->room_id        = $reservation->room_id;
                $host_payout->list_type      = 'Experiences';
                $host_payout->user_id        = $reservation->host_id;
                $host_payout->user_type      = 'host';
                $host_payout->currency_code  = $reservation->currency_code;
                $host_payout->status         = 'Future';
                $host_payout->save();
            }
            $host_payout->currency_code  = $reservation->currency_code;
            $host_payout->amount         = $host_amount;
            $host_payout->penalty_amount = $penalty_amount;
            $host_payout->penalty_id     = $penalty_id;
            $host_payout->save();
        }
        else
        {
            if($host_payout)
                $host_payout->delete();
        }
    }

    /**
     * To update the payment data in session
     * 
     */
    public function update_session_payment_data()
    {
        Session::put('experience_payment.'.$this->scheduled_id, $this->payment_data);
        Session::save();
    }

    /**
     * To calculate the experience payment 
     *
     * @param Illuminate\Http\Request $request
     * @return Array payment_data
     */
    public function payment_calculation($request)
    {
        $payment_data = $this->payment_data;

        $availability_status = $this->host_experience->get_date_availability_details(@$payment_data['date'], true);
        if(!@$availability_status['is_available_booking'])
        {
            $this->returnRedirect('experiences/'.$this->host_experience_id, $request->ajax());
        }

        $payment_data['date']                 = $availability_status['date'];
        $payment_data['status']               = $availability_status['status'];
        $payment_data['price']                = $availability_status['price'];
        $payment_data['spots_left']           = $availability_status['spots_left'];
        $payment_data['is_reserved']          = $availability_status['is_reserved'];
        $payment_data['is_available_booking'] = $availability_status['is_available_booking'];
        $payment_data['currency_symbol']      = $availability_status['currency_symbol'];
        $payment_data['start_time']           = $availability_status['start_time'];
        $payment_data['end_time']             = $availability_status['end_time'];
        $min_service_fee                      = 0;

        $service_fee_percent = @Fees::where('name','experience_service_fee')->first()->value;

        $min_service_fee1 = @Fees::where('name','expr_min_service_fee')->first()->value;
        $fee_currency    = @Fees::where('name','expr_fees_currency')->first()->value;

        if(!@$payment_data['guest_details'])
        {
            $payment_data['guest_details'] = array();
        }

        $payment_data['number_of_guests'] = count($payment_data['guest_details']) +1;

        $payment_data['subtotal'] = $payment_data['number_of_guests'] * $payment_data['price'];
        $payment_data['service_fee'] = round(($payment_data['subtotal'] * ($service_fee_percent/100)));
        if($min_service_fee1)
        {
            $min_service_fee = $this->currency_convert($fee_currency, '', $min_service_fee1);
        }

        if($payment_data['service_fee']<$min_service_fee && $service_fee_percent)
        {
          $payment_data['service_fee'] = $min_service_fee;
        }

        $payment_data['total'] = $payment_data['subtotal'] +$payment_data['service_fee'];
        $payment_data['currency_code'] = $this->host_experience->currency->session_code;
        $payment_data['paypal_currency_code'] = PAYPAL_CURRENCY_CODE;
        $payment_data['paypal_currency_symbol'] = PAYPAL_CURRENCY_SYMBOL;
        $payment_data['paypal_exchange_rate'] = $this->currency_convert('', PAYPAL_CURRENCY_CODE, 1);

        $this->payment_data = $payment_data;
        $this->apply_coupon_code();
        $this->payment_data['paypal_price'] = $this->payment_helper->currency_convert('', PAYPAL_CURRENCY_CODE, $this->payment_data['total']);

        $this->update_session_payment_data();
        return $this->payment_data;
    }

    /**
     * To apply coupon code on payment calculation
     *
     * @return Array payment_data
     */
    public function apply_coupon_code()
    {
        $payment_data = $this->payment_data;

        if(session('get_token'))
            $mobile_web_auth_user_id =  JWTAuth::toUser(Session::get('get_token'))->id;
        else
            $mobile_web_auth_user_id = Auth::user()->id;

        if(@$payment_data['coupon_code'] === null)
        {
            $payment_data['is_coupon_code'] = false;
            $payment_data['coupon_code_applied'] = false;
            $payment_data['coupon_code']  = '';
            $payment_data['coupon_price']  = 0;
            $payment_data['coupon_code_error']  = 0;
        }
        $payment_data['coupon_code_error']  = '';
        if(@$payment_data['coupon_code'] != '')
        {
            $coupon_code_data = CouponCode::where('coupon_code', $payment_data['coupon_code'])->where('status','Active')->first();
            $interval         = "Check_Expired_coupon";
            if($coupon_code_data)
            {
                $reservation_result = Reservation::where('user_id', $mobile_web_auth_user_id)->where('coupon_code', $payment_data['coupon_code'])->get();
                $datetime1 = new DateTime(); 
                $datetime2 = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($coupon_code_data->expired_at)));

                if($reservation_result->count()) {
                    $interval = "Already_applied";
                }
                elseif($datetime1 < $datetime2)
                {
                    $interval_diff = $datetime1->diff($datetime2);
                    if($interval_diff->days)
                        $interval      = $interval_diff->days; 
                    else
                        $interval      = $interval_diff->h;            
                }
                else
                {
                    $interval = "Expired_coupon"; 
                } 
            }
            if($interval == "Expired_coupon")
            {
                $payment_data['coupon_price'] = 0;
                $payment_data['coupon_code_error'] = trans('messages.payments.expired_coupon');
            }
            elseif($interval == "Check_Expired_coupon")
            {
                $payment_data['coupon_price'] = 0;
                $payment_data['coupon_code_error'] = trans('messages.payments.invalid_coupon');
            }
            elseif($interval == "Already_applied") {
                $payment_data['coupon_price'] = 0;
                $payment_data['coupon_code_error'] = trans('messages.payments.coupon_already_used');
            }
            else
            {
                $payment_data['coupon_price']  = $this->payment_helper->currency_convert($coupon_code_data->currency_code,$payment_data['currency_code'],$coupon_code_data->amount);

                if($payment_data['coupon_price'] >= $payment_data['total']) {
                    $payment_data['coupon_price'] = $payment_data['total'];
                    $payment_data['total']  = 0;
                }  
                else {   
                    $payment_data['total']  = $payment_data['total'] - $payment_data['coupon_price'];
                }
                $payment_data['coupon_code_applied'] = true;
            }
        }   
        else
        {
            $payment_data['coupon_price'] = 0;
        }
        $this->payment_data  = $payment_data;
        return $payment_data;
    }

    /**
     * To convert the amount from one currency to another currency
     *
     * @param Int $from Current currency code
     * @param Int $to Required currency code
     * @param Int $price Amount to be converted
     * @return Int Converted amount
     */
    public function currency_convert($from = '', $to = '', $price)
    {
      if($from == '')
      {
        if(Session::get('currency'))
           $from = Session::get('currency');
        else
           $from = Currency::where('default_currency', 1)->first()->code;
      }

      if($to == '')
      {
        if(Session::get('currency'))
           $to = Session::get('currency');
        else
           $to = Currency::where('default_currency', 1)->first()->code;
      }

      $rate = Currency::whereCode($from)->first()->rate;

      $usd_amount = $price / $rate;
      
      $session_rate = Currency::whereCode($to)->first()->rate;

      return ceil($usd_amount * $session_rate);
    }

    /**
     * Generate Reservation Code
     *
     * @param date $length Code Length
     * @param date $seed Reservation Id
     * @return string $code Reservation Code
     */
    public function getCode($length, $seed)
    {  
        $code = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";

        mt_srand($seed);

        for($i=0;$i<$length;$i++) {
            $code .= $codeAlphabet[mt_rand(0,strlen($codeAlphabet)-1)];
        }

        return $code;
    }
   
}
