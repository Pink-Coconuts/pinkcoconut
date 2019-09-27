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

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\RoomsDataTable;
use App\Models\BedType;
use App\Models\PropertyType;
use App\Models\RoomType;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Amenities;
use App\Models\RoomsPhotos;
use App\Models\Rooms;
use App\Models\User;
use App\Models\RoomsAddress;
use App\Models\RoomsDescription;
use App\Models\RoomsDescriptionLang;
use App\Models\RoomsPrice;
use App\Models\RoomsStepsStatus;
use App\Models\Reservation;
use App\Models\SavedWishlists;
use App\Models\SpecialOffer;
use App\Models\Reviews;
use App\Models\Payouts;
use App\Models\HostPenalty;
use App\Models\ImportedIcal;
use App\Models\Calendar;
use App\Models\PayoutPreferences;
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use App\Http\Controllers\CalendarController;
use Validator;
use App\Models\RoomsPriceRules;
use App\Models\RoomsAvailabilityRules;

class RoomsController extends Controller
{


    protected $payment_helper; // Global variable for Helpers instance   

    protected $helper;  // Global variable for instance of Helpers

    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;        
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Rooms
     *
     * @param array $dataTable  Instance of RoomsDataTable
     * @return datatable
     */
    public function index(RoomsDataTable $dataTable)
    {
        return $dataTable->render('admin.rooms.view');
    }

