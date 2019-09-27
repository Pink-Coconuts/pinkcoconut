<?php

/**
 * Host Experience Provide Items DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Host Experience Provide Items
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\HostExperienceProvideItems;
use Yajra\Datatables\Services\DataTable;
use Helpers;

class HostExperienceProvideItemsDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = ['id', 'name', 'status'];
    protected $base_url;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $host_experience_provide_items = $this->query();
        $this->base_url = url(ADMIN_URL.'/host_experience_provide_items');

        return $this->datatables
            ->of($host_experience_provide_items)
            ->addColumn('image', function ($host_experience_provide_items) {   
                if($host_experience_provide_items->image_url)
                {
                    return '<img src="'.$host_experience_provide_items->image_url.'" style="width:16px !important; height:19px !important;" />';
                }
            })
            ->addColumn('action', function ($host_experience_provide_items) {   
                return '<a href="'.$this->base_url.'/edit/'.$host_experience_provide_items->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.$this->base_url.'/delete/'.$host_experience_provide_items->id.'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $host_experience_provide_items = HostExperienceProvideItems::get();

        return $this->applyScopes($host_experience_provide_items);
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
            'status'
        ])
        ->addColumn(['data' => 'image', 'name' => 'image', 'title' => 'Image', 'orderable' => false, 'searchable' => false])
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
            'A' => '20',
            'B' => '45',
            'C' => '45',
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
