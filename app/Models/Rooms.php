<?php

/**
 * Rooms Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Rooms
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use App\Models\RoomType;
use App\Models\User;
use Auth;
use Config;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;
use Request;
use Session;

class Rooms extends Model {
	/* use SoftDeletes;*/

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'rooms';

	protected $fillable = ['summary', 'name'];

	protected $appends = ['steps_count', 'property_type_name', 'room_type_name', 'bed_type_name', 'photo_name', 'host_name', 'reviews_count', 'overall_star_rating', 'reviews_count_lang', 'bed_lang','popup_price'];

	protected $dates = ['deleted_at'];

	public function setSummaryAttribute($input) {
		$this->attributes['summary'] = strip_tags($input);
	}
	public function setNameAttribute($input) {
		$this->attributes['name'] = strip_tags($input);
	}

	public function setRoomTypeAttribute($input) {
		$room_type = RoomType::where('id', $input)->first();
		$is_shared = @$room_type->is_shared == 'Yes' ? 'Yes' : 'No';
		$this->attributes['room_type'] = $input;
		$this->attributes['is_shared'] = $is_shared;
	}

	// Check rooms table user_id is equal to current logged in user id
	public static function check_user($id) {
		return Rooms::where(['id' => $id, 'user_id' => Auth::user()->id])->first();
	}

	// Join with rooms_address table
	public function rooms_address() {
		return $this->belongsTo('App\Models\RoomsAddress', 'id', 'room_id');
	}

	// Join with rooms_price table
	public function rooms_price() {
		return $this->belongsTo('App\Models\RoomsPrice', 'id', 'room_id');
	}

	// Join with rooms_price table
	public function room_type_data() {
		return $this->belongsTo('App\Models\RoomType', 'room_type', 'id');
	}

	// Join with rooms_description table
	public function rooms_description() {
		return $this->belongsTo('App\Models\RoomsDescription', 'id', 'room_id');
	}

	// Join with saved_wishlists table
	public function saved_wishlists() {
		return $this->belongsTo('App\Models\SavedWishlists', 'id', 'room_id');
	}

	// Join with reviews table
	public function reviews() {
		return $this->hasMany('App\Models\Reviews', 'room_id', 'id')->where('user_to', $this->attributes['user_id'])->where('list_type', 'Rooms');
	}

	// Reviews Count
	public function getReviewsCountAttribute() {
		$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id'])->where('list_type', 'Rooms');

		return $reviews->count();
	}

	// Bed Count
	public function getBedLangAttribute() {

		return ucfirst(trans_choice('messages.lys.bed', @$this->attributes['beds']));

	}

	// Reviews Count
	public function getReviewsCountLangAttribute() {

		return ucfirst(trans_choice('messages.header.review', $this->getReviewsCountAttribute()));
	}

	// Overall Reviews Star Rating
	public function getOverallStarRatingAttribute() {

		if (request()->segment(1) == 'api') {

			//get review details
			$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

			if ($reviews->count() == 0) {
				$result['rating_value'] = '0';
			} else {
				$result['rating_value'] = @($reviews->sum('rating') / $reviews->count());

			}

			$result['is_wishlist'] = "No";
			if (request('token')) {

				$user_details = JWTAuth::parseToken()->authenticate();

				$result_wishlist = SavedWishlists::with('wishlists')->where('room_id', $this->attributes['id'])->where('user_id', $user_details->id);

				if ($result_wishlist->count() == 0) {
					$result['is_wishlist'] = "No";
				} else {
					$result['is_wishlist'] = "Yes";
				}
			}
			return $result;

		} else {

			$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

			$average = @($reviews->sum('rating') / $reviews->count());

			if ($average > 0) {
				$html = '<div class="star-rating"> <div class="foreground">';

				$whole = floor($average);
				$fraction = $average - $whole;

				for ($i = 0; $i < $whole; $i++) {
					$html .= ' <i class="icon icon-beach icon-star"></i>';
				}

				if ($fraction >= 0.5) {
					$html .= ' <i class="icon icon-beach icon-star-half"></i>';
				}

				$html .= ' </div> <div class="background mb_blck">';
				$html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
				$html .= ' </div> </div>';
				return $html;
			} else {
				return '';
			}

		}
	}

	//PrideIlusionScore

	public function getPrideScoreStarRatingAttribute() {

		$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

		$average = @($reviews->sum('pride_comments') / $reviews->count());

		if ($average) {
			$html = '<div class="star-rating"> <div class="foreground">';

			$whole = floor($average);
			$fraction = $average - $whole;

			for ($i = 0; $i < $whole; $i++) {
				$html .= ' <i class="icon icon-beach icon-star"></i>';
			}

			if ($fraction >= 0.5) {
				$html .= ' <i class="icon icon-beach icon-star-half"></i>';
			}

			$html .= ' </div> <div class="background mb_blck">';
			$html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
			$html .= ' </div> </div>';
			return $html;
		} else {
			return '';
		}

	}



	// Accuracy Reviews Star Rating
	public function getAccuracyStarRatingAttribute() {
		$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

		$average = @($reviews->sum('accuracy') / $reviews->count());

		if ($average) {
			$html = '<div class="star-rating"> <div class="foreground">';

			$whole = floor($average);
			$fraction = $average - $whole;

			for ($i = 0; $i < $whole; $i++) {
				$html .= ' <i class="icon icon-beach icon-star"></i>';
			}

			if ($fraction >= 0.5) {
				$html .= ' <i class="icon icon-beach icon-star-half"></i>';
			}

			$html .= ' </div> <div class="background mb_blck">';
			$html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
			$html .= ' </div> </div>';
			return $html;
		} else {
			return '';
		}

	}

	// Location Reviews Star Rating
	public function getLocationStarRatingAttribute() {
		$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

		$average = @($reviews->sum('location') / $reviews->count());

		if ($average) {
			$html = '<div class="star-rating"> <div class="foreground">';

			$whole = floor($average);
			$fraction = $average - $whole;

			for ($i = 0; $i < $whole; $i++) {
				$html .= ' <i class="icon icon-beach icon-star"></i>';
			}

			if ($fraction >= 0.5) {
				$html .= ' <i class="icon icon-beach icon-star-half"></i>';
			}

			$html .= ' </div> <div class="background mb_blck">';
			$html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
			$html .= ' </div> </div>';
			return $html;
		} else {
			return '';
		}

	}

	// Communication Reviews Star Rating
	public function getCommunicationStarRatingAttribute() {
		$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

		$average = @($reviews->sum('communication') / $reviews->count());

		if ($average) {
			$html = '<div class="star-rating"> <div class="foreground">';

			$whole = floor($average);
			$fraction = $average - $whole;

			for ($i = 0; $i < $whole; $i++) {
				$html .= ' <i class="icon icon-beach icon-star"></i>';
			}

			if ($fraction >= 0.5) {
				$html .= ' <i class="icon icon-beach icon-star-half"></i>';
			}

			$html .= ' </div> <div class="background mb_blck">';
			$html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
			$html .= ' </div> </div>';
			return $html;
		} else {
			return '';
		}

	}

	// Checkin Reviews Star Rating
	public function getCheckinStarRatingAttribute() {
		$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

		$average = @($reviews->sum('checkin') / $reviews->count());

		if ($average) {
			$html = '<div class="star-rating"> <div class="foreground">';

			$whole = floor($average);
			$fraction = $average - $whole;

			for ($i = 0; $i < $whole; $i++) {
				$html .= ' <i class="icon icon-beach icon-star"></i>';
			}

			if ($fraction >= 0.5) {
				$html .= ' <i class="icon icon-beach icon-star-half"></i>';
			}

			$html .= ' </div> <div class="background mb_blck">';
			$html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
			$html .= ' </div> </div>';
			return $html;
		} else {
			return '';
		}

	}

	// Cleanliness Reviews Star Rating
	public function getCleanlinessStarRatingAttribute() {
		$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

		$average = @($reviews->sum('cleanliness') / $reviews->count());

		if ($average) {
			$html = '<div class="star-rating"> <div class="foreground">';

			$whole = floor($average);
			$fraction = $average - $whole;

			for ($i = 0; $i < $whole; $i++) {
				$html .= ' <i class="icon icon-beach icon-star"></i>';
			}

			if ($fraction >= 0.5) {
				$html .= ' <i class="icon icon-beach icon-star-half"></i>';
			}

			$html .= ' </div> <div class="background mb_blck">';
			$html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
			$html .= ' </div> </div>';
			return $html;
		} else {
			return '';
		}

	}

	// Value Reviews Star Rating
	public function getValueStarRatingAttribute() {
		$reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

		$average = @($reviews->sum('value') / $reviews->count());

		if ($average) {
			$html = '<div class="star-rating"> <div class="foreground">';

			$whole = floor($average);
			$fraction = $average - $whole;

			for ($i = 0; $i < $whole; $i++) {
				$html .= ' <i class="icon icon-beach icon-star"></i>';
			}

			if ($fraction >= 0.5) {
				$html .= ' <i class="icon icon-beach icon-star-half"></i>';
			}

			$html .= ' </div> <div class="background mb_blck">';
			$html .= '<i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i> <i class="icon icon-star icon-light-gray"></i>';
			$html .= ' </div> </div>';
			return $html;
		} else {
			return '';
		}

	}
	//Get rooms photo all
	public function rooms_photos() {
		return $this->hasMany('App\Models\RoomsPhotos', 'room_id', 'id');

	}

	// Get rooms featured photo_name URL
	public function getPhotoNameAttribute() {
		$result = RoomsPhotos::where('room_id', $this->attributes['id'])->where('featured', 'Yes');

		if ($result->count() == 0) {
			return "room_default_no_photos.png";
		} else {
			return $result->first()->name;
		}

	}

	// Get rooms featured photo_name URL
	public function getPopupPriceAttribute() {
		$result = RoomsPrice::where('room_id', $this->attributes['id']);

			return @$result->first()->night;
		

	}
	// Get rooms featured photo_name URL
	public function getSrcAttribute() {
		$result = RoomsPhotos::where('room_id', $this->attributes['id'])->where('featured', 'Yes');

		if ($result->count() == 0) {
			return url('/') . "/images/room_default_no_photos.png";
		} else {
			return $result->first()->name;
		}

	}

	// Get rooms featured photo_name URL
	public function getBannerPhotoNameAttribute() {
		$result = RoomsPhotos::where('room_id', $this->attributes['id'])->where('featured', 'Yes');

		if ($result->count() == 0) {
			return "room_default_no_photos.png";
		} else {
			return $result->first()->banner_image_name;
		}

	}

	// Get steps_count using sum of rooms_steps_status
	public function getStepsCountAttribute() {
		$result = RoomsStepsStatus::find($this->attributes['id']);

		return 6 - (@$result->basics+@$result->description+@$result->location+@$result->photos+@$result->pricing+@$result->calendar);
	}

	// Join with users table
	public function users() {
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	// Join with calendar table
	public function calendar() {
		// return $this->hasMany('App\Models\Calendar','room_id','id');
		return $this->hasMany('App\Models\Calendar', 'room_id', 'id')
			->where('status', 'Not available');
	}

	public function calendar_data() {
		return $this->hasMany('App\Models\Calendar', 'room_id', 'id');
	}

	// Get property_type_name from property_type table
	public function getPropertyTypeNameAttribute() {
		return PropertyType::find($this->attributes['property_type'])->name;
	}

	// Get room_type_name from room_type table
	public function getRoomTypeNameAttribute() {
		return RoomType::find($this->attributes['room_type'])->name;
	}

	// Get host name from users table
	public function getHostNameAttribute() {
		return User::find($this->attributes['user_id'])->first_name;
	}

	// Get bed_type_name from bed_type table
	public function getBedTypeNameAttribute() {
		if ($this->attributes['bed_type'] != NULL) {
			return BedType::find($this->attributes['bed_type'])->name;
		} else {
			return $this->attributes['bed_type'];
		}

	}

	public function getLinkAttribute() {
		$site_settings_url = @SiteSettings::where('name', 'site_url')->first()->value;
		$url = \App::runningInConsole() ? $site_settings_url : url('/');
		$this_link = $url . '/rooms/' . $this->id;
		return $this_link;
	}

	// Get host user data
	public function scopeUser($query) {
		return $query->where('user_id', '=', Auth::user()->id);
	}

	// Get Created at Time for Rooms Listed
	public function getCreatedTimeAttribute() {
		$new_str = new DateTime($this->attributes['updated_at'], new DateTimeZone(Config::get('app.timezone')));
		$new_str->setTimeZone(new DateTimeZone(Auth::user()->timezone));

		return date(PHP_DATE_FORMAT, strtotime($this->attributes['updated_at'])) . ' at ' . $new_str->format('h:i A');
	}

	// delete for rooms relationship data (for all table) $this->attributes['id']
	public function Delete_All_Room_Relationship() {
		if ($this->attributes['id'] != '') {
			$RoomsPrice = RoomsPrice::find($this->attributes['id']);if ($RoomsPrice != '') {$RoomsPrice->delete();};
			$RoomsAddress = RoomsAddress::find($this->attributes['id']);if ($RoomsAddress != '') {$RoomsAddress->delete();};
			$RoomsPhotos = RoomsPhotos::where('room_id', $this->attributes['id']);if ($RoomsPhotos != '') {$RoomsPhotos->delete();};
			$RoomsDescription = RoomsDescription::find($this->attributes['id']);if ($RoomsDescription != '') {$RoomsDescription->delete();};
			$RoomsStepsStatus = RoomsStepsStatus::find($this->attributes['id']);if ($RoomsStepsStatus != '') {$RoomsStepsStatus->delete();};
			$SavedWishlists = SavedWishlists::where('room_id', $this->attributes['id']);if ($SavedWishlists != '') {$SavedWishlists->delete();};
			$RoomsDescriptionLang = RoomsDescriptionLang::where('room_id', $this->attributes['id']);if ($RoomsDescriptionLang != '') {$RoomsDescriptionLang->delete();};
			$ImportedIcal = ImportedIcal::where('room_id', $this->attributes['id'])->delete();
			$Calendar = Calendar::where('room_id', $this->attributes['id'])->delete();
			SavedWishlists::where('room_id', $this->attributes['id'])->delete();
			RoomsPriceRules::where('room_id', $this->attributes['id'])->delete();
			RoomsAvailabilityRules::where('room_id', $this->attributes['id'])->delete();
			Rooms::find($this->attributes['id'])->delete();
			return true;
		}

	}

	public function getNameAttribute() {

		if (Request::segment(1) == 'manage-listing' || Request::segment(1) == ADMIN_URL) {return $this->attributes['name'];}

		$default_lang = Language::where('default_language', 1)->first()->value;

		$lang = Language::whereValue((Session::get('language')) ? Session::get('language') : $default_lang)->first()->value;

		if ($lang == 'en') {
			return $this->attributes['name'];
		} else {
			$name = @RoomsDescriptionLang::where('room_id', $this->attributes['id'])->where('lang_code', $lang)->first()->name;
			if ($name) {
				return $name;
			} else {
				return $this->attributes['name'];
			}

		}
	}

	public function getSummaryAttribute() {

		if (Request::segment(1) == 'manage-listing' || Request::segment(1) == ADMIN_URL) {return $this->attributes['summary'];}

		$default_lang = Language::where('default_language', 1)->first()->value;

		$lang = Language::whereValue((Session::get('language')) ? Session::get('language') : $default_lang)->first()->value;

		if ($lang == 'en') {
			return $this->attributes['summary'];
		} else {
			$name = @RoomsDescriptionLang::where('room_id', $this->attributes['id'])->where('lang_code', $lang)->first()->summary;
			if ($name) {
				return $name;
			} else {
				return $this->attributes['summary'];
			}

		}
	}

	public function getRoomCreatedAtAttribute() {
		return date(PHP_DATE_FORMAT, strtotime($this->attributes['created_at']));
	}

	public function getRoomUpdatedAtAttribute() {
		return date(PHP_DATE_FORMAT, strtotime($this->attributes['created_at']));
	}

	public function price_rules() {
		return $this->hasMany('App\Models\RoomsPriceRules', 'room_id', 'id');
	}

	public function length_of_stay_rules() {
		return $this->price_rules()->type('length_of_stay');
	}

	public function early_bird_rules() {
		return $this->price_rules()->type('early_bird');
	}

	public function last_min_rules() {
		return $this->price_rules()->type('last_min');
	}

	public function availability_rules() {
		return $this->hasMany('App\Models\RoomsAvailabilityRules', 'room_id', 'id');
	}

	public static function getLenghtOfStayOptions($keep_keys = false) {
		$nights = Request::segment(1) == ADMIN_URL ? 'nights' : trans_choice('messages.rooms.night', 2);
		$weekly = Request::segment(1) == ADMIN_URL ? 'Weekly' : trans('messages.lys.weekly');
		$monthly = Request::segment(1) == ADMIN_URL ? 'Monthly' : trans('messages.lys.monthly');

		$length_of_stay_options = [
			2 => [
				'nights' => 2,
				'text' => '2 ' . $nights,
			],
			3 => [
				'nights' => 3,
				'text' => '3 ' . $nights,
			],
			4 => [
				'nights' => 4,
				'text' => '4 ' . $nights,
			],
			5 => [
				'nights' => 5,
				'text' => '5 ' . $nights,
			],
			6 => [
				'nights' => 6,
				'text' => '6 ' . $nights,
			],
			7 => [
				'nights' => 7,
				'text' => $weekly,
			],
			14 => [
				'nights' => 14,
				'text' => '14 ' . $nights,
			],
			28 => [
				'nights' => 28,
				'text' => $monthly,
			],
		];
		if (!$keep_keys) {
			$length_of_stay_options = array_values($length_of_stay_options);
		}
		return $length_of_stay_options;
	}

	public static function getAvailabilityRulesMonthsOptions() {
		$month = date('m');
		$year = date('Y');
		$this_time = $start_time = mktime(12, 0, 0, $month, 1, $year);
		$end_time = mktime(12, 0, 0, $month, 1, $year + 1);

		$format = PHP_DATE_FORMAT;
		if (request()->segment(1) == 'api') {
			$format = ('d-m-Y');
		}

		$availability_rules_months_options = collect();
		$i = 1;
		while ($this_time < $end_time) {
			$loop_time = mktime(12, 0, 0, $month + ($i * 3), 0, $year);
			$start_month = date('F', $this_time);
			$end_month = date('F', $loop_time);
			$start_year = date('Y', $this_time);
			$end_year = date('Y', $loop_time);
			$start_month = trans('messages.lys.'.$start_month);
			$end_month = trans('messages.lys.'.$end_month);
			$start_year_month = $start_month.' '.$start_year;
			$end_year_month = $end_month.' '.$end_year;
			$availability_rules_months_options[] = [
				'text' => $start_year_month . ' - ' . $end_year_month,
				'start_date' => date($format, $this_time),
				'end_date' => date($format, $loop_time),
			];
			$this_time = strtotime('+1 day', $loop_time);
			$i++;
		}
		return $availability_rules_months_options;
	}
}
