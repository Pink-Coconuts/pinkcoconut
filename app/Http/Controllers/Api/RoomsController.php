<?php

/**
 * Rooms Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Rooms
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\Amenities;
use App\Models\BedType;
use App\Models\Calendar;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Messages;
use App\Models\PropertyType;
use App\Models\Reservation;
use App\Models\Reviews;
use App\Models\Rooms;
use App\Models\RoomsAddress;
use App\Models\RoomsAvailabilityRules;
use App\Models\RoomsDescription;
use App\Models\RoomsPhotos;
use App\Models\RoomsPrice;
use App\Models\RoomsPriceRules;
use App\Models\RoomsStepsStatus;
use App\Models\RoomType;
use App\Models\User;
use Auth;
use DateTime;
use DB;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class RoomsController extends Controller {
	protected $payment_helper; // Global variable for Helpers instance

	public function __construct(PaymentHelper $payment) {
		$this->payment_helper = $payment;
		$this->helper = new Helpers;
	}

/**
 * Display Rooms Detaials
 *@param  Get method request inputs
 *
 * @return Response Json
 */
	public function rooms_detail(Request $request) {

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

			$data['room_id'] = $request->room_id;

			if (request('token')) {

				$user_token = JWTAuth::parseToken()->authenticate();

				$currency_code = $user_token->currency_code;
				//Prevent host book rooms
				$rooms_user = Rooms::where('id', $data['room_id'])->first();

				if ($rooms_user->status == "Unlisted") 
				{

						return response()->json([

							'success_message' => 'Room Not available',

							'status_code' => '2',

						]);

			    }

				if ($user_token->id == $rooms_user->user_id) {

					$canbook = 'No';

				} else {

					$canbook = 'Yes';

				}

			} else {

				$currency_code = Currency::where('default_currency', 1)->first()->code;
				$canbook = 'Yes';
			}

			$rooms_details = @Rooms::with(['calendar_data' => function ($query) {
				$query->notAvailable();
				$query->where('date', '>=', date('Y-m-d'));
			},
				'availability_rules',
				'length_of_stay_rules',
				'early_bird_rules',
				'last_min_rules',
			])

				->where('rooms.id', $request->room_id)->first()->toArray();

			//Get Blocked Dates
			foreach ($rooms_details['calendar_data'] as $date) {
				$date = $date['date'];

				$createDate = new DateTime(date('Y-m-d', strtotime($date)));

				$blocked_dates[] = $createDate->format('d-m-Y');
			}

			$data['url'] = url('/') . '/rooms/' . $data['room_id'];

			$data['result'] = Rooms::find($request->room_id);

			@$host_name = @User::with('profile_picture')->where('id', $data['result']

					->user_id)->first();

			if (!empty($data['result']['amenities'])) {

				$amenities_data = explode(",", $data['result']['amenities']);

				foreach ($amenities_data as $value) {

					$amenities_code = Amenities::where('id', $value)->where('status', 'Active')->first();

					if (count($amenities_code)) {
						$amenities_details[] = array('id' => $amenities_code->id, 'name' => $amenities_code->name, 'icon' => $amenities_code->mobile_icon);
					} else {
						$amenities_details = array();
					}

				}

			} else {

				$amenities_details = array();

			}

			$house_rules = RoomsDescription::where('room_id', $request->room_id)->first();

			$result['price_details'] = RoomsPrice::where('room_id', $request->room_id)

				->get()->first()->toArray();

			$currency_details = @Currency::where('code', $currency_code)

				->first();
			if ($currency_details) {
				$currency_details = $currency_details->toArray();
			} else {
				$currency_details = Currency::where('default_currency', 1)

					->first()->toArray();
				$user_token->currency_code = @$currency_details['code'];
				$user_token->save();
			}

			$data['amenities'] = Amenities::selected($request->room_id);

			$data['safety_amenities'] = Amenities::selected_security($request->room_id);

			$data['rooms_photos'] = RoomsPhotos::where('room_id', $request->room_id)->get();

			// Return Default  Image In First Index In Array.
			foreach ($data['rooms_photos'] as $images) {

				if ($images['featured'] == 'Yes') {
					@$image_default[] = $images['name'];
				} else {
					@$image_undefault[] = $images['name'];
				}

			} //merge image
			if (!empty($image_undefault)) {
				@$image_collection = array_merge($image_default, @$image_undefault != null ? $image_undefault : '');
			} else {
				@$image_collection = $image_default;

			}

			//get guest reviews details
			$data['reviews_details'] = @Reviews::where('room_id', $request->room_id)

				->where('review_by', 'guest')->get()->first();
			// get guest user details
			$data['reviews_details_user'] = @User::with(['profile_picture'])

				->where('users.id', $data['reviews_details']

						->user_from)->first();

			$reviews = @Reviews::where('room_id', $request->room_id)

				->where('review_by', 'guest');

			$rating_value = @($reviews->sum('value') / $reviews->count());

			//get host user detials
			$data['reviews_details_host'] = @User::join('profile_picture', function ($join) {

				$join->on('id', '=', 'profile_picture.user_id');

			})

				->where('id', $data['reviews_details']->user_to)

				->where('users.status', 'Active')->get()->first();

			$rooms_address = $data['result']->rooms_address;

			$latitude = $rooms_address->latitude;

			$longitude = $rooms_address->longitude;

			if ($request->checkin != '' && $request->checkout != '') {
				$data['checkin'] = date('m/d/Y', strtotime($request->checkin));
				$data['checkout'] = date('m/d/Y', strtotime($request->checkout));
				$data['guests'] = $request->guests;
			} else {
				$data['checkin'] = '';
				$data['checkout'] = '';
				$data['guests'] = '';
			}
			//get similar rooms list
			$data['similar'] = Rooms::join('rooms_address', function ($join) {
				$join->on('rooms.id', '=', 'rooms_address.room_id');
			})
				->select(DB::raw('*, ( 3959 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) as distance'))
				->having('distance', '<=', 30)
				->where('rooms.id', '!=', $request->room_id)
				->where('rooms.status', 'Listed')
				->whereHas('users', function ($query) {
					$query->where('users.status', 'Active');
				})
				->get();

			// Get Similar List Value
			if (request('token')) {
				foreach ($data['similar'] as $similar) {
					// check wishlist having or not
					$whishlist_count = @DB::table('saved_wishlists')

						->where('user_id', $user_token->id)

						->where('room_id', $similar->id)->count();

					if ($whishlist_count > '0') {

						$whishlist['is_whishlist'] = 'Yes';

					} else {
						$whishlist['is_whishlist'] = 'No';

					}

					$reviews = @Reviews::where('room_id', $similar->id)->where('review_by', 'guest');

					if ($reviews->count() == 0) {
						$data['rating_value'] = '0';
					} else {
						$data['rating_value'] = @($reviews->sum('value') / $reviews->count());
					}

					$result['price'] = RoomsPrice::where('room_id', $similar->id)->get()

						->pluck('night');

					$room_address_details = @RoomsAddress::where('room_id', $similar->id)->first();

					@$similar_list[] = array(
						'room_id' => $similar->id,

						'user_id' => $similar->user_id,

						'room_price' => $result['price']['0'],

						'room_name' => $similar->name,

						'city_name' => @$similar->city != ''

						? $similar->city : $room_address_details->country_name,

						'room_thumb_image' => $similar->photo_name,

						'rating_value' => $data['rating_value'] != null

						? (string) $data['rating_value']

						: '0',

						'reviews_value' => $reviews->count() == 0

						? '0' : (string) $reviews->count(),

						'is_whishlist' => $whishlist['is_whishlist'],

					);

				}
			}

			$data['title'] = $data['result']->name . ' in ' . $data['result']->rooms_address->city;

			$id = $request->room_id;

			$result['not_avilable'] = @Calendar::where('room_id', $id)->notAvailable()->get()->pluck('date');

			$result['price'] = @RoomsPrice::where('room_id', $id)->get()

				->pluck('night');

			if ($data['reviews_details'] != null) {

				$date = $data['reviews_details']['created_at'];

				$createDate = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($date)));

				$data['date'] = $createDate->format('M Y');

			} else {
				$data['date'] = '';
			}

			$availability_rules = array();

			foreach (@$rooms_details['availability_rules'] ?: array() as $key => $rule) {
				$availability_rules[$key] = array_map(function ($v) {
					return (is_null($v)) ? "" : $v;
				}, $rule);
			}

			$rooms_price = RoomsPrice::where('room_id', $rooms_details['id'])->first();

			$rooms_details = array(

				'success_message' => 'Room Detail Listed Successfully',

				'status_code' => '1',

				'can_book' => $canbook,

				'instant_book' => $data['result']['booking_type'] == 'instant_book'

				? 'Yes' : 'No',

				'room_id' => intval($data['room_id']),

				'room_price' => $result['price']['0'],

				'room_name' => $data['result']['name'],

				'room_images' => @$image_collection != null

				? @$image_collection : array(),

				'room_share_url' => $data['url'],

				'is_whishlist' => $rooms_details['overall_star_rating']['is_wishlist'],

				'rating_value' => (string) $rooms_details['overall_star_rating']['rating_value'],

				'host_user_id' => @$host_name['id'] != null

				? $host_name['id'] : '',

				'host_user_name' => @$host_name['full_name'] != null

				? $host_name['full_name'] : '',

				'room_type' => $data['result']['room_type_name'],

				'host_user_image' => $host_name->profile_picture->header_src,

				'no_of_guest' => $data['result']['accommodates'] != null

				? $data['result']['accommodates'] : '',

				'no_of_beds' => $data['result']['beds'] != null

				? $data['result']['beds'] : '',

				'no_of_bedrooms' => $data['result']['bedrooms'] != null

				? $data['result']['bedrooms'] : '',

				'no_of_bathrooms' => $data['result']['bathrooms'] != null

				? $data['result']['bathrooms'] : '',

				'amenities_values' => $amenities_details,

				'locaiton_name' => $data['result']['rooms_address']['city'] . ',' .

				$data['result']['rooms_address']['country'],

				'loc_latidude' => $data['result']['rooms_address']['latitude'],

				'loc_longidude' => $data['result']['rooms_address']['longitude'],

				'review_user_name' => @$data['reviews_details_user']->full_name != null

				? @$data['reviews_details_user']->full_name : '',

				'review_user_image' => @$data['reviews_details_user']

					->profile_picture->src != null

				? @$data['reviews_details_user']

					->profile_picture->src : '',

				'review_date' => $data['date'] != null ? $data['date'] : '',

				'review_message' => $data['reviews_details']['comments'] != null

				? $data['reviews_details']['comments'] : '',

				'review_count' => $data['result']['reviews_count'] != null

				? $data['result']['reviews_count'] : '',

				'room_detail' => $data['result']['summary'],

				'space' => $data['result']->rooms_description->space != '' ? $data['result']->rooms_description->space : '',

				'access' => $data['result']->rooms_description->access != 'null' ? $data['result']->rooms_description->access : '',

				'interaction' => $data['result']->rooms_description->interaction != 'null' ? $data['result']->rooms_description->interaction : '',

				'notes' => $data['result']->rooms_description->notes != 'null' ? $data['result']->rooms_description->notes : '',

				'house_rules' => $data['result']->rooms_description->house_rules != 'null' ? $data['result']->rooms_description->house_rules : '',

				'neighborhood_overview' => $data['result']->rooms_description->neighborhood_overview != 'null' ? $data['result']->rooms_description->neighborhood_overview : '',

				'getting_around' => $data['result']->rooms_description->transit != 'null' ? $data['result']->rooms_description->transit : '',

				'cancellation_policy' => $data['result']['cancel_policy'],

				/*'weekly_price'       =>  $result['price_details']['week']>0

					                                      ? $result['price_details']['week']

					                                      : $result['price']['0']*7,

					             'monthly_price'      =>  $result['price_details']['month']>0

					                                      ? $result['price_details']['month']

				*/

				'cleaning' => $result['price_details']['cleaning'] > 0

				? $result['price_details']['cleaning'] : 0,

				'additional_guest' => $result['price_details']['additional_guest'] > 0

				? $result['price_details']['additional_guest'] : 0,

				'guests' => $result['price_details']['guests'] > 0

				? $result['price_details']['guests'] : 0,

				'security' => $result['price_details']['security'] > 0

				? $result['price_details']['security'] : 0,

				'weekend' => $result['price_details']['weekend'] > 0

				? $result['price_details']['weekend'] : 0,

				'house_rules' => $house_rules ? $house_rules->house_rules : '',

				'currency_code' => @$currency_details['code'],

				'currency_symbol' => @$currency_details['original_symbol'],

				'blocked_dates' => @$blocked_dates != null ? $blocked_dates : array(),

				'similar_list_details' => @$similar_list != null

				? @$similar_list : array(),
				'availability_rules' => @$availability_rules ?: array(),
				'length_of_stay_rules' => @$rooms_details['length_of_stay_rules'] ?: array(),
				'early_bird_rules' => @$rooms_details['early_bird_rules'] ?: array(),
				'last_min_rules' => @$rooms_details['last_min_rules'] ?: array(),
				'minimum_stay' => @$rooms_price->minimum_stay ?: '',
				'maximum_stay' => @$rooms_price->maximum_stay ?: '',

				'is_shared' => @$data['result']['is_shared'],
			);

			return json_encode($rooms_details, JSON_UNESCAPED_SLASHES);
		}
	}
	/**
	 * Display Review Resource
	 *@param  Get method request inputs
	 *
	 * @return Response in Json
	 */
	public function review_detail(Request $request) {

		$rules = array(
			'room_id' => 'required|exists:rooms,id',

			'page' => 'required|numeric|min:1',
		);

		$niceNames = array('room_id' => 'Room Id', 'page' => 'Page No');

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
			
			//get review details
			$reviews = Reviews::where('room_id', $request->room_id)

				->where('review_by', 'guest');

			$accuracy_value = @($reviews->sum('accuracy') / $reviews->count());

			$check_in_value = @($reviews->sum('checkin') / $reviews->count());

			$cleanliness_value = @($reviews->sum('cleanliness') / $reviews->count());

			$communication_value = @($reviews->sum('communication') / $reviews->count());

			$location_value = @($reviews->sum('location') / $reviews->count());

			$value = @($reviews->sum('value') / $reviews->count());

			$total_review = @$reviews->count();

			$result_reviews = $reviews->orderByRaw('RAND(1234)')->paginate(20)->toJson();

			$data_result = json_decode($result_reviews, true);

			$count = count($data_result['data']);

			if (empty($count)) {
				return response()->json([

					'success_message' => 'No Reviews Found',

					'status_code' => '0',
				]);
			}
			for ($i = 0; $i < $count; $i++) {

				$user_from = $data_result['data'][$i]['user_from'];

				$reviews_users = @User::with(['profile_picture'])->where('users.id', $user_from)

					->first()->toArray();

				$date = $data_result['data'][$i]['created_at'];

				$createDate = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($date)));

				$data['date'] = $createDate->format('M Y');

				@$result[] = array(

					'review_user_name' => $reviews_users['full_name'] != null

					? $reviews_users['full_name'] : '',

					'review_user_image' => $reviews_users['profile_picture']['header_src'],

					'review_date' => $data['date'] != null

					? $data['date']

					: '',

					'review_message' => $data_result['data'][$i]['comments'] != null

					? $data_result['data'][$i]['comments']

					: '',
				);
			}

			if ($count > 0) {
				return response()->json([
					'success_message' => 'Reviews Detail Listed Successfully',

					'status_code' => '1',

					'total_review' => $total_review > 0

					? (string) $total_review : 0,

					'accuracy_value' => $accuracy_value > 0

					? (string) $accuracy_value : 0,

					'check_in_value' => $check_in_value > 0

					? (string) $check_in_value : 0,

					'cleanliness_value' => $cleanliness_value > 0

					? (string) $cleanliness_value : 0,

					'communication_value' => $communication_value > 0

					? (string) $communication_value : 0,

					'location_value' => $location_value > 0

					? (string) $location_value : 0,

					'value' => $value > 0 ? (string) $value : 0,

					'data' => $result]);
			}

		}
	}
	/**
	 * Calendar Availability Check
	 *@param  Get method request inputs
	 *
	 * @return Response in Json
	 */
	public function calendar_availability(Request $request) {
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

				'status_code' => '0',

			]);

		} else {

			$date_check = date('Y-m-d');

			$rooms_count = Rooms::where('id', $request->room_id)

				->where('status', 'Listed')->get()->toArray();

			if ($request->room_id != '' && !empty($rooms_count)) {

				$data = Calendar::where('room_id', $request->room_id)

					->notAvailable()

					->where('date', '>=', $date_check)

					->get()->pluck('date')->toArray();

				if (!empty($data)) {
					$data = array(

						'success_message' => 'Calendar Blocked Dates Listed Successfully',

						'status_code' => '1',

						'blocked_dates' => $data,

					);

					return response()->json($data);
				} else {

					$data = array(

						'success_message' => 'No Data Found',

						'status_code' => '0',

					);

				}
				return response()->json($data);
			} elseif ($request->room_id == '') {
				return response()->json([

					'success_message' => 'Undefind Room Id',

					'status_code' => '0',

				]);
			} elseif (empty($rooms_count)) {
				return response()->json([

					'success_message' => 'Invalid Room Id',

					'status_code' => '0',

				]);
			}

		}

	}
	/**
	 * Calendar Availability Status Check
	 *@param  Get method request inputs
	 *
	 * @return Response in Json
	 */
	public function calendar_availability_status(Request $request) {
		$rules = array(

			'room_id' => 'required|exists:rooms,id',

			'start_date' => 'required|date_format:d-m-Y',

			'end_date' => ' required|date_format:d-m-Y|after:today|after:start_date',
			'total_guest' => 'required|integer',

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
			//get room count
			$rooms_count = Rooms::where('id', $request->room_id)->where('status', 'Listed')

				->get()->count();

			if ($rooms_count) {

				//Check Dates are Aviable or not
				$data = $this->payment_helper->price_calculation(
					$request->room_id,
					$request->start_date,
					$request->end_date,
					$request->total_guest,
					'', ''
				);

				$data = json_decode($data, TRUE);

				$result = @$data['status'];

				if ((isset($data['status'])) && ($result == 'Not available')) {

					return response()->json([
						'success_message' => @$data['error'] ?: 'Room Date Is Not Available',

						'status_code' => '0',
					]);
				} else {
					return response()->json([
						'success_message' => 'Room Date Is Available',

						'status_code' => '1',

						'pernight_price' => $data['rooms_price'],

						'availability_msg' => 'Rooms Available',
					]);

				}

			} else {
				return response()->json([

					'success_message' => 'Invalid Room Id',

					'status_code' => '0',

				]);

			}
		}
	}
