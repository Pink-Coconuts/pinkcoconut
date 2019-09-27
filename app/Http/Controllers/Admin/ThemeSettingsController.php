<?php

/**
 * Theme Settings Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Theme Settings
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ThemeSettings;
use App\Http\Start\Helpers;
use Validator;

class ThemeSettingsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update Theme Settings Data
     *
     * @return redirect     to theme_settings
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = ThemeSettings::get();

            return view('admin.theme_settings', $data);
        }
        else if($request->submit)
        {
            // Theme Settings Validation Rules
            $rules = array(
                    'body_bg_color' => 'required',
                    'body_font_color' => 'required',
                    'body_font_size' => 'required',
                    'header_bg_color' => 'required',
                    'footer_bg_color' => 'required',
                    'href_color' => 'required',
                    'primary_btn_color' => 'required'
                    );

            // Theme Settings Validation Custom Names
            $niceNames = array(
                        'body_bg_color' => 'Background Color',
                        'body_font_color' => 'Font Color',
                        'body_font_size' => 'Font Size',
                        'header_bg_color' => 'Header Color',
                        'footer_bg_color' => 'Footer Color',
                        'href_color' => 'Link Color',
                        'primary_btn_color' => 'Primary Button Color'
                        );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                ThemeSettings::where(['name' => 'body_bg_color'])->update(['value' => $request->body_bg_color]);
                ThemeSettings::where(['name' => 'body_font_color'])->update(['value' => $request->body_font_color]);
                ThemeSettings::where(['name' => 'body_font_size'])->update(['value' => $request->body_font_size]);
                ThemeSettings::where(['name' => 'header_bg_color'])->update(['value' => $request->header_bg_color]);
                ThemeSettings::where(['name' => 'footer_bg_color'])->update(['value' => $request->footer_bg_color]);
                ThemeSettings::where(['name' => 'href_color'])->update(['value' => $request->href_color]);
                ThemeSettings::where(['name' => 'primary_btn_color'])->update(['value' => $request->primary_btn_color]);

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
            
                return redirect(ADMIN_URL.'/theme_settings');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/theme_settings');
        }
    }
}
