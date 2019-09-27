<?php

/**
 * Host Banners Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Host Banners
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HostBannersDataTable;
use App\Models\HostBanners;
use App\Models\Language;
use App\Http\Start\Helpers;
use Validator;

class HostBannersController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Host Banners
     *
     * @param array $dataTable  Instance of HostBannersDataTable
     * @return datatable
     */
    public function index(HostBannersDataTable $dataTable)
    {
        return $dataTable->render('admin.host_banners.view');
    }

    /**
     * Add a New Host Banners
     *
     * @param array $request  Input values
     * @return redirect     to Host Banners view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            $data['languages'] = Language::translatable()->pluck('name', 'value');
            return view('admin.host_banners.add', $data);
        }
        else if($request->submit)
        {
            // Add Host Banners Validation Rules
            $rules = array(
                    'title'         => 'required',
                    'description'   => 'required', 
                    'link_title'    => 'required',
                    'link'          => 'required',
                    'image'   => 'required|mimes:jpg,png,gif,jpeg'
                    );

            // Add Host Banners Validation Custom Names
            $niceNames = array(
                        'title'         => 'Title',
                        'description'   => 'Description', 
                        'link_title'    => 'Link Title',
                        'link'          => 'Link',
                        'image'         => 'Image'
                        );
            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.title'] = 'required';
                $rules['translations.'.$k.'.description'] = 'required';
                $rules['translations.'.$k.'.link_title'] = 'required';
                $rules['translations.'.$k.'.locale'] = 'required';

                $niceNames['translations.'.$k.'.title'] = 'Title';
                $niceNames['translations.'.$k.'.description'] = 'Description';
                $niceNames['translations.'.$k.'.link_title'] = 'Link Title';
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
                        return redirect(ADMIN_URL.'/host_banners');
                    }
                }
                else
                {
                    $image     =   $request->file('image');
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'host_banners_'.time() . '.' . $extension;

                    $success = $image->move('images/host_banners', $filename);
            
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }

                $host_banners = new HostBanners;

                $host_banners->title  = $request->title;
                $host_banners->description  = $request->description;
                $host_banners->link  = $request->link;
                $host_banners->link_title  = $request->link_title;
                $host_banners->image = $filename;

                $host_banners->save();

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $host_banners->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->title = $translation_data['title'];
                    $translation->description = $translation_data['description'];
                    $translation->link_title = $translation_data['link_title'];

                    $translation->save();
                }

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/host_banners');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/host_banners');
        }
    }

    /**
     * Update Host Banners Details
     *
     * @param array $request    Input values
     * @return redirect     to Host Banners View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = HostBanners::find($request->id);
            $data['languages'] = Language::translatable()->pluck('name', 'value');
            return view('admin.host_banners.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Host Banners Validation Rules
            $rules = array(
                    'title'         => 'required',
                    'description'   => 'required', 
                    'link_title'    => 'required',
                    'link'          => 'required',
                    'image'         => 'mimes:jpg,png,gif,jpeg'
                    );

            // Edit Host Banners Validation Custom Names
            $niceNames = array(
                        'title'         => 'Title',
                        'description'   => 'Description', 
                        'link_title'    => 'Link TItle',
                        'link'          => 'Link',
                        'image'         => 'Image'
                        );

            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.title'] = 'required';
                $rules['translations.'.$k.'.description'] = 'required';
                $rules['translations.'.$k.'.link_title'] = 'required';
                $rules['translations.'.$k.'.locale'] = 'required';

                $niceNames['translations.'.$k.'.title'] = 'Title';
                $niceNames['translations.'.$k.'.description'] = 'Description';
                $niceNames['translations.'.$k.'.link_title'] = 'Link Title';
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
                $host_banners = HostBanners::find($request->id);

                $host_banners->title  = $request->title;
                $host_banners->description  = $request->description;
                $host_banners->link  = $request->link;
                $host_banners->link_title  = $request->link_title;

                $image     =   $request->file('image');

                if($image) {
                    if(UPLOAD_DRIVER=='cloudinary')
                    {
                        $c=$this->helper->cloud_upload($request->file('image'));
                        if($c['status']!="error")
                        {
                            $filename=$c['message']['public_id'];    
                            $host_banners->image=$filename;
                        }
                        else
                        {
                            $this->helper->flash_message('danger', $c['message']); // Call flash message function
                            return redirect(ADMIN_URL.'/host_banners');
                        }
                    }
                    else
                    {
                        $extension =   $image->getClientOriginalExtension();
                        $filename  =   'host_banners_'.time() . '.' . $extension;
        
                        $success = $image->move('images/host_banners', $filename);
                        $compress_success = $this->helper->compress_image('images/host_banners/'.$filename, 'images/host_banners/'.$filename, 80);
                        
                        if(!$success)
                            return back()->withError('Could not upload Image');

                        chmod('images/host_banners/'.$filename, 0777);
                        $host_banners->image = $filename;
                    }
                }

                $host_banners->save();

                $removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $host_banners->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $host_banners->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->title = $translation_data['title'];
                    $translation->description = $translation_data['description'];
                    $translation->link_title = $translation_data['link_title'];

                    $translation->save();
                }

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/host_banners');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/host_banners');
        }
    }

    /**
     * Delete Host Banners
     *
     * @param array $request    Input values
     * @return redirect     to Host Banners View
     */
    public function delete(Request $request)
    {
        $banner = HostBanners::find($request->id);
        if($banner != ''){
            HostBanners::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function    
        }
        return redirect(ADMIN_URL.'/host_banners');
    }
}