    /**
     * Add a New Room
     *
     * @param array $request  Input values
     * @return redirect     to Rooms view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            $bedrooms = [];
            $bedrooms[0] = 'Studio';
            for($i=1; $i<=10; $i++)
                $bedrooms[$i] = $i;

            $beds = [];
            for($i=1; $i<=16; $i++)
                $beds[$i] = ($i == 16) ? $i.'+' : $i;

            $bathrooms = [];
            $bathrooms[0] = 0;
            for($i=0.5; $i<=8; $i+=0.5)
                $bathrooms["$i"] = ($i == 8) ? $i.'+' : $i;

            $accommodates = [];
            for($i=1; $i<=16; $i++)
                $accommodates[$i] = ($i == 16) ? $i.'+' : $i;

            $data['bedrooms']      = $bedrooms;
            $data['beds']          = $beds;
            $data['bed_type']      = BedType::where('status','Active')->pluck('name','id');
            $data['bathrooms']     = $bathrooms;
            $data['property_type'] = PropertyType::where('status','Active')->pluck('name','id');
            $data['room_type']     = RoomType::where('status','Active')->pluck('name','id');
            $data['accommodates']  = $accommodates;
            $data['country']       = Country::pluck('long_name','short_name');
            $data['amenities']     = Amenities::active_all();
            $data['users_list']    = User::whereStatus('Active')->pluck('first_name','id');

            $data['length_of_stay_options'] = Rooms::getLenghtOfStayOptions();
            $data['availability_rules_months_options'] = Rooms::getAvailabilityRulesMonthsOptions();
            
            return view('admin.rooms.add', $data);
        }
        else if($_POST)
        {   //check name exists or not            
             $room_type_name = Rooms::where('name','=',@$request->name[0])->get();    
             if(@$room_type_name->count() != 0){             
                     $this->helper->flash_message('error', 'This Name already exists'); // Call flash message function
                     return redirect(ADMIN_URL.'/rooms');
                }  
            
            $photos_uploaded = array();
            if(UPLOAD_DRIVER=='cloudinary')
            {
                if(isset($_FILES["photos"]["name"]))
                {
                    foreach($_FILES["photos"]["error"] as $key=>$error) 
                    {
                        $tmp_name = $_FILES["photos"]["tmp_name"][$key];

                        $name = str_replace(' ', '_', $_FILES["photos"]["name"][$key]);
                        
                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                        $name = time().$key.'_.'.$ext;

                        if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')   
                        {    

                            $c=$this->helper->cloud_upload($tmp_name);
                            if($c['status']!="error")
                            {
                                $name=$c['message']['public_id'];    
                            }
                            else
                            {
                                $this->helper->flash_message('danger', $c['message']); // Call flash message function
                                return redirect(ADMIN_URL.'/rooms');
                            }
                            $photos_uploaded[] = $name;
                        }
                    }
                }
            }

            $rooms = new Rooms;

            $rooms->user_id       = $request->user_id;
            $rooms->calendar_type = 'Always'; //$request->calendar;
            $rooms->bedrooms      = $request->bedrooms;
            $rooms->beds          = $request->beds;
            $rooms->bed_type      = $request->bed_type;
            $rooms->bathrooms     = $request->bathrooms;
            $rooms->property_type = $request->property_type;
            $rooms->room_type     = $request->room_type;
            $rooms->accommodates  = $request->accommodates;
            $rooms->name          = $request->name[0];

            $search     = '#(.*?)(?:href="https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12}).*#x';
                $count      = preg_match($search, $request->video);
                if($count == 1) {
                    $replace    = 'https://www.youtube.com/embed/$2';
                    $video      = preg_replace($search,$replace,$request->video);
                    $rooms->video = $video;
                }
                else {
                    $rooms->video = $request->video;
                }

            $rooms->sub_name      = RoomType::find($request->room_type)->name.' in '.$request->city;
            $rooms->summary       = $request->summary[0];
            $rooms->amenities     = @implode(',', @$request->amenities);
            $rooms->booking_type  = $request->booking_type;
            $rooms->started       = 'Yes';
            $rooms->status        = 'Listed';
            $rooms->cancel_policy = $request->cancel_policy;

            $rooms->save();

            $rooms_address = new RoomsAddress;

            $latt=$request->latitude;
            $longg=$request->longitude;
            if($latt==''||$longg==''){
                $address        =$request->address_line_1.' '.$request->address_line_2.' '.$request->city.' '.$request->state.' '.$request->country;
                $latlong=$this->latlong($address);

                $latt       = $latlong['lat'];
                $longg      = $latlong['long'];
            }

            $rooms_address->room_id        = $rooms->id;
            $rooms_address->address_line_1 = $request->address_line_1;
            $rooms_address->address_line_2 = $request->address_line_2;
            $rooms_address->city           = $request->city;
            $rooms_address->state          = $request->state;
            $rooms_address->country        = $request->country;
            $rooms_address->postal_code    = $request->postal_code;
            $rooms_address->latitude       = $latt;
            $rooms_address->longitude      = $longg;

            $rooms_address->save();

            $rooms_description = new RoomsDescription;
            $rooms_description->room_id               = $rooms->id;
            $rooms_description->space                 = $request->space[0];
            $rooms_description->access                = $request->access[0];
            $rooms_description->interaction           = $request->interaction[0];
            $rooms_description->notes                 = $request->notes[0];
            $rooms_description->house_rules           = $request->house_rules[0];
            $rooms_description->neighborhood_overview = $request->neighborhood_overview[0];
            $rooms_description->transit               = $request->transit[0];
            $rooms_description->save();

            $count=count($request->name);

              for($i=1;$i<$count;$i++)
              {
                $lan_description  = new RoomsDescriptionLang;

                $lan_description->room_id         = $rooms->id;
                $lan_description->lang_code        = $request->language[$i-1];
                $lan_description->name            = $request->name[$i];
                $lan_description->summary         = $request->summary[$i];
                $lan_description->space           = $request->space[$i];
                $lan_description->access          = $request->access[$i];
                $lan_description->interaction     = $request->interaction[$i];
                $lan_description->notes           = $request->notes[$i];
                $lan_description->house_rules     = $request->house_rules[$i];
                $lan_description->neighborhood_overview=$request->neighborhood_overview[$i];
                $lan_description->transit         = $request->transit[$i];
                $lan_description->save();

            }






            $rooms_price = new RoomsPrice;

            $rooms_price->room_id          = $rooms->id;
            $rooms_price->night            = $request->night;
            // $rooms_price->week             = $request->week;
            // $rooms_price->month            = $request->month;
            $rooms_price->cleaning         = $request->cleaning;
            $rooms_price->additional_guest = $request->additional_guest;
            $rooms_price->guests           = ($request->additional_guest) ? $request->guests : '0';
            $rooms_price->security         = $request->security;
            $rooms_price->weekend          = $request->weekend;
            $rooms_price->currency_code    = $request->currency_code;

            $rooms_price->save();

            // Image upload
            if(isset($_FILES["photos"]["name"]))
            {
            foreach($_FILES["photos"]["error"] as $key=>$error) 
            {
                $tmp_name = $_FILES["photos"]["tmp_name"][$key];

                $name = str_replace(' ', '_', $_FILES["photos"]["name"][$key]);
                
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                $name = time().$key.'_.'.$ext;

                $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/rooms/'.$rooms->id;
                                
                if(!file_exists($filename))
                {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/rooms/'.$rooms->id, 0777, true);
                }
                                           
                if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')   
                {    

                    if(UPLOAD_DRIVER=='cloudinary')
                    {
                        $name = @$photos_uploaded[$key];
                    }
                    else
                    {

                        if($ext == 'gif')
                        {

                            move_uploaded_file($tmp_name, "images/rooms/".$rooms->id."/".$name);
                        }
                        else
                        {

                            if(move_uploaded_file($tmp_name, "images/rooms/".$rooms->id."/".$name))
                            {
                                $this->helper->compress_image("images/rooms/".$rooms->id."/".$name, "images/rooms/".$rooms->id."/".$name, 80, 1440, 960);
                                $this->helper->compress_image("images/rooms/".$rooms->id."/".$name, "images/rooms/".$rooms->id."/".$name, 80, 1349, 402);
                                $this->helper->compress_image("images/rooms/".$rooms->id."/".$name, "images/rooms/".$rooms->id."/".$name, 80, 450, 250);
                            }
                        }
                    }
                    $photos          = new RoomsPhotos;
                    $photos->room_id = $rooms->id;
                    $photos->name    = $name;
                    $photos->save();      
                }
            }
            $photosfeatured = RoomsPhotos::where('room_id',$rooms->id);
            if($photosfeatured->count() != 0){
            $photos_featured = RoomsPhotos::where('room_id',$rooms->id)->where('featured','Yes');
            if($photos_featured->count() == 0)
            {
                $photos = RoomsPhotos::where('room_id',$rooms->id)->first();
                $photos->featured = 'Yes';
                $photos->save();
            }
            }
            }

            $rooms_steps = new RoomsStepsStatus;

            $rooms_steps->room_id     = $rooms->id;
            $rooms_steps->basics      = 1;
            $rooms_steps->description = 1;
            $rooms_steps->location    = 1;
            $rooms_steps->photos      = 1;
            $rooms_steps->pricing     = 1;
            $rooms_steps->calendar    = 1;

            $rooms_steps->save();

            $length_of_stay_rules =  $request->length_of_stay ?: array();
            foreach($length_of_stay_rules as $rule) {
                if(@$rule['id']) {
                    $check = [
                        'id' => $rule['id'],
                        'room_id' => $rooms->id,
                        'type'    => 'length_of_stay',
                    ];
                }
                else {
                    $check = [
                        'room_id' => $rooms->id,
                        'type'    => 'length_of_stay',
                        'period'  => $rule['period']
                    ];
                }
                $price_rule = RoomsPriceRules::firstOrNew($check);
                $price_rule->room_id = $rooms->id;
                $price_rule->type =  'length_of_stay';
                $price_rule->period = $rule['period'];
                $price_rule->discount = $rule['discount'];

                $price_rule->save();
            }

            $early_bird_rules = $request->early_bird ?: array();
            foreach($early_bird_rules as $rule) {
                if(@$rule['id']) {
                    $check = [
                        'id' => $rule['id'],
                        'room_id' => $rooms->id,
                        'type'    => 'early_bird',
                    ];
                }
                else {
                    $check = [
                        'room_id' => $rooms->id,
                        'type'    => 'early_bird',
                        'period'  => $rule['period']
                    ];
                }
                $price_rule = RoomsPriceRules::firstOrNew($check);
                $price_rule->room_id = $rooms->id;
                $price_rule->type =  'early_bird';
                $price_rule->period = $rule['period'];
                $price_rule->discount = $rule['discount'];

                $price_rule->save();
            }

            $last_min_rules = $request->last_min ?: array();
            foreach($last_min_rules as $rule) {
                if(@$rule['id']) {
                    $check = [
                        'id' => $rule['id'],
                        'room_id' => $rooms->id,
                        'type'    => 'last_min',
                    ];
                }
                else {
                    $check = [
                        'room_id' => $rooms->id,
                        'type'    => 'last_min',
                        'period'  => $rule['period']
                    ];
                }
                $price_rule = RoomsPriceRules::firstOrNew($check);
                $price_rule->room_id = $rooms->id;
                $price_rule->type =  'last_min';
                $price_rule->period = $rule['period'];
                $price_rule->discount = $rule['discount'];

                $price_rule->save();
            }        

            $availability_rules = $request->availability_rules ?: array();
            foreach($availability_rules as $rule) {
                if(@$rule['edit'] == 'true')
                {
                    continue;
                }
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
            }
            $rooms_price = RoomsPrice::find($rooms->id);
            $rooms_price->minimum_stay = $request->minimum_stay ?: null;
            $rooms_price->maximum_stay = $request->maximum_stay ?: null;
            $rooms_price->save();

            $this->helper->flash_message('success', 'Room Added Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else
        {
            return redirect(ADMIN_URL.'/rooms');
        }
    }

       //update validation function 
      public function update_price(Request $request)
    {          

        $minimum_amount = $this->payment_helper->currency_convert(DEFAULT_CURRENCY,$request->currency_code, MINIMUM_AMOUNT); 
        $currency_symbol = Currency::whereCode($request->currency_code)->first()->original_symbol;
   if(isset($request->night) || isset($request->week) || isset($request->month))
   {
        $night_price = $request->night;        $week_price = $request->week;       $month_price = $request->month;

         // all error validation check
         if(isset($request->night) && isset($request->week) && isset($request->month))
        {
            if($night_price < $minimum_amount && $week_price < $minimum_amount && $month_price < $minimum_amount)
                {
                     return json_encode(['success'=>'all_error','msg' => trans('validation.min.numeric', ['attribute' => trans('messages.inbox.price'), 'min' => $currency_symbol.$minimum_amount]), 'attribute' => 'price', 'currency_symbol' => $currency_symbol,'min_amt' => $minimum_amount]);

                }
        }
         // night validation check
        if(isset($request->night))
        {
            $night_price = $request->night; 
            if($night_price < $minimum_amount)
            {
                return json_encode(['success'=>'night_false','msg' => trans('validation.min.numeric', ['attribute' => trans('messages.inbox.price'), 'min' => $currency_symbol.$minimum_amount]), 'attribute' => 'price', 'currency_symbol' => $currency_symbol,'min_amt' => $minimum_amount,'val' => $night_price]);
            }else  {   return json_encode(['success'=>'true','msg' => 'true']);     }  
        }
         // week validation check
        elseif(isset($request->week) && @$request->week !='0')
            {
                $week_price = $request->week; 
                if($week_price < $minimum_amount)
                {
                    return json_encode(['success'=>'week_false','msg' => trans('validation.min.numeric', ['attribute' => 'price', 'min' => $currency_symbol.$minimum_amount]), 'attribute' => 'week', 'currency_symbol' => $currency_symbol,'val' => $week_price]);
                }else
                {
                    return json_encode(['success'=>'true','msg' => 'true']);
                }                                    
            }
        // month validation check
       elseif(isset($request->month) && @$request->month !='0')
           {
                $month_price = $request->month; 
                if($month_price < $minimum_amount)
                {
                    return json_encode(['success'=>'month_false','msg' => trans('validation.min.numeric', ['attribute' => 'price', 'min' => $currency_symbol.$minimum_amount]), 'attribute' => 'month', 'currency_symbol' => $currency_symbol,'val' => $month_price]);
                }else
                {
                    return json_encode(['success'=>'true','msg' => 'true']);
                }  
                
           } 

        else {  return json_encode(['success'=>'true','msg' => 'true']); }  
    }  
         

    }

    /**
     * Update Room Details
     *
     * @param array $request    Input values
     * @return redirect     to Rooms View
     */
    public function update(Request $request, CalendarController $calendar)
    {
        // dd($request->all());
        $rooms_id = Rooms::find($request->id); if(empty($rooms_id))  abort('404');
        if(!$_POST)
        {
            $bedrooms = [];
            $bedrooms[0] = 'Studio';
            for($i=1; $i<=10; $i++)
                $bedrooms[$i] = $i;

            $beds = [];
            for($i=1; $i<=16; $i++)
                $beds[$i] = ($i == 16) ? $i.'+' : $i;

            $bathrooms = [];
            $bathrooms[0] = 0;
            for($i=0.5; $i<=8; $i+=0.5)
                $bathrooms["$i"] = ($i == 8) ? $i.'+' : $i;

            $accommodates = [];
            for($i=1; $i<=16; $i++)
                $accommodates[$i] = ($i == 16) ? $i.'+' : $i;

            $data['bedrooms']      = $bedrooms;
            $data['beds']          = $beds;

            $data['bed_type']      = BedType::where('status','Active')->pluck('name','id');
            $data['bathrooms']     = $bathrooms;
            $data['property_type'] = PropertyType::where('status','Active')->pluck('name','id');
            $data['room_type']     = RoomType::where('status','Active')->pluck('name','id');
            $data['lan_description']=RoomsDescriptionLang::where('room_id',$request->id)->get();
            $data['accommodates']  = $accommodates;
            $data['country']       = Country::pluck('long_name','short_name');
            $data['amenities']     = Amenities::active_all()->groupBy('type_id');;
            $data['users_list']    = User::pluck('first_name','id');
            $data['room_id']       = $request->id;
            $data['result']        = Rooms::find($request->id);
            $data['rooms_photos']  = RoomsPhotos::where('room_id',$request->id)->get();
            $data['calendar']      = str_replace(['<form name="calendar-edit-form">','</form>', url('manage-listing/'.$request->id.'/calendar')], ['', '', 'javascript:void(0);'],$calendar->generate($request->id));
            $data['prev_amenities'] = explode(',', $data['result']->amenities);
            
            $data['length_of_stay_options'] = Rooms::getLenghtOfStayOptions();
            $data['availability_rules_months_options'] = Rooms::getAvailabilityRulesMonthsOptions();
            return view('admin.rooms.edit', $data);
        }
        else if($request->submit == 'basics')
        {
            $rooms = Rooms::find($request->room_id);

            $rooms->bedrooms      = $request->bedrooms;
            $rooms->beds          = $request->beds;
            $rooms->bed_type      = $request->bed_type;
            $rooms->bathrooms     = $request->bathrooms;
            $rooms->property_type = $request->property_type;
            $rooms->room_type     = $request->room_type;
            $rooms->accommodates  = $request->accommodates;

            $rooms->save();
            
            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'booking_type')
        {
            $rooms = Rooms::find($request->room_id);

            $rooms->booking_type  = $request->booking_type;

            $rooms->save();
            
            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'description')
        {
            //check name exists or not            
             $room_type_name = Rooms::where('id','!=',@$request->room_id)->where('name','=',@$request->name[0])->get();    
             if(@$room_type_name->count() != 0){             
                     $this->helper->flash_message('error', 'This Name already exists'); // Call flash message function
                     return redirect(ADMIN_URL.'/rooms');
                }  
            //end check name
            $rooms = Rooms::find($request->room_id);
            
            $rooms->name          = $request->name[0];
            $rooms->sub_name      = RoomType::find($request->room_type)->name.' in '.$request->city;
            $rooms->summary       = $request->summary[0];

            $rooms->save();

            $rooms_description = RoomsDescription::find($request->room_id);

            $rooms_description = RoomsDescription::find($request->room_id);
            $rooms_description->space                 = $request->space[0];
            $rooms_description->access                = $request->access[0];
            $rooms_description->interaction           = $request->interaction[0];
            $rooms_description->notes                 = $request->notes[0];
            $rooms_description->house_rules           = $request->house_rules[0];
            $rooms_description->neighborhood_overview = $request->neighborhood_overview[0];
            $rooms_description->transit               = $request->transit[0];
            $rooms_description->save();
            
             RoomsDescriptionLang::where('room_id',$request->id)->delete();
            $count=count($request->name);
              for($i=1;$i<$count;$i++)
              {
                $lan_description           =  new RoomsDescriptionLang;
                $lan_description->room_id     =$rooms->id;
                $lan_description->lang_code    =$request->language[$i-1];
                $lan_description->name        =$request->name[$i];
                $lan_description->summary     =$request->summary[$i];
                $lan_description->space       =$request->space[$i];
                $lan_description->access      =$request->access[$i];
                $lan_description->interaction =$request->interaction[$i];
                $lan_description->notes       =$request->notes[$i];
                $lan_description->house_rules =$request->house_rules[$i];
                $lan_description->neighborhood_overview=$request->neighborhood_overview[$i];
                $lan_description->transit     =$request->transit[$i];
                $lan_description->save();
              }


            
            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'location')
        {
            $latt=$request->latitude;
            $longg=$request->longitude;
            if($latt==''||$longg==''){
                $address        =$request->address_line_1.' '.$request->address_line_2.' '.$request->city.' '.$request->state.' '.$request->country;
                $latlong=$this->latlong($address);

                $latt       = $latlong['lat'];
                $longg      = $latlong['long'];
            }

            $rooms_address = RoomsAddress::find($request->room_id);

            $rooms_address->address_line_1 = $request->address_line_1;
            $rooms_address->address_line_2 = $request->address_line_2;
            $rooms_address->city           = $request->city;
            $rooms_address->state          = $request->state;
            $rooms_address->country        = $request->country;
            $rooms_address->postal_code    = $request->postal_code;
            $rooms_address->latitude       = $latt;
            $rooms_address->longitude      = $longg;

            $rooms_address->save();
            
            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'amenities')
        {
            $rooms = Rooms::find($request->room_id);
            
            $rooms->amenities     = @implode(',', @$request->amenities);

            $rooms->save();
            
            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'photos')
        {
            // Image upload
        if(isset($_FILES["photos"]["name"]))
           {
                foreach($_FILES["photos"]["error"] as $key=>$error) 
                {
                $tmp_name = $_FILES["photos"]["tmp_name"][$key];

                $name = str_replace(' ', '_', $_FILES["photos"]["name"][$key]);
                
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                $name = time().$key.'_.'.$ext;

                $filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/rooms/'.$request->room_id;
                                
                if(!file_exists($filename))
                {
                    mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/rooms/'.$request->room_id, 0777, true);
                }
                                           
                if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')   
                {            
                    if(UPLOAD_DRIVER=='cloudinary')
                    {
                        $c=$this->helper->cloud_upload($tmp_name);
                        if($c['status']!="error")
                        {
                            $name=$c['message']['public_id'];    
                        }
                        else
                        {
                            $this->helper->flash_message('danger', $c['message']); // Call flash message function
                            return redirect(ADMIN_URL.'/rooms');
                        }
                    }
                    else
                    {
                        if($ext=='gif')
                        {

                            move_uploaded_file($tmp_name, "images/rooms/".$request->id."/".$name);
                        }
                        else
                        {

                            if(move_uploaded_file($tmp_name, "images/rooms/".$request->room_id."/".$name))
                            {
                                $this->helper->compress_image("images/rooms/".$request->room_id."/".$name, "images/rooms/".$request->room_id."/".$name, 80, 1440, 960);
                                $this->helper->compress_image("images/rooms/".$request->room_id."/".$name, "images/rooms/".$request->room_id."/".$name, 80, 1349, 402);
                                $this->helper->compress_image("images/rooms/".$request->room_id."/".$name, "images/rooms/".$request->room_id."/".$name, 80, 450, 250);
                            }
                        }
                    }
                    $photos          = new RoomsPhotos;
                    $photos->room_id = $request->room_id;
                    $photos->name    = $name;
                    $photos->save();        
                    
                }
           }

            $photos_featured = RoomsPhotos::where('room_id',$request->room_id)->where('featured','Yes');
            
            if($photos_featured->count() == 0)
            {
                $photos = RoomsPhotos::where('room_id',$request->room_id)->first();
                $photos->featured = 'Yes';
                $photos->save();
            }
            }
            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'video')
        {
            $rooms = Rooms::find($request->room_id);

            $search     = '#(.*?)(?:href="https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12}).*#x';
                $count      = preg_match($search, $request->video);
                $rooms      = Rooms::find($request->id); 
                if($count == 1) {
                    $replace    = 'https://www.youtube.com/embed/$2';
                    $video      = preg_replace($search,$replace,$request->video);
                    $rooms->video = $video;
                }
                else {
                    $rooms->video = $request->video;
                }

            $rooms->save();
            
            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'pricing')
        {
            $rooms_price = RoomsPrice::find($request->room_id);

            $rooms_price->night            = $request->night;
            // $rooms_price->week             = $request->week;
            // $rooms_price->month            = $request->month;
            $rooms_price->cleaning         = $request->cleaning;
            $rooms_price->additional_guest = $request->additional_guest;
            $rooms_price->guests           = ($request->additional_guest) ? $request->guests : '0';
            $rooms_price->security         = $request->security;
            $rooms_price->weekend          = $request->weekend;
            $rooms_price->currency_code    = $request->currency_code;

            $rooms_price->save();
            
            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'terms')
        {
            $rooms = Rooms::find($request->room_id);
            
            $rooms->cancel_policy = $request->cancel_policy;

            $rooms->save();
            
            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'price_rules')
        {
            $length_of_stay_rules =  $request->length_of_stay ?: array();
            foreach($length_of_stay_rules as $rule) {
                if(@$rule['id']) {
                    $check = [
                        'id' => $rule['id'],
                        'room_id' => $request->room_id,
                        'type'    => 'length_of_stay',
                    ];
                }
                else {
                    $check = [
                        'room_id' => $request->room_id,
                        'type'    => 'length_of_stay',
                        'period'  => $rule['period']
                    ];
                }
                $price_rule = RoomsPriceRules::firstOrNew($check);
                $price_rule->room_id = $request->room_id;
                $price_rule->type =  'length_of_stay';
                $price_rule->period = $rule['period'];
                $price_rule->discount = $rule['discount'];

                $price_rule->save();
            }

            $early_bird_rules = $request->early_bird ?: array();
            foreach($early_bird_rules as $rule) {
                if(@$rule['id']) {
                    $check = [
                        'id' => $rule['id'],
                        'room_id' => $request->room_id,
                        'type'    => 'early_bird',
                    ];
                }
                else {
                    $check = [
                        'room_id' => $request->room_id,
                        'type'    => 'early_bird',
                        'period'  => $rule['period']
                    ];
                }
                $price_rule = RoomsPriceRules::firstOrNew($check);
                $price_rule->room_id = $request->room_id;
                $price_rule->type =  'early_bird';
                $price_rule->period = $rule['period'];
                $price_rule->discount = $rule['discount'];

                $price_rule->save();
            }

            $last_min_rules = $request->last_min ?: array();
            foreach($last_min_rules as $rule) {
                if(@$rule['id']) {
                    $check = [
                        'id' => $rule['id'],
                        'room_id' => $request->room_id,
                        'type'    => 'last_min',
                    ];
                }
                else {
                    $check = [
                        'room_id' => $request->room_id,
                        'type'    => 'last_min',
                        'period'  => $rule['period']
                    ];
                }
                $price_rule = RoomsPriceRules::firstOrNew($check);
                $price_rule->room_id = $request->room_id;
                $price_rule->type =  'last_min';
                $price_rule->period = $rule['period'];
                $price_rule->discount = $rule['discount'];

                $price_rule->save();
            }

            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'availability_rules') 
        {
            $availability_rules = $request->availability_rules ?: array();
            foreach($availability_rules as $rule) {
                if(@$rule['edit'] == 'true')
                {
                    continue;
                }
                $check = [
                    'id' => @$rule['id'] ?: '',
                ];
                $availability_rule = RoomsAvailabilityRules::firstOrNew($check);
                $availability_rule->room_id = $request->room_id;
                $availability_rule->start_date = date('Y-m-d', $this->helper->custom_strtotime(@$rule['start_date'], PHP_DATE_FORMAT));
                $availability_rule->end_date = date('Y-m-d', $this->helper->custom_strtotime(@$rule['end_date'], PHP_DATE_FORMAT));
                $availability_rule->minimum_stay = @$rule['minimum_stay'] ?: null;
                $availability_rule->maximum_stay = @$rule['maximum_stay'] ?: null;
                $availability_rule->type = @$rule['type'] != 'prev' ? @$rule['type']: @$availability_rule->type;
                $availability_rule->save();
            }
            $rooms_price = RoomsPrice::find($request->room_id);
            $rooms_price->minimum_stay = $request->minimum_stay ?: null;
            $rooms_price->maximum_stay = $request->maximum_stay ?: null;
            $rooms_price->save();

            $this->helper->flash_message('success', 'Room Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/rooms');
        }
        else if($request->submit == 'cancel')
        {
            return redirect(ADMIN_URL.'/rooms');
        }
        else
        {
            return redirect(ADMIN_URL.'/rooms');
        }
    }

