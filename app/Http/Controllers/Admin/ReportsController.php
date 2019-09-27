<?php

/**
 * Reports Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Reports
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Start\Helpers;
use Excel;
use App\Models\User;
use App\Models\Rooms;
use App\Models\HostExperiences;
use App\Models\Reservation;
use Validator;
use DB;

class ReportsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Reports
     *
     * @return view file
     */
    public function index(Request $request)
    {
        if($request->isMethod('get'))
            return view('admin.reports');
        else {
            $from = date('Y-m-d H:i:s', $this->helper->custom_strtotime($request->from));
            $to = date('Y-m-d H:i:s', $this->helper->custom_strtotime($request->to." 23:59:59"));
            $category = $request->category;

            if($category == '') {
                $result = User::where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();
                
            }
            if($category == 'rooms') {
                $result = Rooms::where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();
                
            }
            if($category == 'reservations') {
                $result = Reservation::where('list_type','Rooms')->where('reservation.created_at', '>=', $from)->where('reservation.created_at', '<=', $to)   ->join('rooms', function($join) {
                                $join->on('rooms.id', '=', 'reservation.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reservation.user_id');
                            })
                        ->join('currency', function($join) {
                                $join->on('currency.code', '=', 'reservation.currency_code');
                            })
                        ->leftJoin('users as u', function($join) {
                                $join->on('u.id', '=', 'reservation.host_id');
                            })
                        ->select(['reservation.id as id', 'u.first_name as host_name', 'users.first_name as guest_name', 'rooms.name as room_name', DB::raw('CONCAT(currency.symbol, reservation.total) AS total_amount'), 'reservation.status', 'reservation.created_at as created_at', 'reservation.updated_at as updated_at', 'reservation.*'])->get();
                
            }
            if($category == 'experience') {
                $result = HostExperiences::where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();
                
            }
            if($category == 'exp_reservations') {
                $result = Reservation::where('list_type','Experiences')->where('type','reservation')->where('reservation.created_at', '>=', $from)->where('reservation.created_at', '<=', $to)   ->join('host_experiences', function($join) {
                                $join->on('host_experiences.id', '=', 'reservation.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reservation.user_id');
                            })
                        ->join('currency', function($join) {
                                $join->on('currency.code', '=', 'reservation.currency_code');
                            })
                        ->leftJoin('users as u', function($join) {
                                $join->on('u.id', '=', 'reservation.host_id');
                            })
                        ->select(['reservation.id as id', 'u.first_name as host_name', 'users.first_name as guest_name', 'host_experiences.title as room_name', DB::raw('CONCAT(currency.symbol, reservation.total) AS total_amount'), 'reservation.status', 'reservation.created_at as created_at', 'reservation.updated_at as updated_at', 'reservation.*'])->get();
                
            }
            $final['from']=$request->from == '' ? 'all' : date('Y-m-d',$this->helper->custom_strtotime($request->from));
            $final['to']=$request->to == '' ? 'all' : date('Y-m-d',$this->helper->custom_strtotime($request->to));
            $final['result']=$result;
            return $final;
        }
    }

    public function export(Request $request)
    {
        $from = $request->from == 'all' ? '' : date('Y-m-d H:i:s', $this->helper->custom_strtotime($request->from));
        $to = $request->to == 'all' ? '' : date('Y-m-d H:i:s', $this->helper->custom_strtotime($request->to." 23:59:59"));
        
        if($request->category == 'users') {            
            $results = User::select('id','first_name','last_name','email','status','created_at as registered_at','created_at','updated_at','languages');

            if($from != '')
                $results =  $results->where('created_at', '>=', $from);
            if($to != '')
                 $results =  $results->where('created_at', '<=', $to);

            $results = $results->get()->toArray();

            foreach($results as $k => $res){
                $result_values = array_except($res, ['languages', 'dob_dmy','age','full_name','languages_name','primary_phone_number_protected', 'primary_phone_number', 'created_at', 'updated_at']);
                $result[$k]['Id'] = @$result_values['id'];
                $result[$k]['First Name'] = @$result_values['first_name'];
                $result[$k]['Last Name'] = @$result_values['last_name'];
                $result[$k]['Email'] = @$result_values['email'];
                $result[$k]['Status'] = @$result_values['status'];
                $result[$k]['Registered At'] = @$result_values['registered_at'];
            }
        }
        if($request->category == 'rooms') {
            $results = Rooms::select('id','name','status','created_at','updated_at','views_count','popular','recommended','property_type','room_type','bed_type','user_id');
            
            if($from != '')
                $results =  $results->where('created_at', '>=', $from);
            if($to != '')
                 $results =  $results->where('created_at', '<=', $to);

            $results = $results->get()->toArray();
            
            foreach($results as $i => $res){
                $result_values = array_except($res, ['property_type','room_type','bed_type','user_id','bed_type_name','photo_name','overall_star_rating','steps_count','reviews_count', 'popular', 'recommended', 'views_count', 'updated_at']);
                $result[$i]['Id'] = @$result_values['id'];
                $result[$i]['Name'] = @$result_values['name'];
                $result[$i]['Host Name'] = @$result_values['host_name'];
                $result[$i]['Property Type'] = @$result_values['property_type_name'];
                $result[$i]['Room Type'] = @$result_values['room_type_name'];
                $result[$i]['Status'] = @$result_values['status'];
                $result[$i]['Created At'] = @$result_values['created_at'];
            }
        }
        if($request->category == 'reservations') {
                $results = Reservation::where('list_type', 'Rooms')->join('rooms', function($join) {
                                $join->on('rooms.id', '=', 'reservation.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reservation.user_id');
                            })
                        ->join('currency', function($join) {
                                $join->on('currency.code', '=', 'reservation.currency_code');
                            })
                        ->leftJoin('users as u', function($join) {
                                $join->on('u.id', '=', 'reservation.host_id');
                            })
                        ->select(['reservation.id as id', 'u.first_name as host_name', 'users.first_name as guest_name', 'rooms.name as room_name', DB::raw('CONCAT(currency.symbol, reservation.total) AS total_amount'), 'reservation.status', 'reservation.created_at as created_at', 'reservation.updated_at as updated_at', 'reservation.*','currency.symbol as currency_symbol']);
                
                if($from != '')
                    $results =  $results->where('reservation.created_at', '>=', $from);
                if($to != '')
                     $results =  $results->where('reservation.created_at', '<=', $to);

                $results = $results->get()->toArray();

                foreach($results as $i => $res){
                    $result_values = array_except($res, ['room_id','host_id','user_id','checkin','checkout','number_of_guests','nights','per_night','subtotal','cleaning','additional_guest','security','service','host_fee','total','coupon_code','coupon_amount','currency_code','transaction_id','paymode','cancellation','first_name','last_name','postal_code','country','type','friends_email','cancelled_by','cancelled_reason','decline_reason','host_remainder_email_sent','special_offer_id','accepted_at','expired_at','declined_at','cancelled_at','date_check','created_at_timer','status_color','receipt_date','dates_subject','checkin_arrive','checkout_depart','guests','host_payout','guest_payout','admin_host_payout','admin_guest_payout','checkin_md','checkout_md','checkin_mdy','checkout_mdy','checkout_mdy','check_total','checkin_site_date_format','checkout_site_date_format','review_end_date','grand_total','room_category','checkinformatted','checkoutformatted','status_language','avablity']);
                    $result[$i]['Id'] = @$result_values['id'];
                    $result[$i]['Host Name'] = @$result_values['host_name'];
                    $result[$i]['Guest Name'] = @$result_values['guest_name'];
                    $result[$i]['Room Name'] = @$result_values['room_name'];
                    $result[$i]['Total Amount'] = html_entity_decode(@$result_values['total_amount'], ENT_COMPAT, 'UTF-8');
                    $result[$i]['Status'] = @$result_values['status'];
                    $result[$i]['Created At'] = @$result_values['created_at'];
                }        
        }
        if($request->category == 'experience') {
            $results = HostExperiences::select('host_experiences.*');
            if($from != '')
                $results =  $results->where('created_at', '>=', $from);
            if($to != '')
                 $results =  $results->where('created_at', '<=', $to);
            
            $results = $results->get()->toArray();

            foreach($results as $i => $res){
                $result_values = array_except($res, ['user_id','photo_name','overall_star_rating','updated_at']);
                $result[$i]['Id'] = @$result_values['id'];
                $result[$i]['Experience Name'] = @$result_values['title'];
                $result[$i]['Host Name'] = @$result_values['host_name'];
                $result[$i]['Status'] = @$result_values['status'];
                $result[$i]['Created At'] = @$result_values['created_at'];
            }
        }
        if($request->category == 'exp_reservations') {
                
                $results = Reservation::where('list_type', 'Experiences')->where('type','reservation')->join('host_experiences', function($join) {
                                $join->on('host_experiences.id', '=', 'reservation.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reservation.user_id');
                            })
                        ->join('currency', function($join) {
                                $join->on('currency.code', '=', 'reservation.currency_code');
                            })
                        ->leftJoin('users as u', function($join) {
                                $join->on('u.id', '=', 'reservation.host_id');
                            })
                        ->select(['reservation.id as id', 'u.first_name as host_name', 'users.first_name as guest_name', 'host_experiences.title as room_name', DB::raw('CONCAT(currency.symbol, reservation.total) AS total_amount'), 'reservation.status', 'reservation.created_at as created_at', 'reservation.updated_at as updated_at', 'reservation.*','currency.symbol as currency_symbol']);
                    
                    if($from != '')
                        $results =  $results->where('reservation.created_at', '>=', $from);
                    if($to != '')
                         $results =  $results->where('reservation.created_at', '<=', $to);

                    $results = $results->get()->toArray();

                    foreach($results as $i => $res){
                    $result_values = array_except($res, ['room_id','host_id','user_id','checkin','checkout','number_of_guests','nights','per_night','subtotal','cleaning','additional_guest','security','service','host_fee','total','coupon_code','coupon_amount','currency_code','transaction_id','paymode','cancellation','first_name','last_name','postal_code','country','type','friends_email','cancelled_by','cancelled_reason','decline_reason','host_remainder_email_sent','special_offer_id','accepted_at','expired_at','declined_at','cancelled_at','date_check','created_at_timer','status_color','receipt_date','dates_subject','checkin_arrive','checkout_depart','guests','host_payout','guest_payout','admin_host_payout','admin_guest_payout','checkin_md','checkout_md','checkin_mdy','checkout_mdy','checkout_mdy','check_total','checkin_site_date_format','checkout_site_date_format','review_end_date','grand_total','room_category','checkinformatted','checkoutformatted','status_language','avablity']);
                    $result[$i]['Id'] = @$result_values['id'];
                    $result[$i]['Host Name'] = @$result_values['host_name'];
                    $result[$i]['Guest Name'] = @$result_values['guest_name'];
                    $result[$i]['Experience Name'] = @$result_values['room_name'];
                    $result[$i]['Total Amount'] = html_entity_decode(@$result_values['total_amount'], ENT_COMPAT, 'UTF-8');
                    $result[$i]['Status'] = @$result_values['status'];
                    $result[$i]['Created At'] = @$result_values['created_at'];
                }        
        }
        
        Excel::create($request->category.'-report', function($excel) use($result) {
            $excel->sheet('sheet1', function($sheet) use($result) {
                $sheet->fromArray($result);
            });
        })->export('csv');
    }
}
