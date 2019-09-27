<?php

/**
 * HostPenalty DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    HostPenalty
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\HostPenalty;
use Yajra\Datatables\Services\DataTable;
use Auth;
use DB;
use Helpers;
class HostPenaltyDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $host_penalty = $this->query();

        return $this->datatables
            ->of($host_penalty)
            /*->addColumn('action', function ($host_penalty) {
                return '<a href="'.url(ADMIN_URL.'/host_penalty/detail/'.$host_penalty->id).'" class="btn btn-xs btn-primary" title="Detail View"><i class="fa fa-share"></i></a>&nbsp;';
            })*/
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $host_penalty = HostPenalty::join('rooms', function($join) {
                                $join->on('rooms.id', '=', 'host_penalty.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'host_penalty.user_id');
                            })
                        ->join('currency', function($join) {
                                $join->on('currency.code', '=', 'host_penalty.currency_code');
                            })
                        ->select(['host_penalty.id as id', 'rooms.name as room_name', 'users.first_name as host_name', 'host_penalty.reservation_id', DB::raw('CONCAT(currency.symbol, host_penalty.amount) AS host_penalty_amount'), DB::raw('CONCAT(currency.symbol, host_penalty.remain_amount) AS remain_amount'), 'host_penalty.status as status', 'host_penalty.created_at', 'host_penalty.updated_at', 'host_penalty.currency_code']);

        return $this->applyScopes($host_penalty);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        ->addColumn(['data' => 'id', 'name' => 'host_penalty.id', 'title' => 'Id'])
        ->addColumn(['data' => 'host_name', 'name' => 'users.first_name', 'title' => 'Host Name'])
        ->addColumn(['data' => 'room_name', 'name' => 'rooms.name', 'title' => 'Room Name'])
        ->addColumn(['data' => 'reservation_id', 'name' => 'host_penalty.reservation_id', 'title' => 'Reservation Id'])
        ->addColumn(['data' => 'host_penalty_amount', 'name' => 'host_penalty.amount', 'title' => 'Total Amount'])
        ->addColumn(['data' => 'remain_amount', 'name' => 'host_penalty.remain_amount', 'title' => 'Remaining Amount'])
        ->addColumn(['data' => 'status', 'name' => 'host_penalty.status', 'title' => 'Status'])
        ->addColumn(['data' => 'created_at', 'name' => 'host_penalty.created_at', 'title' => 'Created At'])
        ->addColumn(['data' => 'updated_at', 'name' => 'host_penalty.updated_at', 'title' => 'Updated At'])
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
                        'B' => '2',
                        'C' => '2',
                        'D' => '2',
                        'E' => '2',
                        'F' => '2',
                        'G' => '2',
                        'H' => '2',
                        'I' => '2',
                    );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
}