    public function delete_price_rule(Request $request) {
        $id = $request->id;
        RoomsPriceRules::where('id', $id)->delete();
        return json_encode(['success' => true]);
    }
    public function delete_availability_rule(Request $request) {
        $id = $request->id;
        RoomsAvailabilityRules::where('id', $id)->delete();
        return json_encode(['success' => true]);
    }
    

 public function update_video(Request $request)
     {
            
            $data_calendar     = @json_decode($request['data']);
            $rooms = Rooms::find($data_calendar->id);
             
            $search     = '#(.*?)(?:href="https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12}).*#x';
                $count      = preg_match($search, $data_calendar->video);
                $rooms      = Rooms::find($data_calendar->id); 
                if($count == 1) {
                    $replace    = 'http://www.youtube.com/embed/$2';
                    $video      = preg_replace($search,$replace,$data_calendar->video);
                    $rooms->video = $video;
                }
                else {
                    $rooms->video = $data_calendar->video;
                }

            $rooms->save();
          
            return json_encode(['success'=>'true', 'steps_count' => $rooms->steps_count,'video' => $rooms->video]);
        }
    /**
     * Delete Rooms
     *
     * @param array $request    Input values
     * @return redirect     to Rooms View
     */
    public function latlong($address){
        $url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($address);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
            $responseJson = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($responseJson);

            if ($response->status == 'OK') {
                $latitude = $response->results[0]->geometry->location->lat;
                $longitude = $response->results[0]->geometry->location->lng;
                $add=array('lat'=>$latitude,'long'=>$longitude);
                return $add;
            }
            
    }


