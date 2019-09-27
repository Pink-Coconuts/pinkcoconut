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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Calendar;
use App\Models\Country;
use App\Models\CouponCode;
use App\Models\Currency;
use App\Models\Messages;
use App\Models\PaymentGateway;
use App\Models\PayoutPreferences;
use App\Models\Payouts;
use App\Models\ProfilePicture;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Models\SiteSettings;
use App\Models\SpecialOffer;
use App\Models\User;
use Auth;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use JWTAuth;
use Session;
use Validator;

class PaymentController extends Controller {
	protected $payment_helper; // Global variable for Helpers instance

	public function __construct(PaymentHelper $payment) {
		$this->payment_helper = $payment;
		$this->helper = new Helpers;
	}

/**
 * Pre Payment Calculation
 *
 * @param  Get method request inputs
 * @return Response in Json
 */
	public function pre_payment(Request $request) {
		$rules = array(

			'room_id' => 'required|exists:rooms,id',

			'start_date' => 'required|date_format:d-m-Y',

			'end_date' => ' required|date_format:d-m-Y|after:today|after:start_date',

			'total_guest' => 'required',

		);

		$niceNames = array(

			'room_id' => 'Room Id',

			'start_date' => 'Start Date',

			'end_date' => 'End Date',

			'total_guest' => 'Total Guest',

		);

		$messages = array('required' => ':attribute is required.');

		$validator = Validator::make($request->all(), $rules, $messages);

		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {

			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}

			return response()->json([

				'success_message' => $error_msg['0']['0']['0'],

				'status_code' => '0',

			]);
		} else {

			$rooms_total_guest = Rooms::where('id', $request->room_id)->first()->accommodates;
			//check guest count is valid or not
			if ($request->total_guest < 1 || $request->total_guest > $rooms_total_guest) {

				return response()->json([

					'success_message' => 'Maximum Total Guest Limit - ' . $rooms_total_guest,

					'status_code' => '0',

				]);
			}
			$user = JWTAuth::parseToken()->authenticate();

			$rooms_info = Rooms::where('id', $request->room_id)->first();

			// Prevent Host Own Booking.
			if (@$user->id == @$rooms_info->user_id) {
				return response()->json([

					'success_message' => 'You Can Not Book Your Own Listing',

					'status_code' => '0',

				]);
			}
			//remove session value
			Session::forget('coupon_code');
			Session::forget('coupon_code');
			Session::forget('coupon_amount');
			Session::forget('remove_coupon');
			Session::forget('manual_coupon');
			Session::forget('currency');
			//Session::put('currency',$user->currency_code);
			//check rooms date avialble or not
			$data = $this->payment_helper->price_calculation(
				$request->room_id,
				$request->start_date,
				$request->end_date,
				$request->total_guest,
				'',
				$request->change_reservation
			);

			$data = json_decode($data, TRUE);

			$status_value = @$data['status'];

			if ($status_value && ($status_value != 'Not available')) {

				$data['rooms'] = Rooms::find($request->room_id)->toArray();

				$result['currency'] = Currency::where('code', $user->currency_code)->first();

				$data['host'] = @User::with('profile_picture')

					->where('id', $data['rooms']['user_id'])->first();

				$data = array(

					'success_message' => 'Pre Payment Details Listed Successfully',

					'status_code' => '1',

					'room_name' => $data['rooms']['name'],

					'bedrooms' => $data['rooms']['bedrooms'],

					'bathrooms' => $data['rooms']['bathrooms'],

					'description' => $data['rooms']['summary'],

					'room_type' => $data['rooms']['room_type_name'],

					'host_user_name' => $data['host']['full_name'],

					'rooms_total_guest' => $rooms_total_guest,

					'host_user_thumb_image' => $data['host']->profile_picture->src,

					'start_date' => $request->start_date,

					'end_date' => $request->end_date,

					'total_price' => $data['total'],

					'currency_code' => $result['currency']['code'],

					'currency_symbol' => $result['currency']['original_symbol'],

					'policy_name' => $data['rooms']['cancel_policy'],

					'per_night_price' => $data['base_rooms_price'],

					'length_of_stay_type' => (string) $data['length_of_stay_type'],
					'length_of_stay_discount' => $data['length_of_stay_discount'],
					'length_of_stay_discount_price' => $data['length_of_stay_discount_price'],
					'booked_period_type' => (string) $data['booked_period_type'],
					'booked_period_discount' => $data['booked_period_discount'],
					'booked_period_discount_price' => $data['booked_period_discount_price'],

					'nights_count' => intval($data['total_nights']),

					'service_fee' => (string) ($data['service_fee']),

					'security_fee' => (string) $data['security_fee'],

					'cleaning_fee' => (string) ($data['cleaning_fee']),

					'additional_guest' => (string) ($data['additional_guest']),

				);

				return json_encode($data, JSON_UNESCAPED_SLASHES);
			} else {
				return response()->json([
					'success_message' => @$data['error'] ?: 'Rooms Not available ',

					'status_code' => '0',

				]);
			}

		}

	}
/**
 * Display Payment Method
 *
 * @param Get method request inputs
 * @return @return Response in Json
 */
	public function payment_methods(Request $request) {
		$data = array(

			'success_message' => 'Payment Methods',
			'status_code' => '1',
			'payment_method' => array('PayPal', 'Direct'),

		);

		return response()->json($data);
	}