/**
 * Display House Rules
 *
 * @param  Get method request inputs
 * @return Response in Json
 */
	public function house_rules(Request $request) {

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

			$house_rules = RoomsDescription::where('room_id', $request->room_id)

				->pluck('house_rules');
			if (!empty($house_rules)) {

				return response()->json([

					'success_message' => 'House Rules Details',

					'status_code' => '1',

					'house_rules' => $house_rules,

				]);
			} else {

				return response()->json([
					'success_message' => 'No House Rules',

					'status_code' => '0',
				]);
			}

		}

	}

	/**
	 *Display Map Listing Resource
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function maps(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		$currency_symbol = @Currency::where('code', $user_details->currency_code)->first();

		$data = Rooms::with(['rooms_price', 'rooms_address'])

			->where('rooms.status', 'Listed')->get();

		foreach ($data as $value) {

			$maps_details[] = array(

				'room_id' => $value->id,

				'instant_book' => $value->booking_type == 'instant_book'

				? 'Yes' : 'No',

				'room_price' => $value->rooms_price->night != null

				? $value->rooms_price->night : '',

				'room_type' => $value->room_type_name != null

				? $value->room_type_name : '',

				'room_name' => $value->name != null

				? $value->name : '',

				'room_thumb_image' => $value->photo_name != null

				? $value->photo_name : '',

				'rating_value' => $value['overall_star_rating']

				['rating_value'] != null

				? (string) $value['overall_star_rating']

				['rating_value']
				: '0',
				'reviews_count' => $value['reviews_count'] != null

				? (string) $value['reviews_count'] : '0',

				'is_whishlist' => $value['overall_star_rating']

				['is_wishlist'] != null

				? $value['overall_star_rating']

				['is_wishlist'] : '',

				'loc_latidude' => $value->rooms_address->latitude != null

				? $value->rooms_address->latitude : '',

				'loc_longidude' => $value->rooms_address->longitude != null

				? $value->rooms_address->longitude : '',

				'country_name' => $value->rooms_address->country_name,

				'currency_code' => $user_details->currency_code,

				'currency_symbol' => $currency_symbol->original_symbol,

			);
		}

		if (!empty($data)) {
			return response()->json([

				'success_message' => 'Maps Details Listed Successfully',

				'status_code' => '1',

				'maps_details' => $maps_details,

			]);
		} else {
			return response()->json([

				'success_message' => 'No Rooms Available',

				'status_code' => '0',

			]);
		}
	}

	/**
	 *  To Update the Price Rule
	 *
	 * @param  Get method inputs [id, room_id, type, period, discount]
	 * @return Response in Json
	 */
	public function update_price_rule(Request $request) {
		$rules = [
			'id' => 'exists:rooms_price_rules,id,room_id,' . $request->room_id . ',type,' . $request->type,
			'room_id' => 'required|exists:rooms,id',
			'type' => 'required|in:length_of_stay,early_bird,last_min',
			'period' => 'required|integer|unique:rooms_price_rules,period,' . @$request->id . ',id,type,' . $request->type . ',room_id,' . $request->room_id,
			'discount' => 'required|integer|between:1,99',
		];
		if ($request->type == 'early_bird') {
			$rules['period'] .= '|between:30,1080';
		}
		if ($request->type == 'last_min') {
			$rules['period'] .= '|between:1,28';
		}

		$messages = [
			'required' => 'The :attribute is required',
			'period.integer' => trans('validation.numeric', ['attribute' => trans('messages.lys.period')]),
			'discount.integer' => trans('validation.numeric', ['attribute' => trans('messages.lys.discount')]),
		];
		$attributes = [
			'id' => 'Id',
			'room_id' => 'Room Id',
			'type' => 'Type',
			'period' => trans('messages.lys.period'),
			'discount' => trans('messages.lys.discount'),
		];

		$validator = \Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			$errors = @$validator->errors()->all();
			return response()->json(['success_message' => @$errors[0], 'status_code' => '0']);
		}

		$user_details = JWTAuth::parseToken()->authenticate();
		$room_details = @Rooms::where('id', $request->room_id)->where('user_id', $user_details->id)->first();
		if (!$room_details) {
			return response()->json(['success_message' => 'Permission Denied', 'status_code' => '0']);
		}

		$rule = $request->all();

		if (@$rule['id']) {
			$check = [
				'id' => $rule['id'],
				'room_id' => $rule['room_id'],
				'type' => $rule['type'],
			];
		} else {
			$check = [
				'room_id' => $rule['room_id'],
				'type' => $rule['type'],
				'period' => $rule['period'],
			];
		}
		$price_rule = RoomsPriceRules::firstOrNew($check);
		$price_rule->room_id = $rule['room_id'];
		$price_rule->type = $rule['type'];
		$price_rule->period = $rule['period'];
		$price_rule->discount = $rule['discount'];

		$price_rule->save();

		$price_rules = RoomsPriceRules::where('room_id', $price_rule->room_id)->type($price_rule->type)->get();
		$price_rules = $price_rules->count() ? $price_rules : array();

		return response()->json(['success_message' => 'Price Rule Updated Successfully', 'status_code' => '1', 'price_rules' => $price_rules]);
	}

	/**
	 *  To Delete the Price Rule
	 *
	 * @param  Get method inputs [id, room_id]
	 * @return Response in Json
	 */
	public function delete_price_rule(Request $request) {
		$rules = [
			'id' => 'required|exists:rooms_price_rules,id,room_id,' . $request->room_id,
			'room_id' => 'required|exists:rooms,id',
		];
		$messages = [
			'required' => 'The :attribute is required',
		];
		$attributes = [
			'id' => 'Id',
			'room_id' => 'Room Id',
		];

		$validator = \Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			$errors = @$validator->errors()->all();
			return response()->json(['success_message' => @$errors[0], 'status_code' => '0']);
		}

		$user_details = JWTAuth::parseToken()->authenticate();
		$room_details = @Rooms::where('id', $request->room_id)->where('user_id', $user_details->id)->first();
		if (!$room_details) {
			return response()->json(['success_message' => 'Permission Denied', 'status_code' => '0']);
		}

		$price_rule = RoomsPriceRules::where('id', $request->id)->where('room_id', $request->room_id)->first();

		$type = '';
		if ($price_rule) {
			$type = $price_rule->type;
			$price_rule->delete();
		}

		$price_rules = RoomsPriceRules::where('room_id', $price_rule->room_id)->type($type)->get();
		$price_rules = $price_rules->count() ? $price_rules : array();

		return response()->json(['success_message' => 'Price Rule Deleted Successfully', 'status_code' => '1', 'price_rules' => $price_rules]);
	}

	/**
	 *  To Update the Availability Rule
	 *
	 * @param  Get method inputs [id, room_id, type, start_date, end_date, minimum_stay, maximum_stay]
	 * @return Response in Json
	 */
	public function update_availability_rule(Request $request) {
		$rules = [
			'id' => 'exists:rooms_availability_rules,id,room_id,' . $request->room_id,
			'room_id' => 'required|exists:rooms,id',
			'type' => 'required|in:month,custom',
			'start_date' => 'required|date_format:d-m-Y|after:last day of previous month',
			'end_date' => 'required|date_format:d-m-Y|after:last day of previous month|after:start_date',
			'minimum_stay' => 'integer|min:1|maxmin:' . @$request->maximum_stay,
			'maximum_stay' => 'integer|min:1|required_if:minimum_stay,""',
		];
		if ($request->id) {
			$rules['type'] .= ',prev';
		}
		$messages = [
			'required' => 'The :attribute is required',
			'minimum_stay.maxmin' => trans('validation.max.numeric', ['attribute' => trans('messages.lys.minimum_stay'), 'max' => trans('messages.lys.maximum_stay')]),
			'maximum_stay.required_if' => trans('messages.lys.minimum_or_maximum_stay_required'),
			'minimum_stay.integer' => trans('validation.numeric', ['attribute' => trans('messages.lys.minimum_stay')]),
			'maximum_stay.integer' => trans('validation.numeric', ['attribute' => trans('messages.lys.maximum_stay')]),
		];
		$attributes = [
			'id' => 'Id',
			'room_id' => 'Room Id',
			'type' => 'Type',
			'start_date' => trans('messages.lys.start_date'),
			'end_date' => trans('messages.lys.end_date'),
			'minimum_stay' => trans('messages.lys.minimum_stay'),
			'maximum_stay' => trans('messages.lys.maximum_stay'),
		];

		$validator = \Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			$errors = @$validator->errors()->all();
			return response()->json(['success_message' => @$errors[0], 'status_code' => '0']);
		}

		$user_details = JWTAuth::parseToken()->authenticate();
		$room_details = @Rooms::where('id', $request->room_id)->where('user_id', $user_details->id)->first();
		if (!$room_details) {
			return response()->json(['success_message' => 'Permission Denied', 'status_code' => '0']);
		}

		$rule = $request->all();
		$check = [
			'id' => @$rule['id'] ?: '',
		];
		$availability_rule = RoomsAvailabilityRules::firstOrNew($check);
		$availability_rule->room_id = $request->room_id;
		$availability_rule->start_date = date('Y-m-d', $this->helper->custom_strtotime(@$rule['start_date'], 'd-m-Y'));
		$availability_rule->end_date = date('Y-m-d', $this->helper->custom_strtotime(@$rule['end_date'], 'd-m-Y'));
		$availability_rule->minimum_stay = @$rule['minimum_stay'] ?: null;
		$availability_rule->maximum_stay = @$rule['maximum_stay'] ?: null;
		$availability_rule->type = @$rule['type'] != 'prev' ? @$rule['type'] : @$availability_rule->type;
		$availability_rule->save();

		$rooms_availability_rules = RoomsAvailabilityRules::where('room_id', $request->room_id)->get()->toArray();
		$availability_rules = array();
		foreach (@$rooms_availability_rules ?: array() as $key => $rule) {
			$availability_rules[$key] = array_map(function ($v) {
				return (is_null($v)) ? "" : $v;
			}, $rule);
		}

		return response()->json(['success_message' => 'Availability Rule Updated Successfully', 'status_code' => '1', 'availability_rules' => $availability_rules]);
	}

	/**
	 *  To Delete the Availability Rule
	 *
	 * @param  Get method inputs [id, room_id]
	 * @return Response in Json
	 */
	public function delete_availability_rule(Request $request) {
		$rules = [
			'id' => 'required|exists:rooms_availability_rules,id,room_id,' . $request->room_id,
			'room_id' => 'required|exists:rooms,id',
		];
		$messages = [
			'required' => 'The :attribute is required',
		];
		$attributes = [
			'id' => 'Id',
			'room_id' => 'Room Id',
		];

		$validator = \Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			$errors = @$validator->errors()->all();
			return response()->json(['success_message' => @$errors[0], 'status_code' => '0']);
		}

		$user_details = JWTAuth::parseToken()->authenticate();
		$room_details = @Rooms::where('id', $request->room_id)->where('user_id', $user_details->id)->first();
		if (!$room_details) {
			return response()->json(['success_message' => 'Permission Denied', 'status_code' => '0']);
		}

		RoomsAvailabilityRules::where('id', $request->id)->where('room_id', $request->room_id)->delete();

		$rooms_availability_rules = RoomsAvailabilityRules::where('room_id', $request->room_id)->get()->toArray();
		$availability_rules = array();
		foreach (@$rooms_availability_rules ?: array() as $key => $rule) {
			$availability_rules[$key] = array_map(function ($v) {
				return (is_null($v)) ? "" : $v;
			}, $rule);
		}

		return response()->json(['success_message' => 'Availability Rule Deleted Successfully', 'status_code' => '1', 'availability_rules' => $availability_rules]);
	}

	/**
	 *  To Get The Availability Rules List
	 *
	 * @param  Get method inputs [room_id]
	 * @return Response in Json
	 */
	public function get_availability_rules_list(Request $request) {
		$rules = [
			'room_id' => 'required|exists:rooms,id',
		];
		$messages = [
			'required' => 'The :attribute is required',
		];
		$attributes = [
			'room_id' => 'Room Id',
		];

		$validator = \Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			$errors = @$validator->errors()->all();
			return response()->json(['success_message' => @$errors[0], 'status_code' => '0']);
		}

		$user_details = JWTAuth::parseToken()->authenticate();
		$room_details = @Rooms::where('id', $request->room_id)->where('user_id', $user_details->id)->first();
		if (!$room_details) {
			return response()->json(['success_message' => 'Permission Denied', 'status_code' => '0']);
		}

		$rooms_availability_rules = RoomsAvailabilityRules::where('room_id', $request->room_id)->get()->toArray();
		$availability_rules = array();
		foreach (@$rooms_availability_rules ?: array() as $key => $rule) {
			$availability_rules[$key] = array_map(function ($v) {
				return (is_null($v)) ? "" : $v;
			}, $rule);
		}

		return response()->json(['success_message' => 'Availability Rules List Loaded Successfully', 'status_code' => '1', 'availability_rules' => $availability_rules]);
	}

	/**
	 *  To Get The Price Rules List
	 *
	 * @param  Get method inputs [room_id]
	 * @return Response in Json
	 */
	public function get_price_rules_list(Request $request) {
		$rules = [
			'room_id' => 'required|exists:rooms,id',
		];
		$messages = [
			'required' => 'The :attribute is required',
		];
		$attributes = [
			'room_id' => 'Room Id',
		];

		$validator = \Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			$errors = @$validator->errors()->all();
			return response()->json(['success_message' => @$errors[0], 'status_code' => '0']);
		}

		$user_details = JWTAuth::parseToken()->authenticate();
		$room_details = @Rooms::where('id', $request->room_id)->where('user_id', $user_details->id)->first();
		if (!$room_details) {
			return response()->json(['success_message' => 'Permission Denied', 'status_code' => '0']);
		}

		$price_rules = array();

		$price_rules['success_message'] = 'Price Rules List Loaded Successfully';
		$price_rules['status_code'] = '1';
		$price_rules['length_of_stay_rules'] = @$room_details->length_of_stay_rules ?: array();
		$price_rules['early_bird_rules'] = @$room_details->early_bird_rules ?: array();
		$price_rules['last_min_rules'] = @$room_details->last_min_rules ?: array();

		return response()->json($price_rules);
	}

	/**
	 *  To Update the Minimum Maximum Stay
	 *
	 * @param  Get method inputs [room_id, minimum_stay, maximum_stay]
	 * @return Response in Json
	 */
	public function update_minimum_maximum_stay(Request $request) {
		$rules = [
			'room_id' => 'required|exists:rooms,id',
			'minimum_stay' => 'integer|min:1|maxmin:' . @$request->maximum_stay,
			'maximum_stay' => 'integer|min:1',
		];
		if (@$request->maximum_stay == 0 && @$request->minimum_stay == 0) {
			$rules['minimum_stay'] = 'integer';
			$rules['maximum_stay'] = 'integer';
		}

		$messages = [
			'required' => 'The :attribute is required',
			'minimum_stay.maxmin' => trans('validation.max.numeric', ['attribute' => trans('messages.lys.minimum_stay'), 'max' => trans('messages.lys.maximum_stay')]),
			'maximum_stay.required_if' => trans('messages.lys.minimum_or_maximum_stay_required'),
			'minimum_stay.integer' => trans('validation.numeric', ['attribute' => trans('messages.lys.minimum_stay')]),
			'maximum_stay.integer' => trans('validation.numeric', ['attribute' => trans('messages.lys.maximum_stay')]),
		];
		$attributes = [
			'room_id' => 'Room Id',
			'minimum_stay' => trans('messages.lys.minimum_stay'),
			'maximum_stay' => trans('messages.lys.maximum_stay'),
		];

		$validator = \Validator::make($request->all(), $rules, $messages, $attributes);

		if ($validator->fails()) {
			$errors = @$validator->errors()->all();
			return response()->json(['success_message' => @$errors[0], 'status_code' => '0']);
		}

		$user_details = JWTAuth::parseToken()->authenticate();
		$room_details = @Rooms::where('id', $request->room_id)->where('user_id', $user_details->id)->first();
		if (!$room_details) {
			return response()->json(['success_message' => 'Permission Denied', 'status_code' => '0']);
		}

		$rooms_price = $room_details->rooms_price;
		$rooms_price->minimum_stay = $request->minimum_stay ?: null;
		$rooms_price->maximum_stay = $request->maximum_stay ?: null;
		$rooms_price->save();

		return response()->json(['success_message' => 'Minimum and Maximum Stay Updated Successfully', 'status_code' => '1']);
	}

	/**
	 * Unlist/Listing the Room Listing
	 *@param  Get method request inputs
	 *
	 * @return Response in Json
	 */
	public function disable_listing(Request $request) {
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

				'status_code' => '0',
			]);
		} else {
			$user_details = JWTAuth::parseToken()->authenticate();

			$data = @Rooms::where('id', $request->room_id)->where('user_id', $user_details->id)->first();

			if ($data == null) {

				return response()->json([

					'success_message' => 'Permission Denied',

					'status_code' => '0',

				]);

			}
			if ($data->steps_count != 0) {

				return response()->json([

					'success_message' => 'Room Listing Not Completed',

					'status_code' => '0',

				]);
			}
			//check the listing listing or not
			if ($data->status == 'Listed') {

				//change listed room to unlisted room
				$result = DB::table('rooms')->where('id', $request->room_id)

					->where('user_id', $user_details->id)

					->update(['status' => 'Unlisted']);

				return response()->json([

					'success_message' => 'Room Successfully Unlisted ',

					'status_code' => '1',

				]);

			} //change unlisted or pending room to listed room
			if ($data->status == 'Unlisted' || $data->status == null) {

				$user_details = JWTAuth::parseToken()->authenticate();

				$result = DB::table('rooms')->where('id', $request->room_id)

					->where('user_id', $user_details->id)

					->update(['status' => 'Listed']);

				return response()->json([

					'success_message' => 'Room Successfully Listed ',

					'status_code' => '1',

				]);

			}

		}

	}
	/**
	 * Load Rooms Price
	 *@param  Get method request inputs
	 *
	 * @return Response in Json
	 */
	public function add_rooms_price(Request $request) {

		$rules = array('room_id' => 'required|exists:rooms_price,room_id',

			'room_price' => 'required|numeric');

		$niceNames = array('room_id' => 'Room Id');

		$messages = array('required' => ':attribute is required.');

		$validator = Validator::make($request->all(), $rules, $messages);

		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			$error = $validator->messages()->toArray();

			if (isset($error['room_id'])) {

				return response()->json([

					'success_message' => 'Invalid Room Id',

					'status_code' => '0']);

			}

			if (isset($error['room_price'])) {

				return response()->json([

					'success_message' => 'Undefind Room Price',

					'status_code' => '0',

					'error_message' => $error['room_price']['0'],

				]);

			}

		} else {

			$user = JWTAuth::parseToken()->authenticate();

			$rooms_currency = RoomsPrice::where('room_id', $request->room_id)

				->pluck('currency_code');

			$rate = Currency::whereCode($rooms_currency)->first()->rate;

			// $minimum_price=round($rate *10);
			$minimum_price = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $rooms_currency, MINIMUM_AMOUNT);
			//check minimum room price
			if ($request->room_price < $minimum_price) {
				return response()->json([

					'success_message' => 'Rooms Price Must Be Minimum' . '-' . $minimum_price . '-' . $rooms_currency,

					'status_code' => '0',

				]);
			}

			$rooms_info = Rooms::where('id', $request->room_id)->first();
			//check valid user or not
			if ($user->id == $rooms_info->user_id) {
				DB::table('rooms_price')->whereRoom_id($request->room_id)->update(['night' => $request->room_price]);

				$rooms_status = RoomsStepsStatus::find($request->room_id);

				$rooms_status->pricing = 1;

				$rooms_status->save();

				return response()->json([

					'success_message' => 'Room Price updated Successfully',

					'status_code' => '1',

				]);
			} else {
				return response()->json([

					'success_message' => 'Permission Denied',

					'status_code' => '0']);

			}
		}
	}
	/**
	 * Update Room Location
	 *@param  Get method request inputs
	 *@param  Get is_success yes load the given request value in db.
	 *@param  Get is_success No Get Address from google map and load db.
	 * @return Response in Json
	 */
	public function update_location(Request $request) {
		if ($request->is_success == 'Yes') {
			$rules = array(

				'room_id' => 'required|exists:rooms_address,room_id',

				'latitude' => 'required',

				'longitude' => 'required',

				'city' => 'required',

				'state' => 'required',

				'country' => 'required|exists:country,long_name',

			);

			$niceNames = array(

				'room_id' => 'Room Id',

				'latitude' => 'Latitude',

				'longitude' => 'Longitude',

				'city' => 'City',

				'state' => 'State',

				'country' => 'Country',

			);
		}

		if ($request->is_success == 'No') {

			$rules = array(

				'room_id' => 'required|exists:rooms_address,room_id',

				'latitude' => 'required',

				'longitude' => 'required',

			);

			$niceNames = array(

				'room_id' => 'Room Id',

				'latitude' => 'Latitude',

				'longitude' => 'Longitude',

			);
		}
		if ($request->is_success != 'Yes' && $request->is_success != 'No') {

			return response()->json([

				'success_message' => 'Request Is Invalid',

				'status_code' => '0',

			]);

		}

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

			$rooms_info = Rooms::where('id', $request->room_id)->first();

			//check valid user or not
			if (JWTAuth::parseToken()->authenticate()->id != $rooms_info->user_id) {
				return response()->json([
					'success_message' => 'Permission Denied',

					'status_code' => '0',

				]);

			}
			if ($request->is_success == 'No') {

				$geocode = @file_get_contents('https://maps.google.com/maps/api/geocode/json?key=' . MAP_SERVER_KEY . '&latlng=' . $request->latitude . ',' . $request->longitude);

				$json = json_decode($geocode);

				if (($json->status == 'ZERO_RESULTS') || ($json->status == 'null')) {

					return response()->json([

						'success_message' => 'Invalid Address',

						'status_code' => '0',

					]);
				} else {
					for ($i = 0; $i < count($json->{'results'}[0]->{'address_components'}); $i++) {
						$loc_address = $json->{'results'}[0]->{'address_components'}[$i]->{'types'}[0];

						if ($loc_address == "street_number") {
							$room_address_line_1 = @$json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} : '';
						}

						if ($loc_address == "route") {
							$room_address_line_2 = @$json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} : '';
						}

						if ($loc_address == "locality") {
							$room_city = @$json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} : '';

						}

						if ($loc_address == "administrative_area_level_1") {
							$room_state = $json->{'results'}[0]->{'address_components'}[$i]->{'long_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'long_name'} : '';
						}

						if ($loc_address == "country") {
							$room_country = $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} : '';
							$room_country_fullname = $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'long_name'} : '';
						}

						if ($loc_address == "postal_code") {
							$room_postal_code = $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} : '';
							// $room_country_fullname=$json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'long_name'} : '';
						}
					}

				}
				$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
				$lng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

				$address_line_1 = @$room_address_line_1 != null ? $room_address_line_1 : '';

				$address_line_2 = @$room_address_line_2 != null ? $room_address_line_2 : '';

				$city = @$room_city != null ? $room_city : '';

				$state = @$room_state != null ? $room_state : '';

				$room_country = @$room_country != null ? $room_country : '';

				$latitude = @$lat != null ? $lat : '';

				$longitude = @$lng != null ? $lng : '';

				$postal_code = @$room_postal_code != null ? $room_postal_code : '';

			}
			if ($request->is_success == 'No') {
				//get and check map location without empty address
				$map_location = array();

				if ($address_line_1 != '') {

					$map_location[] = $address_line_1;

				}
				if ($address_line_2 != '') {

					$map_location[] = $address_line_2;

				}
				if ($city != '') {

					$map_location[] = $city;

				}
				if ($state != '') {

					$map_location[] = $state;

				}
				if ($room_country_fullname != '') {

					$map_location[] = $room_country_fullname;

				}
			}

			if ($request->is_success == 'Yes') {
				//get country code
				$country_code = Country::where('long_name', $request->country)->first();
				if ($country_code) {
					$country_code = $country_code->short_name;
				} else {
					$country_code = IN;
				}

				//remove empty address
				$address = array();

				if ($request->street_name != '') {
					$address[] = $request->street_name;
				}
				if ($request->street_address != '') {
					$address[] = $request->street_address;
				}
				if ($request->city != '') {
					$address[] = $request->city;
				}
				if ($request->state != '') {
					$address[] = $request->state;
				}
				if ($request->country != '') {
					$address[] = $request->country;
				}
				if ($request->zip != '') {
					$address[] = $request->zip;
				}
			}

			$result = array(

				'room_id' => $request->room_id,

				'address_line_1' => $request->is_success == 'No' ? $address_line_1 : $request->street_name,

				'address_line_2' => $request->is_success == 'No' ? $address_line_2 : $request->street_address,

				'city' => $request->is_success == 'No' ? $city : $request->city,

				'state' => $request->is_success == 'No' ? $state : $request->state,

				'country' => $request->is_success == 'No' ? $room_country : $country_code,

				'postal_code' => $request->is_success == 'No' ? $postal_code : $request->zip,

				'latitude' => $request->is_success == 'No' ? $latitude : $request->latitude,

				'longitude' => $request->is_success == 'No' ? $longitude : $request->longitude,

			);

			$result_display = array(

				'address_line_1' => $request->is_success == 'No' ? $address_line_1 : ($request->street_name != null ? $request->street_name : ''),

				'address_line_2' => $request->is_success == 'No' ? $address_line_2 : ($request->street_address != null ? $request->street_address : ''),

				'city' => $request->is_success == 'No' ? $city : $request->city,

				'state' => $request->is_success == 'No' ? $state : $request->state,

				'country' => $request->is_success == 'No' ? $room_country_fullname : $request->country,

				'postal_code' => $request->is_success == 'No' ? $postal_code : ($request->zip != null ? $request->zip : ''),

			);

			DB::table('rooms_address')->whereRoom_id($request->room_id)->update($result);

			$rooms_status = RoomsStepsStatus::find($request->room_id);

			$rooms_status->location = 1;

			$rooms_status->save();

			return response()->json([

				'success_message' => 'Rooms Map Details updated Successfully',

				'status_code' => '1',

				'location_details' => $result_display,

				'location_name' => $request->is_success == 'No'

				? implode(',', $map_location)

				: implode(',', $address),

			]);
		}
	}
	/**
	 *Update Room Long Term Price
	 *@param  Get method request inputs
	 *
	 * @return Response in Json
	 */
	public function update_Long_term_prices(Request $request) {

		$rules = array('room_id' => 'required|exists:rooms_price,room_id');

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

				'status_code' => '0',

			]);
		} else {
			$user = JWTAuth::parseToken()->authenticate();
			//get currecny code
			$rooms_currency = RoomsPrice::where('room_id', $request->room_id)->first();

			$currency = 'USD';

			if ($rooms_currency) {

				$currency = $rooms_currency->currency_code;
			}

			//get currency rate
			$rate = Currency::whereCode($currency)->first()->rate;

			// $minimum_price=round($rate *10);
			$minimum_price = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $currency, MINIMUM_AMOUNT);
			//check minimum weekly price limit or not
			if ($request->weekly_price < $minimum_price && $request->weekly_price != '') {
				return response()->json([

					'success_message' => 'Weekly Price Must Be Minimum' . '-' . $minimum_price . '-' . $currency,

					'status_code' => '0',

				]);
			}
			//check minimum monthly price limit or not
			if ($request->monthly_price < $minimum_price && $request->monthly_price != '') {

				return response()->json([

					'success_message' => 'Monthly Price Must Be Minimum' . '-' . $minimum_price . '-' . $currency,

					'status_code' => '0',
				]);
			}

			$rooms_info = Rooms::where('id', $request->room_id)->first();
			//check valid user or not
			if ($user->id == $rooms_info->user_id) {

				$UpdateDetails = RoomsPrice::where('Room_id', '=', $request->room_id)->first();

				// $UpdateDetails->week              =   $request->weekly_price;

				// $UpdateDetails->month             =   $request->monthly_price;

				$UpdateDetails->cleaning = $request->cleaning_fee != ''

				? $request->cleaning_fee : 0;

				$UpdateDetails->additional_guest = $request->additional_guests != ''

				? $request->additional_guests : 0;

				$UpdateDetails->guests = $request->for_each_guest != ''

				? $request->for_each_guest : 0;

				$UpdateDetails->security = $request->security_deposit != ''

				? $request->security_deposit : 0;

				$UpdateDetails->weekend = $request->weekend_pricing != ''

				? $request->weekend_pricing : 0;

				$UpdateDetails->currency_code = $currency;

				$UpdateDetails->save();

				return response()->json([

					'success_message' => 'Room Long Term Prices updated Successfully',

					'status_code' => '1',

				]);
			} else {

				return response()->json([

					'success_message' => 'Permission Denied',

					'status_code' => '0',

				]);
			}
		}

	}
	/**
	 * Load New Room Resource
	 *@param  Get method request inputs
	 *
	 * @return Response in Json
	 */

	public function new_add_room(Request $request) {

		$user_token = JWTAuth::parseToken()->authenticate();

		$rules = array('latitude' => 'required', 'longitude' => 'required');

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

			$rooms = new Rooms;

			$rooms->user_id = $user_token->id;
			//get address based on map latitute and longitute
			$geocode = @file_get_contents('https://maps.google.com/maps/api/geocode/json?key=' . MAP_SERVER_KEY . '&latlng=' . $request->latitude . ',' . $request->longitude);

			$json = json_decode($geocode);

			// check given latitute and longitute are valid or not
			if ((@$json->status == 'ZERO_RESULTS') || (@$json->status == 'null')) {

				return response()->json([

					'success_message' => 'Invalid Address',

					'status_code' => '0',

				]);

			} else {
				if (isset($json->error_message)) {
					return response()->json([

						'success_message' => $json->error_message,

						'status_code' => '0',

					]);
				}
				//get address based on address components
				for ($i = 0; $i < count($json->{'results'}[0]->{'address_components'}); $i++) {
					$loc_address = $json->{'results'}[0]->{'address_components'}[$i]->{'types'}[0];

					if ($loc_address == "route") {
						$address_line_1 = @$json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} : '';

					}

					if ($loc_address == "locality") {
						$room_city = @$json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} : '';

					}
					if ($loc_address == "administrative_area_level_1") {
						$room_state = $json->{'results'}[0]->{'address_components'}[$i]->{'long_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'long_name'} : '';
					}
					if ($loc_address == "country") {
						$room_country = $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} : '';
						$room_country_fullname = $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'long_name'} : '';
					}
					if ($loc_address == "postal_code") {
						$room_postal_code = $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} != '' ? $json->{'results'}[0]->{'address_components'}[$i]->{'short_name'} : '';
					}
				}

				$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
				$lng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

				$room_type_sub_name = @$room_city != null ? $room_city : @$room_state;

				$room_type = RoomType::single_field($request->room_type, 'name');

				if ($request->name == '') {
					$rooms->sub_name = $room_type . " in " . $room_type_sub_name;
				} else {
					$rooms->name = $request->name;
				}
				$rooms->property_type = $request->property_type != '' ? $request->property_type : '';

				$rooms->room_type = $request->room_type != '' ? $request->room_type : '';

				$rooms->accommodates = $request->max_guest != '' ? $request->max_guest : '';

				$rooms->bedrooms = $request->bedrooms_count != '' ? $request->bedrooms_count : '';
				$rooms->beds = $request->beds_count != '' ? $request->beds_count : '';

				$rooms->bed_type = $request->bed_type != '' ? $request->bed_type : '';

				$rooms->bathrooms = $request->bathrooms != '' ? $request->bathrooms : '';

				$rooms->started = 'Yes';

				$rooms->save(); // Store data to rooms Table

				$rooms_status = new RoomsStepsStatus;

				$rooms_status->room_id = $rooms->id;

				$rooms_status->basics = 1;

				$rooms_status->calendar = 1;

				$rooms_status->save(); // Store data to rooms_steps_status table

				$rooms_address = new RoomsAddress;

				$rooms_address->room_id = $rooms->id;

				$rooms_address->address_line_1 = @$address_line_1 != null ? $address_line_1 : '';

				$rooms_address->city = @$room_city != null ? $room_city : '';

				$rooms_address->state = @$room_state != null ? $room_state : '';

				$rooms_address->country = @$room_country != null ? $room_country : '';

				$rooms_address->latitude = $lat != '' ? $lat : '';

				$rooms_address->longitude = $lng != '' ? $lng : '';

				$rooms_address->postal_code = @$room_postal_code != null ? $room_postal_code : '';

				$rooms_address->save();

				$rooms_status = RoomsStepsStatus::find($rooms->id);

				$rooms_status->location = 1;

				$rooms_status->save();

				$rooms_price = new RoomsPrice;

				$rooms_price->room_id = $rooms->id;
				$rooms_price->currency_code = $user_token->currency_code;

				$rooms_price->save(); // Store data to rooms_price table

				$rooms_description = new RoomsDescription;

				$rooms_description->room_id = $rooms->id;

				$rooms_description->save(); // Store data to rooms_description table

				//remove empty address
				$location = array();

				if (@$address_line_1 != null) {
					$location[] = $address_line_1;
				}
				if (@$room_type_sub_name != null) {
					$location[] = $room_type_sub_name;
				}
				if (@$room_state != null) {
					$location[] = @$room_state;
				}
				if (@$room_country_fullname != null) {
					$location[] = @$room_country_fullname;
				}

				return response()->json([

					'success_message' => 'Rooms Details Added Successfully',

					'status_code' => '1',

					'room_id' => $rooms->id,

					'location' => implode(',', $location),
					'length_of_stay_options' => Rooms::getLenghtOfStayOptions() ?: array(),
					'availability_rules_options' => Rooms::getAvailabilityRulesMonthsOptions() ?: array(),

				]);
			}

		}
	}
	/**
	 *Load Rooms Basci Details
	 *
	 * @param  Get method request inputs
	 *
	 * @return Response in Json
	 */
	public function listing_rooms_beds(Request $request) {
		$user_token = JWTAuth::parseToken()->authenticate();

		$rules = array(

			'room_id' => 'required|integer',

			'property_type' => 'required|integer',

			'room_type' => 'required|integer',

			'person_capacity' => 'required|integer',

			'bedrooms' => 'required|integer',

			'bed_type' => 'required|integer',

			'beds' => 'required|integer',

			'bathrooms' => 'required|numeric',

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

			$bath_rooms = array('0', '0.5', '1', '1.5', '2', '2.5', '3', '3.5', '4', '4.5', '5', '5.5', '6', '6.5', '7', '7.5', '8');
			// Validate the Bothrooms values
			if (!in_array($request->bathrooms, $bath_rooms)) {

				return response()->json([

					'success_message' => 'Bathroom Value Is Invalid',

					'status_code' => '0',

				]);
			}

			//check valid user or not
			$user_check = Rooms::where('user_id', $user_token->id)->where('id', $request->room_id)->get();

			if (count($user_check) != '0') {

				$rooms = new Rooms;

				$result = array(

					'property_type' => $request->property_type,

					'room_type' => $request->room_type,

					'accommodates' => $request->person_capacity,

					'bedrooms' => $request->bedrooms,

					'bed_type' => $request->bed_type,

					'beds' => $request->beds,

					'bathrooms' => $request->bathrooms,

				);
				// Update rooms basic details
				DB::table('rooms')->whereId($request->room_id)->update($result);

				return response()->json([

					'success_message' => 'Rooms Details Added Successfully.',

					'status_code' => '1',

				]);
			} else {
				return response()->json([

					'success_message' => 'Permission Denied',

					'status_code' => '0',

				]);
			}
		}

	}
