<?php

/**
 * Host Experience Categories Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Host Experience Categories
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HostExperienceCategoriesDataTable;
use App\DataTables\HostExperienceInquiriesDataTable;
use App\Models\HostExperienceCategories;
use App\Models\HostExperiences;
use App\Http\Start\Helpers;
use Validator;

class HostExperienceCategoriesController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers
    protected $main_title;
    protected $base_url;
    protected $base_view_path;
    protected $view_data;

    public function __construct()
    {
        $this->helper = new Helpers;
        $this->view_data['main_title'] = $this->main_title = 'Host Experience Category';
        $this->view_data['base_url'] = $this->base_url = url(ADMIN_URL.'/host_experience_categories');
        $this->view_data['base_view_path'] = $this->base_view_path = 'admin.host_experiences.host_experience_categories.';
        $this->view_data['host_experience_menu'] = 'host_experience_categories';
    }

    /**
     * Load Datatable for Host Experience Categories
     *
     * @param array $dataTable  Instance of HostExperienceCategoriesDataTable
     * @return datatable
     */
    public function index(HostExperienceCategoriesDataTable $dataTable)
    {
        return $dataTable->render($this->base_view_path.'view', $this->view_data);
    }

    /**
     * Add a New Host Experience Categories
     *
     * @param array $request  Input values
     * @return redirect     to Host Experience Categories view
     */
    public function add(Request $request)
    {
        if($request->method() == 'GET')
        {
            $this->view_data['host_experience_category'] = new HostExperienceCategories;
            return view($this->base_view_path.'add', $this->view_data);
        }
        else if($request->submit != 'submit')
        {
            return redirect($this->base_url);
        }
        else
        {
            $validate_return = $this->validate_request_data($request->all());
            if($validate_return)
            {
                return $validate_return;
            }
            
            $host_experience_category = new HostExperienceCategories;
            $host_experience_category->name = $request->name;
            $host_experience_category->status = $request->status;
            $host_experience_category->save();

            $this->helper->flash_message('success', 'New '.$this->main_title.' Added Successfully');
            return redirect($this->base_url);
        }
    }

    /**
     * Update Host Experience Categories Details
     *
     * @param array $request    Input values
     * @return redirect     to Host Experience Categories View
     */
    public function update(Request $request)
    {
        if($request->method() == 'GET')
        {
            $host_experience_category = $this->view_data['host_experience_category'] = HostExperienceCategories::find($request->id);
            $this->view_data['id'] = $request->id;

            if(!$host_experience_category)
            {
                return redirect($this->base_url);
            }
            return view($this->base_view_path.'edit', $this->view_data);
        }
        else if($request->submit != 'submit')
        {
            return redirect($this->base_url);
        }
        else
        {
            $host_experience_category = HostExperienceCategories::find($request->id);
            if(!$host_experience_category)
            {
                return redirect($this->base_url);
            }
            
            $validate_return = $this->validate_request_data($request->all(), $request->id);
            if($validate_return)
            {
                return $validate_return;
            }
            
            $host_experience_category->name = $request->name;
            $host_experience_category->status = $request->status;
            $host_experience_category->save();

            $this->helper->flash_message('success', $this->main_title.' Updated Successfully');
            return redirect($this->base_url);
        }
    }

    /**
     * Delete Host Experience Categories
     *
     * @param array $request    Input values
     * @return redirect     to Host Experience Categories View
     */
    public function delete(Request $request)
    {
        $already_used_count = $this->get_already_used_count($request->id);
        if($already_used_count > 0)
        {
            $this->helper->flash_message('error', 'Some Host Experiences using this '.$this->main_title.' already.');
            return redirect($this->base_url);
        }
        $active_rows_count = $this->get_active_rows_count($request->id);
        if($active_rows_count <= 1)
        {
            $this->helper->flash_message('error', 'Atleast one Active '.$this->main_title.' is required.');
            return redirect($this->base_url);
        }
        HostExperienceCategories::where('id', $request->id)->delete();

        $this->helper->flash_message('success', $this->main_title.' Deleted Successfully');
        return redirect($this->base_url);
    }

    /**
     * Validate Host Experience Categories Request Data
     *
     * @param array $request    Input values
     * @return redirect     to Validation Results
     */
    public function validate_request_data($request_data, $id = '')
    {
        $rules  = array(
            'name' => 'required',
            'status' => 'required',
        );

        $messages = array(

        );

        $attributes = array(
            'name' => 'Name',
            'status' => 'Status'
        );

        $validator = Validator::make($request_data, $rules, $messages, $attributes);

        if(@$request_data['status'] == 'Inactive' && @$id)
        {
            $active_rows_count = $this->get_active_rows_count($id);
            if($active_rows_count <= 0)
            {
                $validator->after(function($validator) {
                    $validator->errors()->add('status', 'Must choose Active(Atleast One '.$this->main_title.' in "Active" status)');
                });
            }
            $already_used_count = $this->get_already_used_count($id);
            if($already_used_count > 0)
            {
                $validator->after(function($validator) {
                    $validator->errors()->add('status', 'Some Host Experiences using this '.$this->main_title.' already. You can\'t change the status to "Inactive".');
                });
            }
        }

        if ($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
        }

    }

    public function get_active_rows_count($id)
    {
        $active_rows_count = HostExperienceCategories::where('id', '!=', $id)->where('status', 'Active')->count();
        return $active_rows_count;
    }

    public function get_already_used_count($id)
    {
        $already_used_count = HostExperiences::where('category', $id)->orWhere('secondary_category', $id)->count();
        return $already_used_count;
    }

    public function feature(Request $request)
    {
        $host_experience_category = HostExperienceCategories::find($request->id);
        $prev = $host_experience_category->is_featured;

        if($prev == 'Yes')
            HostExperienceCategories::where('id',$request->id)->update(['is_featured'=>'No']);
        else
            HostExperienceCategories::where('id',$request->id)->update(['is_featured'=>'Yes']);

        $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
        
        return redirect($this->base_url);            

    }

    public function host_experiences_inquiries(HostExperienceInquiriesDataTable $dataTable)
    {
        return $dataTable->render('admin.host_experience_inquiries.view');
    }

}
