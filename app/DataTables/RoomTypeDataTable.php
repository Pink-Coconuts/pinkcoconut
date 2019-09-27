<?php

/**
 * Room Type DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Room Type
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\RoomType;
use Yajra\Datatables\Services\DataTable;
use Helpers;

class RoomTypeDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = ['id', 'name', 'description', 'status'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $room_type = $this->query();

        return $this->datatables
            ->of($room_type)
            ->addColumn('action', function ($room_type) {   
                return '<a href="'.url(ADMIN_URL.'/edit_room_type/'.$room_type->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url(ADMIN_URL.'/delete_room_type/'.$room_type->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $room_type = RoomType::select();

        return $this->applyScopes($room_type);
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
            'description',
            'status'
        ])
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
                    );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
}
