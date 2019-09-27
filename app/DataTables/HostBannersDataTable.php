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

use App\Models\HostBanners;
use Yajra\Datatables\Services\DataTable;
use Helpers;
class HostBannersDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = ['id', 'image', 'title', 'description'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $host_banners = $this->query();

        return $this->datatables
            ->of($host_banners)
            ->addColumn('image', function ($host_banners) {   
                return '<img src="'.$host_banners->image_url.'" width="200" height="100">';
            })
            ->addColumn('action', function ($host_banners) {   
                return '<a href="'.url(ADMIN_URL.'/edit_host_banners/'.$host_banners->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url(ADMIN_URL.'/delete_host_banners/'.$host_banners->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $host_banners = HostBanners::all();

        return $this->applyScopes($host_banners);
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
            'title',
            'description',
            'link_title', 
            'link',
        ])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => [],
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