/**
 * Display Listing Rooms Resource
 *@param  Get method request inputs
 *
 * @return Response in Json
 */

	public function listing_rooms(Request $request) {

		$user_token = JWTAuth::parseToken()->authenticate();

		$rules = array('page' => 'required | numeric |min:1');

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

			$result = Rooms::with(['rooms_address', 'rooms_description', 'rooms_price', 'availability_rules', 'length_of_stay_rules', 'early_bird_rules', 'last_min_rules'])

				->where('user_id', $user_token->id);

			$rooms_res = $result->orderBy('id', 'asc')->paginate(100)->toJson();

			$de_result = json_decode($rooms_res, true);

			if ($de_result['total'] == 0 || empty($de_result['data'])) {

				return response()->json([

					'success_message' => 'No Data Found',

					'status_code' => '0',

				]);
			} else {

				for ($i = 0; $i < count($de_result['data']); $i++) {
					//get roomsphoto
					$room_img = @RoomsPhotos::where('room_id', $de_result['data'][$i]['id'])->

						orderBy('featured', 'Asc')->get()->toArray();

					if (count($room_img) > 0) {
						foreach ($room_img as $row) {
							@$res_room[$i][] = $row['name'];

							@$res_room_image_id[$i][] = (string) $row['id'];
						}

					} else {
						@$res_room[$i] = array();

						@$res_room_image_id[$i] = array();

					}

					$get_room_type = RoomType::where('id', $de_result['data'][$i]['room_type'])->first()->name;

					$map_address_line_1 = $de_result['data'][$i]['rooms_address']

					['address_line_1'] != null

					? $de_result['data'][$i]['rooms_address']['address_line_1']

					: '';

					$map_address_line_2 = $de_result['data'][$i]['rooms_address']

					['address_line_2'] != null

					? $de_result['data'][$i]['rooms_address']['address_line_2']

					: '';

					$map_city = $de_result['data'][$i]['rooms_address']['city'] != null

					? $de_result['data'][$i]['rooms_address']['city'] : '';

					$map_state = $de_result['data'][$i]['rooms_address']['state'] != null

					? $de_result['data'][$i]['rooms_address']['state'] : '';

					$map_country = $de_result['data'][$i]['rooms_address']['country_name'] != null

					? $de_result['data'][$i]['rooms_address']['country_name'] : '';

					$zip_code = $de_result['data'][$i]['rooms_address']['postal_code'] != null

					? $de_result['data'][$i]['rooms_address']['postal_code'] : '';

					//get blocked dates
					$rooms_details = @Rooms::with(['calendar' => function ($query) {

						$query->where('date', '>=', date('Y-m-d'));

					},

					])->where('rooms.id', $de_result['data'][$i]['id'])->first()->toArray();

					foreach ($rooms_details['calendar'] as $date) {
						$date = $date['date'];

						$createDate = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($date)));

						$blocked_dates[] = $createDate->format('d-m-Y');
					}
					//get currency details
					$currency_details = @Currency::where('code', $de_result['data'][$i]['rooms_price']['currency_code'])->first();
					//get  room_location_name

					$room_location_name = array();

					if ($map_address_line_1 != '') {

						$room_location_name[] = $map_address_line_1;

					}
					if ($map_address_line_2 != '') {

						$room_location_name[] = $map_address_line_2;

					}
					if ($map_city != '') {

						$room_location_name[] = $map_city;

					}
					if ($map_state != '') {

						$room_location_name[] = $map_state;

					}
					if ($map_country != '') {

						$room_location_name[] = $map_country;

					}
					$availability_rules = array();

					foreach (@$de_result['data'][$i]['availability_rules'] ?: array() as $key => $rule) {
						$availability_rules[$key] = array_map(function ($v) {
							return (is_null($v)) ? "" : $v;
						}, $rule);
					}

					$data[] = array(

						'room_id' => $de_result['data'][$i]['id'] != null

						? $de_result['data'][$i]['id'] : '',

						'room_thumb_images' => @$res_room[$i],

						'room_image_id' => @$res_room_image_id[$i],

						'room_type' => $de_result['data'][$i]['room_type'],

						'bed_type' => @$de_result['data'][$i]['bed_type'] != null ? $de_result['data'][$i]['bed_type'] : '1',

						'room_name' => $get_room_type != null ? $get_room_type : '',

						'room_description' => $de_result['data'][$i]['summary'] != null

						? $de_result['data'][$i]['summary'] : '',

						'amenities' => $de_result['data'][$i]['amenities'] != null

						? $de_result['data'][$i]['amenities'] : '',

						'room_title' => $de_result['data'][$i]['name'] != null

						? $de_result['data'][$i]['name'] : '',

						'latitude' => $de_result['data'][$i]

						['rooms_address']['latitude'] != null

						? $de_result['data'][$i]

						['rooms_address']['latitude'] : '',

						'longitude' => $de_result['data'][$i]

						['rooms_address']['longitude'] != null

						? $de_result['data'][$i]

						['rooms_address']['longitude'] : '',

						'room_location' => @$map_city != null ? $map_city : @$map_state,

						'room_location_name' => @implode(',', $room_location_name),

						'additional_rules_msg' => $de_result['data'][$i]

						['rooms_description']['house_rules'] != null

						? $de_result['data'][$i]

						['rooms_description']['house_rules'] : '',

						'room_price' => $de_result['data'][$i]['rooms_price']

						['original_night'] != null

						? (string) $de_result['data'][$i]

						['rooms_price']['original_night'] : '',

						'remaining_steps' => (string) $de_result['data'][$i]['steps_count'],

						'max_guest_count' => $de_result['data'][$i]['accommodates'] != null

						? (string) $de_result['data'][$i]

						['accommodates'] : '',

						'bedroom_count' => $de_result['data'][$i]['bedrooms'] != null

						? (string) $de_result['data'][$i]['bedrooms'] : '',

						'beds_count' => $de_result['data'][$i]['beds'] != null

						? (string) $de_result['data'][$i]['beds'] : '',

						'bathrooms_count' => $de_result['data'][$i]['bathrooms'] != null

						? (string) $de_result['data'][$i]

						['bathrooms'] : '0',

						'availability_rules' => $availability_rules,
						'length_of_stay_rules' => @$de_result['data'][$i]['length_of_stay_rules'] ?: array(),
						'early_bird_rules' => @$de_result['data'][$i]['early_bird_rules'] ?: array(),
						'last_min_rules' => @$de_result['data'][$i]['last_min_rules'] ?: array(),
						'minimum_stay' => @$de_result['data'][$i]['rooms_price']['minimum_stay'] ?: "",
						'maximum_stay' => @$de_result['data'][$i]['rooms_price']['maximum_stay'] ?: "",
						'length_of_stay_options' => Rooms::getLenghtOfStayOptions() ?: array(),
						'availability_rules_options' => Rooms::getAvailabilityRulesMonthsOptions() ?: array(),

						'home_type' => $de_result['data'][$i]['property_type_name'],

						'property_type' => (string) $de_result['data'][$i]['property_type'],

						'cleaning_fee' => (string) $de_result['data'][$i]

						['rooms_price']['original_cleaning'],

						'additional_guests_fee' => (string) $de_result['data'][$i]

						['rooms_price']['original_additional_guest'],

						'for_each_guest_after' => (string) $de_result['data'][$i]

						['rooms_price']['guests'],

						'security_deposit' => (string) $de_result['data'][$i]

						['rooms_price']['original_security'],

						'weekend_pricing' => (string) $de_result['data'][$i]

						['rooms_price']['original_weekend'],

						'room_currency_symbol' => @$currency_details->original_symbol != null

						? $currency_details->original_symbol : '',

						'room_currency_code' => @$de_result['data'][$i]['rooms_price']

						['currency_code'] != null

						? $de_result['data'][$i]['rooms_price']['currency_code']
						: '',

						'is_list_enabled' => $de_result['data'][$i]

						['status'] == 'Listed' ? 'Yes' : 'No',

						'policy_type' => $de_result['data'][$i]['cancel_policy'],

						'booking_type' => $de_result['data'][$i]

						['booking_type'] == 'instant_book'

						? 'Instant Book' : 'Request to Book',

						'street_name' => $map_address_line_1,

						'street_address' => $map_address_line_2,

						'city' => $map_city,

						'state' => $map_state,

						'country' => $map_country,

						'zip' => $zip_code,

					);

				}
				//split separate listing and unlisting

				$listed = array();

				$Unlisted = array();

				foreach ($data as $data_result) {
					if ($data_result['is_list_enabled'] == 'Yes') {

						$listed[] = $data_result;

					} else {
						$Unlisted[] = $data_result;
					}
				}

				return json_encode(

					array(

						'success_message' => 'Listing Rooms Loaded Successfully',

						'status_code' => '1',

						'total_page' => $de_result['last_page'],

						'listed' => $listed,

						'unlisted' => $Unlisted,

					), JSON_UNESCAPED_SLASHES);

			}
		}

	}

	public function room_image_uploads() {
		$request = request();
		$user = JWTAuth::toUser($request->token);
		$user_id = $user->id;
		$room_id = $request->room_id;
		if ($request->file('image')) {
			$rules = [
				'image' => 'required|image|mimes:jpg,png,jpeg,gif',
				'room_id' => 'required',
			];

			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(
					[
						'status_message' => $validator->messages()->first(),
						'status_code' => '0',
					]
				);
			}

			$file = $request->file('image');
			$path = '/images/rooms/' . $room_id . '/';

			if (UPLOAD_DRIVER == 'cloudinary') {
				$c = $this->helper->cloud_upload($file_tmp);
				if ($c['status'] != "error") {
					$file_name = $c['message']['public_id'];
				} else {
					return response()->json([
						'success_message' => $c['message'],
						'status_code' => "0",
					]);
				}
			} else {
				$file_name = $this->helper->fileUpload($file, $path);
				//compress image to size 1440*960
				$this->helper->compress_image("images/rooms/" . $room_id . "/" . $file_name, "images/rooms/" . $room_id . "/" . $file_name, 80, 1440, 960);
				//compress image to size 255*255
				$li = $this->helper->compress_image("images/rooms/" . $room_id . "/" . $file_name, "images/rooms/" . $room_id . "/" . $file_name, 80, 255, 255);
				//compress image to size 500*500
				$this->helper->compress_image("images/rooms/" . $room_id . "/" . $file_name, "images/rooms/" . $room_id . "/" . $file_name, 80, 500, 500);
				//compress image to size 1349*402
				$this->helper->compress_image("images/rooms/" . $room_id . "/" . $file_name, "images/rooms/" . $room_id . "/" . $file_name, 80, 1349, 402);
				//compress image to size 450*250
				$this->helper->compress_image("images/rooms/" . $room_id . "/" . $file_name, "images/rooms/" . $room_id . "/" . $file_name, 80, 450, 250);
			}

			//set featured image .The featured image is null

			$photos_featured = RoomsPhotos::where('room_id', $room_id)->where('featured', 'Yes');
			$photos = new RoomsPhotos;
			$photos->room_id = $room_id;
			$photos->name = $file_name;
			if ($photos_featured->count() == 0) {
				$photos->featured = 'Yes';
			}
			$photos->save(); //save rooms image

			$rooms_status = RoomsStepsStatus::find($room_id);
			$rooms_status->photos = 1;
			$rooms_status->save(); //save step count

			$image_name = @RoomsPhotos::where('id', $photos->id)->get()->first()->name;
			$image_list = @RoomsPhotos::where('room_id', $room_id)->get();
			foreach ($image_list as $image_data) {
				$room_image[] = @$image_data->name;
				$room_image_id[] = @(string) $image_data->id;
			}
			return response()->json([
				'success_message' => "Room Image Uploaded Successfully",
				'status_code' => "1",
				'image_urls' => $image_name,
				'room_image_id' => (string) $photos->id,
				'room_thumb_images' => @$room_image != null ? @$room_image : array(),
				'room_thumb_image_id' => @$room_image_id != null ? @$room_image_id : array(),
			]);
		}

	}
	/*
		   * Upload room Image
		   * @param  Post method request inputs
		   *
		   * @return Response in Json
	*/
	public function room_image_upload() {
		$user = JWTAuth::toUser($_POST['token']);
		$user_id = $user->id;
		$room_id = $_POST['room_id'];
		if (isset($_FILES['image'])) {
			$errors = array();
			$file_name = time() . '_' . $_FILES['image']['name']; //get image file name
			$type = pathinfo($file_name, PATHINFO_EXTENSION); //get image type
			$file_tmp = $_FILES['image']['tmp_name'];
			//create image folder name
			$dir_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/rooms/' . $room_id;
			//Add image in create folder
			$f_name = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/rooms/' . $room_id . '/' . $file_name;
			//check image folder already exit or not
			if (!file_exists($dir_name)) {
				//create image folder
				mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/rooms/' . $room_id, 0777, true);
			}
			if (UPLOAD_DRIVER == 'cloudinary') {
				$c = $this->helper->cloud_upload($file_tmp);
				if ($c['status'] != "error") {
					$file_name = $c['message']['public_id'];
				} else {
					return response()->json([
						'success_message' => $c['message'],
						'status_code' => "0",
					]);
				}
			} else {
				//move the image to server folder
				if (move_uploaded_file($file_tmp, $f_name)) {
					//compress image to size 1440*960
					$this->helper->compress_image("images/rooms/" . $room_id . "/" . $file_name, "images/rooms/" . $room_id . "/" . $file_name, 80, 1440, 960);
					//compress image to size 255*255
					$li = $this->helper->compress_image("images/rooms/" . $room_id . "/" . $file_name, "images/rooms/" . $room_id . "/" . $file_name, 80, 255, 255);
					//compress image to size 500*500
					$this->helper->compress_image("images/rooms/" . $room_id . "/" . $file_name, "images/rooms/" . $room_id . "/" . $file_name, 80, 500, 500);
					//compress image to size 1349*402
					$this->helper->compress_image("images/rooms/" . $room_id . "/" . $file_name, "images/rooms/" . $room_id . "/" . $file_name, 80, 1349, 402);
					//compress image to size 450*250
					$this->helper->compress_image("images/rooms/" . $room_id . "/" . $file_name, "images/rooms/" . $room_id . "/" . $file_name, 80, 450, 250);
				}
			}
			//set featured image .The featured image is null
			$photos_featured = RoomsPhotos::where('room_id', $room_id)->where('featured', 'Yes');
			$photos = new RoomsPhotos;
			$photos->room_id = $room_id;
			$photos->name = $file_name;
			if ($photos_featured->count() == 0) {
				$photos->featured = 'Yes';
			}
			$photos->save(); //save rooms image

			$rooms_status = RoomsStepsStatus::find($room_id);
			$rooms_status->photos = 1;
			$rooms_status->save(); //save step count

			$image_name = @RoomsPhotos::where('id', $photos->id)->get()->first()->name;
			$image_list = @RoomsPhotos::where('room_id', $room_id)->get();
			foreach ($image_list as $image_data) {
				$room_image[] = @$image_data->name;
				$room_image_id[] = @(string) $image_data->id;
			}
			return response()->json([
				'success_message' => "Room Image Uploaded Successfully",
				'status_code' => "1",
				'image_urls' => $image_name,
				'room_image_id' => (string) $photos->id,
				'room_thumb_images' => @$room_image != null ? @$room_image : array(),
				'room_thumb_image_id' => @$room_image_id != null ? @$room_image_id : array(),
			]);
		}
	}
