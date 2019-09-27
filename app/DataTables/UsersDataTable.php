<?php

/**
 * Users DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Users
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\User;
use Yajra\Datatables\Services\DataTable;
use Auth;
use Helpers;
class UsersDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';

    // protected $exportColumns = [ 'id', 'first_name', 'last_name', 'email', 'phone_code' , 'phone_numbers' , 'created_at', 'updated_at','verification_status' ];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $users = $this->query();

        return $this->datatables
            ->of($users)
            ->addColumn('action', function ($users) {

                $edit = (Auth::guard('admin')->user()->can('edit_user')) ? '<a href="'.url(ADMIN_URL.'/edit_user/'.$users->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;' : '';

                $delete = (Auth::guard('admin')->user()->can('delete_user')) ? '<a data-href="'.url(ADMIN_URL.'/delete_user/'.$users->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>' : '';

                return $edit.$delete;
            })
            ->addColumn('phone_numbers', function ($users) {
                $phone_numbers = '';
                foreach($users->users_phone_numbers as $k => $phone_number){
                    if($phone_number->status == 'Confirmed'){
                        $phone_numbers .= $phone_number->phone_number.', ';
                    }
                }
                return trim($phone_numbers, ', ');
            })
            ->addColumn('verification_status', function ($users) {
                $status = $users->original_verification_status;
                $status = $status == 'No'?'--':$status;
                return $status;
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
        $users = User::select('users.id as id', 'users.first_name', 'users.last_name', 'users_phone_numbers.phone_code', 'users_phone_numbers.phone_number', 'users.email', 'users.status','users.created_at','users.updated_at', 'users.verification_status','users.languages')
                    ->leftJoin('users_phone_numbers', function($join) {
                        $join->on('users_phone_numbers.user_id', '=', 'users.id');
                    })->groupBy('id');       
        return $this->applyScopes($users);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        ->addColumn(['data' => 'id', 'name' => 'users.id', 'title' => 'Id'])
        ->addColumn(['data' => 'first_name', 'name' => 'users.first_name', 'title' => 'First Name'])
        ->addColumn(['data' => 'last_name', 'name' => 'users.last_name', 'title' => 'Last Name'])
        ->addColumn(['data' => 'email', 'name' => 'users.email', 'title' => 'Email'])
        ->addColumn(['data' => 'phone_code', 'name' => 'users_phone_numbers.phone_code', 'title' => 'Phone Code'])
        ->addColumn(['data' => 'phone_numbers', 'name' => 'users_phone_numbers.phone_number', 'title' => 'Phone Numbers', 'orderable' => false, 'searchable' => true])
        ->addColumn(['data' => 'status', 'name' => 'users.status', 'title' => 'Status'])
        ->addColumn(['data' => 'created_at', 'name' => 'users.created_at', 'title' => 'Created At'])
        ->addColumn(['data' => 'updated_at', 'name' => 'users.updated_at', 'title' => 'Updated At'])
        ->addColumn(['data' => 'verification_status', 'name' => 'users.verification_status', 'title' => 'ID verification status'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            // 'dom' => 'Bfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
            'order' => [0, 'desc'],
        ]);
    }

      protected function buildExcelFile()
    {

        $width = array(
                        'A' => '1',
                        'B' => '1',
                        'C' => '1',
                        'D' => '1',
                        'E' => '1',
                        'F' => '1',
                        'G' => '1',
                        'H' => '1',
                    );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
}
