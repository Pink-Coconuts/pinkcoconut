<?php

/**
 * Admin Users DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Admin Users
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Admin;
use Yajra\Datatables\Services\DataTable;
use Helpers;
class AdminusersDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = [ 'id', 'username', 'email', 'role_name', 'status', 'created_at', 'updated_at' ];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $admin = $this->query();
       
        return $this->datatables
            ->of($admin)
            ->addColumn('action', function ($admin) {
                return '<a href="'.url(ADMIN_URL.'/edit_admin_user/'.$admin->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url(ADMIN_URL.'/delete_admin_user/'.$admin->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $admin = Admin::join('role_user', function($join) {
                                $join->on('role_user.user_id', '=', 'admin.id');
                            })
                        ->join('roles', function($join) {
                                $join->on('roles.id', '=', 'role_user.role_id');
                            })
                        ->select(['admin.id as id', 'username', 'email', 'roles.display_name as role_name', 'status']);

        return $this->applyScopes($admin);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            /*->columns([
                'id',
                'username',
                'email',
                'role_name',
                'status',
            ])*/
            ->addColumn(['data' => 'id', 'name' => 'admin.id', 'title' => 'Id'])
            ->addColumn(['data' => 'username', 'name' => 'admin.username', 'title' => 'Username'])
            ->addColumn(['data' => 'email', 'name' => 'admin.email', 'title' => 'Email'])
            ->addColumn(['data' => 'role_name', 'name' => 'roles.display_name', 'title' => 'Role Name'])
            ->addColumn(['data' => 'status', 'name' => 'admin.status', 'title' => 'Status'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
            ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
            ]);
    }
    //column alignment 
      protected function buildExcelFile()
    {

        $width = array(
                        'A' => '1',
                        'B' => '2',
                        'C' => '2',
                        'D' => '2',
                        'E' => '2',
                        'F' => '2',
                        'G' => '2',
                    );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
}
