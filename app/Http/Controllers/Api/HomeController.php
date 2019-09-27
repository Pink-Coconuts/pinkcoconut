<?php

/**
 * Home Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Home
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Amenities;
use App\Models\Calendar;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Models\RoomsDescription;
use App\Models\RoomsPrice;
use App\Models\RoomsStepsStatus;
use App\Models\SavedWishlists;
use App\Models\User;
use Auth;
use DateTime;
use DB;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class HomeController extends Controller {
	protected $payment_helper; // Global variable for Helpers instance

	/**
	 * Constructor to Set PaymentHelper instance in Global variable
	 *
	 * @param array $payment   Instance of PaymentHelper
	 */
	protected $helper; // Global variable for Helpers instance
	public function __construct(PaymentHelper $payment) {
		$this->payment_helper = $payment;
		$this->helper = new Helpers;
	}
	/**
	 *Display Currency List
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */

	public function currency_list(Request $request) {
		//Get Currency Details
		$currency_details = Currency::where('status', 'Active')->select('code', 'symbol')

			->orderBy('code', 'asc')->get()->toArray();

		//Store Currency Code and Symbol On Array Format
		foreach ($currency_details as $currency) {

			$currency_list[] = array(
				'code' => $currency['code'],

				'symbol' => $currency['original_symbol'],
			);
		}
		if (!empty($currency_list)) {

			return response()->json([

				'success_message' => 'Currency Details Listed Successfully',

				'status_code' => '1',

				'currency_list' => $currency_list,

			]);
		} else {
			return response()->json([

				'success_message' => 'Currency Details Not Found',

				'status_code' => '0',

			]);
		}

	}
	/**
	 *Display Country List
	 *
	 * @param Get method request inputs
	 * @return @return Response in Json
	 */
	public function country_list(Request $request) {

		$data = Country::select(
			'id as country_id',
			'long_name as country_name',
			'short_name as country_code'
		)->get();

		return response()->json([

			'success_message' => 'Country Listed Successfully',

			'status_code' => '1',

			'country_list' => $data,

		]);

	}

	/**
	 *Display Country List
	 *
	 * @param Get method request inputs
	 * @return @return Response in Json
	 */
	public function stripe_supported_country_list(Request $request) {

		$data = Country::select(
			'id as country_id',
			'long_name as country_name',
			'short_name as country_code'
		)->where('stripe_country','Yes')->get();

		$data = $data->map(function($data){
			return [

			'country_id' => $data->country_id,
			'country_name' => $data->country_name,
			'country_code' => $data->country_code,
			'currency_code'	=> $this->helper->getStripeCurrency($data->country_code),

			];
		});
		
		return response()->json([

			'success_message' => 'Country Listed Successfully',

			'status_code' => '1',

			'country_list' => $data,

		]);

	}
	/**
	 *Display Amenities List
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function amenities_list(Request $request) {
		//Get Active  Amenities list
		$amenities_list['data'] = Amenities::whereStatus('Active')

			->get()->toArray();

		$data_success = array(

			'success_message' => 'Amenities Details Listed Successfully',

			'status_code' => '1',

		);

		return json_encode(

			array_merge($data_success, $amenities_list),

			JSON_UNESCAPED_SLASHES

		);

	}
	/**
	 *Update Amenities List
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function update_amenities(Request $request) {

		$rules = array('room_id' => 'required|exists:rooms,id');

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
			$user = JWTAuth::parseToken()->authenticate();

			$rooms_info = Rooms::where('id', $request->room_id)->first();
			//check valid user or not
			if ($user->id == $rooms_info->user_id) {

				$Update_amenities = Rooms::where('id', '=', $request->room_id)->first();

				$Update_amenities->amenities = $request->selected_amenities;

				$Update_amenities->save(); //save amenities

				return response()->json([

					'success_message' => 'Room Amenities Was Updated Successfully',

					'status_code' => '1']);
			} else {

				return response()->json([

					'success_message' => 'Permission Denied',

					'status_code' => '0']);
			}

		}
	}
	/**
	 *Load Wishlist
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function add_whishlist(Request $request) {

		$rules = array('room_id' => 'required|exists:rooms,id');

		$niceNames = array('room_id' => 'Room Id');

		$messages = array('required' => ':attribute is required.');

		$validator = Validator::make($request->all(), $rules, $messages);

		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return response()->json([
				'success_message' => 'Invalid Room Id',

				'status_code' => '0',

			]);
		} else {
			$user = JWTAuth::parseToken()->authenticate();

			$result = SavedWishlists::where('user_id', $user->id)->where('room_id', $request->room_id)->get()->first();
			//check wishlist count
			if ((!empty($result)) && $result->wishlist_id == 1) {
				SavedWishlists::find($result->id)->delete();

				return response()->json([

					'success_message' => 'Wishlist Was Removed Successfully',

					'status_code' => '1',

				]);
			} elseif (empty($result)) {
				$save_wishlist = new SavedWishlists;

				$save_wishlist->room_id = $request->room_id;

				$save_wishlist->wishlist_id = 1;

				$save_wishlist->user_id = $user->id;

				$save_wishlist->save(); //add wishlist

				return response()->json([

					'success_message' => 'Wishlist Was Added Successfully',

					'status_code' => '1',

				]);
			}
		}
	}
	/**
	 *Update Calendear
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function update_calendar(Request $request) {
		$rules = array(

			'room_id' => 'required|exists:rooms,id',

			'start_date' => 'required|date_format:d-m-Y',

			'end_date' => 'required|date_format:d-m-Y|after:today|after:start_date',

			'is_avaliable_selected' => 'required',

			'nightly_price' => 'required|numeric',

		);

		$niceNames = array(

			'room_id' => 'Room Id',

			'start_date' => 'Start Date',

			'end_date' => 'End Date',

			'is_avaliable_selected' => 'Is Avaliable Selected',

			'nightly_price' => 'Nightly Price',

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
		}

		$user = JWTAuth::parseToken()->authenticate();

		$rooms_info = Rooms::where('id', $request->room_id)->first();
		//check valid user or not
		if (@$user->id != @$rooms_info->user_id) {
			return response()->json([
				'success_message' => 'Permission Denied',

				'status_code' => '0',
			]);
		}
		//validation for available select
		if ($request->is_avaliable_selected != 'Yes' && $request->is_avaliable_selected != 'No') {
			return response()->json([

				'success_message' => 'Is Avaliable Selected Only Allow Yes or No',

				'status_code' => '0',

			]);
		}
		//get room price details
		$price_currencycode = @RoomsPrice::where('room_id', $request->room_id)->first();

		if ($price_currencycode->currency_code != null) {
			$currency_code = @$price_currencycode->currency_code;

		} else {
			$currency_code = $user->currency_code;

			$UpdateDetails = RoomsPrice::where('Room_id', '=', $request->room_id)->first();

			$UpdateDetails->currency_code = $user->currency_code;

			$UpdateDetails->save(); //update room currency code

		}

		$rate = Currency::whereCode($currency_code)->first()->rate;

		// $minimum_price=round($rate *MINIMUM_AMOUNT);
		$minimum_price = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $currency_code, MINIMUM_AMOUNT);
		//validate minimum night price
		if ($request->nightly_price < $minimum_price) {
			return response()->json([

				'success_message' => 'Nightly Price Must Be Minimum' . '-' . $minimum_price . '-' . $currency_code,

				'status_code' => '0',

			]);
		}

		//check is avaliable selected is no or not
		$status = $request->is_avaliable_selected == 'No' ? 'Not available' : 'Available';

		$room_price = RoomsPrice::where('room_id', $request->room_id)->first()->original_night;

		$start_date = date('Y-m-d', strtotime($request->start_date));
		$start_date = strtotime($start_date);

		$end_date = date('Y-m-d', strtotime($request->end_date));
		$end_date = strtotime($end_date);

		if ($request->nightly_price && $request->nightly_price - 0 > 0) {
			for ($i = $start_date; $i <= $end_date; $i += 86400) {
				$date = date("Y-m-d", $i);

				$is_reservation = Reservation::whereRoomId($request->room_id)->whereRaw('status!="Declined"')->whereRaw('status!="Expired"')->whereRaw('status!="Cancelled"')->whereRaw('(checkin = "' . $date . '" or (checkin < "' . $date . '" and checkout > "' . $date . '")) ')->get()->count();
				if ($is_reservation == 0) {
					$data = ['room_id' => $request->room_id,
						'price' => ($request->nightly_price) ? $request->nightly_price : '0',
						'status' => "$status",
						'source' => 'Calendar',
					];
					Calendar::updateOrCreate(['room_id' => $request->room_id, 'date' => $date], $data);
					//remove from calendar table when set available same price as room price
					if ($status == "Available" && $room_price == $request->nightly_price) {
						Calendar::where('room_id', $request->room_id)->where('date', $date)->delete();
					}
				}
			}
		}

		return response()->json([

			'success_message' => $request->is_avaliable_selected == 'No' ? 'Dates Blocked Successfully' : 'Room Dates Was Unblocked Successfully',

			'status_code' => '1',

		]);
	}
/**
 * Get days between two dates
 *
 * @param date $sStartDate  Start Date
 * @param date $sEndDate    End Date
 * @return array $days      Between two dates
 */
	public function get_days($sStartDate, $sEndDate) {
		$sStartDate = date("Y-m-d", strtotime($sStartDate));
		$sEndDate = date("Y-m-d", strtotime($sEndDate));
		$aDays[] = $sStartDate;
		$sCurrentDate = $sStartDate;

		while ($sCurrentDate < $sEndDate) {
			$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
			$aDays[] = $sCurrentDate;
		}

		return $aDays;
	}
	/**
	 *Display Pending Request Resource
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function home_pending_request(Request $request) {
		$user_details = JWTAuth::parseToken()->authenticate();

		$reservation_details = Reservation::where([

			'type' => 'reservation',

			'host_id' => $user_details->id,

			'status' => 'Pending',

		])->get()->toArray();

		if (empty($reservation_details)) {
			return response()->json([

				'success_message' => 'No Pending Request',

				'status_code' => '0',

			]);

		}

		foreach ($reservation_details as $result) {
			$users_where['users.status'] = 'Active';

			$rooms_details = @Rooms::with(['rooms_address' => function ($query) {},

				'users' => function ($query) use ($users_where) {
					$query->with('profile_picture')->where($users_where);

				}])

				->where('rooms.id', $result['room_id'])

				->where('status', 'Listed')->first()->toArray();

			$user_details = @User::with(['profile_picture' => function ($query) {}])

			//->where('users.status','Active')

				->where('users.id', $result['user_id'])

				->first()->toArray();
			//remove rooms address null value
			if ($rooms_details['rooms_address']['address_line_1'] != '' &&
				$rooms_details['rooms_address']['address_line_2'] != '') {
				$address = $rooms_details['rooms_address']['address_line_1'] . ',' . $rooms_details['rooms_address']['address_line_2'];

			} elseif ($address = $rooms_details['rooms_address']['address_line_1'] == '') {

				$address = $rooms_details['rooms_address']['address_line_2'];

			} else {

				$address = $rooms_details['rooms_address']['address_line_1'];

			}

			$Pending[] = array(

				'pending_status' => $result['status'],

				'booking_date' => $result['checkin'] . '-' . $result['checkout'],

				'room_type ' => $rooms_details['room_type_name'],

				'room_id' => $rooms_details['id'],

				'address' => $address,

				'city' => $rooms_details['rooms_address']['city'],

				'guest_user_thumb_image' => $user_details['profile_picture']['src'],

				'guest_user_name' => $user_details['full_name'],

				'total_paid' => $result['total'],

			);

		}

		return json_encode([

			'success_message' => 'Pending Request Successfully Listed',

			'status_code' => '1',

			'pending_data' => $Pending,

		], JSON_UNESCAPED_SLASHES);
	}
	/**
	 *Display Rooms Calendar Resourse
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function rooms_list_calendar(Request $request) {

		$user = JWTAuth::parseToken()->authenticate();

		$rooms_details = Rooms::with(['calendar' => function ($query) {
			$query->where('date', '>=', date('Y-m-d'));
		}, 'rooms_address', 'rooms_price'], 'rooms_price')

			->where('user_id', $user->id)

			->where('rooms.status', 'Listed')->get();

		if (count($rooms_details) < 1) {

			return response()->json([
				'success_message' => 'No Data Found',

				'status_code' => '0',
			]);

		}

		foreach ($rooms_details as $result_data) {
			if ($result_data->steps_count == 0) {
				//get reservat dates
				$get_reservation_date = Reservation::where('checkout', '>=', date('Y-m-d'))

					->where('room_id', $result_data->id)

					->Where('code', '!=', '')

					->whereRaw('status!="Declined"')->whereRaw('status!="Expired"')->whereRaw('status!="Cancelled"')->get();

				$reserved_dates = array();

				foreach ($get_reservation_date as $reservation_date) {
					$days = $this->get_days($reservation_date->checkin, $reservation_date->checkout);
					// Update Calendar
					for ($j = 0; $j < count($days) - 1; $j++) {
						$createDate = new DateTime($days[$j]);

						if ($createDate->format('Y-m-d') >= date('Y-m-d')) {
							@$reserved_dates[] = $createDate->format('d-m-Y');
						}

					}
				}

				$blocked_dates = array();

				foreach ($result_data->calendar as $date) {

					$validate_date = date('d-m-Y', strtotime($date['date']));
					//check calender dates not in reservation dates
					if (!@in_array($validate_date, $reserved_dates)) {

						if (date('Y-m-d', strtotime($date['date'])) >= date('Y-m-d')) {
							$blocked_dates[] = date('d-m-Y', strtotime($date['date']));
						}

					}

				}

				$changed_prices = array();
				//get nighlty price changed in calendar
				$changed_price = @Calendar::where('room_id', $result_data->id)

					->where('price', '!=', 0)

					->where('date', '>=', date('Y-m-d'))

					->orderBy('date', 'asc')->get();

				foreach ($changed_price as $get_date) {
					@$createDate = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($get_date->date)));
					//validate not allow rooms original price in nightly price
					if (@$result_data->rooms_price->original_night != @$get_date->price) {

						@$changed_prices[] = $createDate->format('d-m-Y') . '*' . $get_date->price;
					}
				}

				$currency_details = @Currency::where('code', $result_data->rooms_price->currency_code)->first();

				$result[] = array(

					'room_id' => $result_data->id,

					'room_price' => @$result_data->rooms_price

						->original_night != null

					? @$result_data->rooms_price->original_night : 0,

					'nightly_price' => @$changed_prices != null

					? @$changed_prices : array(),

					'room_type' => @$result_data->room_type_name != ''

					? $result_data->room_type_name : '',

					'room_name' => @$result_data->name != ''

					? $result_data->name : '',

					'room_thumb_images' => @$result_data->photo_name != ''

					? array($result_data->photo_name) : array(),

					'remaining_steps' => (string) $result_data->steps_count,

					'room_location' => @$result_data->rooms_address->state . ',' .

					@$result_data->rooms_address->country_name,

					'blocked_dates' => @$blocked_dates != '' ? $blocked_dates : array(),

					'reserved_dates' => @$reserved_dates != null

					? @$reserved_dates : array(),

					'room_currency_symbol' => @$currency_details->original_symbol != null

					? @$currency_details->original_symbol : '',

					'room_currency_code' => @$result_data->rooms_price->currency_code != null

					? @$result_data->rooms_price->currency_code : '',

				);

			}
		}

		return json_encode(

			array(

				'success_message' => 'Rooms Details List Successfully',

				'status_code' => '1',

				'data' => $result,

			)

			, JSON_UNESCAPED_SLASHES);

	}
	/**
	 *Update Room House Rules
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function update_house_rules(Request $request) {
		$rules = array('room_id' => 'required|exists:rooms_description,room_id');

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
			$user = JWTAuth::parseToken()->authenticate();

			$rooms_info = Rooms::where('id', $request->room_id)->first();
			//check valid user or not
			if ($user->id == $rooms_info->user_id) {

				$UpdateDetails = RoomsDescription::where('Room_id', '=', $request->room_id)->first();

				$UpdateDetails->house_rules = $request->house_rules;

				$UpdateDetails->save(); //save roomsdescription

				return response()->json([

					'success_message' => 'Room House Rules Updated Successfully',

					'status_code' => '1']);
			} else {

				return response()->json([

					'success_message' => 'Permission Denied',

					'status_code' => '0',

				]);

			}
		}

	}
	/**
	 *Update Room Description
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function update_description(Request $request) {
		$where = '';

		$description_type = '';

		$validate = '';

		if ($request->room_id != '') {

			if ($request->space) {
				$description_type = $request->space;

				$where = 'space';

				$validate = 'space';
			} elseif ($request->guest_access) {
				$description_type = $request->guest_access;

				$where = 'access';

				$validate = 'guest_access';
			} elseif ($request->interaction_guests) {
				$description_type = $request->interaction_guests;

				$where = 'interaction';

				$validate = 'interaction_guests';
			} elseif ($request->notes) {

				$description_type = $request->notes;

				$where = 'notes';

				$validate = 'notes';

			} elseif ($request->house_rules) {
				$description_type = $request->house_rules;

				$where = 'house_rules';

				$validate = 'house_rules';
			} elseif ($request->neighborhood_overview) {
				$description_type = $request->neighborhood_overview;

				$where = 'neighborhood_overview';

				$validate = 'neighborhood_overview';
			} elseif ($request->getting_arround) {
				$description_type = $request->getting_arround;

				$where = 'transit';

				$validate = 'getting_arround';
			} else {
				//get room description
				$rules = array('room_id' => 'required|exists:rooms_description,room_id');
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
					$user = JWTAuth::parseToken()->authenticate();

					$rooms_info = Rooms::where('id', $request->room_id)->first();
					//check valid user or not
					if ($user->id == $rooms_info->user_id) {
						$result = RoomsDescription::where('Room_id', '=', $request->room_id)->first();

						return response()->json([

							'success_message' => 'Room Description Listed Successfully',

							'status_code' => '1',

							'space_msg' => $result->space != '' ? $result->space : '',

							'guest_access_msg' => $result->access != '' ? $result->access : '',

							'interaction_with_guest_msg' => $result->interaction != ''

							? $result->interaction : '',

							'overview_msg' => $result->neighborhood_overview != ''

							? $result->neighborhood_overview : '',

							'getting_arround_msg' => $result->transit != '' ? $result->transit : '',

							'other_things_to_note_msg' => $result->notes != '' ? $result->notes : '',

							'house_rules_msg' => $result->house_rules != ''

							? $result->house_rules : '',
						]);
					} else {
						return response()->json([

							'success_message' => 'Permission Denied',

							'status_code' => '0',

						]);
					}

				}
			}
		}
		//update room description
		$rules = array('room_id' => 'required|exists:rooms_description,room_id');

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
			$user = JWTAuth::parseToken()->authenticate();

			$rooms_info = Rooms::where('id', $request->room_id)->first();

			if ($user->id == $rooms_info->user_id) {

				$UpdateDetails = RoomsDescription::where('Room_id', '=', $request->room_id)->first();

				$UpdateDetails->$where = $description_type;

				$UpdateDetails->save();

				return response()->json([

					'success_message' => 'Room Description Update Successfully',

					'status_code' => '1']);
			} else {

				return response()->json([

					'success_message' => 'Permission Denied',

					'status_code' => '0',

				]);
			}

		}

	}
	/**
	 *Update Room Title and Description
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function update_title_description(Request $request) {
		if ($request->room_title != '' && $request->room_description == '') {
			$rules = array(
				'room_id' => 'required|exists:rooms,id',

				'room_title' => 'required',

			);

		} elseif ($request->room_description != '' && $request->room_title == '') {
			$rules = array(
				'room_id' => 'required|exists:rooms,id',

				'room_description' => 'required',
			);

		} elseif ($request->room_description != '' && $request->room_title != '') {
			return response()->json([
				'success_message' => 'You Can\'t Update Both Values',

				'status_code' => '0',

			]);
		} else {
			return response()->json([
				'success_message' => 'Invalid Request',

				'status_code' => '0',

			]);

		}

		$niceNames = array('room_id' => 'Room Id');

		$messages = array(

			'required' => ':attribute is required.',

			'room_title.max' => 'Room title should not more than 35 characters.',

			'room_description.max' => 'Room description should not more than 500 characters.',

		);

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

			$rooms_info = Rooms::where('id', $request->room_id)->first();
			//check valid user or not
			if ($user->id == $rooms_info->user_id) {

				if ($request->room_title != '') {
					//check title count limit
					$title_count = strlen($request->room_title);

					if ($title_count > 35) {

						return response()->json([

							'success_message' => 'Rooms Title Not Longer Then 35 characters',

							'status_code' => '0']);
					}

					$UpdateDetails = Rooms::where('id', '=', $request->room_id)->first();

					$UpdateDetails->name = $this->helper->phone_email_remove($request->room_title);

					$UpdateDetails->save(); //update the rooms title

				}
				if ($request->room_description != '') {
					//check description count limit
					$title_count = strlen($request->room_description);

					if ($title_count > 500) {

						return response()->json([

							'success_message' => 'Room Description Not Longer Then 500 characters',

							'status_code' => '0']);
					}
					$UpdateDetails = Rooms::where('id', '=', $request->room_id)->first();

					$UpdateDetails->summary = $this->helper->phone_email_remove($request->room_description);

					$UpdateDetails->save(); //update rooms description

				}

				$rooms_details_count = Rooms::where('id', '=', $request->room_id)->first();
				//prevent not given rooms title and description in same time
				if ($rooms_details_count->summary != '' && $rooms_details_count->summary != '') {

					DB::table('rooms_steps_status')->whereRoom_id($request->room_id)->update(['description' => 1]);

				}

				$rooms_step_status = RoomsStepsStatus::find($request->room_id);

				$rooms_step_status->description = 1;

				$rooms_step_status->save();

				if ($request->room_title != '') {
					return response()->json([

						'success_message' => 'Room Title Updated Successfully',

						'status_code' => '1']);

				}
				if ($request->room_description != '') {
					return response()->json([

						'success_message' => 'Room Description Updated Successfully',

						'status_code' => '1']);

				}

			} else {

				return response()->json([
					'success_message' => 'Permission Denied',

					'status_code' => '0']);

			}
		}
	}
/**
 *Upade Room calendar
 *
 * @param  Get method inputs
 * @return Response in Json
 */

	public function new_update_calendar(Request $request) {
		$rules = array(

			'room_id' => 'required|exists:rooms,id',

			'blocked_dates' => 'required',

			'is_avaliable_selected' => 'required',

			'nightly_price' => 'required|numeric',

		);

		$niceNames = array(

			'room_id' => 'Room Id',

			'is_avaliable_selected' => 'Is Avaliable Selected',

			'nightly_price' => 'Nightly Price',

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
		}

		//validate is avaliable selected is  yes or not
		if ($request->is_avaliable_selected != 'Yes' && $request->is_avaliable_selected != 'No') {
			return response()->json([
				'success_message' => 'Is Avaliable Selected Only Allow Yes or No',

				'status_code' => '0',
			]);
		}

		$user = JWTAuth::parseToken()->authenticate();

		$rooms_info = @Rooms::where('id', $request->room_id)->first();

		//prevent from other user changes value
		if (@$user->id != @$rooms_info->user_id) {
			return response()->json([
				'success_message' => 'Permission Denied',

				'status_code' => '0',
			]);
		}
		//get room price currency details
		$price_currencycode = @RoomsPrice::where('room_id', $request->room_id)->first();

		if ($price_currencycode->currency_code != null) {
			$currency_code = @$price_currencycode->currency_code;

		} else {
			$currency_code = $user->currency_code;

			$UpdateDetails = RoomsPrice::where('Room_id', '=', $request->room_id)->first();

			$UpdateDetails->currency_code = $user->currency_code;

			$UpdateDetails->save(); // update room currency code

		}

		$rate = Currency::whereCode($currency_code)->first()->rate;

		// $minimum_price=round($rate *MINIMUM_AMOUNT);
		$minimum_price = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $currency_code, MINIMUM_AMOUNT);
		//check rooms nightly price limit
		if ($request->nightly_price < $minimum_price) {
			return response()->json([

				'success_message' => 'Nightly Price Must Be Minimum' . '-' . $minimum_price . '-' . $currency_code,

				'status_code' => '0',

			]);
		}

		//get reservad dates
		$get_reservation_date = @Reservation::where('checkout', '>=', date('Y-m-d'))

			->where('room_id', $request->room_id)

			->Where('code', '!=', '')

			->Where('status', '!=', 'Cancelled')
			->where('type', 'reservation')

			->get();

		foreach ($get_reservation_date as $reservation_date) {
			$days = $this->get_days($reservation_date->checkin, $reservation_date->checkout);

			// Update Calendar
			for ($j = 0; $j < count($days) - 1; $j++) {
				$createDate = new DateTime($days[$j]);

				if ($createDate->format('Y-m-d') >= date('Y-m-d')) {
					@$reserved_dates[] = $createDate->format('d-m-Y');
				}

			}
		}

		$blocked_dates = array();

		$blocked_dates = explode(',', $request->blocked_dates);
		//check is avaliable selected is no or not
		foreach ($blocked_dates as $start_date_org) {
			//update calendar
			$data = $start_date_org;

			$next_date = date('d-m-Y', strtotime($data . ' +1 day'));

			$start_date = $start_date_org;

			$end_date = $next_date;

			$days = $this->get_days($start_date, $end_date);

			// Update Calendar
			$start_date = date('Y-m-d', strtotime($start_date));
			$start_date = strtotime($start_date);

			$end_date = date('Y-m-d', strtotime($end_date));
			$end_date = strtotime($end_date);
			$room_price = RoomsPrice::where('room_id', $request->room_id)->first()->original_night;

			$status = $request->is_avaliable_selected == 'No' ? 'Not available' : 'Available';
			if ($request->nightly_price && $request->nightly_price - 0 > 0) {
				for ($i = $start_date; $i < $end_date; $i += 86400) {
					$date = date("Y-m-d", $i);

					$is_reservation = Reservation::whereRoomId($request->room_id)->whereRaw('status!="Declined"')->whereRaw('status!="Expired"')->whereRaw('status!="Cancelled"')->whereRaw('(checkin = "' . $date . '" or (checkin < "' . $date . '" and checkout > "' . $date . '")) ')->get()->count();
					if ($is_reservation == 0) {
						$data = ['room_id' => $request->room_id,
							'price' => ($request->nightly_price) ? $request->nightly_price : '0',
							'status' => "$status",
							'source' => 'Calendar',
						];
						$calendar = Calendar::updateOrCreate(['room_id' => $request->room_id, 'date' => $date], $data);
						//remove from calendar table when set available same price as room price
						if ($request->status == "Available" && $room_price == $request->nightly_price) {
							Calendar::where('room_id', $request->room_id)->where('date', $date)->delete();
						}
					}
				}
			}
			/*for($j=0; $j<count($days)-1; $j++)
				            {

				             $calendar_data = [
				                                'room_id' => $request->room_id,

				                                'date'    => $days[$j],

				                                'price'   => $request->nightly_price,

				                                'status'  => $request->is_avaliable_selected == 'No' ? 'Not available' : 'Avaliable',
				                                'source'  => 'Calendar',

				                              ];

				             Calendar::updateOrCreate(['room_id' => $request->room_id, 'date' => $days[$j]], $calendar_data);
			*/
		}
		//get blocked dates details
		$blocked_dates_details = @Calendar::where('date', '>=', date('Y-m-d'))

			->where('room_id', $request->room_id)
			->where('status', 'Not available')
			->orderBy('date', 'asc')
			->get();

		$blocked_dates = array();

		foreach ($blocked_dates_details as $date) {

			$date = $date->date;

			$createDate = new DateTime(date('Y-m-d', strtotime($date)));
			// check calender blocked date not in reservation date
			if (!@in_array($createDate->format('d-m-Y'), @$reserved_dates)) {
				if ($createDate->format('Y-m-d') >= date('Y-m-d')) {

					$blocked_dates[] = $createDate->format('d-m-Y');

				}

			}

		}

		$changed_prices = array();
		//get nightly price changed in calendar
		$changed_price = @Calendar::where('room_id', $request->room_id)

			->where('price', '!=', 0)->get();

		foreach ($changed_price as $get_date) {
			@$createDate = new DateTime(date('Y-m-d', strtotime($get_date->date)));
			//prevent nighly price not listed the privious day
			if ($createDate->format('Y-m-d') >= date('Y-m-d')) {
				@$changed_prices[] = $createDate->format('d-m-Y') . '*' . $get_date->price;
			}

		}

		return response()->json([

			'success_message' => $request->is_avaliable_selected == 'No' ? 'Room Calendar Dates Was Blocked Successfully' : 'Room Calendar Dates Was Unblocked Successfully',

			'status_code' => '1',

			'blocked_dates' => @$blocked_dates != null

			? @$blocked_dates : array(),

			'reserved_dates' => @$reserved_dates != null

			? @$reserved_dates : array(),

			'nightly_price' => @$changed_prices != null

			? @$changed_prices : array(),
		]);

	}

}
