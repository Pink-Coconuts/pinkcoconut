<?php

/**
 * Reviews Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Reviews
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\ReviewsDataTable;
use App\DataTables\HostExperienceReviewsDataTable;
use App\Models\Reviews;
use App\Models\ProfilePicture;
use App\Models\ReviewsVerification;
use App\Http\Start\Helpers;
use Validator;

class ReviewsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Reviews
     *
     * @param array $dataTable  Instance of ReviewsDataTable
     * @return datatable
     */
    public function index(ReviewsDataTable $dataTable)
    {
        return $dataTable->render('admin.reviews.view');
    }

    /**
     * Load Datatable for Reviews
     *
     * @param array $dataTable  Instance of ReviewsDataTable
     * @return datatable
     */
    public function host_experiences_reviews(HostExperienceReviewsDataTable $dataTable)
    {
        return $dataTable->render('admin.host_experience_reviews.view');
    }

    /**
     * Update Reviews Details
     *
     * @param array $request    Input values
     * @return redirect     to Reviews View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = Reviews::join('rooms', function($join) {
                                $join->on('rooms.id', '=', 'reviews.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reviews.user_from');
                            })
                        ->join('users as users_to', function($join) {
                                $join->on('users_to.id', '=', 'reviews.user_to');
                            })
                        ->where('reviews.id',$request->id)->where('list_type' , 'Rooms')->select(['reviews.id as id', 'reservation_id', 'rooms.name as room_name', 'users.first_name as user_from', 'users_to.first_name as user_to', 'review_by', 'comments', 'private_feedback','cleanliness','accuracy_comments','cleanliness_comments','checkin_comments','communication_comments','value_comments','amenities_comments','love_comments','improve_comments','communication','respect_house_rules','checkin','reviews.amenities as amenities','accuracy','location','value','rating','location_comments','pride_comments'])->get();
            if(!$data['result']->count())
            {
                abort(404);
            }
            return view('admin.reviews.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Reviews Validation Rules
            $rules = array(
                    'comments' => 'required'
                    );

            // Edit Reviews Validation Custom Fields Name
            $niceNames = array(
                        'comments' => 'Comments'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $user = Reviews::find($request->id);

                $user->comments     = $request->comments;

                $user->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/reviews');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/reviews');
        }
    }

    /**
     * Update Reviews Details
     *
     * @param array $request    Input values
     * @return redirect     to Reviews View
     */
    public function exp_update(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = Reviews::join('host_experiences', function($join) {
                                $join->on('host_experiences.id', '=', 'reviews.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reviews.user_from');
                            })
                        ->join('users as users_to', function($join) {
                                $join->on('users_to.id', '=', 'reviews.user_to');
                            })
                        ->where('reviews.id',$request->id)->where('list_type' , 'Experiences')->select(['reviews.id as id', 'reservation_id', 'host_experiences.title as room_name', 'users.first_name as user_from', 'users_to.first_name as user_to', 'review_by', 'comments'])->get();
            if(!$data['result']->count())
            {
                abort(404);
            }
            return view('admin.host_experience_reviews.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Reviews Validation Rules
            $rules = array(
                    'comments' => 'required'
                    );

            // Edit Reviews Validation Custom Fields Name
            $niceNames = array(
                        'comments' => 'Comments'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $user = Reviews::find($request->id);

                $user->comments     = $request->comments;

                $user->save();

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/host_experiences_reviews');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/host_experiences_reviews');
        }
    }
}
