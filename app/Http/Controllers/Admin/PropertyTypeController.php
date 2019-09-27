<?php

/**
 * Property Type Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Property Type
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\PropertyTypeDataTable;
use App\Models\PropertyType;
use App\Models\PropertyTypeLang;
use App\Models\Rooms;
use App\Models\language;
use App\Http\Start\Helpers;
use Validator;

class PropertyTypeController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Property Type
     *
     * @param array $dataTable  Instance of PropertyTypeDataTable
     * @return datatable
     */
    public function index(PropertyTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.property_type.view');
    }

    /**
     * Add a New Property Type
     *
     * @param array $request  Input values
     * @return redirect     to Property Type view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {   
            $data['language'] = Language::translatable()->get();           
            return view('admin.property_type.add',$data);
        }
        else if($request->submit)
        {
             //check name exists or not            
             $property_name = PropertyType::where('name','=',@$request->name[0])->get();            
             if(@$property_name->count() != 0){             
                     $this->helper->flash_message('error', 'This Name already exists'); // Call flash message function
                     return redirect(ADMIN_URL.'/property_type');
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
        //cloudinary upload for property_type icon

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
              return redirect(ADMIN_URL . '/property_type');
            }
            $photos_uploaded[] = $name;
          }

        }
      } else {

        // local upload for property_type icon

        $icon_name = [];
        if (isset($_FILES["icon"]["name"])) {

          $tmp_name = $_FILES["icon"]["tmp_name"];

          $name = str_replace(' ', '_', $_FILES["icon"]["name"]);

          $icon_name = $name;

          $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

          $name = time() . '.' . $ext;

          $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/property_type';

          if (!file_exists($filename)) {
            mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/property_type', 0777, true);
          }

          if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {

            $file = $request->file('icon');
            $file->move("images/property_type/", $name);

          }

        }
      }        
        $property_type = new PropertyType;
        

        for($i=0;$i < count($request->lang_code);$i++){
         
        if($request->lang_code[$i]=="en"){
      
        $property_type->name        = $request->name[$i];
        $property_type->description = $request->description[$i];
        $property_type->icon = $name;
        $property_type->status      = $request->status;
        $property_type->save();
        $lastInsertedId = $property_type->id;
        }
        else{
         $property_type_lang = new PropertyTypeLang;
         $property_type_lang->property_id   = $lastInsertedId;
         $property_type_lang->lang_code   = $request->lang_code[$i];
        $property_type_lang->name        = $request->name[$i];
        $property_type_lang->description = $request->description[$i];
        $property_type_lang->save();

        }

        }

       

         $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/property_type');

              }

         
        else
        {
            return redirect(ADMIN_URL.'/property_type');
        }
    }

    /**
     * Update Property Type Details
     *
     * @param array $request    Input values
     * @return redirect     to Property Type View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {

      $data['language'] = Language::get();  
			$data['result'] = PropertyType::find($request->id);
      $data['langresult'] = PropertyTypeLang::where('property_id',$request->id)->get();    
      return view('admin.property_type.edit', $data);

        }
        else if($request->submit)
        {

        // Delete Property Type

        $lang_id_arr = $request->lang_id;
        unset($lang_id_arr[0]);  

         if(empty($lang_id_arr))
        {
        $property_type_lang = PropertyTypeLang::where('property_id',$request->id); 
        $property_type_lang->delete();
        }

        $property_del = PropertyTypeLang::select('id')->where('property_id',$request->id)->get();
        foreach($property_del as $values){ 
          if(!in_array($values->id,$lang_id_arr))
        {
        $property_type_lang = PropertyTypeLang::find($values->id); 
        $property_type_lang->delete();
        }
             
        } // End Delete Property

         //check name exists or not         
          $property_name = PropertyType::where('id','!=',$request->id)->where('name','=',@$request->name[0])->get();        
             if(@$property_name->count() != 0){             
                     $this->helper->flash_message('error', 'This Name already exists'); // Call flash message function
                     return redirect(ADMIN_URL.'/property_type');
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

        //cloudinary upload for property_type icon

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
              return redirect(ADMIN_URL . '/property_type');
            }
            $photos_uploaded[] = $name;
          }

        }
      } else {

        // local upload for property_type icon

        if (isset($_FILES["icons"]["name"]) && $request->icons) {

          $tmp_name = $_FILES["icons"]["tmp_name"];

          $name = str_replace(' ', '_', $_FILES["icons"]["name"]);

          $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

          $name = time() . '.' . $ext;

          $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/property_type';

          if (!file_exists($filename)) {
            mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/property_type', 0777, true);
          }

          if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {

            $file = $request->file('icons');
            $file->move("images/property_type/", $name);

          }

        }
      }   

        //Update Property type
 
                  
        for($i=0;$i < count($request->lang_code);$i++){
         
          if($request->lang_code[$i]=="en"){
          $property_type = PropertyType::find($request->id);
          $property_type->name        = $request->name[$i];
          $property_type->description = $request->description[$i];
          $property_type->status      = $request->status;
          if($name)
          {
            $property_type->icon = $name;
          }
          $property_type->save();

          }
        else{
              if(isset($request->lang_id[$i])){

              $property_type_lang = PropertyTypeLang::find($request->lang_id[$i]);
              $property_type_lang->lang_code   = $request->lang_code[$i];
              $property_type_lang->name        = $request->name[$i];
              $property_type_lang->description = $request->description[$i];
              $property_type_lang->save();            
              } 
              else{

              $property_type_lang =  new PropertyTypeLang; 
              $property_type_lang->property_id   = $request->id;    
              $property_type_lang->lang_code   = $request->lang_code[$i];
              $property_type_lang->name        = $request->name[$i];
              $property_type_lang->description = $request->description[$i];
              $property_type_lang->save();

              }

        }
      }
      // End Update Property

        $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

        return redirect(ADMIN_URL.'/property_type');
          
       
        }
         else
        {
            return redirect(ADMIN_URL.'/property_type');
        }
    }



    //ajax

    public function view_lang(Request $request)
    {

     $propertylang = PropertyTypeLang::where('property_id',$request->id)->get();
        echo $propertylang->toJson();

        }

    /**
     * Delete Property Type
     *
     * @param array $request    Input values
     * @return redirect     to Property Type View
     */
    public function delete(Request $request)
    {
        $count = Rooms::where('property_type', $request->id)->count();
        $property_type_counts=PropertyType::where('status','Active')->count();
        $delete_property_type_counts=PropertyType::whereId($request->id)->where('status','Active')->count();
        if($count > 0)
             $this->helper->flash_message('error', 'Rooms have this Property Type. So, Delete that Rooms or Change that Rooms Property Type.'); // Call flash message function
        else {

             if($property_type_counts < 2)
             {
                if($delete_property_type_counts==1)
                { 
                  $this->helper->flash_message('danger', 'Atleast one Active property type in admin panel. So can\'t delete this'); // Call flash message function
                 return redirect(ADMIN_URL.'/property_type');
                }
             }
            $exists_rnot = PropertyType::find($request->id);
            if(@$exists_rnot){

                  PropertyTypeLang::where('property_id',$request->id)->delete();
                  PropertyType::find($request->id)->delete();
                  $this->helper->flash_message('success', 'Deleted Successfully');

                             }

            else{

            $this->helper->flash_message('error', 'This Property Type Already Deleted.');// Call flash message function
            
                }
        }
        return redirect(ADMIN_URL.'/property_type');
    }
}