/**
 *Update Room Currency
 *@param  Get method request inputs
 *
 * @return Response in Json
 */
	public function update_room_currency(Request $request) {

		$rules = array(

			'room_id' => 'required|exists:rooms,id',

			'currency_code' => 'required|exists:currency,code',

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

				'status_code' => '0']);
		} else {
			$user = JWTAuth::parseToken()->authenticate();

			$rooms_info = Rooms::where('id', $request->room_id)->first();
			//check the user is valid or not
			if ($user->id != $rooms_info->user_id) {
				return response()->json([
					'success_message' => 'Permission Denied',

					'status_code' => '0',
				]);
			}

			//get room price details
			$room_price_details = RoomsPrice::where('room_id', $request->room_id)->first();

			if ($room_price_details->currency_code != $request->currency_code) {

				$rate = Currency::whereCode(strtoupper($request->currency_code))->first()->rate;

				// $minimum_price=round($rate *10);
				$minimum_price = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $request->currency_code, MINIMUM_AMOUNT);
				//set minimum price validation for night price
				$price = $room_price_details->night > $minimum_price ? $room_price_details->night : $minimum_price;

				$week = $room_price_details->week > $minimum_price ? $room_price_details->week : $minimum_price;

				$month = $room_price_details->month > $minimum_price ? $room_price_details->month : $minimum_price;

				$cleaning_fee = $room_price_details['original_cleaning'] != 0

				? $room_price_details['original_cleaning'] : 0;

				$additional_guests_fee = $room_price_details['original_additional_guest'] != 0

				? $room_price_details['original_additional_guest'] : 0;

				$security_deposit = $room_price_details['original_security'] != 0

				? $room_price_details['original_security'] : 0;

				$weekend_pricing = $room_price_details['original_weekend'] != 0

				? $room_price_details['original_weekend'] : 0;

				$room_price_details->night = $price;

				// $room_price_details->week              =   $week;

				// $room_price_details->month             =   $month;

				$room_price_details->cleaning = $cleaning_fee;

				$room_price_details->additional_guest = $additional_guests_fee;

				$room_price_details->security = $security_deposit;

				$room_price_details->weekend = $weekend_pricing;

				$room_price_details->currency_code = strtoupper($request->currency_code);

				$room_price_details->save();

				return response()->json([

					'success_message' => 'Room Currency Updated Successfully',

					'status_code' => '1',

					'room_price' => $price,

					'weekly_price' => $week,

					'monthly_price' => $month,

					'cleaning_fee' => $cleaning_fee,

					'additional_guests_fee' => $additional_guests_fee,

					'security_deposit' => $security_deposit,

					'weekend_pricing' => $weekend_pricing,

				]);
			} else {
				return response()->json([

					'success_message' => 'Default Room Currency Not changed',

					'status_code' => '0',

				]);

			}
		}

	}
