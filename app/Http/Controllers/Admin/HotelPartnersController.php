<?php

/**
 * Hotel Partners Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Hotel Partners
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HotelPartnersDataTable;
use App\Models\Hotelpartners;
use App\Models\Language;
use App\Http\Start\Helpers;
use Validator;

class HotelPartnersController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Hotel Partners
     *
     * @param array $dataTable  Instance of HotelPartnersDataTable
     * @return datatable
     */


    public function index(HotelPartnersDataTable $dataTable)
    {
        return $dataTable->render('admin.hotel_partners.view');
    }

    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.hotel_partners.add');
        }
        else if($request->submit)
        {
            //dd($request->all()); 
            // Add Host Banners Validation Rules
            $rules = array(
                    'name'         => 'required',
                    'description'   => 'required',                     
                    'link'          => 'required|url',
                    'image'   => 'required|mimes:jpg,png,gif,jpeg'
                    );

            // Add Host Banners Validation Custom Names
            $niceNames = array(
                        'name'         => 'Name',
                        'description'   => 'Description', 
                        'link'          => 'Link',
                        'image'         => 'Image'
                        );
            
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }else{
                
                $image     =   $request->file('image');
                $extension =   $image->getClientOriginalExtension();
                $filename  =   'hotel_partners_'.time() . '.' . $extension;
                $success = $image->move('images/hotel_partners', $filename);
                if(!$success)
                return back()->withError('Could not upload Image');

                $hotel_partners = new Hotelpartners;
                $hotel_partners->name  = $request->name;
                $hotel_partners->description  = $request->description;
                $hotel_partners->image = $filename;
                $hotel_partners->link  = $request->link;
                $hotel_partners->status  = ($request->status == 0) ? 'Active' : 'Inactive';

                $hotel_partners->save();

                $this->helper->flash_message('success', 'Added Successfully'); 
                return redirect(ADMIN_URL.'/features');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/features');
        }
    }


    public function update(Request $request)
    {
        if(!$_POST){
            $data['result'] = Hotelpartners::find($request->id);
            $data['status'] = ($data['result']->status =='Active') ? '0' : '1'; 
            return view('admin.hotel_partners.edit',$data);
        }
        else if($request->submit)
        {
            //dd($request->all()); 
            // Add Host Banners Validation Rules
            $rules = array(
                    'name'         => 'required',
                    'description'   => 'required',                     
                    'link'          => 'required|url',
                    'image'   => 'mimes:jpg,png,gif,jpeg'
            );

            // Add Host Banners Validation Custom Names
            $niceNames = array(
                        'name'         => 'Name',
                        'description'   => 'Description', 
                        'link'          => 'Link',
                        );
            
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }else{
                
                $hotel_partners = Hotelpartners::find($request->id);;
                $image     =   $request->file('image');
                if($image){
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'hotel_partners_'.time() . '.' . $extension;
                    $success = $image->move('images/hotel_partners', $filename);
                    if(!$success)
                    return back()->withError('Could not upload Image');
                    $hotel_partners->image = $filename;
                }
                
                $hotel_partners->name  = $request->name;
                $hotel_partners->description  = $request->description;
                $hotel_partners->link  = $request->link;
                $hotel_partners->status  = ($request->status == 0) ? 'Active' : 'Inactive';

                $hotel_partners->save();

                $this->helper->flash_message('success', 'Added Successfully'); 
                return redirect(ADMIN_URL.'/features');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/features');
        }
    }


    public function delete(Request $request){
        $banner = Hotelpartners::find($request->id);
        if($banner != ''){
            Hotelpartners::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function    
        }
        return redirect(ADMIN_URL.'/features');
    }
}

