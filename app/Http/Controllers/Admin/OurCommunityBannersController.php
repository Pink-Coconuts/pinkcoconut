<?php

/**
 * Our Community Banners Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Our Community Banners
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\OurCommunityBannersDataTable;
use App\Models\OurCommunityBanners;
use App\Models\Language;
use App\Http\Start\Helpers;
use Validator;

class OurCommunityBannersController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Our Community Banners
     *
     * @param array $dataTable  Instance of OurCommunityBannersDataTable
     * @return datatable
     */
    public function index(OurCommunityBannersDataTable $dataTable)
    {
        return $dataTable->render('admin.our_community_banners.view');
    }

    /**
     * Add a New Our Community Banners
     *
     * @param array $request  Input values
     * @return redirect     to Our Community Banners view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            $data['languages'] = Language::translatable()->pluck('name', 'value');
            return view('admin.our_community_banners.add', $data);
        }
        else if($request->submit)
        {
            // Add Our Community Banners Validation Rules
            $rules = array(
                    'image'   => 'required|mimes:jpeg,jpg,png,gif' 
                    );

            // Add Our Community Banners Validation Custom Names
            $niceNames = array(
                        'title'         => 'Title',
                        'description'   => 'Description', 
                        'link'          => 'Link',
                        'image'         => 'Image'
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
                        return redirect(ADMIN_URL.'/our_community_banners');
                    }
                }
                else
                {
                    $image     =   $request->file('image');
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'our_community_banners_'.time() . '.' . $extension;

                    $success = $image->move('images/our_community_banners', $filename);
            
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }

                $our_community_banners = new OurCommunityBanners;

                $our_community_banners->title  = $request->title;
                $our_community_banners->description  = $request->description;
                $our_community_banners->link  = $request->link;
                $our_community_banners->image = $filename;

                $our_community_banners->save();

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $our_community_banners->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->title = $translation_data['title'];
                    $translation->description = $translation_data['description'];

                    $translation->save();
                }
                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/our_community_banners');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/our_community_banners');
        }
    }

    /**
     * Update Our Community Banners Details
     *
     * @param array $request    Input values
     * @return redirect     to Our Community Banners View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = OurCommunityBanners::find($request->id);
            $data['languages'] = Language::translatable()->pluck('name', 'value');
            return view('admin.our_community_banners.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Our Community Banners Validation Rules
            $rules = array('image'   => 'mimes:jpeg,png,gif,jpg');

            // Edit Our Community Banners Validation Custom Names
            $niceNames = array(
                        'title'         => 'Title',
                        'description'   => 'Description', 
                        'link'          => 'Link',
                        'image'         => 'Image'
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
                $our_community_banners = OurCommunityBanners::find($request->id);

                $our_community_banners->title  = $request->title;
                $our_community_banners->description  = $request->description;
                $our_community_banners->link  = $request->link;

                $image     =   $request->file('image');

                if($image) {
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
                            return redirect(ADMIN_URL.'/our_community_banners');
                        }
                    }
                    else
                    {
                        $extension =   $image->getClientOriginalExtension();
                        $filename  =   'our_community_banners_'.time() . '.' . $extension;
        
                        $success = $image->move('images/our_community_banners', $filename);
                        $compress_success = $this->helper->compress_image('images/our_community_banners/'.$filename, 'images/our_community_banners/'.$filename, 80);
                        
                        if(!$success)
                            return back()->withError('Could not upload Image');

                        chmod('images/our_community_banners/'.$filename, 0777);
                    }
                    $our_community_banners->image = $filename;
                }

                $our_community_banners->save();
                
                $removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $our_community_banners->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $our_community_banners->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->title = $translation_data['title'];
                    $translation->description = $translation_data['description'];

                    $translation->save();
                }

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/our_community_banners');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/our_community_banners');
        }
    }

    /**
     * Delete Our Community Banners
     *
     * @param array $request    Input values
     * @return redirect     to Our Community Banners View
     */
    public function delete(Request $request)
    {
        $banner = OurCommunityBanners::find($request->id);
        if(!is_null($banner)) {
            OurCommunityBanners::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        else {
            // Call flash message function
            $this->helper->flash_message('warning', 'Already Deleted');
        }
        return redirect(ADMIN_URL.'/our_community_banners');
    }
}
