<?php

/**
 * Dispute DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Dispute
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Currency;
use App\Models\Disputes;
use App\Models\DisputeMessages;
use App\Models\DisputeDocuments;
use Yajra\Datatables\Services\DataTable;
use Auth;
use DB;
use Helpers;
class DisputesDataTable extends DataTable
{
    // protected $exportColumns = [];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $disputes = $this->query();

        return $this->datatables
            ->of($disputes)
            ->addColumn('reservation_id', function ($disputes) {
                return '<a target="_blank" href="'.url(ADMIN_URL.'/reservation/detail/'.$disputes->reservation_id).'" class="" title="Reservation details">'.$disputes->reservation_id.'</a>';
            })
            ->addColumn('user_name', function ($disputes) {
                return $disputes->user_name;
            })
            ->addColumn('action', function ($disputes) {
                return '<a href="'.url(ADMIN_URL.'/dispute/details/'.$disputes->id).'" class="btn btn-xs btn-primary" title="Detail View"><i class="fa fa-share"></i></a>';
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
        $disputes = Disputes::with(['user','reservation'=>function($query){
            $query->with(['rooms']);
        }])->get();
        return $this->applyScopes($disputes);
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
        ->addColumn(['data' => 'reservation_id', 'name' => 'disputes.reservation_id', 'title' => 'Reservation'])
        ->addColumn(['data' => 'user_name', 'name' => 'user_name', 'title' => 'User name'])
        ->addColumn(['data' => 'reservation.rooms.name', 'name' => 'created_at', 'title' => 'Listing Name'])
        ->addColumn(['data' => 'reservation.created_at', 'name' => 'created_at', 'title' => 'Reservation Date'])
        ->addColumn(['data' => 'subject', 'name' => 'subject', 'title' => 'Subject'])
        ->addColumn(['data' => 'amount', 'name' => 'amount', 'title' => 'Amount'])
        ->addColumn(['data' => 'currency_code', 'name' => 'currency_code', 'title' => 'Currency code'])
        ->addColumn(['data' => 'status', 'name' => 'status', 'title' => 'Status'])
        ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'])
        ->addColumn(['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'exportable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            // 'dom' => 'Bfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
            'order' => [0, 'desc'],
        ]);
    }

    //column alignment 
    protected function buildExcelFile()
    {

        $width = array(
            'A' => '1',
            'B' => '1',
            'C' => '1',
            'D' => '1',
            'E' => '1',
            'F' => '1',
            'G' => '1',
            'H' => '1',
            'I' => '1',
            'J' => '1',
            'K' => '1',
            'L' => '1',
            'M' => '1',
            'N' => '1',
            'O' => '1',
            'P' => '1',
            'Q' => '1',
            'R' => '1',
            'S' => '1',
            'T' => '1',
        );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
}
