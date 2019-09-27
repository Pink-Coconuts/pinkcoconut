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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Calendar;
use App\Models\Currency;
use App\Models\Fees;
use App\Models\Messages;
use App\Models\ProfilePicture;
use App\Models\Reservation;
use App\Models\SpecialOffer;
use App\Models\User;
use Auth;
use DateTime;
use DB;
use Illuminate\Http\Request;
use JWTAuth;
use Session;
use Validator;

class ReservationController extends Controller {
	protected $helper; // Global variable for Helpers instance

	protected $payment_helper; // Global variable for PaymentHelper instance

	/**
	 * Constructor to Set PaymentHelper instance in Global variable
	 *
	 * @param array $payment   Instance of PaymentHelper
	 */
	public function __construct(PaymentHelper $payment) {
		$this->payment_helper = $payment;
		$this->helper = new Helpers;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function inbox_reservation(Request $request) {
		$rules = array('type' => 'required');

		$niceNames = array('type' => 'Type');

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

			$user = JWTAuth::parseToken()->authenticate();

			$user_id = $user->id;
			//check request type is valid or not
			if ($request->type == 'inbox') {

				$all_message = Messages::whereIn('id', function ($query) use ($user_id) {
					$query->select(DB::raw('max(id)'))->from('messages')->where('user_to', $user_id)->groupby('reservation_id');
				})->with(['user_details' => function ($query) {
					$query->with('profile_picture');
				}])->with(['reservation' => function ($query) {
					$query->with('currency');
				}])->with('rooms')->with('rooms_address')->orderBy('id', 'desc')->get();

			} else {
				return response()->json([

					'success_message' => 'Invalid Type Name',

					'status_code' => '0',
				]);

			}
			//check message count
			if (count($all_message) < 0) {

				return response()->json([

					'success_message' => 'No Data Found',

					'status_code' => '0',

				]);

			}

			foreach ($all_message as $all) {

				$profile_picture = $all->user_details->profile_picture->src;

				if($all->list_type=="Experiences"){

					$rooms_address = @$all->host_experience->host_experience_location->city . ', ' . @$all->host_experience->host_experience_location->state;
				}
				else{

					$rooms_address = @$all->rooms_address->city . ', ' . @$all->rooms_address->state;
				}

				$unread = $all->read;

				//check booking status for host contact
				$get_special_offer = @SpecialOffer::where('reservation_id', $all->reservation_id)

					->orderBy('id', 'desc')->first();

				$update_date = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($all->reservation->updated_at)));

				$expire_timer = '';
				//$update_date=date('D, F d, Y');
				if (@$all->reservation->status == 'Pending') {
					$from_time = strtotime($all->reservation->created_at_timer);
					$to_time = strtotime(date('Y/m/d H:i:s'));
					$diff = abs($from_time - $to_time);
					$expire_timer = sprintf('%02d:%02d:%02d', ($diff / 3600), ($diff / 60 % 60), $diff % 60);
				}

				if ($get_special_offer['type'] == 'pre-approval' || $all->reservation->status == 'Pre-Accepted' || $get_special_offer['type'] == 'special_offer') {
					//check date availability
					if ($get_special_offer['type'] == 'special_offer' && $all->reservation->status != 'Pre-Accepted') {
						$data_inquiry = $this->payment_helper->price_calculation(
							$get_special_offer->room_id,
							$get_special_offer->checkin,
							$get_special_offer->checkout,
							$get_special_offer->number_of_guests,
							''
						);
					} else {
						$data_inquiry = $this->payment_helper->price_calculation(
							$all->reservation->room_id,
							$all->reservation->checkin,
							$all->reservation->checkout,
							$all->reservation->number_of_guests,
							''
						);
					}
					$data_inquiry = json_decode($data_inquiry, TRUE);

					$result = @$data_inquiry['status'];

					$date = date('Y-m-d');

					if ((isset($data_inquiry['status'])) && ($result == 'Not available')) {

						$booking_status = 'Not available';

					} else {
						if ($all->reservation->checkin < $date) {
							$booking_status = 'Not available';
						} else {
							$booking_status = 'Available';
						}
					}

				} else {
					$booking_status = 'Not Available';
				}

				$so_status = 'No';
				$so_room_name = '';
				$so_room_id = '';
				$so_checkin = '';
				$so_checkin_time = '';
				$so_checkout = '';
				$so_checkout_time = '';
				$so_guests = '';
				$so_price = '';
				$so_id = '';

				if ($get_special_offer['type'] == 'special_offer') {
					$so_id = $get_special_offer['id'];
					$so_status = 'Yes';
					if($all->list_type=='Experiences'){
						$so_room_name = '';
					}
					else{
						$so_room_name = $get_special_offer->rooms->name;
					}
					
					$so_room_id = $get_special_offer->room_id;
					$so_checkin = $get_special_offer->checkin;
					$so_checkin_time = date('M d, Y', strtotime($get_special_offer->checkin));
					$so_checkout = $get_special_offer->checkout;
					$so_checkout_time = date('M d, Y', strtotime($get_special_offer->checkout));
					$so_guests = $get_special_offer->number_of_guests;
					$so_price = $get_special_offer->price;
				}

				$coupon_amount = $all->reservation->coupon_code != 'Travel_Credit' ? $all->reservation->coupon_amount : '0';

				$travel_credit = $all->reservation->coupon_code == 'Travel_Credit' ? $all->reservation->coupon_amount : '0';

				$data[] = array(
					'booking_status' => $booking_status,
					'reservation_id' => $all->reservation_id,
					'trip_date' => $all->reservation->dates,
					'list_type' => $all->list_type,
					'room_id' => $all->room_id,
					'is_message_read' => $unread == 1 ? 'Yes' : 'No',
					'message_status' => $all->reservation->status,
					'host_user_name' => $all->user_details->first_name,
					'host_thumb_image' => $profile_picture,
					'last_message_date_time' => "",
					'room_name' => ($all->list_type=='Experiences')?$all->host_experience->title:$all->rooms->name,
					'room_location' => @$rooms_address,
					'last_message' => $all->message,
					'check_in_time' => date('M d, Y', strtotime(
						$all->reservation->checkin)),
					'check_out_time' => date('M d, Y', strtotime(
						$all->reservation->checkout)),
					'total_cost' => $all->reservation->check_total,
					'host_fee' => $all->reservation->host_fee,

					'coupon_amount' => (string) $coupon_amount,

					'travel_credit' => (string) $travel_credit,

					'host_penalty_amount' => @(string) $all->reservation->hostPayouts->total_penalty_amount,

					// 'per_night_price'        =>  @$all->reservation->per_night,
					'per_night_price' => @$all->reservation->base_per_night,
					'length_of_stay_type' => @(string) $all->reservation->length_of_stay_type,
					'length_of_stay_discount' => @$all->reservation->length_of_stay_discount,
					'length_of_stay_discount_price' => @$all->reservation->length_of_stay_discount_price,
					'booked_period_type' => @(string) $all->reservation->booked_period_type,
					'booked_period_discount' => @$all->reservation->booked_period_discount,
					'booked_period_discount_price' => @$all->reservation->booked_period_discount_price,

					'service_fee' => @$all->reservation->service,
					'security_deposit' => @$all->reservation->security,
					'cleaning_fee' => @$all->reservation->cleaning,
					'additional_guest_fee' => @$all->reservation->additional_guest,
					'host_user_id' => $all->user_details->id,
					'request_user_id' => @$all->reservation->host_id,
					'user_location' => $all->user_details->live,
					'room_thumb_image' => ($all->list_type=='Experiences')?$all->host_experience->photo_name:$all->rooms->src,
					'total_guest' => (string) $all->reservation->number_of_guests,
					'total_nights' => (string) @$all->reservation->nights,
					'host_member_since_from' => $all->user_details->since,
					'review_count' => (string) ($all->list_type=='Experiences')?$all->host_experience->reviews_count:$all->rooms->reviews_count,
					'expire_timer' => (string) @$expire_timer,

					'special_offer_id' => $so_id,
					'special_offer_status' => $so_status,
					'special_offer_room_name' => $so_room_name,
					'special_offer_room_id' => $so_room_id,
					'special_offer_checkin' => $so_checkin,
					'special_offer_checkin_time' => $so_checkin_time,
					'special_offer_checkout' => $so_checkout,
					'special_offer_checkout_time' => $so_checkout_time,
					'special_offer_guests' => $so_guests,
					'special_offer_price' => $so_price,
				);
			}

