<?php

/**
 * ngofee DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    ngofee
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;


use App\Models\Ngofee;
use Yajra\Datatables\Services\DataTable;
use Helpers;
class NgofeeDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = ['id','name','description','amount','status'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $ngofee = $this->query();

        return $this->datatables
            ->of($ngofee)
            ->addColumn('action', function ($ngofee) {   
                return '<a href="'.url(ADMIN_URL.'/edit_ngofee/'.$ngofee->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url(ADMIN_URL.'/delete_ngofee/'.$ngofee->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $ngofee = Ngofee::select();

        return $this->applyScopes($ngofee);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html(){
        return $this->builder()
        ->columns(['id','name','description','amount','status'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
        ]);
    }

      //column alignment 
    protected function buildExcelFile(){
        $width = array(
                        'A' => '1',
                        'B' => '2',
                        'C' => '2',
                        'D' => '2',
                        'E' => '2',
                        'F' => '2'
                    );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
}
