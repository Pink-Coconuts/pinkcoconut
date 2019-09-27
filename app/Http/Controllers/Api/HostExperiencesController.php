<?php
 
/**
 * Host Experiences Controller
 *
 * @package    Makent
 * @subpackage Controller
 * @category   Host Experiences
 * @author     Trioangle Product Team
 * @version    1.6
 * @link       http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\SiteSettings;

use App\Models\Admin;
use App\Models\User;
use App\Models\Reservation;
use App\Models\HostExperienceCities;
use App\Models\HostExperienceCategories;
use App\Models\HostExperienceProvideItems;
use App\Models\HostExperiences;
use App\Models\HostExperiencePhotos;
use App\Models\HostExperienceLocation;
use App\Models\HostExperienceGuestRequirements;
use App\Models\HostExperienceProvides;
use App\Models\HostExperiencePackingLists;
use App\Models\HostExperienceTranslations;
use App\Models\HostExperienceProvideTranslations;
use App\Models\HostExperiencePackingListTranslations;
use App\Models\HostExperienceCalendar;
use App\Models\Messages;
use App\Models\Language;
use App\Models\Country;
use App\Models\SavedWishlists;
use App\Models\Fees;
use App\Models\Reviews;
use App\Models\Currency;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use Auth;
use DB;
use DateTime;
use Session;
use Validator;
use JWTAuth;

class HostExperiencesController extends Controller
{
    protected $payment_helper; // Global variable for Payment Helpers instance
    protected $helper; // Global variable for Helpers instance
    
    /**
     * Constructor to Set Global variables
     */
    public function __construct()
    {
        // Initialize the helpers instances
        $this->payment_helper = new PaymentHelper;
        $this->helper = new Helpers;
    }

    /**
     * List all host experience categories
     *
     * @return json response
     */
    public function host_experience_categories()
    {
        $host_experience_categories = HostExperienceCategories::active()->get();

        $host_experience_categories = $host_experience_categories->map(
            function ($category) {
                return [
                    'name'  => $category->name,
                    'id'    => $category->id
                ];
            }
        );

        return response()->json(
            [
                'success_message'   => 'Host experience categories listed successfully',
                'status_code'   => '1',
                'host_experience_categories' => $host_experience_categories
            ]
        );
    }

   

    /**
    * To save the contact host message Ajax
    * 
    * @param Illuminate\Http\Request $request
    * @return Array [status, location]
    */
    public function contact_host(Request $request)
    {
        $rules = [
            'host_experience_id'    => 'required|integer|exists:host_experiences,id'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success_message'   => $validator->messages()->first(),
                    'status_code'   => '0'
                ]
            );
        }
        $user_details = JWTAuth::parseToken()->authenticate();

        $host_experience_id = $request->host_experience_id;
        $host_experience    = HostExperiences::listed()->where('id', $host_experience_id)->first();
        $status  = 200;

        if($user_details->id == $host_experience->user_id)
        {
             return response()->json(
                [
                    'success_message'   => 'Try again',
                    'status_code'   => '0'
                ]
            );
        }
        $contact_message = $this->helper->phone_email_remove($request->message);
        $mobile_web_auth_user_id = $user_details->id;

        $reservation = new Reservation;
        $reservation->room_id           = $host_experience->id;
        $reservation->list_type         = 'Experiences';
        $reservation->host_id           = $host_experience->user_id;
        $reservation->user_id           = $mobile_web_auth_user_id;
        $reservation->currency_code     = $host_experience->currency->code;
        $reservation->paypal_currency   = PAYPAL_CURRENCY_CODE;
        $reservation->country           = Country::first()->short_name;
        $reservation->cancellation      = 'Flexible';
        $reservation->paymode           = 'Paypal';
        $reservation->status            = 'Expired';
        $reservation->type              = 'contact';
        $reservation->save();

        $message = new Messages;
        $message->room_id        = $host_experience->id;
        $message->list_type      = 'Experiences';
        $message->reservation_id = $reservation->id;
        $message->user_to        = $reservation->host_id;
        $message->user_from      = $reservation->user_id;
        $message->message        = $contact_message;
        $message->message_type   = 9;
        $message->read           = 0;
        $message->save();

        $email_controller = new EmailController;
        $email_controller->experience_inquiry_mail($reservation->id, $contact_message);

            
       return response()->json(
                [
                    'success_message'   => 'Message send Successfully',
                    'status_code'   => '1'
                ]
            );

    }
    /**
     * Experience Details data 
     * 
     * @param  Illuminate\Http\Request $request
     * @return Response Json response
     */
    public function experience_details(Request $request)
    {
        $rules = [
            'host_experience_id'    => 'required|integer|exists:host_experiences,id'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success_message'   => $validator->messages()->first(),
                    'status_code'   => '0'
                ]
            );
        }
        $host_experience_id = $request->host_experience_id;
        $experience    = HostExperiences::where('id', $host_experience_id)->first();

        $blocked_all_dates = $experience->host_experience_calendar()->where('date','>',date('Y-m-d',strtotime("-1 days")))->where('date','<',date('Y-m-d',strtotime("+364 days")))->notAvailable(1)->pluck('date');

        $blocked_dates=[];
        foreach ($blocked_all_dates as $key => $date) {
           
            $availablity = $experience->get_date_availability_details($date, true);
            if(!$availablity['is_available_booking'])
            {
                $blocked_dates[] = date("d-m-Y",strtotime($date));
            }  
        }

        if(request('token'))
        $user_details = JWTAuth::parseToken()->authenticate();
        $currency_code =  $this->helper->get_user_currency_code();

        
            if($experience->admin_status!='Approved'){

                return response()->json(
                    [
                        'success_message'   => 'Experience is not approved by admin',
                        'status_code'   => '0'
                    ]
                );

            }
       
        
        if($experience->preparation_hours){
            $current_date = date('Y-m-d');
            $estimated_date = date('Y-m-d',strtotime('+'.$experience->preparation_hours.'hours'));
            
            while($current_date <= $estimated_date)
            {   
                $availablity = $experience->get_date_availability_details($current_date, true);
                if(!in_array($current_date, $blocked_dates) && !$availablity['is_available_booking']){
                    $blocked_dates[] = date("d-m-Y",strtotime($current_date));
                }
                $current_date = date("Y-m-d", strtotime("+1 day", strtotime($current_date)));
            }

        }
        
        $similar_items = HostExperiences::listed()->approved()->where('id', '!=', $experience->id)->where('city', $experience->city)->get();
        $similar_list_details = $similar_items->map(
            function ($experience) use($currency_code) {
                return [
                    'experience_id'   => $experience->id,
                    'user_id'  => $experience->user_id,
                    'experience_price' => $experience->session_price,
                    'experience_name'  => $experience->title,
                    'experience_thumb_images'   => $experience->photo_name,
                    'experience_category'   => $experience->category_details->name,
                    'rating_value'  => $experience->overall_star_rating['rating_value'] ? (string)$experience->overall_star_rating['rating_value'] : '0',
                    'reviews_count' => $experience->reviews_count,
                    'latitude'  => $experience->host_experience_location->latitude,
                    'longitude'  => $experience->host_experience_location->longitude,
                    'is_wishlist'  => $experience->overall_star_rating['is_wishlist'],
                    'country_name'  => $experience->host_experience_location->country_name,
                    'city_name'        => $experience->host_experience_location->city ?: $experience->host_experience_location->country_name,
                    'currency_code'    => $currency_code ,
                    'currency_symbol'  => Currency::original_symbol($currency_code ),
                    
                ];
            }
        )->toArray();
        $can_book = isset($user_details)?(($user_details->id == $experience->user_id) ? "No" : "Yes"):'Yes';


            if($experience->guest_requirements->allowed_under_2=="Yes")
                $under =  trans('experiences.manage.free_for_under_2');
             else
                $under = '';

                 
        return response()->json(
            [
                'success_message'   => 'Experience details listed successfully',
                'status_code'   => '1',
                'can_book'  => $can_book,
                'experience_id' => $experience->id,
                'experience_price'  =>$experience->session_price,
                'experience_name'   => $experience->title,
                'experience_images'   => $experience->host_experience_photos->map(
                    function ($photo) {
                        return [
                            'name'  => $photo->image_url
                        ];
                    }
                ),
                'experience_share_url' => url('experiences/'.$experience->id),
                'is_whishlist'  => $experience->overall_star_rating['is_wishlist'],
                'rating_value'  => $experience->overall_star_rating['rating_value'] ? (string)$experience->overall_star_rating['rating_value'] : '0',
                'reviews_count' => $experience->reviews_count,
                // 'review_rating' => '',
                'host_user_id'  => $experience->user_id,
                'host_user_name'=> $experience->host_name,
                'host_user_image'=> $experience->user->profile_picture->header_src,
                'host_user_description' => $experience->about_you,
                'no_of_guest'   => $experience->number_of_guests,
                'start_time'    => date("H:i", strtotime($experience->start_time)),
                'end_time'      => date("H:i", strtotime($experience->end_time)),
                'category_type' => $experience->category_details->name,
                'locaiton_name' => $experience->host_experience_location->location_name,
                'city_name'     => $experience->host_experience_location->city ?: $experience->host_experience_location->country_name,
                'city'          => $experience->city_details->name,
                'loc_latitude'  => $experience->host_experience_location->latitude,
                'loc_longitude' => $experience->host_experience_location->longitude,
                'hours'         => $experience->total_hours,
                'provide_items' => $experience->provide_items_names,
                'language'      => $experience->language_details->name,
                'what_will_do'    => $experience->what_will_do,
                'what_i_provide'=> $experience->host_experience_provides->map(
                    function ($provide) {
                        return [
                            'name'  => $provide->name,
                            'description'   => $provide->additional_details,
                            'image' => $provide->provide_item->image_url
                        ];
                    }
                )->toArray(),
                'notes'         => $experience->notes,
                'includes_alcohol'  => $experience->guest_requirements->includes_alcohol,
                'who_can_come'  => $experience->guest_requirements->minimum_age,
                'where_will_be' => $experience->where_will_be,
                'currency_code' => $currency_code,
                'currency_symbol' => Currency::original_symbol($currency_code),
                'blocked_dates' => $blocked_dates,
                'similar_list_details'  => $similar_list_details,
                'who_can_come_heading'  => trans('experiences.manage.who_can_come'),
                'who_can_come'  => trans('experiences.details.guest_ages_age_and_up_can_attend', ['count' => $experience->guest_requirements->minimum_age]),
                'minimum_age'  =>$experience->guest_requirements->minimum_age < 18 ? trans('experiences.details.bring_guest_under_18_your_responsibility'):'',
                'alcohol'  =>$experience->guest_requirements->includes_alcohol  == 'Yes' ? trans('experiences.details.this_alcohol_includes_only_for_legal_age'):'',
                'additional_heading' => trans('experiences.details.from_the_host'),
                'alcohol_heading' => trans('experiences.manage.alcohol'),
                'minimum_age_heading' => trans('experiences.details.bringing_guests_under_18'),
                'additional_requirements'  =>($experience->guest_requirements->special_certifications || $experience->guest_requirements->additional_requirements)? ($experience->guest_requirements->special_certifications?$experience->guest_requirements->special_certifications:''):''.''.$experience->guest_requirements->additional_requirements,
                'allowed_under_2' => $under,
            ]
        );
    }

    /**
    * To set the review data in selected experience 
    * 
    * @param Illuminate\Http\Request $request
    * @return Array [status, scheduled_id]
    */
    public function experience_review_detail(Request $request)
    {
        $rules = [
            'host_experience_id'    => 'required|integer|exists:host_experiences,id'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success_message'   => $validator->messages()->first(),
                    'status_code'   => "0"
                ]
            );
        }
        $host_experience_id = $request->host_experience_id;
        $page = $request->page ? $request->page : 1;
        $experience    = HostExperiences::where('id', $host_experience_id)->first();
        $results = $experience->reviews()->paginate(5);
        if($results->count()){

            $return_data['status_code'] = "1";  
            $return_data['success_message'] = 'success';
            $return_data['total_page'] = $results->lastPage();
            $return_data['data'] = $results->map(
                function ($review) {
                    $profile_picture = $review->users_from->profile_picture?$review->users_from->profile_picture->src:'';
                    return [
                        'review_user_name' => $review->users_from->first_name,
                        'review_user_image' => $profile_picture,
                        'review_date' => $review->DateFy,
                        'review_message' => $review->comments,

                    ];
                }
            );
        }
        else
        {
            $return_data['status_code'] = "0";  
            $return_data['success_message'] = 'No reviews found';  
        }
        return $return_data;
    }

    /**
    * To set the payment data in session based on choosed date
    * 
    * @param Illuminate\Http\Request $request
    * @return Array [status, scheduled_id]
    */
    public function choose_date(Request $request)
    {
        $rules = [
            'host_experience_id'    => 'required|integer|exists:host_experiences,id',
            'date' => 'required|date_format:d-m-Y|after:today'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success_message'   => $validator->messages()->first(),
                    'status_code'   => '0'
                ]
            );
        }
        $user_details = JWTAuth::parseToken()->authenticate();

        

        $host_experience_id = $request->host_experience_id;
        $host_experience    = HostExperiences::listed()->where('id', $host_experience_id)->first();


            if($user_details->id == $host_experience->user_id)
            {
                return response()->json(
                [
                    'success_message'   => "Cannot book because listing is yours ",
                    'status_code'   => '0'
                ]
              );
            }

        $date = $request->date;
        $availablity = $host_experience->get_date_availability_details($date, true);
        if(!@$availablity['is_available_booking'])
        {
            return response()->json(
                [
                    'success_message'   => "The date is not available",
                    'status_code'   => '0'
                ]
            );
        }
        $availablity['number_of_guests'] = 1;
        $availablity['host_experience_id'] = $host_experience_id;
        $guest_details=array('first_name'=>$request->first_name,'last_name'=>$request->last_name,'email'=>$request->email);
        $availablity['guest_details'][] = $guest_details;
        $scheduled_id = time().str_random(5);
        Session::put('experience_payment.'.$scheduled_id, $availablity);
        Session::save();

        return response()->json(
            [
                'success_message'   => "Pre payment successfully created",
                'status_code'   => '1',
                'scheduled_id' => $scheduled_id,
                'guest_user_image' => $user_details->profile_picture->src,
                'guest_first_name' => $user_details->first_name,
                'guest_last_name' => $user_details->last_name,
                'spots_left'    => $availablity['spots_left'] - 1,
            ]
        );
    }

    /**
    * To add guest_details in session based on choosed date
    * 
    * @param Illuminate\Http\Request $request
    * @return Array [status, scheduled_id]
    */
    public function add_guest_details(Request $request)
    {
        $rules = [
            'host_experience_id'    => 'required|integer|exists:host_experiences,id',
            'scheduled_id'          => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success_message'   => $validator->messages()->first(),
                    'status_code'   => '0'
                ]
            );
        }
        $host_experience_id = $request->host_experience_id;
        $scheduled_id = $request->scheduled_id;
        $scheduled_detail = Session::get('experience_payment.'.$scheduled_id);
        if(!$scheduled_detail)
        {
            return response()->json(
                [
                    'success_message'   => 'Try again',
                    'status_code'   => '0'
                ]);
        }
        // dd('sf',$scheduled_detail);
        $host_experience    = HostExperiences::listed()->where('id', $host_experience_id)->first();

        $date = $scheduled_detail['date'];
        $availablity = $host_experience->get_date_availability_details($date, true);
        if(!@$availablity['is_available_booking'])
        {
            return response()->json(
                [
                    'success_message'   => "The date is not available",
                    'status_code'   => '0'
                ]
            );
        }
        $availablity['number_of_guests'] = $scheduled_detail['number_of_guests']+1;
        $availablity['host_experience_id'] = $host_experience_id;
        if(isset($scheduled_detail['guest_details']))
            $availablity['guest_details']= $scheduled_detail['guest_details'];
        $guest_details=array('first_name'=>$request->first_name,'last_name'=>$request->last_name,'email'=>$request->email);
        $availablity['guest_details'][] = $guest_details;

        Session::put('experience_payment.'.$scheduled_id, $availablity);
        Session::save();
        $schedule = session('experience_payment.'.$scheduled_id);
        return response()->json(
            [
                'success_message'   => "Pre payment successfully created",
                'status_code'   => '1',
                'scheduled_id' => $scheduled_id,
                'guest_details' => $schedule['guest_details'],
            ]
        );
    }

}
