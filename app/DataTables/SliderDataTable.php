<?php

/**
 * Slider DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Slider
 * @author      Trioangle Product Team
 * @version     0.8
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Slider;
use Yajra\Datatables\Services\DataTable;
use Helpers;
class SliderDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = ['id', 'image','order', 'status'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $slider = $this->query();

        return $this->datatables
            ->of($slider)
            ->addColumn('image', function ($slider) {   
                return '<img src="'.$slider->image_url.'" width="200" height="100">';
            })
            ->editColumn('slider_for', function ($slider) {
                  return $slider->front_end;
           })
            ->addColumn('action', function ($slider) {   
                return '<a href="'.url(ADMIN_URL.'/edit_slider/'.$slider->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url(ADMIN_URL.'/delete_slider/'.$slider->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $slider = Slider::select();

        return $this->applyScopes($slider);
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
            'image',
            'slider_for',
            'order', 
            'status',
        ])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => [],
        ]);
    }
      protected function buildExcelFile()
    {

        $width = array(
                        'A' => '1',
                        'B' => '1',
                        'C' => '1',
                        'D' => '1',
                        
                    );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
}
