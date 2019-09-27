<?php

/**
 * Payouts DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Payouts
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Payouts;
use Yajra\Datatables\Services\DataTable;
use Auth;
use DB;
use Helpers;

class PayoutsDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';

    // protected $exportColumns = [];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $payouts = $this->query();

        return $this->datatables
            ->of($payouts)
            ->addColumn('listing_name', function ($payouts) {
                return $payouts->reservation->rooms->name;
            })
            ->addColumn('action', function ($payouts) {
                return '<a href="'.url(ADMIN_URL.'/payouts/detail/'.$payouts->id).'" class="btn btn-xs btn-primary" title="Detail View"><i class="fa fa-share"></i></a>';
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
        $payouts = Payouts::where('status', 'future')->with(['users','reservation' => function($query) {
                                    $query->with('currency','rooms');
                                }])->get();

        return $this->applyScopes($payouts);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'Id'])
        ->addColumn(['data' => 'reservation.id', 'name' => 'id', 'title' => 'Reservation Id'])
        ->addColumn(['data' => 'listing_name', 'name' => 'listing_name', 'title' => 'Listing Name'])
        ->addColumn(['data' => 'list_type', 'name' => 'list_type', 'title' => 'Type'])
        ->addColumn(['data' => 'users.first_name', 'name' => 'users.first_name', 'title' => 'User  Name'])
        ->addColumn(['data' => 'currency_code', 'name' => 'currency_code', 'title' => 'Currency Code'])        
        ->addColumn(['data' => 'amount', 'name' => 'amount', 'title' => 'Payout Amount'])
        ->addColumn(['data' => 'status', 'name' => 'status', 'title' => 'Status'])
        ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'])
        ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'exportable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
            'order' => [0, 'desc'],
        ]);
    }

    //column alignment 
    protected function buildExcelFile()
    {

        $width = array(
                        'A' => '5',
                        'B' => '13',
                        'C' => '13',
                        'D' => '10',
                        'E' => '24',
                        'F' => '10',
                        'G' => '5',
                        'H' => '15',
                        'I' => '15',
                        'J' => '15',
                    );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
}