    public function delete(Request $request)
    {
        $check = Reservation::whereRoomId($request->id)->whereIn('status', ['Accepted','Pending','Pre-Accepted','Pre-approved'])->count();

        if($check) {
            $this->helper->flash_message('error', 'This room has some reservations. So, you cannot delete this room.'); // Call flash message function
        }
        else 
        {   $exists_rnot = Rooms::find($request->id);
            if(@$exists_rnot){
            Rooms::find($request->id)->Delete_All_Room_Relationship(); 
           $this->helper->flash_message('success', 'Deleted Successfully');
           }
           else{
              $this->helper->flash_message('error', 'This Room Already Deleted.');
           } // Call flash message function
        }
        
        return redirect(ADMIN_URL.'/rooms');
    }

    /**
     * Users List for assign Rooms Owner
     *
     * @param array $request    Input values
     * @return json Users table
     */
    public function users_list(Request $request)
    {
        return User::where('first_name', 'like', $request->term.'%')->select('first_name as value','id')->get();
    }

    /**
     * Ajax function of Calendar Dropdown and Arrow
     *
     * @param array $request    Input values
     * @param array $calendar   Instance of CalendarController
     * @return html Calendar
     */
    public function ajax_calendar(Request $request, CalendarController $calendar)
    {
        $data_calendar     = @json_decode($request['data']);
        $year              = @$data_calendar->year;
        $month             = @$data_calendar->month;
        $data['calendar']  = str_replace(['<form name="calendar-edit-form">','</form>', url('manage-listing/'.$request->id.'/calendar')], ['', '', 'javascript:void(0);'],$calendar->generate($request->id, $year, $month));

        return $data['calendar'];
    }