/**
 * Coupon Code Apply
 *
 * @param Get method request inputs
 * @return @return Response in Json
 */
	public function apply_coupon(Request $request) {
		$coupon_details = CouponCode::where('coupon_code', $request->coupon_code)

			->where('status', 'Active')->get()->first();
		if (!empty($coupon_details)) {
			$currency = Currency::where('code', $coupon_details['currency_code'])

				->get()->first();

			if ($coupon_details->count()) {

				$datetime1 = new DateTime();

				$datetime2 = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($coupon_details->expired_at)));

				if ($datetime1 <= $datetime2) {

					$interval_diff = $datetime1->diff($datetime2);

					$interval = $interval_diff->days;

					@$data = array(

						'success_message' => 'Coupon Details',

						'status_code' => '1',

						'coupon_price' => $coupon_details->amount,

						'currency_code' => $coupon_details->currency_code,

						'currency_symbol' => $currency->symbol,

					);
					return response()->json($data);

				} else {

					return response()->json([
						'success_message' => 'The Given Coupon Code is Expired',

						'status_code' => '0',
					]);
				}

			}

		} else {

			return response()->json([
				'success_message' => 'The Given Coupon Code is Invalid',

				'status_code' => '0',

			]);
		}

	}
	/**
	 * Get days between two dates
	 *
	 * @param date $sStartDate  Start Date
	 * @param date $sEndDate    End Date
	 * @return array $days      Between two dates
	 */
	public function get_days($sStartDate, $sEndDate) {
		$sStartDate = $this->payment_helper->date_convert($sStartDate);
		$sEndDate = $this->payment_helper->date_convert($sEndDate);
		$aDays[] = $sStartDate;
		$sCurrentDate = $sStartDate;

		while ($sCurrentDate < $sEndDate) {
			$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
			$aDays[] = $sCurrentDate;
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
	public function getCode($length, $seed) {
		$code = "";

		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

		$codeAlphabet .= "0123456789";

		mt_srand($seed);

		for ($i = 0; $i < $length; $i++) {

			$code .= $codeAlphabet[mt_rand(0, strlen($codeAlphabet) - 1)];

		}

		return $code;
	}

	/**
	 * After Payment Process
	 *
	 * @param Get method request inputs
	 * @return @return Response in Json
	 */
	public function after_payment(Request $request) {
		$rules = array(

			'room_id' => 'required|exists:rooms,id',

			'checkin' => 'required|date_format:d-m-Y',

			'checkout' => ' required|date_format:d-m-Y|after:today|after:checkin',

			'paypal_success_message' => 'required',

			'paypal_status_code' => 'required',

			'paypal_transaction_id' => 'required',

			'number_of_guests' => 'required',

			'payment_country_code' => 'required|exists:country,short_name',

		);

		$niceNames = array(

			'room_id' => 'Room Id',

			'checkin' => 'Check In',

			'checkout' => 'Check Out',

			'paypal_success_message' => 'Paypal Success Message',

			'paypal_status_code' => 'Paypal Status Code',

			'number_of_guests' => 'Number Of Guests',

		);

		$messages = array('required' => ':attribute is required.');

		$validator = Validator::make($request->all(), $rules, $messages);

		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			foreach ($error as $er) {

				$error_msg[] = array($er);

			}

			return response()->json([

				'success_message' => $error_msg['0']['0']['0'],

				'status_code' => '0',

			]);
		} else {
			$rooms_total_guest = Rooms::where('id', $request->room_id)->pluck('accommodates');
			//check total guests validation
			if ($request->number_of_guests < 1 || $request->number_of_guests > $rooms_total_guest) {

				return response()->json([

					'success_message' => 'Not Valid Total Guest',

					'status_code' => '0',

				]);
			}
			$user = JWTAuth::parseToken()->authenticate();

			$rooms_info = Rooms::where('id', $request->room_id)->first();

			//Prevent form Host Booking
			if ($user->id == $rooms_info->user_id) {
				return response()->json([

					'success_message' => 'You Can Not Book Yours Own Listing',

					'status_code' => '0',

				]);
			}

			$result['ACK'] = $request->paypal_success_message;

			$result['CODE'] = $request->paypal_status_code;

			$room_id = $request->room_id;

			$checkin = $request->checkin;

			$checkout = $request->checkout;

			$transaction_id = $request->paypal_transaction_id;

			$number_of_guests = $request->number_of_guests;

			$special_offer_id = $request->special_offer_id;

			$reservation_id = $request->reservation_id;

			$message_to_host = 'success';

			$payment_country_code = strtoupper($request->payment_country_code);

			//Get Price List
			$data['price_list'] = json_decode($this->payment_helper->price_calculation($room_id, $checkin, $checkout, $number_of_guests, $special_offer_id));

			if (!isset($data['price_list']->total_nights)) {

				return response()->json([

					'success_message' => 'Rooms Date Already Booked',

					'status_code' => '0',

				]);
			} else {

				//set session value
				Session::put('payment_room_id', $room_id);

				Session::put('payment_checkin', date('d-m-Y', strtotime($checkin)));

				Session::put('payment_checkout', date('d-m-Y', strtotime($checkout)));

				Session::put('payment_number_of_guests', $number_of_guests);

				Session::put('payment_booking_type', 'instant_book');

				Session::put('payment_special_offer_id', $special_offer_id);

				Session::put('payment_reservation_id', $reservation_id);

				Session::put('payment_price_list', $data['price_list']);

				Session::put('message_to_host_', $message_to_host);

				Session::put('payment_country', $payment_country_code);

				Session::put('payment_reservation_id', $reservation_id);

				//Check Payment Status Success or Not
				if (@$result['ACK'] == 'payment_success' && @$result['CODE'] == '1') {
					$data = [

						'room_id' => Session::get('payment_room_id'),

						'checkin' => Session::get('payment_checkin'),

						'checkout' => Session::get('payment_checkout'),

						'number_of_guests' => Session::get('payment_number_of_guests'),

						'transaction_id' => $transaction_id,

						'price_list' => Session::get('payment_price_list'),

						'country' => Session::get('payment_country'),

						'message_to_host' => Session::get('message_to_host_'),

						'paymode' => 'PayPal',

					];

					$code = $this->store($data);

					return response()->json([

						'success_message' => 'Rooms Booked Successfully',

						'status_code' => '1',

					]);
				} else {

					return response()->json([

						'success_message' => 'Payment Failed',

						'status_code' => '0',

					]);
				}
			}

		}

	}
/**
 * Load Reservation After Payment
 *@param
 *
 * @return code
 */
	public function store($data) {
		if (Session::get('payment_reservation_id')) {
			$reservation = Reservation::find(Session::get('payment_reservation_id'));
		} else {
			$user = JWTAuth::parseToken()->authenticate();
		}

		$user = User::whereId($user->id)->first();
		// echo $user->id; exit;
		$reservation = new Reservation;

		$reservation->room_id = $data['room_id'];
		$reservation->host_id = Rooms::find($data['room_id'])->user_id;
		$reservation->user_id = $user->id;
		$reservation->checkin = date('Y-m-d', strtotime($data['checkin']));
		$reservation->checkout = date('Y-m-d', strtotime($data['checkout']));
		$reservation->number_of_guests = $data['number_of_guests'];
		$reservation->nights = $data['price_list']->total_nights;
		$reservation->per_night = $data['price_list']->per_night;
		$reservation->subtotal = $data['price_list']->subtotal;
		$reservation->cleaning = $data['price_list']->cleaning_fee;
		$reservation->additional_guest = $data['price_list']->additional_guest;
		$reservation->security = $data['price_list']->security_fee;
		$reservation->service = $data['price_list']->service_fee;
		$reservation->host_fee = $data['price_list']->host_fee;
		$reservation->total = $data['price_list']->total;
		$reservation->currency_code = $data['price_list']->currency;

		if ($data['price_list']->coupon_amount) {
			$reservation->coupon_code = $data['price_list']->coupon_code;
			$reservation->coupon_amount = $coupon_amount = $data['price_list']->coupon_amount;
		}

		$reservation->transaction_id = $data['transaction_id'];
		$reservation->paymode = $data['paymode'];
		$reservation->cancellation = Rooms::find($data['room_id'])->cancel_policy;
		$reservation->type = 'reservation';

		if ($data['paymode'] == 'Credit Card') {
			$reservation->first_name = $data['first_name'];
			$reservation->last_name = $data['last_name'];
			$reservation->postal_code = $data['postal_code'];
		}

		$reservation->country = $data['country'];
		$reservation->status = (Session::get('payment_booking_type') == 'instant_book') ? 'Accepted' : 'Pending';

		$reservation->save();

		if (@$data['price_list']->coupon_code == 'Travel_Credit') {
			$referral_friend = Referrals::whereFriendId($user->id)->get();
			foreach ($referral_friend as $row) {
				$friend_credit = $row->friend_credited_amount;
				if ($coupon_amount != 0) {
					if ($friend_credit <= $coupon_amount) {
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
					} else {
						$referral = Referrals::find($row->id);
						$referral->friend_credited_amount = $friend_credit - $coupon_amount;
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
			$referral_user = Referrals::whereUserId($user->id)->get();
			foreach ($referral_user as $row) {
				$user_credit = $row->credited_amount;
				if ($coupon_amount != 0) {
					if ($user_credit <= $coupon_amount) {
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
					} else {
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

		do {
			$code = $this->getCode(6, $reservation->id);
			$check_code = Reservation::where('code', $code)->get();
		} while (empty($check_code));

		$reservation_code = Reservation::find($reservation->id);

		$reservation_code->code = $code;

		$reservation_code->save();

		$days = $this->get_days($data['checkin'], $data['checkout']);

		// Update Calendar
		for ($j = 0; $j < count($days) - 1; $j++) {
			$calendar_data = [
				'room_id' => $data['room_id'],
				'date' => $days[$j],
				'status' => 'Not available',
			];

			Calendar::updateOrCreate(['room_id' => $data['room_id'], 'date' => $days[$j]], $calendar_data);
		}

		if ($reservation_code->status == 'Accepted') {
			$payouts = new Payouts;

			$payouts->reservation_id = $reservation_code->id;
			$payouts->room_id = $reservation_code->room_id;
			$payouts->user_id = $reservation_code->host_id;
			$payouts->user_type = 'host';
			$payouts->amount = $reservation_code->host_payout;
			$payouts->currency_code = $reservation_code->currency_code;
			$payouts->status = 'Future';

			$payouts->save();
		}

		$message = new Messages;
		$messages = '';
		if (@$data['message_to_host']) {
			$messages = $this->helper->phone_email_remove($data['message_to_host']);
		}

		$message->room_id = $data['room_id'];
		$message->reservation_id = $reservation->id;
		$message->user_to = $reservation->host_id;
		$message->user_from = $reservation->user_id;
		$message->message = $messages;
		$message->message_type = 2;
		$message->read = 0;

		$message->save();

		//$email_controller = new EmailController;
		//$email_controller->booking($reservation->id);

		Session::forget('payment_room_id');
		Session::forget('payment_checkin');
		Session::forget('payment_checkout');
		Session::forget('payment_number_of_guests');
		Session::forget('payment_booking_type');
		Session::forget('coupon_code');
		Session::forget('coupon_amount');
		Session::forget('remove_coupon');
		Session::forget('manual_coupon');

		return $code;
	}

	/**
	 * Change User Currency
	 *
	 * @param Get method request inputs
	 * @return @return Response in Json
	 */
	public function currency_change(Request $request) {
		$rules = array('currency_code' => 'required|exists:currency,code');

		$niceNames = array('currency_code' => 'Currency Code');

		$messages = array('required' => ':attribute is required.');

		$validator = Validator::make($request->all(), $rules, $messages);

		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {

			return response()->json([

				'success_message' => 'Invalid Currency Code',

				'status_code' => '0',

			]);
		} else {
			$currency_code_request = $request->currency_code;
			//convert currency code to upper case
			$currency_code_original = strtoupper($request->currency_code);
			// currency code validation
			if ($currency_code_request == $currency_code_original) {
				$user = JWTAuth::parseToken()->authenticate();

				$user = User::whereId($user->id)->first();

				DB::table('users')->where('id', $user->id)->update(['currency_code' => $request->currency_code]);

				return response()->json([

					'success_message' => 'Currency Code is Changed Successfully',

					'status_code' => '1',

				]);
			} else {

				return response()->json([

					'success_message' => 'Invalid Currency Code',

					'status_code' => '0',

				]);
			}

		}

	}

	/**
	 *Load payout Preferences
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	Public function add_payout_perference(Request $request) {

		if ($request->getMethod() == 'GET') {

			$user_token = $user = JWTAuth::parseToken()->authenticate();
		}

		if ($request->getMethod() == 'POST') {

			$user = $user_token = JWTAuth::toUser($_POST['token']);
			if ($user) {
				$user_id = $user->id;
			} else {
				return response()->json([

					'success_message' => 'user_not_found',

					'status_code' => '0',

				]);
			}
		}

		if ($request->getMethod() == 'POST')

		// first get payout method and country validation
		{
			$rules = array(

				'payout_method' => 'required|in:stripe,paypal,Stripe,Paypal',

				'country' => 'required|exists:country,short_name',

			);

			$messages = array('required' => ':attribute is required.');

			$validator = Validator::make($request->all(), $rules, $messages);

			if ($validator->fails()) {
				$error = $validator->messages()->toArray();

				foreach ($error as $er) {
					$error_msg[] = array($er);

				}

				return response()->json([

					'success_message' => $error_msg['0']['0']['0'],

					'status_code' => '0',

				]);

			}

		}

		/*** Add payout preference for Stripe --start-- ***/
		if ($request->payout_method == 'stripe' || $request->payout_method == 'Stripe') {

			if (empty($_POST['document'])) {
				return response()->json([

					'success_message' => 'document required',

					'status_code' => '0',

				]);
			}

			$country = $request->country;

			/*** required field validation --start-- ***/
			$mandatory_field = PayoutPreferences::getMandatory($country);

			$rules = $mandatory_field;

			// $rules['email'] = 'required';
			$rules['address1'] = 'required';
			$rules['city'] = 'required';
			$rules['state'] = 'required';
			$rules['postal_code'] = 'required';
			$rules['document'] = 'required';
			if ($country == 'JP') {

				$rules['phone_number'] = 'required';
				$rules['bank_name'] = 'required';
				$rules['branch_name'] = 'required';
				$rules['address1'] = 'required';
				$rules['kanji_address1'] = 'required';
				$rules['kanji_address2'] = 'required';
				$rules['kanji_city'] = 'required';
				$rules['kanji_state'] = 'required';
				$rules['kanji_postal_code'] = 'required';

				if (!$user->gender) {
					$rules['gender'] = 'required|in:male,female';
				}

			}
			$messages = array('required' => ':attribute is required.');

			$validator = Validator::make($request->all(), $rules, $messages);

			if ($validator->fails()) {
				$error = $validator->messages()->toArray();

				foreach ($error as $er) {
					$error_msg[] = array($er);

				}

				return response()->json([

					'success_message' => $error_msg['0']['0']['0'],

					'status_code' => '0',

				]);

			}

			/*** required field validation --end-- ***/

			$stripe_data = PaymentGateway::where('site', 'Stripe')->pluck('value', 'name');

			\Stripe\Stripe::setApiKey($stripe_data['secret']);

			$account_holder_type = 'individual';

			// $check_exist = PayoutPreferences::where('user_id',$user->id)->where('payout_method','Stripe')->first();
			// // delete old account
			// if($check_exist)
			// {
			//   try
			//   {
			//     $account = \Stripe\Account::retrieve($check_exist->paypal_email);
			//     $account->delete();
			//   }
			//   catch(\Exception $e)
			//   {

			//   }

			// }

			// create Stripe Account //
			try
			{
				$recipient = \Stripe\Account::create(array(
					"country" => strtolower($country),
					"tos_acceptance" => array(
						"date" => time(),
						"ip" => $_SERVER['REMOTE_ADDR'],
					),
					"payout_schedule" => array(
						"interval" => "manual",
					),
					"type" => "custom",
					'email' => $user->email,
				));

				$payout_preference_stripe_token = @$recipient->id;

			} catch (\Exception $e) {

				return response()->json([

					'success_message' => $e->getMessage(),

					'status_code' => '0',

				]);
			}
			// create stripe token to add bank account //
			try
			{
				$routing_number = $request->routing_number ? $request->routing_number : '';

				$iban_supported_country = Country::getIbanRequiredCountries();
				if (in_array($country, $iban_supported_country)) {

					$account_number = $request->iban;
					$stripe_token = \Stripe\Token::create(array(
						"bank_account" => array(
							"country" => $country,
							"currency" => $request->currency,
							"account_holder_name" => $request->account_holder_name,
							"account_holder_type" => $account_holder_type,
							// "routing_number" => $routing_number,
							"account_number" => $account_number,
						),
					));
				} else {

					$account_number = $request->account_number;
					if ($country == 'AU') {
						$routing_number = $request->bsb;
					} else if ($country == 'HK') {
						$routing_number = $request->clearing_code . '-' . $request->branch_code;
					} else if ($country == 'JP' || $country == 'SG') {
						$routing_number = $request->bank_code . $request->branch_code;
					} else if ($country == 'GB') {
						$routing_number = $request->sort_code;
					}

					$stripe_token = \Stripe\Token::create(array(
						"bank_account" => array(
							"country" => $country,
							"currency" => $request->currency,
							"account_holder_name" => $request->account_holder_name,
							"account_holder_type" => $account_holder_type,
							"routing_number" => $routing_number,
							"account_number" => $request->account_number,
						),
					));

				}

			} catch (\Exception $e) {
				return response()->json([

					'success_message' => $e->getMessage(),

					'status_code' => '0',

				]);
			}
			// create external account using stripe token //
			try
			{

				$recipient->external_accounts->create(array(
					"external_account" => $stripe_token,
				));

			} catch (\Exception $e) {
				return response()->json([

					'success_message' => $e->getMessage(),

					'status_code' => '0',

				]);
			}
			try
			{

				// insert external account details //
				if ($country != 'JP') {
					$recipient->legal_entity->type = $account_holder_type;
					$recipient->legal_entity->first_name = $user->first_name;
					$recipient->legal_entity->last_name = $user->last_name;
					$recipient->legal_entity->dob->day = @$user->dob_array[2];
					$recipient->legal_entity->dob->month = @$user->dob_array[1];
					$recipient->legal_entity->dob->year = @$user->dob_array[0];
					$recipient->legal_entity->address->line1 = @$request->address1;
					$recipient->legal_entity->address->line2 = @$request->address2 ? @$request->address2 : null;
					$recipient->legal_entity->address->city = @$request->city;
					$recipient->legal_entity->address->country = @$country;
					$recipient->legal_entity->address->state = @$request->state ? @$request->state : null;
					$recipient->legal_entity->address->postal_code = @$request->postal_code;
					if ($country == 'US') {
						$recipient->legal_entity->ssn_last_4 = $request->ssn_last_4;
					}
					$recipient->save();
				} else {

					// $address_kana = array(
					//                     'line1'         => '27-15 FLAG 3A',
					//                     // 'line2'         => '4-4-13',
					//                     'town'          => 'ジングウマエ 3-',
					//                     'city'          => 'シブヤク',
					//                     'state'         => 'トウキョウト',
					//                     'postal_code'   => '1500001',
					//                     );
					// $address_kanji = array(
					//                     'line1'         => '27-15 FLAG 3A',
					//                     'town'          => '神宮前 3丁目',
					//                     // 'line2'         => '4-4-13',
					//                     'city'          => '渋谷区',
					//                     'state'         => '東京都',
					//                     'postal_code'   => '1500001',
					//                     );

					$address_kana = array(
						'line1' => $request->address1,
						'town' => $request->address2,
						'city' => $request->city,
						'state' => $request->state,
						'postal_code' => $request->postal_code,
						'country' => $country,
					);
					$address_kanji = array(
						'line1' => $request->kanji_address1,
						'town' => $request->kanji_address2,
						'city' => $request->kanji_city,
						'state' => $request->kanji_state,
						'postal_code' => $request->kanji_postal_code,
						'country' => $country,
					);

					$recipient->legal_entity->type = $account_holder_type;
					$recipient->legal_entity->first_name_kana = $user->first_name;
					$recipient->legal_entity->last_name_kana = $user->last_name;
					$recipient->legal_entity->first_name_kanji = $user->first_name;
					$recipient->legal_entity->last_name_kanji = $user->last_name;
					$recipient->legal_entity->dob->day = @$user->dob_array[2];
					$recipient->legal_entity->dob->month = @$user->dob_array[1];
					$recipient->legal_entity->dob->year = @$user->dob_array[0];
					$recipient->legal_entity->address_kana = $address_kana;
					$recipient->legal_entity->address_kanji = $address_kanji;
					$recipient->legal_entity->gender = $request->gender ? $request->gender : strtolower($user->gender);

					$recipient->legal_entity->phone_number = @$request->phone_number ? $request->phone_number : null;

					$recipient->save();
				}

			} catch (\Exception $e) {
				return response()->json([

					'success_message' => $e->getMessage(),

					'status_code' => '0',

				]);
			}
			// document upload to create stripe custome account end //
			$document = $_POST['document'];
			$document = str_replace('data:image/png;base64,', '', $document);
			$document = str_replace(' ', '+', $document);
			$data = base64_decode($document);
			$file = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user->id . '/uploads/' . time() . '.jpg';

			$filenamepath = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user->id . '/uploads';
			if (!file_exists($filenamepath)) {
				mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user->id . '/uploads', 0777, true);
			}

			$success = file_put_contents($file, $data);
			if ($success) {
				try
				{
					$stripe_file_details = \Stripe\FileUpload::create(
						array(
							"purpose" => "identity_document",
							"file" => fopen($file, 'r'),
						),
						array("stripe_account" => @$recipient->id)
					);

					$recipient->legal_entity->verification->document = $stripe_file_details->id;
					$recipient->save();
					$stripe_document = $stripe_file_details->id;

				} catch (\Exception $e) {
					return response()->json([

						'success_message' => $e->getMessage(),

						'status_code' => '0',

					]);
					// $this->helper->flash_message('error', $e->getMessage());
					// return back();
				}
			}

			// document upload to create stripe custome account end //

			//check payoutpreferences is selected or not
			$payout_default_count = PayoutPreferences::where('user_id', $user->id)->where('default', '=', 'yes');

			// $check_exist = PayoutPreferences::where('user_id',$user->id)->where('payout_method','Stripe')->first();
			// if($check_exist)
			//   $payout_perference = $check_exist;
			// else
			$payout_perference = new PayoutPreferences;

			$payout_perference->user_id = $user_token->id;

			$payout_perference->paypal_email = @$recipient->id;

			$payout_perference->address1 = $request->address1 != ''

			? $request->address1 : '';

			$payout_perference->address2 = $request->address2 != ''

			? $request->address2 : '';

			$payout_perference->city = $request->city != ''

			? $request->city : '';

			$payout_perference->state = $request->state != ''

			? $request->state : '';

			$payout_perference->country = $country;

			$payout_perference->default = $payout_default_count->count() == 0 ? 'yes' : 'no';

			$payout_perference->postal_code = $request->postal_code != ''

			? $request->postal_code : '';

			$payout_perference->currency_code = $request->currency != null

			? $request->currency : DEFAULT_CURRENCY;

			$payout_perference->routing_number = $routing_number ? $routing_number : '';

			$payout_perference->account_number = $account_number ? $account_number : '';

			$payout_perference->holder_name = $request->account_holder_name;

			$payout_perference->holder_type = $account_holder_type;

			$payout_perference->document_id = @$stripe_document;

			$payout_perference->document_image = @$filename;

			$payout_perference->phone_number = @$request->phone_number ? $request->phone_number : '';

			$payout_perference->branch_code = @$request->branch_code ? $request->branch_code : '';

			$payout_perference->bank_name = @$request->bank_name ? $request->bank_name : '';

			$payout_perference->branch_name = @$request->branch_name ? $request->branch_name : '';

			$payout_perference->payout_method = 'Stripe';

			$payout_perference->ssn_last_4 = @$country == 'US' ? $request->ssn_last_4 : '';

			$payout_perference->address_kanji = @$address_kanji ? json_encode(@$address_kanji) : json_encode([]);

			$payout_perference->save(); //save Payout Details
			// dd($recipient);
			return response()->json([

				'success_message' => 'Payout Details Is Added Successfully',

				'status_code' => '1',

			]);

		}
		/*** Add payout preference for Stripe --end-- ***/
		else {

			$rules = array(

				'address1' => 'required | max:255',

				'city' => 'required',

				'country' => 'required|exists:country,short_name',

				'postal_code' => 'required',

				'paypal_email' => 'required|email',

			);
			$messages = array('required' => ':attribute is required.');

			$validator = Validator::make($request->all(), $rules, $messages);

			if ($validator->fails()) {
				$error = $validator->messages()->toArray();

				foreach ($error as $er) {
					$error_msg[] = array($er);

				}

				return response()->json([

					'success_message' => $error_msg['0']['0']['0'],

					'status_code' => '0',

				]);

			} else {

				//get country shortname
				$country_short_name = $request->country;

				//Get Default PayPal Currency code
				$paypal_currency = @SiteSettings::where('name', '=', 'paypal_currency')->first()->value;

				//check payoutpreferences is selected or not
				$payout_default_count = PayoutPreferences::where('user_id', $user->id)->where('default', '=', 'yes');

				$payout_perference = new PayoutPreferences;

				$payout_perference->user_id = $user_token->id;

				$payout_perference->paypal_email = $request->paypal_email;

				$payout_perference->address1 = $request->address1 != ''

				? $request->address1 : '';

				$payout_perference->address2 = $request->address2 != ''

				? $request->address2 : '';

				$payout_perference->city = $request->city != ''

				? $request->city : '';

				$payout_perference->state = $request->state != ''

				? $request->state : '';

				$payout_perference->country = $country_short_name;

				$payout_perference->default = $payout_default_count->count() == 0 ? 'yes' : 'no';

				$payout_perference->postal_code = $request->postal_code != ''

				? $request->postal_code : '';

				$payout_perference->currency_code = $paypal_currency != null

				? $paypal_currency : DEFAULT_CURRENCY;

				$payout_perference->payout_method = 'Paypal';

				$payout_perference->save(); //save Payout Details

				return response()->json([

					'success_message' => 'Payout Details Is Added Successfully',

					'status_code' => '1',

				]);

			}

		}

	}
	/**
	 * Display Request to Book Page on Web
	 *@param  Get method request inputs
	 *
	 * @return  Redirect Request to Book or Payment Page Based On Booking Type
	 */
	Public function book_now(Request $request) {

		$rules = array(

			'room_id' => 'required|exists:rooms,id',

			'card_type' => 'required',

			'check_in' => 'required|date_format:d-m-Y',

			'check_out' => 'required|date_format:d-m-Y|after:today|after:check_in',

			'number_of_guests' => 'required|integer|between:1,16',

			'country' => 'required|exists:country,long_name',

		);

		$niceNames = array('room_id' => 'Room Id');

		$messages = array('required' => ':attribute is required.');

		$validator = Validator::make($request->all(), $rules, $messages);

		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {

			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}
			return response()->json([

				'success_message' => $error_msg['0']['0']['0'],

				'status_code' => '0']);

		} else {

			//validate  for payment card type
			if ($request->card_type != 'Credit Card' && $request->card_type != 'PayPal') {

				return response()->json([

					'success_message' => 'Invalid Card Type Name Only Allowed Credit Card or PayPal',

					'status_code' => '0',

				]);

			}

			$user = JWTAuth::parseToken()->authenticate();

			$currency_details = @Currency::where('code', $user->currency_code)->first();

			$user_profile_picture = @ProfilePicture::where('user_id', $user->id)->first();

			$rooms_info = @Rooms::where('id', $request->room_id)->first();

			// prevent from host booking own room
			if (@$user->id == @$rooms_info->user_id) {

				return response()->json([

					'success_message' => 'You Can Not Book Your Own Listing',

					'status_code' => '0',

				]);
			}
			//check rooms date is available or not
			$data = $this->payment_helper->price_calculation(
				$request->room_id,
				$request->check_in,
				$request->check_out,
				$request->number_of_guests,
				''
			);

			$data = json_decode($data, TRUE);

			$result = @$data['status'];

			if ((isset($data['status'])) && ($result == 'Not available')) {

				return response()->json([

					'success_message' => 'Dates Not Available',

					'status_code' => '0',

				]);
			}

			Session::flush();
			//In mobile app booking
			$s_key = $request->s_key ?: time() . $request->id . str_random(4);

			$mobile_payment_counry_code = Country::where('long_name', $request->country)->pluck('short_name');
			$payment = array(
				'payment_room_id' => $request->room_id,
				'payment_checkin' => $request->check_in,
				'payment_checkout' => $request->check_out,
				'payment_number_of_guests' => $request->number_of_guests,
				'payment_special_offer_id' => $request->special_offer_id,
				'payment_booking_type' => $request->payment_booking_type,
				'payment_reservation_id' => $request->reservation_id,
				'payment_cancellation' => ($request->cancellation != '') ? $request->cancellation : $rooms_info->cancel_policy,
				'currency' => @$user->currency_code,
				'currency_symbol' => @$currency_details->original_symbol,
				'mobile_auth_user_id' => $user->id,
				'mobile_user_image' => $user_profile_picture->email_src,
				'mobile_guest_message' => $request->message,
				'payment_card_type' => $request->card_type,
				'payment_country' => $request->country,
				'payment_message_to_host' => $request->message,
				'mobile_payment_counry_code' => $mobile_payment_counry_code,
				'user_token' => $request->token,
			);

			Session::put('payment.' . $s_key, $payment);

			Session::put('s_key', $s_key);
			Session::put('get_token', $request->token);
			return redirect('api_payments/book/' . $request->room_id . '?s_key=' . $s_key.'&token='.$request->token);

		}

	}
	/**
	 * Display Payment  Page on Web
	 *@param   Get method request inputs
	 *
	 * @return Response in Json
	 */
	public function pay_now(Request $request) {
		if (!$request->special_offer_id) {
			$rules = array('reservation_id' => 'required|exists:reservation,id');
		} else {
			$rules = array('special_offer_id' => 'required|exists:special_offer,id');
		}

		$niceNames = array('reservation_id' => 'Reservation Id', 'special_offer_id' => 'Special Offer Id');

		$messages = array('required' => ':attribute is required.');

		$validator = Validator::make($request->all(), $rules, $messages);

		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {

			$error = $validator->messages()->toArray();

			foreach ($error as $er) {
				$error_msg[] = array($er);

			}
			return response()->json([

				'success_message' => $error_msg['0']['0']['0'],

				'status_code' => '0']);
			exit;
		} else {
			if (!$request->special_offer_id) {
				$reservation = Reservation::find($request->reservation_id);

				$user = JWTAuth::parseToken()->authenticate();

				$user_profile_picture = @ProfilePicture::where('user_id', $user->id)->first();

				$currency_details = @Currency::where('code', $user->currency_code)->first();

				$rooms_info = Rooms::where('id', $reservation->room_id)->first();
				//prevent from host booking
				if ($user->id == $rooms_info->user_id) {

					return response()->json([

						'success_message' => 'You Can Not Book Yours Own Listing',

						'status_code' => '0']);
				}
				//check rooms dates available or not
				$data = $this->payment_helper->price_calculation(
					$reservation->room_id,
					$reservation->checkin,
					$reservation->checkout,
					$reservation->number_of_guests,
					''
				);

				$data = json_decode($data, TRUE);

				$result = @$data['status'];

				if ((isset($data['status'])) && ($result == 'Not available')) {

					/*  return response()->json([

						                                    'success_message' => 'Rooms Already Booked',

						                                    'status_code'     => '0'

					*/

					$result = array('success_message' => 'Rooms Already Booked', 'status_code' => '0');
					return view('json_response.json_response', array('result' => json_encode($result)));

				}

				Session::flush();

				$s_key = $request->s_key ?: time() . $request->id . str_random(4);

				$country_name = Country::where('short_name', $reservation->country)
					->pluck('long_name');

				$payment = array(
					'payment_room_id' => $reservation->room_id,
					'payment_checkin' => $reservation->checkin,
					'payment_checkout' => $reservation->checkout,
					'payment_number_of_guests' => $reservation->number_of_guests,
					'payment_reservation_id' => $reservation->id,
					'payment_special_offer_id' => $reservation->special_offer_id,
					'payment_booking_type' => 'instant_book',
					'payment_cancellation' => $reservation->cancellation,
					'currency' => @$user->currency_code,
					'currency_symbol' => @$currency_details->original_symbol,
					'mobile_auth_user_id' => $user->id,
					'mobile_user_image' => $user_profile_picture->email_src,
					'payment_card_type' => $reservation->paymode,
					'payment_country' => $country_name,
					'mobile_payment_counry_code' => $reservation->country,
				);

				Session::put('payment.' . $s_key, $payment);

				Session::put('s_key', $s_key);
				Session::put('get_token', $request->token);

				return redirect('api_payments/book?s_key=' . $s_key . '&reservation_id=' . $request->reservation_id);
			} else {

				$special_offer = SpecialOffer::where('id', $request->special_offer_id)->first();
				$reservation = Reservation::where('id', $special_offer->reservation_id)->first();

				$user = JWTAuth::parseToken()->authenticate();

				$user_profile_picture = @ProfilePicture::where('user_id', $user->id)->first();

				$currency_details = @Currency::where('code', $user->currency_code)->first();

				$rooms_info = Rooms::where('id', $special_offer->room_id)->first();
				//prevent from host booking
				if ($user->id == $rooms_info->user_id) {

					return response()->json([

						'success_message' => 'You Can Not Book Yours Own Listing',

						'status_code' => '0']);
				}
				//check rooms dates available or not
				$data = $this->payment_helper->price_calculation(
					$special_offer->room_id,
					$special_offer->checkin,
					$special_offer->checkout,
					$special_offer->number_of_guests,
					''
				);

				$data = json_decode($data, TRUE);

				$result = @$data['status'];

				if ((isset($data['status'])) && ($result == 'Not available')) {

					/*  return response()->json([

						                                    'success_message' => 'Rooms Already Booked',

						                                    'status_code'     => '0'

					*/

					$result = array('success_message' => 'Rooms Already Booked', 'status_code' => '0');
					return view('json_response.json_response', array('result' => json_encode($result)));

				}

				Session::flush();

				$s_key = $request->s_key ?: time() . $request->id . str_random(4);

				$country_name = Country::where('short_name', $reservation->country)
					->pluck('long_name');

				$payment = array(
					'payment_room_id' => $special_offer->room_id,
					'payment_checkin' => $special_offer->checkin,
					'payment_checkout' => $special_offer->checkout,
					'payment_number_of_guests' => $special_offer->number_of_guests,
					'payment_reservation_id' => '',
					'payment_special_offer_id' => $special_offer->id,
					'payment_booking_type' => 'instant_book',
					'payment_cancellation' => $reservation->cancellation,
					'currency' => @$user->currency_code,
					'currency_symbol' => @$currency_details->original_symbol,
					'mobile_auth_user_id' => $user->id,
					'mobile_user_image' => $user_profile_picture->email_src,
					'payment_card_type' => $reservation->paymode,
					'payment_country' => $country_name,
					'mobile_payment_counry_code' => $reservation->country,
				);

				Session::put('payment.' . $s_key, $payment);

				Session::put('s_key', $s_key);
				Session::put('get_token', $request->token);

				return redirect('api_payments/book?s_key=' . $s_key . '&reservation_id=' . $request->reservation_id);

			}
		}

	}
	/**
	 * Reservation Request Decline by Host
	 *
	 * @param array $request Input values
	 * @return redirect to Reservation Request page
	 */
	public function decline(Request $request) {
		$reservation_details = Reservation::find($request->reservation_id);
		//check reservation status is cancelled or not
		if ($reservation_details->status == 'Cancelled') {
			return response()->json(['success_message' => 'Already this Reservation Cancelled', 'status_code' => '0']);
		} else if ($reservation_details->status == 'Declined') {
			return response()->json(['success_message' => 'Already this Reservation Declined', 'status_code' => '0']);
		} else {
			$reservation_details->status = 'Declined';
			$reservation_details->decline_reason = $request->decline_reason;
			$reservation_details->declined_at = date('Y-m-d H:m:s');

			$reservation_details->save();

			$messages = new Messages;

			$messages->room_id = $reservation_details->room_id;
			$messages->reservation_id = $reservation_details->id;
			$messages->user_to = $reservation_details->user_id;
			$messages->user_from = JWTAuth::parseToken()->authenticate()->id;
			$messages->message = $this->helper->phone_email_remove($request->decline_message);
			$messages->message_type = 3;

			$messages->save();

			$this->payment_helper->revert_travel_credit($request->id);

			return response()->json(['success_message' => 'Reservation Request has Successfully Declined', 'status_code' => '1']);
		}
	}

	/**
	 * Reservation Request Accept by Host
	 *
	 * @param array $request Input values
	 * @return redirect to Reservation Request page
	 */
	public function accept(Request $request, EmailController $email_controller) {
		$reservation_details = Reservation::find($request->reservation_id);

		if ($reservation_details->status != 'Pending' && $reservation_details->status != 'Inquiry') {

			return response()->json(['success_message' => 'Already this Reservation ' . $reservation_details->status, 'status_code' => '0']);

		} else {

			$reservation_details->status = 'Pre-Accepted';
			$reservation_details->accepted_at = date('Y-m-d H:m:s');

			$reservation_details->save();

			$friends_email = explode(',', $reservation_details->friends_email);
			if (count($friends_email) > 0) {
				foreach ($friends_email as $email) {
					if ($email != '') {
						$email_controller->itinerary($reservation_details->code, $email);
					}
				}
			}

			$messages = new Messages;

			$messages->room_id = $reservation_details->room_id;
			$messages->reservation_id = $reservation_details->id;
			$messages->user_to = $reservation_details->user_id;
			$messages->user_from = JWTAuth::parseToken()->authenticate()->id;
			$messages->message = $this->helper->phone_email_remove($request->message_to_guest);
			$messages->message_type = 12;

			$messages->save();

			$email_controller->pre_accepted($reservation_details->id);

			return response()->json([

				'success_message' => 'Reservation Request has Successfully Pre-Accepted',

				'status_code' => '1',

			]);
		}
	}

}