			return response()->json([

				'success_message' => ucfirst($request->type) . 'Details Listed Successfully',

				'status_code' => '1',

				'unread_message_count' => @$unread != null ? $unread : '0',

				'data' => @$data != null ? $data : array(),

			]);

		}
	}
	/**
	 *Display Conversation List
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */

	public function conversation_list(Request $request) {

		$rules = array(

			'host_user_id' => 'required|exists:users,id',

			'reservation_id' => 'required|exists:reservation,id',

		);

		$niceNames = array('host_user_id' => 'Host User Id');

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

			$user = JWTAuth::parseToken()->authenticate();
			//get usr profile image
			$profile_image_user = ProfilePicture::where('user_id', $user->id)->first()->src;
			//set user token to session for getting  user time based on time zone.
			Session::put('get_token', $request->token);

			$user_id = $user->id;

			$user_name = $user->first_name . ' ' . $user->last_name;

			//get host user details
			$host_user_name = User::with('profile_picture')->where('users.id', $request->host_user_id)->first();

			$host_id = $request->host_user_id;
			//Prevent from host
			if ($user_id == $host_id) {
				return response()->json([

					'success_message' => 'Host And User Id Is Same',

					'status_code' => '0',

				]);

			}

			$result = Messages::with('user_details')

				->where('reservation_id', $request->reservation_id)

				->where(function ($query) use ($user_id) {

					$query->where('user_to', '=', $user_id)

						->orWhere('user_from', '=', $user_id);})

				->where(function ($query) use ($host_id) {

					$query->where('user_to', '=', $host_id)

						->orWhere('user_from', '=', $host_id);})

			// ->orderBy('id','DESC')
				->get();

			if (count($result) == 0) {
				return response()->json([

					'success_message' => 'Reservation Not Fount',

					'status_code' => '0']);

			}
			foreach ($result as $result_data) {

				$createDate = new DateTime(date('Y-m-d', strtotime($result_data['created_at'])));

				$today_date = date('Y-m-d');

				if ($today_date == $createDate->format('Y-m-d')) {

					$message_date = $result_data->created_time;
				} else {

					$message_date = $createDate->format('d-m-Y');

				}

				if ($result_data->message_type == 1) {
					$sender_details = array('status' => 'Pre-Accept the Request?', 'date/time' => $message_date);
				} elseif ($result_data->message_type == 2) {
					$sender_details = array('status' => 'Reservation Accept', 'date/time' => $message_date);
				} elseif ($result_data->message_type == 3) {
					$sender_details = array('status' => 'Reservation Decline', 'date/time' => $message_date);
				} elseif ($result_data->message_type == 4) {
					$sender_details = array('status' => 'Reservation Expire', 'date/time' => $message_date);
				} elseif ($result_data->message_type == 5) {
					$sender_details = array('status' => 'Reservation Discuss', 'date/time' => $message_date);
				} elseif ($result_data->message_type == 6) {
					$sender_details = array('status' => 'Pre-Approval', 'date/time' => $message_date);
				} elseif ($result_data->message_type == 7) {
					$sender_details = array('status' => 'Special Offer', 'date/time' => $message_date);
				} elseif ($result_data->message_type == 8) {
					$sender_details = array('status' => 'Unavailable', 'date/time' => $message_date);
				} elseif ($result_data->message_type == 9) {
					$sender_details = array('status' => 'Contact Request', 'date/time' => $message_date);
				} elseif ($result_data->message_type == 10) {
					$sender_details = array('status' => 'Cancel Reservation by Guest', 'date/time' => $message_date);
				} elseif ($result_data->message_type == 11) {
					$sender_details = array('status' => 'Cancel Reservation by Host', 'date/time' => $message_date);
				}
				$reservation = Reservation::with(['rooms' => function ($query) {
					$query->with('rooms_price');
				}])
					->where('id', $result_data->reservation_id)->first();

				//message details send from user to host
				if ($result_data->user_to == $request->host_user_id && $result_data->user_from == $user_id) {

					if ($result_data->message_type == 12) {

						$sender_details = array(

							'status' => 'Pre-Accept',

							'date' => $message_date,

							'inquiry_title' => 'Inquiry about ' . $reservation->rooms->name,

							'date' => $reservation->checkin . ' - ' . $reservation->checkout . ' ' .

							$reservation->number_of_guests . ' Guest',

							'amount' => 'You will earn ' . $reservation->rooms->rooms_price->night . ' ' . $user->currency_code,

						);
					}

					//get usr profile image
					$profile_image = ProfilePicture::where('user_id', $user_id)->first()->src;

					$data[] = array(
						'receiver_thumb_image' => '',

						'receiver_user_name' => '',

						'receiver_message_status' => '',

						'receiver_details' => (object) array(),

						'receiver_messages' => '',

						'sender_thumb_image' => $profile_image,

						'sender_user_name' => $result_data->user_details->first_name . ' ' .

						$result_data->user_details->last_name,

						'sender_message_status' => $result_data->read,

						'sender_details' => @$sender_details != null ? $sender_details : (object) array(),

						'sender_messages' => $result_data->message,

						'sender_messages_date/time' => $message_date,
					);

				}
				// //message details send from host to user
				if ($result_data->user_to == $user_id && $result_data->user_from == $request->host_user_id) {

					//get host profile  image
					$profile_image = ProfilePicture::where('user_id', $request->host_user_id)->first()->src;

					$data[] = array(

						'receiver_thumb_image' => $profile_image,

						'receiver_user_name' => $result_data->user_details->first_name . ' ' .

						$result_data->user_details->last_name,

						'receiver_message_status' => $result_data->read,

						'receiver_details' => @$sender_details != null ? $sender_details : (object) array(),

						'receiver_messages' => $result_data->message,

						'receiver_messages_date/time' => $message_date,

						'sender_thumb_image' => '',

						'sender_user_name' => '',

						'sender_message_status' => '',

						'sender_details' => (object) array(),

						'sender_messages' => '',

					);

				}
			}

			return json_encode(

				array('success_message' => 'Conversation Details Listed Successfully',

					'status_code' => '1',

					'sender_user_name' => $user_name,

					'sender_thumb_image' => $profile_image_user,

					'receiver_user_name' => $host_user_name->full_name,

					'receiver_thumb_image' => $host_user_name->profile_picture->src,

					'data' => @$data != null ? $data : ''), JSON_UNESCAPED_SLASHES);

		}

	}
	/**
	 *Display Reservation List
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function reservation_list(Request $request) {
		$user = JWTAuth::parseToken()->authenticate();

		//get reservation details
		$data = Reservation::with([

			'host_users' => function ($query) {
				$query->with('profile_picture');
			},

			'users' => function ($query) {
				$query->with('profile_picture');
			},
			'rooms' => function ($query) {
				$query->with('rooms_address');

			},

		])->where('host_id', $user->id)->where('type', '!=', 'contact')->orderBy('id', 'DESC')->get();

		if (count($data) < 1) {
			return response()->json([

				'success_message' => 'No reservation Found',

				'status_code' => '0']);
		}

		foreach ($data as $result_data) {

			//get room locaiton address remove null location address
			$room_location = array();

			if($result_data->list_type=='Experiences'){

				if ($result_data->host_experiences->host_experience_location->address_line_1 != '') {

					$room_location[] = $result_data->host_experiences->host_experience_location->address_line_1;

				}
				if ($result_data->host_experiences->host_experience_location->address_line_2 != '') {

					$room_location[] = $result_data->host_experiences->host_experience_location->address_line_2;

				}
				if ($result_data->host_experiences->host_experience_location->city != '') {

					$room_location[] = $result_data->host_experiences->host_experience_location->city;

				}
				if ($result_data->host_experiences->host_experience_location->state != '') {

					$room_location[] = $result_data->host_experiences->host_experience_location->state;

				}
				if ($result_data->host_experiences->host_experience_location->postal_code != '') {

					$room_location[] = $result_data->host_experiences->host_experience_location->postal_code;

				}

			}
			else{

				if ($result_data->rooms->rooms_address->address_line_1 != '') {

					$room_location[] = $result_data->rooms->rooms_address->address_line_1;

				}
				if ($result_data->rooms->rooms_address->address_line_2 != '') {

					$room_location[] = $result_data->rooms->rooms_address->address_line_2;

				}
				if ($result_data->rooms->rooms_address->city != '') {

					$room_location[] = $result_data->rooms->rooms_address->city;

				}
				if ($result_data->rooms->rooms_address->state != '') {

					$room_location[] = $result_data->rooms->rooms_address->state;

				}
				if ($result_data->rooms->rooms_address->postal_code != '') {

					$room_location[] = $result_data->rooms->rooms_address->postal_code;

				}

			}

			
			//get currency details
			$currency_details = Currency::where('code', $user->user_currency_code)->first()->toArray();

			$update_date = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($result_data->updated_at)));

			$expire_timer = '';

			if ($result_data->status == 'Pending') {
				//$update_date=date('D, F d, Y');
				$from_time = strtotime($result_data->created_at_timer);
				$to_time = strtotime(date('Y/m/d H:i:s'));
				$diff = abs($from_time - $to_time);
				$expire_timer = sprintf('%02d:%02d:%02d', ($diff / 3600), ($diff / 60 % 60), $diff % 60);
			}

			$coupon_amount = $result_data->coupon_code != 'Travel_Credit' ? $result_data->coupon_amount : '0';

			$travel_credit = $result_data->coupon_code == 'Travel_Credit' ? $result_data->coupon_amount : '0';

			$result[] = array(

				'reservation_id' => $result_data->id,

				'room_id' => $result_data->room_id,

				'trip_status' => $result_data->status,

				'list_type' => $result_data->list_type,

				'host_users_id' => $result_data->host_id,

				'host_user_name' => $result_data->host_users->full_name,

				'host_thumb_image' => $result_data->host_users->profile_picture->email_src,

				'guest_users_id' => $result_data->user_id,

				'guest_user_name' => $result_data->users->full_name,

				'guest_thumb_image' => $result_data->users->profile_picture->email_src,

				'guest_user_location' => $result_data->users->live != null

				? $result_data->users->live : '',

				'member_from' => $result_data->users->since != null

				? $result_data->users->since : '',

				'trip_date' => $result_data->dates,

				'room_name' => ($result_data->list_type=='Experiences')?$result_data->host_experiences->title:$result_data->rooms->name,

				'room_type' => ($result_data->list_type=='Experiences')?$result_data->host_experiences->category_details->name:$result_data->rooms->room_type_name,

				'room_location' => implode(',', $room_location),

				'start_time' => date('H:i',strtotime($result_data->start_time)),
				
				'end_time' => date('H:i',strtotime($result_data->end_time)),

				'total_nights' => $result_data->nights,

				'guest_count' => @$result_data->number_of_guests != null

				? $result_data->number_of_guests : '0',

				'room_image' => ($result_data->list_type=='Experiences')?$result_data->host_experiences->photo_name:$result_data->rooms->photo_name,

				'check_in' => date('M d, Y', strtotime($result_data->checkin)),

				'check_out' => date('M d, Y', strtotime($result_data->checkout)),

				'total_cost' => @(string) $result_data->check_total,

				// 'per_night_price'        =>     @(string)$result_data->per_night,

				'per_night_price' => @(string) $result_data->base_per_night,
				'length_of_stay_type' => @(string) $result_data->length_of_stay_type,
				'length_of_stay_discount' => @$result_data->length_of_stay_discount,
				'length_of_stay_discount_price' => @$result_data->length_of_stay_discount_price,
				'booked_period_type' => @(string) $result_data->booked_period_type,
				'booked_period_discount' => @$result_data->booked_period_discount,
				'booked_period_discount_price' => @$result_data->booked_period_discount_price,

				'service_fee' => @(string) $result_data->service,

				'security_deposit' => @(string) $result_data->security,

				'cleaning_fee' => @(string) $result_data->cleaning,

				'additional_guest_fee' => @(string) $result_data->additional_guest,

				'host_fee' => @(string) $result_data->host_fee,
				'coupon_amount' => (string) $coupon_amount,
				'travel_credit' => (string) $travel_credit,
				'host_penalty_amount' => @(string) $result_data->hostPayouts->total_penalty_amount,

				'payment_recieved_date' => $result_data->transaction_id !== ''
				? $update_date : '',

				'can_view_receipt' => $result_data->status == 'Accepted'

				? 'Yes' : 'No',

				'currency_symbol' => @$currency_details['original_symbol'],

				'expire_timer' => @(string) $expire_timer,

			);

		}

		return response()->json([

			'success_message' => 'Reservation Details Listed Successfully',

			'status_code' => '1',

			'data' => $result,

		]);

	}
	/**
	 * Reservation Cancel by Host
	 *
	 * @param array $request Input values
	 * @return response json
	 */
	public function host_cancel_reservation(Request $request) {

		$rules = array('reservation_id' => 'required|exists:reservation,id');

		$niceNames = array('reservation_id' => 'Reservation Id');

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
		}
		$user = JWTAuth::parseToken()->authenticate();

		//set currency code to session for price converstion

		Session::put('currency', $user->currency_code);

		$reservation_details = Reservation::find($request->reservation_id);
		//check valid user or not
		if (@$user->id != @$reservation_details->host_id) {
			return response()->json([

				'success_message' => 'Permission Denied',

				'status_code' => '0',

			]);

		}

		// check reservation status is cancelled
		if ($reservation_details->status == 'Cancelled') {
			//return redirect('my_reservations');

			return response()->json([

				'success_message' => 'Reservation Already Cancelled',

				'status_code' => '0',

			]);
		}

		//  Host Penalty Details from admin panel
		$host_fee_percentage = Fees::find(2)->value;
		$host_penalty = Fees::find(3)->value;
		$penalty_currency = Fees::find(4)->value;
		$penalty_before_days = Fees::find(5)->value;
		$penalty_after_days = Fees::find(6)->value;
		$penalty_cancel_limits_count = Fees::find(7)->value;
		$host_payout_amount = 0;
		$guest_refundable_amount = 0;
		$host_penalty_amount = 0;

		$cancel_count = Reservation::where('host_id', $user->id)->where('cancelled_by', 'Host')->where('cancelled_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 6 MONTH)'))->get()->count();

		$datetime1 = new DateTime(date('Y-m-d'));
		$datetime2 = new DateTime(date('Y-m-d', strtotime($reservation_details->checkin)));
		$interval_diff = $datetime1->diff($datetime2);
		$interval = $interval_diff->days;

		$per_night_price = $reservation_details->per_night;
		$total_nights = $reservation_details->nights;

		// Additional guest price is added to the per night price for calculation
		$additional_guest_per_night = ($reservation_details->additional_guest / $total_nights);
		$per_night_price = $per_night_price + $additional_guest_per_night;

		$total_night_price = $per_night_price * $total_nights;
		if ($interval_diff->invert) // To check the check in is less than today date
		{
			$spend_night_price = $per_night_price * ($interval <= $total_nights ? $interval : $total_nights);
			$remain_night_price = $per_night_price * (($total_nights - $interval) > 0 ? ($total_nights - $interval) : 0);
		} else {
			$spend_night_price = 0;
			$remain_night_price = $total_night_price;
		}

		$additional_guest_price = /*$reservation_details->additional_guest*/0;
		$cleaning_fees = $reservation_details->cleaning;
		$security_deposit = /*$reservation_details->security*/0;
		$coupon_amount = $reservation_details->coupon_amount;
		$service_fee = $reservation_details->service;
		$host_payout_ratio = (1 - ($host_fee_percentage / 100));

		if (!$interval_diff->invert) // Cancel before checkin
		{
			$refund_night_price = $total_night_price;
			$guest_refundable_amount = array_sum([
				$refund_night_price,
				$additional_guest_price,
				$cleaning_fees,
				$security_deposit,
				-$coupon_amount,
				$service_fee,
			]);

			$payout_night_price = 0;
			$host_payout_amount = array_sum([
				$payout_night_price,
			]);

			if ($cancel_count >= $penalty_cancel_limits_count && $host_penalty == 1) {
				if ($interval > 7) {
					$host_penalty_amount = $this->payment_helper->currency_convert($penalty_currency, $reservation_details->currency_code, $penalty_before_days);
				} else {
					$host_penalty_amount = $this->payment_helper->currency_convert($penalty_currency, $reservation_details->currency_code, $penalty_after_days);
				}
			}
		} else // Cancel after checkin
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

			if ($cancel_count >= $penalty_cancel_limits_count && $host_penalty == 1) {
				$host_penalty_amount = $this->payment_helper->currency_convert($penalty_currency, $reservation_details->currency_code, $penalty_after_days);
			}
		}

		$host_fee = ($host_payout_amount * ($host_fee_percentage / 100));
		$host_payout_amount = $host_payout_amount * $host_payout_ratio;

		$this->payment_helper->payout_refund_processing($reservation_details, $guest_refundable_amount, $host_payout_amount, $host_penalty_amount);

		if (!$interval_diff->invert) // Revert travel credit if cancel before checkin
		{
			$this->payment_helper->revert_travel_credit($reservation_details->id);
		}

		// Update Calendar, delete stayed date
		$days = $this->get_days($reservation_details->checkin, $reservation_details->checkout);
		for ($j = 0; $j < count($days) - 1; $j++) {
			$calendar_detail = Calendar::where('room_id', $reservation_details->room_id)->where('date', $days[$j]);
			if ($calendar_detail->get()->count()) {
				$calendar_row = $calendar_detail->first();
				$calendar_price = $calendar_row->price;
				$calendar_row->spots_booked = $calendar_row->spots_booked - $reservation_details->number_of_guests;
				$calendar_row->save();
				if ($calendar_row->spots_booked <= 0) {
					if ($calendar_price != "0") {
						$calendar_row->status = 'Available';
						$calendar_row->save();
					} else {
						$calendar_row->delete();
					}
				}
			}
		}

		$messages = new Messages;
		$messages->room_id = $reservation_details->room_id;
		$messages->reservation_id = $reservation_details->id;
		$messages->user_to = $reservation_details->user_id;
		$messages->user_from = $user->id;
		$messages->message = $this->helper->phone_email_remove($request->cancel_message);
		$messages->message_type = 11;
		$messages->save();

		$cancel = Reservation::find($request->reservation_id);
		$cancel->host_fee = $host_fee;
		$cancel->cancelled_by = "Host";
		$cancel->cancelled_reason = $request->cancel_reason;
		$cancel->cancelled_at = date('Y-m-d H:m:s');
		$cancel->status = "Cancelled";
		$cancel->updated_at = date('Y-m-d H:m:s');
		$cancel->save();

		$email_controller = new EmailController;
		$email_controller->cancel_host($cancel->id);

		//$this->helper->flash_message('success', 'Reservation Successfully Cancelled');
		// return redirect('my_reservations');
		Session::forget('currency');

		return response()->json([

			'success_message' => 'Reservation Successfully Cancelled',

			'status_code' => '1',

		]);
	}

	/**
	 * Ajax function for Conversation reply
	 *
	 * @param array $request  Input values
	 * @return html Reply message html
	 */
	public function pre_approve(Request $request, EmailController $email_controller) {

		$reservation_details = Reservation::find($request->reservation_id);

		$message = $this->helper->phone_email_remove($request->message);

		if ($reservation_details->user_id == JWTAuth::parseToken()->authenticate()->id) {
			$messages = new Messages;

			$messages->room_id = $reservation_details->room_id;
			$messages->reservation_id = $reservation_details->id;
			$messages->user_to = $reservation_details->rooms->user_id;
			$messages->user_from = JWTAuth::parseToken()->authenticate()->id;
			$messages->message = $message;
			$messages->message_type = 5;

			$messages->save();

			return response()->json([

				'success_message' => 'Message Send Successfully',

				'status_code' => '1',

				//'message_send'    => $message,
				//'message_time'    => $messages->created_time

			]);
		} else if ($reservation_details->rooms->user_id == JWTAuth::parseToken()->authenticate()->id) {
			if ($request->template == 1) {

				$message_type = 6;

				$special_offer = new SpecialOffer;

				$special_offer->reservation_id = $reservation_details->id;
				$special_offer->room_id = $reservation_details->room_id;
				$special_offer->user_id = $reservation_details->user_id;
				$special_offer->checkin = $reservation_details->checkin;
				$special_offer->checkout = $reservation_details->checkout;
				$special_offer->number_of_guests = $reservation_details->number_of_guests;
				$special_offer->price = $reservation_details->subtotal;
				$special_offer->currency_code = JWTAuth::parseToken()->authenticate()->currency_code;
				$special_offer->type = 'pre-approval';
				$special_offer->created_at = date('Y-m-d H:i:s');

				$special_offer->save();

				$reservation_details->status = 'pre-approved';
				$reservation_details->save();

				$special_offer_id = $special_offer->id;

				$email_controller->preapproval($reservation_details->id, $message);
			} else if ($request->template == 2) {
				$message_type = 7;

				$special_offer = new SpecialOffer;

				$rules = array(
					'pricing_price' => 'required|numeric',

					'pricing_room_id' => 'required|exists:rooms,id',

					'pricing_checkin' => 'required|date_format:d-m-Y',

					'pricing_checkout' => 'required|date_format:d-m-Y|after:today|after:pricing_checkin',

					'pricing_guests' => 'required',

					'pricing_guests' => 'required',
				);

				$validator = Validator::make($request->all(), $rules);

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
					$cur_ency = $request->currency;

					$minimum_amount = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, JWTAuth::parseToken()->authenticate()->currency_code, MINIMUM_AMOUNT);

					$currency_symbol = Currency::whereCode(JWTAuth::parseToken()->authenticate()->currency_code)->first()->original_symbol;

					$night_price = $request->pricing_price;

					if ($night_price < $minimum_amount && $night_price != '') {
						return json_encode([

							'success_message' => trans('validation.min.numeric', ['attribute' => 'price', 'min' => $currency_symbol . $minimum_amount]),
							'attribute' => 'price', 'status_code' => '0',
						]);
					} else {

						$special_offer->reservation_id = $reservation_details->id;
						$special_offer->room_id = $request->pricing_room_id;
						$special_offer->user_id = $reservation_details->user_id;
						$special_offer->checkin = $request->pricing_checkin;
						$special_offer->checkout = $request->pricing_checkout;
						$special_offer->number_of_guests = $request->pricing_guests;
						$special_offer->price = $request->pricing_price;
						$special_offer->currency_code = JWTAuth::parseToken()->authenticate()->currency_code;
						$special_offer->type = 'special_offer';
						$special_offer->created_at = date('Y-m-d H:i:s');

						$special_offer->save();

						$special_offer_id = $special_offer->id;

						$email_controller->preapproval($reservation_details->id, $message, 'special_offer');

					}
				}
			} else if ($request->template == 'NOT_AVAILABLE') {
				$message_type = 8;

				$blocked_days = $this->get_days($reservation_details->checkin, $reservation_details->checkout);

				// Update Calendar
				for ($j = 0; $j < count($blocked_days) - 1; $j++) {
					$calendar_data = [
						'room_id' => $reservation_details->room_id,
						'date' => $blocked_days[$j],
						'status' => 'Not available',
						'source' => 'Calendar',
					];

					Calendar::updateOrCreate(['room_id' => $reservation_details->room_id, 'date' => $blocked_days[$j]], $calendar_data);
				}
			} else if ($request->template == 9) {

				$message_type = 8;
				$message = 'Those Dates are Unavailable. ' . $message;

				$reservation_details->status = 'Declined';
				$reservation_details->save();

				//remove pre_approvel from special offer table
				$get_special_offer_id = @Messages::where('reservation_id', $reservation_details->id)

					->where('special_offer_id', '!=', '')->first();
				//print_r(count( $get_special_offer_id));exit;
				if (count($get_special_offer_id) > 0) {

					$id = $get_special_offer_id->special_offer_id;

					$special_offer = SpecialOffer::find($id);

					$reservation_id = $special_offer->reservation_id;
					$type = $special_offer->type;

					$special_offer->delete();

					$messages = Messages::where('special_offer_id', $id)->delete();

				} else {
					$messages = new Messages;

					$messages->room_id = $reservation_details->room_id;
					$messages->reservation_id = $reservation_details->id;
					$messages->user_to = $reservation_details->user_id;
					$messages->user_from = JWTAuth::parseToken()->authenticate()->id;
					$messages->message = $message;
					$messages->message_type = $message_type;
					$messages->special_offer_id = @$special_offer_id;

					$messages->save();

					return response()->json([

						'success_message' => 'Decline Successfully.',

						'status_code' => '1',

					]);
				}
			} else {
				$message_type = 5;
			}
			//$request->reservation_id

			$messages = new Messages;

			$messages->room_id = $reservation_details->room_id;
			$messages->reservation_id = $reservation_details->id;
			$messages->user_to = $reservation_details->user_id;
			$messages->user_from = JWTAuth::parseToken()->authenticate()->id;
			$messages->message = $message;
			$messages->message_type = $message_type;
			$messages->special_offer_id = @$special_offer_id;

			$messages->save();

			if ($message_type == 6) {

				return response()->json([

					'success_message' => 'Pre_approval',
					'status_code' => '1',
					//'first_name'      => $messages->reservation->users->first_name,
					//'room_name'       => $messages->special_offer->rooms->name,
					//'number_of_guests'=> $messages->special_offer->number_of_guests,
					//'currency_symbol' => $messages->special_offer->currency->symbol,
					//'price'           => $messages->special_offer->price,
					//'currency_code'   => $messages->special_offer->currency->session_code,
					//'special_offer_id'=> $messages->special_offer_id,
					//'message_time'    => $messages->created_time

				]);
			} else if ($message_type == 7) {

				return response()->json([

					'success_message' => 'Send Special Offer To Guest',
					'status_code' => '1',
					//'first_name'          => $messages->reservation->users->first_name,
					//'room_id'             => $messages->special_offer->room_id,
					//'room_name'           => $messages->special_offer->rooms->name,
					//'special_offer_dates' => $messages->special_offer->dates,
					//'number_of_guests'    => $messages->special_offer->number_of_guests,
					//'currency_symbol'     => $messages->special_offer->currency->symbol,
					//'price'               => $messages->special_offer->price,
					//'currency_code'       => $messages->special_offer->currency->session_code,
					//'special_offer_id'    => $messages->special_offer_id

				]);

			} else if ($message_type == 8) {

				return response()->json([

					'success_message' => 'Decline Successfully',
					'status_code' => '1',

				]);

			}

			return response()->json([

				'success_message' => 'Message Send Successfully',
				'status_code' => '1',
				//'first_name'         => JWTAuth::parseToken()->authenticate()->first_name,
				//'profile_picture'   =>  JWTAuth::parseToken()->authenticate()->profile_picture->src,
				//'message'            =>  $message,
				//'message_create_time'=>  $messages->created_time,
				//'message_created_at' =>  $messages->created_at

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
		$aDays[] = $sStartDate;

		$sCurrentDate = $sStartDate;

		while ($sCurrentDate < $sEndDate) {
			$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

			$aDays[] = $sCurrentDate;
		}

		return $aDays;
	}

}