    /**
     * Delete Rooms Photo
     *
     * @param array $request    Input values
     * @return json success   
     */
    public function delete_photo(Request $request)
    {
        
        $photos          = RoomsPhotos::find($request->photo_id);
        if($photos != NULL){
            $photos->delete();
        }
        $photos_featured = RoomsPhotos::where('room_id',$request->room_id)->where('featured','Yes');
            
        if($photos_featured->count() == 0)
        {
            $photos_featured = RoomsPhotos::where('room_id',$request->room_id);
            
            if($photos_featured->count() !=0)
            {
                $photos = RoomsPhotos::where('room_id',$request->room_id)->first();
                $photos->featured = 'Yes';
                $photos->save();
            }
        }
        
        return json_encode(['success'=>'true']);
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

    public function popular(Request $request)
    {
        $prev = Rooms::find($request->id)->popular;

        if($prev == 'Yes')
            Rooms::where('id',$request->id)->update(['popular'=>'No']);
        else
            Rooms::where('id',$request->id)->update(['popular'=>'Yes']);

        $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
        return redirect(ADMIN_URL.'/rooms');
    }

    public function recommended(Request $request)
    {
        $room = Rooms::find($request->id);
        $user_check = User::find($room->user_id);         
        if($room->status != 'Listed')
        {
            $this->helper->flash_message('error', 'Not able to recommend for unlisted listing');
            return back();
        }
        if($user_check->status != 'Active')
        {
            $this->helper->flash_message('error', 'Not able to recommend for Not Active users');
            return back();
        }

        $prev = $room->recommended;

        if($prev == 'Yes')
            Rooms::where('id',$request->id)->update(['recommended'=>'No']);
        else
            Rooms::where('id',$request->id)->update(['recommended'=>'Yes']);

        $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
        return redirect(ADMIN_URL.'/rooms');
    }

    public function featured_image(Request $request) 
    {

        RoomsPhotos::whereRoomId($request->id)->update(['featured' => 'No']);

        RoomsPhotos::whereId($request->photo_id)->update(['featured' => 'Yes']);

        return 'success';
    }
}
