<?php

/**
 * Our Community Banners DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Our Community Banners
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\OurCommunityBanners;
use Yajra\Datatables\Services\DataTable;
use Helpers;
class OurCommunityBannersDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    protected $exportColumns = ['id', 'title', 'description','image'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $our_community_banners = $this->query();

        return $this->datatables
            ->of($our_community_banners)
            ->addColumn('image', function ($our_community_banners) {   
                return '<img src="'.$our_community_banners->image_url.'" width="200" height="100">';
            })
            ->addColumn('action', function ($our_community_banners) {   
                return '<a href="'.url(ADMIN_URL.'/edit_our_community_banners/'.$our_community_banners->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;<a data-href="'.url(ADMIN_URL.'/delete_our_community_banners/'.$our_community_banners->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
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
        $our_community_banners = OurCommunityBanners::select();

        return $this->applyScopes($our_community_banners);
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
            'title',
            'description',
            'image',
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
