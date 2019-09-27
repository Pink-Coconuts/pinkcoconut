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

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Controller;
use App\DataTables\HostExperiencesDataTable;
use App\Models\HostExperiences;
use App\Models\HostExperienceCities;
use App\Models\HostExperienceCategories;
use App\Models\HostExperienceProvideItems;
use App\Models\HostExperiencePhotos;
use App\Models\HostExperienceProvides;
use App\Models\HostExperiencePackingLists;
use App\Models\HostExperienceGuestRequirements;
use App\Models\HostExperienceLocation;
use App\Models\Language;
use App\Models\Country;
use App\Models\User;
use App\Models\Reservation;
use App\Http\Start\Helpers;
use Validator;

class HostExperiencesController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers
    protected $main_title;
    protected $base_url;
    protected $base_view_path;
    protected $view_data;

    public function __construct()
    {
        $this->helper = new Helpers;
        $this->view_data['main_title'] = $this->main_title = 'Host Experience';
        $this->view_data['base_url'] = $this->base_url = url(ADMIN_URL.'/host_experiences');
        $this->view_data['base_view_path'] = $this->base_view_path = 'admin.host_experiences.manage.';
        $this->view_data['host_experience_menu'] = 'host_experiences';
    }

    /**
     * Load Datatable for Host Experiences
     *
     * @param array $dataTable  Instance of HostExperiences
     * @return datatable
     */
    public function index(HostExperiencesDataTable $dataTable)
    {
        return $dataTable->render($this->base_view_path.'view', $this->view_data);
    }

    /**
     * Add a New Host Experiences
     *
     * @param array $request  Input values
     * @return redirect     to Host Experiences view
     */
    public function add(Request $request)
    {
        if($request->method() == 'GET')
        {
            $host_experience = new HostExperiences;
            $this->view_data['cities'] = HostExperienceCities::active()->get();
            $this->view_data['categories'] = HostExperienceCategories::active()->get();
            $this->view_data['provide_items'] = HostExperienceProvideItems::active()->get();
            $this->view_data['languages'] = Language::translatable()->pluck('name', 'value');
            $this->view_data['countries'] = Country::all()->pluck('long_name','short_name');
            $this->view_data['times_array'] = $host_experience->times_array;
            $this->view_data['minimum_age_array'] = $host_experience->minimum_age_array;
            $this->view_data['group_size_array'] = $host_experience->group_size_array;
            $this->view_data['preparation_times_array'] = $host_experience->preparation_times_array;
            $this->view_data['cutoff_times_array'] = $host_experience->cutoff_times_array;
            $this->view_data['users_array'] = User::whereStatus('Active')->pluck('first_name', 'id');
            
            return view($this->base_view_path.'add', $this->view_data);
        }
        elseif($request->method() == 'POST')
        {

            $city_details = HostExperienceCities::where('id', $request->city)->first();
            if(!$city_details)
            {
                return back();
            }
            $host_experience = new HostExperiences;
            $host_experience->user_id = $request->user_id;
            $host_experience->city = $city_details->id;
            $host_experience->timezone = $city_details->timezone;
            $host_experience->currency_code = $city_details->currency_code;
            $host_experience->save();

            $host_experience_guest_requirements = new HostExperienceGuestRequirements;
            $host_experience_guest_requirements->host_experience_id = $host_experience->id;
            $host_experience_guest_requirements->save();

            $host_experience_location = new HostExperienceLocation;
            $host_experience_location->host_experience_id = $host_experience->id;
            $host_experience_location->save();

            $host_experience->hosting_standards_reviewed = 'Yes';
            $host_experience->experience_standards_reviewed = 'Yes';
            $host_experience->language = $request->language;
            $host_experience->category = $request->category;
            $host_experience->secondary_category = $request->secondary_category ? $request->secondary_category : NULL;
            $host_experience->title = $request->title;
            $host_experience->start_time = $request->start_time;
            $host_experience->end_time = $request->end_time;
            $host_experience->tagline = $request->tagline;
            $host_experience->what_will_do = $request->what_will_do;
            $host_experience->where_will_be = $request->where_will_be;
            $host_experience->notes = $request->notes;
            $host_experience->about_you = $request->about_you;
            $host_experience->number_of_guests = $request->number_of_guests;
            $host_experience->price_per_guest = $request->price_per_guest;
            $host_experience->is_free_under_2 = $request->is_free_under_2 == 'Yes' ? 'Yes' : 'No' ;
            $host_experience->preparation_hours = $request->preparation_hours;
            $host_experience->last_minute_guests = $request->last_minute_guests == 'Yes' ? 'Yes' : 'No' ;
            $host_experience->cutoff_time = $request->cutoff_time;
            $host_experience->quality_standards_reviewed = 'Yes';
            $host_experience->local_laws_reviewed = 'Yes';
            $host_experience->terms_service_reviewed = 'Yes';
            $host_experience->need_notes = $request->need_notes == 'No' ? 'No' : 'Yes';
            $host_experience->need_provides = $request->need_provides == 'No' ? 'No' : 'Yes';
            $host_experience->need_packing_lists = $request->need_packing_lists == 'No' ? 'No' : 'Yes';
            $host_experience->save();

            $host_experience_guest_requirements->includes_alcohol = $request->includes_alcohol == 'Yes' ? 'Yes' : 'No';
            $host_experience_guest_requirements->minimum_age = $request->minimum_age;
            $host_experience_guest_requirements->allowed_under_2 = $request->allowed_under_2 == 'Yes' ? 'Yes' : 'No' ;
            $host_experience_guest_requirements->special_certifications = $request->special_certifications;
            $host_experience_guest_requirements->additional_requirements = $request->additional_requirements;
            $host_experience_guest_requirements->save();

            $request_location = $request->location;
            $host_experience_location->location_name = $request_location['location_name'];
            $host_experience_location->country = $request_location['country'];
            $host_experience_location->address_line_1 = $request_location['address_line_1'];
            $host_experience_location->address_line_2 = $request_location['address_line_2'];
            $host_experience_location->city = $request_location['city'];
            $host_experience_location->state = $request_location['state'];
            $host_experience_location->postal_code = $request_location['postal_code'];
            $host_experience_location->latitude = $request_location['latitude'];
            $host_experience_location->longitude = $request_location['longitude'];
            $host_experience_location->directions = $request_location['directions'];
            $host_experience_location->save();

            $request_provides = $request->provides;
            if($request_provides)
            {
                foreach($request_provides as $provide)
                {
                    $host_experience_provides = new HostExperienceProvides;
                    $host_experience_provides->host_experience_id = $host_experience->id;
                    $host_experience_provides->host_experience_provide_item_id = @$provide['host_experience_provide_item_id'];
                    $host_experience_provides->name = @$provide['name'];
                    $host_experience_provides->additional_details = @$provide['additional_details'];
                    $host_experience_provides->save();
                }
            }

            $request_packing_lists = $request->packing_lists;
            if($request_packing_lists)
            {
                foreach($request_packing_lists as $packing_list)
                {
                    $host_experience_packing_lists = new HostExperiencePackingLists;
                    $host_experience_packing_lists->host_experience_id = $host_experience->id;
                    $host_experience_packing_lists->item = @$packing_list['item'];
                    $host_experience_packing_lists->save();
                }
            }

            $request_photos = $request->file('photos');
            if($request_photos)
            {
                foreach($request_photos as $k => $photo)
                {
                    $result = $this->upload_file($photo, 'images/host_experiences/'.$host_experience->id.'/');
                    if($result['error'] != '')
                    {
                        $this->helper->flash_message('danger', $result['error']);
                        return redirect($this->base_url);
                    }
                    else
                    {
                        $host_experience_photo = new HostExperiencePhotos;
                        $host_experience_photo->host_experience_id = $host_experience->id;
                        $host_experience_photo->name = $result['filename'];
                        $host_experience_photo->save();
                    }   
                }
            }

            $host_experience->status = 'Listed';
            $host_experience->save();

            $this->helper->flash_message('success', 'New '.$this->main_title.' Added Successfully');
            return redirect($this->base_url);
        }
    }

    /**
     * Update Host Experiences Details
     *
     * @param array $request    Input values
     * @return redirect     to Host Experiences View
     */
    public function update(Request $request)
    {
        if($request->method() == 'GET')
        {
            $host_experience = $this->view_data['host_experience'] = HostExperiences::find($request->id);
            $this->view_data['id'] = $request->id;

            if(!$host_experience)
            {
                return redirect($this->base_url);
            }
            $this->view_data['cities'] = HostExperienceCities::active()->get();
            $this->view_data['categories'] = HostExperienceCategories::active()->get();
            $this->view_data['provide_items'] = HostExperienceProvideItems::active()->get();
            $this->view_data['languages'] = Language::translatable()->pluck('name', 'value');
            $this->view_data['countries'] = Country::all()->pluck('long_name','short_name');
            $this->view_data['times_array'] = $host_experience->times_array;
            $this->view_data['minimum_age_array'] = $host_experience->minimum_age_array;
            $this->view_data['group_size_array'] = $host_experience->group_size_array;
            $this->view_data['preparation_times_array'] = $host_experience->preparation_times_array;
            $this->view_data['cutoff_times_array'] = $host_experience->cutoff_times_array;
            $this->view_data['users_array'] = User::whereStatus('Active')->pluck('first_name', 'id');
            $this->view_data['tab'] = ($request->tab-1 > 0)  ? ($request->tab -1) : 0;

            $this->get_calendar_data($host_experience->id);

            return view($this->base_view_path.'edit', $this->view_data);
        }
        elseif($request->method() == 'POST')
        {
            $host_experience = HostExperiences::find($request->id);
            if(!$host_experience)
            {
                return redirect($this->base_url);
            }

            $step = $request->current_step_id;
            if($step == '2')
            {
                $host_experience->language = $request->language;
            }
            elseif($step == '3')
            {
                $host_experience->category = $request->category;
                $host_experience->secondary_category = $request->secondary_category ? $request->secondary_category : NULL;
            }
            elseif($step == '4')
            {
                $host_experience->title = $request->title;
            }
            elseif($step == '5')
            {
                $host_experience->start_time = $request->start_time;
                $host_experience->end_time = $request->end_time;
            }
            elseif($step == '6')
            {
                $host_experience->tagline = $request->tagline;
            }
            elseif($step == '7')
            {
                $request_photos = $request->file('photos');
                if($request_photos)
                {
                    foreach($request_photos as $k => $photo)
                    {
                        $result = $this->upload_file($photo, 'images/host_experiences/'.$host_experience->id);
                        if($result['error'] != '')
                        {
                            $this->helper->flash_message('danger', $result['error']);
                            return redirect($this->base_url);
                        }
                        else
                        {
                            $host_experience_photo = new HostExperiencePhotos;
                            $host_experience_photo->host_experience_id = $host_experience->id;
                            $host_experience_photo->name = $result['filename'];
                            $host_experience_photo->save();
                        }   
                    }
                }
            }
            elseif($step == '8')
            {
                $host_experience->what_will_do = $request->what_will_do;
            }
            elseif($step == '9')
            {
                $host_experience->where_will_be = $request->where_will_be;
            }
            elseif($step == '10')
            {
                $request_location = $request->location;
                $host_experience_location = HostExperienceLocation::where('host_experience_id', $host_experience->id)->first();
                $host_experience_location->location_name = $request_location['location_name'];
                $host_experience_location->country = $request_location['country'];
                $host_experience_location->address_line_1 = $request_location['address_line_1'];
                $host_experience_location->address_line_2 = $request_location['address_line_2'];
                $host_experience_location->city = $request_location['city'];
                $host_experience_location->state = $request_location['state'];
                $host_experience_location->postal_code = $request_location['postal_code'];
                $host_experience_location->latitude = $request_location['latitude'];
                $host_experience_location->longitude = $request_location['longitude'];
                $host_experience_location->directions = $request_location['directions'];
                $host_experience_location->save();
            }
            elseif($step == '11')
            {
                $request_provides = $request->provides;
                if($request_provides)
                {
                    foreach($request_provides as $provide)
                    {
                        $host_experience_provides = null;
                        if(@$provide['id'] > 0)
                        {
                            $host_experience_provides = HostExperienceProvides::find(@$provide['id']);
                        }
                        if(!$host_experience_provides)
                        {
                            $host_experience_provides = new HostExperienceProvides;
                        }
                        $host_experience_provides->host_experience_id = $host_experience->id;
                        $host_experience_provides->host_experience_provide_item_id = @$provide['host_experience_provide_item_id'];
                        $host_experience_provides->name = @$provide['name'];
                        $host_experience_provides->additional_details = @$provide['additional_details'];
                        $host_experience_provides->save();
                    }
                }
                $host_experience->need_provides = $request->need_provides == 'No' ? 'No' : 'Yes';
            }
            elseif($step == '12')
            {
                $host_experience->notes = $request->notes;
                $host_experience->need_notes = $request->need_notes == 'No' ? 'No' : 'Yes';
            }
            elseif($step == '13')
            {
                $host_experience->about_you = $request->about_you;
            }
            elseif($step == '14')
            {
                $host_experience_guest_requirements = HostExperienceGuestRequirements::where('host_experience_id', $host_experience->id)->first();
                $host_experience_guest_requirements->includes_alcohol = $request->includes_alcohol == 'Yes' ? 'Yes' : 'No';
                $host_experience_guest_requirements->minimum_age = $request->minimum_age;
                $host_experience_guest_requirements->allowed_under_2 = $request->allowed_under_2 == 'Yes' ? 'Yes' : 'No' ;
                $host_experience_guest_requirements->special_certifications = $request->special_certifications;
                $host_experience_guest_requirements->additional_requirements = $request->additional_requirements;
                $host_experience_guest_requirements->save();
            }
            elseif($step == '15')
            {
                $host_experience->number_of_guests = $request->number_of_guests;
            }
            elseif($step == '16')
            {
                $host_experience->price_per_guest = $request->price_per_guest;
                $host_experience->is_free_under_2 = $request->is_free_under_2 == 'Yes' ? 'Yes' : 'No' ;
            }
            elseif($step == '17')
            {
                $host_experience->preparation_hours = $request->preparation_hours;
                $host_experience->last_minute_guests = $request->last_minute_guests == 'Yes' ? 'Yes' : 'No' ;
                $host_experience->cutoff_time = $request->cutoff_time;
            }
            elseif($step == '18')
            {
                $request_packing_lists = $request->packing_lists;
                if($request_packing_lists)
                {
                    foreach($request_packing_lists as $packing_list)
                    {
                        $host_experience_packing_lists = null;
                        if(@$packing_list['id'] > 0)
                        {
                            $host_experience_packing_lists = HostExperiencePackingLists::find(@$packing_list['id']);
                        }
                        if(!$host_experience_packing_lists)
                        {
                            $host_experience_packing_lists = new HostExperiencePackingLists;
                        }
                        
                        $host_experience_packing_lists->host_experience_id = $host_experience->id;
                        $host_experience_packing_lists->item = @$packing_list['item'];
                        $host_experience_packing_lists->save();
                    }
                }
                $host_experience->need_packing_lists = $request->need_packing_lists == 'No' ? 'No' : 'Yes';
            }

            $host_experience->save();

            $this->helper->flash_message('success', $this->main_title.' Updated Successfully');

            $redirect = $this->base_url;
            if($request->submit == 'submit')
            {
                $redirect = $this->base_url.'/edit/'.$host_experience->id.'?tab='.($request->current_step+1);
            }
            return redirect($redirect); 
        }
    }

    /**
     * Delete Host Experiences
     *
     * @param array $request    Input values
     * @return redirect     to Host Experiences View
     */
    public function delete(Request $request)
    {
        $already_used_count = $this->get_already_used_count($request->id);
        if($already_used_count > 0)
        {
            $this->helper->flash_message('error', 'This host experience has some reservations. So, you cannot delete this host experience.');
            return redirect($this->base_url);
        }
        
        $host_experience = HostExperiences::where('id', $request->id)->first();
        if($host_experience)
        {
            $host_experience->force_delete();
        }

        $this->helper->flash_message('success', $this->main_title.' Deleted Successfully');
        return redirect($this->base_url);
    }

    public function get_already_used_count($id)
    {
        $already_used_count = Reservation::where('room_id', $id)->where('list_type', 'Experiences')->count();
        return $already_used_count;
    }

    public function photo_delete (Request $request)
    {
        HostExperiencePhotos::where('id', $request->id)->delete();
    }
    public function provide_item_delete (Request $request)
    {
        HostExperienceProvides::where('id', $request->id)->delete();
    }
    public function packing_list_delete (Request $request)
    {
        HostExperiencePackingLists::where('id', $request->id)->delete();
    }

    public function upload_file($file, $upload_path)
    {

        $upload_path_dir = public_path().'/'.$upload_path;

        if(!file_exists($upload_path_dir))
        {
            mkdir($upload_path_dir, 0777, true);
        }

        $return = array('filename' => '', 'error' => '');

        if($file) {
            
            if(UPLOAD_DRIVER=='cloudinary')
            {
                $c=$this->helper->cloud_upload($file);
                if($c['status']!="error")
                {
                    $filename=$c['message']['public_id'];  
                    $return['filename'] = $filename;  
                }
                else
                {
                    $return['error'] = $c['message'];
                }
            }
            else
            {
                $extension =   $file->getClientOriginalExtension();
                $fname  =   str_slug($file->getClientOriginalName()).time();
                $filename = $fname . '.' . $extension;
                $success = $file->move($upload_path_dir, $filename);

                $return['filename'] = $filename;
                
                if(!$success)
                {
                    $return['error'] = 'Could not upload Image';
                }
                else
                {
                    $this->helper->compress_image($upload_path_dir."/".$filename, $upload_path_dir."/".$filename, 80, 853, 1280);

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
        }
        else
        {
            $return['error'] = 'Could not upload Image';
        }

        return $return;
    }

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
                $calendar_data[$k]['status'] .= ' tile-previous ';
            }

            $date_data = $host_experience->get_date_status_price($this_date);
            $calendar_data[$k]['status'] .= @$date_data['status'] != 'Available' ? ' status-b ' : '';
            $calendar_data[$k]['price']  = @$date_data['price'];
            $calendar_data[$k]['spots_left']  = @$date_data['spots_left'];
            $calendar_data[$k]['is_reserved']  = @$date_data['is_reserved'];
            $calendar_data[$k]['today']  = $this_date == $current_date;

            $day++;
            $k++;
        }

        $this->view_data['calendar_data'] = $calendar_data;
        $this->view_data['current_time']  = $today_time;
        $this->view_data['prev_month']    = date('m', $prev_time);
        $this->view_data['prev_year']     = date('Y', $prev_time);
        $this->view_data['next_month']    = date('m', $next_time);
        $this->view_data['next_year']     = date('Y', $next_time);
        $this->view_data['year_months']   = $this->year_month();
    }

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
    
    public function refresh_calendar(Request $request)
    {        
        $host_experience_id = $request->id;
        $host_experience    = $this->view_data['host_experience'] = HostExperiences::where('id', $host_experience_id)->with(['host_experience_location', 'guest_requirements'])->first();
        $return             = array();

        if(!$host_experience)
        {
            $return['status'] = 503;
            $return['location'] =  $this->base_url;
        }
        else
        {
            $this->get_calendar_data($host_experience_id, $request->year, $request->month);
            $this->view_data['host_experience'] = $host_experience;

            $return['status']   = 200;
            $return['content']  = view($this->base_view_path.'calendar', $this->view_data)->render();
        }
        return json_encode($return);
    }
    public function update_hostexperience_status(Request $request,EmailController $email_controller)
    {
        $host_experience = HostExperiences::where('id',$request->id)->first();
        if(!$host_experience)
        {
            return redirect($this->base_url);
        }
        if($host_experience->status == NULL && ($request->admin_status == 'Approved' || $request->admin_status == 'Rejected'))
        {
            $this->helper->flash_message('error', 'Cannot change the experience status now, because the experience is not yet completed');
            return redirect($this->base_url);   
        }

        if($host_experience->user->status != 'Active' && $request->admin_status == 'Approved')
        {
            $this->helper->flash_message('error', 'Cannot approve the experience now, because the host is not in "Active" status');
            return redirect($this->base_url);   
        }

        $data['admin_status']=$request->admin_status;
        HostExperiences::where('id',$request->id)->update($data);
        if($request->admin_status=="Approved")
        {
            $email_controller->review_approved($request->id);
        }
        elseif($request->admin_status=="Rejected")
        {
            $email_controller->review_rejected($request->id);
        }
        $this->helper->flash_message('success', 'Status Updated Successfully');
        return redirect($this->base_url);
    }
    public function feature(Request $request)
    {
        $host_experience = HostExperiences::find($request->id);
        $prev = $host_experience->is_featured;
        $admin_status = HostExperiences::find($request->id)->admin_status;

        if($host_experience->user->status != 'Active' && $prev == 'No')
        {
            $this->helper->flash_message('error', 'Cannot make featured, because the host is not in "Active" status');
            return redirect($this->base_url);   
        }

        if($admin_status=="Approved")
        {
            if($prev == 'Yes')
                HostExperiences::where('id',$request->id)->update(['is_featured'=>'No']);
            else
                HostExperiences::where('id',$request->id)->update(['is_featured'=>'Yes']);

            $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
        }
        else
        {
            $this->helper->flash_message('danger', 'Could not featured the experience. because admin not approved'); // Call flash message function
        }
        return redirect($this->base_url);            

    }
}
