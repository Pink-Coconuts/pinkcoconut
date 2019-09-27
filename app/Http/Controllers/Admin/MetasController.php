<?php

/**
 * Metas Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Metas
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\MetasDataTable;
use App\Models\Language;
use App\Models\Metas;
use App\Models\MetasTranslations;
use App\Http\Start\Helpers;
use Validator;

class MetasController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Metas
     *
     * @param array $dataTable  Instance of MetasDataTable
     * @return datatable
     */
    public function index(MetasDataTable $dataTable)
    {
        return $dataTable->render('admin.metas.view');
    }

    /**
     * Update Meta Details
     *
     * @param array $request    Input values
     * @return redirect     to Metas View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
            $data['languages'] = Language::where('status','Active')->pluck('name', 'value');
			$data['result'] = Metas::find($request->id);
            return view('admin.metas.edit', $data);
        }
        else if($request->submit)
        {
            // Edit Metas Validation Rules
            $rules = array(
                    'title'    => 'required'
                    );

            // Edit Metas Validation Custom Fields Name
            $niceNames = array(
                        'title'    => 'Page Title'
                        );
            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.title'] = 'required';
                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.title'] = 'Page Title';
            }
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                // dd($request->translations);
                $metas = Metas::find($request->id);

			    $metas->title        = $request->title;
			    $metas->description = $request->description;
			    $metas->keywords      = $request->keywords;

                $metas->save();

                $removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $metas->deleteTranslationById($id);
                }
                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $metas->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->title = $translation_data['title'];
                    $translation->description = $translation_data['description'];
                    $translation->keywords = $translation_data['keywords'];

                    $translation->save();
                }
                $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect(ADMIN_URL.'/metas');
            }
        }
        else
        {
            return redirect(ADMIN_URL.'/metas');
        }
    }

    /**
     * Delete Meta
     *
     * @param array $request    Input values
     * @return redirect     to Metas View
     */
    public function delete(Request $request)
    {
        Metas::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect(ADMIN_URL.'/metas');
    }
}
