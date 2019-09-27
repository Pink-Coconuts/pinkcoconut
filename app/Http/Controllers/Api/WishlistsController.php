<?php

/**
 * Wishlists Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Wishlists
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Rooms;
use App\Models\HostExperiences;
use App\Models\SavedWishlists;
use App\Models\Wishlists;
use Auth;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class WishlistsController extends Controller {
	/**
	 *Load Wishlists
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */

	public function add_wishlists(Request $request) {

		$rules['room_id'] = 'required';

		if ($request->list_id != '') 
				$rules['list_id'] = 'exists:wishlists,id';

		if (!$request->list_id && !$request->list_name ) 
			{
				return response()->json([

					'success_message' => 'Try again',

					'status_code' => '0',

				]);
			}

		$niceNames = array('room_id' => 'Room Id', 'list_id' => 'List Id');
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
			$list_type = $request->list_type?$request->list_type:'Rooms';

			if($list_type=='Rooms')
			{
				if(!Rooms::find($request->room_id))
					{
						return response()->json([

							'success_message' => 'Invalid Room Id',

							'status_code' => '0',

						]);
					}
			}
			if($list_type=='Experiences')
			{
				if(!HostExperiences::find($request->room_id))
					{
						return response()->json([

							'success_message' => 'Invalid Experience Id',

							'status_code' => '0',

						]);
					}
			}
			//check request list_id is present or not
			if ($request->list_id != '') {
				//Get Wishlist details
				$check_wishlist = Wishlists::where('user_id', JWTAuth::parseToken()->authenticate()->id)

					->where('id', $request->list_id)->first();
				//get Wishlist cout
				if (count($check_wishlist) > 0) {

					//get Saved wishlit count
					$check_save_wishlist = SavedWishlists::whereWishlistId($check_wishlist->id)

						->whereUserId(JWTAuth::parseToken()->authenticate()->id)

						->whereRoomId($request->room_id)->whereListType($list_type)->count();
					//check saved wishlist is empty or not
					if ($check_save_wishlist < 1) {

						$save_wishlist 				= new SavedWishlists;

						$save_wishlist->room_id 	= $request->room_id;

						$save_wishlist->wishlist_id = $check_wishlist->id;

						$save_wishlist->list_type 	= $list_type;

						$save_wishlist->user_id 	= JWTAuth::parseToken()->authenticate()->id;

						$save_wishlist->save(); //save Wishlist

						return response()->json([

							'success_message' => 'Wishlist Added Sucessfully',

							'status_code' => '1',

						]);
					} else {
						return response()->json([

							'success_message' => 'Wishlist Already Selected',

							'status_code' => '0',

						]);
					}
				}
			}

			//check list name is present or not
			if ($request->list_name != '') {
				//Add new Wishlist
				$wishlist 			= new Wishlists;

				$wishlist->name 	= $request->list_name;

				$wishlist->user_id 	= JWTAuth::parseToken()->authenticate()->id;

				$wishlist->privacy 	= @$request->privacy_settings == '1' ? '1' : '0';

				$wishlist->save(); //save wishist

				$save_wishlist 				= new SavedWishlists;

				$save_wishlist->room_id 	= $request->room_id;

				$save_wishlist->wishlist_id = $wishlist->id;

				$save_wishlist->list_type 	= $list_type;

				$save_wishlist->user_id 	= JWTAuth::parseToken()->authenticate()->id;

				$save_wishlist->save(); //save savedwishlists

				return response()->json([
					'success_message' => 'Wishlist Added Sucessfully',

					'status_code' => '1',

					'list_id' => $wishlist->id,

				]);

			}
		}
	}
	/**
	 *Display Wishlist Resource
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function get_whishlist(Request $request) {

		$user = JWTAuth::parseToken()->authenticate();
		$result = Wishlists::with(['saved_wishlists' => function ($query) {

			$query->with(['rooms','host_experiences']);

		}])->wherehas('saved_wishlists', function ($query){

			$query->wherehas('rooms', function ($query) {

				$query->where('status', 'Listed');
			})->orWhereHas('host_experiences', function ($query) {

				$query->where('status', 'Listed');
			});

		})->where('wishlists.user_id', $user->id)->get();

		$room_count = count($result);

		if (@$room_count > 0) {
			$list = array();
			foreach ($result as $result_data) {
				//savedwishlist details				
				$image = array_merge($result_data->all_host_experience_image,$result_data->all_rooms_image);
				@$list[] = array(

					'list_id' => $result_data->id,

					'list_name' => $result_data->name,
					'host_experience_count' => $result_data->host_experience_count,
					'rooms_count' => $result_data->rooms_count,

					'room_thumb_images' => $image,

					'privacy' => $result_data->privacy,

				);

			}

			return response()->json([

				'success_message' => 'Wishlist Was Listed Successfully',

				'status_code' => '1',

				'wishlist_data' => @$list,

			]);
		} else {

			return response()->json([

				'success_message' => 'Wishlist Not Found',

				'status_code' => '0',

			]);

		}
	}
	/**
	 *Display particular wishlist based on wishlist id
	 *
	 * @param  Get method inputs
	 * @return Response in Json
	 */
	public function get_particular_wishlist(Request $request) {

		$rules = array('list_id' => 'required|exists:wishlists,id');

		$list_type = $request->list_type ? $request->list_type : 'Rooms';

		$niceNames = array('list_id' => 'List Id');

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

			$currency_details = @Currency::where('code', $user->currency_code)->first();

			//Check Wishlist or Not
			$check = @Wishlists::where('user_id', $user->id)->where('id', $request->list_id)->get();
			//dd($check);
			if (count($check) > 0) {
				if($list_type=='Rooms')
					return $this->room_wishlist($request->list_id,$currency_details,$user);
				else
					return $this->experience_wishlist($request->list_id,$currency_details,$user);
				
			} else {
				return response()->json([

					'success_message' => 'Wishlist Not Found',

					'status_code' => '0',

				]);
			}
		}
	}


	public function room_wishlist($list_id,$currency_details,$user)
	{		
		//get savedwishlsits details
				$result = @SavedWishlists::with(['rooms' => function ($query) {

					$query->with('rooms_address');

				},

					'rooms_price',

					'rooms_price' => function ($query) {

						$query->with('currency');

					},

					'users', 'profile_picture',

				])->wherehas('rooms', function ($query) {

					$query->where('status', 'Listed');

				})

					->where('user_id', $user->id)

					->where('saved_wishlists.wishlist_id', $list_id)
					->where('saved_wishlists.list_type', 'Rooms')->get();

				foreach ($result as $result_data) {
					//dd($result_data);
					$room_details = $result_data->rooms;

					$room_price_details = $result_data->rooms_price;

					$room_rooms_address_details = $result_data->rooms->rooms_address;
					//dd($room_details);

					$list[] = array(

						'room_id' => $result_data->rooms->id,

						'room_type' => $room_details->room_type_name,

						'room_name' => $room_details->name != null

						? $room_details->name : $room_details->sub_name,

						'room_thumb_image' => $room_details->photo_name,

						'rating_value' => $room_details->overall_star_rating['rating_value'],

						'reviews_count' => (string) $room_details->reviews_count,

						'is_wishlist' => 'Yes',

						'instant_book' => $room_details->booking_type == 'instant_book' ? 'Yes' : 'No',

						'latitude' => '',

						'longitude' => '',

						'country_name' => $room_rooms_address_details->country_name,

						'currency_code' => $user->currency_code,

						'currency_symbol' => $currency_details->original_symbol,

						'room_price' => (string) $room_price_details->night,

					);

				}

				return json_encode([

					'success_message' => 'Wishlist Listed Successfully',

					'status_code' => '1',

					'wishlist_details' => @$list != null ? $list : array(),

				], JSON_UNESCAPED_SLASHES);
	}

	public function experience_wishlist($list_id,$currency_details,$user)
	{
		//get savedwishlsits details
		$check = Wishlists::whereId($list_id)->whereUserId(@Auth::user()->id)->first();
        $wishlist = Wishlists::with([
            'saved_wishlists' => function($query){
                $query->with([
                    'host_experiences' => function($query){
                        $query->with('host_experience_location','host_experience_photos','currency','city_details');
                    },
                    'users', 
                    'profile_picture'
                ])->where('list_type', 'Experiences');
        }])->where('id', $list_id);
        if($check) 
        {
            $wishlist =$wishlist->first();
        }
        else 
        {
            $wishlist =$wishlist->where('privacy','0')->first();
        }


				foreach ($wishlist->saved_wishlists as $result_experiences) {
					// dd($wishlist->saved_wishlists->toArray(),$result_data->toArray());
					$result_data = $result_experiences->host_experiences;
					// dd($result_data->id,$result_data);
					// $room_price_details = $result_data->rooms_price;

					// $room_rooms_address_details = $result_data->rooms->rooms_address;
					//dd($room_details);

					$list[] = array(

						'room_id' => $result_data->id,

						'room_type' => $wishlist->list_type,

						'room_name' => @$result_data->title != null

						? $result_data->title : '',

						'room_thumb_image' => $result_data->photo_name,

						'rating_value' => $result_data->overall_star_rating['rating_value'],

						'reviews_count' => (string) $result_data->reviews_count,

						'is_wishlist' => 'Yes',

						'latitude' => '',

						'longitude' => '',

						'country_name' => $result_data->host_experience_location->location_name,

						'currency_code' => $user->currency_code,

						'currency_symbol' => $currency_details->original_symbol,
						
						'room_price' => (string) $result_data->session_price,

					);

				}

				return json_encode([

					'success_message' => 'Wishlist Listed Successfully',

					'status_code' => '1',

					'wishlist_details' => @$list != null ? $list : array(),

				], JSON_UNESCAPED_SLASHES);
	}
	/**
	 *Delete wishlist
	 *
	 * @param  Get method inputs
	 * @param  Get only list_id delete all list
	 * @param  Get room_id  delete specific room
	 * @return Response in Json
	 */
	public function delete_wishlist(Request $request) {
		$list_type = $request->list_type?$request->list_type:'Rooms';

		if ($request->room_id != '' && $request->list_id == '') {
			$rules = array('room_id' => 'required');

			$niceNames = array('room_id' => 'Room Id');

		} elseif ($request->room_id == '' && $request->list_id != '') {

			$rules = array('list_id' => 'required|exists:wishlists,id');

			$niceNames = array('list_id' => 'List Id');

		} else {
			return response()->json([

				'success_message' => 'Invalid Request',

				'status_code' => '0']);

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
		}
		//delete  saved wishlist
		if ($request->room_id != '') {
			//deleted the saved wishlist
			$delete = SavedWishlists::whereRoomId($request->room_id)->where('list_type',$list_type)

				->whereUserId(JWTAuth::parseToken()->authenticate()->id);

			if ($delete->count()) {
				$delete->delete();

				//$saved_wishlists_count=SavedWishlists::whereWishlistId($request->list_id)->count();

				return response()->json([

					'success_message' => 'Wishlist Deleted Successfully',

					'status_code' => '1',

					//'wishlist_list_count'=> $saved_wishlists_count

				]);
			} else {
				return response()->json([

					'success_message' => 'Saved Wishlist Not Found',

					'status_code' => '0',

				]);

			}

		} else {
			//delete wishlist
			$delete = Wishlists::whereId($request->list_id)->whereUserId(JWTAuth::parseToken()->authenticate()->id);

			if ($delete->count()) {
				//delete saved wishlists
				$counts = SavedWishlists::whereWishlistId($request->list_id)->delete();

				$delete->delete();

				return response()->json([

					'success_message' => 'Wishlist Deleted Successfully',

					'status_code' => '1',

				]);
			} else {
				return response()->json([

					'success_message' => 'Wishlist Not Found',

					'status_code' => '0',

				]);

			}

		}
	}
	/**
	 *Up wishlist
	 *
	 * @param  Get method inputs
	 * @param  Get list_id and privacy_type  they update privacy
	 * @param  Get list_id and list_name they update list name
	 * @return Response in Json
	 */
	public function edit_wishlist(Request $request) {

		if ($request->list_id != '' && $request->privacy_type != '') {
			$rules = array(

				'list_id' => 'required|exists:wishlists,id',

				'privacy_type' => 'required|numeric|min:0|max:1');

			$niceNames = array('list_id' => 'List Id', 'privacy_type' => 'Privacy Type');

		} elseif ($request->list_id != '' && $request->list_name != '') {
			$rules = array(

				'list_id' => 'required|exists:wishlists,id',

				'list_name' => 'required');

			$niceNames = array('list_id' => 'List Id', 'list_name' => 'List Name');

		} else {
			return response()->json([

				'success_message' => 'Invalid Request',

				'status_code' => '0']);

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
		}

		$wishlist = Wishlists::find($request->list_id);

		if ($request->list_id != '' && $request->privacy_type != '') {
			$wishlist->privacy = $request->privacy_type; //update privacy_type
		}

		if ($request->list_id != '' && $request->list_name != '') {
			$wishlist->name = $request->list_name; //update list name.
		}

		$wishlist->save();

		return response()->json([

			'success_message' => 'WishList Updated Successfully',

			'status_code' => '1']);
	}
}
