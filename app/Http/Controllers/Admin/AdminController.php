<?php

/**
 * Admin Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Admin
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\Admin;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Rooms;
use App\Models\Slider;
use App\Http\Start\Helpers;

class AdminController extends Controller
{
    protected $helper; // Global variable for instance of Helpers
    
    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Index View for Dashboard
     *
     * @return view index
     */
    public function index()
    {
        $data['users_count'] = User::get()->count();
        $data['reservations_count'] = Reservation::get()->count();
        $data['rooms_count'] = Rooms::get()->count();
        $data['today_users_count'] = User::whereDate('created_at', '=', date('Y-m-d'))->count();
        $data['today_reservations_count'] = Reservation::whereDate('created_at', '=', date('Y-m-d'))->count();
        $data['today_rooms_count'] = Rooms::whereDate('created_at', '=', date('Y-m-d'))->count();

        $chart = Reservation::select(DB::raw('sum(total) as total'),'created_at','status','currency_code', DB::raw("DATE_FORMAT(created_at, '%Y%c') as ym"))->whereYear('created_at', '=', date('Y'))->where('status', 'Accepted')->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))->get();

        $chart_array = [];

        for($month=1;$month<=12;$month++)
        {
            $where_month = date('Y').$month;
            $array['y'] = date('Y').'-'.$month;
            $array['amount'] = $chart->where('ym', $where_month)->sum('total');

            $chart_array[] = $array;
        }

        $data['line_chart_data'] = json_encode($chart_array);
        
        return view('admin.index', $data);
    }

    /**
     * Load Login View
     *
     * @return view login
     */
    public function login()
    {
        if(!session()->has('url.intended'))
        {
            session(['url.intended' => url()->previous()]);
        }
        return view('admin.login');
    }

    public function get() {
        
    $slider = Slider::whereStatus('Active')->orderBy('order', 'asc')->whereFrontEnd('Adminpage')->get(); 
    $rows['succresult'] = $slider->pluck('image_url');
    return json_encode($rows);
     
    }

    /**
     * Login Authentication
     *
     * @param array $request Input values
     * @return redirect     to dashboard
     */
    public function authenticate(Request $request)
    {
        $admin = Admin::where('username', $request->username)->first();
        
        if(@$admin->status != 'Inactive')
        {
            if(Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password]))
            {
                return redirect()->intended(ADMIN_URL.'/dashboard'); // Redirect to dashboard page
            }
            else
            {
                $this->helper->flash_message('danger', 'Log In Failed. Please Check Your Username/Password'); // Call flash message function
                return redirect(ADMIN_URL.'/login'); // Redirect to login page
            }
        }
        else
        {
            $this->helper->flash_message('danger', 'Log In Failed. You are Blocked by Admin.'); // Call flash message function
            return redirect(ADMIN_URL.'/login'); // Redirect to login page
        }
    }

    /**
     * Create a test admin user
     * 
     * @param array $request Input values
     */
    public function create(Request $request)
    {
        $admin = new Admin;

        $admin->username =   "admin";
        $admin->email    =   "admin@gmail.com";
        $admin->password =   bcrypt("admin123");

        $admin->save();
    }

    /**
     * Admin Logout
     */
    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect(ADMIN_URL.'/login');
    }
}
