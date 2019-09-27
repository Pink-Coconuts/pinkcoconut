<?php

/**
 * Pages Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Pages
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\PagesDataTable;
use App\Models\Pages;
use App\Models\Language;
use App\Http\Start\Helpers;
use Illuminate\Support\Facades\Input;
use Validator;

class PagesController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Pages
     *
     * @param array $dataTable  Instance of PagesDataTable
     * @return datatable
     */
    public function index(PagesDataTable $dataTable)
    {
        return $dataTable->render('admin.pages.view');
    }

    /**
     * Add a New Page
     *
     * @param array $request  Input values
     * @return redirect     to Pages view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            $data['languages'] = Language::translatable()->pluck('name', 'value');
            return view('admin.pages.add', $data);
        }
        else if($request->submit)
        {
            // Add Page Validation Rules
            $rules = array(
                    'name'    => 'required|unique:pages',
                    'content' => 'required',
                    'footer'  => 'required',
                    'status'  => 'required'
                    );

            if($request->footer == 'yes')
                $rules['under'] = 'required';

            // Add Page Validation Custom Names
            $niceNames = array(
                        'name'    => 'Name',
                        'content' => 'Content',
                        'footer'  => 'Footer',
                        'under'   => 'Under',
                        'status'  => 'Status'
                        );
            $except = array('content');
            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
                $rules['translations.'.$k.'.content'] = 'required';

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Name';
                $niceNames['translations.'.$k.'.content'] = 'Content';
                $except[] = 'translations.'.$k.'.content';
            }
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(Input::except($except)); // Form calling with Errors and Input values
            }
            else
            {
                $pages = new Pages;

                $pages->name    = $request->name;
                $pages->url     = str_slug($request->name, '_');
                $pages->content = $request->content;
                $pages->footer  = $request->footer;
                $pages->under   = $request->under;
                $pages->status  = $request->status;

                $pages->save();

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $pages->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->name = $translation_data['name'];
                    $translation->content = $translation_data['content'];

                    $translation->save();
                }

                $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect(ADMIN_URL.'/pages');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/pages');
        }
    }

    /**
     * Update Page Details
     *
     * @param array $request    Input values
     * @return redirect     to Pages View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
			$data['result'] = Pages::find($request->id);
            $data['languages'] = Language::translatable()->pluck('name', 'value');
            return view('admin.pages.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Page Validation Rules
            $rules = array(
                    'name'    => 'required|unique:pages,name,'.$request->id,
                    'content' => 'required',
                    'footer'  => 'required',
                    'status'  => 'required'
                    );

            // Edit Page Validation Custom Fields Name
            $niceNames = array(
                        'name'    => 'Name',
                        'content' => 'Content',
                        'footer'  => 'Footer',
                        'under'   => 'Under',
                        'status'  => 'Status'
                        );
            $except = array('content');
            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
                $rules['translations.'.$k.'.content'] = 'required';

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Name';
                $niceNames['translations.'.$k.'.content'] = 'Content';
                $except[] = 'translations.'.$k.'.content';
            }
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(Input::except($except)); // Form calling with Errors and Input values
            }
            else
            {
                $pages = Pages::find($request->id);

                $pages->name    = $request->name;
                $pages->url     = str_slug($request->name, '_');
                $pages->content = $request->content;
                $pages->footer  = $request->footer;
                $pages->under   = $request->under;
                $pages->status  = $request->status;

                $pages->save();

                $removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $pages->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $pages->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->name = $translation_data['name'];
                    $translation->content = $translation_data['content'];

                    $translation->save();
                }

                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/pages');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/pages');
        }
    }

    /**
     * Delete Page
     *
     * @param array $request    Input values
     * @return redirect     to Pages View
     */
    public function delete(Request $request)
    {
        $page = Pages::find($request->id);
        if(!is_null($page)) {
            Pages::find($request->id)->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        else {
            // Call flash message function
            $this->helper->flash_message('warning', 'Already Deleted');
        }

        return redirect(ADMIN_URL.'/pages');
    }

    public function chck_status($id)
    {
        $pagestatus=Pages::where('status','Active')->where(function ($query) {
                $query->where('under','company');
                      })->get();
        if(count($pagestatus) > 1)
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
