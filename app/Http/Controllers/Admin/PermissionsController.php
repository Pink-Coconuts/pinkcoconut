<?php

/**
 * Permissions Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Permissions
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\PermissionsDataTable;
use App\Models\Permission;
use App\Http\Start\Helpers;

class PermissionsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Permissions
     *
     * @param array $dataTable  Instance of PermissionsDataTable
     * @return datatable
     */
    public function index(PermissionsDataTable $dataTable)
    {
        return $dataTable->render('admin.permissions.view');
    }

    /**
     * Add a New Permission
     *
     * @param array $request  Input values
     * @return redirect     to Permissions view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.permissions.add');
        }
        else if($request->submit)
        {
            $role = new Permission;

            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;

            $role->save();

            return redirect(ADMIN_URL.'/permissions');
        }
        else
        {
            return redirect(ADMIN_URL.'/permissions');
        }
    }

    /**
     * Update Permission Details
     *
     * @param array $request    Input values
     * @return redirect     to Permissions View
     */
    public function update(Request $request)
    {
        if(!$_POST)
        {
            $data['result'] = Permission::find($request->id);

            return view('admin.permissions.edit', $data);
        }
        else if($request->submit)
        {
            $role = Permission::find($request->id);

            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;

            $role->save();

            return redirect(ADMIN_URL.'/permissions');
        }
        else
        {
            return redirect(ADMIN_URL.'/permissions');
        }
    }

    /**
     * Delete Permission
     *
     * @param array $request    Input values
     * @return redirect     to Permissions View
     */
    public function delete(Request $request)
    {
        Permission::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect(ADMIN_URL.'/permissions');
    }
}