/**
 *Update Room Booking Type
 *@param  Get method request inputs
 *
 * @return Response in Json
 */
	public function update_booking_type(Request $request) {

		$rules = array(
			'room_id' => 'required|exists:rooms,id',

			'booking_type' => 'required',
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

				'status_code' => '0']);
		} else {
			$user = JWTAuth::parseToken()->authenticate();

			$rooms_info = Rooms::where('id', $request->room_id)->first();
			//check valid user or not
			if ($user->id != $rooms_info->user_id) {
				return response()->json([
					'success_message' => 'Permission Denied',

					'status_code' => '0',
				]);
			}
			//check valid room booking type
			if ($request->booking_type != 'request_to_book' && $request->booking_type != 'instant_book') {

				return response()->json([
					'success_message' => 'Booking Type Is Invalid',

					'status_code' => '0',
				]);

			}

			DB::table('rooms')->where('id', $request->room_id)

				->update(['booking_type' => $request->booking_type]);

			return response()->json([

				'success_message' => 'Room Booking Type Successfully Updated',

				'status_code' => '1']);
		}

	}
/**
 *Update Room Policy
 *@param  Get method request inputs
 *
 * @return Response in Json
 */
	public function update_policy(Request $request) {

		$rules = array(

			'room_id' => 'required|exists:rooms,id',

			'policy_type' => 'required',

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

				'status_code' => '0']);
		} else {
			$user = JWTAuth::parseToken()->authenticate();

			$rooms_info = Rooms::where('id', $request->room_id)->first();
			//check valid user or not
			if ($user->id != $rooms_info->user_id) {
				return response()->json([
					'success_message' => 'Permission Denied',

					'status_code' => '0',
				]);
			}
			//check valid room policy type or not
			if ($request->policy_type != 'Flexible' && $request->policy_type != 'Moderate' && $request->policy_type != 'Strict') {

				return response()->json([
					'success_message' => 'Invalid Policy Type',

					'status_code' => '0',
				]);

			}

			DB::table('rooms')->where('id', $request->room_id)

				->update(['cancel_policy' => $request->policy_type]);

			return response()->json([

				'success_message' => 'Room Policy Type Successfully Updated',

				'status_code' => '1']);
		}

	}
	public function remove_uploaded_image(Request $request) {
		$all_request = $request->all();

		if (isset($all_request['image_id']) && @$all_request['image_id'] != '') {
			$all_request['image_id'] = explode(',', $all_request['image_id']);
		} else {
			$all_request['image_id'] = array();
		}

		$rules = array(

			'room_id' => 'required|exists:rooms,id',
			'image_id' => 'required|array',
			'image_id.*' => 'exists:rooms_photos,id',

		);

		$messages = array('required' => ':attribute is required.', 'image_id.*' => 'Invalid Image Id ');

		$validator = Validator::make($all_request, $rules, $messages);

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
			//check valid usr or not
			if (@$user->id != @$rooms_info->user_id) {

				return response()->json([
					'success_message' => 'Permission Denied',

					'status_code' => '0',
				]);
			}
			//delete room iamge
			$qry = RoomsPhotos::where('room_id', $request->room_id)

				->whereIn('id', $all_request['image_id'])->delete();
			// get room photo count
			$room_photo_count = RoomsPhotos::where('room_id', $request->room_id);
			//find the featured is set or not
			if ($room_photo_count->count() > 0) {
				$change_featured = @$room_photo_count->where('featured', '=', 'Yes')->first();
				// dd($change_featured);

				if ($change_featured == null) {
					$featured_image_update = RoomsPhotos::where('room_id', $request->room_id)->first();

					DB::table('rooms_photos')->whereId($featured_image_update->id)->update(['featured' => 'Yes']);

				}
			}

			if ($room_photo_count->count() < 1) {

				$rooms_status = RoomsStepsStatus::find($request->room_id);

				$rooms_status->photos = 0;
				$rooms_info->status = "Unlisted";
				$rooms_status->save(); //save stepstatus
				$rooms_info->save(); //save roomstatus
			}

			if ($qry) {
				//get image list
				$image_list = @RoomsPhotos::where('room_id', $request->room_id)->get();

				foreach ($image_list as $image_data) {

					$room_image[] = @$image_data->name;

					$room_image_id[] = @(string) $image_data->id;

				}

			} else {
				return response()->json([

					'success_message' => 'Image Id Not Found',

					'status_code' => '0',

				]);

			}

			return response()->json([

				'success_message' => 'Room Image Successfully Deleted',

				'status_code' => '1',

				'room_thumb_images' => @$room_image != null

				? @$room_image : array(),

				'room_image_id' => @$room_image_id != null

				? @$room_image_id : array(),

			]);
		}

	}
	/**
	 * Contact Request send to Host
	 *
	 * @param array $request Input values
	 * @return redirect to Rooms Detail page
	 */
	public function contact_request(Request $request, EmailController $email_controller) {
		$rules = array(

			'room_id' => 'required|exists:rooms,id',

			'check_in_date' => 'required|date_format:d-m-Y',

			'check_out_date' => 'required|date_format:d-m-Y|after:today|after:check_in_date',

			'no_of_guest' => 'required|integer|min:1',

		);

		$niceNames = array('room_id' => 'Room Id', 'check_in_date' => 'Check In Date');

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
		//prevent own listing request
		$rooms_verify = Rooms::find($request->room_id);
		$user_id = JWTAuth::parseToken()->authenticate()->id;

		if ($user_id == $rooms_verify->user_id) {

			return response()->json([

				'success_message' => 'Permission Denied',

				'status_code' => '0',

			]);
		}

		$rooms_total_guest = Rooms::where('id', $request->room_id)->pluck('accommodates');
		//check guest count is valid or not
		if ($request->no_of_guest < 1 || $request->no_of_guest > $rooms_total_guest) {

			return response()->json([

				'success_message' => 'Maximum Total Guest Limit - ' . $rooms_total_guest,

				'status_code' => '0',

			]);
		}

		$data['price_list'] = json_decode($this->payment_helper->price_calculation($request->room_id, $request->check_in_date, $request->check_out_date, $request->no_of_guest));

		if (@$data['price_list']->status == 'Not available') {

			return response()->json([

				'success_message' => @$data['price_list']->error ?: 'Those Dates Are Not Available',

				'status_code' => '0',

			]);
		}

		$rooms = Rooms::find($request->room_id);

		$reservation = new Reservation;

		$reservation->room_id = $request->room_id;
		$reservation->host_id = $rooms->user_id;
		$reservation->user_id = JWTAuth::parseToken()->authenticate()->id;
		$reservation->checkin = date('Y-m-d', strtotime($request->check_in_date));
		$reservation->checkout = date('Y-m-d', strtotime($request->check_out_date));
		$reservation->number_of_guests = $request->no_of_guest;
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
		$reservation->type = 'contact';
		$reservation->country = 'US';

		$reservation->base_per_night = $data['price_list']->base_rooms_price;
		$reservation->length_of_stay_type = $data['price_list']->length_of_stay_type;
		$reservation->length_of_stay_discount = $data['price_list']->length_of_stay_discount;
		$reservation->length_of_stay_discount_price = $data['price_list']->length_of_stay_discount_price;
		$reservation->booked_period_type = $data['price_list']->booked_period_type;
		$reservation->booked_period_discount = $data['price_list']->booked_period_discount;
		$reservation->booked_period_discount_price = $data['price_list']->booked_period_discount_price;

		$reservation->save();

		$replacement = "[removed]";

		$email_pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
		$url_pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
		$phone_pattern = "/\+?[0-9][0-9()\s+]{4,20}[0-9]/";

		$find = array($email_pattern, $phone_pattern);
		$replace = array($replacement, $replacement);

		$question = preg_replace($find, $replace, $request->message_to_host);
		$question = preg_replace($url_pattern, $replacement, $question);

		$message = new Messages;

		$message->room_id = $request->room_id;
		$message->reservation_id = $reservation->id;
		$message->user_to = $rooms->user_id;
		$message->user_from = JWTAuth::parseToken()->authenticate()->id;
		$message->message = $question;
		$message->message_type = 9;
		$message->read = 0;

		$message->save();

		$email_controller->inquiry($reservation->id, $question);

		return response()->json([

			'success_message' => 'Contact request has sent to Host',

			'status_code' => '1',

		]);

	}

	/**
	 *Display Room type and Property type
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function room_property_type(Request $request) {

		$room_type = RoomType::where('status', 'Active')->get()->toArray();

		$property_type = PropertyType::where('status', 'Active')->get()->toArray();

		$bed_type = BedType::where('status', 'Active')->select('id', 'name')->get()->toArray();

		if (count($room_type) && count($property_type)) {
			return response()->json([
				'room_type' => ($room_type != '') ? $room_type : '',

				'property_type' => ($property_type != '') ? $property_type : '',

				'bed_type' => ($bed_type != '') ? $bed_type : '',

				'success_message' => 'Success',

				'status_code' => '1',

				'length_of_stay_options' => Rooms::getLenghtOfStayOptions() ?: array(),
				'availability_rules_options' => Rooms::getAvailabilityRulesMonthsOptions() ?: array(),

			]);
		} else {
			return response()->json([

				'success_message' => 'Please add room type or property type',

				'status_code' => '0',

			]);
		}

	}
}
