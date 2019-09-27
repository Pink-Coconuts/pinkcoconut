<?php

/**
 * TokenAuth Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    TokenAuth
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\Currency;
use App\Models\ProfilePicture;
use App\Models\User;
use App\Models\UsersVerification;
use Auth;
use DateTime;
use DB;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class TokenAuthController extends Controller {
	public function authenticate(Request $request) {
		$credentials = $request->only('email', 'password');

		try {
			if (!$token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials']);
			}
		} catch (JWTException $e) {
			return response()->json(['error' => 'could_not_create_token']);
		}

		// if no errors are encountered we can return a JWT
		return response()->json(compact('token'));
	}

	public function getAuthenticatedUser() {
		try {

			if (!$user = JWTAuth::parseToken()->authenticate()) {
				return response()->json(['error' => 'user_not_found']);
			}

		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

			return response()->json(['error' => 'token_expired']);

		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

			return response()->json(['error' => 'token_invalid']);

		} catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

			return response()->json(['error' => 'token_absent']);

		}

		return response()->json(compact('user'));
	}

	public function register(Request $request, EmailController $email_controller) {
		$user = new User;

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->password = bcrypt($request->password);
		$user->dob = $request->birthday;

		$user->save(); // Create a new user

		$user_pic = new ProfilePicture;

		$user_pic->user_id = $user->id;
		$user_pic->src = "";
		$user_pic->photo_source = 'Local';

		$user_pic->save(); // Create a profile picture record

		$user_verification = new UsersVerification;

		$user_verification->user_id = $user->id;

		$user_verification->save(); // Create a users verification record

		$email_controller->welcome_email_confirmation($user);

		$credentials = $request->only('email', 'password');

		try {
			if (!$token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials']);
			}
		} catch (JWTException $e) {
			return response()->json(['error' => 'could_not_create_token']);
		}

		// if no errors are encountered we can return a JWT
		return response()->json(compact('token'));
	}

	public function token(Request $request) {

		$token = JWTAuth::refresh($request->token);

		return response()->json(['token' => $token], 200);
	}

	/**
	 * Login and signup on Facebook and GooglePlus. And Signup Default
	 *
	 * @return Response in Json
	 */
	public function signup(Request $request, EmailController $email_controller) {

		//validation for signup and login
		if (@$request->email != '') {
			$rules = array(
				'email' => 'exists:users,email',

			);
			$validator = Validator::make($request->all(), $rules);

		} else if ($request->fbid != '' && $request->gpid != '') {

			return response()->json([
				'success_message' => 'Invalid Request...',

				'status_code' => '0',
			]);

		} elseif ($request->fbid != '') {

			$rules = array(
				'fbid' => 'required|exists:users,fb_id',

			);

			$messages = array('required' => ':required.');

			$validator = Validator::make($request->all(), $rules, $messages);
		} elseif ($request->gpid != '') {

			if ($request->gpid != '') {

				$rules = array('gpid' => 'required|exists:users,google_id');

				$messages = array('required' => ':required.');

				$validator = Validator::make($request->all(), $rules, $messages);
			}
		}

		if ($request->fbid == '' && $request->gpid == '') {

			$rules = array('test' => 'required');

			$messages = array('required' => ':required.');

			$validator = Validator::make($request->all(), $rules, $messages);
		}

		//set default currency
		$currency_set = $currency_set = Currency::Where('default_currency', '1')

			->where('status', 'Active')->first();

		$currency_details = @Currency::where('code', @$user->currency_code != null

			? $user->currency_code : $currency_set->code)

			->first()->toArray();

		if (!$validator->fails()) {

			if (@$request->email != '') {

				$user = User::where('email', @$request->email)->first();
				$token = JWTAuth::fromUser($user);
				
				if ($request->profile_pic != '') {

					if($request->gpid!= '')
					{
						DB::table('profile_picture')->whereUser_id($user->id)->update(['photo_source' => 'Google', 'src' => $request->profile_pic]);

                      $user->google_id = $request->gpid;
                      $user->save();

					}
					else if($request->fbid!= '')
					{
						DB::table('profile_picture')->whereUser_id($user->id)->update(['photo_source' => 'Facebook', 'src' => $request->profile_pic]);
						$user->fb_id = $request->fbid;
						$user->save();
					}

						
					}

			} else if ($request->fbid != '' && $request->gpid == '') {

				$user = @User::with('profile_picture')->where('fb_id', $request->fbid)

					->first();

				$email_check = @$user->email;

				if ($email_check == '') {

					return response()->json([

						'success_message' => 'Email Id Missing',

						'status_code' => '0',
					]);
					exit;
				} else {

					if ($request->profile_pic != '') {

						DB::table('profile_picture')->whereUser_id($user->id)->update(['photo_source' => 'Facebook', 'src' => $request->profile_pic]);
					}

					$token = JWTAuth::fromUser($user);

				}

			} elseif ($request->fbid == '' && $request->gpid != '') {

				$user = @User::with('profile_picture')->where('google_id', $request->gpid)
					->first();

				$token = JWTAuth::fromUser($user);

				if ($request->profile_pic != '') {
					$picture = str_replace('5000', '50', $request->profile_pic);
					DB::table('profile_picture')->whereUser_id($user->id)->update(['photo_source' => 'Google', 'src' => $picture]);
				}

			}

			$test_profile_picture = @ProfilePicture::whereUser_id($user->id)->first();

			$profile_img = $request->profile_pic != '' ? $request->profile_pic

			: $test_profile_picture->src;

			$user = array(
				'success_message' => 'SignUp Successfully...',

				'status_code' => '1',

				'access_token' => $token,

				'user_id' => $user->id,

				'first_name' => $user->first_name != null

				? $user->first_name : '',

				'last_name' => $user->last_name != null

				? $user->last_name : '',

				'user_image' => $profile_img != ''

				? $profile_img

				: url() . 'images/user_pic-225x225.png',

				'dob' => $user->dob != null

				? $user->dob : '',

				'email_id' => $user->email != null

				? $user->email : '',

				'currency_code' => $user->currency_code != null

				? $user->currency_code : $currency_set->code,

				'currency_symbol' => @$currency_details['original_symbol'],

			);

			return response()->json($user);

			exit;

		} else {

			if ($request->fbid != '' || $request->gpid != '') {

				if ($request->fbid != '') {

					if ($request->email != '') {
						$rules = array(
							'fbid' => 'required|unique:users,fb_id',

							'email' => 'required|max:255|email|unique:users',

							'first_name' => 'required',

							'last_name' => 'required',
						);
					} else {
						$rules = array(
							'fbid' => 'required|unique:users,fb_id',

							'first_name' => 'required',

							'last_name' => 'required',
						);

					}

				} else {
					if ($request->email != '' && $request->gpid != '') {

						$rules = array(

							'gpid' => 'required|unique:users,google_id',

							'email' => 'required|max:255|email|unique:users',

							'first_name' => 'required',

							'last_name' => 'required',
						);
					} else {
						$rules = array(

							'gpid' => 'required|unique:users,google_id',

							'first_name' => 'required',

							'last_name' => 'required',

						);
					}

				}

			} else {

				$rules = array(

					'email' => 'required|max:255|email|unique:users',

					'dob' => 'required|date',

					'password' => 'required',

					'first_name' => 'required',

					'last_name' => 'required',

				);
			}

			// Email signup validation custom messages
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
				if ($request->fbid == '' && $request->gpid == '') {
					// calculate minimum age limit
					$from = new DateTime($request->dob);

					$to = new DateTime('today');

					$age = $from->diff($to)->y;

					if ($age < 18) {
						$user = array(

							'success_message' => 'You must be 18 or older.',

							'status_code' => '0',

						);

						return response()->json($user);
						exit;
					}

				}
				// Validate Ip Address
				if ($request->ip_address != '') {

					$clean_ip_address = addslashes(htmlspecialchars(strip_tags(trim($request->ip_address))));

					// the regular expression for valid ip addresses
					$reg_ex = '/^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))*$/';

					if (!preg_match($reg_ex, $clean_ip_address)) {

						return response()->json([
							'success_message' => 'Not Valid IP Address',
							'status_code' => '0']
						);exit;
					}

				}
				//get currency code based on IP Address
				if ($request->ip_address != '') {

					$result_contry_code = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $request->ip_address));
					if ($result_contry_code) {
						$currency_code = $result_contry_code['geoplugin_currencyCode'];
					} else {
						$currency_code = $currency_set->code;
					}
				} else {
					$currency_code = $currency_set->code;
				}
				$currency_details = @Currency::where('code', $currency_code)->first()->toArray();

				if ($request->dob != '') {
					$str_date = $request->dob;

					$date = DateTime::createFromFormat('d-m-Y', $str_date);

					$dob = $date->format('Y-m-d');

				}

				$user = new User;

				$user->first_name = $request->first_name;

				$user->last_name = $request->last_name;

				if ($request->fbid != '' || $request->gpid != '') {

					if ($request->email != '') {

						$user->email = $request->email;

					} elseif ($request->fbid != '' && $request->email == '') {

						return response()->json([

							'success_message' => 'Please Update Your Email Id',

							'status_code' => '0',

						]);

					} elseif ($request->gpid != '' && $request->email == '') {

						return response()->json([

							'success_message' => 'Please Update Your Email Id',

							'status_code' => '0',

						]);

					}
					if ($request->password != '') {
						$password_check = $request->password;
						$user->password = bcrypt($password_check);
					} else {
						$password_check = $request->fbid;
						$user->password = bcrypt($password_check);
					}

				} else {
					$user->email = $request->email;

					$user->password = bcrypt($request->password);
				}

				if ($request->fbid == '' && $request->gpid == '') {

					$user->dob = $dob;

				}
				if ($request->fbid != '' || $request->gpid != '') {
					if ($request->dob != '') {
						$user->dob = $dob;
					}
					$user->status = "Active";

				}
				if ($currency_code) {
					$user->currency_code = $currency_code;
				} else {
					$user->currency_code = $currency_set->code;
				}

				if ($request->fbid != '') {
					$user->fb_id = $request->fbid;
				}

				if ($request->gpid != '') {
					$user->google_id = $request->gpid;
				}

				if($request->timezone){
					$user->timezone = $request->timezone;
				}
				
				$user->save(); // Create a new user

				$user_pic = new ProfilePicture;

				if ($request->fbid == '' && $request->gpid == '') {
					$user_pic->user_id = $user->id;

					$user_pic->src = "";

					$user_pic->photo_source = 'Local';

					$user_pic->save(); // Create a profile picture record
				} elseif ($request->fbid != '') {

					$user_pic->user_id = $user->id;

					if ($request->profile_pic != '') {

						$user_pic->src = $request->profile_pic;

					}

					$user_pic->photo_source = 'Facebook';

					$user_pic->save();

				} else {

					$user_pic->user_id = $user->id;

					if ($request->profile_pic != '') {
						$picture = str_replace('5000', '50', $request->profile_pic);
						$user_pic->src = $picture;

					}

					$user_pic->photo_source = 'Google ';

					$user_pic->save(); // Create a profile picture record

				}

				$user_verification = new UsersVerification;

				$user_verification->user_id = $user->id;

				if ($request->fbid != '') {
					$user_verification->facebook = 'yes';
				}
				if ($request->gpid != '') {
					$user_verification->google = 'yes';
				}

				$user_verification->save(); // Create a users verification record

				//send welcome email to user
				if ($request->fbid == '' && $request->gpid == '') {

					$email_controller->welcome_email_confirmation($user);

				}
				//Get Access Token
				if ($request->email != '' && $request->password != '') {

					$credentials = $request->only('email', 'password');

				} else {

					$credentials = array('email' => $user->email, 'password' => $request->fbid);
				}

				try
				{

					if (!$token = JWTAuth::attempt($credentials)) {

						return response()->json([
							'success_message' => 'Signup Failed',

							'status_code' => '0',
						]);
					}

				} catch (JWTException $e) {

					return response()->json([

						'success_message' => 'could_not_create_token',

						'status_code' => '0',

					]);
				}

				if ($request->fbid == '' && $request->gpid == '') {

					$user = array(
						'success_message' => 'signup success',

						'status_code' => '1',

						'user_id' => $user->id,

						'access_token' => $token,

						'first_name' => $request->first_name != null

						? $request->first_name : '',

						'last_name' => $request->last_name != null

						? $request->last_name : '',

						'user_image' => $user->profile_picture->src != null

						? $user->profile_picture->src

						: url() . 'images/user_pic-225x225.png',

						'dob' => $user->dob != null

						? $user->dob : '',

						'email_id' => $request->email != null

						? $request->email : '',

						'currency_code' => $currency_code,

						'currency_symbol' => @$currency_details['original_symbol'],

					);
					return response()->json($user);

				} else {

					$user = array(
						'success_message' => 'signup success',

						'status_code' => '1',

						'access_token' => $token,

						'user_id' => $user->id,

						'first_name' => $request->first_name != null

						? $request->first_name : '',

						'last_name' => $request->last_name != null

						? $request->last_name : '',

						'user_image' => $request->profile_pic != null

						? $request->profile_pic

						: url('images/user_pic-225x225.png'),

						'dob' => $user->dob != null

						? $user->dob : '',

						'email_id' => $user->email != null

						? $user->email : '',

						'currency_code' => $currency_code,

						'currency_symbol' => @$currency_details['original_symbol'],

					);

					return response()->json($user);

				}
			}
		}

	}
