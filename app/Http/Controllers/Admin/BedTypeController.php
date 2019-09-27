<?php

/**
 * Bed Type Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Bed Type
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\BedTypeDataTable;
use App\Models\BedType;
use App\Models\BedTypeLang;
use App\Models\language;
use App\Models\Rooms;
use App\Http\Start\Helpers;
use Validator;

class BedTypeController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Bed Type
     *
     * @param array $dataTable  Instance of BedTypeDataTable
     * @return datatable
     */
    public function index(BedTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.bed_type.view');
    }

    /**
     * Add a New Bed Type
     *
     * @param array $request  Input values
     * @return redirect     to Bed Type view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {   $data['language'] = Language::translatable()->get(); 
            return view('admin.bed_type.add',$data);
        }
        else if($request->submit)
        {
            //check name exists or not            
             $bed_type_name = BedType::where('name','=',@$request->name[0])->get();            
             if(@$bed_type_name->count() != 0){             
                     $this->helper->flash_message('error', 'This Name already exists'); // Call flash message function
                     return redirect(ADMIN_URL.'/bed_type');
                }    

            $bed_type = new BedType;
        

        for($i=0;$i < count($request->lang_code);$i++){
         
        if($request->lang_code[$i]=="en"){
      
        $bed_type->name        = $request->name[$i];
        $bed_type->status      = $request->status;
        $bed_type->save();
        $lastInsertedId = $bed_type->id;
        }
        else{
         $bed_type_lang = new BedTypeLang;
         $bed_type_lang->bed_type_id   = $lastInsertedId;
         $bed_type_lang->lang_code   = $request->lang_code[$i];
         $bed_type_lang->name        = $request->name[$i];      
         $bed_type_lang->save();

        }

        }
                

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/bed_type');
            
        }
        else
        {
            return redirect(ADMIN_URL.'/bed_type');
        }
    }

    /**
     * Update Bed Type Details
     *
     * @param array $request    Input values
     * @return redirect     to Bed Type View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {   $data['language'] = Language::get();  
            $data['result'] = BedType::find($request->id);
            $data['langresult'] = BedTypeLang::where('bed_type_id',$request->id)->get();    
            return view('admin.bed_type.edit', $data);
        }
        else if($request->submit)
        {
           

            // Delete Bed Type

            $lang_id_arr = $request->lang_id;
            unset($lang_id_arr[0]); 

            if(empty($lang_id_arr))
            {
            $bed_type_lang = BedTypeLang::where('bed_type_id',$request->id); 
            $bed_type_lang->delete();
            }

            $room_del = BedTypeLang::select('id')->where('bed_type_id',$request->id)->get();
            foreach($room_del as $values){ 
            if(!in_array($values->id,$lang_id_arr))
            {
            $bed_type_lang = BedTypeLang::find($values->id); 
            $bed_type_lang->delete();
            }       

            }

            //End Delete Bed Type
            //check name exists or not            
             $bed_type_name = BedType::where('id','!=',@$request->id)->where('name','=',@$request->name[0])->get();            
             if(@$bed_type_name->count() != 0){             
                     $this->helper->flash_message('error', 'This Name already exists'); // Call flash message function
                     return redirect(ADMIN_URL.'/bed_type');
                }  
             // update Bed type

        for($i=0;$i < count($request->lang_code);$i++){
         
          if($request->lang_code[$i]=="en"){
          $bed_type = BedType::find($request->id);
          $bed_type->name        = $request->name[$i];        
          $bed_type->status      = $request->status;
          $bed_type->save();

          }
        else{
              if(isset($request->lang_id[$i])){

              $bed_type_lang = BedTypeLang::find($request->lang_id[$i]);
              $bed_type_lang->lang_code   = $request->lang_code[$i];
              $bed_type_lang->name        = $request->name[$i];            
              $bed_type_lang->save();            
              } 
              else{

              $bed_type_lang =  new BedTypeLang; 
              $bed_type_lang->bed_type_id   = $request->id;    
              $bed_type_lang->lang_code   = $request->lang_code[$i];
              $bed_type_lang->name        = $request->name[$i];              
              $bed_type_lang->save();

              }

        }
      } // End update Bed type
                    
               $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
                       
                return redirect(ADMIN_URL.'/bed_type');
        }
       
        else
        {
            return redirect(ADMIN_URL.'/bed_type');
        }
    }

    /**
     * Delete Bed Type
     *
     * @param array $request    Input values
     * @return redirect     to Bed Type View
     */
    public function delete(Request $request)
    {
        $count = Rooms::where('bed_type', $request->id)->count();

        if($count > 0)
            $this->helper->flash_message('error','Rooms have this Bed Type.'); // Call flash message function
        else {            
            $bedtype = BedType::where('id','!=',$request->id)->where('status','Active')->get();
            if(count($bedtype) == 0)
                  {                   
                       $this->helper->flash_message('error', 'Atleast One Bedtype shoud be Active.'); 
                   }else{ 
                   $exists_rnot = BedType::find($request->id);
                          if(@$exists_rnot){

                              BedTypeLang::where('bed_type_id',$request->id)->delete();                   
                              BedType::find($request->id)->delete();
                              $this->helper->flash_message('success', 'Deleted Successfully.'); 

                          }
                      else{

                        $this->helper->flash_message('error', 'This Bed Type Already Deleted.');

                          }
           
                      }
        }
        return redirect(ADMIN_URL.'/bed_type');
    }

    //for Atleast One Bed in "Active"...

    public function chck_status($id)
    {
        $bedstatus=BedType::where('status','Active')->get();
        if(count($bedstatus) > 1)
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

}
