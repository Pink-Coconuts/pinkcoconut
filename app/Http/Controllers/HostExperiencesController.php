<?php
 
/**
 * Host Experiences Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Host Experiences
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

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
use App\Http\Helper\PaymentHelper;
use App\Http\Start\Helpers;
use Auth;
use DB;
use DateTime;
use Session;
use Validator;

class HostExperiencesController extends Controller
{
    protected $payment_helper; // Global variable for Payment Helpers instance
    protected $helper; // Global variable for Helpers instance
    
    /**
     * Constructor to Set Global variables
     *
     */
    public function __construct()
    {
        // Initialize the helpers instances
        $this->payment_helper = new PaymentHelper;
        $this->helper = new Helpers;
    }

    /**
     * Load Experiences Home View
     *
     * @return Html host experiences home view file
     */
    public function index()
    {
        $data['host_experience_cities'] = HostExperienceCities::active()->pluck('name', 'id');
        $data['header_class']  = '';
        if(!Auth::user())
        {
            $data['header_class'] = 'exp_mak';
        }
        else
        {
            $data['host_experiences'] = HostExperiences::authUser()->get();
            if($data['host_experiences']->count() == 0)
            {
                $data['header_class']  = 'exp_mak';
            }
        }

        return view('host_experiences.home', $data);
    }

    /**
     * Delete Experiences
     *
     * @param Illuminate\Http\Request $request
     * @return Redirect to Host Experiences Home page
     */
    public function delete_host_experience(Request $request)
    {
        $host_experience_id = $request->host_experience_id;
        $host_experience = HostExperiences::authUser()->where('id', $host_experience_id)->first();
        $reservation_count = Reservation::where('room_id', $host_experience_id)->where('list_type', 'Experiences')->count();
        if($host_experience && $reservation_count <= 0)
        {
            $host_experience->force_delete();
            $this->helper->flash_message('success', trans('experiences.details.deleted_success_message'));
        }
        elseif($reservation_count > 0)
        {
            $this->helper->flash_message('error', trans('experiences.details.delete_bookings_exists_error'));
        }
        return redirect('host/experiences');
    }

    /**
     * 
     *
     * @param Illuminate\Http\Request $request
     * @return Redirect to Review Experience Page
     */
    public function set_city(Request $request)
    {
        $city = $request->city; 
        $url = url('host/experiences/new?city='.$city);
        Session::put('ajax_redirect_url',$url);
    }
    /**
     * Create a new Host Experience
     *
     * @param Illuminate\Http\Request $request
     * @return Redirect to Review Experience Page
     */
    public function new_host_experience(Request $request)
    {
        $city_id = $request->city;
        $city = HostExperienceCities::active()->where('id', $city_id)->first();

        if(!$city)
        {
            return redirect('host/experiences');
        }

        $host_experience = new HostExperiences;
        $host_experience->user_id = Auth::user()->id;
        $host_experience->city = $city->id;
        $host_experience->timezone = $city->timezone;
        $host_experience->currency_code = $city->currency_code;
        $host_experience->save();

        $host_experience_guest_requirements = new HostExperienceGuestRequirements;
        $host_experience_guest_requirements->host_experience_id = $host_experience->id;
        $host_experience_guest_requirements->save();

        $host_experience_location = new HostExperienceLocation;
        $host_experience_location->host_experience_id = $host_experience->id;
        $host_experience_location->save();

        return redirect('host/review_experience/'.$host_experience->id.'?step_num=0');
    }

    /**
     * Review Standards for the Experience Hosting
     *
     * @param Illuminate\Http\Request $request
     * @return Html Review Experience view
     */
    public function review_experience(Request $request)
    {
        $host_experience_id = $request->host_experience_id;
        $step_num           = $data['step_num'] = ($request->step_num && $request->step_num <= 2) ? $request->step_num : 0;

        $host_experience    = $data['host_experience'] = HostExperiences::where('id', $host_experience_id)->first();
        $step               = $data['step'] = 'step_'.$step_num;

        if(!$host_experience)
        {
            return redirect('host/experiences');
        }

        if($host_experience->is_reviewed)
        {
            return redirect('host/manage_experience/'.$host_experience_id);
        }

        $data['host_experience_steps_group'] = $host_experience->steps->groupBy('parent');
        $data['main_content_section']        = 'review_experience.'.$step;
        $data['ajax_base_url']               = url('host/ajax_review_experience/'.$host_experience_id);

        $data['host_experience_array']       = $this->get_host_experience_array($host_experience);
        
        return view('host_experiences.manage_experience.main', $data);
    }

    /**
     * Review Standards for the Experience Hosting Ajax 
     *
     * @param Illuminate\Http\Request $request
     * @return Json $return
     */
    public function ajax_review_experience(Request $request)
    {
        $host_experience_id = $request->host_experience_id;
        $step_num           = $data['step_num'] = ($request->step_num && $request->step_num <= 2) ? $request->step_num : 0;

        $host_experience    = $data['host_experience'] = HostExperiences::where('id', $host_experience_id)->first();
        $step               = $data['step'] = 'step_'.$step_num;
        $return             = array();

        if(!$host_experience)
        {
            $return['status'] = 503;
            $return['location'] =  url('host/experiences');
        }
        elseif($host_experience->is_reviewed)
        {
            $return['status'] = 503;
            $return['location'] =  url('host/manage_experience/'.$host_experience_id);
        }
        else
        {
            $data['main_content_section']        = 'review_experience.'.$step;

            $return['status']   = 200;
            $return['step_num'] = $step_num;
            $return['host_experience_steps'] = $host_experience->steps;
            $return['content']  = view('host_experiences.review_experience.step_'.$step_num, $data)->render();
        
        }
        return json_encode($return);
    }

    /**
     * Manage Experience view
     *
     * @param Illuminate\Http\Request $request
     * @return Html Manage Experience View 
     */
    public function manage_experience(Request $request)
    {
        $host_experience_id = $request->host_experience_id;
        $step_num           = $data['step_num'] = ($request->step_num && $request->step_num < 21) ? $request->step_num : 0;

        $host_experience    = $data['host_experience'] = HostExperiences::where('id', $host_experience_id)->with(['host_experience_location', 'guest_requirements', 'city_details'])->first();

        if(!$host_experience)
        {
            return redirect('host/experiences');
        }

        if(!$host_experience->is_reviewed)
        {
            return redirect('host/review_experience/'.$host_experience_id);
        }
        if($step_num != 21)
        {
            $step_details = $host_experience->steps[$step_num];
            $is_locked   = @$step_details['locked'];
            if($step_details['parent'] != '')
            {
                $parent_step_details = $host_experience->steps->where('step', $step_details['parent'])->first();
                $is_locked = @$parent_step_details['locked'];
            }
            if($is_locked)
            {
                $return_step = $host_experience->steps->where('status', 0)->filter(function ($value) {
                    return $value['parent'] != '';
                })->first();
                $step_num = $return_step['step_num'];
                return redirect('host/manage_experience/'.$host_experience_id.'?step_num='.$step_num);
            }
        }
        
        if($step_num == 21)
            $step               = $data['step']  = 'created';
        else
            $step               = $data['step']  = @$host_experience->steps[$step_num]['step'];

        $data['host_experience_steps_group'] = $host_experience->steps->groupBy('parent');
        $data['main_content_section']        = 'manage_experience.'.$step;
        $data['ajax_base_url']               = url('host/ajax_manage_experience/'.$host_experience_id);

        $data['languages']                   = Language::translatable()->pluck('name', 'value');
        $data['categories']                  = HostExperienceCategories::active()->get();
        $data['times_array']                 = $host_experience->times_array;
        $data['host_experience_photos']      = HostExperiencePhotos::where('host_experience_id', $host_experience_id)->get();
        $data['provide_items']               = HostExperienceProvideItems::active()->get();
        $data['host_experience_provides']    = HostExperienceProvides::where('host_experience_id', $host_experience_id)->get();
        $data['countries']                   = Country::all()->pluck('long_name','short_name');
        $data['service_fee']                 = @Fees::where('name', 'experience_service_fee')->first()->value;

        $users_languages = User::where('status', 'Active')->get()->implode('languages', ',');
        $languages_array = explode(',', $users_languages);
        // $languages_array = array_unique($languages_array);
        $language_users_count = array_count_values($languages_array);
        asort($language_users_count);

        $active_users_count = User::count();

        $language_spoken_data = array();
        foreach($language_users_count as $language_id => $users_count)
        {
            $language = Language::active()->where('id', $language_id)->first();
            if($language)
            {
                $percentage = round(($users_count / $active_users_count) * 100);
                $language_spoken_data[] = array(
                    'name' => $language->name,
                    'percentage' => $percentage
                );
            }
        }
        
        $language_spoken_data = array_slice($language_spoken_data, -5);
        $data['language_spoken_data'] = array_reverse($language_spoken_data);

        $data['host_experience_array']       = $this->get_host_experience_array($host_experience);

        if($step == 'edit_calendar')
        {
            $calendar_data = $this->get_calendar_data($host_experience_id);
            $data = array_merge($data, $calendar_data);
        }

        return view('host_experiences.manage_experience.main', $data);
    }

    /**
     * Manage Experience View Ajax
     *
     * @param Illuminate\Http\Request $request
     * @return Json $return
     */
    public function ajax_manage_experience(Request $request)
    {        
        $host_experience_id = $request->host_experience_id;
        $step_num           = $data['step_num'] = ($request->step_num && $request->step_num <= 21) ? $request->step_num : 0;

        $host_experience    = $data['host_experience'] = HostExperiences::where('id', $host_experience_id)->with(['host_experience_location', 'guest_requirements', 'city_details'])->first();
        $return             = array();

        if($step_num != 21)
        {
            $step_details = $host_experience->steps[$step_num];
            $is_locked   = @$step_details['locked'];
            if($step_details['parent'] != '')
            {
                $parent_step_details = $host_experience->steps->where('step', $step_details['parent'])->first();
                $is_locked = @$parent_step_details['locked'];
            }
            if($is_locked)
            {
                $return_step = $host_experience->steps->where('status', 0)->filter(function ($value) {
                    return $value['parent'] != '';
                })->first();
                $step_num = $return_step['step_num'];
            }
        }
        $data['step_num'] = $step_num;
        if(!$host_experience)
        {
            $return['status'] = 503;
            $return['location'] =  url('host/experiences');
        }
        elseif(!$host_experience->is_reviewed)
        {
            $return['status'] = 503;
            $return['location'] =  url('host/review_experience/'.$host_experience_id);
        }
        else
        {
            if($step_num == 21)
                $step               = $data['step']  = 'created';
            else
                $step               = $data['step']  = @$host_experience->steps[$step_num]['step'];
            $data['languages']  = Language::translatable()->pluck('name', 'value');
            $data['categories']                  = HostExperienceCategories::active()->get();
            $data['times_array']                 = $host_experience->times_array;
            $data['host_experience_photos']      = HostExperiencePhotos::where('host_experience_id', $host_experience_id)->get();
            $data['provide_items']               = HostExperienceProvideItems::active()->get();
            $data['host_experience_provides']    = HostExperienceProvides::where('host_experience_id', $host_experience_id)->get();
            $data['countries']                   = Country::all()->pluck('long_name','short_name');
            $data['service_fee']                 = @Fees::where('name', 'experience_service_fee')->first()->value;

            if($step == 'edit_calendar')
            {
                $calendar_data = $this->get_calendar_data($host_experience_id);
                $data = array_merge($data, $calendar_data);
            }

            $users_languages = User::where('status', 'Active')->get()->implode('languages', ',');
            $languages_array = explode(',', $users_languages);
            // $languages_array = array_unique($languages_array);
            $language_users_count = array_count_values($languages_array);
            asort($language_users_count);

            $active_users_count = User::count();

            $language_spoken_data = array();
            foreach($language_users_count as $language_id => $users_count)
            {
                $language = Language::active()->where('id', $language_id)->first();
                if($language)
                {
                    $percentage = round(($users_count / $active_users_count) * 100);
                    $language_spoken_data[] = array(
                        'name' => $language->name,
                        'percentage' => $percentage
                    );
                }
            }
            $language_spoken_data = array_slice($language_spoken_data, -5);
            $data['language_spoken_data'] = array_reverse($language_spoken_data);

            if($step_num == 21)
            {
                $data['host_experience_steps_group'] = $host_experience->steps->groupBy('parent');
                $return['menu_content'] = view('host_experiences.manage_experience.menu', $data)->render();
            }

            $return['status']   = 200;
            $return['step_num'] = $step_num;
            $return['step']     = $step;
            $return['host_experience_steps'] = $host_experience->steps;
            $return['content']  = view('host_experiences.manage_experience.'.$step, $data)->render();
        
        }
        return json_encode($return);
    }

    /**
     * Update Experience Ajax
     *
     * @param Illuminate\Http\Request $request
     * @return Array $return
     */
    public function update_experience(Request $request,EmailController $email_controller)
    {
        $host_experience_id = $request->host_experience_id;
        $step_num           = $request->step_num;

        $host_experience    = HostExperiences::where('id', $host_experience_id)->with(['host_experience_location', 'guest_requirements'])->first();
        $return             = array();

        if(!$host_experience)
        {
            $return['status'] = 503;
            $return['location'] =  url('host/experiences');
        }
        else
        {
            $step = !$host_experience->is_reviewed ? $step_num : @$host_experience->steps[$step_num]['step'];

            $errors = $this->validate_host_experiene($request->all(), $step, $host_experience->is_reviewed);
            if(count($errors) >0)
            {
                $return['status'] = 300;
                $return['errors'] = $errors;
            }
            else
            {
                if(!$host_experience->is_reviewed)
                {
                    if($step_num == 1)
                    {
                        $host_experience->hosting_standards_reviewed = $request->hosting_standards_reviewed ? 'Yes' : 'No';
                    }
                    if($step_num == 2)
                    {
                        $host_experience->experience_standards_reviewed = $request->experience_standards_reviewed ? 'Yes' : 'No';
                    }   
                }
                else
                {
                    if($step == 'language')
                    {
                        $host_experience->language = $request->language;
                    }
                    if($step == 'category')
                    {
                        $host_experience->category = $request->category;
                        $host_experience->secondary_category = $request->secondary_category;
                    }
                    if($step == 'title')
                    {
                        $host_experience->title = $request->title;
                    }
                    if($step == 'time')
                    {
                        $host_experience->start_time = $request->start_time;
                        $host_experience->end_time = $request->end_time;
                    }
                    if($step == 'tagline')
                    {
                        $host_experience->tagline = $request->tagline;
                    }
                    if($step == 'what_will_do')
                    {
                        $host_experience->what_will_do = $request->what_will_do;
                    }
                    if($step == 'where_will_be')
                    {
                        $host_experience->where_will_be = $request->where_will_be;
                    }
                    if($step == 'where_will_meet')
                    {
                        $host_experience->host_experience_location->address_line_1     = @$request->host_experience_location['address_line_1'];
                        $host_experience->host_experience_location->address_line_2     = @$request->host_experience_location['address_line_2'];
                        $host_experience->host_experience_location->city               = @$request->host_experience_location['city'];
                        $host_experience->host_experience_location->country            = @$request->host_experience_location['country'];
                        $host_experience->host_experience_location->directions         = @$request->host_experience_location['directions'];
                        $host_experience->host_experience_location->latitude           = @$request->host_experience_location['latitude'];
                        $host_experience->host_experience_location->location_name      = @$request->host_experience_location['location_name'];
                        $host_experience->host_experience_location->longitude          = @$request->host_experience_location['longitude'];
                        $host_experience->host_experience_location->postal_code        = @$request->host_experience_location['postal_code'];
                        $host_experience->host_experience_location->state              = @$request->host_experience_location['state'];
                        $host_experience->host_experience_location->save();
                    }
                    if($step == 'what_will_provide')
                    {
                        foreach($request->removed_provides as $removed_provide)
                        {
                            HostExperienceProvides::where('id', @$removed_provide['id'])->delete();
                        }
                        foreach($request->host_experience_provides as $provide)
                        {
                            if(@$provide['host_experience_provide_item_id'] > 0 && @$provide['name'] != '')
                            {
                                if(@$provide['id'])
                                {
                                    $host_experience_provide = HostExperienceProvides::find(@$provide['id']);
                                }
                                else
                                {
                                    $host_experience_provide = new HostExperienceProvides;
                                }
                                $host_experience_provide->host_experience_id = $host_experience->id;
                                $host_experience_provide->host_experience_provide_item_id = @$provide['host_experience_provide_item_id'];
                                $host_experience_provide->name = @$provide['name'];
                                $host_experience_provide->additional_details = @$provide['additional_details'];
                                $host_experience_provide->save();
                            }
                        }
                        $host_experience->need_provides = $request->need_provides;
                    }
                    if($step == 'notes')
                    {
                        $host_experience->notes = $request->notes;
                        $host_experience->need_notes = $request->need_notes;
                    }
                    if($step == 'about_you')
                    {
                        $host_experience->about_you = $request->about_you;
                    }
                    if($step == 'guest_requirements')
                    {
                        $host_experience->guest_requirements->includes_alcohol        = @$request->guest_requirements['includes_alcohol'];
                        $host_experience->guest_requirements->minimum_age             = @$request->guest_requirements['minimum_age'];
                        $host_experience->guest_requirements->allowed_under_2         = @$request->guest_requirements['allowed_under_2'];
                        $host_experience->guest_requirements->special_certifications  = @$request->guest_requirements['special_certifications'];
                        $host_experience->guest_requirements->additional_requirements = @$request->guest_requirements['additional_requirements'];
                        $host_experience->guest_requirements->save();
                    }
                    if($step == 'group_size')
                    {
                        $host_experience->number_of_guests = $request->number_of_guests;
                    }
                    if($step == 'price')
                    {
                        $host_experience->price_per_guest = $request->price_per_guest;
                        $host_experience->is_free_under_2 = $request->is_free_under_2;
                    }
                    if($step == 'preparation_time')
                    {
                        $host_experience->preparation_hours = $request->preparation_hours;
                        $host_experience->last_minute_guests = $request->last_minute_guests;
                        $host_experience->cutoff_time = $request->cutoff_time;
                    }
                    if($step == 'packing_list')
                    {
                        foreach($request->removed_packing_lists as $removed_packing_list)
                        {
                            HostExperiencePackingLists::where('id', @$removed_packing_list['id'])->delete();
                        }
                        foreach($request->host_experience_packing_lists as $packing_list)
                        {
                            if(@$packing_list['item'] != '')
                            {
                                if(@$packing_list['id'])
                                {
                                    $host_experience_packing_list = HostExperiencePackingLists::find(@$packing_list['id']);
                                }
                                else
                                {
                                    $host_experience_packing_list = new HostExperiencePackingLists;
                                }
                                $host_experience_packing_list->host_experience_id = $host_experience->id;
                                $host_experience_packing_list->item = @$packing_list['item'];
                                $host_experience_packing_list->save();
                            }
                        }
                        $host_experience->need_packing_lists = $request->need_packing_lists;
                    }
                    if($step == 'review_submit')
                    {
                        $host_experience->quality_standards_reviewed  = $request->quality_standards_reviewed;
                        $host_experience->local_laws_reviewed         = $request->local_laws_reviewed;
                        $host_experience->terms_service_reviewed      = $request->terms_service_reviewed;
                        $host_experience->status = 'Listed';
                        $email_controller->review_submited($host_experience->id);
                    }
                }     
                $return['status']   = 200;
                $host_experience->updated_at = Date('Y-m-d H:i:s');
                $host_experience->save();
                $return['host_experience'] = $this->get_host_experience_array($host_experience);
                $return['host_experience_provides'] = HostExperienceProvides::where('host_experience_id', $host_experience->id)->get();
                $return['host_experience_packing_lists'] = $host_experience->host_experience_packing_lists;
                $return['host_experience_steps'] = $host_experience->steps;
            }
        }
        return $return;
    }

    /**
    * Upload Experience Photos Ajax
    *
    * @param Illuminate\Http\Request $request
    * @return Array $return
    */
    public function upload_photo(Request $request)
    {
        $file = $request->file('file');
        $host_experience_id = $request->host_experience_id;
        $host_experience    = HostExperiences::where('id', $host_experience_id)->first();
        $return = array('status', 'error');
        if($file) {

            $upload_path = 'images/host_experiences/'.$host_experience_id;
            $upload_path_dir = public_path().'/'.$upload_path;
            if(!file_exists($upload_path_dir))
            {
                mkdir($upload_path_dir, 0777, true);
            }

            if(UPLOAD_DRIVER=='cloudinary')
            {
                $c=$this->helper->cloud_upload($file);
                if($c['status']!="error")
                {
                    $filename=$c['message']['public_id'];
                }
                else
                {
                    $return['status'] = 300;
                    $return['error']  = 'File upload error';
                }
            }
            else
            {
                $extension =   $file->getClientOriginalExtension();
                $fname  =   str_slug($file->getClientOriginalName()).time();
                $filename = $fname . '.' . $extension;
                $success = $file->move($upload_path_dir, $filename);
                
                if(!$success)
                {
                    $return['status'] = 300;
                    $return['error']  = 'File upload error';
                }
                else
                {
                    $this->helper->compress_image($upload_path."/".$filename, $upload_path."/".$filename, 80, 853, 1280);

                /* Start - Resize image for display in home page */

                $resize_name = $fname. '_resize.' . $extension;
                $resize_file_path = $upload_path_dir."/".$filename;
                // $resize_width ='237'; 
                // $resize_height ='160'; 
                $resize_path =$upload_path."/".$resize_name; 

                $this->helper->resizeImage($resize_file_path/*,$resize_width,$resize_height*/,$resize_path);

                /* End - Resize image for display in home page */
                
                }
            }

            if(@$return['error'] == '') 
            {
                $host_experience_photo = new HostExperiencePhotos;
                $host_experience_photo->host_experience_id = $host_experience_id;
                $host_experience_photo->name = $filename;
                $host_experience_photo->save();

                $host_experience->updated_at = Date('Y-m-d H:i:s');
                $host_experience->save();

                $return['status'] = 200;
                $return['host_experience_photos'] = $host_experience->host_experience_photos;
                $return['host_experience_steps'] = $host_experience->steps;
            }
            else
            {
                $return['status'] = 300;
                $return['error']  = 'File upload error';
            }
        }
        else
        {
            $return['status'] = 300;
            $return['error']  = 'File not supported';
        }
        return $return;
    }

    /**
    * Upload Experience Photos Ajax
    *
    * @param Illuminate\Http\Request $request
    * @return Array $return
    */
    public function delete_photo(Request $request)
    {
        $host_experience_id = $request->host_experience_id;
        $photo_id = $request->photo_id;
        $host_experience    = HostExperiences::where('id', $host_experience_id)->first();
        HostExperiencePhotos::where('host_experience_id', $host_experience_id)->where('id', $photo_id)->delete();
        $host_experience->updated_at = Date('Y-m-d H:i:s');
        $host_experience->save();
        $return['host_experience_steps'] = $host_experience->steps;
        $return['status'] = 200;
        return $return;
    }

    /**
    * Validate Experience Update Data
    *
    * @param Array $request_data Post values
    * @param String $step Current Step of the Manage/Review Experience
    * @param Bool $is_reviewed Is it Manage or Review Experience
    * @return Array $errors
    */
    public function validate_host_experiene($request_data, $step, $is_reviewed)
    {
        $errors = [];
        $rules = array();
        if(!$is_reviewed)
        {
            if($step == 1)    
            {
                $rules['hosting_standards_reviewed'] = 'accepted';        
            }
            if($step == 2)
            {
                $rules['experience_standards_reviewed'] = 'accepted';
            }
        }
        else
        {
            if($step == 'language')    
            {
                $rules['language'] = 'required';
            }
            if($step == 'category')
            {
                $rules['category'] = 'required';
            }
            if($step == 'title')
            {
                $rules['title'] = 'required';
            }
            if($step == 'time')
            {
                $rules['start_time'] = 'required';
                $rules['end_time'] = 'required';
            }
            if($step == 'tagline')
            {
                $rules['tagline'] = 'required';
            }
            if($step == 'what_will_do')
            {
                $rules['what_will_do'] = 'required';
            }
            if($step == 'where_will_be')
            {
                $rules['where_will_be'] = 'required';
            }
            if($step == 'where_will_meet')
            {
                $rules['host_experience_location.address_line_1'] = 'required';
                $rules['host_experience_location.city'] = 'required';
                $rules['host_experience_location.country'] = 'required';
                $rules['host_experience_location.latitude'] = 'required';
                $rules['host_experience_location.location_name'] = 'required';
                $rules['host_experience_location.longitude'] = 'required';
            }
            if($step == 'notes')
            {
                if(@$request_data['need_notes'] != 'No')
                    $rules['notes'] = 'required';
            }
            if($step == 'about_you')
            {
                $rules['about_you'] = 'required';
            }
            if($step == 'guest_requirements')
            {
                $rules['guest_requirements.minimum_age'] = 'required';
            }
            if($step == 'group_size')
            {
                $rules['number_of_guests'] = 'required';
            }
            if($step == 'price')
            {
                $rules['price_per_guest'] = 'required';
            }
            if($step == 'preparation_time')
            {
                $rules['preparation_hours'] = 'required';
            }
            if($step == 'review_submit')
            {
                $rules['quality_standards_reviewed'] = 'accepted';
                $rules['local_laws_reviewed'] = 'accepted';
                $rules['terms_service_reviewed'] = 'accepted';

                $request_data['quality_standards_reviewed'] = $request_data['quality_standards_reviewed'] == 'Yes';
                $request_data['local_laws_reviewed'] = $request_data['quality_standards_reviewed'] == 'Yes';
                $request_data['terms_service_reviewed'] = $request_data['terms_service_reviewed'] == 'Yes';
            }
        }
        
        $messages = array();

        // validation custom Fields name
        $niceNames = array(
            'hosting_standards_reviewed'                    => trans('experiences.manage.hosting_standards'),
            'experience_standards_reviewed'                 => trans('experiences.manage.experience_standards'),
            'language'                                      => trans('experiences.manage.language'),
            'category'                                      => trans('experiences.manage.category'),
            'title'                                         => trans('experiences.manage.experience_title'),
            'start_time'                                    => trans('experiences.manage.start_time'),
            'end_time'                                      => trans('experiences.manage.end_time'),
            'tagline'                                       => trans('experiences.manage.tagline'),
            'what_will_do'                                  => trans('experiences.manage.what_will_do'),
            'where_will_be'                                 => trans('experiences.manage.where_will_be'),
            'notes'                                         => trans('experiences.manage.notes'),
            'host_experience_location.address_line_1'       => trans('experiences.manage.street_address'),
            'host_experience_location.city'                 => trans('experiences.manage.city'),
            'host_experience_location.country'              => trans('experiences.manage.country'),
            'host_experience_location.latitude'             => trans('experiences.manage.latitude'),
            'host_experience_location.location_name'        => trans('experiences.manage.location_name'),
            'host_experience_location.longitude'            => trans('experiences.manage.longitude'),
            'host_experience_location.about_you'            => trans('experiences.manage.about_you'),
            'guest_requirements.minimum_age'                => trans('experiences.manage.minimum_age'),
            'number_of_guests'                              => trans('experiences.manage.number_of_guests'),
            'price_per_guest'                               => trans('experiences.manage.price_per_guest'),
            'quality_standards_reviewed'                    => trans('experiences.manage.quality_standards'),
            'local_laws_reviewed'                           => trans('experiences.manage.local_laws'),
            'terms_service_reviewed'                        => trans('experiences.manage.terms_of_service'),
        );

        $validator = Validator::make($request_data, $rules, $messages, $niceNames);
        if($validator->messages())
        {
            $errors = $validator->errors()->getMessages();
        }
        return $errors;
    }

    /**
    * Convert Host Experience Object to Array 
    * 
    * @param App\Models\HostExperiences $host_experience 
    * @return Array $host_experience_array
    */
    public function get_host_experience_array($host_experience)
    {
        $host_experience_array               = $host_experience->toArray();
        unset($host_experience_array['updated_at']);
        unset($host_experience_array['timezone_details']);
        $host_experience_array['category'] = @$host_experience_array['category'] ? $host_experience_array['category'] : '';
        $host_experience_array['secondary_category'] = @$host_experience_array['secondary_category'] ? $host_experience_array['secondary_category'] : '';
        $host_experience_array['number_of_guests'] = @$host_experience_array['number_of_guests'] ? $host_experience_array['number_of_guests'] : '';
        $host_experience_array['preparation_hours'] = @$host_experience_array['preparation_hours'] ? $host_experience_array['preparation_hours'] : '';
        $host_experience_array['cutoff_time'] = @$host_experience_array['cutoff_time'] ? $host_experience_array['cutoff_time'] : 1;
        $host_experience_array['host_experience_location']['country'] = @$host_experience_array['host_experience_location']['country'] ? $host_experience_array['host_experience_location']['country'] : '';
        $host_experience_array['guest_requirements']['minimum_age'] = @$host_experience_array['guest_requirements']['minimum_age'] ? $host_experience_array['guest_requirements']['minimum_age'] : '';

        return $host_experience_array;
    }

    /**
    * Edit Calendar Content Ajax
    * 
    * @param Illuminate\Http\Request $request
    * @return Json $return
    */
    public function refresh_calendar(Request $request)
    {        
        $host_experience_id = $request->host_experience_id;
        $host_experience    = $data['host_experience'] = HostExperiences::where('id', $host_experience_id)->with(['host_experience_location', 'guest_requirements'])->first();
        $return             = array();

        if(!$host_experience)
        {
            $return['status'] = 503;
            $return['location'] =  url('host/experiences');
        }
        elseif(!$host_experience->is_reviewed)
        {
            $return['status'] = 503;
            $return['location'] =  url('host/manage_experience/'.$host_experience_id);
        }
        else
        {
            $data = $this->get_calendar_data($host_experience_id, $request->year, $request->month);
            $data['host_experience'] = $host_experience;

            $return['status']   = 200;
            $return['content']  = view('host_experiences.manage_experience.calendar', $data)->render();
        }
        return json_encode($return);
    }

    /**
    * Update Host Experience Calendar Data Ajax
    * 
    * @param Illuminate\Http\Request $request
    * @return Json $return
    */
    public function update_calendar(Request $request)
    {        
        $host_experience_id = $request->host_experience_id;
        $host_experience    = $data['host_experience'] = HostExperiences::where('id', $host_experience_id)->with(['host_experience_location', 'guest_requirements'])->first();
        $return             = array();

        if(!$host_experience)
        {
            $return['status'] = 503;
            $return['location'] =  url('host/experiences');
        }
        elseif(!$host_experience->is_reviewed)
        {
            $return['status'] = 503;
            $return['location'] =  url('host/manage_experience/'.$host_experience_id);
        }
        else
        {
            $start_date = date('Y-m-d', strtotime($request->start_date));
            $start_date = strtotime($start_date);
            
            $end_date   = date('Y-m-d', strtotime($request->end_date));
            $end_date   = strtotime($end_date);
            if($request->price && $request->price-0 > 0){
                for ($i=$start_date; $i<=$end_date; $i+=86400) 
                {
                    $date = date("Y-m-d", $i);

                    $data = [ 
                                'host_experience_id' => $host_experience_id,
                                'price'   => ($request->price) ? $request->price : '0',
                                'status'  => "$request->status",
                            ];
                    HostExperienceCalendar::updateOrCreate(['host_experience_id' => $host_experience_id, 'date' => $date], $data);
                }
            }
            $return['status'] = 200;
        }
        return json_encode($return);        
    }

    /**
    * Get Calendar Html
    * 
    * @param Int $host_experience_id Host Experience Id
    * @param Int $year Calendar Year
    * @param Int $month Calendar Month
    * @param String $type large/small
    * @return Html $calendar_view
    */
    public function get_calendar_view($host_experience_id, $year = '', $month = '', $type = 'large')
    {
        $calendar_data = $this->get_calendar_data($host_experience_id, $year, $month);
        if($type == 'small')
        {
            $calendar_view = view('host_experiences.manage_experience.small_calendar', $calendar_data)->render();
        }
        else
        {
            $calendar_view = view('host_experiences.manage_experience.calendar', $calendar_data)->render();
        }

        return $calendar_view;
    }

    /**
    * Get the Host Experience Calendar Data
    * 
    * @param Int $host_experience_id Host Experience Id
    * @param Int $year Calendar Year
    * @param Int $month Calendar Month
    * @return Array $data
    */
    public function get_calendar_data($host_experience_id, $year = '', $month = '')
    {
        $host_experience = HostExperiences::where('id', $host_experience_id)->first();

        $this_start_day = 'monday';
        if ($year == '')
        {
            $year  = date('Y');
        }
        if ($month == '')
        {
            $month = date('m');
        }
        $calendar_data = array();

        $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $start_days = array('sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6);
        $start_day  = ( ! isset($start_days[$this_start_day])) ? 0 : $start_days[$this_start_day];
        
        $today_time = mktime(12, 0, 0, $month, 1, $year);
        $today_date = getdate($today_time);
        $day        = $start_day + 1 - $today_date["wday"];

        $prev_time  = mktime(12, 0, 0, $month-1, 1, $year);
        $next_time  = mktime(12, 0, 0, $month+1, 1, $year);
        
        $last_time  = mktime(12, 0, 0, $month, $total_days, $year);
        $last_date  = getdate($last_time);
        $total_dates= $total_days + ($last_date["wday"] != ($start_day-1) ? ( 6 + $start_day - $last_date["wday"] ) : 0);

        $current_date= date('Y-m-d');
        $current_time= time();
       
        if ($day > 1)
        {
            $day -= 7;
        }

        $k = 0;
        while($day <= $total_dates)
        {
            $this_time = mktime(12, 0, 0, $month, $day, $year);
            $this_date = date('Y-m-d', $this_time);
            $calendar_data[$k]['date'] = $this_date;
            $calendar_data[$k]['date_d'] = date('d', $this_time);
            $calendar_data[$k]['status'] = '';
            if(date('Ymd', $this_time) < date('Ymd',$current_time))
            {
                $calendar_data[$k]['status'] .= 'tile-previous';
            }
            elseif(date('Ymd', $this_time) == date('Ymd',$current_time))
            {
                $calendar_data[$k]['status'] .= 'today';   
            }

            $date_data = $host_experience->get_date_status_price($this_date);
            $calendar_data[$k]['status'] .= @$date_data['status'] != 'Available' ? ' status-b' : '';
            $calendar_data[$k]['status'] .= @$date_data['is_reserved'] ? ' tile-previous' : '';
            $calendar_data[$k]['spots_left']  = @$date_data['spots_left'];
            $calendar_data[$k]['is_reserved']  = @$date_data['is_reserved'];
            $calendar_data[$k]['price']  = @$date_data['price'];
            $calendar_data[$k]['today']  = $this_date == $current_date;

            $day++;
            $k++;
        }

        $data['calendar_data'] = $calendar_data;
        $data['current_time']  = $today_time;
        $data['prev_month']    = date('m', $prev_time);
        $data['prev_year']     = date('Y', $prev_time);
        $data['next_month']    = date('m', $next_time);
        $data['next_year']     = date('Y', $next_time);
        $data['year_months']   = $this->year_month();

        return $data;
    }

    /**
    * Get Months for the calendar dropdown
    * 
    * @return Array $year_month
    */
    public function year_month()
    {
        $year_month = array();

        $this_time = mktime(0, 0, 0, date('m'), 1, date('Y'));
        for($i=-2;$i<35;$i++)
        {
          $time               = strtotime("+$i months", $this_time);
          $value              = date('Y-m', $time);
          $label              = trans('messages.lys.'.date('F', $time)).' '.date('Y', $time);
          $year_month[$value] = $label; 
        }
        return $year_month;
    }

    /**
    * Experience Details page view 
    * 
    * @param Illuminate\Http\Request $request
    * @return Html Experiece details page view
    */
    public function experience_detail(Request $request)
    {
        $host_experience_id = $request->host_experience_id;
        /*$host_experience    = $data['result'] =$data['host_experience'] = HostExperiences::where('id', $host_experience_id)->approved()->listed()->first();*/
        $host_experience    = $data['result'] =$data['host_experience'] = HostExperiences::where('id', $host_experience_id)->first();

        if(!$host_experience) {           
            abort(404);
        }
        elseif($host_experience->user_id  == @Auth::user()->id && !$host_experience->isCompleted) {
            return redirect('host/manage_experience/'.$host_experience->id);
        }
        elseif($host_experience->user_id  != @Auth::user()->id && $host_experience->admin_status != 'Approved'){
            abort(404);
        }
        else{}
        $data['is_wishlist']      = SavedWishlists::where('user_id',@Auth::user()->id)->where('room_id',$host_experience_id)->where('list_type','Experiences')->count();
        $data['similar_items'] = HostExperiences::listed()->approved()->where('id', '!=', $host_experience->id)->where('city', $host_experience->city)->get();
        return view('host_experiences.experience_details', $data);

    }

    /**
    * Experience Get Available Dates Ajax
    * 
    * @param Illuminate\Http\Request $request
    * @return Array [status, available_dates]
    */
    public function get_available_dates(Request $request)
    {
        $host_experience_id = $request->host_experience_id;
        $host_experience    = HostExperiences::where('id', $host_experience_id)->first();
        $status  = 200;
        if(!$host_experience)
        {
            $status  = 503;
            $location = url('host/experiences');
            return compact("status", "location");
        }

        $page = $request->page ? $request->page : 1;
        $months = ($page * 1);
        if($months > 5)
        {
            $available_dates = array();
        }
        else
        {
            $now = time();
            $start_time  = strtotime('+'.($months-1).' months', $now);
            $end_time = strtotime('+'.$months.' months', $now);

            $sStartDate   = gmdate("Y-m-d", $start_time);  
            $sEndDate     = gmdate("Y-m-d", $end_time);  

            $available_dates = array();
            
            $sCurrentDate = $sStartDate;  
           
            while($sCurrentDate < $sEndDate)
            {
                $availablity = $host_experience->get_date_availability_details($sCurrentDate, true);
                if($availablity['is_available_booking'])
                {
                    $available_dates[] = $availablity;
                }
                $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  
            }
        }
        
        return compact("status", "available_dates");
    }

    /**
    * Experience Get Reviews Ajax
    * 
    * @param Illuminate\Http\Request $request
    * @return Array $reviews
    */
    public function get_all_reviews(Request $request)
    {
        $host_experience_id = $request->host_experience_id;
        $host_experience    = HostExperiences::where('id', $host_experience_id)->first();
        $page = $request->page ? $request->page : 1;
        $host_id=$host_experience->user_id;
        $reviews= Reviews::with(['users_from' => function($query){
                                $query->with('profile_picture');
                            }])->where('room_id',$host_experience_id)->where('user_to',$host_id);
        $reviews=$reviews->paginate(10)->toJson();
        return $reviews;
    }

    /**
    * To set the payment data in session based on choosed date
    * 
    * @param Illuminate\Http\Request $request
    * @return Array [status, scheduled_id]
    */
    public function choose_date(Request $request)
    {
        $host_experience_id = $request->host_experience_id;
        $host_experience    = HostExperiences::listed()->where('id', $host_experience_id)->first();
        $status  = 200;

        if(!$host_experience)
        {
            $status  = 503;
            $location = url('host/experiences');
            return compact("status", "location");
        }

        $date = $request->date;
        $availablity = $host_experience->get_date_availability_details($date, true);
        if(!@$availablity['is_available_booking'])
        {
            $status  = 503;
            $location = url('experiences/'.$host_experience->id);
            return compact("status", "location");   
        }
        $availablity['number_of_guests'] = 1;
        $availablity['host_experience_id'] = $host_experience_id;

        $scheduled_id = time();
        Session::put('experience_payment.'.$scheduled_id, $availablity);
        if(!@Auth::user())
            Session::put('url.intended', url('experiences/'.$host_experience_id.'/book/guest-requirements?scheduled_id='.$scheduled_id)); 

        return compact("status", "scheduled_id");
    }

    /**
    * To save the contact host message Ajax
    * 
    * @param Illuminate\Http\Request $request
    * @return Array [status, location]
    */
    public function contact_host(Request $request)
    {
        $host_experience_id = $request->host_experience_id;
        $host_experience    = HostExperiences::listed()->where('id', $host_experience_id)->first();
        $status  = 200;

        if(!$host_experience)
        {
            $status  = 503;
            $location = url('host/experiences');
            return compact("status", "location");
        }
        if(!Auth::user() || (@Auth::user()->id == $host_experience->user_id))
        {
            $status  = 503;
            $location = url('host/experiences');
            return compact("status", "location");
        }
        $contact_message = $this->helper->phone_email_remove($request->message);
        $mobile_web_auth_user_id = Auth::user()->id;

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

        $this->helper->flash_message('success', trans('messages.rooms.contact_request_has_sent', ['first_name' => @$host_experience->user->first_name]));
            
        $status                  = 503;
        $location                = url('experiences/'.$host_experience->id);
        return compact("status", "location");
    }

}
