<?php

/**
 * Host Experience Cities DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Host Experience Cities
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\HostExperienceCities;
use Yajra\Datatables\Services\DataTable;
use Helpers;

class HostExperienceCitiesDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    // protected $exportColumns = ['id', 'name', 'status'];
    protected $base_url;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $host_experience_city = $this->query();
        $this->base_url = url(ADMIN_URL.'/host_experience_cities');

        return $this->datatables
            ->of($host_experience_city)
            ->addColumn('timezone_name', function ($host_experience_city) {   
                return $host_experience_city->timezone_details->name;
            })
            ->addColumn('action', function ($host_experience_city) {   
                return '<a href="'.$this->base_url.'/edit/'.$host_experience_city->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.$this->base_url.'/delete/'.$host_experience_city->id.'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $host_experience_city = HostExperienceCities::with(['timezone_details'])->get();

        return $this->applyScopes($host_experience_city);
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
        ])
        ->addColumn(['data' => 'timezone_name', 'name' => 'timezone_name', 'title' => 'Timezone'])
        ->columns([
            'currency_code',
            'status'
        ])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'exportable' => false, 'printable' =>false])
        ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
        ]);
    }
    
    //column alignment 
    protected function buildExcelFile()
    {
        $width = array(
            'A' => '10',
            'B' => '35',
            'C' => '35',
            'D' => '15',
            'E' => '15',
        );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
    
    public function printPreview()
    {
        $data_print = $this->getDataForPrint();
        $view = $this->printPreview ?: 'datatables::print';

        $data = array();
        foreach($data_print as $k => $v)
        {
            foreach ($v as $key => $value) {
                $data[$k][$key] = (string)$value;
            }
        }

        return $this->viewFactory->make($view, compact('data'));
    }
}
