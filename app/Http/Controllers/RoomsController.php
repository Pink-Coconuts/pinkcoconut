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

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EmailController;
use App\Models\PropertyType;
use App\Models\PropertyTypeLang;
use App\Models\Language;
use App\Models\RoomType;
use App\Models\Rooms;
use App\Models\RoomsAddress;
use App\Models\BedType;
use App\Models\RoomsStepsStatus;
use App\Models\Country;
use App\Models\Amenities;
use App\Models\AmenitiesType;
use App\Models\RoomsPhotos;
use App\Models\RoomsPrice;
use App\Models\RoomsDescription;
use App\Models\RoomsDescriptionLang;
use App\Models\Calendar;
use App\Models\Currency;
use App\Models\Reservation;
use App\Models\SavedWishlists;
use App\Models\Messages;
use App\Models\SiteSettings;
use App\Models\RoomsPriceRules;
use App\Models\RoomsAvailabilityRules;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Models\User;
use Auth;
use DB;
use DateTime;
use Session;

class RoomsController extends Controller
{
    protected $payment_helper; // Global variable for Helpers instance
    
    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper = new Helpers;
    }

    /**
     * Load Your Listings View
     *
     * @return your listings view file
     */
    public function index()
    {        
        $data['listed_result']   = Rooms::user()->where('status', 'Listed')->get();
        
        $data['unlisted_result'] = Rooms::user()->where(function ($query){
            $query->where('status','Unlisted')->orWhereNull('status');
        })->get();  
        return view('rooms.listings', $data);
    }

    /**
     * Load List Your Space First Page
     *
     * @return list your space first view file
     */
    public function new_room()
    {       


        $data['property_type'] = PropertyType::active_all();        
        $data['room_type']     = RoomType::active_all();

        $data['country_list']   = Country::all()->pluck('short_name');

        return view('list_your_space.new', $data);
    }

    /**
     * Create a new Room
     *
     * @param array $request    Post values from List Your Space first page
     * @return redirect     to manage listing
     */
    public function create(Request $request)
    {
        $country = Country::where('short_name', $request->hosting['country'])->first();
        if (!$country) {
            $this->helper->flash_message('error', trans('messages.lys.service_not_available_country'));
            return back();
        }
        
        $rooms = new Rooms;

        $rooms->user_id       = Auth::user()->id;
        $rooms->sub_name      = RoomType::find($request->hosting['room_type'])->name.' in '.$request->hosting['city'];
        $rooms->property_type = $request->hosting['property_type_id'];
        $rooms->room_type     = $request->hosting['room_type'];
        $rooms->accommodates  = $request->hosting['person_capacity'];
        $rooms->calendar_type = 'Always';
        $rooms->save(); // Store data to rooms Table

        $rooms_address = new RoomsAddress;
        
        $rooms_address->room_id        = $rooms->id;
        $rooms_address->address_line_1 = $request->hosting['street_number'] ? $request->hosting['street_number'].', ' : '';
        $rooms_address->address_line_1.= $request->hosting['route'];
        $rooms_address->city           = $request->hosting['city'];
        $rooms_address->state          = $request->hosting['state'];
        $rooms_address->country        = $request->hosting['country'];
        $rooms_address->postal_code    = $request->hosting['postal_code'];
        $rooms_address->latitude       = $request->hosting['latitude'];
        $rooms_address->longitude      = $request->hosting['longitude'];

        $rooms_address->save(); // Store data to rooms_address Table

        $rooms_price = new RoomsPrice;
        
        $rooms_price->room_id       = $rooms->id;
        $rooms_price->currency_code = Session::get('currency');

        $rooms_price->save();   // Store data to rooms_price table

        $rooms_status = new RoomsStepsStatus;
        $rooms_status->calendar = 1;
        $rooms_status->room_id = $rooms->id;

        $rooms_status->save();  // Store data to rooms_steps_status table

        $rooms_description = new RoomsDescription;
        
        $rooms_description->room_id = $rooms->id;

        $rooms_description->save(); // Store data to rooms_description table

        return redirect('manage-listing/'.$rooms->id.'/basics');
    }

    /**
     * Manage Listing
     *
     * @param array $request    Post values from List Your Space first page
     * @param array $calendar   Instance of CalendarController
     * @return list your space main view file
     */    
    public function manage_listing(Request $request, CalendarController $calendar)
    {
        $data['property_type']  = PropertyType::dropdown();
        $data['room_type']      = RoomType::dropdown();
        $data['room_types']     = RoomType::where('status','Active')->limit(3)->get();
        $data['bed_type']       = BedType::active_all();
        $data['amenities']      = Amenities::active_all();
        $data['amenities_type'] = AmenitiesType::active_all();

        $data['room_type_is_shared']    = RoomType::where('status','Active')->pluck('is_shared', 'id');

        $data['room_id']        = $request->id;
        $data['room_step']      = $request->page;    // It will get correct view file based on page name

        $data['result']         = Rooms::check_user($request->id); // Check Room Id and User Id is correct or not
                
        $data['rooms_status']   = RoomsStepsStatus::where('room_id',$request->id)->first();

        if(count($data['result']) == 0)
            abort('404');

        $data['calendar']       = $calendar->generate($request->id);
        
        $data['prev_amenities'] = explode(',', $data['result']->amenities);

        $data['length_of_stay_options'] = Rooms::getLenghtOfStayOptions();
        $data['availability_rules_months_options'] = Rooms::getAvailabilityRulesMonthsOptions();

        Session::forget('ajax_redirect_url');

        return view('list_your_space.main', $data);
    }

    /**
     * Ajax Manage Listing, while you click steps from sidebar, it will call
     *
     * @param array $request    Post values from List Your Space first page
     * @param array $calendar   Instance of CalendarController
     * @return list your space steps view file
     */ 
    public function ajax_manage_listing(Request $request, CalendarController $calendar)
    {
        $data['property_type']  = PropertyType::dropdown();
        $data['room_type']      = RoomType::dropdown();
        $data['room_types']     = RoomType::where('status','Active')->limit(3)->get();
        $data['bed_type']       = BedType::active_all();
        $data['amenities']      = Amenities::active_all();
        $data['amenities_type'] = AmenitiesType::active_all();

        $data['room_type_is_shared']    = RoomType::where('status','Active')->pluck('is_shared', 'id');

        $data['room_id']        = $request->id;
        $data['room_step']      = $request->page;   

         // It will get correct view file based on page name
        
        $data['result']         = Rooms::check_user($request->id); // Check Room Id and User Id is correct or not        
       
        if(@$data['result'] == NULL) 
        { 
            return json_encode(['success_303'=>'false', 'status' => '300']); exit; 
        }
        $data['success_303'] = 'true';    
        $data['prev_amenities'] = explode(',', $data['result']->amenities);
        
        $data['rooms_status']   = RoomsStepsStatus::where('room_id',$request->id)->first();

        if($request->page == 'calendar')
        {
            $data_calendar     = @json_decode($request['data']);
            $year              = @$data_calendar->year;
            $month             = @$data_calendar->month;
            $data['room_step'] = 'edit_calendar';
            $data['calendar']  = $calendar->generate($request->id, $year, $month);
        }

        $data['length_of_stay_options'] = Rooms::getLenghtOfStayOptions();
        $data['availability_rules_months_options'] = Rooms::getAvailabilityRulesMonthsOptions();
        
        return view('list_your_space.'.$data['room_step'], $data);
    }

    /**
     * Ajax List Your Space Header
     *
     * @param array $request    Post values from List Your Space first page
     * @return list your space header view file
     */ 
    public function ajax_header(Request $request)
    {
        $data['room_id']   = $request->id;
        $data['room_step'] = $request->page;
        
        $data['result']    = Rooms::check_user($request->id); // Check Room Id and User Id is correct or not
        
        return view('list_your_space.header', $data);
    }

    /**
     * Ajax List Your Space Update Rooms Values
     *
     * @param array $request    Post values from List Your Space first page
     * @return json success, steps_count
     */ 
    public function update_rooms(Request $request, EmailController $email_controller)
    {
       
        $data  = $request;        
        $data  = json_decode($data['data']); // AngularJS data decoding

        if($request->current_tab)
                {

                if($request->current_tab == 'en')
                    {
                        $rooms = Rooms::find($request->id); // Where condition for Update
                    }
                else
                    {

                    $des_id = RoomsDescriptionLang::where('room_id', $request->id)->where('lang_code', $request->current_tab)->first()->id;

                    $rooms = RoomsDescriptionLang::find($des_id);

                    }

                }
                else
                {
                        $rooms = Rooms::find($request->id);
                } 
         
        $email = '';

        foreach($data as $key=>$value)
        {
            if($key != 'video')
                $rooms->$key =$this->helper->phone_email_remove($value);     // Dynamic Update
            else {
                $search     = '#(.*?)(?:href="https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12}).*#x';
                $count      = preg_match($search, $value);
                $rooms      = Rooms::find($request->id); 
                if($count == 1) {
                    $replace    = 'https://www.youtube.com/embed/$2';
                    $video      = preg_replace($search,$replace,$value);
                    $rooms->$key = $video;
                }
                else {
                    return json_encode(['success'=>'false', 'steps_count' => $rooms->steps_count]);
                }
            }

            if($key == 'booking_type')
                $rooms->$key = (!empty($value)) ? $value : NULL;

            if($key == 'room_type')
                $rooms->sub_name = RoomType::single_field($value, 'name').' in '.RoomsAddress::single_field($request->id, 'city');

            if($key == 'status' && $value == 'Listed')
                $email = 'Listed';

            if($key == 'status' && $value == 'Unlisted'){
                $email = 'Unlisted';
                $rooms->recommended='No';
            }

            if($key != 'status'){
                $field = str_replace('_', ' ', $key); 
                $email_controller->room_details_updated($request->id, ucwords($field));
            }
        }
        
        $rooms->save(); // Save rooms data to rooms table

        if($email == 'Listed')
            $email_controller->listed($request->id);

        if($email == 'Unlisted')
            $email_controller->unlisted($request->id);
        else
        $this->update_status($request->id); // This function for update steps count in rooms_steps_count table

        return json_encode(['success'=>'true', 'steps_count' => $rooms->steps_count, 'video' => $rooms->video]);
    }

    /**
     * Update List Your Space Steps Count, It will calling from ajax update functions
     *
     * @param int $id    Room Id
     * @return true
     */ 
    public function update_status($id)
    {
        $result_rooms = Rooms::whereId($id)->first();

        $rooms_status = RoomsStepsStatus::find($id);
         
        if(@$result_rooms->name != '' && @$result_rooms->summary != '' ) 
            $rooms_status->description = 1;
        else
            $rooms_status->description = 0;

        // if($result_rooms->bedrooms != '' && $result_rooms->beds != '' && $result_rooms->bathrooms != '' && $result_rooms->bed_type != '')
        if(($result_rooms->bedrooms != '' || $result_rooms->bedrooms == '0') && ($result_rooms->beds != '' || $result_rooms->beds == '0') && ($result_rooms->bathrooms != '' || $result_rooms->bathrooms == '0') && ($result_rooms->bed_type != '' || $result_rooms->bed_type == '0'))
            $rooms_status->basics = 1;
        else
            $rooms_status->basics = 0;

        $photos_count = RoomsPhotos::where('room_id', $id)->count();

        if($photos_count != 0)
            $rooms_status->photos = 1;
        else
            $rooms_status->photos = 0;

        $price = RoomsPrice::find($id);

        if($price != NULL)
        {
        if($price->night != 0)
            $rooms_status->pricing = 1;
        else
            $rooms_status->pricing = 0;
        }

        if($result_rooms->calendar_type != NULL)
            $rooms_status->calendar = 1;

        $rooms_status->save(); // Update Rooms Steps Count

        if($result_rooms->steps_count > 0 && $result_rooms->status == 'Listed' ){
            $result_rooms->status = 'Unlisted';
            $result_rooms->save();
        }if($result_rooms->steps_count == 0 && $result_rooms->status == 'Unlisted' ){
            $result_rooms->status = 'Listed';
            $result_rooms->save();
        }
        return true;
    }

    /**
     * Load List Your Space Address Popup
     *
     * @param array $request    Input values
     * @return enter_address view file
     */ 
    public function enter_address(Request $request)
    {
        $data_result['room_id']   = $request->id;
        $data_result['room_step'] = $request->page;
        $data_result['country']   = Country::all()->pluck('long_name','short_name');

        $data  = $request;
        
        $data  = json_decode($data['data']); // AngularJS data decoding
        $country = Country::where('short_name', $data->country)->first();
        $data->country_name = $country ? $country->long_name : "";

        $data_result['result'] = $data;
        
        // $data['result']    = RoomsAddress::where('room_id',$request->id)->first();

        return view('list_your_space.enter_address', $data_result);
    }

    /**
     * Load List Your Space Address Location Not Found Popup
     *
     * @param array $request    Input values
     * @return enter_address view file
     */ 
    public function location_not_found(Request $request)
    {
        $data  = $request;
        
        $data  = json_decode($data['data']); // AngularJS data decoding
        
        /*$rooms = RoomsAddress::find($request->id); // Where condition for Update
        
        foreach($data as $key=>$value)
        {
            $rooms->$key = $value;          // Dynamic Update
        }

        $rooms->save();*/

        // $data_result['result'] = RoomsAddress::find($request->id);
        $country = Country::where('short_name', $data->country)->first();

        if(!$country) {
            return json_encode(['status' => "country_error"]);
        }

        $data->country_name = $country->long_name;
        
        $data_result['result'] = $data;

        return view('list_your_space.location_not_found', $data_result);
    }

    /**
     * Load List Your Space Verify Location Popup
     *
     * @param array $request    Input values
     * @return verify_location view file
     */
    public function verify_location(Request $request)
    {
        $data  = $request;
        
        $data  = json_decode($data['data']); // AngularJS data decoding

        // $data['result'] = RoomsAddress::find($request->id);
        $country = Country::where('short_name', $data->country)->first();

        if(!$country) {
            return json_encode(['status' => "country_error"]);
        }

        $data->country_name = $country->long_name;
        $data_result['result'] = $data;

        return view('list_your_space.verify_location', $data_result);
    }

    /**
     * List Your Space Address Data
     *
     * @param array $request    Input values
     * @return json rooms_address result
     */
    public function finish_address(Request $request,EmailController $email_controller)
    {
        $data  = $request;
        
        $data  = json_decode($data['data']); // AngularJS data decoding


        $country = Country::where('short_name', $data->country)->first();

        if(!$country) {
            return json_encode(['status' => "country_error"]);
        }

        
        $rooms = RoomsAddress::find($request->id); // Where condition for Update
        
        foreach($data as $key=>$value)
        {
            $rooms->$key = $value;          // Dynamic Update
        }

        $rooms->save();

        $rooms_status = RoomsStepsStatus::find($request->id);

        $rooms_status->location = 1;

        $rooms_status->save();

        $data_result = RoomsAddress::find($request->id);

        $email_controller->room_details_updated($request->id, 'Address');

        return json_encode($data_result);
    }

    /**
     * Ajax Update List Your Space Amenities
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_amenities(Request $request,EmailController $email_controller)
    {        
        $rooms = Rooms::find($request->id);

        $rooms->amenities = rtrim($request->data,',');
        
        $rooms->save();

        $email_controller->room_details_updated($request->id, 'Amenities');

        return json_encode(['success'=>'true']);
    }

    /**
     * Ajax List Your Space Add Photos, it will upload multiple files
     *
     * @param array $request    Input values
     * @return json rooms_photos table result
     */
    public function add_photos(Request $request,EmailController $email_controller)
    {
        if(isset($_FILES["photos"]["name"]))
        {  $rows = array();
            $err = array();
            foreach($_FILES["photos"]["error"] as $key=>$error) 
            {
                $tmp_name = $_FILES["photos"]["tmp_name"][$key];

                $name = str_replace(' ', '_', $_FILES["photos"]["name"][$key]);
                
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                $name = time().$key.'_.'.$ext;

                $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/rooms/'.$request->id;
                                
                if(!file_exists($filename))
                {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/rooms/'.$request->id, 0777, true);
                }

                if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')   
                {            
                    // if($this->helper->compress_image($tmp_name, "images/rooms/".$request->id."/".$name, 80))
                    if(UPLOAD_DRIVER=='cloudinary')
                    {
                        $c=$this->helper->cloud_upload($tmp_name);
                        if($c['status']!="error")
                        {
                            $name=$c['message']['public_id'];    
                        }
                        else
                        {
                            $err = array('error_title' => ' Photo Error', 'error_description' => $c['message']);
                            $result = RoomsPhotos::where('room_id',$request->id)->get();
                            $rows['succresult'] = $result;
                            $rows['error'] = $err;
                            return json_encode($rows);
                            exit;
                        }
                    }
                    else
                    {
                        if($ext=='gif'){

                            move_uploaded_file($tmp_name, "images/rooms/".$request->id."/".$name);
                        }else{

                            if(move_uploaded_file($tmp_name, "images/rooms/".$request->id."/".$name))
                            {
                                // $this->helper->compress_image("images/rooms/".$request->id."/".$name, "images/rooms/".$request->id."/".$name, 80, 993, 662);
                                $this->helper->compress_image("images/rooms/".$request->id."/".$name, "images/rooms/".$request->id."/".$name, 80, 1440, 960);
                                $this->helper->compress_image("images/rooms/".$request->id."/".$name, "images/rooms/".$request->id."/".$name, 80, 1349, 402);
                                $this->helper->compress_image("images/rooms/".$request->id."/".$name, "images/rooms/".$request->id."/".$name, 80, 450, 250);
                            }
                        }


                    }

                    $photos          = new RoomsPhotos;
                    $photos->room_id = $request->id;
                    $photos->name    = $name;
                    $photos->save();

                    $this->update_status($request->id);
                }
                else
                {
                    $err = array('error_title' => ' Photo Error', 'error_description' => 'Some files are not valid');
                    
                }
            }

            $photos_featured = RoomsPhotos::where('room_id',$request->id)->where('featured','Yes');
            
            if($photos_featured->count() == 0)
            {
                $photos = RoomsPhotos::where('room_id',$request->id)->first();
                if($photos){
                    $photos->featured = 'Yes';
                    $photos->save();
                }
            }
            $email_controller->room_details_updated($request->id, 'Photos');

            $result = RoomsPhotos::where('room_id',$request->id)->get();
            $rows['succresult'] = $result;
            $rows['error'] = $err;
           
            return json_encode($rows);
            
        }
    }

    public function featured_image(Request $request) 
    {
        RoomsPhotos::whereRoomId($request->id)->update(['featured' => 'No']);

        RoomsPhotos::whereId($request->photo_id)->update(['featured' => 'Yes']);

        $photos = RoomsPhotos::where('room_id', $request->id)->get();
        
        return json_encode($photos);

        // return 'true';
    }
    /**
     * Ajax List Your Space Delete Photo
     *
     * @param array $request    Input values
     * @return json success, steps_count
     */
    public function delete_photo(Request $request,EmailController $email_controller)
    {
        $photos          = RoomsPhotos::find($request->photo_id);
        if($photos != NULL){
            $photos->delete();
            $success = 'true';
        }
        else {
            $success = 'false';
        }
        
        $photos_featured = RoomsPhotos::where('room_id',$request->id)->where('featured','Yes');
            
        if($photos_featured->count() == 0)
        {
            $photos_featured = RoomsPhotos::where('room_id',$request->id);
            
            if($photos_featured->count() != 0)
            {
                $photos = RoomsPhotos::where('room_id',$request->id)->first();
                $photos->featured = 'Yes';
                $photos->save();
            }
        }

        $rooms = Rooms::find($request->id);

        $featured_room = RoomsPhotos::where('room_id',$request->id)->where('featured','Yes')->first();

        $this->update_status($request->id);
        $email_controller->room_details_updated($request->id, 'Photos');
        
        return json_encode(['success'=>$success, 'steps_count' => $rooms->steps_count,'featured_room' => $featured_room->id]);
    }

    /**
     * Ajax List Your Space Photos List
     *
     * @param array $request    Input values
     * @return json rooms_photos table result
     */
    public function photos_list(Request $request)
    {
        $photos = RoomsPhotos::where('room_id', $request->id)->get();
        
        return json_encode($photos);
    }

    /**
     * Ajax List Your Space Photos Highlights
     *
     * @param array $request    Input values
     * @return json success
     */
    public function photo_highlights(Request $request)
    {
        $photos = RoomsPhotos::find($request->photo_id);

        $photos->highlights = $request->data;

        $photos->save();

        return json_encode(['success'=>'true']);
    }

    /**
     * Ajax Update price rules in manage listing
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_price_rules(Request $request,EmailController $email_controller)
    {        
        $rules    = [
            'period'    => 'required|integer|unique:rooms_price_rules,period,'.@$request->data['id'].',id,type,'.$request->type.',room_id,'.$request->id,
            'discount' => 'required|integer|between:1,99',
        ];
        if($request->type == 'early_bird') {
            $rules['period'] .= '|between:30,1080';
        }
        if($request->type == 'last_min') {
            $rules['period'] .= '|between:1,28';
        }

        $messages = [
            'period.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.period')]),
            'discount.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.discount')]),
        ];
        $attributes = [
            'period'    => trans('messages.lys.period'),
            'discount' => trans('messages.lys.discount'),
        ];

        $validator = \Validator::make($request->data, $rules, $messages, $attributes);
        
        if($validator->fails()) {
            $errors = @$validator->errors()->getMessages();
            return json_encode(['success' => 'false', 'errors' => $errors]);
        }
        
        $rule = $request->data;
        if(@$rule['id']) {
            $check = [
                'id' => $rule['id'],
                'room_id' => $request->id,
                'type'    => $request->type,
            ];
        }
        else {
            $check = [
                'room_id' => $request->id,
                'type'    => $request->type,
                'period'  => $rule['period']
            ];
        }
        $price_rule = RoomsPriceRules::firstOrNew($check);
        $price_rule->room_id = $request->id;
        $price_rule->type =  $request->type;
        $price_rule->period = $rule['period'];
        $price_rule->discount = $rule['discount'];

        $price_rule->save();

        return json_encode(['success'=>'true', 'id' => $price_rule->id]);
    }

    /**
     * Ajax Delete Price Rules
     *
     * @param array $request    Input values
     * @return json success
     */
    public function delete_price_rule(Request $request) {
        $id = $request->rule_id;
        RoomsPriceRules::where('id', $id)->delete();
        return json_encode(['success' => true]);
    }

    /**
     * Ajax Delete Availability Rules
     *
     * @param array $request    Input values
     * @return json success
     */
    public function delete_availability_rule(Request $request) {
        $id = $request->rule_id;
        RoomsAvailabilityRules::where('id', $id)->delete();
        return json_encode(['success' => true]);
    }

    /**
     * Ajax Update Reservation Settings values
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_reservation_settings(Request $request,EmailController $email_controller)
    {      
        $room = Rooms::find($request->id);
        $rooms_price = $room->rooms_price;

        $rules    = [
            'minimum_stay' => 'integer|min:1|maxmin:'.$request->maximum_stay,
            'maximum_stay'  => 'integer|min:1'
        ];

        $messages = [
            'minimum_stay.maxmin'   => trans('validation.max.numeric', ['attribute' => trans('messages.lys.minimum_stay'), 'max' => trans('messages.lys.maximum_stay')]),
            'minimum_stay.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.minimum_stay')]),
            'maximum_stay.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.maximum_stay')]),
        ];
        $attributes = [
            'minimum_stay'    => trans('messages.lys.minimum_stay'),
            'maximum_stay' => trans('messages.lys.maximum_stay'),
        ];

        $request_data = $request->all();
        $request_data['minimum_stay'] = is_numeric($request->minimum_stay) ? $request->minimum_stay -0 : $request->minimum_stay;
        $request_data['maximum_stay'] = is_numeric($request->maximum_stay) ? $request->maximum_stay -0 : $request->maximum_stay;
        $validator = \Validator::make($request_data, $rules, $messages, $attributes);

        if($validator->fails()) {
            $errors = @$validator->errors()->getMessages();
            return json_encode(['success' => 'false', 'errors' => $errors]);
        }
        
        $rooms_price->minimum_stay = $request->minimum_stay ?: null;
        $rooms_price->maximum_stay = $request->maximum_stay ?: null;

        $rooms_price->save();

        return json_encode(['success'=>'true']);
    }
    /**
     * Ajax Update Availability rule values
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_availability_rule(Request $request,EmailController $email_controller)
    {    
        $rules    = [
            'type'         => 'required',
            'start_date'   => 'required',
            'end_date'     => 'required',
            'minimum_stay' => 'integer|min:1|maxmin:'.@$request->availability_rule_item['maximum_stay'],
            'maximum_stay' => 'integer|min:1|required_if:minimum_stay,""'
        ];

        $messages = [
            'minimum_stay.maxmin'   => trans('validation.max.numeric', ['attribute' => trans('messages.lys.minimum_stay'), 'max' => trans('messages.lys.maximum_stay')]),
            'maximum_stay.required_if' => trans('messages.lys.minimum_or_maximum_stay_required'),
            'minimum_stay.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.minimum_stay')]),
            'maximum_stay.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.maximum_stay')]),
        ];
        $attributes = [
            'type'          => trans('messages.lys.select_dates'),
            'start_date'    => trans('messages.lys.start_date'),
            'end_date'      => trans('messages.lys.end_date'),
            'minimum_stay'  => trans('messages.lys.minimum_stay'),
            'maximum_stay'  => trans('messages.lys.maximum_stay'),
        ];

        $request_data = $request->availability_rule_item;

        $request_data['minimum_stay'] = is_numeric(@$request_data['minimum_stay']) ? @$request_data['minimum_stay'] -0 : @$request_data['minimum_stay'];
        $request_data['maximum_stay'] = is_numeric(@$request_data['maximum_stay']) ? @$request_data['maximum_stay'] -0 : @$request_data['maximum_stay'];

        $validator = \Validator::make($request_data, $rules, $messages, $attributes);

        if($validator->fails()) {
            $errors = @$validator->errors()->getMessages();
            return json_encode(['success' => 'false', 'errors' => $errors]);
        }

        $rule = $request->availability_rule_item;
        $rooms = Rooms::where('id', $request->id)->first();
        
        $check = [
            'id' => @$rule['id'] ?: '',
        ];
        $availability_rule = RoomsAvailabilityRules::firstOrNew($check);
        $availability_rule->room_id = $rooms->id;
        $availability_rule->start_date = date('Y-m-d', $this->helper->custom_strtotime(@$rule['start_date'], PHP_DATE_FORMAT));
        $availability_rule->end_date = date('Y-m-d', $this->helper->custom_strtotime(@$rule['end_date'], PHP_DATE_FORMAT));
        $availability_rule->minimum_stay = @$rule['minimum_stay'] ?: null;
        $availability_rule->maximum_stay = @$rule['maximum_stay'] ?: null;
        $availability_rule->type = @$rule['type'] != 'prev' ? @$rule['type']: @$availability_rule->type;
        $availability_rule->save();

        
        return json_encode(['success'=>'true', 'availability_rules' => $rooms->availability_rules]);
    }

    /**
     * Load Rooms Detail View
     *
     * @param array $request    Input values
     * @return view rooms_detail
     */



    public function rooms_detail(Request $request)
    {   
        $data['room_id']          = $request->id;
        
        $data['result']           = Rooms::find($request->id);

        $data['is_wishlist']      = SavedWishlists::where('user_id',@Auth::user()->id)->where('room_id',$request->id)->where('list_type','Rooms')->count();
        if(count($data['result']) == 0)
            abort('404');
        $data['user_details']   =   User::find($data['result']->user_id);
        $userID = Auth::id();
        if(!is_null($userID)){
            $userDetails   =   User::find($userID);           
            $data['username'] = $userDetails->first_name." ".$userDetails->last_name;
            $data['reason'] = $userDetails->id_resubmit_reason;
            $data['verification_status'] = $userDetails->verification_status;
        }else{
            $data['verification_status'] = 'login_null';
            $data['reason'] = '';
            $data['username']='';
            $url = url('rooms/'.$request->id);
            Session::put('ajax_redirect_url',$url);
        }

        if(count($data['result']) == 0)
            abort('404');
        if($data['user_details']->status != 'Active' && $data['result']->user_id != @Auth::user()->id)
           abort('404');
        else if($data['result']->status != 'Listed' && $data['result']->user_id != @Auth::user()->id)
            abort('404');

        if($data['result']->user_id != @Auth::user()->id && $data['result']->status == 'Listed' )
        {
            $data['result']->views_count += 1;            
            $data['result']->save();
        }
        
        $data['amenities']        = Amenities::selected($request->id);    

        $site_settings_url = @SiteSettings::where('name', 'site_url')->first()->value;
        $url = \App::runningInConsole() ? $site_settings_url : url('/');
        foreach ($data['amenities'] as $amenities) {
            @$photo_src = explode('.', $amenities->icon);
            if (count($photo_src) > 1) {
                $amenities->image_name= $url . '/images/amenities/' . $amenities->icon;
            } else {
                $options['secure'] = TRUE;
                $options['crop'] = 'fill';
                $amenities->image_name= \Cloudder::show($amenities->icon, $options);
            }
        }
        
        $data['safety_amenities'] = Amenities::selected_security($request->id); 
        foreach ($data['safety_amenities'] as $amenities) {
            @$photo_src = explode('.', $amenities->icon);
            if (count($photo_src) > 1) {
                $amenities->image_name= $url . '/images/amenities/' . $amenities->icon;
            } else {
                $options['secure'] = TRUE;
                $options['crop'] = 'fill';
                $amenities->image_name= \Cloudder::show($amenities->icon, $options);
            }
        }
        
        $data['rooms_photos']     = $data['result']->rooms_photos;
        $data['room_types']       = $data['result']->room_type_name;
        $data['cancellation']     = $data['result']->cancel_policy;
        
        $rooms_address            = $data['result']->rooms_address;
        
        $latitude                 = $rooms_address->latitude;
        
        $longitude                = $rooms_address->longitude;

        if($request->checkin != '' && $request->checkout != '')
        {
            $data['checkin']         = date('m/d/Y', $this->helper->custom_strtotime($request->checkin));
            $data['checkout']        = date('m/d/Y', $this->helper->custom_strtotime($request->checkout));
            $data['formatted_checkin'] = date('d-m-Y',$this->helper->custom_strtotime($request->checkin));
            $data['formatted_checkout'] = date('d-m-Y',$this->helper->custom_strtotime($request->checkout));
            if(@$data['result']['accommodates'] >= @$request->guests){
                $data['guests']          = $request->guests;}else{
                $data['guests']          = '1';
            }
        }
        else
        {
            $data['checkin'] = '';
            $data['checkout'] = '';
            $data['guests'] = @$request->guests ; 
            $data['formatted_checkin'] = '';
            $data['formatted_checkout'] = '';   
        }
        $data['similar']          = Rooms::join('rooms_address', function($join) {
                                        $join->on('rooms.id', '=', 'rooms_address.room_id');
                                    })
                                    ->select(DB::raw('*, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) as distance'))
                                    ->having('distance', '<=', 30)
                                    ->where('rooms.id', '!=', $request->id)
                                    ->where('rooms.status', 'Listed')
                                    ->whereHas('users', function($query)  {
                                $query->where('users.status','Active');
                                        })
                                    ->get();
        $data['title']  =   $data['result']->name.' in '.$data['result']->rooms_address->city;

        //dd($data);
        return view('rooms.rooms_detail', $data);
    }
 public function remove_video(Request $request){
        $rooms = Rooms::find($request->id); 
        
        $rooms->video = ''; 
        $rooms->save(); 
        return json_encode(['success' => 'true', 'video' => '']);
    }
    /**
     * Load Rooms Detail Slider View
     *
     * @param array $request    Input values
     * @return view rooms_slider
     */
     public function rooms_slider(Request $request)
    {        
        $data['room_id']      = $request->id;
        
        $data['result']       = Rooms::find($request->id);
        if($request->order != 'id')
            $data['rooms_photos'] = RoomsPhotos::where('room_id', $request->id)->orderBy('featured')->get();
        else 
           $data['rooms_photos'] = RoomsPhotos::where('room_id', $request->id)->get();
            
        $data['version'] = @SiteSettings::where('name', 'version')->first()->value;

        return view('rooms.rooms_slider', $data);
    }

    public function currency_check(Request $request)
    {
        $id             = $request->id;
        $new_price      = $request->n_price;
        $price          = RoomsPrice::find($id);
        $minimum_amount = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $price->currency_code, MINIMUM_AMOUNT); 
        $currency_symbol = Currency::whereCode($price->currency_code)->first()->original_symbol;
        if($minimum_amount > $new_price)
        {
            echo "fail";
        }
        else
        {
            echo "success";
        }
    }

    /**
     * Ajax Update ListFV Your Space Price
     *
     * @param array $request    Input values
     * @return json success, currency_symbol, steps_count
     */
    public function update_price(Request $request,EmailController $email_controller)
    {
        $data           = $request;
        
        $data           = json_decode($data['data']); // AngularJS data decoding

        $minimum_amount = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $data->currency_code, MINIMUM_AMOUNT); 

        $currency_symbol = Currency::whereCode($data->currency_code)->first()->original_symbol;
         
        if(isset($data->night))
        {
            $old_currency_format = RoomsPrice::find($request->id);
            $night_price = $data->night;
            if(is_numeric($night_price) && $night_price < $minimum_amount)
            {
                return json_encode(['success'=>'false','msg' => trans('validation.min.numeric', ['attribute' => trans('messages.inbox.price'), 'min' => $currency_symbol.$minimum_amount]), 'attribute' => 'price', 'currency_symbol' => $currency_symbol,'min_amt' => $minimum_amount]);
            }
            $data->night=$night_price;
        } else {
            $night_price = '';
        }

        if(isset($data->week) && @$data->week !='0' && @$data->week !=''){
            $week_price = $data->week; 
            if($week_price < $minimum_amount){
                return json_encode(['success'=>'false','msg' => trans('validation.min.numeric', ['attribute' => 'price', 'min' => $currency_symbol.$minimum_amount]), 'attribute' => 'week', 'currency_symbol' => $currency_symbol]);
            }
        }

        if(isset($data->month) && @$data->month !='0' && @$data->month !=''){
            $month_price = $data->month; 
            if($month_price < $minimum_amount){
                return json_encode(['success'=>'false','msg' => trans('validation.min.numeric', ['attribute' => 'price', 'min' => $currency_symbol.$minimum_amount]), 'attribute' => 'month', 'currency_symbol' => $currency_symbol]);
            }
        }
        
        $price          = RoomsPrice::find($request->id);
        
        $price->room_id = $request->id;
        if($price->currency_code != $data->currency_code)
            $this->update_calendar_currency($request->id,$price->currency_code,$data->currency_code);
        foreach ($data as $key => $value) 
        {
            $price->$key = $value;
        }

        $price->save();

        $this->update_status($request->id);

        $email_controller->room_details_updated($request->id, 'Pricing');

        return json_encode(['success'=>'true', 'currency_symbol' => $price->currency->original_symbol, 'steps_count' => $price->steps_count,'night_price'=>$night_price]);
    }
 
    /**
     * Ajax List Your Space Steps Status
     *
     * @param array $request    Input values
     * @return json rooms_steps_status result
     */
    public function rooms_steps_status(Request $request)
    {
        return RoomsStepsStatus::find($request->id);
    }

    /**
     * Ajax Rooms Related Table Data
     *
     * @param array $request    Input values
     * @return json rooms, rooms_address, rooms_price, currency table results
     */
    public function rooms_data(Request $request)
    {
        $data           = Rooms::find($request->id);
        
        $rooms_address  = array_merge($data->toArray(),$data->rooms_address->toArray());
        
        $rooms_price    = array_merge($rooms_address,$data->rooms_price->toArray());
        
        $rooms_currency = array_merge($rooms_price,['symbol' => $data->rooms_price->currency->symbol ]);

        return json_encode($rooms_currency);
    }

    /**
     * Ajax Rooms Detail Calendar Dates Blocking
     *
     * @param array $request    Input values
     * @return json calendar results
     */
    public function rooms_calendar(Request $request)
    {   
        // For coupon code destroy
        
        Session::forget('ngo_status');
        Session::forget('ngo_amount');
        Session::forget('ngo_list');


        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('remove_coupon');
        Session::forget('manual_coupon');
            
        $id     = $request->data;
        

        $result['not_avilable'] = Calendar::where('room_id', $id)->notAvailable()->get()->pluck('date');

        $result['changed_price'] = Calendar::where('room_id', $id)->get()->pluck('session_currency_price','date');

        $result['price'] = RoomsPrice::where('room_id', $id)->get()->pluck('night');
        //get weekend price
        $result['weekend'] = RoomsPrice::where('room_id', $id)->get()->pluck('weekend');

        $result['currency_symbol'] = Currency::first()->symbol;

        $result['room_accomodates'] = Rooms::find($id)->accommodates;

        return json_encode($result);
    }

        public function rooms_calendar_alter(Request $request)
    {
        $id     = $request->data;
        
        $checkin                      = date('Y-m-d', strtotime($request->checkin));
        $date2                    = date('Y-m-d', strtotime($request->checkout));

        $checkout                      = date('Y-m-d',(strtotime ( '-1 day' , strtotime ($date2 ) ) ));

        $booked_days = $this->get_days($checkin, $checkout);
       
        $result['not_avilable'] = Calendar::where('room_id', $id)->where('status','Not available')->whereNotIn('date',$booked_days)->get()->pluck('date');

        $result['changed_price'] = Calendar::where('room_id', $id)->get()->pluck('price','date');

        $result['price'] = RoomsPrice::where('room_id', $id)->get()->pluck('night');

        $result['currency_symbol'] = Currency::first()->symbol;

        return json_encode($result);
    }
    /**
     * Ajax Rooms Detail Price Calculation while choosing date
     *
     * @param array $request    Input values
     * @return json price list
     */
    public function price_calculation(Request $request)
    {
       // For coupon code destroy
        Session::forget('ngo_status');
        Session::forget('ngo_amount');
        Session::forget('ngo_list');

        Session::forget('coupon_code');
        Session::forget('coupon_amount');
        Session::forget('remove_coupon');
        Session::forget('manual_coupon');

        return $this->payment_helper->price_calculation($request->room_id, $request->checkin, $request->checkout, $request->guest_count ,'',$request->change_reservation);
    }

