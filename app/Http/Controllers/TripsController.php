<?php

/**
 * Trips Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Trips
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
use App\Models\Ngofee;


use Auth;
use DB;
use App\Http\Start\Helpers;
use App\Http\Helper\PaymentHelper;
use DateTime;

class TripsController extends Controller
{
    /**
     * Load Current Trips page.
     *
     * @return view Current Trips File
     */
    protected $helper; // Global variable for Helpers instance
    
    protected $payment_helper; // Global variable for PaymentHelper instance

    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper = new Helpers;
    }

    public function current()
    {
        $data['pending_trips'] = Reservation::with('users','rooms')->where('type','!=','contact')
                ->where(function($query){
                    $query->where('status','Pending')->orwhere('status','Pre-Accepted'); 
                })
                ->orderBy('id','desc')
                ->where('checkin', '>=', date('Y-m-d'))
                ->where('user_id',Auth::user()->id)->get();

        $data['current_trips'] = Reservation::with('users','rooms')->where(function($query){
            $query->where(function($query) {
                $query->where('checkin','>=',date('Y-m-d'))->where('checkout','<=',date('Y-m-d'));
            })->orWhere(function($query) {
                $query->where('checkin','<=',date('Y-m-d'))->where('checkout','>=',date('Y-m-d'));
            });
        })->where('status','!=','Pending')->where('status','!=','Pre-Accepted')->where('status','!=','Pre-Approved')->where('type','!=','contact')->where('user_id',Auth::user()->id)->get();

        $data['upcoming_trips'] = Reservation::with('users','rooms')->where('checkin','>',date('Y-m-d'))->where('type','!=','contact')->where('status','!=','Pre-Accepted')->where('status','!=','')->where('status','!=','Pending')->where('status','!=','Pre-Approved')->where('user_id',Auth::user()->id)->orderBy('id','desc')->get();

        return view('trips.current', $data);
    }

    /**
     * Load Previous Trips page.
     *
     * @return view Previous Trips File
     */
    public function previous()
    {
        $data['previous_trips'] = Reservation::with('users','rooms')->where('checkout','<',date('Y-m-d'))->where('user_id',Auth::user()->id)->where('type','!=','contact')->get();

        return view('trips.previous', $data);
    }

    /**
     * Load Reservation Receipt file.
     *
     * @return view Receipt
     */
    public function receipt(Request $request)
    {
        $data['reservation_details'] = $reservation = Reservation::where('code',$request->code)->first();
        if(!$data['reservation_details']){
            abort('404');
        }
        if($data['reservation_details']->user_id != Auth::user()->id)
            abort('404');
        
        $data['additional_title'] = $request->code;
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
        return view('trips.receipt', $data);
    }
    public function get_status(Request $request)
    {
        $id = $request->id;
        $room_id= $request->room_id;
        $checkin= $request->checkin;
        $checkout=$request->checkout;
        $date_from = strtotime($checkin);
        $date_to = strtotime($checkout); 
        $date_ar=array();
        for ($i=$date_from; $i<=$date_to - 1; $i+=86400) {  
            $date_ar[]= date("Y-m-d", $i).'<br />';  
        }  
        $check=array();
        for ($i=0; $i < count($date_ar) ; $i++) { 
            $check[]=DB::table('calendar')->where([ 'room_id' => $room_id, 'date' => $date_ar[$i], 'status' => 'Not available' ])->get();
        }
        if(count(array_filter($check)) == 0 ) 
        {
            echo "Pre-Accepted";
            exit;
        }
        else
        {
            $this->status_update($room_id,$checkin,$checkout);
            echo "Already Booked";
            exit;
        }
    }

    public function status_update($room_id, $checkin, $checkout)
    {
        $chck_reservation=Reservation::where(['room_id'=>$room_id,'checkin'=>$checkin, 'checkout'=>$checkout])->where('status','!=','Accepted')->get();
        $count_reservation=count($chck_reservation);
        if($count_reservation > 0)
        {
            foreach ($chck_reservation as $result) 
            {
                // Reservation::where('id',$result->id)->update(['date_check' => 'No']);
            }
        }
    }

    /**
     * Reservation Cancel by Guest
     *
     * @param array $request Input values
     * @return redirect to Current Trips page
     */
    public function guest_cancel_pending_reservation(Request $request,EmailController $email_controller){

        $reservation_details = Reservation::find($request->id);

        if($reservation_details->status=='Cancelled' || $reservation_details->status=='Declined' || $reservation_details->status=='Expired')
            return redirect('trips/current');

        $messages = new Messages;
        $messages->room_id        = $reservation_details->room_id;
        $messages->reservation_id = $reservation_details->id;
        $messages->user_to        = $reservation_details->host_id;
        $messages->user_from      = Auth::user()->id;
        $messages->message        = $this->helper->phone_email_remove($request->cancel_message);
        $messages->message_type   = 10;

        $messages->save();


        $cancel = Reservation::find($request->id);

        $cancel->cancelled_by = "Guest";
        $cancel->cancelled_reason = $request->cancel_reason;
        $cancel->cancelled_at = date('Y-m-d H:m:s');
        $cancel->status = "Cancelled";
        $cancel->updated_at = date('Y-m-d H:m:s');

        $cancel->save();

        $email_controller->cancel_guest($cancel->id);
        
        $this->helper->flash_message('success', trans('messages.your_reservations.cancelled_successfully'));
        return redirect('trips/current');
    }

    /**
     * Get Days Between two date time
     *
     * @param date sstartDate
     * @param date endDate
     * @return array aDays
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
     * Reservation Cancel by Guest
     *
     * @param array $request Input values
     * @return redirect to Current Trips page
     */
    public function guest_cancel_reservation(Request $request,EmailController $email_controller)
    {
        $reservation_details = Reservation::find($request->id);
        
        if($reservation_details->status=='Cancelled')
            return redirect('trips/current');

        $rooms_details = Rooms::find($reservation_details->room_id);

        if($reservation_details->status != 'Accepted')
        {
            return redirect('trips/current');
        }
        if($reservation_details->list_type == 'Experiences')
        {
            $this->guest_cancel_experience_reservation($reservation_details);

            $reservation_details->cancelled_by = "Guest";
            $reservation_details->cancelled_reason = $request->cancel_reason;
            $reservation_details->cancelled_at = date('Y-m-d H:m:s');
            $reservation_details->status = "Cancelled";
            $reservation_details->updated_at = date('Y-m-d H:m:s');
            $reservation_details->save();

            $messages = new Messages;
            $messages->room_id        = $reservation_details->room_id;
            $messages->list_type      = 'Experiences';
            $messages->reservation_id = $reservation_details->id;
            $messages->user_to        = $reservation_details->host_id;
            $messages->user_from      = Auth::user()->id;
            $messages->message        = $this->helper->phone_email_remove($request->cancel_message);
            $messages->message_type   = 10;
            $messages->save();

            $email_controller->experience_booking_cancelled($reservation_details->id);

            $this->helper->flash_message('success', trans('messages.your_reservations.cancelled_successfully'));
            return redirect('trips/current');
        }
        $host_fee_percentage        = Fees::find(2)->value > 0 ? Fees::find(2)->value : 0;
        $host_payout_amount = $reservation_details->subtotal;
        $guest_refundable_amount = 0;

        $datetime1 = new DateTime(date('Y-m-d')); 
        $datetime2 = new DateTime(date('Y-m-d', strtotime($reservation_details->checkin)));
        $interval_diff = $datetime1->diff($datetime2);
        $interval = $interval_diff->days;

        $per_night_price   = $reservation_details->per_night;
        $total_nights      = $reservation_details->nights;

        // Additional guest price is added to the per night price for calculation
        $additional_guest_per_night     = ($reservation_details->additional_guest / $total_nights);
        $per_night_price                = $per_night_price+$additional_guest_per_night;

        $total_night_price = $per_night_price * $total_nights;
        if($interval_diff->invert) // To check the check in is less than today date
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
        $airportfee                = $reservation_details->airportfee;
        $host_payout_ratio          = (1 - ($host_fee_percentage / 100));

        if($reservation_details->cancellation == "Flexible")
        {
            if($interval_diff->invert) // To check the check in is less than today date
            {
                if($interval > 0) //  (interval < 0) condition
                {
                    $refund_night_price = $remain_night_price;
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $security_deposit,
                        -$coupon_amount
                    ]);

                    $payout_night_price = $spend_night_price;
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                        $additional_guest_price,
                        $cleaning_fees,
                    ]);
                }
            }
            else
            {
                if($interval == 0) //  (interval = 0) condition
                {
                    $refund_night_price = $total_night_price-$per_night_price;
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $additional_guest_price,
                        $security_deposit,
                        -$coupon_amount
                    ]);

                    $payout_night_price = $per_night_price;
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                        $cleaning_fees,
                    ]);
                }
                else if($interval > 0) //  (interval > 0) condition
                {
                    $refund_night_price = $total_night_price;
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $additional_guest_price,
                        $cleaning_fees,
                        $security_deposit,$airportfee,
                        -$coupon_amount
                    ]);

                    $payout_night_price = 0;
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                    ]);
                }
            }
        }

        else if($reservation_details->cancellation == "Moderate")
        {
            if($interval_diff->invert) // To check the check in is less than today date
            {
                if($interval > 0) //  (interval < 0) condition
                {
                    $refund_night_price = $remain_night_price * (50 / 100); // 50 % of remain night price
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $security_deposit,
                        -$coupon_amount
                    ]);

                    $payout_night_price = $spend_night_price + ($remain_night_price * (50 / 100)); // spend night price and 50% remain night price
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                        $additional_guest_price,
                        $cleaning_fees,
                    ]);
                }
            }
            else
            {
                if($interval < 5 && $interval > 0) //  (interval < 5 && interval >= 0) condition
                {
                    $refund_night_price = ($total_night_price-$per_night_price) * (50 /100); // 50% of other than first night price
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $additional_guest_price,
                        $cleaning_fees,
                        $security_deposit,
                        -$coupon_amount
                    ]);

                    $payout_night_price = $per_night_price + (($total_night_price-$per_night_price) * (50 /100)); // First night price and 50% other night price
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                    ]);
                }
                else if($interval == 0) //  (interval < 5 && interval >= 0) condition
                {
                    $refund_night_price = ($total_night_price-$per_night_price) * (50 /100); // 50% of other than first night price
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $additional_guest_price,
                        $security_deposit,
                        -$coupon_amount
                    ]);

                    $payout_night_price = $per_night_price + (($total_night_price-$per_night_price) * (50 /100)); // First night price and 50% other night price
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                        $cleaning_fees,
                    ]);
                }
                else if($interval >= 5) //  (interval >= 5) condition
                {
                    $refund_night_price = $total_night_price;
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $additional_guest_price,
                        $cleaning_fees,
                        $security_deposit,$airportfee,
                        -$coupon_amount
                    ]);

                    $payout_night_price = 0;
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                    ]);
                }
            }
        }

        else if($reservation_details->cancellation == "Strict")
        {
            if($interval_diff->invert) // To check the check in is less than today date
            {
                if($interval > 0) //  (interval < 0) condition
                {
                    $refund_night_price = 0; // Total night price is non refundable
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $security_deposit,
                        -$coupon_amount
                    ]);

                    $payout_night_price = $total_night_price; // Total night price is payout
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                        $additional_guest_price,
                        $cleaning_fees,
                    ]);
                }
            }
            else
            {
                if($interval < 7 && $interval > 0) //  (interval < 7 && interval >= 0) condition
                {
                    $refund_night_price = 0; // Total night price is non refundable
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $additional_guest_price,
                        $cleaning_fees,
                        $security_deposit,
                        -$coupon_amount
                    ]);

                    $payout_night_price = $total_night_price; // Total night price is payout
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                    ]);
                }
                if($interval == 0) //  (interval < 7 && interval >= 0) condition
                {
                    $refund_night_price = 0; // Total night price is non refundable
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $additional_guest_price,
                        $security_deposit,
                        -$coupon_amount
                    ]);

                    $payout_night_price = $total_night_price; // Total night price is payout
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                        $cleaning_fees,
                    ]);
                }
                else if($interval >= 7) //  (interval >= 7) condition
                {
                    $refund_night_price = $total_night_price * (50/100); // 50% of total night price;
                    $guest_refundable_amount = array_sum([
                        $refund_night_price,
                        $additional_guest_price,
                        $cleaning_fees,
                        $security_deposit,$airportfee,
                        -$coupon_amount
                    ]);

                    $payout_night_price = $total_night_price * (50/100); // 50% of total night price;
                    $host_payout_amount = array_sum([
                        $payout_night_price,
                    ]);
                }
            }
        }

        $host_fee           = ($host_payout_amount * ($host_fee_percentage / 100));
        $host_payout_amount = $host_payout_amount * $host_payout_ratio;

        $this->payment_helper->payout_refund_processing($reservation_details, $guest_refundable_amount, $host_payout_amount);

        // Revert travel credit if cancel before checkin
        if(!$interval_diff->invert)
        {
            $this->payment_helper->revert_travel_credit($reservation_details->id);
        }

        // Update Calendar, delete stayed date
        // $cancelled_date = date('Y-m-d H:m:s');
        $days = $this->get_days($reservation_details->checkin ,$reservation_details->checkout);
        
        for($j=0; $j<count($days)-1; $j++)
        {
            $calendar_detail=Calendar::where('room_id',$reservation_details->room_id)->where('date', $days[$j]);
            if($calendar_detail->get()->count())
            {
                // $calendar_price=$calendar_detail->get()->first()->price;
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

        // Send message for cancellation
        $messages = new Messages;
        $messages->room_id        = $reservation_details->room_id;
        $messages->reservation_id = $reservation_details->id;
        $messages->user_to        = $reservation_details->host_id;
        $messages->user_from      = Auth::user()->id;
        $messages->message        = $this->helper->phone_email_remove($request->cancel_message);
        $messages->message_type   = 10;
        $messages->save();
        
        // Update reservation status and other details
        $cancel = Reservation::find($request->id);
        $cancel->host_fee = $host_fee;
        $cancel->cancelled_by = "Guest";
        $cancel->cancelled_reason = $request->cancel_reason;
        $cancel->cancelled_at = date('Y-m-d H:m:s');
        $cancel->status = "Cancelled";
        $cancel->updated_at = date('Y-m-d H:m:s');
        $cancel->save();
        
        // Send mail to host
        $email_controller->cancel_guest($cancel->id);

        $this->helper->flash_message('success', trans('messages.your_reservations.cancelled_successfully'));
        return redirect('trips/current');
    }


    /**
     * Host Experience Reservation cancel by Guest
     *
     * @param App\Models\Reservation $reservation_details
     */
    public function guest_cancel_experience_reservation($reservation_details)
    {
        $today_date_time = new DateTime(); 
        $start_date_time = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($reservation_details->checkin))); 
        $created_date_time = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($reservation_details->created_at))); 
        $interval_to_start = $today_date_time->diff($start_date_time);
        $interval_from_created = $created_date_time->diff($today_date_time);
        $interval_start = $interval_to_start->days;
        $interval_created = $interval_from_created->days;
        
        $host_payout_amount = $reservation_details->subtotal;
        $guest_refundable_amount = 0;
        $pending_guest_refund = 'No';

        $guest_details = $reservation_details->guest_details;
        $spots = $guest_details->pluck('spot')->toArray();

        if($today_date_time < $start_date_time)
        {
            if($interval_created <= 1)
            {
                $guest_refundable_amount = $reservation_details->total;
                $host_payout_amount = 0;
            }
            else if($interval_start >= 30)
            {
                $guest_refundable_amount = $reservation_details->total;
                $host_payout_amount = 0;
            }
            else if($interval_start < 30){
                $guest_refundable_amount = 0;
                $pending_guest_refund = 'Yes';
            }
        }
        else
        {
            $guest_refundable_amount = 0;
        }
        HostExperiencePaymentController::payout_refund_processing($reservation_details, $guest_refundable_amount, $host_payout_amount, $spots);
            
        if($pending_guest_refund == 'Yes')
        {
            foreach($guest_details as $guest)
            {
                $guest->refund_status = 'Pending';
                $guest->save();
            }
        }

        $calendar = HostExperienceCalendar::where('host_experience_id', $reservation_details->room_id)->where('date', $reservation_details->checkin)->first();
        if($calendar)
        {
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

}
