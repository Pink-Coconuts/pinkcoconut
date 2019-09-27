<?php

/**
 * Reservation Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Reservation
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use App\Models\PayoutPreferences;
use App\Models\Payouts;
use App\Models\Reviews;
use App\Models\Rooms;
use App\Models\SiteSettings;
use Auth;
use DateTime;
use DB;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;
use Session;

class Reservation extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'reservation';

	protected $appends = ['created_at_timer', 'status_color', 'receipt_date', 'dates_subject', 'checkin_arrive', 'checkout_depart', 'guests', 'host_payout', 'guest_payout', 'admin_host_payout', 'admin_guest_payout', 'checkin_md', 'checkout_md', 'checkin_mdy', 'checkout_mdy', 'check_total', 'checkin_site_date_format', 'checkout_site_date_format', 'review_end_date', 'grand_total', 'room_category', 'avablity', 'checkinformatted', 'checkoutformatted', 'status_language', 'review_link', 'original_created_at', 'guests_text'];

	// Check reservation table user_id is equal to current logged in user id
	public static function check_user($id) {
		$host_id = Rooms::find($id)->user_id;
		if ($host_id == Auth::user()->id) {
			return 1;
		} else {
			return 0;
		}

	}

	// Join with rooms table
	public function rooms() {
		if (@$this->attributes['list_type'] == 'Experiences') {
			return $this->host_experiences();
		} else {
			return $this->belongsTo('App\Models\Rooms', 'room_id', 'id');
		}
	}

	public function host_experiences() {
		return $this->belongsTo('App\Models\HostExperiences', 'room_id', 'id');
	}

	public function guest_details() {
		return $this->hasMany('App\Models\ReservationGuestDetails', 'reservation_id', 'id');
	}

	public function getRoomsAttribute() {
		return $this->rooms()->first();
	}

	// Join with users table
	public function users() {
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	public function host_users() {
		return $this->belongsTo('App\Models\User', 'host_id', 'id');
	}

	// Join with currency table
	public function currency() {
		return $this->belongsTo('App\Models\Currency', 'currency_code', 'code');
	}
	// Join with currency table
	public function refund_currency() {
		return $this->belongsTo('App\Models\Currency', 'paypal_currency', 'code');
	}

	// Join with messages table
	public function messages() {
		return $this->belongsTo('App\Models\Messages', 'id', 'reservation_id');
	}

	// Join with special_offer table
	public function special_offer() {
		return $this->belongsTo('App\Models\SpecialOffer', 'id', 'reservation_id')->latest();
	}
	// Join with special_offer table
	public function special_offer_details() {
		return $this->belongsTo('App\Models\SpecialOffer', 'special_offer_id', 'id');
	}

	// Join with payouts table
	public function payouts() {
		return $this->belongsTo('App\Models\Payouts', 'id', 'reservation_id');
	}
	// Join with payouts table
	public function hostPayouts() {
		return $this->belongsTo('App\Models\Payouts', 'id', 'reservation_id')->where('user_type', 'host');
	}

	// Join with payouts table
	public function guestPayouts() {
		return $this->belongsTo('App\Models\Payouts', 'id', 'reservation_id')->where('user_type', 'guest');
	}

	// Join with host_penalty table
	public function host_penalty() {
		return $this->belongsTo('App\Models\HostPenalty', 'id', 'reservation_id');
	}

	// Join with reviews table
	public function reviews() {
		return $this->hasMany('App\Models\Reviews', 'reservation_id', 'id');
	}

	// Join with reviews table
	public function guest_reviews() {
		return $this->hasMany('App\Models\Reviews', 'reservation_id', 'id')->where('user_from', @Auth::user()->id);
	}

	// Join with payout preferences table
	public function host_payout_preferences() {
		return $this->belongsTo('App\Models\PayoutPreferences', 'host_id', 'user_id')->where('default', 'Yes');
	}

	// Join with payout preferences table
	public function guest_payout_preferences() {
		return $this->belongsTo('App\Models\PayoutPreferences', 'user_id', 'user_id')->where('default', 'Yes');
	}
	// Get Review Details using Review ID
	public function review_details($id) {
		return Reviews::find($id);
	}

	// Get Review User Details using User ID
	public function review_user($id) {
		if ($this->attributes['user_id'] == $id) {
			$user_id = $this->attributes['host_id'];
		} else {
			$user_id = $this->attributes['user_id'];
		}

		return @User::find($user_id);
	}

	// Get Review Remaining Days
	public function getReviewDaysAttribute() {
		$start_date = $this->attributes['checkout'];
		$end_date = date('Y-m-d', strtotime($this->attributes['checkout'] . ' +14 days'));

		$datetime1 = new DateTime(date('Y-m-d'));
		$datetime2 = new DateTime($end_date);
		$interval = $datetime1->diff($datetime2);
		$days = $interval->format('%R%a');
		return $days + 1;
	}

	// Get Review Remaining Days
	public function getReviewEndDateAttribute() {
		$start_date = $this->attributes['checkout'];
		$end_date = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkout'] . ' +14 days'));

		return $end_date;
	}

	// Get Host Payout Email ID
	public function getHostPayoutEmailIdAttribute() {
		$payout = PayoutPreferences::where('user_id', $this->attributes['host_id'])->where('default', 'yes')->get();
		return @$payout[0]->paypal_email;
	}

	public function getRoomCategoryAttribute() {
		$rooms = Rooms::where('id', $this->attributes['room_id'])->get();
		$property_type = RoomType::where('id', @$rooms[0]->room_type)->get();
		return @$property_type[0]->name;
	}

	// Get Guest Payout Email ID
	public function getGuestPayoutCurrencyAttribute() {
		$payout = PayoutPreferences::where('user_id', $this->attributes['user_id'])->where('default', 'yes')->get();
		return @$payout[0]->currency_code;
	}

	// Get Guest Payout Email ID
	public function getPaypalCurrencyAttribute() {
		if ($this->attributes['paypal_currency'] != null) {
			return @$this->attributes['paypal_currency'];
		} else {
			$payout = PayoutPreferences::where('user_id', $this->attributes['user_id'])->where('default', 'yes')->get();
			return @$payout[0]->currency_code;
		}
	}

	// Get Host Payout Email ID
	public function getHostPayoutCurrencyAttribute() {
		$payout = PayoutPreferences::where('user_id', $this->attributes['host_id'])->where('default', 'yes')->get();
		return @$payout[0]->currency_code;
	}

	// Get Guest Payout Email ID
	public function getGuestPayoutEmailIdAttribute() {
		$payout = PayoutPreferences::where('user_id', $this->attributes['user_id'])->where('default', 'yes')->get();
		return @$payout[0]->paypal_email;
	}

	// Get Host Payout ID
	public function getHostPayoutIdAttribute() {
		$payout = Payouts::where('user_id', $this->attributes['host_id'])->where('reservation_id', $this->attributes['id'])->get();
		return @$payout[0]->id;
	}

	// Get Guest Payout ID
	public function getGuestPayoutIdAttribute() {
		$payout = Payouts::where('user_id', $this->attributes['user_id'])->where('reservation_id', $this->attributes['id'])->get();
		return @$payout[0]->id;
	}

	// Get Host Payout Preference ID
	public function getHostPayoutPreferenceIdAttribute() {
		$payout = PayoutPreferences::where('user_id', $this->attributes['host_id'])->where('default', 'yes')->get();
		return @$payout[0]->id;
	}

	// Get Guest Payout Preference ID
	public function getGuestPayoutPreferenceIdAttribute() {
		$payout = PayoutPreferences::where('user_id', $this->attributes['user_id'])->where('default', 'yes')->get();
		return @$payout[0]->id;
	}

	// Check Host is eligible or not for amount transfer using Payouts table
	public function getCheckHostPayoutAttribute() {
		$check = Payouts::where('reservation_id', $this->attributes['id'])->where('user_type', 'host')->where('status', 'Completed')->get();

		if ($check->count()) {
			return 'yes';
		} else {
			return 'no';
		}

	}

	// Check Guest is eligible or not for amount transfer using Payouts table
	public function getCheckGuestPayoutAttribute() {
		$check = Payouts::where('reservation_id', $this->attributes['id'])->where('user_type', 'guest')->where('status', 'Completed')->get();

		if ($check->count()) {
			return 'yes';
		} else {
			return 'no';
		}

	}

	// Get Host Payout Amount
	public function getHostPayoutAttribute() {
		$check = Payouts::where('user_id', $this->attributes['host_id'])->where('reservation_id', $this->attributes['id'])->get();

		if ($check->count()) {
			return $check[0]->amount;
		} else {
			return $this->currency_calc('total') - $this->currency_calc('service') - $this->currency_calc('airportfee') - $this->currency_calc('ngo_amount') - $this->currency_calc('host_fee') + $this->currency_calc('coupon_amount');
		}

	}

	// Get Host/Guest Total and check with the service and coupon amount
	public function getCheckTotalAttribute() {
		$host_id = $this->attributes['host_id'];

		/*$route=@Route::getCurrentRoute();
			      if($route){
			        $api_url = @$route->getPath();
			      }else{
			        $api_url = '';
			      }
			      $url_array=explode('/',$api_url);
		*/
		if (request()->segment(1) == 'api') {
			$user = JWTAuth::parseToken()->authenticate();
			$user_id = $user->id;
		} else {
			$user_id = @Auth::user()->id;
		}

		if ($host_id == $user_id) {
			return $this->currency_calc('total') + $this->currency_calc('coupon_amount') - $this->currency_calc('service') - $this->currency_calc('host_fee')-@$this->hostPayouts->total_penalty_amount;
		} else {
			return $this->currency_calc('total');
		}

	}
	public function getGrandTotalAttribute() {
		$host_id = $this->attributes['host_id'];

		if ($host_id == @Auth::user()->id) {
			return $this->currency_calc('subtotal') + $this->currency_calc('coupon_amount') - $this->currency_calc('service');
		} else {
			return $this->currency_calc('subtotal');
		}

	}
	// Admin host /Guest payout
	public function getAdminHostPayoutAttribute() {
		$check = Payouts::where('user_id', $this->attributes['host_id'])->where('reservation_id', $this->attributes['id'])->get();

		if ($check->count()) {
			return $check[0]->amount;
		} else {
			return 0;
		}

	}

	public function getAdminGuestPayoutAttribute() {
		$check = Payouts::where('user_id', $this->attributes['user_id'])->where('reservation_id', $this->attributes['id'])->get();

		if ($check->count()) {
			return $check[0]->amount;
		} else {
			return 0;
		}

	}

	// Get Guest Payout Amount
	public function getGuestPayoutAttribute() {
		$check = Payouts::where('user_id', $this->attributes['user_id'])->where('reservation_id', $this->attributes['id'])->get();

		if ($check->count()) {
			return $check[0]->amount;
		} else {
			return $this->currency_calc('total');
		}

		//$this->attributes['total'];
	}

	// Get Receipt Date from created_at field
	public function getReceiptDateAttribute() {
		return date(PHP_DATE_FORMAT, strtotime($this->attributes['created_at']));
	}

	// Get Date for Email Subject
	public function getDatesSubjectAttribute() {
		if (@$this->attributes['list_type'] == 'Experiences') {
			return date(PHP_DATE_FORMAT, strtotime($this->attributes['checkin'])) . ', ' . $this->times;
		} else {
			return date(PHP_DATE_FORMAT, strtotime($this->attributes['checkin'])) . ' - ' . date(PHP_DATE_FORMAT, strtotime($this->attributes['checkout']));
		}

	}

	// Get checkin_date in dmy format
	public function getCheckinDateAttribute() {
		return date(PHP_DATE_FORMAT, strtotime($this->attributes['checkin']));
	}
	
	// Get checkout_date in dmy format
	public function getCheckoutDateAttribute() {
		return date(PHP_DATE_FORMAT, strtotime($this->attributes['checkout']));
	}


	// Get Checkin Date in dmy format
	public function getCheckinDmyAttribute() {
		$checkin = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkin']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkin .= ' ' . $this->start_time_hi;
		}

		return $checkin;
	}

	// Get Checkout Date in dmy format
	public function getCheckoutDmyAttribute() {
		$checkout = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkout']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkout .= ' ' . $this->end_time_hi;
		}

		return $checkout;
	}

	// Get Checkin Date in dmd format
	public function getCheckinDmdAttribute() {
		$checkin = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkin']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkin .= ' ' . $this->start_time_hi;
		}

		return $checkin;
	}

	// Get Checkout Date in dmy format
	public function getCheckoutDmdAttribute() {
		$checkout = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkout']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkout .= ' ' . $this->end_time_hi;
		}

		return $checkout;
	}

	// Get Checkin Date in datepicker format
	public function getCheckinDatepickerAttribute() {
		$checkin = date('d-m-Y', strtotime($this->attributes['checkin']));
		return $checkin;
	}

	// Get Checkout Date in datepicker format
	public function getCheckoutDatepickerAttribute() {
		$checkout = date('d-m-Y', strtotime($this->attributes['checkout']));
		return $checkout;
	}

	// Get Checkin Date in mdy format
	public function getCheckinMdyAttribute() {
		$checkin = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkin']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkin .= ' ' . $this->start_time_hi;
		}

		return $checkin;
	}

	// Get Checkout Date in mdy format
	public function getCheckoutMdyAttribute() {
		$checkout = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkout']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkout .= ' ' . $this->end_time_hi;
		}

		return $checkout;
	}

	// Get Checkin Date in dmy format
	public function getCheckinDmySlashAttribute() {
		$checkin = date('d/m/y', strtotime($this->attributes['checkin']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkin .= ' ' . $this->start_time_hi;
		}

		return $checkin;
	}

	// Get Checkout Date in dmy format
	public function getCheckoutDmySlashAttribute() {
		$checkout = date('d/m/y', strtotime($this->attributes['checkout']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkout .= ' ' . $this->end_time_hi;
		}

		return $checkout;
	}

	// Get Checkin Date Site Date format
	public function getCheckinSiteDateFormatAttribute() {
		$checkin = date(SITE_DATE_FORMAT, strtotime($this->attributes['checkin']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkin .= ' ' . $this->start_time_hi;
		}

		return $checkin;
	}

	// Get Checkout Date Site Date format
	public function getCheckoutSiteDateFormatAttribute() {
		$checkout = date(SITE_DATE_FORMAT, strtotime($this->attributes['checkout']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkout .= ' ' . $this->end_time_hi;
		}

		return $checkout;
	}

	// Get Checkin Date in md format
	public function getCheckinMdAttribute() {
		$checkin = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkin']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkin .= ' ' . $this->start_time_hi;
		}

		return $checkin;
	}

	// Get Checkin Arrive Date in md format
	public function getCheckinArriveAttribute() {
		$checkin = date('D, d F, Y', strtotime($this->attributes['checkin']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkin .= ' ' . $this->start_time_hi;
		}

		return $checkin;
	}

	// Get Checkout Depart Date in md format
	public function getCheckoutDepartAttribute() {
		$checkout = date('D, d F, Y', strtotime($this->attributes['checkout']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkout .= ' ' . $this->end_time_hi;
		}

		return $checkout;
	}

	// Get Checkout Date in md format
	public function getCheckoutMdAttribute() {
		$checkout = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkout']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkout .= ' ' . $this->end_time_hi;
		}

		return $checkout;
	}

	// Get Checkin and Checkout Dates
	public function getDatesAttribute() {
		if (@$this->attributes['list_type'] == 'Experiences') {
			return date(PHP_DATE_FORMAT, strtotime($this->attributes['checkin'])) . ', ' . $this->times;
		} else {
			return date(PHP_DATE_FORMAT, strtotime($this->attributes['checkin'])) . ' - ' . date(PHP_DATE_FORMAT, strtotime($this->attributes['checkout']));
		}

	}

	public function getStartTimeHiAttribute() {
		$start_time = date('H:i', strtotime($this->attributes['start_time']));
		return $start_time;
	}

	public function getEndTimeHiAttribute() {
		$end_time = date('H:i', strtotime($this->attributes['end_time']));
		return $end_time;
	}

	public function getTimesAttribute() {
		return $this->start_time_hi . ' - ' . $this->end_time_hi;
	}

	public function getDurationAttribute() {
		$duration = $this->attributes['nights'];

		if (@$this->attributes['list_type'] == 'Experiences') {
			$start_time = @$this->attributes['start_time'];
			$end_time = @$this->attributes['end_time'];

			$diff_time = strtotime($end_time) - strtotime($start_time);
			$duration = round(($diff_time / 3600), 1);
		}
		return $duration;
	}

	public function getDurationTextAttribute() {
		$duration = $this->duration;
		$duration_text = $duration . ' ' . trans_choice('messages.rooms.night', $duration);
		if (@$this->attributes['list_type'] == 'Experiences') {
			$duration_text = $duration . ' ' . trans_choice('experiences.manage.hour_s', $duration);
		}
		return $duration_text;
	}

	public function getDurationTypeTextAttribute() {
		$duration = 1;
		$duration_text = trans_choice('messages.rooms.night', $duration);
		if (@$this->attributes['list_type'] == 'Experiences') {
			$duration_text = trans_choice('experiences.manage.hour_s', $duration);
		}
		return $duration_text;
	}

	public function getGuestsTextAttribute() {
		$guests = $this->attributes['number_of_guests'];
		$guests_text = $guests . ' ' . trans_choice('messages.home.guest', $guests);
		return $guests_text;
	}

	public function getSubtotalMultiplyTextAttribute() {
		if (@$this->attributes['list_type'] == 'Experiences') {
			return $this->guests_text;
		} else {
			return $this->duration_text;
		}

	}

	// Get Created At Timer for Expired
	public function getCreatedAtTimerAttribute() {
		$expired_at = date('Y/m/d H:i:s', strtotime(str_replace('-', '/', $this->attributes['created_at']) . ' +1 day'));
		return $expired_at;
	}

	public function getPerNightAttribute() {
		return $this->currency_calc('per_night');
	}
	public function getSubtotalAttribute() {
		return $this->currency_calc('subtotal');
	}
	public function getCleaningAttribute() {
		return $this->currency_calc('cleaning');
	}
	public function getAdditionalGuestAttribute() {
		return $this->currency_calc('additional_guest');
	}
	public function getSecurityAttribute() {
		return $this->currency_calc('security');
	}
	public function getServiceAttribute() {
		return $this->currency_calc('service');
	}
	public function getAirportFeeAttribute() {
		return $this->currency_calc('airportfee');
	}
	public function getHostFeeAttribute() {
		return $this->currency_calc('host_fee');
	}
	public function getCouponAmountAttribute() {
		return $this->currency_calc('coupon_amount');
	}
	public function getTotalAttribute() {
		return $this->currency_calc('total');
	}
	public function getPayoutAttribute() {
		return $this->currency_calc('payout');
	}

	public function getBasePerNightAttribute() {
		return $this->currency_calc('base_per_night');
	}
	public function getLengthOfStayDiscountPriceAttribute() {
		return $this->currency_calc('length_of_stay_discount_price');
	}
	public function getBookedPeriodDiscountPriceAttribute() {
		return $this->currency_calc('booked_period_discount_price');
	}
	// Get value of Checkin crossed days
	public function getCheckinCrossAttribute() {
		$date1 = date_create($this->attributes['checkin']);
		$date2 = date_create(date('Y-m-d'));
		$diff = date_diff($date1, $date2);
		if ($date2 < $date1) {
			return 1;
		} else {
			return 0;
		}

		// return $diff->format("%a");
	}

	// Get value of Checkout crossed days
	public function getCheckoutCrossAttribute() {
		$date1 = date_create($this->attributes['checkout']);
		$date2 = date_create(date('Y-m-d'));

		if ($date2 > $date1) {
			return 1;
		} else {
			return 0;
		}

	}

	// Calculation for current currency conversion of given price field
	public function currency_calc($field) {

		/*$route=@Route::getCurrentRoute();
			      if($route){
			        $api_url = @$route->getPath();
			      }else{
			        $api_url = '';
			      }

			      $url_array=explode('/',$api_url);
			        //Api currency conversion
		*/
		if (request()->segment(1) == 'api') {
			$user_details = JWTAuth::parseToken()->authenticate();

			$rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;

			$usd_amount = $this->attributes[$field] / $rate;

			$api_currency = $user_details->currency_code;

			$default_currency = Currency::where('default_currency', 1)->first()->code;

			$session_rate = Currency::whereCode($user_details->currency_code != null ? $user_details->currency_code : $default_currency)->first()->rate;

			return round($usd_amount * $session_rate);

		} else {

			$rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;

			$usd_amount = @$this->attributes[$field] / @$rate;

			$default_currency = Currency::where('default_currency', 1)->first()->code;

			$session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->rate;

			return round($usd_amount * $session_rate);
		}

	}

	// Get default currency code if session is not set
	public function getCurrencyCodeAttribute() {
		if (request()->segment(1) == 'api') {
			$user_details = JWTAuth::parseToken()->authenticate();
			return $user_details->currency_code;
		}
		if (Session::get('currency')) {
			return Session::get('currency');
		} else {
			return DB::table('currency')->where('default_currency', 1)->first()->code;
		}

	}

	public function getOriginalCurrencyCodeAttribute() {
		return $this->attributes['currency_code'];
	}

	// Set Reservation Status Color
	public function getStatusColorAttribute() {
		if (@$this->attributes['type'] == 'contact') {
			return 'inquiry';
		} else if ($this->attributes['status'] == 'Accepted') {
			return 'success';
		} else if ($this->attributes['status'] == 'Expired') {
			return 'info';
		} else if ($this->attributes['status'] == 'Pending') {
			return 'warning';
		} else if ($this->attributes['status'] == 'Declined') {
			return 'info';
		} else if ($this->attributes['status'] == 'Cancelled') {
			return 'info';
		} else {
			return '';
		}

	}

	// Get Reservation Status
	public function getStatusAttribute() {

		if (@$this->attributes['status'] == null) {

                $date = date('Y-m-d', time());

           if (strtotime($this->checkin) < strtotime($date)) {

				$this->status = 'Expired';
				$this->save();

				return 'Expired';
	     	}
			return 'Inquiry';

		} else if ($this->attributes['status'] == 'Pre-Accepted' || $this->attributes['status'] == 'Pending' || $this->attributes['status'] == 'Pre-Approved') {

			$date = date('Y-m-d', time());

			if (strtotime($this->checkin) < strtotime($date)) {

				$this->status = 'Expired';
				$this->save();

				return 'Expired';
			}

		}

		return $this->attributes['status'];

	}

	// Get Guest Count with Plural
	public function getGuestsAttribute() {
		if ($this->attributes['number_of_guests'] > 1) {
			$plural = ($this->attributes['number_of_guests'] - 1 > 1) ? 's' : '';
			return '+' . ($this->attributes['number_of_guests'] - 1) . ' Guest' . $plural;
		}
	}

	public function calendar() {
		return $this->hasMany('App\Models\Calendar', 'room_id', 'room_id');
	}

	// Get This reservation date is avaablie
	public function getAvablityAttribute() {
		if (isset($this->attributes['date_check'])) {
			if (@$this->attributes['date_check'] == 'No') {
				return 1;
			} else {
				$calendar_not_available = $this->calendar()->where('date', '>=', $this->attributes['checkin'])->where('date', '<', $this->attributes['checkout'])->notAvailable($this->attributes['number_of_guests'])->get();
				if ($calendar_not_available->count() > 0) {
					@$this->attributes['date_check'] = 'No';
					@$this->save();
					return 1;
				} else {
					return 0;
				}
			}
		}
	}
	public function getBookedReservationAttribute() {
		$booked_room = Reservation::where('id', $this->attributes['id'])->where('status', 'Accepted')->count();
		if ($booked_room) {
			return false;
		} else {
			return true;
		}

	}

	public function getCheckinFormattedAttribute() {
		$checkin = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkin']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkin .= ' ' . $this->start_time_hi;
		}

		return $checkin;
	}

	public function getCheckoutFormattedAttribute() {
		$checkout = date(PHP_DATE_FORMAT, strtotime($this->attributes['checkout']));
		if (@$this->attributes['list_type'] == 'Experiences') {
			$checkout .= ' ' . $this->end_time_hi;
		}

		return $checkout;
	}

	public function getCreatedAtAttribute() {
		return date(PHP_DATE_FORMAT . ' H:i:s', strtotime($this->attributes['created_at']));
	}
	public function getCancelledAtAttribute() {
		return date(PHP_DATE_FORMAT . ' H:i:s', strtotime($this->attributes['cancelled_at']));
	}
	public function getOriginalCreatedAtAttribute() {
		return $this->attributes['created_at'];
	}

	public function getUpdatedAtAttribute() {
		return date(PHP_DATE_FORMAT . ' H:i:s', strtotime($this->attributes['updated_at']));
	}

	// status_language
	public function getStatusLanguageAttribute() {
		if (@$this->attributes['status'] == null) {
			return trans('messages.dashboard.Inquiry');
		} else {
			return trans('messages.dashboard.' . $this->attributes['status'] . '');
		}
	}

	public function getTitleClassAttribute() {
		if ($this->list_type == "Experiences") {
			return 'text-rausch';
		} else {
			return '';
		}
	}

	public function getReviewLinkAttribute() {
		$site_settings_url = @SiteSettings::where('name', 'site_url')->first()->value;
		$url = \App::runningInConsole() ? $site_settings_url : url('/');

		if ($this->list_type == "Experiences") {
			return $url . '/host_experience_reviews/edit/' . $this->id;
		} else {
			return $url . '/reviews/edit/' . $this->id;
		}
	}

	public function getDiscountsListAttribute() {
		$discounts_list = [];
		$length_of_stay_type = $this->attributes['length_of_stay_type'] ?: '';
		$booked_period_type = $this->attributes['booked_period_type'] ?: '';

		$k = 0;

		if ($booked_period_type != '') {
			$discounts_list[$k] = array();
			if ($booked_period_type == 'early_bird') {
				$type_text = trans('messages.rooms.early_bird_price_discount');
			} else if ($booked_period_type == 'last_min') {
				$type_text = trans('messages.rooms.last_min_price_discount');
			}

			$text = @$this->attributes['booked_period_discount'] . '% ' . $type_text;
			$discounts_list[$k]['text'] = $text;
			$discounts_list[$k]['price'] = @$this->getBookedPeriodDiscountPriceAttribute();
			$k++;
		}
		if ($length_of_stay_type != '') {
			$discounts_list[$k] = array();
			if ($length_of_stay_type == 'weekly') {
				$type_text = trans('messages.rooms.weekly_price_discount');
			} else if ($length_of_stay_type == 'monthly') {
				$type_text = trans('messages.rooms.monthly_price_discount');
			} else if ($length_of_stay_type == 'custom') {
				$type_text = trans('messages.rooms.long_term_price_discount');
			}

			$text = @$this->attributes['length_of_stay_discount'] . '% ' . $type_text;
			$discounts_list[$k]['text'] = $text;
			$discounts_list[$k]['price'] = @$this->getLengthOfStayDiscountPriceAttribute();
			$k++;
		}
		return $discounts_list;
	}

	/*
		  * Join Diputes Table
	*/
	public function dispute() {
		return $this->belongsTo('App\Models\Disputes', 'id', 'reservation_id');
	}

	public function scopeUserRelated($query, $user_id = null) {
		$user_id = $user_id ?: @Auth::user()->id;

		$query = $query->where(function ($query) use ($user_id) {
			$query->where('user_id', $user_id)->orWhere('host_id', $user_id);
		});

		return $query;
	}

	/*
		  * To get the Current User relation to this reservation
	*/
	public function getHostOrGuestAttribute() {
		$host_or_guest = 'Host';
		$current_user_id = @Auth::user()->id;
		if ($this->attributes['user_id'] == $current_user_id) {
			$host_or_guest = 'Guest';
		} elseif ($this->attributes['host_id'] == $current_user_id) {
			$host_or_guest = 'Host';
		}

		if (request()->segment(1) == ADMIN_URL) {
			$host_or_guest = '';
		}
		return $host_or_guest;
	}

	/*
		  * To get the Maximum amount host can apply dispute
	*/
	public function getMaximumHostDisputeAmountAttribute() {
		return $this->security;
	}

	/*
		  * To get the Maximum amount guest can apply dispute
	*/
	public function getMaximumGuestDisputeAmountAttribute() {
		$guest_payout = Payouts::where('user_id', $this->attributes['user_id'])->where('reservation_id', $this->attributes['id'])->first();

		$guest_payout_amount = $this->total - $this->service;
		if ($guest_payout) {
			$original_guest_payout_amount = $guest_payout->amount;
			$guest_payout_amount -= $guest_payout->currency_convert($guest_payout->currency_code, $this->currency_code, $original_guest_payout_amount);
		}

		return $guest_payout_amount;
	}

	/*
		  * To get the Maximum amount can current user can apply dispute
	*/
	public function getMaximumDisputeAmountAttribute() {
		$host_or_guest = $this->getHostOrGuestAttribute();
		if ($host_or_guest == 'Guest') {
			return $this->getMaximumGuestDisputeAmountAttribute();
		} else if ($host_or_guest == 'Host') {
			return $this->getMaximumHostDisputeAmountAttribute();
		} else {
			return $this->getMaximumGuestDisputeAmountAttribute() ? $this->getMaximumGuestDisputeAmountAttribute() : $this->getMaximumHostDisputeAmountAttribute();
		}
	}

	/*
		  * To get the last date to apply for the dispute
	*/
	public function getLastDateForDisputeAttribute() {
		$start_date = date('Y-m-d', strtotime(' -15 days'));

		if ($this->attributes['status'] == 'Cancelled' && ($this->attributes['checkin'] <= $this->attributes['cancelled_at'])) {
			$start_date = date('Y-m-d', strtotime($this->attributes['cancelled_at']));
		} else if ($this->attributes['status'] == 'Accepted') {
			if (date('Y-m-d') > $this->attributes['checkout']) {
				$start_date = $this->attributes['checkout'];
			}
		}

		$end_date = date('Y-m-d', strtotime($start_date . ' +14 days'));
		return $end_date;
	}

	/*
		  * To get the remaining days to apply for dispute
	*/
	public function getRemainingDaysForDisputeAttribute() {
		$remaining_days_for_dispute = 0;
		$last_date_for_dispute = $this->getLastDateForDisputeAttribute();

		$today_date = new DateTime(date('Y-m-d'));
		$last_date = new DateTime($last_date_for_dispute);
		$interval = $today_date->diff($last_date);

		$remaining_days_for_dispute = $interval->format('%R%a');

		return $remaining_days_for_dispute;
	}

	/*
		  * To check if the current user can apply for the dispute
	*/
	public function getCanApplyForDisputeAttribute() {
		$remaining_days_for_dispute = $this->getRemainingDaysForDisputeAttribute();
		$maximuim_dispute_amount = $this->getMaximumDisputeAmountAttribute();
		$already_applied_dispute = $this->dispute()->count();

		$can_apply_dipute = true;
		if (($remaining_days_for_dispute <= 0) || ($maximuim_dispute_amount <= 0) || ($already_applied_dispute > 0)) {
			$can_apply_dipute = false;
		}

		return $can_apply_dipute;
	}

	/**
	 * To check if the payout button can show
	 *
	 * @return bool $can_payout_button_show
	 **/
	function can_payout_button_show() {
		$date1 = date_create($this->attributes['checkout']);
		$date2 = date_create(date('Y-m-d'));

		$checkout_cross = 0;

		if ($date2 > $date1) {
			$checkout_cross = 1;
		}
		$open_dipsutes = Disputes::reservationBased($this->attributes['id'])
			->where(function ($query) {
				$query->where('status', 'Open')->orwhere('admin_status', 'Open');
			})->count();
		return (!$this->getCanApplyForDisputeAttribute() && !$open_dipsutes && ($checkout_cross || $this->attributes['status'] == 'Cancelled'));
	}

	public function getCreatedAtDateAttribute() {
		return date(PHP_DATE_FORMAT, strtotime($this->attributes['created_at']));
	}

	// Calculation for current currency conversion of given amount
	public function currency_convert($from = '', $to = '', $price) {
		if ($from == '') {
			if (Session::get('currency')) {
				$from = Session::get('currency');
			} else {
				$from = Currency::where('default_currency', 1)->first()->code;
			}

		}

		if ($to == '') {
			if (Session::get('currency')) {
				$to = Session::get('currency');
			} else {
				$to = Currency::where('default_currency', 1)->first()->code;
			}

		}

		$rate = Currency::whereCode($from)->first()->rate;

		$usd_amount = $price / $rate;

		$session_rate = Currency::whereCode($to)->first()->rate;

		return ceil($usd_amount * $session_rate);
	}

	/**
	 * To get reservations of Rooms for Mobile api request (temporary)  zz
	 * @param  Illuminate\Database\Eloquent\Builder $query
	 * @return Illuminate\Database\Eloquent\Builder $query
	 */
	public function scopeOnlyRoomsReservation($query) {
		return $query->where('list_type', 'Rooms');
	}

	// Get Formatted Payment Type Attribute
	public function getFormattedPaymodeAttribute()
	{
		if($this->attributes['total'] > 0) {
			$paymode = $this->attributes['paymode'];
		}
		else {
			$paymode = ($this->attributes['coupon_code'] == 'Travel_Credit')?trans('messages.referrals.travel_credit'):trans('messages.payments.coupon_code');
		}
		return $paymode;
	}
}
