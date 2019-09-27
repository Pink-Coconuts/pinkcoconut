<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Ngofee;
use App\Http\Controllers\Controller;
use App\DataTables\NgofeeDataTable;
use Validator;
use App\Http\Start\Helpers;
use App\Models\Currency;

class NgofeeController extends Controller{
    //

    protected $helper;  // Global variable for instance of Helpers

    public function __construct(){
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Country
     *
     * @param array $dataTable  Instance of NgofeeDataTable
     * @return datatable
     */
    public function index(NgofeeDataTable $dataTable){
    	return $dataTable->render('admin.ngofee.view');
    }

    public function add_ngo(){
    	$data['currency'] = Currency::where('status','Active')->pluck('code', 'id');
    	return view('admin.ngofee.add',$data);
    }

    public function insert_ngofee(Request $request){

            $rules = array(
                    'name' => 'required',
                    'description'  => 'required',
                    'amount' => 'required|numeric'
                    );

            // Edit Country Validation Custom Fields Name
            $niceNames = array(
                        'name' => 'Ngo Name',
                        'description'  => 'Description',
                        'amount' => 'Donation Amount'
                    );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput(); 
            }else{

                $ngofee = new Ngofee;
                $ngofee->name = $request->name;
                $ngofee->description  = $request->description;
                $ngofee->amount  = $request->amount;
                $ngofee->currency  = $request->currency;
                $ngofee->status  = $request->status == '0' ? 'active' : 'inactive';
                $ngofee->save();

                $this->helper->flash_message('success', 'Added Successfully'); 
                return redirect(ADMIN_URL.'/ngofees');
            }
    }

    
    public function edit_ngo(Request $request){
    	
    	$id = $request->id;
    	$data['result'] = Ngofee::find($id);
    	if(!$data['result'])
        abort('404');
    	$data['currency'] = Currency::where('status','Active')->pluck('code', 'id');
    	$data['status'] = $data['result']->status == 'active' ? '0' : '1'; 
    	
        return view('admin.ngofee.edit', $data);
   	}

   	public function update_ngo(Request $request){
   	  		
   	  		$rules = array(
                    'name' => 'required',
                    'description'  => 'required',
                    'amount' => 'required|numeric'
                    );

            // Edit Country Validation Custom Fields Name
            $niceNames = array(
                        'name' => 'Ngo Name',
                        'description'  => 'Description',
                        'amount' => 'Amount'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput(); 
            }else{
            	$ngofee = Ngofee::find($request->id);
	            $ngofee->name = $request->name;
	            $ngofee->description  = $request->description;
	            $ngofee->amount  = $request->amount;
	            $ngofee->currency  = $request->currency;
	            $ngofee->status  = $request->status == '0' ? 'active' : 'inactive';
           		$ngofee->save();

                $this->helper->flash_message('success', 'Updated Successfully'); 
                return redirect(ADMIN_URL.'/ngofees');
            }
   	}

   	public function delete_ngofee(Request $request){
         $this->helper->flash_message('error', 'The Ngo Fee has already used by some users. So you cannot delete it');
         return redirect(ADMIN_URL.'/ngofees');
   	}

}
