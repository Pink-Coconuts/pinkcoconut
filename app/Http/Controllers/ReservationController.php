<?php

/**
 * Reservation Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Reservation
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use Auth;
use App\Models\Reservation;
use App\Models\Messages;
use App\Models\Calendar;
use App\Models\Rooms;
use App\Models\RoomsPhotos;
use App\Models\RoomsPrice;
use App\Models\ReservationAlteration;
use App\Models\HostPenalty;
use App\Models\Payouts;
use App\Models\HostExperienceCalendar;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Fees;
use App\Models\Ngofee;
use App\Models\Currency;


use DateTime;
use DB;
use Session;

class ReservationController extends Controller
{
    protected $helper; // Global variable for Helpers instance
    
    protected $payment_helper; // Global variable for PaymentHelper instance

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['reservation_id'] = $request->id;

        $read_count   = Messages::where('reservation_id',$request->id)->where('user_to',Auth::user()->id)->where('read','0')->count();

        if($read_count !=0)
        {
        Messages::where('reservation_id',$request->id)->where('user_to',Auth::user()->id)->update(['read' =>'1']);  
        }

        $data['result']         = Reservation::find($request->id);

        if(!$data['result'])
            abort('404');

        if($data['result']->host_id != Auth::user()->id)
            abort('404');

        return view('reservation.reservation_detail', $data);
    }

    public function cencel_request_send(Request $request)
    {
        $data  = $request;
        $data  = json_decode($data['data']);

        $reservation_details = Reservation::where('id',$data->id)->where('status','Cancelled')->get();
        if(count($reservation_details) > 0)
            return json_encode(['success'=>'true']);
        else
            return json_encode(['success'=>'false']);
    }

    /**
     * Reservation Request Accept by Host
     *
     * @param array $request Input values
     * @return redirect to Reservation Request page
     */
    public function accept(Request $request, EmailController $email_controller)
    {
        $reservation_details = Reservation::find($request->id);
        if($reservation_details->status == 'Cancelled')
        {
            $this->helper->flash_message('success', trans('messages.your_trips.guest_cancelled_reservation')); // Call flash message function
            return redirect('reservation/'.$request->id);
        }
        else
        {
            $reservation_details->status            = 'Pre-Accepted';
            $reservation_details->accepted_at       = date('Y-m-d H:m:s');

            $reservation_details->save();

            $friends_email = explode(',', $reservation_details->friends_email);
            if(count($friends_email) > 0){
                foreach($friends_email as $email){
                if($email != ''){
                   $email_controller->itinerary($reservation_details->code, $email);
                }
              }
            }

            $messages = new Messages;

            $messages->room_id        = $reservation_details->room_id;
            $messages->reservation_id = $reservation_details->id;
            $messages->user_to        = $reservation_details->user_id;
            $messages->user_from      = Auth::user()->id;
            $messages->message        = $this->helper->phone_email_remove($request->message);
            $messages->message_type   = 12;

            $messages->save();

            $email_controller->pre_accepted($reservation_details->id);

            $this->helper->flash_message('success', trans('messages.your_trips.reservation_request_accepted')); 
            
            return redirect('reservation/'.$request->id);
        }
    }

    /**
     * Reservation Request Decline by Host
     *
     * @param array $request Input values
     * @return redirect to Reservation Request page
     */
    public function decline(Request $request, EmailController $email_controller)
    {
        $reservation_details = Reservation::find($request->id);
        if($reservation_details->status == 'Cancelled')
        {
          $this->helper->flash_message('success', trans('messages.your_trips.guest_cancelled_reservation')); // Call flash message 
          return redirect('reservation/'.$request->id);
        }
        else
        {
            $reservation_details->status          = 'Declined';
            $reservation_details->decline_reason  = ($request->decline_reason == 'other') ? $request->decline_reason_other : $request->decline_reason;
            $reservation_details->declined_at     = date('Y-m-d H:m:s');

            $reservation_details->save();

            $messages = new Messages;

            $messages->room_id        = $reservation_details->room_id;
            $messages->reservation_id = $reservation_details->id;
            $messages->user_to        = $reservation_details->user_id;
            $messages->user_from      = Auth::user()->id;
            $messages->message        =$this->helper->phone_email_remove($request->message);;
            $messages->message_type   = 3;

            $messages->save();

            //send mail to admin cancel this request
            $email_controller->cancel_host($reservation_details->id);

            $this->helper->flash_message('success', trans('messages.your_reservations.declined_successfully')); // Call flash message function
            return redirect('reservation/'.$request->id);
        }
    }

    /**
     * Reservation Request Expire
     *
     * @param array $request Input values
     * @return redirect to Reservation Request page
     */
    public function expire(Request $request)
    {
        $reservation_details = Reservation::find($request->id);
        
        // Expire penalty
        $cancel_count = Reservation::where('host_id', Auth::user()->id)->where('cancelled_by', 'Host')->where('cancelled_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 6 MONTH)'))->get()->count();
        
        // penalty management admin panel
        $host_penalty        = Fees::find(3)->value;
        $penalty_currency    = Fees::find(4)->value;
        $penalty_before_days = Fees::find(5)->value;
        $penalty_after_days  = Fees::find(6)->value;
        $penalty_cancel_limits_count  = Fees::find(7)->value;
        // penalty management admin panel
        
        // Expire penalty

        $to_time   = strtotime($reservation_details->getOriginal('created_at'));
        $from_time = strtotime(date('Y-m-d H:i:s'));
        $diff_mins = round(abs($to_time - $from_time) / 60,2);

        if($diff_mins >= 1440)
        {
            $reservation_details->status       = 'Expired';
            $reservation_details->expired_at   = date('Y-m-d H:m:s');

            $reservation_details->save();

            if($cancel_count >= $penalty_cancel_limits_count && $host_penalty == 1)
            {
                $host_penalty_amount  = $this->payment_helper->currency_convert($penalty_currency,$reservation_details->currency_code,$penalty_before_days);
                $this->payment_helper->payout_refund_processing($reservation_details, 0, 0, $host_penalty_amount);
            }

            $messages = new Messages;

            $messages->room_id        = $reservation_details->room_id;
            $messages->reservation_id = $reservation_details->id;
            $messages->user_to        = $reservation_details->user_id;
            $messages->user_from      = Auth::user()->id;
            $messages->message        = '';
            $messages->message_type   = 4;

            $messages->save();

            $email_controller = new EmailController;
            $email_controller->reservation_expired_admin($reservation_details->id);
            $email_controller->reservation_expired_guest($reservation_details->id);

            $this->helper->flash_message('success', trans('messages.your_reservations.expired_successfully')); // Call flash message function
            return redirect('reservation/'.$request->id);
        }
        else
        {
            $this->helper->flash_message('error', trans('messages.your_reservations.reservation_has_time')); // Call flash message function
            return redirect('reservation/'.$request->id);
        }
    }

    /**
     * Show Host Reservations
     *
     * @param array $request Input values
     * @return redirect to My Reservations page
     */
    public function my_reservations(Request $request)
    {
        if($request->all == 1)
        {
            $data['code'] = '1';
            $data['reservations'] = Reservation::where('host_id', Auth::user()->id)->where('type','!=','contact')->get();
        }
        else
        {
            $data['code'] = '0';
            $data['reservations'] = Reservation::where('host_id', Auth::user()->id)->where('checkout','>=',date('Y-m-d'))->where('type','!=','contact')->get();
        }

        $data['print'] = $request->print;

        return view('reservation.my_reservations', $data);
    }

    /**
     * Load Reservation Itinerary Print Page
     *
     * @param array $request Input values
     * @return view Itinerary file
     */
    public function print_confirmation(Request $request)
    {
        $data['reservation_details'] = $reservation = Reservation::with('rooms','users')->where('code',$request->code)->first();

        $data['additional_title'] = $request->code;

        if($data['reservation_details'])
        {
            if($data['reservation_details']->host_id == Auth::user()->id){
                $data['penalty'] = optional($data['reservation_details']->payouts)->total_penalty_amount;
                return view('reservation.print_confirmation', $data);
            }
            else if($data['reservation_details']->user_id == Auth::user()->id){
               $ngoArr = array();
                if($reservation->ngo_amount > 0){
                    $ngo_list = json_decode($reservation->ngo_list);
                    foreach ($ngo_list as $key => $value) {
                        $ngo_detail = Ngofee::where('id',$value->id)->first();
                        if(!empty($ngo_detail)){
                            $currency = Currency::find($ngo_detail->currency)->code;
                            $amount = $this->payment_helper->currency_convert($currency,'',$ngo_detail->amount);
                            $Arr = array('id'=>$ngo_detail->id,'name'=>$ngo_detail->name,'amount'=>$amount);
                            array_push($ngoArr,$Arr);
                        }
                       
                    }
                  $data['reservation_details']->ngo_list = json_encode($ngoArr); 
                }
                return view('trips.itinerary', $data);
            }else{
                abort('404');
            }
        }
        else
            abort('404');
    }

    /**
     * Load Reservation Requested Page for After Payment
     *
     * @param array $request Input values
     * @return view Reservation Requested file
     */
    public function requested(Request $request)
    {
        $reservation_detail=Reservation::where('code', $request->code);
        if($reservation_detail->get()->count())
        {
            $data['reservation_details'] = $reservation_detail->first();
            return view('reservation.requested', $data);
        }
        else
        {
            abort('404');
        }
    }

    /**
     * Store Itinerary Friends
     *
     * @param array $request Input values 
     * @return redirect to Trips page
     */
    public function itinerary_friends(Request $request, EmailController $email_controller)
    {
        $friends_email = '';

        for($i=0; $i<count($request->friend_address); $i++)
        {
            if($request->friend_address[$i] != '')
            {
                $friends_email .= trim($request->friend_address[$i]).',';
            }
        }

        $reservation = Reservation::where('code',$request->code)->update(['friends_email'=>rtrim($friends_email,',')]);

        $reservation_details = Reservation::whereCode($request->code)->first();

        if($reservation_details->status == 'Accepted') {
            $friends_email = explode(',', $reservation_details->friends_email);
            if(count($friends_email) > 0) {
                foreach($friends_email as $email) {
                    if($email != '') {
                        $email_controller->itinerary($reservation_details->code, $email);
                    }
                }
            }
        }
        
        return redirect('trips/current'); 
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
    
    /**
     * Reservation Cancel by Host
     *
     * @param array $request Input values
     * @return redirect to My Reservations page
     */
    public function host_cancel_reservation(Request $request,EmailController $email_controller)
    {
        $reservation_details = Reservation::find($request->id);

        // Status check start
        if($reservation_details->status=='Cancelled')
            return redirect('my_reservations');
        // Status check end

        if($reservation_details->list_type == 'Experiences')
        {
            $this->host_cancel_experience_reservation($reservation_details);

            $cancel = Reservation::find($request->id);
            $cancel->cancelled_by = "Host";
            $cancel->cancelled_reason = $request->cancel_reason;
            $cancel->cancelled_at = date('Y-m-d H:m:s');
            $cancel->status = "Cancelled";
            $cancel->updated_at = date('Y-m-d H:m:s');
            $cancel->save();

            $messages = new Messages;
            $messages->room_id        = $reservation_details->room_id;
            $messages->list_type      = 'Experiences';
            $messages->reservation_id = $reservation_details->id;
            $messages->user_to        = $reservation_details->user_id;
            $messages->user_from      = Auth::user()->id;
            $messages->message        = $this->helper->phone_email_remove($request->cancel_message);
            $messages->message_type   = 11;
            $messages->save();

            $email_controller->experience_booking_cancelled($reservation_details->id);

            $this->helper->flash_message('success', trans('messages.your_reservations.cancelled_successfully'));
            return redirect('my_reservations');
        }

        // Host Penalty Details from admin panel
        $host_fee_percentage        = Fees::find(2)->value;
        $host_penalty               = Fees::find(3)->value;
        $penalty_currency           = Fees::find(4)->value;
        $penalty_before_days        = Fees::find(5)->value;
        $penalty_after_days         = Fees::find(6)->value;
        $penalty_cancel_limits_count= Fees::find(7)->value;
        $host_payout_amount         = 0;
        $guest_refundable_amount    = 0;
        $host_penalty_amount        = 0;

        $cancel_count               = Reservation::where('host_id', Auth::user()->id)->where('cancelled_by', 'Host')->where('cancelled_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 6 MONTH)'))->get()->count();
        
        // get the days difference between the checkin and the cancellation date
        // if host cancels the reservation on same checkin date, then that should be counted as host cancelling after guest checkin as well
        $datetime1 = new DateTime(date('Y-m-d H:i:s')); 
        $datetime2 = new DateTime(date('Y-m-d H:i:s', strtotime($reservation_details->checkin)));
        $interval_diff = $datetime1->diff($datetime2);
        //$interval = $interval_diff->days;
        $interval = ceil(((($interval_diff->d * 24 + $interval_diff->h) * 60 + $interval_diff->i)*60 + $interval_diff->s)/(24 * 60 * 60));

        $per_night_price   = $reservation_details->per_night;
        $total_nights      = $reservation_details->nights;

        // Additional guest price is added to the per night price for calculation
        $additional_guest_per_night     = ($reservation_details->additional_guest / $total_nights);
        $per_night_price                = $per_night_price+$additional_guest_per_night;

        $total_night_price = $per_night_price * $total_nights;
        if($interval_diff->invert && $reservation_details->status == 'Accepted') // To check the check in is less than today date
        {
            $spend_night_price = $per_night_price * ($interval <= $total_nights ? $interval : $total_nights);
            $remain_night_price= $per_night_price * (($total_nights - $interval) > 0 ? ($total_nights - $interval) : 0);
        }
        else
        {
            $spend_night_price = 0;
            $remain_night_price= $total_night_price;
        }
        
        $additional_guest_price     = /*$reservation_details->additional_guest*/0;
        $cleaning_fees              = $reservation_details->cleaning;
        $security_deposit           = /*$reservation_details->security*/0;
        $coupon_amount              = $reservation_details->coupon_amount;
        $service_fee                = $reservation_details->service;
        $airportfee                  = $reservation_details->airportfee;
        $host_payout_ratio          = (1 - ($host_fee_percentage / 100));

        if(!$interval_diff->invert) // Cancel before checkin
        {
            $refund_night_price = $total_night_price;
            $guest_refundable_amount = array_sum([
                $refund_night_price,
                $additional_guest_price,
                $cleaning_fees,
                $security_deposit,$airportfee,
                -$coupon_amount,
                $service_fee
            ]);

            $payout_night_price = 0;
            $host_payout_amount = array_sum([
                $payout_night_price,
            ]);

            if($cancel_count >= $penalty_cancel_limits_count && $host_penalty == 1)
            { 
                if($interval > 7)
                {
                    $host_penalty_amount= $this->payment_helper->currency_convert($penalty_currency,$reservation_details->currency_code,$penalty_before_days);
                }
                else
                {
                    $host_penalty_amount= $this->payment_helper->currency_convert($penalty_currency,$reservation_details->currency_code,$penalty_after_days);
                }
            }
        }
        else // Cancel after checkin
        {
            $refund_night_price = $remain_night_price;
            $guest_refundable_amount = array_sum([
                $refund_night_price,
                $security_deposit,
                -$coupon_amount,
            ]);

            $payout_night_price = $spend_night_price;
            $host_payout_amount = array_sum([
                $payout_night_price,
                $additional_guest_price,
                $cleaning_fees,
            ]);

            if($cancel_count >= $penalty_cancel_limits_count && $host_penalty == 1)
            { 
                $host_penalty_amount= $this->payment_helper->currency_convert($penalty_currency,$reservation_details->currency_code,$penalty_after_days);
            }
        }
        
        $host_fee           = ($host_payout_amount * ($host_fee_percentage / 100));
        $host_payout_amount = $host_payout_amount * $host_payout_ratio;
        
        if($reservation_details->status != 'Accepted')
        {
            $guest_refundable_amount = 0;
            $host_payout_amount = 0;
        }

        $this->payment_helper->payout_refund_processing($reservation_details, $guest_refundable_amount, $host_payout_amount, $host_penalty_amount);
        
        if(!$interval_diff->invert) // Revert travel credit if cancel before checkin
        {
            $this->payment_helper->revert_travel_credit($reservation_details->id);
        }

        // Update Calendar, delete stayed date
        if($reservation_details->status == 'Accepted'){
            $days = $this->get_days($reservation_details->checkin, $reservation_details->checkout);
            for($j=0; $j<count($days)-1; $j++)
            {
                $calendar_detail=Calendar::where('room_id',$reservation_details->room_id)->where('date', $days[$j]);
                if($calendar_detail->get()->count())
                {
                    $calendar_row = $calendar_detail->first();
                    $calendar_price=$calendar_row->price;
                    $calendar_row->spots_booked = $calendar_row->spots_booked - $reservation_details->number_of_guests;
                    $calendar_row->save();
                    if($calendar_row->spots_booked <= 0)
                    {
                        if($calendar_price!="0")
                        {
                            $calendar_row->status = 'Available';
                            $calendar_row->save();
                        }
                        else
                        {
                            $calendar_row->delete();
                        }
                    }
                }
            }
        }
        $messages = new Messages;
        $messages->room_id        = $reservation_details->room_id;
        $messages->reservation_id = $reservation_details->id;
        $messages->user_to        = $reservation_details->user_id;
        $messages->user_from      = Auth::user()->id;
        $messages->message        = $this->helper->phone_email_remove($request->cancel_message);
        $messages->message_type   = 11;
        $messages->save();

        $cancel = Reservation::find($request->id);
        $cancel->host_fee = $this->payment_helper->currency_convert($reservation_details->currency_code,$reservation_details->original_currency_code,$host_fee);
        $cancel->cancelled_by = "Host";
        $cancel->cancelled_reason = $request->cancel_reason;
        $cancel->cancelled_at = date('Y-m-d H:m:s');
        $cancel->status = "Cancelled";
        $cancel->updated_at = date('Y-m-d H:m:s');
        $cancel->save();

        $email_controller->cancel_host($cancel->id);

        $this->helper->flash_message('success', trans('messages.your_reservations.cancelled_successfully'));
        return redirect('my_reservations');
    }

    /**
     * Host Experience Reservation cancel by Host
     *
     * @param App\Models\Reservation $reservation_details
     */
    public function host_cancel_experience_reservation($reservation_details)
    {
        $guest_refundable_amount = $reservation_details->total;
        $host_payout_amount = 0;

        $guest_details = $reservation_details->guest_details;
        $spots = $guest_details->pluck('spot')->toArray();

        HostExperiencePaymentController::payout_refund_processing($reservation_details, $guest_refundable_amount, $host_payout_amount, $spots);

        $calendar = HostExperienceCalendar::where('host_experience_id', $reservation_details->room_id)->where('date', $reservation_details->checkin)->first();
        $calendar_spots = $calendar->spots_array;

        $updated_calendar_spots = array_diff($calendar_spots, $spots);
        $updated_calendar_spots = array_filter($updated_calendar_spots);
        asort($updated_calendar_spots);

        $calendar->spots = implode(',', $updated_calendar_spots);
        $calendar->spots_booked = count($updated_calendar_spots);
        $calendar->save();

        if($calendar->spots_booked == 0)
        {
            $calendar->delete();
        }
    }
}