// Ajax Check Date availability 



    public function check_availability(Request $request)
    {
        $room_id = $request->room_id;
        $checkin = $request->checkin;
        $checkout= $request->checkout;
        $date_from = strtotime($checkin);
        $date_to = strtotime($checkout); 
        $date_ar=array();
        for ($i=$date_from; $i<=$date_to - 1; $i+=86400) {  
            $date_ar[]= date("Y-m-d", $i);  
        }  
        $check=array();
        for ($i=0; $i < count($date_ar) ; $i++) { 
            $check[]=DB::table('calendar')->where([ 'room_id' => $room_id, 'date' => $date_ar[$i], 'status' => 'Not available' ])->first();
        }

        return $check;
        exit;
    }

    public function checkin_date_check(Request $request)
    {
        $room_id = $request->room_id;
        $date = $request->date;
        $date = (strtotime($date)) - (24*3600*1);
        $date = date('Y-m-d',$date);
        $result = DB::table('calendar')->where([ 'room_id' => $room_id, 'date' => $date, 'status' => 'Not available' ])->get();
        $checkout = (strtotime($request->date)) + (24*3600*1);
        $checkout =  date('d-m-Y',$checkout);
        $check = array(
            'checkin'=>$request->date,
            'checkout'=>$checkout
            );
        return $check;
    }



    public function current_date_check(Request $request)
    {
        $room_id = $request->room_id;
        $checkin = $request->checkin;
        $check_in=date('Y-m-d', strtotime($checkin));
        // return $check_in; exit;
        $result = DB::table('calendar')->where([ 'room_id' => $room_id, 'date' => $check_in, 'status' => 'Not available' ])->get();
        $check=array();
        if(count($result) >= 1 )
        {
            $chck_date=strtotime($checkin);
            $end_date = $chck_date + (24*3600*50);
            for ($i=$chck_date + (24*3600*1) ; $i < $end_date; $i+=86400) 
            { 

                $check[] = DB::table('calendar')->where([ 'room_id' => $room_id, 'date' => date("Y-m-d", $i), 'status' => 'Not available' ])->first(); 
                if($check)
                {
                    $available_date= date('Y-m-d', $i);
                    return $available_date; exit;
                }
            }
        }
        else
        {

            return $result[0]->date;
            exit;
        }
    }


    /**
     * Get days between two dates
     *
     * @param date $sStartDate  Start Date
     * @param date $sEndDate    End Date
     * @return array $days      Between two dates
     */
    public function get_days($sStartDate, $sEndDate)
    {           
       $aDays[]      = $sStartDate;  
       
       $sCurrentDate = $sStartDate;  
       
      while($sCurrentDate < $sEndDate)
      {
        $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  
        
        $aDays[]      = $sCurrentDate;  
      }
      
      return $aDays;  
    }  

    /**
     * Ajax Update List Your Space Description
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_description(Request $request,EmailController $email_controller)
    {
        $data           = @$request;        
        $data           = json_decode($data['data']); // AngularJS data decoding
        $request->current_tab = @$request->current_tab  ?: 'en';
        if(@$request->current_tab != 'en')
        {        
        $roomlang          = RoomsDescriptionLang::where('room_id',@$request->id)->where('lang_code',@$request->current_tab)->first();
        if($roomlang !='')
        {
            $roomlang->room_id = @$request->id;
            $roomlang->lang_code = @$request->current_tab;
            foreach ($data as $key => $value) 
            {
                $roomlang->$key =  $value;
            }
            $roomlang->save();
         }else
         {  $roomlang = new RoomsDescriptionLang;
            $roomlang->room_id = @$request->id;
            $roomlang->lang_code = @$request->current_tab;
            foreach ($data as $key => $value) 
            {
                $roomlang->$key =  $value;
            }
            $roomlang->save();
         }
        }else{
        $price          = @RoomsDescription::find(@$request->id);
        if($price != '')
        {
        $price->room_id = @$request->id;

        foreach ($data as $key => $value) 
        {
            $price->$key =  $value;
        }
        $price->save();
        }else
        {   $price          = new RoomsDescription;
            $price->room_id = @$request->id;
            foreach ($data as $key => $value) 
            {
                $price->$key =  $value;
            }

            $price->save();
        }
        }
        foreach ($data as $key => $value) 
        {
            if($key == 'space'){
                $field = 'The Space';
            }elseif ($key == 'access') {
                $field = 'Guest Access';
            }elseif ($key == 'interaction') {
                $field = 'Interaction with Guests';
            }elseif ($key == 'notes') {
                $field = 'Other Things to Note';
            }elseif ($key == 'house_rules') {
                $field = 'House Rules';
            }elseif ($key == 'neighborhood_overview') {
                $field = 'Overview';
            }elseif ($key == 'transit') {
                $field = 'Getting Around';
            }else{
                $field = ''; 
            }

            if($field != ''){
                $email_controller->room_details_updated($request->id, $field);
            }
        }

        return json_encode(['success'=>'true']);
    }

    /**
     * Ajax Update List Your Space Calendar Dates Price, Status
     *
     * @param array $request    Input values
     * @return empty
     */
    public function calendar_edit(Request $request,EmailController $email_controller)
    {
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $start_date = strtotime($start_date);
        
        $end_date   = date('Y-m-d', strtotime($request->end_date));
        $end_date   = strtotime($end_date);
        $room_price = RoomsPrice::where('room_id',$request->id)->first();

        for ($i=$start_date; $i<=$end_date; $i+=86400) 
        {
            $date = date("Y-m-d", $i);
            $roomprice = $room_price->price($date);
            $is_reservation = Reservation::whereRoomId($request->id)->whereRaw('status!="Declined"')->whereRaw('status!="Expired"')->whereRaw('status!="Cancelled"')->whereRaw('(checkin = "'.$date.'" or (checkin < "'.$date.'" and checkout > "'.$date.'")) ')->get()->count(); 
            if($is_reservation == 0)
            {  
                $data = [ 'room_id' => $request->id,
                  'price'   => ($request->price) ? $request->price : '0',
                  'status'  => "$request->status",
                  'notes'   => $request->notes,
                  'source'  => 'Calendar'
                ];
                Calendar::updateOrCreate(['room_id' => $request->id, 'date' => $date], $data);
            }
        }
        
         $email_controller->room_details_updated($request->id, 'Calendar');
    }

    /**
     * Contact Request send to Host
     *
     * @param array $request Input values
     * @return redirect to Rooms Detail page
     */
    public function contact_request(Request $request, EmailController $email_controller)
    {
        $data['price_list']       = json_decode($this->payment_helper->price_calculation($request->id, $request->message_checkin, $request->message_checkout, $request->message_guests));

        if(@$data['price_list']->status == 'Not available')
        {
            $this->helper->flash_message('error', @$data['price_list']->error ?: trans('messages.rooms.dates_not_available')); // Call flash message function
            return redirect('rooms/'.$request->id);
        }

        $rooms = Rooms::find($request->id);

        $reservation = new Reservation;

        $reservation->room_id          = $request->id;
        $reservation->host_id          = $rooms->user_id;
        $reservation->user_id          = Auth::user()->id;
        $reservation->checkin          = date('Y-m-d', strtotime($request->message_checkin));
        $reservation->checkout         = date('Y-m-d', strtotime($request->message_checkout));
        $reservation->number_of_guests = $request->message_guests;
        $reservation->nights           = $data['price_list']->total_nights;
        $reservation->per_night        = $data['price_list']->per_night;
        $reservation->subtotal         = $data['price_list']->subtotal;
        $reservation->cleaning         = $data['price_list']->cleaning_fee;
        $reservation->additional_guest = $data['price_list']->additional_guest;
        $reservation->security         = $data['price_list']->security_fee;
        $reservation->service          = $data['price_list']->service_fee;
        $reservation->airportfee       = $data['price_list']->airport_fee;
        $reservation->host_fee         = $data['price_list']->host_fee;
        $reservation->total            = $data['price_list']->total;
        $reservation->currency_code    = $data['price_list']->currency;
        $reservation->type             = 'contact';
        $reservation->country          = 'US';

        $reservation->base_per_night                = $data['price_list']->base_rooms_price;
        $reservation->length_of_stay_type           = $data['price_list']->length_of_stay_type;
        $reservation->length_of_stay_discount       = $data['price_list']->length_of_stay_discount;
        $reservation->length_of_stay_discount_price = $data['price_list']->length_of_stay_discount_price;
        $reservation->booked_period_type            = $data['price_list']->booked_period_type;
        $reservation->booked_period_discount        = $data['price_list']->booked_period_discount;
        $reservation->booked_period_discount_price  = $data['price_list']->booked_period_discount_price;
        
        $reservation->save();
        $replacement = "[removed]";

        $email_pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
        $url_pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
        $phone_pattern = "/\+?[0-9][0-9()\s+]{4,20}[0-9]/";
        $dots=".*\..*\..*";

        $find = array($email_pattern, $phone_pattern);
        $replace = array($replacement, $replacement);

        $question = preg_replace($find, $replace, $request->question);
        // dd($dots);

        if($question==$dots)
        {

        $question = preg_replace($url_pattern, $replacement, $question);
        }
      else{

         $question = preg_replace($find, $replace, $request->question);
      }

        $message = new Messages;

        $message->room_id        = $request->id;
        $message->reservation_id = $reservation->id;
        $message->user_to        = $rooms->user_id;
        $message->user_from      = Auth::user()->id;
        $message->message        = $question;
        $message->message_type   = 9;
        $message->read           = 0;

        $message->save();

        $email_controller->inquiry($reservation->id, $question);

        $this->helper->flash_message('success', trans('messages.rooms.contact_request_has_sent',['first_name'=>$rooms->users->first_name])); // Call flash message function
        return redirect('rooms/'.$request->id);
    }
    public function get_lang_details(Request $request)
    {   
        
         $data = RoomsDescriptionLang::with(['language'])->where('room_id', $request->id)->get();

          return json_encode($data);
    }
      public function get_lang(Request $request)
    {   
        
         $data = Language::translatable()->where('name', '!=', 'English')->get();

          return json_encode($data);
    }
     public function add_description(Request $request)
    {   

        $language = new RoomsDescriptionLang;

        $language->room_id        = $request->id;
        $language->lang_code      = $request->lan_code;
        $language->name           = '';
        $language->summary        = '';

        $language->save();


        $result = RoomsDescriptionLang::with(['language'])->where('room_id', $request->id)->where('lang_code', $request->lan_code)->get();
            
        return json_encode($result);
               
    } 
    public function delete_language(Request $request)
    {   

        
        RoomsDescriptionLang::where('room_id', $request->id)->where('lang_code', $request->current_tab)->delete();

        return json_encode(['success'=>'true']);
               
    }
    public function lan_description(Request $request)
    {
        $result = RoomsDescriptionLang::with(['language'])->where('room_id', $request->id)->get();
        
        if($result->count())
        {
            foreach($result as $row)
            {
                $row->lan_id = count($result);
            }
            return json_encode($result);
        }
        else
        {
            return '[{"name":"", "summary":"","space":"","access":"","interaction":"","notes":"","house_rules":"",
             "neighborhood_overview":"","transit":"","lang_code":""}]';
        }
    }

     public function get_description(Request $request)
    {   
        
        if($request->lan_code =="en")
        {
            $result = Rooms::with(['rooms_description'])->where('id', $request->id)->get();
            
        }
        else
        {
            $result = RoomsDescriptionLang::with(['language'])->where('room_id', $request->id)->where('lang_code', $request->lan_code)->get();
        }
        

        if($result->count())
        {         
            return json_encode($result);
        }
        else
        {
            return '[{"name":"", "summary":"","space":"","access":"","interaction":"","notes":"","house_rules":"",
             "neighborhood_overview":"","transit":"","lang_code":""}]';
        }
    } 

    public function get_all_language(Request $request)
    {
        
        $result = DB::select( DB::raw("select * from language where language.value not in (SELECT language.value FROM `language` JOIN rooms_description_lang on (rooms_description_lang.lang_code = language.value AND rooms_description_lang.room_id = '$request->id')) AND  language.status = 'Active' AND language.name != 'English'  ") );
        
        return json_encode($result);
               
    } 

    /**
     * Update Calendar Special Price After update Room Currency
     *
     * @param int $id    Room Id
     * @param string $from    From Currency
     * @param string $to    To Currency
     * @return true
     */ 
    public function update_calendar_currency($room_id,$from,$to)
    {
        $calendar_details = Calendar::where('room_id',$room_id)->where('date','>=',date('Y-m-d'))->get();
        foreach ($calendar_details as $calendar) {
            $new_amount = $this->payment_helper->currency_convert($from, $to, $calendar->price);
            $calendar->price = $new_amount;
            $calendar->save();
        }
        
    }
}
