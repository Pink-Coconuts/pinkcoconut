<?php

/**
 * Coupon Code Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Coupon Code
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\CouponCodeDataTable;
use App\Models\CouponCode;
use App\Models\Currency;
use App\Models\Reservation;
use App\Http\Start\Helpers;
use Validator;

class CouponCodeController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Coupon Code
     *
     * @param array $dataTable  Instance of CouponCodeDataTable
     * @return datatable
     */
    public function index(CouponCodeDataTable $dataTable)
    {
        return $dataTable->render('admin.coupon_code.view');
    }

    /**
     * Add a New Coupon Code
     *
     * @param array $request  Input values
     * @return redirect     to Coupon Code view
     */
    public function add(Request $request)
    {
        $data['currency'] = Currency::where('status','Active')->pluck('code', 'id');
        $data['coupon_currency'] = Currency::where('default_currency','1')->first()->id;

        if(!$_POST)
        {
            return view('admin.coupon_code.add',$data);
        }
        else if($request->submit)
        {
            $rules = array(
                    'coupon_code'   => 'required|regex:/(^[A-Za-z0-9 ]+$)+/|min:4|max:12|unique:coupon_code',
                    'amount'        => 'required|numeric',
                    'expired_at'    => 'required',
                    'status'        => 'required'
                    );
            
            $niceNames = array(
                        'coupon_code'   => 'Coupon Code',
                        'amount'        => 'Amount',
                        'expired_at'    => 'Expired Date',
                        'status'        => 'Status'
                        );

            $message=array(
                    'coupon_code.regex' =>'Special Characters not allowed.'
                );

            $validator = Validator::make($request->all(), $rules,$message);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {   
                $currency_code = Currency::where('id',$request->coupon_currency)->first()->code;

                $coupon = new CouponCode;

                $coupon->coupon_code    = $request->coupon_code;
                $coupon->amount         = $request->amount;
                $coupon->expired_at     = date('Y-m-d', $this->helper->custom_strtotime($request->expired_at));
                $coupon->currency_code  = $currency_code;
                $coupon->status         = $request->status;

                $coupon->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/coupon_code');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/coupon_code');
        }
    }

    /**
     * Update Coupon Code Details
     *
     * @param array $request    Input values
     * @return redirect     to Coupon Code View
     */
    public function update(Request $request)
    {   
        $data['result'] = CouponCode::find($request->id);
    
        if(!$data['result'])
        {
            abort(404);
        }
        $data['currency']   = Currency::where('status','Active')->pluck('code', 'id');

        $data['coupon_currency'] = Currency::where('code',$data['result']->currency_code)->first()->id;


        if(!$_POST)
        {

            return view('admin.coupon_code.edit', $data);
        }
        else if($request->submit)
        {
            $rules = array(
                    'coupon_code'   => 'required|regex:/(^[A-Za-z0-9 ]+$)+/|min:4|max:12|unique:coupon_code,coupon_code,'.$request->id,
                    'amount'        => 'required|numeric',
                    'expired_at'    => 'required',
                    'status'        => 'required'
                    );

            $niceNames = array(
                        'coupon_code'   => 'Coupon Code',
                        'amount'        => 'Amount',
                        'expired_at'    => 'Expired Date',
                        'status'        => 'Status'
                        );

            $message=array(
                    'coupon_code.regex' =>'Special Characters not allowed.'
                );

            $validator = Validator::make($request->all(), $rules,$message);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {   
                $currency_code = Currency::where('id',$request->coupon_currency)->first()->code;

                $coupon = CouponCode::find($request->id);

                $coupon->coupon_code    = $request->coupon_code;
                $coupon->amount         = $request->amount;
                $coupon->expired_at     = date('Y-m-d', $this->helper->custom_strtotime($request->expired_at));
                $coupon->currency_code  = $currency_code;
                $coupon->status         = $request->status;

                $coupon->save();

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/coupon_code');

            }
        }
        else
        {
            return redirect(ADMIN_URL.'/coupon_code');
        }
    }

    /**
     * Delete Coupon Code
     *
     * @param array $request    Input values
     * @return redirect     to Coupon Code View
     */
    public function delete(Request $request)
    {
        $coupon_code = CouponCode::find($request->id)->coupon_code;

        $count = Reservation::where('coupon_code', $coupon_code)->count();

        if($count > 0)
            $this->helper->flash_message('error', 'Reservation have this coupon code. So, Delete that Reservation or Change that Reservation coupon code.'); // Call flash message function
        else {
            CouponCode::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect(ADMIN_URL.'/coupon_code');
    }
}
