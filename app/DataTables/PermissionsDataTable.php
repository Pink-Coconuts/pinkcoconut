<?php

/**
 * Permissions DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Permissions
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Permission;
use Yajra\Datatables\Services\DataTable;

class PermissionsDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    // protected $exportColumns = ['id', 'name'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $permissions = $this->query();

        return $this->datatables
            ->of($permissions)
            ->addColumn('action', function ($permissions) {
                return '<a href="'.url(ADMIN_URL.'/edit_permission/'.$permissions->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url(ADMIN_URL.'/delete_permission/'.$permissions->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $permissions = Permission::select();

        return $this->applyScopes($permissions);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns([
                'id',
                'name',
                'display_name',
                'description',
            ])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]);
    }
}
