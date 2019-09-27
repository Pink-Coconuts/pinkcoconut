<?php

/**
 * Amenities Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Amenities
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\AmenitiesDataTable;
use App\Models\Amenities;
use App\Models\AmenitiesLang;
use App\Models\Language;
use App\Models\AmenitiesType;
use App\Http\Start\Helpers;
use Validator;

class AmenitiesController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Amenities
     *
     * @param array $dataTable  Instance of AmenitiesDataTable
     * @return datatable
     */
    public function index(AmenitiesDataTable $dataTable)
    {
        return $dataTable->render('admin.amenities.view');
    }

    /**
     * Add a New Amenities
     *
     * @param array $request  Input values
     * @return redirect     to Admin view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {    $data['language'] = Language::translatable()->get();  
        	$data['types'] = AmenitiesType::active_all();
            
            return view('admin.amenities.add', $data);
        }
        else if($request->submit)
        {   //check name exists or not            
             $amenities_name = Amenities::where('name','=',@$request->name[0])->get();            
             if(@$amenities_name->count() != 0){             
                     $this->helper->flash_message('error', 'This Name already exists'); // Call flash message function
                     return redirect(ADMIN_URL.'/amenities');
                }   
     $rules = array(

        'icon' => 'required|mimes:jpg,png,jpeg',
      );

      $niceNames = array(

        'icon' => 'Icon',
      );

      $validator = Validator::make($request->all(), $rules);
      $validator->setAttributeNames($niceNames);

      if ($validator->fails()) {

        return back()->withErrors($validator); // Form calling with Errors and Input values
      }

      //upload file

      $name = '';

      $photos_uploaded = array();
      if (UPLOAD_DRIVER == 'cloudinary') {
        //cloudinary upload for amenities icon

        if (isset($_FILES["icon"]["name"])) {

          $tmp_name = $_FILES["icon"]["tmp_name"];

          $name = str_replace(' ', '_', $_FILES["icon"]["name"]);

          $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

          $name = time() . '_.' . $ext;

          if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {

            $c = $this->helper->cloud_upload($tmp_name);
            if ($c['status'] != "error") {
              $name = $c['message']['public_id'];
            } else {
              $this->helper->flash_message('danger', $c['message']); // Call flash message function
              return redirect(ADMIN_URL . '/amenities');
            }
            $photos_uploaded[] = $name;
          }

        }
      } else {

        // local upload for amenities icon

        $icon_name = [];
        if (isset($_FILES["icon"]["name"])) {

          $tmp_name = $_FILES["icon"]["tmp_name"];

          $name = str_replace(' ', '_', $_FILES["icon"]["name"]);

          $icon_name = $name;

          $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

          $name = time() . '.' . $ext;

          $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/amenities';

          if (!file_exists($filename)) {
            mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/amenities', 0777, true);
          }

          if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {

            $file = $request->file('icon');
            $file->move("images/amenities/", $name);

          }

        }
      }
                //end name check
           $amenities = new Amenities;
        

        for($i=0;$i < count($request->lang_code);$i++){
         
        if($request->lang_code[$i]=="en"){

                $amenities->type_id     = $request->type_id;
                $amenities->name        = $request->name[$i];
                $amenities->description = $request->description[$i];
                $amenities->icon      = $name;
                $amenities->status      = $request->status;
                $amenities->save();
                $lastInsertedId = $amenities->id;
        }
        else{
                $amenities_lang = new AmenitiesLang;
                $amenities_lang->amenities_id   = $lastInsertedId;
                $amenities_lang->lang_code   = $request->lang_code[$i];
                $amenities_lang->name        = $request->name[$i];
                $amenities_lang->description = $request->description[$i];
                $amenities_lang->save();

        }

        }
                 $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/amenities');
            }
     
        else
        {
            return redirect(ADMIN_URL.'/amenities');
        }
    }

    /**
     * Update Amenities
     *
     * @param array $request    Input values
     * @return redirect     to Admin View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {    $data['language'] = Language::translatable()->get();          
            $data['langresult'] = AmenitiesLang::where('amenities_id',$request->id)->get();  
			$data['types']  = AmenitiesType::active_all();
			$data['result'] = Amenities::find($request->id);

            return view('admin.amenities.edit', $data);
        }
        else if($request->submit)
        {
            // Delete Amenities Lang

        $lang_id_arr = $request->lang_id;
        unset($lang_id_arr[0]);  

         if(empty($lang_id_arr))
        {
        $amenities_type_lang = AmenitiesLang::where('amenities_id',$request->id); 
        $amenities_type_lang->delete();
        }

        $property_del = AmenitiesLang::select('id')->where('amenities_id',$request->id)->get();
        foreach($property_del as $values){ 
          if(!in_array($values->id,$lang_id_arr))
        {
        $amenities_type_lang = AmenitiesLang::find($values->id); 
        $amenities_type_lang->delete();
        }
             
        } // End Delete Amenities
        //check name exists or not            
        $amenities_name = Amenities::where('id','!=',@$request->id)->where('name','=',@$request->name[0])->get();            
             if(@$amenities_name->count() != 0){             
                     $this->helper->flash_message('error', 'This Name already exists'); // Call flash message function
                     return redirect(ADMIN_URL.'/amenities');
                }   
       $rules = array(

        'icons' => 'mimes:jpg,png,jpeg',
      );

      $niceNames = array(

        'icons' => 'Icon',
      );

      $validator = Validator::make($request->all(), $rules);
      $validator->setAttributeNames($niceNames);

      if ($validator->fails()) {

        return back()->withErrors($validator); // Form calling with Errors and Input values
      }

      $name = '';

      $photos_uploaded = array();
      if (UPLOAD_DRIVER == 'cloudinary') {

        //cloudinary upload for amenities icon

        if ($request->icons) {

          $tmp_name = $_FILES["icons"]["tmp_name"];

          $name = str_replace(' ', '_', $_FILES["icons"]["name"]);

          $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

          $name = time() . '_.' . $ext;

          if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {

            $c = $this->helper->cloud_upload($tmp_name);
            if ($c['status'] != "error") {
              $name = $c['message']['public_id'];
            } else {
              $this->helper->flash_message('danger', $c['message']); // Call flash message function
              return redirect(ADMIN_URL . '/amenities');
            }
            $photos_uploaded[] = $name;
          }

        }
      } else {

        // local upload for amenities icon
          if ($request->icons) {

        if (isset($_FILES["icons"]["name"]) && $request->icons) {

          $tmp_name = $_FILES["icons"]["tmp_name"];

          $name = str_replace(' ', '_', $_FILES["icons"]["name"]);

          $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

          $name = time() . '.' . $ext;

          $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/amenities';

          if (!file_exists($filename)) {
            mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/amenities', 0777, true);
          }

          if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {

            $file = $request->file('icons');
            $file->move("images/amenities/", $name);

          }

        }
      }
    }
        //Update Amenities Lang
 
                  
        for($i=0;$i < count($request->lang_code);$i++){
         
          if($request->lang_code[$i]=="en"){

                $amenities = Amenities::find($request->id);
                $amenities->type_id     = $request->type_id;
                if($name)
                {
                $amenities->icon        = $name;
                }
                $amenities->name        = $request->name[$i];
                $amenities->description = $request->description[$i];
                $amenities->status      = $request->status;
                $amenities->save();

          }
        else{
              if(isset($request->lang_id[$i])){

              $amenities_lang = AmenitiesLang::find($request->lang_id[$i]);
              $amenities_lang->lang_code   = $request->lang_code[$i];
              $amenities_lang->name        = $request->name[$i];
              $amenities_lang->description = $request->description[$i];
              $amenities_lang->save();   

              } 
              else{

              $amenities_lang =  new AmenitiesLang; 
              $amenities_lang->amenities_id   = $request->id;    
              $amenities_lang->lang_code   = $request->lang_code[$i];
              $amenities_lang->name        = $request->name[$i];
              $amenities_lang->description = $request->description[$i];
              $amenities_lang->save();


              }

        }
      }
      // End Update Amenities Lang

                
                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/amenities');
            }
      
        else
        {
            return redirect(ADMIN_URL.'/amenities');
        }
    }

    /**
     * Delete Amenities
     *
     * @param array $request    Input values
     * @return redirect     to Admin View
     */
    public function delete(Request $request)
    {    
      $amenities = Amenities::find($request->id);
      if(!is_null($amenities)) {
        AmenitiesLang::where('amenities_id',$request->id)->delete();
        Amenities::find($request->id)->delete();
        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
      }
      else {
        // Call flash message function
        $this->helper->flash_message('warning', 'Already Deleted'); 
      }
      return redirect(ADMIN_URL.'/amenities');
    }
}
