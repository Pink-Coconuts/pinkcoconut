<?php

/**
 * Host Banners DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Host Banners
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Hotelpartners;
use Yajra\Datatables\Services\DataTable;
use Helpers;
class HotelPartnersDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = ['id','name','description','image','link','status'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $hotel_partners = $this->query();

        return $this->datatables
            ->of($hotel_partners)
            ->addColumn('image', function ($hotel_partners) {   
                return '<img src="'.$hotel_partners->image_url.'" width="150" height="70">';
            })
            ->addColumn('action', function ($hotel_partners) {   
                return '<a href="'.url(ADMIN_URL.'/edit_features/'.$hotel_partners->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url(ADMIN_URL.'/delete_features/'.$hotel_partners->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $hotel_partners = Hotelpartners::select();

        return $this->applyScopes($hotel_partners);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html(){
        return $this->builder()
        ->columns(['id','name','description','image','link','status'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => [],
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
