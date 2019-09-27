<?php

/**
 * Host Experience Provide Items Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Host Experience Provide Items
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HostExperienceProvideItemsDataTable;
use App\Models\HostExperienceProvideItems;
use App\Models\HostExperienceProvides;
use App\Http\Start\Helpers;
use Validator;

class HostExperienceProvideItemsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers
    protected $main_title;
    protected $base_url;
    protected $base_view_path;
    protected $view_data;

    public function __construct()
    {
        $this->helper = new Helpers;
        $this->view_data['main_title'] = $this->main_title = 'Host Experience Provide Item';
        $this->view_data['base_url'] = $this->base_url = url(ADMIN_URL.'/host_experience_provide_items');
        $this->view_data['base_view_path'] = $this->base_view_path = 'admin.host_experiences.host_experience_provide_items.';
        $this->view_data['host_experience_menu'] = 'host_experience_provide_items';
    }

    /**
     * Load Datatable for Host Experience Provide Items
     *
     * @param array $dataTable  Instance of HostExperienceProvideItems
     * @return datatable
     */
    public function index(HostExperienceProvideItemsDataTable $dataTable)
    {
        return $dataTable->render($this->base_view_path.'view', $this->view_data);
    }

    /**
     * Add a New Host Experience Provide Items
     *
     * @param array $request  Input values
     * @return redirect     to Host Experience Provide Items view
     */
    public function add(Request $request)
    {
        if($request->method() == 'GET')
        {
            $this->view_data['host_experience_provide_item'] = new HostExperienceProvideItems;
            return view($this->base_view_path.'add', $this->view_data);
        }
        else if($request->submit != 'submit')
        {
            return redirect($this->base_url);
        }
        elseif($request->method() == 'POST')
        {
            $validate_return = $this->validate_request_data($request->all());
            if($validate_return)
            {
                return $validate_return;
            }

            $host_experience_provide_item = new HostExperienceProvideItems;
            
            $image = $request->file('image');
            if($image)
            {
                $result = $this->upload_file($image);
                if($result['error'] != '')
                {
                    $this->helper->flash_message('danger', $result['error']);
                    return back();
                }
                else
                {
                    $host_experience_provide_item->image = @$result['filename'];
                }
            }
            
            $host_experience_provide_item->name = $request->name;
            $host_experience_provide_item->status = $request->status;
            $host_experience_provide_item->save();

            $this->helper->flash_message('success', 'New '.$this->main_title.' Added Successfully');
            return redirect($this->base_url);
        }
    }

    /**
     * Update Host Experience Provide Items Details
     *
     * @param array $request    Input values
     * @return redirect     to Host Experience Provide Items View
     */
    public function update(Request $request)
    {
        if($request->method() == 'GET')
        {
            $host_experience_provide_item = $this->view_data['host_experience_provide_item'] = HostExperienceProvideItems::find($request->id);
            $this->view_data['id'] = $request->id;

            if(!$host_experience_provide_item)
            {
                return redirect($this->base_url);
            }
            return view($this->base_view_path.'edit', $this->view_data);
        }
        else if($request->submit != 'submit')
        {
            return redirect($this->base_url);
        }
        elseif($request->method() == 'POST')
        {
            $host_experience_provide_item = HostExperienceProvideItems::find($request->id);
            if(!$host_experience_provide_item)
            {
                return redirect($this->base_url);
            }

            $validate_return = $this->validate_request_data($request->all(), $request->id);
            if($validate_return)
            {
                return $validate_return;
            }
            
            $image = $request->file('image');
            if($image)
            {
                $result = $this->upload_file($image);
                if($result['error'] != '')
                {
                    $this->helper->flash_message('danger', $result['error']);
                    return back();
                }
                else
                {
                    $host_experience_provide_item->image = @$result['filename'];
                }
            }
            
            $host_experience_provide_item->name = $request->name;
            $host_experience_provide_item->status = $request->status;
            $host_experience_provide_item->save();

            $this->helper->flash_message('success', $this->main_title.' Updated Successfully');
            return redirect($this->base_url);
        }
    }

    /**
     * Delete Host Experience Provide Items
     *
     * @param array $request    Input values
     * @return redirect     to Host Experience Provide Items View
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
        HostExperienceProvideItems::where('id', $request->id)->delete();

        $this->helper->flash_message('success', $this->main_title.' Deleted Successfully');
        return redirect($this->base_url);
    }

    /**
     * Validate Host Experience Provide Items Request Data
     *
     * @param array $request    Input values
     * @return redirect     to Validation Results
     */
    public function validate_request_data($request_data, $id = '')
    {
        $rules  = array(
            'name' => 'required',
            'image' => 'mimes:jpeg,jpg,png',
            'status' => 'required',
        );

        if(!@$id)
        {
            $rules['image'] .='|required';
        }

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
            $laready_used_count = $this->get_already_used_count($id);
            if($laready_used_count > 0)
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
        $active_rows_count = HostExperienceProvideItems::where('id', '!=', $id)->where('status', 'Active')->count();
        return $active_rows_count;
    }
    public function get_already_used_count($id)
    {
        $already_used_count = HostExperienceProvides::where('host_experience_provide_item_id', $id)->count();
        return $already_used_count;
    }

    public function upload_file($file)
    {
        $upload_path = 'images/host_experiences/provide_items';
        $upload_path_dir = base_path().'/'.$upload_path;
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
                $filename  =   'host_experience_providee_items_'.time() . '.' . $extension;
                $success = $file->move($upload_path, $filename);

                $return['filename'] = $filename;
                
                if(!$success)
                {
                    $return['error'] = 'Could not upload Image';
                }
            }
        }
        else
        {
            $return['error'] = 'Could not upload Image';
        }

        return $return;
    }

}
