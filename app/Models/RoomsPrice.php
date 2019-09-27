<?php

/**
 * Rooms Price Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Rooms Price
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;
use Session;

class RoomsPrice extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'rooms_price';

	public $timestamps = false;

	protected $primaryKey = 'room_id';

	protected $appends = ['steps_count', 'original_night', 'original_cleaning', 'original_additional_guest', 'original_security', 'original_weekend', 'code'];

	// Join with currency table
	public function currency() {
		return $this->belongsTo('App\Models\Currency', 'currency_code', 'code');
	}

	// Get actual result of night price
	public function getOriginalNightAttribute() {
		return $this->attributes['night'];
	}

	// Get actual result of cleaning price
	public function getOriginalCleaningAttribute() {
		return $this->attributes['cleaning'];
	}

	// Get actual result of additional_guest price
	public function getOriginalAdditionalGuestAttribute() {
		return $this->attributes['additional_guest'];
	}

	// Get actual result of security price
	public function getOriginalSecurityAttribute() {
		return $this->attributes['security'];
	}

	// Get actual result of weekend price
	public function getOriginalWeekendAttribute() {
		return $this->attributes['weekend'];
	}

	// Get result of night price for current currency
	public function getNightAttribute() {
		return $this->currency_calc('night');
	}

	// Get result of cleaning price for current currency
	public function getCleaningAttribute() {
		return $this->currency_calc('cleaning');
	}

	// Get result of additional_guest price for current currency
	public function getAdditionalGuestAttribute() {
		return $this->currency_calc('additional_guest');
	}

	// Get result of security price for current currency
	public function getSecurityAttribute() {
		return $this->currency_calc('security');
	}

	// Get result of weekend price for current currency
	public function getWeekendAttribute() {
		return $this->currency_calc('weekend');
	}

	// Get steps_count using sum of rooms_steps_status
	public function getStepsCountAttribute() {
		$result = RoomsStepsStatus::find($this->attributes['room_id']);
		if ($result) {
			return 6 - ($result->basics + $result->description + $result->location + $result->photos + $result->pricing + $result->calendar);
		} else {
			return 6;
		}

	}

	// Get result of night price for given date
	public function price($date) {
		$where = ['room_id' => $this->attributes['room_id'], 'date' => $date];

		$result = Calendar::where($where);

		if ($result->count()) {
			return $result->first()->price;
		} else {
			if ((date('N', strtotime($date)) == 5 || date('N', strtotime($date)) == 6) && $this->attributes['weekend'] != 0) {
				return $this->attributes['weekend'];
			} else {
				return $this->attributes['night'];
			}
//return $this->currency_calc('night');
		}
	}

	// Get result of calendar event status for given date
	public function status($date) {
		$where = ['room_id' => $this->attributes['room_id'], 'date' => $date];

		$result = Calendar::where($where);

		if ($result->count()) {
			return $result->first()->status;
		} else {
			return false;
		}

	}

	// Get result of calendar notes for given date
	public function notes($date) {
		$where = ['room_id' => $this->attributes['room_id'], 'date' => $date];

		$result = Calendar::where($where);

		if ($result->count()) {
			return $result->first()->notes;
		} else {
			return false;
		}

	}

	// Get result of calendar notes for given date
	public function spots_left($date) {
		$where = ['room_id' => $this->attributes['room_id'], 'date' => $date];

		$result = Calendar::where($where);

		if ($result->count()) {
			return $result->first()->spots_booked > 0 && $result->first()->is_shared == 'Yes' ? $result->first()->spots_left : false;
		} else {
			return false;
		}

	}

	// Calculation for current currency conversion of given price field
	public function currency_calc($field) {

		if (request()->segment(1) == 'api' || strlen(request()->token) > 25  ) {

			$rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;

			$usd_amount = $this->attributes[$field] / $rate;

			$default_currency = Currency::where('default_currency', 1)->first()->code;

			if (request('token')) {
				$user_details = JWTAuth::parseToken()->authenticate();
				$currency_code = $user_details->currency_code;
			} else {
				$currency_code = $default_currency;
			}

			$api_currency = $currency_code;

			$session_rate = Currency::whereCode($currency_code != null ? $currency_code : $default_currency)->first()->rate;

			return round($usd_amount * $session_rate);

		} else {
			$rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;

			$usd_amount = $this->attributes[$field] / $rate;

			$default_currency = Currency::where('default_currency', 1)->first()->code;

			$session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->rate;

			return round($usd_amount * $session_rate);
		}
	}

	// Get default currency code if session is not set
	public function getCodeAttribute() {

		if (request()->segment(1) == 'api' || request()->token !='') {

			if (request('token')) {

				if (JWTAuth::parseToken()->authenticate()->currency_code)
				//set user currency code
				{
					return JWTAuth::parseToken()->authenticate()->currency_code;
				} else
				//set default currency  code . for user currency code not given.
				{
					return DB::table('currency')->where('default_currency', 1)->first()->code;
				}
			} else {

				return DB::table('currency')->where('default_currency', 1)->first()->code;

			}

		} else {
			if (Session::get('currency')) {
				return Session::get('currency');
			} else {
				return DB::table('currency')->where('default_currency', 1)->first()->code;
			}

		}

	}

	// Check the given date has any reservation
	public function has_reservation($date)
	{
		$is_reservation = Reservation::whereRoomId($this->attributes['room_id'])->where('list_type', 'Rooms')->where('type', 'reservation')->whereRaw('status!="Declined"')->whereRaw('status!="Expired"')->whereRaw('status!="Cancelled"')->whereRaw('(checkin = "'.$date.'" or (checkin < "'.$date.'" and checkout > "'.$date.'")) ')->get()->count();
        if($is_reservation > 0) {
            return false;
        }
        else {
            return true;
        }
	}
}
