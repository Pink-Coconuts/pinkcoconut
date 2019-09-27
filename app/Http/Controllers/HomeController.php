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

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\Http\Helper\FacebookHelper;
use App\Http\Start\Helpers;
use App\Models\BottomSlider;
use App\Models\Contactus;
use App\Models\Currency;
use App\Models\Help;
use App\Models\HelpSubCategory;
use App\Models\HomeCities;
use App\Models\HostBanners;
/*HostExperiencePHPCommentStart*/
use App\Models\HostExperienceCategories;
use App\Models\HostExperiences;
/*HostExperiencePHPCommentEnd*/
use App\Models\OurCommunityBanners;
use App\Models\Pages;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Models\SiteSettings;
use App\Models\Slider;
use App\Models\ThemeSettings;
use App\Models\HelpTranslations;
use App\Models\Hotelpartners;

use Auth;
use Illuminate\Http\Request;
use Route;
use Session;
use Validator;
use View;

class HomeController extends Controller {
	protected $helper; // Global variable for Helpers instance

	/**
	 * Constructor to Set FacebookHelper instance in Global variable
	 *
	 * @param array $fb   Instance of FacebookHelper
	 */
	public function __construct() {
		$this->helper = new Helpers;
	}

	/**
	 * Load Home view file
	 *
	 * @return home page view
	 */
	public function index() {
		$data['redirect'] = SiteSettings::where('name', 'default_home')->get();
		$data['popular_rooms'] = Rooms::where('status', 'Listed')->get();
		
		$data['result'] = ThemeSettings::get();
		$data['browser'] = '';
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$agent = $_SERVER['HTTP_USER_AGENT'];
			if (strlen(strstr($agent, "Chrome")) > 0 && (strlen(strstr($agent, "Edge")) <= 0)) {
				$data['browser'] = 'chrome';
			}
		}

		$data['home_city'] = HomeCities::all();
		$data['city_count'] = HomeCities::all()->count();

		$data['home_page_media'] = SiteSettings::where('name', 'home_page_header_media')->first()->value;
		$data['home_page_sliders'] = Slider::whereStatus('Active')->orderBy('order', 'asc')->whereFrontEnd('Homepage')->get();
		$data['home_page_bottom_sliders'] = BottomSlider::whereStatus('Active')->orderBy('order', 'asc')->get();
		$data['host_banners'] = HostBanners::all();
		$data['bottom_sliders'] = BottomSlider::whereStatus('Active')->orderBy('order', 'asc')->get();
		$data['our_community_banners'] = OurCommunityBanners::get();

		//home page two data start

		$data['recommented'] = Rooms::whereHas('users', function ($query) {$query->where('status', 'Active');})->orderBy('id', 'desc')->where('recommended', 'Yes')->where('status', 'Listed')->groupBy('id')->get();
		$data['room_recommented_view'] = count($data['recommented']);
		$data['hotel_partners'] = Hotelpartners::where('status','Active')->get();
		//dd($data);
		/*HostExperiencePHPCommentStart*/
		// $data['host_experiences'] = HostExperiences::with('currency', 'category_details', 'city_details')->homePage()->get();

