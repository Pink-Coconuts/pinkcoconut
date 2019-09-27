<?php

/**
 * Amenities Type Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Amenities Type
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\AmenitiesTypeDataTable;
use App\Models\AmenitiesType;
use App\Models\AmenitiesTypeLang;
use App\Models\Amenities;
use App\Models\language;
use App\Http\Start\Helpers;
use Validator;

class AmenitiesTypeController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Amenities Type
     *
     * @param array $dataTable  Instance of AmenitiesTypeDataTable
     * @return datatable
     */
    public function index(AmenitiesTypeDataTable $dataTable)
    {
        return $dataTable->render('admin.amenities_type.view');
    }

    /**
     * Add a New Amenities Type
     *
     * @param array $request  Input values
     * @return redirect     to Amenities view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {    $data['language'] = Language::translatable()->get();   
            return view('admin.amenities_type.add',$data);
        }
        else if($request->submit)
        {
            $amenities_type = new AmenitiesType;
        

        for($i=0;$i < count($request->lang_code);$i++){
         
        if($request->lang_code[$i]=="en"){
      
        $amenities_type->name        = $request->name[$i];
        $amenities_type->description = $request->description[$i];
        $amenities_type->status      = $request->status;
        $amenities_type->save();
        $lastInsertedId = $amenities_type->id;
        }
        else{
         $amenities_type_lang = new AmenitiesTypeLang;
         $amenities_type_lang->amenities_type_id   = $lastInsertedId;
         $amenities_type_lang->lang_code   = $request->lang_code[$i];
        $amenities_type_lang->name        = $request->name[$i];
        $amenities_type_lang->description = $request->description[$i];
        $amenities_type_lang->save();

        }

        }


                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/amenities_type');
            
        }
        else
        {
            return redirect(ADMIN_URL.'/amenities_type');
        }
    }

    /**
     * Update Amenities Type Details
     *
     * @param array $request    Input values
     * @return redirect     to Amenities View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = AmenitiesType::find($request->id);
            $data['language'] = Language::translatable()->get();           
           $data['langresult'] = AmenitiesTypeLang::where('amenities_type_id',$request->id)->get();  
            return view('admin.amenities_type.edit', $data);
        }
        else if($request->submit)
        {
            
        // Delete Amenities Type

        $lang_id_arr = $request->lang_id;
        unset($lang_id_arr[0]);  

         if(empty($lang_id_arr))
        {
        $amenities_type_lang = AmenitiesTypeLang::where('amenities_type_id',$request->id); 
        $amenities_type_lang->delete();
        }

        $property_del = AmenitiesTypeLang::select('id')->where('amenities_type_id',$request->id)->get();
        foreach($property_del as $values){ 
          if(!in_array($values->id,$lang_id_arr))
        {
        $amenities_type_lang = AmenitiesTypeLang::find($values->id); 
        $amenities_type_lang->delete();
        }
             
        } // End Delete Amenities

        //Update Amenities type
 
                  
        for($i=0;$i < count($request->lang_code);$i++){
         
          if($request->lang_code[$i]=="en"){
          $amenities_type = AmenitiesType::find($request->id);
          $amenities_type->name        = $request->name[$i];
          $amenities_type->description = $request->description[$i];
          $amenities_type->status      = $request->status;
          $amenities_type->save();

          }
        else{
              if(isset($request->lang_id[$i])){

              $amenities_type_lang = AmenitiesTypeLang::find($request->lang_id[$i]);
              $amenities_type_lang->lang_code   = $request->lang_code[$i];
              $amenities_type_lang->name        = $request->name[$i];
              $amenities_type_lang->description = $request->description[$i];
              $amenities_type_lang->save();            
              } 
              else{

              $amenities_type_lang =  new AmenitiesTypeLang; 
              $amenities_type_lang->amenities_type_id   = $request->id;    
              $amenities_type_lang->lang_code   = $request->lang_code[$i];
              $amenities_type_lang->name        = $request->name[$i];
              $amenities_type_lang->description = $request->description[$i];
              $amenities_type_lang->save();

              }

        }
      }
      // End Update Amenities


                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/amenities_type');
            }
        
        else
        {
            return redirect(ADMIN_URL.'/amenities_type');
        }
    }

    /**
     * Delete Amenities Type
     *
     * @param array $request    Input values
     * @return redirect     to Amenities View
     */
    public function delete(Request $request)
    {
        $count = Amenities::where('type_id', $request->id)->count();

        if($count > 0)
            $this->helper->flash_message('error', 'Amenities have this type. So, Delete that Amenities or Change that Amenities Type.'); // Call flash message function
        else {
             AmenitiesTypeLang::where('amenities_type_id',$request->id)->delete();
            AmenitiesType::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect(ADMIN_URL.'/amenities_type');
    }
}
