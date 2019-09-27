<?php

/**
 * Room Type Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Room Type
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\RoomTypeDataTable;
use App\Models\RoomType;
use App\Models\RoomTypeLang;
use App\Models\Rooms;
use App\Models\language;
use App\Http\Start\Helpers;
use Validator;

class RoomTypeController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Room Type
     *
     * @param array $dataTable  Instance of RoomTypeDataTable
     * @return datatable
     */
    public function index(RoomTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.room_type.view');
    }

    /**
     * Add a New Room Type
     *
     * @param array $request  Input values
     * @return redirect     to Room Type view
     */
    public function add(Request $request)
    {

        if(!$_POST)
        {    $data['language'] = Language::translatable()->get();    
            return view('admin.room_type.add',$data);
        }
        else if($request->submit)
        {
            //check name exists or not           
            $room_type__name = RoomType::where('name','=',@$request->name[0])->get();            
             if(@$room_type__name->count() != 0){             
                     $this->helper->flash_message('error', 'This Name already exists'); // Call flash message function
                     return redirect(ADMIN_URL.'/room_type');
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
        //cloudinary upload for room_type icon

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
              return redirect(ADMIN_URL . '/room_type');
            }
            $photos_uploaded[] = $name;
          }

        }
      } else {

        // local upload for room_type icon

        $icon_name = [];
        if (isset($_FILES["icon"]["name"])) {

          $tmp_name = $_FILES["icon"]["tmp_name"];

          $name = str_replace(' ', '_', $_FILES["icon"]["name"]);

          $icon_name = $name;

          $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

          $name = time() . '.' . $ext;

          $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/room_type';

          if (!file_exists($filename)) {
            mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/room_type', 0777, true);
          }

          if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {

            $file = $request->file('icon');
            $file->move("images/room_type/", $name);

          }

        }
      }
                       
        $room_type = new RoomType;        

        for($i=0;$i < count($request->lang_code);$i++){
         
        if($request->lang_code[$i]=="en"){
      
        $room_type->name        = $request->name[$i];
        $room_type->description = $request->description[$i];
        $room_type->status      = $request->status;
        $room_type->icon =  $name;
        $room_type->is_shared      = $request->is_shared;
        $room_type->save();
        $lastInsertedId = $room_type->id;
        }
        else {
         $room_type_lang = new RoomTypeLang;
         $room_type_lang->room_type_id   = $lastInsertedId;
         $room_type_lang->lang_code   = $request->lang_code[$i];
        $room_type_lang->name        = $request->name[$i];
        $room_type_lang->description = $request->description[$i];
        $room_type_lang->save();

           }

        }

   
    $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

    return redirect(ADMIN_URL.'/room_type');
      }
   
        else
        {
            return redirect(ADMIN_URL.'/room_type');
        }
    }

    /**
     * Update Room Type Details
     *
     * @param array $request    Input values
     * @return redirect     to Room Type View
     */
    public function update(Request $request)
    {
      $ids=RoomType::select('id')->where('id',$request->id)->count();
if($ids==0)
{
 return redirect('404');
  }

else
{
        if(!$_POST)
        {
            
            $data['language'] = Language::get();
            $data['result'] = RoomType::find($request->id);
            $data['langresult'] = RoomTypeLang::where('room_type_id',$request->id)->get();    
            return view('admin.room_type.edit', $data);
        }
        else if($request->submit)
        {

            // Delete Room Type

            $lang_id_arr = $request->lang_id;
            unset($lang_id_arr[0]); 

            if(empty($lang_id_arr))
            {
            $room_type_lang = RoomTypeLang::where('room_type_id',$request->id); 
            $room_type_lang->delete();
            }

            $room_del = RoomTypeLang::select('id')->where('room_type_id',$request->id)->get();
            foreach($room_del as $values){ 
            if(!in_array($values->id,$lang_id_arr))
            {
            $room_type_lang = RoomTypeLang::find($values->id); 
            $room_type_lang->delete();
            }       

            }

            //End Delete Room Type


            //check name exists or not            
            $room_type__name = RoomType::where('id','!=',$request->id)->where('name','=',@$request->name[0])->get();         
             if(@$room_type__name->count() != 0){             
                     $this->helper->flash_message('error', 'This Name already exists'); // Call flash message function
                     return redirect(ADMIN_URL.'/room_type');
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

        //cloudinary upload for room_type icon

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
              return redirect(ADMIN_URL . '/room_type');
            }
            $photos_uploaded[] = $name;
          }

        }
      } else {

        // local upload for room_type icon

        if (isset($_FILES["icons"]["name"]) && $request->icons) {

          $tmp_name = $_FILES["icons"]["tmp_name"];

          $name = str_replace(' ', '_', $_FILES["icons"]["name"]);

          $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

          $name = time() . '.' . $ext;

          $filename = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/room_type';

          if (!file_exists($filename)) {
            mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/room_type', 0777, true);
          }

          if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {

            $file = $request->file('icons');
            $file->move("images/room_type/", $name);

          }

        }
      }

             // update Room type

        for($i=0;$i < count($request->lang_code);$i++){
         
          if($request->lang_code[$i]=="en"){
          $room_type = RoomType::find($request->id);
          $room_type->name        = $request->name[$i];
          $room_type->description = $request->description[$i];
          $room_type->status      = $request->status;
          $room_type->is_shared   = $request->is_shared;
          if($name)
          {
            $room_type->icon = $name;
          }
          $room_type->save();

          }
        else{
              if(isset($request->lang_id[$i])){

              $room_type_lang = RoomTypeLang::find($request->lang_id[$i]);
              $room_type_lang->lang_code   = $request->lang_code[$i];
              $room_type_lang->name        = $request->name[$i];
              $room_type_lang->description = $request->description[$i];
              $room_type_lang->save();            
              } 
              else{

              $room_type_lang =  new RoomTypeLang; 
              $room_type_lang->room_type_id   = $request->id;    
              $room_type_lang->lang_code   = $request->lang_code[$i];
              $room_type_lang->name        = $request->name[$i];
              $room_type_lang->description = $request->description[$i];
              $room_type_lang->save();

              }

        }
      } // End update Room type

            // Atleast one room type should be Active

            $room_type = RoomType::find($request->id);
            $room_status=RoomType::where('id','!=',$request->id)->where('status','Active')->get();          
            $c_status=count($room_status);
            if ($c_status >= "1") {
            $status_room=$request->status; 
            }
            else
            {
            $status_room="Active";  
            }
            $room_type->status=$status_room;
            $room_type->save();   

            Rooms::where('room_type', $request->id)->update(['is_shared' => $request->is_shared]);

            if($c_status == 0)
            {
            $this->helper->flash_message('error', 'Atleast One Roomtype shoud be Active'); 
            }else{
            $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
            }
                return redirect(ADMIN_URL.'/room_type');
         
         }
            
        else
        {
            return redirect(ADMIN_URL.'/room_type');
        }
    }
}


    //for Atleast One RoomType in "Active"...

    public function chck_status($id)
    {
        $room_status=RoomType::where('status','Active')->get();
        if(count($room_status) > "1")
        {
            echo "Active";
            exit;
        }
        else
        {
            echo "InActive";
            exit;
        }
    }

    /**
     * Delete Room Type
     *
     * @param array $request    Input values
     * @return redirect     to Room Type View
     */
    public function delete(Request $request)
    {
        $count = Rooms::where('room_type', $request->id)->count();
        $room_type_counts=RoomType::where('status','Active')->count();
        $delete_room_type_counts=RoomType::whereId($request->id)->where('status','Active')->count();
        if($count > 0)
             $this->helper->flash_message('error', 'Some Rooms have this Room Type. So, Delete that Rooms or Change that Rooms Room Type.'); // Call flash message function
        else {

             if($room_type_counts < 2)
             {
                if($delete_room_type_counts==1)
                { 
                 $this->helper->flash_message('danger', "Atleast one  Room type shoud be Active"); // Call flash message function
                 return redirect(ADMIN_URL.'/room_type');
                }
             }
            RoomTypeLang::where('room_type_id',$request->id)->delete();
            RoomType::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect(ADMIN_URL.'/room_type');
    }
}