/**
 * User Login
 *
 * @return Response in Json
 */
	public function login(Request $request) {
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			$credentials = $request->only('email', 'password');

			try {
				if (!$token = JWTAuth::attempt($credentials)) {

					return response()->json([

						'success_message' => "Those credentials don't look right. Please try again.",

						'status_code' => '0',

					]);

				}
			} catch (JWTException $e) {

				return response()->json([

					'success_message' => 'could_not_create_token',

					'status_code' => '0',

				]);

			}

			$user = User::with(['profile_picture'])->whereemail($request->email)->select('id', 'first_name', 'last_name', 'status', 'email', 'dob', 'users.currency_code')->first();

			if ($user->status == 'Inactive') {
				return response()->json([

					'success_message' => 'Account disabled please contact admin',

					'status_code' => '0',

				]);
			}
			// if no errors are encountered we can return a JWT

			//update currency_code
			if ($user->currency_code == '') {

				$currency_set = @Currency::Where('default_currency', '1')

					->where('status', 'Active')->first();

				DB::table('users')->whereId($user->id)

					->update(['currency_code' => $currency_set->code]);

			}
			$currency_details = @Currency::where('code', @$user->currency_code != null

				? $user->currency_code : @$currency_set->code)

				->first()->toArray();

			if($request->timezone){
				$user->timezone = $request->timezone;
				$user->save();
			}

			$user = array(

				'success_message' => 'Login Success',

				'status_code' => '1',

				'access_token' => $token,

				'user_id' => $user->id,

				'first_name' => $user->first_name,

				'last_name' => $user->last_name,

				'user_image' => $user->profile_picture->header_src,

				'dob' => $user->dob,

				'email_id' => $user->email,

				'currency_code' => $user->currency_code != null

				? $user->currency_code

				: @$currency_set->code,

				'currency_symbol' => @$currency_details['original_symbol'],

			);

			return response()->json($user);

		} else {
			return response()->json([

				'success_message' => "Those credentials don't look right. Please try again.",

				'status_code' => '0',

			]);

		}

	}
	/**
	 * User Email Validation
	 *
	 * @return Response in Json
	 */

	public function emailvalidation(Request $request) {
		$rules = array('email' => 'required|max:255|email|unique:users');

		// Email signup validation custom messages
		$messages = array('required' => ':attribute is required.');

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			$user = array('success_message' => 'Email Already exist', 'status_code' => '0');

			return response()->json($user);
		} else {
			$user = array('success_message' => 'Emailvalidation Success', 'status_code' => '1');
			return response()->json($user);
		}
	}
	/**
	 * Forgot Password
	 *
	 * @return Response in Json
	 */
	public function forgotpassword(Request $request, EmailController $email_controller) {
		$rules = array('email' => 'required|email|exists:users,email');

		$messages = array('required' => ':attribute is required.');

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			$user = array('success_message' => 'Invalid Emailid', 'status_code' => '0');

			return response()->json($user);
		} else {
			$user = User::whereEmail($request->email)->first();

			$email_controller->forgot_password($user);

			$user = array(
				'success_message' => 'Reset password link send to your Email id',

				'status_code' => '1',

			);
			return response()->json($user);

		}
	}
}
