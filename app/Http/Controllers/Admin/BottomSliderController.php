<?php

/**
 * BottomSlider Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    BottomSlider
 * @author      Trioangle Product Team
 * @version     0.8
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\BottomSliderDataTable;
use App\Models\BottomSlider;
use App\Models\Language;
use App\Http\Start\Helpers;
use Validator;

class BottomSliderController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for BottomSlider
     *
     * @param array $dataTable  Instance of BottomSliderDataTable
     * @return datatable
     */
    public function index(BottomSliderDataTable $dataTable)
    {
        return $dataTable->render('admin.bottom_slider.view');
    }

    /**
     * Add a New Bottom Slider
     *
     * @param array $request  Input values
     * @return redirect     to Bottom Slider view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {   
            $data['languages'] = Language::translatable()->pluck('name', 'value');
            return view('admin.bottom_slider.add', $data);
        }
        else if($request->submit)
        {
            // Add Bottom Slider Validation Rules
            $rules = array(
                    'image'   => 'required|mimes:jpg,png,gif,jpeg' 
                    );

            // Add Bottom Slider Validation Custom Names
            $niceNames = array(
                        'image'    => 'Image',
                        'title'    => 'Title',
                        'des'    => 'Title',
                        'order'   => 'Position', 
                        'status'  => 'Status',
                        );
            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $niceNames['translations.'.$k.'.locale'] = 'Language';
            }
            $validator = Validator::make($request->all(), $rules);
            
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $image    = $request->file('image');

                 $slider = new BottomSlider;

                    if( $image)
                    {
                        $filesize = $image->getSize();

                        if($filesize > (5 * 1024 * 1024))
                        {
                            $this->helper->flash_message('error', trans('messages.profile.image_size_exceeds_5mb')); // Call flash message function
                            return back();

                        }
               
           
 

                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $c=$this->helper->cloud_upload($request->file('image'));
                    if($c['status']!="error")
                    {
                        $filename=$c['message']['public_id'];    
                    }
                    else
                    {
                        $this->helper->flash_message('danger', $c['message']); // Call flash message function
                        return redirect(ADMIN_URL.'/bottom_slider');
                    }
                }
                else
                {
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'slider_'.time() . '.' . $extension;

                    $success = $image->move('images/bottom_slider', $filename);
                    $this->helper->compress_image('images/bottom_slider/'.$filename, 'images/bottom_slider/'.$filename, 80);
            
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }

                $slider->image = $filename;

            }

               

                
                $slider->order = $request->order; 
                $slider->title = $request->title; 
                $slider->description = $request->description; 
                $slider->status = $request->status;

                $slider->save();

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $slider->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->title = $translation_data['title'];
                    $translation->description = $translation_data['description'];

                    $translation->save();
                }

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/bottom_slider');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/bottom_slider');
        }
    }

    /**
     * Update Bottom Slider Details
     *
     * @param array $request    Input values
     * @return redirect     to Bottom Slider View
     */
    public function update(Request $request)
    {
        $slider_id = BottomSlider::find($request->id); if(empty($slider_id))  abort('404');
        if(!$_POST)
        {
			$data['result'] = BottomSlider::find($request->id);
            $data['languages'] = Language::translatable()->pluck('name', 'value');
            return view('admin.bottom_slider.edit', $data);
        }
        else if($request->submit)
        {

            // Update Bottom Slider Validation Rules
            $rules = array(
                    'image'   => 'mimes:jpeg,png,gif' 
                    );

            // Update Bottom Slider Validation Custom Names
            $niceNames = array(
                        'order'   => 'Position', 
                        'image'    => 'Image',
                        'status'  => 'Status',
                        'title'  => 'Title',
                        'description'  => 'Description',
                        );

            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $niceNames['translations.'.$k.'.locale'] = 'Language';
            }

            $validator = Validator::make($request->all(), $rules);

            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }

                $slider = BottomSlider::find($request->id);

                $image     =   $request->file('image');


                if($image) {

                    $filesize = $image->getSize();

                    if($filesize > (5 * 1024 * 1024))
                    {
                        $this->helper->flash_message('error', trans('messages.profile.image_size_exceeds_5mb')); // Call flash message function
                        return back();
                    }

                    if(UPLOAD_DRIVER=='cloudinary')
                    {
                        $c=$this->helper->cloud_upload($request->file('image'));
                        if($c['status']!="error")
                        {
                            $filename=$c['message']['public_id'];    
                        }
                        else
                        {
                            $this->helper->flash_message('danger', $c['message']); // Call flash message function
                            return redirect(ADMIN_URL.'/bottom_slider');
                        }
                    }
                    else
                    {
                        $extension =   $image->getClientOriginalExtension();
                        $filename  =   'slider_'.time() . '.' . $extension;
        
                        $success = $image->move('images/bottom_slider', $filename);
                        $this->helper->compress_image('images/bottom_slider/'.$filename, 'images/bottom_slider/'.$filename, 80);
            
                        if(!$success)
                            return back()->withError('Could not upload Image');
                    }

                    $slider->image = $filename;
                }

                $slider->order = $request->order;
                $slider->status = $request->status;
                $slider->title = $request->title;
                $slider->description = $request->description;
                $slider->updated_at = date('Y-m-d H:i:s');
                
                $slider->save();

                $removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $slider->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $slider->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->title = $translation_data['title'];
                    $translation->description = $translation_data['description'];

                    $translation->save();
                }

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/bottom_slider');
        }
        else
        {
            return redirect(ADMIN_URL.'/bottom_slider');
        }
    }

    /**
     * Delete Bottom Slider
     *
     * @param array $request    Input values
     * @return redirect     to Bottom Slider View
     */
    public function delete(Request $request)
    {
        $slider = BottomSlider::find($request->id);
        if(!is_null($slider)) {
            BottomSlider::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        else {
            // Call flash message function
            $this->helper->flash_message('warning', 'Already Deleted');
        }

        return redirect(ADMIN_URL.'/bottom_slider');
    }
}
