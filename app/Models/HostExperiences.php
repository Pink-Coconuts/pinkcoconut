<?php

/**
 * HostExperiences Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    HostExperiences
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use App\Http\Helper\PaymentHelper;
use App\Models\HostExperienceCalendar;
use Auth;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;
use Session;

class HostExperiences extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'host_experiences';

    public $timestamps = true;

    protected $appends = ['is_reviewed', 'provides_count', 'packing_lists_count', 'changes_saved', 'photo_name', 'session_price', 'link', 'reviews_count', 'overall_star_rating', 'host_name', 'minimum_price', 'reviews_count_lang','photo_resize_name','category_name','city_name'];

    // Filter for Auth user experiences
    public function scopeAuthUser($query) {
        $query = $query->where('user_id', @\Auth::user()->id);
        return $query;
    }
    public function scopeApproved($query) {
        $query = $query->where('admin_status', 'Approved')->whereHas('user', function ($query) {
            $query->where('status', 'Active');
        });
    }
    // Filter for Listed experiences
    public function scopeListed($query) {
        $query = $query->where('status', 'Listed');
        return $query;
    }

    public function scopeFeatured($query) {
        return $query->where('is_featured', 'Yes');
    }

    public function scopeHomePage($query) {
        return $query->featured()->listed()->approved();
    }

    public function scopeHomePageFeatured($query) {
        return $query->listed()->approved();
    }

    // Filter for Listed and approved experiences
    public function scopeProfilePage($query) {
        return $query->listed()->approved();
    }

    // Filter for date based available experiences
    public function scopeDaysAvailable($query, $days, $number_of_guests) {
        if (count($days) == 0) {
            return $query;
        }
        $dateTime = new DateTime(@$days[0]);
        $currentTime = new DateTime();
        $diff = $currentTime->diff($dateTime);
        $hour_diff = $diff->h + ($diff->days * 24);

        $query = $query->with('host_experience_calendar');
        $query = $query->whereHas('host_experience_calendar', function ($subQuery) use ($days, $number_of_guests) {
            $subQuery->whereIn('date', $days);
            $subQuery->where('status', 'Not available');
            $subQuery->where(function ($inner_query) use ($number_of_guests) {
                $inner_query->where('source', 'Calendar');
                $inner_query->orWhereRaw('`host_experiences`.`number_of_guests` < `host_experience_calendar`.`spots_booked` + ' . $number_of_guests);
            });
        }, '=', 0);

        $query = $query->where(function ($subQuery) use ($hour_diff) {
            $subQuery->where('preparation_hours', '<=', $hour_diff);
        });

        return $query;
    }

    // Join with users table
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    // Get host name from users table
    public function getHostNameAttribute() {
        return User::find($this->attributes['user_id'])->first_name;
    }
    // Join with host_experience_cities table
    public function city_details() {
        return $this->belongsTo('App\Models\HostExperienceCities', 'city', 'id');
    }
    // Join with language table
    public function language_details() {
        return $this->belongsTo('App\Models\Language', 'language', 'value');
    }
    // Join with currency table
    public function currency() {
        return $this->belongsTo('App\Models\Currency', 'currency_code', 'code');
    }

    // Join with host_experience_categories table
    public function category_details() {
        return $this->belongsTo('App\Models\HostExperienceCategories', 'category', 'id');
    }

    // Join with host_experience_categories table
    public function secondary_category_details() {
        return $this->belongsTo('App\Models\HostExperienceCategories', 'secondary_category', 'id');
    }
    // Join with timezone table
    public function timezone_details() {
        return $this->belongsTo('App\Models\Timezone', 'timezone', 'id');
    }

    // Join with host_experience_location table
    public function host_experience_location() {
        return $this->belongsTo('App\Models\HostExperienceLocation', 'id', 'host_experience_id');
    }

    // Join with host_experience_guest_requirements table
    public function guest_requirements() {
        return $this->belongsTo('App\Models\HostExperienceGuestRequirements', 'id', 'host_experience_id');
    }

    //Join with host_experience_photos table
    public function host_experience_photos() {
        return $this->hasMany('App\Models\HostExperiencePhotos', 'host_experience_id', 'id');

    }

    // Join with saved_wishlists table
    public function saved_wishlists() {
        return $this->belongsTo('App\Models\SavedWishlists', 'id', 'room_id');
    }

    // Get rooms featured photo_name URL
    public function getPhotoNameAttribute() {
        $result = HostExperiencePhotos::where('host_experience_id', $this->attributes['id']);

        if ($result->count() == 0) {
            return url('/') . "/images/room_default_no_photos.png";
        } else {
            return $result->first()->image_url;
        }

    }

    // Get rooms featured photo_name URL
    public function getPhotoResizeNameAttribute() {
        $result = HostExperiencePhotos::where('host_experience_id', $this->attributes['id']);

        if ($result->count() == 0) {
            return url('/') . "/images/room_default_no_photos.png";
        } 
        else {
                $url = '';
                $filename = $result->first()->name;
                $photo_src=explode('.',$filename);
            if(count($photo_src)>1)
            {
                $photo_details = pathinfo($result->first()->name); 
                $name = $photo_details['filename'].'_resize.'.$photo_details['extension'];
               
                $url = url('images/host_experiences/'.$result->first()->host_experience_id.'/'.$name);
                 
         }
         else
         {
                $options['secure']=TRUE;
                $options['width']=237;
                $options['height']=160;
                $url =\Cloudder::show($filename,$options);
         }  

        return $url;

    }
}

    //Join with host_experience_provides table
    public function host_experience_provides() {
        return $this->hasMany('App\Models\HostExperienceProvides', 'host_experience_id', 'id');

    }

    //Join with host_experience_packing_lists table
    public function host_experience_packing_lists() {
        return $this->hasMany('App\Models\HostExperiencePackingLists', 'host_experience_id', 'id');

    }

    //Join with host_experience_calendar table
    public function host_experience_calendar() {
        return $this->hasMany('App\Models\HostExperienceCalendar', 'host_experience_id', 'id');

    }

    // To check the host approved hosting and experience standards
    public function getIsReviewedAttribute() {
        $is_reviewed = (@$this->attributes['hosting_standards_reviewed'] == 'Yes' && @$this->attributes['experience_standards_reviewed'] == 'Yes');
        return @$is_reviewed;
    }
    // To get the provide items count
    public function getProvidesCountAttribute() {
        $active_provides_count = $this->host_experience_provides()->where('name', '!=', '')->count();
        return $active_provides_count;
    }
    // To get the packing lists count
    public function getPackingListsCountAttribute() {
        $active_packing_lists_count = $this->host_experience_packing_lists()->where('item', '!=', '')->count();
        return $active_packing_lists_count;
    }
    // To check if all the steps are completed
    public function getIsCompletedAttribute() {
        $steps = $this->getStepsAttribute();
        $not_completed = $steps->search(function ($v, $k) {
            return $v['status'] == 0;
        });

        return !($not_completed !== false);
    }
    // To get the total hours from the start time and end time
    public function getTotalHoursAttribute() {
        $start_time = @$this->attributes['start_time'];
        $end_time = @$this->attributes['end_time'];

        $diff_time = strtotime($end_time) - strtotime($start_time);
        $total_hours = round(($diff_time / 3600), 1);
        return $total_hours;
    }
    // To get the price per guest based on session currency
    public function getSessionPriceAttribute() {
        $session_price = $this->currency_calc('price_per_guest');
        return $session_price;
    }

    // To get all steps for manage an experience and their detials
    public function getStepsAttribute() {
        $this_experience = $this->attributes;
        $location = $this->host_experience_location;
        $guest_requirements = $this->guest_requirements;
        $photos = $this->host_experience_photos;
        $provides = $this->host_experience_provides;
        $packing_lists = $this->host_experience_packing_lists;

        $steps = [];

        $language = ($this_experience['language']) ? 1 : 0;
        $category = ($this_experience['category']) ? 1 : 0;
        $title = ($this_experience['title']) ? 1 : 0;
        $time = ($this_experience['start_time'] && $this_experience['end_time']) ? 1 : 0;
        $tagline = ($this_experience['tagline']) ? 1 : 0;
        $photos = ($photos->count() > 0) ? 1 : 0;
        $what_will_do = ($this_experience['what_will_do']) ? 1 : 0;
        $where_will_be = ($this_experience['where_will_be']) ? 1 : 0;
        $where_will_meet = ($location->location_name && $location->address_line_1 && $location->city && $location->country && $location->latitude && $location->longitude) ? 1 : 0;
        $what_will_provide = ($provides->count() > 0 || $this_experience['need_provides'] == 'No') ? 1 : 0;
        $notes = ($this_experience['notes'] || $this_experience['need_notes'] == 'No') ? 1 : 0;
        $about_you = ($this_experience['about_you']) ? 1 : 0;
        $guest_requirements = ($guest_requirements->minimum_age) ? 1 : 0;
        $group_size = ($this_experience['number_of_guests']) ? 1 : 0;
        $price = ($this_experience['price_per_guest']) ? 1 : 0;
        $preparation_time = ($this_experience['preparation_hours'] && (!$this_experience['last_minute_guests'] || $this_experience['cutoff_time'])) ? 1 : 0;
        $packing_list = ($packing_lists->count() > 0 || $this_experience['need_packing_lists'] == 'No') ? 1 : 0;

        $basics = ($language && $category) ? 1 : 0;
        $experience_detail = ($basics && $title && $time && $tagline && $photos && $what_will_do && $where_will_be && $where_will_meet && $what_will_provide && $notes) ? 1 : 0;
        $finishing_thoughts = ($basics && $experience_detail && $about_you && $guest_requirements && $group_size && $price && $preparation_time && $packing_list);
        $review_submit = ($basics && $experience_detail && $finishing_thoughts && $this_experience['quality_standards_reviewed'] && $this_experience['local_laws_reviewed'] && $this_experience['terms_service_reviewed']) ? 1 : 0;

        $basics_locked = (@$this_experience['hosting_standards_reviewed'] == 'No' || @$this_experience['experience_standards_reviewed'] == 'No');
        $experience_locked = ($basics == 0);
        $finishing_thoughts_locked = ($basics == 0 || $experience_detail == 0);
        $review_submit_locked = ($basics == 0 || $experience_detail == 0 || $finishing_thoughts == 0);

        $steps = collect([
            array(
                'step' => 'basics',
                'name' => trans('experiences.manage.the_basics'),
                'status' => $basics,
                'parent' => '',
                'locked' => $basics_locked,
            ),
            array(
                'step' => 'language',
                'name' => trans('experiences.manage.language'),
                'status' => $language,
                'parent' => 'basics',
            ),
            array(
                'step' => 'category',
                'name' => trans('experiences.manage.category'),
                'status' => $category,
                'parent' => 'basics',
            ),
            array(
                'step' => 'experience_page',
                'name' => trans('experiences.manage.experience_page'),
                'status' => $experience_detail,
                'parent' => '',
                'locked' => $experience_locked,
            ),
            array(
                'step' => 'title',
                'name' => trans('experiences.manage.experience_title'),
                'status' => $title,
                'parent' => 'experience_page',
            ),
            array(
                'step' => 'time',
                'name' => trans('experiences.manage.time'),
                'status' => $time,
                'parent' => 'experience_page',
            ),
            array(
                'step' => 'tagline',
                'name' => trans('experiences.manage.tagline'),
                'status' => $tagline,
                'parent' => 'experience_page',
            ),
            array(
                'step' => 'photos',
                'name' => trans('experiences.manage.photos'),
                'status' => $photos,
                'parent' => 'experience_page',
            ),
            array(
                'step' => 'what_will_do',
                'name' => trans('experiences.manage.what_will_do'),
                'status' => $what_will_do,
                'parent' => 'experience_page',
            ),
            array(
                'step' => 'where_will_be',
                'name' => trans('experiences.manage.where_will_be'),
                'status' => $where_will_be,
                'parent' => 'experience_page',
            ),
            array(
                'step' => 'where_will_meet',
                'name' => trans('experiences.manage.where_will_meet'),
                'status' => $where_will_meet,
                'parent' => 'experience_page',
            ),
            array(
                'step' => 'what_will_provide',
                'name' => trans('experiences.manage.what_will_provide'),
                'status' => $what_will_provide,
                'parent' => 'experience_page',
            ),
            array(
                'step' => 'notes',
                'name' => trans('experiences.manage.notes'),
                'status' => $notes,
                'parent' => 'experience_page',
            ),

            array(
                'step' => 'finishing_thoughts',
                'name' => trans('experiences.manage.finishing_thoughts'),
                'status' => $finishing_thoughts,
                'parent' => '',
                'locked' => $finishing_thoughts_locked,
            ),
            array(
                'step' => 'about_you',
                'name' => trans('experiences.manage.about_you'),
                'status' => $about_you,
                'parent' => 'finishing_thoughts',
            ),
            array(
                'step' => 'guest_requirements',
                'name' => trans('experiences.manage.guest_requirements'),
                'status' => $guest_requirements,
                'parent' => 'finishing_thoughts',
            ),
            array(
                'step' => 'group_size',
                'name' => trans('experiences.manage.group_size'),
                'status' => $group_size,
                'parent' => 'finishing_thoughts',
            ),
            array(
                'step' => 'price',
                'name' => trans('experiences.manage.price'),
                'status' => $price,
                'parent' => 'finishing_thoughts',
            ),
            array(
                'step' => 'preparation_time',
                'name' => trans('experiences.manage.preparation_time'),
                'status' => $preparation_time,
                'parent' => 'finishing_thoughts',
            ),
            array(
                'step' => 'packing_list',
                'name' => trans('experiences.manage.packing_list'),
                'status' => $packing_list,
                'parent' => 'finishing_thoughts',
            ),
        ]);
        if ($this->attributes['status'] == NULL) {
            $steps[] = array(
                'step' => 'review_submit',
                'name' => trans('experiences.manage.review_submit'),
                'status' => $review_submit,
                'parent' => '',
                'locked' => $review_submit_locked,
            );
        } else {
            $calendar_step = array(
                'step' => 'edit_calendar',
                'name' => trans('experiences.manage.edit_calendar'),
                'status' => 1,
                'parent' => 'basics',
            );
            $steps->splice(1, 0, [$calendar_step]);
        }
        $steps = $steps->map(function ($v, $k) {
            $v['step_num'] = $k;
            return $v;
        });
        return $steps;
    }
    // To get the times options for start time and end time fields
    public function getTimesArrayAttribute() {
        $timezone_abbr = '';
        if ($this->timezone_details) {
            $timezone = $this->timezone_details->value;
            $dateTime = new DateTime();
            $dateTime->setTimeZone(new DateTimeZone($timezone));
            $timezone_abbr = $dateTime->format('T');
        }

        $times = array();
        $start_time = '00:00:00';
        $end_time = '23:30:00';
        while (strtotime($start_time) < strtotime($end_time)) {
            $times[$start_time] = date('H:i', strtotime($start_time)) . ' ' . $timezone_abbr;
            $start_time = date('H:i:s', strtotime('+30 minutes', strtotime($start_time)));
        }
        $times[$start_time] = date('H:i', strtotime($start_time)) . ' ' . $timezone_abbr;
        return $times;
    }
    // To get the options minimum age field
    public function getMinimumAgeArrayAttribute() {
        $minimum_age = array();
        for ($i = 18; $i >= 2; $i--) {
            $minimum_age[$i] = $i;
        }
        return $minimum_age;
    }
    // To get the options group size fields
    public function getGroupSizeArrayAttribute() {
        $group_size = array();
        for ($i = 1; $i <= 10; $i++) {
            $group_size[$i] = $i;
        }
        return $group_size;
    }
    // To get the options preparation time field
    public function getPreparationTimesArrayAttribute() {
        $preparation_time = array();
        for ($i = 1; $i < 7; $i++) {
            $preparation_time[($i * 24)] = $i . ' ' . trans_choice('experiences.manage.day_s', $i);
        }
        for ($i = 1; $i < 5; $i++) {
            $preparation_time[($i * 7 * 24)] = $i . ' ' . trans_choice('experiences.manage.week_s', $i);
        }
        return $preparation_time;
    }
    // To get the options cutoff time field
    public function getCutoffTimesArrayAttribute() {
        $cutoff_time = array();
        $i = 1;
        while ($i <= 48) {
            $cutoff_time[$i] = $i . ' ' . trans_choice('experiences.manage.hour_s', $i);
            if ($i < 4) {
                $i++;
            } elseif ($i == 8) {
                $i = $i + 4;
            } else {
                $i = (2 * $i);
            }
        }
        return $cutoff_time;
    }
    // To get the last updated time
    public function getChangesSavedAttribute() {
        $to = time();
        $from = strtotime($this->attributes['updated_at']);
        $secs = $to - $from;

        $bit = array(
            'second' => $secs % 60,
            'minute' => $secs / 60 % 60,
            'hour' => $secs / 3600 % 24,
            'day' => $secs / 86400 % 7,
            'week' => $secs / 604800 % 52,
            'year' => $secs / 31556926 % 12,
        );

        $time_text = trans('experiences.manage.few_seconds');
        foreach ($bit as $k => $v) {
            if ($v > 0) {
                if ($k == 'second') {
                    $time_text = trans('experiences.manage.few_seconds');
                } else {
                    $time_text = $v . ' ' . trans_choice('experiences.manage.' . $k . '_s', $v);
                }
            }
        }

        $changes_saved = trans('experiences.manage.saved_time_ago', ['time' => $time_text]);

        return $changes_saved;
    }
    // To get the link of the experience
    public function getLinkAttribute() {
        $site_settings_url = @SiteSettings::where('name', 'site_url')->first()->value;
        $url = \App::runningInConsole() ? $site_settings_url : url('/');
        $this_link = $url . '/experiences/' . $this->id;
        return $this_link;
    }

    /*For Rooms Data Merge*/
    // Join with host_experience_location table
    public function rooms_address() {
        return $this->host_experience_location();
    }
    // Join with users table
    public function users() {
        return $this->user();
    }
    //Get rooms photo all
    public function rooms_photos() {
        return $this->host_experience_photos();

    }
    public function getNameAttribute() {
        return $this->attributes['title'];
    }
    /*For Rooms Data Merge*/
    // Join with reviews table
    public function reviews() {
        return $this->hasMany('App\Models\Reviews', 'room_id', 'id')->where('user_to', $this->attributes['user_id'])->where('list_type', 'Experiences');
    }

    // To get the price and availability details for a particular date for details page
    public function get_date_status_price($date) {
        $calendar_data = HostExperienceCalendar::where('host_experience_id', $this->attributes['id'])->where('date', $date)->first();
        $status = 'Available';
        $price = $this->attributes['price_per_guest'];
        $is_reserved = false;
        $spots_left = $this->attributes['number_of_guests'];

        if ($calendar_data) {
            $status = $calendar_data->status;
            $price = $calendar_data->price;
            $is_reserved = ($calendar_data->source == 'Reservation');
            $spots_left = ($spots_left - $calendar_data->spots_booked);
        }

        return compact('status', 'price', 'is_reserved', 'spots_left');
    }

    // To get the price and availability details for a particular date for payment
    public function get_date_availability_details($date, $is_session = false) {
        $date = date('Y-m-d',strtotime($date));
        $start_time = $this->attributes['start_time'];
        $dateTime = new DateTime($date . ' ' . $start_time);
        $currentTime = new DateTime();
        $preparation_hours = @$this->attributes['preparation_hours'];
        $last_minute_guests = @$this->attributes['last_minute_guests'];
        $cutoff_time = @$this->attributes['cutoff_time'];
        $start_time = @$this->attributes['start_time'];
        $end_time = @$this->attributes['end_time'];

        $calendar_data = HostExperienceCalendar::where('host_experience_id', $this->attributes['id'])->where('date', $date)->first();

        $status = 'Available';
        $price = $this->attributes['price_per_guest'];
        $spots_left = $this->attributes['number_of_guests'];
        $is_reserved = false;
        $is_available_booking = true;
        $currency_symbol = html_entity_decode($this->currency->original_symbol);

        if ($calendar_data) {
            $status = $calendar_data->status;
            $price = $calendar_data->price;
            $spots_left = ($spots_left - $calendar_data->spots_booked);
            $is_reserved = ($calendar_data->source == 'Reservation');
            $is_available_booking = !($status == 'Not available' && $calendar_data->source == 'Calendar');
        }

        $diff = $currentTime->diff($dateTime);
        $hour_diff = $diff->h + ($diff->days * 24);

        if ($spots_left <= 0) {
            $is_available_booking = false;
        } else {
            if ($hour_diff < $preparation_hours) {
                if (($last_minute_guests == 'No') || !$is_reserved || ($hour_diff < $cutoff_time)) {
                    $is_available_booking = false;
                }
            }
        }

        if ($is_session) {
            $price = $this->currency_calc('',$price);
            $currency_symbol = html_entity_decode($this->currency->symbol);
        }

      

        $return_array = compact("date", "status", "price", "spots_left", "is_reserved", "is_available_booking", "currency_symbol", "start_time", "end_time");
        return $return_array;
    }
    // To delete all the details if experience deleted
    public function force_delete() {
        $host_experience_id = $this->attributes['id'];
        $this->host_experience_location()->delete();
        $this->guest_requirements()->delete();
        $this->host_experience_photos()->delete();
        $this->host_experience_provides()->delete();
        $this->host_experience_packing_lists()->delete();
        $this->host_experience_calendar()->delete();

        $this->delete();
        return true;
    }
    // To clone the experience to new one
    public function this_clone() {
        $host_experience = $this->replicate();
        $host_experience->save();
        $host_experience_id = $host_experience->id;

        $host_experience_location = $this->host_experience_location->replicate();
        $host_experience_location->host_experience_id = $host_experience_id;
        $host_experience_location->save();

        $guest_requirements = $this->guest_requirements->replicate();
        $guest_requirements->host_experience_id = $host_experience_id;
        $guest_requirements->save();

        $this_photo = $this->host_experience_photos()->first();
        if ($this_photo) {
            $host_experience_photos = $this_photo->replicate();
            $host_experience_photos->host_experience_id = $host_experience_id;
            $host_experience_photos->save();

            $filename = $host_experience_photos->name;
            $file_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/host_experiences/' . $host_experience_id;
            $parent_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/host_experiences/' . $this->id;
            if (!file_exists($file_path)) {
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/host_experiences/' . $host_experience_id, 0777, true);
            }
            copy($parent_path . '/' . $filename, $file_path . '/' . $filename);
        }

        $this_provide = $this->host_experience_provides()->first();
        if ($this_provide) {
            $host_experience_provides = $this_provide->replicate();
            $host_experience_provides->host_experience_id = $host_experience_id;
            $host_experience_provides->save();
        }

        $this_packing_list = $this->host_experience_packing_lists()->first();
        if ($this_packing_list) {
            $host_experience_packing_lists = $this_packing_list->replicate();
            $host_experience_packing_lists->host_experience_id = $host_experience_id;
            $host_experience_packing_lists->save();
        }

        return $host_experience;
    }
    // Calculation for current currency conversion of given price field
    public function currency_calc($field,$amount='') {
        $org_amount = $amount!=''?$amount:$this->attributes[$field];
        if (request()->segment(1) == 'api' || strlen(request()->token) > 25 ) {

            $rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;

            $usd_amount = $org_amount / $rate;

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

            $usd_amount = $org_amount / $rate;

            $default_currency = Currency::where('default_currency', 1)->first()->code;

            $session_rate = Currency::whereCode((Session::get('currency')) ? Session::get('currency') : $default_currency)->first()->rate;

            return round($usd_amount * $session_rate);
        }
    }

    // Overall Reviews Star Rating
    public function getOverallStarRatingAttribute() {
        //get current url
        /*$route=@Route::getCurrentRoute();

            if($route)
            {
                $api_url = @$route->getPath();
            }
            else
            {
                $api_url = '';
            }

            $url_array=explode('/',$api_url);
            //Api currency conversion
*/
        if (request()->segment(1) == 'api') {
            //get review details
            $reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id']);

            if ($reviews->count() == 0) {
                $result['rating_value'] = '0';
            } else {
                $result['rating_value'] = @($reviews->sum('rating') / $reviews->count());

            }
            if (request('token')) {
                $user_details = JWTAuth::parseToken()->authenticate(); 
                $result_wishlist=SavedWishlists::with('wishlists')->where('room_id',$this->attributes['id'])->where('user_id',$user_details->id);

                if($result_wishlist->count() == 0)

                    $result['is_wishlist']="No";

                else

                    $result['is_wishlist']="Yes";
            }
            else
                $result['is_wishlist']="No";

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
    // Reviews Count
    public function getReviewsCountAttribute() {
        $reviews = Reviews::where('room_id', $this->attributes['id'])->where('user_to', $this->attributes['user_id'])->where('list_type', 'Experiences');

        return $reviews->count();
    }

    // Reviews Count
    public function getReviewsCountLangAttribute() {

        return ucfirst(trans_choice('messages.header.review', $this->getReviewsCountAttribute()));
    }

    public function getMinimumPriceAttribute() {
        $payment_helper = new PaymentHelper;
        $minimum_amount = $payment_helper->currency_convert(DEFAULT_CURRENCY, @$this->attributes['currency_code'], MINIMUM_AMOUNT);
        return $minimum_amount;
    }

    public function getProvideItemsNamesAttribute() {
        $provide_items = $this->host_experience_provides;
        $provide_items_names = '';
        foreach ($provide_items as $k => $item) {
            $provide_items_names .= $item->provide_item->name;
            if ($k + 2 == $provide_items->count()) {
                $provide_items_names .= " " . trans('experiences.details.and') . " ";
            } else if ($k + 1 != $provide_items->count()) {
                $provide_items_names .= ", ";
            }
        }

        return $provide_items_names;
    }

    // Get Host Experience Category Name
    public function getCategoryNameAttribute() {
        $category = HostExperienceCategories::find($this->attributes['category']);
        return isset($category->name)?$category->name:'';
    }

    // Get Host Experience City Name
    public function getCityNameAttribute() {
        $city = HostExperienceCities::find($this->attributes['city']);
        return isset($city->name)?$city->name:'';
    }
}
