<?php 

/**
 * Payment Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Payment
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\Rooms;
use App\Models\RoomsPrice;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Ngofee;
use App\Models\PaymentGateway;
use App\Models\Reservation;
use App\Models\Calendar;
use App\Models\Messages;
use App\Models\Payouts;
use App\Models\CouponCode;
use App\Models\Referrals;
use App\Models\AppliedTravelCredit;
use App\Models\HostPenalty;
use App\Models\SpecialOffer;
use App\Models\Fees;
use Validator;
use App\Http\Helper\PaymentHelper;
use App\Http\Controllers\EmailController;
use App\Http\Start\Helpers;
use DateTime;
use Session;
use Auth;
use DB;
use JWTAuth;

class PaymentController extends Controller 
{
    protected $omnipay; // Global variable for Omnipay instance

    protected $payment_helper; // Global variable for Helpers instance
    
    /**
     * Constructor to Set PaymentHelper instance in Global variable
     *
     * @param array $payment   Instance of PaymentHelper
     */
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

        $this->omnipay->setUsername($paypal_credentials[0]->value);
        $this->omnipay->setPassword($paypal_credentials[1]->value);
        $this->omnipay->setSignature($paypal_credentials[2]->value);
        $this->omnipay->setTestMode(($paypal_credentials[3]->value == 'sandbox') ? true : false);
        if($gateway == 'PayPal_Express')
            $this->omnipay->setLandingPage('Login');
    }

    /**
     * Load Payment view file
     *
     * @param $request  Input values
     * @return payment page view
     */
    public function index(Request $request)
    {


        if(Session::get('get_token')!='')
        { 
            $user = @JWTAuth::toUser(Session::get('get_token'));
            $mobile_web_auth_user_id=$user->id; 
            $currency_details = @Currency::where('code', $user->currency_code)->first();
            Session::put('currency_symbol', $currency_details->original_symbol); //mobile  currency_symbol
            Session::put('currency',$currency_details->code);
        }
        else
        {            
           $mobile_web_auth_user_id=@Auth::user()->id; 
        }
        $s_key = $request->s_key ?: time().$request->id.str_random(4);
        $data   = array();
        $data['user_id']            = $mobile_web_auth_user_id;
        $data['s_key']              = $s_key;
        $data['special_offer_id']   = '';
        $data['special_offer_type'] = '';

        if($request->s_key)
        {
            $payment = Session::get('payment.'.$request->s_key);
        }
        else if($request->method() == 'POST')
        {
            $payment = array(
                'payment_room_id' => $request->id, 
                'payment_checkin' => $request->checkin,
                'payment_checkout' => $request->checkout,
                'payment_number_of_guests' => $request->number_of_guests,
                'payment_booking_type' => $request->booking_type,
                'payment_special_offer_id' => @$request->special_offer_id,
                'payment_reservation_id' => $request->reservation_id,                       
                'payment_cancellation' => $request->cancellation,
            );
            Session::put('payment.'.$s_key, $payment);
        }
        else if($request->method() == 'GET')
        {
            $payment = array(
                'payment_room_id' => $request->room_id, 
                'payment_checkin' => date('Y-m-d', strtotime(@$request->checkin)),
                'payment_checkout' => date('Y-m-d', strtotime(@$request->checkout)),
                'payment_number_of_guests' => $request->number_of_guests,
                'payment_special_offer_id' => $request->special_offer_id,
                'payment_booking_type' => 'instant_book',
                'payment_reservation_id' => $request->reservation_id,
                'payment_cancellation' => $request->cancellation,
            );
            Session::put('payment.'.$s_key,$payment);
        }

        if(!$payment)
        {
            return redirect('404');
        }

        if(@$payment['payment_special_offer_id'] != '')
        {
            $special_offer_id = $payment['payment_special_offer_id'];
            $special_offer_data   = SpecialOffer::where('id', $special_offer_id)->where('user_id', $mobile_web_auth_user_id)->first();
            if(!$special_offer_data)
            {
                $host_name = Rooms::find($payment['payment_room_id'])->host_name;

                $this->helper->flash_message('danger', trans('messages.inbox.type_removed_by_host',['type'=>trans('messages.inbox.special_offer'),'host_name'=>$host_name]),url('inbox'));    
                 if(\URL::previous() && !strrpos(\URL::previous(), 'pcss'))
                {
                    return back();
                }
                return redirect('inbox');
            }

             $already = Reservation::where('special_offer_id',$special_offer_id)->where('status','Accepted')->first();
         
            if($already){
                $this->helper->flash_message('danger', trans('messages.inbox.already_booked'));
                Session::forget('payment.'.$s_key);
                if(\URL::previous() && !strrpos(\URL::previous(), 'pcss'))
                {
                    return back();
                }
                return redirect('trips/current');
            }
            $data['special_offer_id']   = $special_offer_id;
            $data['special_offer_type'] = $special_offer_data->type;
        }
        else if(@$payment['payment_reservation_id'] != '')
        {
            $reservation_id = $payment['payment_reservation_id'];
            $reservation = Reservation::where('id', $reservation_id)->where('user_id',$mobile_web_auth_user_id)->first();
            if(!$reservation)
            {
                if(Session::get('get_token')=='')
                {
                   $this->helper->flash_message('error', trans('messages.rooms.dates_not_available')); // Call flash message function
                   return redirect('trips/current');
                }
                else
                {
                    return response()->json(['success_message'=>'Rooms Dates Not Available','status_code'=>'0']);
                }
            } 
            else 
            {            
                /* check reservation status is already booked or cancelled
                * if Accepted - redirect user to rooms detail page with dates not available flash
                * if Cancelled - redirect user to search page with your reservation has been cancelled flash
                */
                if($reservation->status == 'Accepted')
                {                                    
                    $this->helper->flash_message('danger', trans('messages.rooms.dates_not_available'));
                    return redirect('rooms/'.$reservation->room_id); 
                }
                else if($reservation->status == 'Cancelled' && $reservation->cancelled_by == 'Host')
                {
                    $this->helper->flash_message('error', trans('messages.email.sorry_book_some_other_dates'));
                    return redirect('s');
                    
                }
                else if($reservation->status == 'Cancelled' && $reservation->cancelled_by == 'Guest')
                {
                    $this->helper->flash_message('error', trans('messages.email.sorry_book_some_other_dates_guest'));
                    return redirect('s');
                }
                                
            }
            
            
            if($request->segment(1) != 'api_payments')
            {
                $payment = array(
                    'payment_room_id' => $reservation->room_id, 
                    'payment_checkin' => date('d-m-Y', strtotime($reservation->checkin)),
                    'payment_checkout' => date('d-m-Y', strtotime($reservation->checkout)),
                    'payment_number_of_guests' => $reservation->number_of_guests,
                    'payment_special_offer_id' => $reservation->special_offer_id,
                    'payment_booking_type' => 'instant_book',
                    'payment_reservation_id' => $reservation->id,
                    'payment_cancellation' => $reservation->cancellation,
                    'payment_card_type' => $reservation->paymode,
                );
                Session::put('payment.'.$s_key,$payment);
            }
        }

        if(!@$payment['payment_checkin'])
        {
            return redirect('rooms/'.$request->id); 
        }
        if(!@$payment['payment_room_id'])
        {
            return redirect('404');
        }

        $payment_room=Rooms::find(@$payment['payment_room_id']);
        if(!$payment_room){
        return redirect('404');
        }

        $data['result']           = Rooms::find(Session::get('payment')[$s_key]['payment_room_id']);
        $data['room_id']          = Session::get('payment')[$s_key]['payment_room_id'];
        $data['checkin']          = Session::get('payment')[$s_key]['payment_checkin'];
        $data['checkout']         = Session::get('payment')[$s_key]['payment_checkout'];
        $data['number_of_guests'] = Session::get('payment')[$s_key]['payment_number_of_guests'];
        $data['special_offer_id'] = Session::get('payment')[$s_key]['payment_special_offer_id'];
        $data['booking_type']     = Session::get('payment')[$s_key]['payment_booking_type'];
        $data['reservation_id']   = Session::get('payment')[$s_key]['payment_reservation_id'];
        $data['cancellation']     = Session::get('payment')[$s_key]['payment_cancellation'];
        $data['s_key']            = $s_key;
        $from                     = new DateTime($data['checkin']);
        $to                       = new DateTime($data['checkout']);
        $data['nights']           = $to->diff($from)->format("%a");

        $travel_credit_result = Referrals::whereUserId($mobile_web_auth_user_id)->get();
        $travel_credit_friend_result = Referrals::whereFriendId($mobile_web_auth_user_id)->get();

        $travel_credit = 0;
        
        foreach($travel_credit_result as $row) {
            $travel_credit += $row->credited_amount;
        }
        
        foreach($travel_credit_friend_result as $row) {
            $travel_credit += $row->friend_credited_amount;
        }

        if($travel_credit && Session::get('remove_coupon') != 'yes' && Session::get('manual_coupon') != 'yes' && ($data['reservation_id']!='' || $data['booking_type'] == 'instant_book')) {
            Session::put('coupon_code', 'Travel_Credit');
            Session::put('coupon_amount', $travel_credit);
        }

        $data['travel_credit']      = $travel_credit;
        $data['price_list']         = json_decode($this->payment_helper->price_calculation($data['room_id'], $data['checkin'], $data['checkout'], $data['number_of_guests'], $data['special_offer_id'], '', $data['reservation_id']));
        $pending_reservation_check  = Reservation::where(['room_id' => $data['room_id'],'id' => $data['reservation_id'], 'checkin' => date('Y-m-d', strtotime($data['checkin'])), 'checkout' => date('Y-m-d', strtotime($data['checkout'])), 'user_id' => $mobile_web_auth_user_id, 'status' => 'Pending'])->get(); 

        if(@$data['price_list']->status == 'Not available' || count($pending_reservation_check) > 0)
        {
            $this->helper->flash_message('error', trans('messages.rooms.dates_not_available')); // Call flash message function
            Session::forget('payment.'.$s_key);
            if(\URL::previous() && !strrpos(\URL::previous(), 'pcss') && \URL::full() != \URL::previous())
            {
                return back();
            }
            return redirect('rooms/'.$data['room_id']);
        }

        if($data['result']->user_id == $data['user_id']){
            return redirect('rooms/'.$data['room_id']);
        }
        

        Session::put('payment.'.$s_key.'.payment_price_list', $data['price_list']);

        $data['paypal_price']        = $this->payment_helper->currency_convert($data['result']->rooms_price->code, PAYPAL_CURRENCY_CODE, $data['price_list']->total);

        $from_rate = @Currency::whereCode($data['result']->rooms_price->currency_code)->first()->rate;
        $to_rate = @Currency::whereCode(PAYPAL_CURRENCY_CODE)->first()->rate;

        $data['paypal_price_rate']       = number_format(($from_rate/$to_rate), 2);
        
        // Get First Default Currency from currency table
        $data['currency']         = Currency::where('default_currency', 1)->take(1)->get();
        $data['country']          = Country::all()->pluck('long_name', 'short_name');
        $data['country_phone_codes'] = Country::get(); 
        //dd($data['country_phone_codes']);
        //NGO Fee
        $ngo_detail = Ngofee::where('status', 'active')->get();
        foreach ($ngo_detail as $key => $value) {
            $currency = Currency::find($value->currency)->code;
            $ngo_detail[$key]['amount'] = $this->payment_helper->currency_convert($currency,'',$value->amount);
        }
        //dd(session::all());
        if(Session::has('ngo_status')){
            foreach ($ngo_detail as $key => $value) {
                $ngo_list = Session::get('ngo_code');
                if(in_array($value->id,$ngo_list)){
                    $ngo_detail[$key]['checked'] = 'checked'; 
                }
            }
        }
        $data['ngo_detail'] = $ngo_detail;
        //End
        //dd($data);
        return view('payment.payment', $data);
    }

    /**
     * Pre Accept send to Host
     *
     * @param array $request Input values
     * @return redirect to Rooms Detail page
     */
    public function pre_accept(Request $request, EmailController $email_controller)
    {        
        if($request->session_key && $request->session_key !='')
        {
            if(Session::get('get_token')!='')
            { 
                $user = JWTAuth::toUser(Session::get('get_token'));

                $mobile_web_auth_user_id=$user->id; 
            }
            else
            {
                
               $mobile_web_auth_user_id=@Auth::user()->id; 
            }     

            $country = @Session::get('payment.'.$s_key.'.mobile_payment_counry_code')=='' ? 'US': Session::get('payment.'.$s_key.'.mobile_payment_counry_code');
            $country_data = Country::where('short_name', $country)->first();

            if (!$country_data) {
                $message = trans('messages.lys.service_not_available_country');
                if(Session::get('get_token')=='')
                {
                   $this->helper->flash_message('error', $message); // Call flash message function
                   return back();
                }
                else
                {
                    return response()->json(['success_message'=>$message,'status_code'=>'0']);
                }
            }

            $s_key = $request->session_key;

            if(!isset(Session::get('payment')[$s_key])) {
                return redirect(404);
            }

            $booking_room=Rooms::find(Session::get('payment')[$s_key]['payment_room_id']);
             if(!$booking_room){
             return redirect('404');
             }

            // to prevent host book their own list
            if(Session::get('payment')[$s_key]['payment_room_id'])
            {
                $user_id = Rooms::find(Session::get('payment')[$s_key]['payment_room_id'])->user_id;
            
                if($user_id == @$mobile_web_auth_user_id)
                {
                    return redirect('rooms/'.Session::get('payment')[$s_key]['payment_room_id']);
                }
            }
            // to prevent host book their own list

            $data['price_list']       = json_decode($this->payment_helper->price_calculation($request->room_id, $request->checkin, $request->checkout, $request->number_of_guests,$request->special_offer_id));
            if(@$data['price_list']->status == 'Not available')
            {
                $this->helper->flash_message('error', trans('messages.rooms.dates_not_available')); // Call flash message function
                return redirect('rooms/'.$request->id);
            }

            //session and request value are equal or not 
            $room_id            =@Session::get('payment')[$s_key]['payment_room_id'];
            $payment_checkin    =@Session::get('payment')[$s_key]['payment_checkin'];
            $payment_checkout   =@Session::get('payment')[$s_key]['payment_checkout'];
            $number_of_guests   =@Session::get('payment')[$s_key]['payment_number_of_guests'];            
            $cancellation       =@Session::get('payment')[$s_key]['payment_cancellation'];
            $host_penalty = Fees::find(3)->value;

            $rooms = Rooms::find($room_id);
             
            $reservation = new Reservation;
            
            $reservation->room_id          = $room_id;
            $reservation->host_id          = Rooms::find($room_id)->user_id;
            $reservation->user_id          = $mobile_web_auth_user_id;
            $reservation->checkin          = date('Y-m-d', strtotime($payment_checkin));
            $reservation->checkout         = date('Y-m-d', strtotime($payment_checkout));
            $reservation->number_of_guests = $number_of_guests;        
            $reservation->nights           = $data['price_list']->total_nights;
            $reservation->per_night        = $data['price_list']->per_night;
            $reservation->subtotal         = $data['price_list']->subtotal;
            $reservation->cleaning         = $data['price_list']->cleaning_fee;
            $reservation->additional_guest = $data['price_list']->additional_guest;
            $reservation->security         = $data['price_list']->security_fee;
            $reservation->service          = $data['price_list']->service_fee;
            $reservation->airportfee        = $data['price_list']->airport_fee;
            $reservation->host_fee         = $data['price_list']->host_fee;
            $reservation->total            = $data['price_list']->total;
            $reservation->currency_code    = $data['price_list']->currency;
            $reservation->host_penalty     = $host_penalty;
            $reservation->type             = 'reservation';
            $reservation->status           = 'Pending';
            $reservation->cancellation     = $cancellation;
            $reservation->country          = $country;//'US'; mobile change
            $reservation->paymode          = @Session::get('payment.'.$s_key.'.payment_card_type');//mobile change
            
            $reservation->base_per_night                = $data['price_list']->base_rooms_price;
            $reservation->length_of_stay_type           = $data['price_list']->length_of_stay_type;
            $reservation->length_of_stay_discount       = $data['price_list']->length_of_stay_discount;
            $reservation->length_of_stay_discount_price = $data['price_list']->length_of_stay_discount_price;
            $reservation->booked_period_type            = $data['price_list']->booked_period_type;
            $reservation->booked_period_discount        = $data['price_list']->booked_period_discount;
            $reservation->booked_period_discount_price  = $data['price_list']->booked_period_discount_price;
            
            $reservation->save();
            
            $replacement = "[removed]";

            $dots=".*\..*\..*";

            $email_pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
            $url_pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
            $phone_pattern = "/\+?[0-9][0-9()\s+]{4,20}[0-9]/";

            $find = array($email_pattern, $phone_pattern);
            $replace = array($replacement, $replacement);

            $question = preg_replace($find, $replace,$request->message_to_host);
           
            if($question==$dots)
            {
                $question = preg_replace($url_pattern, $replacement, $question);
            }
            else{
                $question = preg_replace($find, $replace,$request->message_to_host);
            }

            $message = new Messages;

            $message->room_id        = $room_id;
            $message->reservation_id = $reservation->id;
            $message->user_to        = $rooms->user_id;
            $message->user_from      = $mobile_web_auth_user_id;
            $message->message        = $question;
            $message->message_type   = 1;
            $message->read           = 0;

            $message->save();

            $email_controller->inquiry($reservation->id, $question);
            
            if(Session::get('get_token')!='')
            {   
                $result=array('success_message'=>'Request Booking Send to Host','status_code'=>'1');
                return view('json_response.json_response',array('result' =>json_encode($result)));
            } 
            //end mobile changes
            $this->helper->flash_message('success', trans('messages.rooms.pre-accept_request',['first_name'=>$rooms->users->first_name])); // Call flash message function

            Session::forget('s_key');
            Session::forget('payment.'.$s_key);

            return redirect('trips/current');
        }
        else
        {
            return redirect('404'); 
            if(empty(Session::get('payment'))) return redirect('404'); 
            $session_key = array_keys(Session::get('payment'));
            $s_key = end($session_key);        

            Session::put('s_key',$s_key);

            return redirect('payments/book/'.Session::get('payment')[$s_key]['payment_room_id']);
        }
    }

    /**
     * Appy Coupen Code Function
     *
     * @param array $request    Input values
     * @return redirect to Payemnt Page
     */

    


    public function apply_ngo(Request $request){
        $charity      = $request->charity;
        $s_key            = $request->s_key;
        $ngoArr =array();
        
        Session::forget('ngo_code');
        Session::forget('ngo_amount');
        Session::forget('ngo_status');

          if(count($charity) > 0){
                    foreach ($charity as $row) {
                        $ngo_detail = Ngofee::where('id',$row)->get();
                        foreach ($ngo_detail as $key => $value) {
                            $currency = Currency::find($value->currency)->code;
                            $amount = $this->payment_helper->currency_convert($currency,'',$value->amount);
                            array_push($ngoArr, $amount);
                        }
                    }
                    $ngo_amount = array_sum($ngoArr);    
                 }else{
                    $ngo_amount = 0;
           }
            $price_list       = Session::get('payment')[$s_key]['payment_price_list'];
            $ngo_amount = array_sum($ngoArr);
           // $data['ngo_amount']  = $ngo_amount;


            $actual_amount = ($price_list->subtotal + $price_list->service_fee + $price_list->airport_fee - $price_list->coupon_amount);

            
            $ngo_applied_total = $actual_amount + $ngo_amount;
            $data['count'] = count($charity);
            $data['ngo_applied_total']  = $ngo_applied_total > 0 ? $ngo_applied_total: 0;

            $room_detail          = Rooms::find(Session::get('payment')[$s_key]['payment_room_id']);
            $data['paypal_price']  = $this->payment_helper->currency_convert($room_detail->rooms_price->code, PAYPAL_CURRENCY_CODE, $ngo_applied_total);
            $from_rate = @Currency::whereCode($room_detail->rooms_price->currency_code)->first()->rate;
            $to_rate = @Currency::whereCode(PAYPAL_CURRENCY_CODE)->first()->rate;
            $data['paypal_price_rate']       = number_format(($from_rate/$to_rate), 2);

            Session::put('ngo_code', $charity);
            Session::put('ngo_amount', $ngo_amount);
            Session::put('ngo_status', 'yes');
            Session::save();

        return json_encode($data);
        
    }


    public function apply_coupon(Request $request)
    {
        $coupon_code      = $request->coupon_code;
        $s_key            = $request->s_key;
        $result           = CouponCode::where('coupon_code', $coupon_code)->where('status','Active')->get();
        $interval         = "Check_Expired_coupon";

        if($result->count())
        {
            // get user id
            $user_id = @Auth::user()->id;
            
            // check if coupon already used by the user
            $reservation_result = Reservation::where('user_id', $user_id)->where('coupon_code', $coupon_code)->get();
            if($reservation_result->count())
            {
                $data['message']  = trans('messages.payments.coupon_already_used');
                return json_encode($data);
            }


            $datetime1 = new DateTime(); 
            $datetime2 = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($result[0]->expired_at)));

            if($datetime1 < $datetime2)
            {
                $interval_diff = $datetime1->diff($datetime2);
                if($interval_diff->days)
                $interval      = $interval_diff->days; 
                else
                 $interval      = $interval_diff->h;            }
            else
            {
                $interval = "Expired_coupon"; 
            } 
        }
        else
            $interval = "Check_Expired_coupon";

        if($interval != "Expired_coupon" && $interval != "Check_Expired_coupon")
        {
            $id               = Session::get('payment')[$s_key]['payment_room_id'];
            $price_list       = Session::get('payment')[$s_key]['payment_price_list'];
            $code             = Session::get('currency');
            
            $data['coupon_amount']  = $this->payment_helper->currency_convert($result[0]->currency_code,$code,$result[0]->amount);
            $coupon_applied_total = ($price_list->subtotal + $price_list->service_fee + $price_list->airport_fee ) - $data['coupon_amount'];
            $data['ngo_amount'] = $price_list->ngo_amount;
            if($price_list->ngo_amount > 0){
                if($coupon_applied_total < 0){
                    $coupon_applied_total = 0;
                }
                $coupon_applied_total = $price_list->ngo_amount + $coupon_applied_total;
            }
            $data['coupen_applied_total']  = $coupon_applied_total > 0 ? $coupon_applied_total: 0;
            Session::forget('coupon_code');
            Session::forget('coupon_amount');
            Session::forget('remove_coupon');
            Session::forget('manual_coupon');
            Session::put('coupon_code', $coupon_code);
            Session::put('coupon_amount', $data['coupon_amount']);
            Session::put('manual_coupon', 'yes');
        }
        else
        { 
            if($interval == "Expired_coupon")
            {
                $data['message']  = trans('messages.payments.expired_coupon');  
            }
            else
            {
                $data['message']  = trans('messages.payments.invalid_coupon');     
            }
        }

        return json_encode($data);
    }

    public function remove_coupon(Request $request)
    {
        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('manual_coupon');
        Session::put('remove_coupon', 'yes');
    }

    /**
     * Payment Submit Function
     *
     * @param array $request    Input values
     * @return redirect to Dashboard Page
     */

    public function create_booking(Request $request)
    {  
        //dd(Session::all()); 
        $listed = Rooms::find($request->room_id);

        if($listed!='' && $listed->status=="Listed" && isset($request->session_key) && $request->session_key !='' && isset(Session::get('payment')[$request->session_key]))
        {
            $s_key = $request->session_key;

            if(Session::get('get_token')!='')
            { 
                $user = JWTAuth::toUser(Session::get('get_token'));

                $mobile_web_auth_user_id=$user->id; 
            }
            else
            {            
               $mobile_web_auth_user_id=@Auth::user()->id; 
            }     

            $reservation_id = $i_id=@Session::get('payment')[$s_key]['payment_reservation_id'];
            $room_id= $request->room_id;
            $checkin= $request->checkin;
            $checkout=$request->checkout;
            
            // to prevent host book their own list

            if(Session::get('payment')[$s_key]['payment_room_id'])
            {
                    $user_id = Rooms::find(Session::get('payment')[$s_key]['payment_room_id'])->user_id;
            
                //if($user_id == @Auth::user()->id)
                if($user_id == @$mobile_web_auth_user_id)
                {
                    return redirect('rooms/'.Session::get('payment')[$s_key]['payment_room_id']);
                }
            }
            // to prevent host book their own list

            // Get PayPal credentials from payment_gateway table
            $paypal_credentials = PaymentGateway::where('site', 'PayPal')->get();
            
            $price_list     = json_decode($this->payment_helper->price_calculation($request->room_id, $request->checkin, $request->checkout, $request->number_of_guests,$request->special_offer_id, '', $reservation_id));
            
            if($price_list->status == 'Not available') {
                $this->helper->flash_message('error', trans('messages.rooms.dates_not_available'));
                return redirect('trips/current');
            }

            $amount         = $this->payment_helper->currency_convert($request->currency, PAYPAL_CURRENCY_CODE, $price_list->payment_total);

            $country = $request->payment_country;
            $country_data = Country::where('short_name', $country)->first();

            if (!$country_data && $price_list->coupon_code != 'Travel_Credit') {
                $message = trans('messages.lys.service_not_available_country');
                if(Session::get('get_token')=='')
                {
                   $this->helper->flash_message('error', $message); // Call flash message function
                   return back();
                }
                else
                {
                    return response()->json(['success_message'=>$message,'status_code'=>'0']);
                }
            }

            $message_to_host = $request->message_to_host;

            $room_id            =   Session::get('payment')[$s_key]['payment_room_id'];
            $checkin            =   Session::get('payment')[$s_key]['payment_checkin'];
            $checkout           =   Session::get('payment')[$s_key]['payment_checkout'];
            $number_of_guests   =   Session::get('payment')[$s_key]['payment_number_of_guests'];

            $reservation_id     =   @Session::get('payment')[$s_key]['payment_reservation_id'];
            $room               =   Rooms::find($room_id); 

            $payment_description=   $room->name.' '.$checkin.' - '.$checkout;
            //mobile /web redirect
           if(Session::get('get_token')!='')
            { 
                 $purchaseData   =   [
                    'testMode'  => ($paypal_credentials[3]->value == 'sandbox') ? true : false,
                    'amount'    => $amount,
                    'description' => $payment_description,
                    'currency'  => PAYPAL_CURRENCY_CODE,
                    'returnUrl' => url('api_payments/success?s_key='.$s_key),
                    'cancelUrl' => url('api_payments/cancel?s_key='.$s_key),
                ];
            }
            else
            {
                
                $purchaseData   =   [
                    'testMode'  => ($paypal_credentials[3]->value == 'sandbox') ? true : false,
                    'amount'    => $amount,
                    'description' => $payment_description,
                    'currency'  => PAYPAL_CURRENCY_CODE,
                    'returnUrl' => url('payments/success?s_key='.$s_key),
                    'cancelUrl' => url('payments/cancel?s_key='.$s_key),
                ];
            }     
           
            Session::put('payment.'.$s_key.'.pphone_number',"+".$request->country_code."~".$request->phone_number);
            Session::put('payment.'.$s_key.'.amount', $amount);
            Session::put('payment.'.$s_key.'.payment_country', $country);
            Session::put('payment.'.$s_key.'.message_to_host_'.$mobile_web_auth_user_id, $message_to_host);
            Session::put('payment.'.$s_key.'.payment_price_list', $price_list);
            Session::save();

            if(Session::get('payment.'.$s_key.'.payment_card_type')!='')
            {
                if($request->payment_type =='cc')
                {  
                    Session::put('payment.'.$s_key.'.payment_card_type','Credit Card');
                }
                else
                {
                    Session::put('payment.'.$s_key.'.payment_card_type','PayPal');
                }
            }
            if($request->payment_type =='paypal'){
                $rules  =   ['phone_number'  => 'required|numeric|',];
                $niceNames =  [ 'phone_number' => 'Phone number',];
                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($niceNames);
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput(); // Form calling with Errors and 
                }
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
                            'phone_number'        => 'required|numeric|',
                            ];

                $niceNames =    [
                                'cc_number'        => 'Card number',
                                'cc_expire_month'  => 'Expires',
                                'cc_expire_year'   => 'Expires',
                                'cc_security_code' => 'Security code',
                                'first_name'       => 'First name',
                                'last_name'        => 'Last name',
                                'zip'              => 'Postal code',
                                'phone_number'     => 'Phone number',
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


                $this->setup('Stripe');

                $card   =   [
                    'firstName'       => $request->first_name,
                    'lastName'        => $request->last_name,
                    'number'          => $request->cc_number, 
                    'expiryMonth'     => $request->cc_expire_month, 
                    'expiryYear'      => $request->cc_expire_year, 
                    'cvv'             => $request->cc_security_code, 
                    'billingAddress1' => $request->payment_country,
                    'billingCountry'  => $request->payment_country,
                    'billingCity'     => $request->payment_country,
                    'billingPostcode' => $request->zip,
                    'billingState'    => $request->payment_country
                ];

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
            }
            else 

                $this->setup();    

            if($amount > 0) {
                try{
                    $response = $this->omnipay->purchase($purchaseData)->send();
                }
                catch(\Exception $e)
                {
                    $this->helper->flash_message('error', $e->getMessage());
                    return redirect('payments/book/'.$request->room_id.'?s_key='.$s_key);
                }
                // Process response
                if ($response->isSuccessful())
                {
                    // Payment was successful 
                    $result = $response->getData();

                    $data = [
                        'room_id'          => $request->room_id,
                        'checkin'          => $request->checkin,
                        'checkout'         => $request->checkout,
                        'number_of_guests' => $request->number_of_guests,
                        'transaction_id'   => $request->payment_method == 'cc' ? @$result['id'] : @$result['TRANSACTIONID'],
                        'price_list'       => $price_list,
                        'paymode'          => 'Credit Card',
                        'first_name'       => $request->first_name,
                        'last_name'        => $request->last_name,
                        'postal_code'      => $request->zip,
                        'pphone_number'    => '+'.$request->country_code."~".$request->phone_number,
                        'country'          => $request->payment_country,
                        'message_to_host'  => Session::get('payment')[$s_key]['message_to_host_'.$mobile_web_auth_user_id],
                        's_key'            => $s_key,
                    ];

                    $code = $this->store($data);
                    $this->status_update($request->room_id,$request->checkin,$request->checkout);
                    
                    //mobile changes
                    if(Session::get('get_token')!='')
                    {   
                         $result=array('success_message'=>'Payment Successfully Paid','status_code'=>'1');
                         return view('json_response.json_response',array('result' =>json_encode($result)));
                      
                    }
                    //end mobile changes
                    //payment success
                    $this->helper->flash_message('success', trans('messages.payments.payment_success')); // Call flash message function
                    return redirect('reservation/requested?code='.$code);
                } 
                elseif ($response->isRedirect()) 
                {

                    // Redirect to offsite payment gateway
                    $response->redirect();
                } 
                else 
                {   
                    Session::put('s_key',$s_key);
                    //payment failed for web
                      if(Session::get('get_token')!='')
                    {   
                        $result=array('success_message'=>'Payment Failed','status_code'=>'0','error', $response->getMessage());
                        return view('json_response.json_response',array('result' =>json_encode($result)));  
                    } 
                    //end mobile changes
                    // Payment failed
                    $this->helper->flash_message('error', $response->getMessage()); // Call flash message function
                    return redirect('payments/book/'.$request->room_id.'?s_key='.$s_key);            
                }
            }
            else 
            {

                $rules =    ['phone_number'        => 'required|numeric|',];
                $niceNames =    ['phone_number'     => 'Phone number',];
                $validator = Validator::make($request->all(), $rules);
                $validator->setAttributeNames($niceNames);
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
                }             
                $data = [
                    'room_id'          => $request->room_id,
                    'checkin'          => $request->checkin,
                    'checkout'         => $request->checkout,
                    'number_of_guests' => $request->number_of_guests,
                    'transaction_id'   => '',
                    'price_list'       => $price_list,
                    'paymode'          => ($request->payment_type == 'cc') ? 'Credit Card' : 'PayPal',
                    'first_name'       => $request->first_name,
                    'last_name'        => $request->last_name,
                    'postal_code'      => $request->zip,
                    'pphone_number'    => '+'.$request->country_code."~".$request->phone_number,
                    'country'          => $request->payment_country,
                    'message_to_host'  => Session::get('payment')[$s_key]['message_to_host_'.$mobile_web_auth_user_id],
                    's_key'            => $s_key,
                ];
                
                $code = $this->store($data);
                $this->status_update($request->room_id,$request->checkin,$request->checkout);
                //payment success for mobile
                if(Session::get('get_token')!='')
                {   
                    $result=array('success_message'=>'Payment Successfully Paid','status_code'=>'1');
                    return view('json_response.json_response',array('result' =>json_encode($result)));
                } 
                //end mobile changes  
                $this->helper->flash_message('success', trans('messages.payments.payment_success')); // Call flash message function
                return redirect('reservation/requested?code='.$code);
            }
        }
        else
        {
            return redirect('404'); 
            if(empty(Session::get('payment'))) return redirect('404'); 
            $session_key = array_keys(Session::get('payment'));
            $s_key = end($session_key);        

            Session::put('s_key',$s_key);

            return redirect('payments/book/'.Session::get('payment')[$s_key]['payment_room_id']);
        }
    }

    /**
     * Callback function for Payment Success
     *
     * @param array $request    Input values
     * @return redirect to Payment Success Page
     */
    public function success(Request $request)
    {   

        if(!@Session::get('payment')[$request->s_key])
        {
            if(Session::get('get_token')!='')
            {   
                $result=array('success_message'=>'Payment Successfully Paid','status_code'=>'1');
                return view('json_response.json_response',array('result' =>json_encode($result)));
            } 

            return redirect('/');
        } 
        $s_key = $request->s_key;
        if(Session::get('get_token')!='')
        { 
            $user = JWTAuth::toUser(Session::get('get_token'));
            $mobile_web_auth_user_id=$user->id; 
        }
        else
        {
           $mobile_web_auth_user_id=@Auth::user()->id; 
        }     
        
        $this->setup();

        $transaction = $this->omnipay->completePurchase(array(
            'payer_id'              => $request->PayerID,
            'transactionReference'  => $request->token,
            'amount'                => Session::get('payment')[$s_key]['amount'],
            'currency'              => PAYPAL_CURRENCY_CODE
        ));

        try
        {
            $response = $transaction->send();
        }
        catch(\Exception  $e)
        {
            $this->helper->flash_message('error', @$e->getMessage());
            return redirect('payments/book?s_key='.$s_key);
        }

        $result = $response->getData();

        if(@$result['ACK'] == 'Success')
        {
            $data = [
                'room_id'          => Session::get('payment')[$s_key]['payment_room_id'],
                'checkin'          => Session::get('payment')[$s_key]['payment_checkin'],
                'checkout'         => Session::get('payment')[$s_key]['payment_checkout'],
                'number_of_guests' => Session::get('payment')[$s_key]['payment_number_of_guests'],
                'transaction_id'   => @$result['PAYMENTINFO_0_TRANSACTIONID'],
                'price_list'       => Session::get('payment')[$s_key]['payment_price_list'],
                'country'          => Session::get('payment')[$s_key]['payment_country'],
                'pphone_number'    => Session::get('payment')[$s_key]['pphone_number'],
                'message_to_host'  => Session::get('payment')[$s_key]['message_to_host_'.$mobile_web_auth_user_id],
                'paymode'          => 'PayPal',
                's_key'            => $s_key,
            ];
            //dd($data);
            $room_id = $data['room_id'];

            $checkin  = date('Y-m-d', $this->helper->custom_strtotime($data['checkin']));
            $checkout = date('Y-m-d', $this->helper->custom_strtotime($data['checkout']));
            
            $days     = $this->get_days_search($checkin, $checkout);
            unset($days[count($days)-1]);
            $count_reservation = Calendar::where('room_id',$room_id)->daysNotAvailable($days, $data['number_of_guests'])->get();
            if($count_reservation->count() > 0 )
            {
                //refund process
                $refund = $this->omnipay->refund(array(
                    'payer_id'              => $request->PayerID,
                    'transactionReference'  => $data['transaction_id'],
                    'amount'                => Session::get('payment')[$s_key]['amount'],
                    'currency'              => PAYPAL_CURRENCY_CODE
                ));
                $response = $refund->send();

                $refundresult = $response->getData();
                $code = $this->decline_store($data);

                if(@$refundresult['ACK'] == 'Success')
                {
                    $return_message=trans('messages.payments.refundpayment');
                }
                else
                {
                    $return_message=trans('messages.payments.refundpayment_cancel');
                }
                //mobile changes
                if(Session::get('get_token')!='')
                {  
                    $result=array('success_message'=>'Payment Failed',

                                    'status_code'=>'0',

                                    'error'=> trans('messages.payments.refundpayment'));

                    return view('json_response.json_response',array('result' =>json_encode($result)));
                } 
                $this->helper->flash_message('error', $return_message);
                return redirect('trips/current');
            }
            else{
                //mobile changes
                //dd($data);
                $code = $this->store($data);
                
                if(Session::get('get_token')!='')
                {   
                    $result=array('success_message'=>'Payment Successfully Paid','status_code'=>'1');
                    return view('json_response.json_response',array('result' =>json_encode($result)));
                } 
                //end mobile changes
                
                $this->helper->flash_message('success', trans('messages.payments.payment_success')); 
                return redirect('reservation/requested?code='.$code);
            }
        }
        else
        {    
            Session::put('s_key',$s_key);
            
            //mobile changes
            if(Session::get('get_token')!='')
            {  
                if($result['L_SHORTMESSAGE0']=='Duplicate Request')
                {

                    $result=array('success_message'=>'Payment Successfully Paid','status_code'=>'1');
                    return view('json_response.json_response',array('result' =>json_encode($result)));
                }
                else
                {
                    $result=array('success_message'=>'Payment Failed',

                                'status_code'=>'0',

                                'error'=>$result['L_LONGMESSAGE0']);
                    return view('json_response.json_response',array('result' =>json_encode($result)));
                }
            } 
            //end mobile changes

            // Payment failed
            $this->helper->flash_message('error', $result['L_SHORTMESSAGE0']); // Call flash message function
            return redirect('payments/book/'.Session::get('payment')[$s_key]['payment_room_id'].'?s_key='.$s_key);
        }
    }


    // Checking for Already Booked Or Not..
    public function status_update($room_id, $checkin, $checkout)
    {
        $chck_reservation= Reservation::where(['room_id'=>$room_id,'checkin'=>$checkin, 'checkout'=>$checkout])->where('status','!=','Accepted')->get();
        $count_reservation=count($chck_reservation);
        if($count_reservation > '0')
        {
            foreach ($chck_reservation as $result) 
            {
                // Reservation::where('id',$result->id)->update(['date_check' => 'No']);
            }
        }
    }

    /**
     * Callback function for Payment Failed
     *
     * @param array $request    Input values
     * @return redirect to Payments Booking Page
     */
    public function cancel(Request $request)
    {    
        $s_key = $request->s_key;
        $redirect_to = '404';

        Session::put('s_key',$s_key);

        // Payment failed        
        if(Session::get('get_token')!='')
        {
            $result=array('success_message' => 'The payment process was cancelled.',

                        'status_code'    => '0');
            return view('json_response.json_response',array('result' =>json_encode($result)));
        }

        if(isset(Session::get('payment')[$s_key])) {
            $redirect_to = 'payments/book/'.Session::get('payment')[$s_key]['payment_room_id'].'?s_key='.$s_key;
        }

        $this->helper->flash_message('error', trans('messages.payments.payment_cancelled')); // Call flash message function

        return redirect($redirect_to);
    }

    /**
     * Create Reservation After paypal refund  Done when same time booking
     *
     * @param array $data    Payment Data
     * @return string $code  Reservation Code
     */
    public function decline_store($data)
    {
        $s_key = $data['s_key'];
        $special_offer_ids = @Session::get('payment')[$s_key]['payment_special_offer_id'];

        //change the contact data status after the contact moved to reservation - For calendar purpose
        if($special_offer_ids!="" && $special_offer_ids!="0")
        {
            $get_contact_id=SpecialOffer::find($special_offer_ids);
            if($get_contact_id)
            {
                $contact_id=$get_contact_id->reservation_id;   
                // Reservation::where('id',$contact_id)->update(['status'=>'Cancelled']);
            }
        }
        if(Session::get('get_token')!='')
        { 
            $user = JWTAuth::toUser(Session::get('get_token'));

            $mobile_web_auth_user_id=$user->id; 
        }
        else
        {
           $mobile_web_auth_user_id=@Auth::user()->id; 
        }     

        if(@Session::get('payment')[$s_key]['payment_reservation_id'])
            $reservation= Reservation::find(Session::get('payment')[$s_key]['payment_reservation_id']);
        else
            $reservation = new Reservation;

        $reservation->room_id           = $data['room_id'];
        $reservation->host_id           = Rooms::find($data['room_id'])->user_id;
        $reservation->user_id           = $mobile_web_auth_user_id;
        $reservation->checkin          = date('Y-m-d', strtotime($data['checkin']));
        $reservation->checkout         = date('Y-m-d', strtotime($data['checkout']));
        $reservation->number_of_guests  = $data['number_of_guests'];
        $reservation->nights            = $data['price_list']->total_nights;
        $reservation->per_night         = $data['price_list']->per_night;
        $reservation->subtotal          = $data['price_list']->subtotal;
        if($data['price_list']->special_offer == '' ){
        $reservation->cleaning          = $data['price_list']->cleaning_fee;
        $reservation->additional_guest  = $data['price_list']->additional_guest;
        $reservation->security          = $data['price_list']->security_fee;
        }else{
        $reservation->cleaning          = 0;
        $reservation->additional_guest  = 0;
        $reservation->security          = 0;
        }
        $reservation->service           = $data['price_list']->service_fee;
        $reservation->airportfee        = $data['price_list']->airport_fee;
        $reservation->ngo_list          = $data['price_list']->ngo_list;
        $reservation->ngo_amount        = $data['price_list']->ngo_amount;

        $reservation->host_fee          = $data['price_list']->host_fee;
        $reservation->total             = $data['price_list']->total;
        $reservation->currency_code     = $data['price_list']->currency;
        $reservation->paypal_currency     = PAYPAL_CURRENCY_CODE;

        $reservation->base_per_night                = $data['price_list']->base_rooms_price;
        $reservation->length_of_stay_type           = $data['price_list']->length_of_stay_type;
        $reservation->length_of_stay_discount       = $data['price_list']->length_of_stay_discount;
        $reservation->length_of_stay_discount_price = $data['price_list']->length_of_stay_discount_price;
        $reservation->booked_period_type            = $data['price_list']->booked_period_type;
        $reservation->booked_period_discount        = $data['price_list']->booked_period_discount;
        $reservation->booked_period_discount_price  = $data['price_list']->booked_period_discount_price;
        
        if($data['price_list']->coupon_amount)
        {
          $reservation->coupon_code       = $data['price_list']->coupon_code;
          $reservation->coupon_amount     = $coupon_amount = $data['price_list']->coupon_amount;
        }
        if(@Session::get('payment')[$s_key]['payment_special_offer_id'])
        {
            $reservation->special_offer_id  = $special_offer_ids;
        }
        
        $reservation->transaction_id    = $data['transaction_id'];
        $reservation->paymode           = $data['paymode'];        
        $reservation->type              = 'reservation';
        
        if($data['paymode'] == 'Credit Card')
        {
            $reservation->first_name   = $data['first_name'];
            $reservation->last_name    = $data['last_name'];
            $reservation->postal_code  = $data['postal_code'];
        }
        
        $reservation->country          = $data['country'];
        
        if(@Session::get('payment')[$s_key]['payment_reservation_id']==''){
            $reservation->cancellation      = Rooms::find($data['room_id'])->cancel_policy;
        }

        $reservation->status= @$reservation->id  ? $reservation->status : "Declined";
        $reservation->save();

        $messages = new Messages;
        $messages->room_id        = $reservation->room_id;
        $messages->reservation_id = $reservation->id;
        $messages->user_to        = Auth::user()->id;
        $messages->user_from      = $reservation->host_id;
        $messages->message        = '';
        $messages->message_type   = 10;

        $messages->save();

        Session::forget('ngo_status');
        Session::forget('ngo_amount');
        Session::forget('ngo_list');

        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('remove_coupon');
        Session::forget('manual_coupon');
        Session::forget('s_key');
        Session::forget('payment.'.$s_key);

        return true;
    }

    /**
     * Create Reservation After Payment Successfully Done
     *
     * @param array $data    Payment Data
     * @return string $code  Reservation Code
     */
    public function store($data){
        
        $s_key = $data['s_key'];        
        $special_offer_ids = @Session::get('payment')[$s_key]['payment_special_offer_id'];

        //change the contact data status after the contact moved to reservation - For calendar purpose
        if($special_offer_ids!="" && $special_offer_ids!="0")
        {
            $get_contact_id=SpecialOffer::find($special_offer_ids);
            if($get_contact_id)
            {
                $contact_id=$get_contact_id->reservation_id;   
                // Reservation::where('id',$contact_id)->update(['status'=>'Cancelled']);
            }
        }

        if(Session::get('get_token')!='')
        { 
            $user = JWTAuth::toUser(Session::get('get_token'));
            $mobile_web_auth_user_id=$user->id; 
        }
        else
        {
           $mobile_web_auth_user_id=@Auth::user()->id; 
        }     

        if(@Session::get('payment')[$s_key]['payment_reservation_id'])
            $reservation= Reservation::find(Session::get('payment')[$s_key]['payment_reservation_id']);
        else
            $reservation = new Reservation;

        $days = $this->get_days(date('Y-m-d', strtotime($data['checkin'])), date('Y-m-d', strtotime($data['checkout'])));
        
        // Update Calendar
        for($j=0; $j<count($days)-1; $j++)
        {

            $special_price = Calendar::where('room_id',$data['room_id'])->where('date',$days[$j])->first();
            if($special_price)
                $price = $special_price->price;
            else
                $price = RoomsPrice::find($data['room_id'])->original_night;
            
            $calendar_data = [
                'room_id' => $data['room_id'],
                'date'    => $days[$j],
                'status'  => 'Not available',
                'price'   => $price,
            ];

            $calendar = Calendar::updateOrCreate(['room_id' => $data['room_id'], 'date' => $days[$j]], $calendar_data);
            $calendar->spots_booked = $calendar->spots_booked+$data['number_of_guests'];
            $calendar->source = 'Reservation';
            $calendar->save();
        }

        $reservation->room_id           = $data['room_id'];
        $reservation->host_id           = Rooms::find($data['room_id'])->user_id;
        $reservation->user_id           = $mobile_web_auth_user_id;
        $reservation->checkin           = date('Y-m-d', strtotime($data['checkin']));
        $reservation->checkout          = date('Y-m-d', strtotime($data['checkout']));
        $reservation->number_of_guests  = $data['number_of_guests'];
        $reservation->nights            = $data['price_list']->total_nights;
        $reservation->per_night         = $data['price_list']->per_night;
        $reservation->subtotal          = $data['price_list']->subtotal;
        if($data['price_list']->special_offer == '' )
        {
            $reservation->cleaning          = $data['price_list']->cleaning_fee;
            $reservation->additional_guest  = $data['price_list']->additional_guest;
        }
        else
        {
            $reservation->cleaning          = 0;
            $reservation->additional_guest  = 0;
        }

        $reservation->security          = $data['price_list']->security_fee;
        $reservation->service           = $data['price_list']->service_fee;
        $reservation->airportfee        = $data['price_list']->airport_fee;
        $reservation->ngo_list          = $data['price_list']->ngo_list;
        $reservation->ngo_amount        = $data['price_list']->ngo_amount;
        $reservation->host_fee          = $data['price_list']->host_fee;
        $reservation->total             = $data['price_list']->total;
        $reservation->currency_code     = $data['price_list']->currency;
        $reservation->paypal_currency   = PAYPAL_CURRENCY_CODE;

        $reservation->base_per_night                = $data['price_list']->base_rooms_price;
        $reservation->length_of_stay_type           = $data['price_list']->length_of_stay_type;
        $reservation->length_of_stay_discount       = $data['price_list']->length_of_stay_discount;
        $reservation->length_of_stay_discount_price = $data['price_list']->length_of_stay_discount_price;
        $reservation->booked_period_type            = $data['price_list']->booked_period_type;
        $reservation->booked_period_discount        = $data['price_list']->booked_period_discount;
        $reservation->booked_period_discount_price  = $data['price_list']->booked_period_discount_price;
        
        if($data['price_list']->coupon_amount)
        {
          $reservation->coupon_code       = $data['price_list']->coupon_code;
          $reservation->coupon_amount     = $coupon_amount = $data['price_list']->coupon_amount;
        }
        if(@Session::get('payment')[$s_key]['payment_special_offer_id'])
        {
            $reservation->special_offer_id  = $special_offer_ids;
        }
        
        $reservation->transaction_id    = $data['transaction_id'];
        $reservation->paymode           = $data['paymode'];        
        $reservation->type              = 'reservation';
        
        if($data['paymode'] == 'Credit Card')
        {
            $reservation->first_name   = $data['first_name'];
            $reservation->last_name    = $data['last_name'];
            $reservation->postal_code  = $data['postal_code'];
        }
        
        $reservation->pphone_number    = $data['pphone_number'];
        $reservation->country          = $data['country'];
        $reservation->status           = (@Session::get('payment')[$s_key]['payment_booking_type'] == 'instant_book') ? 'Accepted' : 'Pending';
        
        if(@Session::get('payment')[$s_key]['payment_reservation_id']=='')
        {
            $reservation->cancellation      = Rooms::find($data['room_id'])->cancel_policy;
            $reservation->host_penalty      = Fees::find(3)->value;
        }  
        // dd($reservation);
        $reservation->save();
        $this->status_update($reservation->room_id,$reservation->checkin,$reservation->checkout);

        if(@$data['price_list']->coupon_code == 'Travel_Credit') {
            $coupon_amount = $data['price_list']->coupon_amount;
            $referral_friend = Referrals::whereFriendId($mobile_web_auth_user_id)->get();
            foreach($referral_friend as $row) {
                $friend_credit = $row->friend_credited_amount;
                if($coupon_amount != 0) {
                    if($friend_credit <= $coupon_amount) {
                        $referral = Referrals::find($row->id);
                        $referral->friend_credited_amount = 0;
                        $referral->save();
                        $coupon_amount = $coupon_amount - $friend_credit;

                        $applied_referral = new AppliedTravelCredit;
                        $applied_referral->reservation_id = $reservation->id;
                        $applied_referral->referral_id = $row->id;
                        $applied_referral->amount = $friend_credit;
                        $applied_referral->type = 'friend';
                        $applied_referral->currency_code = $data['price_list']->currency;
                        $applied_referral->save();                    
                    }
                    else {
                        $referral = Referrals::find($row->id);
                        $remain = $friend_credit - $coupon_amount;
                        $referral->friend_credited_amount = $referral->convert($remain);
                        $referral->save();
                        
                        $applied_referral = new AppliedTravelCredit;
                        $applied_referral->reservation_id = $reservation->id;
                        $applied_referral->referral_id = $row->id;
                        $applied_referral->amount = $coupon_amount;
                        $applied_referral->type = 'friend';
                        $applied_referral->currency_code = $data['price_list']->currency;
                        $applied_referral->save();
                        $coupon_amount = 0;
                    }
                }
            }
            $referral_user = Referrals::whereUserId($mobile_web_auth_user_id)->get();
            foreach($referral_user as $row) {
                $user_credit = $row->credited_amount;
                if($coupon_amount != 0) {
                    if($user_credit <= $coupon_amount) {
                        $referral = Referrals::find($row->id);
                        $referral->credited_amount = 0;
                        $referral->save();
                        $coupon_amount = $coupon_amount - $user_credit;
                        
                        $applied_referral = new AppliedTravelCredit;
                        $applied_referral->reservation_id = $reservation->id;
                        $applied_referral->referral_id = $row->id;
                        $applied_referral->amount = $user_credit;
                        $applied_referral->type = 'main';
                        $applied_referral->currency_code = $data['price_list']->currency;
                        $applied_referral->save();
                    }
                    else {
                        $referral = Referrals::find($row->id);
                        $referral->credited_amount = $user_credit - $coupon_amount;
                        $referral->save();
                        
                        $applied_referral = new AppliedTravelCredit;
                        $applied_referral->reservation_id = $reservation->id;
                        $applied_referral->referral_id = $row->id;
                        $applied_referral->amount = $coupon_amount;
                        $applied_referral->type = 'main';
                        $applied_referral->currency_code = $data['price_list']->currency;
                        $applied_referral->save();
                        $coupon_amount = 0;
                    }
                }
            }
        }

        do
        {
            $code = $this->getCode(6, $reservation->id);
            $check_code = Reservation::where('code', $code)->get();
        }
        while(empty($check_code));

        $reservation_code = Reservation::find($reservation->id);
        $reservation_code->code = $code;
        $reservation_code->save();

        if($reservation_code->status == 'Accepted') 
        {   
            $reservation_details = Reservation::find($reservation_code->id);
            $host_payout_amount  = $reservation_details->host_payout;
            $this->payment_helper->payout_refund_processing($reservation_details, 0, $host_payout_amount);
        }

        $message = new Messages;
        $messages='';
        if(@$data['message_to_host'])
        $messages = $this->helper->phone_email_remove($data['message_to_host']);
            
        $message->room_id        = $data['room_id'];
        $message->reservation_id = $reservation->id;
        $message->user_to        = $reservation->host_id;
        $message->user_from      = $reservation->user_id;
        $message->message        = $messages;
        $message->message_type   = 2;
        $message->read           = 0;

        $message->save();

        $email_controller = new EmailController;


        $today = date('Y-m-d');  
        $datetime1 = new DateTime($today);
        $datetime2 = new DateTime($data['checkin']);
        $interval = $datetime1->diff($datetime2);
        $diff_date = $interval->format('%d'); 
        if($diff_date <=1 ){
            $day = ($diff_date ==0) ? 'today' : 'tomorrow';
            $newreservation = Reservation::with('users')->find($reservation->id);
            $email_controller->guest_driver($newreservation,$day);
        }

        $email_controller->accepted($reservation->id);
        $email_controller->booking_confirm_host($reservation->id);
        $email_controller->booking_confirm_admin($reservation->id);

        Session::forget('ngo_status');
        Session::forget('ngo_amount');
        Session::forget('ngo_list');
        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('remove_coupon');
        Session::forget('manual_coupon');
        Session::forget('s_key');
        Session::forget('payment.'.$s_key);

        return $code;
    }

    /**
     * Get days between two dates
     *
     * @param date $sStartDate  Start Date
     * @param date $sEndDate    End Date
     * @return array $days      Between two dates
     */
    public function get_days($sStartDate, $sEndDate)
    {    
        $aDays[]      = $sStartDate;
        $sCurrentDate = $sStartDate;  
       
        while($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
            $aDays[]      = $sCurrentDate;  
        }
      
        return $aDays;  
    }
    public function get_days_search($sStartDate, $sEndDate)
    {            
        $aDays[]      = $sStartDate;  
        $sCurrentDate = $sStartDate;  
        while($sCurrentDate < $sEndDate)
        {
            $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  
            $aDays[]      = $sCurrentDate;  
        }
      
        return $aDays;  
    }

    /**
     * Generate Reservation Code
     *
     * @param date $length  Code Length
     * @param date $seed    Reservation Id
     * @return string Reservation Code
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
