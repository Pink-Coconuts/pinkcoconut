<?php

/**
 * Home Cities Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Home Cities
 * @author      Trioangle Product Team
 * @version     1.5.8.1
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HomeCitiesDataTable;
use App\Models\HomeCities;
use App\Models\HomeCitiesLang;
use App\Models\language;
use App\Http\Start\Helpers;
use Validator;

class HomeCitiesController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Home Cities
     *
     * @param array $dataTable  Instance of HomeCitiesDataTable
     * @return datatable
     */
    public function index(HomeCitiesDataTable $dataTable)
    {
        return $dataTable->render('admin.home_cities.view');
    }

    /**
     * Add a New Home Cities
     *
     * @param array $request  Input values
     * @return redirect     to Home Cities view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            $data['language'] = Language::get(); 
            return view('admin.home_cities.add',$data);
        }
        else if($request->submit)
        {
            // Add Home Cities Validation Rules
            $rules = array(
                    'name'    => 'required',
                    'image'   => 'required|mimes:jpg,png,gif,jpeg', 
                    );

            // Add Home Cities Validation Custom Names
            $niceNames = array(
                        'name'    => 'City Name',
                        'image'    => 'Image'
                        );

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
                        return redirect(ADMIN_URL.'/home_cities');
                    }
                }
                else
                {
                    $image     =   $request->file('image');
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'home_city_'.time() . '.' . $extension;
                    

                    $success = $image->move('images/home_cities', $filename);
            
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }

                $home_cities = new HomeCities;

                 for($i=0;$i < count($request->lang_code);$i++){
         
        if($request->lang_code[$i]=="en"){
      
                $home_cities->name  = $request->name[$i];
                $home_cities->image = $filename;
                $home_cities->save();
                $lastInsertedId = $home_cities->id;
        }
        else{
         $home_cities_lang = new HomeCitiesLang;
         $home_cities_lang->home_cities_id   = $lastInsertedId;
         $home_cities_lang->lang_code   = $request->lang_code[$i];
         $home_cities_lang->name        = $request->name[$i];      
         $home_cities_lang->save();

        }

        }
                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/home_cities');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/home_cities');
        }
    }

    /**
     * Update Home Cities Details
     *
     * @param array $request    Input values
     * @return redirect     to Home Cities View
     */
    public function update(Request $request)
    {
        if($request->id != '')
        {
        $home_cities=@HomeCities::where('id','=',$request->id)->get();
        if(count($home_cities) == 0)
        {
            return redirect(ADMIN_URL.'/home_cities');
        }
        else
        {
        if(!$_POST)
        {
            $data['result'] = HomeCities::find($request->id);
            $data['language'] = Language::get();
            $all_language = Language::select('value')->get();
            $data['langresult'] = HomeCitiesLang::where('home_cities_id',$request->id)->whereIn('lang_code',$all_language)->get();    
            return view('admin.home_cities.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Home Cities Validation Rules
            $rules = array(
                    'name'    => 'required',
                    'images'   => 'mimes:jpeg,jpg,png,gif', 
                    );

            // Edit Home Cities Validation Custom Names
            $niceNames = array(
                        'name'    => 'City Name',
                        'images'    => 'Image'
                        );

            $validator = Validator::make($request->all(), $rules);
            
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $lang_id_arr = $request->lang_id;
            unset($lang_id_arr[0]); 

            if(empty($lang_id_arr))
            {
            $home_cities_lang = HomeCitiesLang::where('home_cities_id',$request->id); 
            $home_cities_lang->delete();
            }

            $room_del = HomeCitiesLang::select('id')->where('home_cities_id',$request->id)->get();
            foreach($room_del as $values){ 
            if(!in_array($values->id,$lang_id_arr))
            {
            $home_cities_lang = HomeCitiesLang::find($values->id); 
            $home_cities_lang->delete();
            }       

            }

                $home_cities = HomeCities::find($request->id);

                $image     =   $request->file('images');

                if($image) {
                    if(UPLOAD_DRIVER=='cloudinary')
                    {
                        $c=$this->helper->cloud_upload($request->file('images'));  
                       
                        if($c['status']!="error")
                        {
                            $filename=$c['message']['public_id'];    
                        }
                        else
                        {                             
                            $this->helper->flash_message('danger', $c['message']); // Call flash message function
                            return redirect(ADMIN_URL.'/home_cities');
                        }

                    }
                    else
                    {
                        $extension =   $image->getClientOriginalExtension();
                        $filename  =   'home_city_'.time() . '.' . $extension;
        
                        $success = $image->move('images/home_cities', $filename);
                        $compress_success = $this->helper->compress_image('images/home_cities/'.$filename, 'images/home_cities/'.$filename, 80);
                        
                        if(!$success)
                            return back()->withError('Could not upload Image');

                        chmod('images/home_cities/'.$filename, 0777);
                    }
                    $home_cities->image = $filename;
                }

                for($i=0;$i < count($request->lang_code);$i++){
         
          if($request->lang_code[$i]=="en"){

          $home_cities->name        = $request->name[$i];     
          $home_cities->save();

          }
        else{
              if(isset($request->lang_id[$i])){

              $home_cities_lang = HomeCitiesLang::find($request->lang_id[$i]);
              $home_cities_lang->lang_code   = $request->lang_code[$i];
              $home_cities_lang->name        = $request->name[$i];            
              $home_cities_lang->save();            
              } 
              else{

              $home_cities_lang =  new HomeCitiesLang; 
              $home_cities_lang->home_cities_id   = $request->id;    
              $home_cities_lang->lang_code   = $request->lang_code[$i];
              $home_cities_lang->name        = $request->name[$i];              
              $home_cities_lang->save();

              }

        }
      }

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/home_cities');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/home_cities');
        }
    }
    }
    else{
        return redirect(ADMIN_URL.'/home_cities');
    }
}

    /**
     * Delete Home Cities
     *
     * @param array $request    Input values
     * @return redirect     to Home Cities View
     */
    public function delete(Request $request)
    {
        if($request->id != '')
        {
        $home_cities=@HomeCities::where('id','=',$request->id)->get();
        if(count($home_cities) == 0)
        {
            return redirect(ADMIN_URL.'/home_cities');
        }
        else
        {
        HomeCitiesLang::where('home_cities_id',$request->id)->delete();
        HomeCities::find($request->id)->delete();
        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        return redirect(ADMIN_URL.'/home_cities');
        }
        }
    }
}
