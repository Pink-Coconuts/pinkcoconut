<?php

/**
 * Host Experience Categories DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Host Experience Categories
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\HostExperienceCategories;
use Yajra\Datatables\Services\DataTable;
use Helpers;

class HostExperienceCategoriesDataTable extends DataTable
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
        $host_experience_category = $this->query();
        $this->base_url = url(ADMIN_URL.'/host_experience_categories');

        return $this->datatables
            ->of($host_experience_category)
            ->addColumn('featured', function ($host_experiences_category) {

                $class = ($host_experiences_category->is_featured == 'No') ? 'danger' : 'success';

                $featured = '<a href="'.url(ADMIN_URL.'/feature_experience_categories/'.$host_experiences_category->id).'" class="btn btn-xs btn-'.$class.'">'.$host_experiences_category->is_featured.'</a>';

                return $featured;
            })
            ->addColumn('action', function ($host_experience_category) {   
                return '<a href="'.$this->base_url.'/edit/'.$host_experience_category->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.$this->base_url.'/delete/'.$host_experience_category->id.'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $host_experience_category = HostExperienceCategories::all();

        return $this->applyScopes($host_experience_category);
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
        ->addColumn(['data' => 'featured', 'name' => 'featured', 'title' => 'Featured'])
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