		/*HostExperiencePHPCommentEnd*/
		//redirect home page
		if ($data['redirect'][0]->value == 'home_two') {
			$data['hotel_partners'] = Hotelpartners::where('status','Active')->get();
			$data['default_home'] = 'two';return view('home.home_two', $data);
		} else {return view('home.home', $data);}}

	public function phpinfo() {
		echo phpinfo();
	}

	/**
	 * Load Login view file with Generated Facebook login URL
	 *
	 * @return Login page view
	 */
	public function login() {
		return view('home.login');
	}

	/**
	 * Load Social OR Email Signup view file with Generated Facebook login URL
	 *
	 * @return Signup page view
	 */
	public function signup_login(Request $request) {
		$data['class'] = '';

		// Social Signup Page
		if ($request->input('sm') == 1 || $request->input('sm') == '') {
			Session::put('referral', $request->referral);
			return view('home.signup_login', $data);
		}

		// Email Signup Page
		else if ($request->input('sm') == 2) {
			return view('home.signup_login_2', $data);
		} else {
			abort(500);
		}
		// Call 500 error page
	}

	public function generateFacebookurl() {

		/*$this->helper->flash_message('danger', trans('messages.login.facebook_https_error')); // Call flash message function
		return redirect('login'); // Redirect to contact page*/
		if (!session_id()) {
		session_start();
		}

		$fb = new FacebookHelper;
		$fb_url = $fb->getUrlLogin();
		return redirect($fb_url);
	}

	/**
	 * Set session for Currency & Language while choosing footer dropdowns
	 *
	 */
	public function set_session(Request $request) {
		if ($request->currency) {
			Session::put('currency', $request->currency);
			Session::put('previous_currency', $request->previous_currency);
			$symbol = Currency::original_symbol($request->currency);
			Session::put('symbol', $symbol);
			Session::put('search_currency', $request->previous_currency);
		} else if ($request->language) {
			Session::put('language', $request->language);
			App::setLocale($request->language);
		}
	}

	/**
	 * View Cancellation Policies
	 *
	 * @return Cancellation Policies view file
	 */
	public function cancellation_policies() {
		return view('home.cancellation_policies');
	}

	/**
	 * View Static Pages
	 *
	 * @param array $request  Input values
	 * @return Static page view file
	 */
	public function static_pages(Request $request) {
		if ($request->token != '') {
			Session::put('get_token', $request->token);

		}
		$pages = Pages::where(['url' => $request->name,'status' => 'active']);
		if (!$pages->count())
		//abort('404');
		{
			return redirect('404');
		}

		$pages = $pages->first();

		$data['content'] = str_replace(['SITE_NAME', 'SITE_URL'], [SITE_NAME, url('/')], $pages->content);
		$data['title'] = $pages->name;

		return view('home.static_pages', $data);
	}

	public function help(Request $request) {

		if ($request->token != '') {
			Session::put('get_token', $request->token);

		}

		if (Route::current()->uri() == 'help') {
			$data['result'] = Help::whereSuggested('yes')->whereStatus('Active')->get();
			//$data['token']  =$request->token;
		} elseif (Route::current()->uri() == 'help/topic/{id}/{category}') {
			$count_result = HelpSubCategory::find($request->id);
			$data['subcategory_count'] = $count = (str_slug($count_result->name, '-') != $request->category) ? 0 : 1;
			$data['is_subcategory'] = (str_slug($count_result->name, '-') == $request->category) ? 'yes' : 'no';
			if ($count) {
				$data['result'] = Help::whereSubcategoryId($request->id)->whereStatus('Active')->get();
			} else {
				$data['result'] = Help::whereCategoryId($request->id)->whereStatus('Active')->get();
			}

		} else {
			$data['result'] = Help::whereId($request->id)->whereStatus('Active')->get();
			$data['is_subcategory'] = ($data['result'][0]->subcategory_id) ? 'yes' : 'no';
		}

		$data['category'] = Help::with(['category', 'subcategory'])->whereStatus('Active')->groupBy('category_id')->get(['category_id', 'subcategory_id']);

		return view('home.help', $data);
	}

	public function ajax_help_search(Request $request) {
		$term = $request->term;

		$queries = Help::where('question', 'like', '%' . $term . '%')->get();
		$queries_translate = HelpTranslations::where('name', 'like', '%' . $term . '%')->get();
		if ($queries->isEmpty() && $queries_translate->isEmpty()) {
			$results[] = ['id' => '0', 'value' => trans('messages.search.no_results_found'), 'question' => trans('messages.search.no_results_found')];
		} else {
			foreach ($queries as $query) {
				$results[] = ['id' => $query->id, 'value' => str_replace('SITE_NAME', SITE_NAME, $query->question), 'question' => str_slug($query->question, '-')];
			}
			foreach ($queries_translate as $translate) {
				$results[] = ['id' => $translate->help_id, 'value' => str_replace('SITE_NAME', SITE_NAME, $translate->name), 'question' => str_slug($translate->name, '-')];
			}
		}

		return json_encode($results);
	}

	public function contact() {
		return view('home.contact');
	}

	public function contact_create(Request $request, EmailController $email_controller) {

		$rules = array(
			'name' => 'required',
			'email' => 'required|max:255|email',
			'feedback' => 'required|min:6',
		);

		$messages = array(
			//'required' => ':attribute is required.',
		);

		$niceNames = array(
			'name' => trans('messages.contactus.name'),
			'email' => trans('messages.contactus.email'),
			'feedback' => trans('messages.contactus.feedback'),
		);

		$validator = Validator::make($request->all(), $rules, $messages);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput(); // Form calling with
		} else {
			$user_contact = new Contactus;

			$user_contact->name = $request->name;
			$user_contact->email = $request->email;
			$user_contact->feedback = $request->feedback;

			$user_contact->save(); // Create a new user

			$email_controller->contact_email_confirmation($user_contact);

			$this->helper->flash_message('success', trans('messages.contactus.sent_successfully')); // Call flash message function
			return redirect('contact'); // Redirect to contact page

		}
	}

	public function ajax_home() {
		$data['host_experiences'] = collect();
		$data['featured_host_experience_categories'] = collect();
		/*HostExperiencePHPCommentStart*/

		$data['host_experiences'] = HostExperiences::
			select('id','price_per_guest','currency_code','updated_at','user_id','category','city','title')
			->with(['currency' => function($q) {
			    $q->select('code','symbol');
			}])
			->with(['category_details' => function($q) {
			    $q->select('id','name');
			}])
			->with(['city_details' => function($q) {
			    $q->select('id','name');
			}])
			->latest()->limit(9)->homePage()
			->get();

		$data['featured_host_experience_categories'] = HostExperienceCategories::select('id','name')->
		with(['host_experiences'=>function($query){
			$query->select('id','price_per_guest','currency_code','updated_at','user_id','category','city','title');
		}])
		->homePage()
		->get();
		/*HostExperiencePHPCommentEnd*/
		$data['reservation'] = Reservation::
			with([
				'rooms' => function ($query) {
					$query->with(['rooms_price'=>function($query1){
						$query1->select('room_id','night','currency_code','cleaning','additional_guest','security','weekend');
					}])
					->select('id','property_type','room_type','bed_type','user_id','beds','name','booking_type');
				}, 
				'currency'=>function($query){
					$query->select('code','symbol');
				}
			])
			->selectRaw('id,created_at,status,checkin,checkout,number_of_guests,host_id,user_id,currency_code,room_id, max(id) as reservation_id')
			->where('list_type', 'Rooms')
			->whereHas('rooms', function ($query) {
				$query->where('status', 'Listed');
			})
			->whereHas('host_users', function ($query) {
				$query->where('status', 'Active');
			})
			->orderBy('reservation_id', 'desc')
			->where('status', 'Accepted')
			->groupBy('room_id')
			->limit(9)
			->get();

		$data['recommented'] = Rooms::
			select('id','property_type','room_type','bed_type','user_id','beds','name')
			->with(['rooms_price' => function ($query) {
				$query->select('room_id','night','currency_code','cleaning','additional_guest','security','weekend')
				->with(['currency'=>function($query1){
					$query1->select('code','symbol');
				}]);
			}])
			->whereHas('users', function ($query) {
				$query->where('status', 'Active');
			})
			->orderBy('id', 'desc')
			->where('recommended', 'Yes')
			->where('status', 'Listed')
			->groupBy('id')
			->limit(9)
			->get();

		$data['view_count'] = Rooms::
			select('id','property_type','room_type','bed_type','user_id','beds','name','booking_type')
			->with(['rooms_price' => function ($query) {
				$query->select('room_id','night','currency_code','cleaning','additional_guest','security','weekend')
				->with(['currency'=>function($query1){
					$query1->select('code','symbol');
				}]);
			}])
			->whereHas('users', function ($query) {
				$query->where('status', 'Active');
			})
			->orderBy('views_count', 'desc')
			->where('status', 'Listed')
			->groupBy('id')
			->limit(9)
			->get();

		return array(
			'host_experiences' => $data['host_experiences'],
			'featured_host_experience_categories' => $data['featured_host_experience_categories'], 
			'reservation' => $data['reservation'], 
			'recommented' => $data['recommented'], 
			'most_viewed' => $data['view_count'],
		);

	}
	public function ajax_home_explore() 
	{
       $data['home_city'] = HomeCities::select('id','image','name')->get();
       $data['our_community_banners'] = OurCommunityBanners::select('id','image','link','title','description')->get();
		return array('home_city' => $data['home_city'],'our_community_banners' => $data['our_community_banners']);
	}


	public function ajax_our_community() 
	{
		$data['our_community_banners'] = OurCommunityBanners::select('id','image','link','title','description')->get();
      
		return array('our_community_banners' => $data['our_community_banners']);
	}
}
