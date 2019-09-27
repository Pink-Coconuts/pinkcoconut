<?php

/**
 * Reservation DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Reservation
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Reservation;
use Yajra\Datatables\Services\DataTable;
use Auth;
use DB;
use Helpers;
class ReservationsDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';

    // protected $exportColumns = [ 'id', 'host_name', 'guest_name','confirmation_code', 'room_name', 'checkin', 'checkout', 'number_of_guests', 'nights', 'subtotal', 'cleaning', 'additional_guest', 'security', 'service', 'total_amount', 'currency_code', 'paymode', 'status', 'created_at', 'updated_at' ];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $reservations = $this->query();

        return $this->datatables
            ->of($reservations)
             ->addColumn('status', function ($reservations) {
                if($reservations->status == 'Pre-Accepted' || $reservations->status == 'Inquiry'){
                    if($reservations->checkin < date("Y-m-d")){
                        return 'Expired';
                    }else{
                        return $reservations->status;
                    }
                }else{
                    return $reservations->status;
                }
            })
            ->addColumn('action', function ($reservations) {
                return '<a href="'.url(ADMIN_URL.'/reservation/detail/'.$reservations->id).'" class="btn btn-xs btn-primary" title="Detail View"><i class="fa fa-share"></i></a>&nbsp;<a href="'.url(ADMIN_URL.'/reservation/conversation/'.$reservations->id).'" class="btn btn-xs btn-primary" title="Conversation"><i class="glyphicon glyphicon-envelope"></i></a>&nbsp;';
            })
            ->addColumn('room_name', function ($reservations) {
                return htmlentities($reservations->room_name);
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
        $reservations = Reservation::join('rooms', function($join) {
                                $join->on('rooms.id', '=', 'reservation.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reservation.user_id');
                            })
                        ->join('currency', function($join) {
                                $join->on('currency.code', '=', 'reservation.currency_code');
                            })
                        ->leftJoin('users as u', function($join) {
                                $join->on('u.id', '=', 'reservation.host_id');
                            })
                        ->select(['reservation.id as id', 'u.first_name as host_name', 'users.first_name as guest_name', 'rooms.name as room_name', DB::raw('CONCAT(currency.symbol, reservation.total) AS total_amount'), 'reservation.status', 'reservation.created_at as created_at','reservation.code as confirmation_code', 'reservation.updated_at as updated_at', 'reservation.checkin', 'reservation.checkout', 'reservation.number_of_guests', 'reservation.host_id', 'reservation.user_id', 'reservation.total', 'reservation.currency_code', 'reservation.service', 'reservation.host_fee','reservation.coupon_code','reservation.coupon_amount','reservation.room_id'])->where('reservation.list_type','Rooms');

        return $this->applyScopes($reservations);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        /*->columns([
            'id',
            'host_name',
            'guest_name',
            'reservation_code',
            'room_name',
            'total_amount',
            'status',
            'created_at',
            'updated_at'
        ])*/
        ->addColumn(['data' => 'id', 'name' => 'reservation.id', 'title' => 'Id'])
        ->addColumn(['data' => 'host_name', 'name' => 'u.first_name', 'title' => 'Host Name'])
        ->addColumn(['data' => 'guest_name', 'name' => 'users.first_name', 'title' => 'Guest Name'])
        ->addColumn(['data' => 'confirmation_code', 'name' => 'reservation.code', 'title' => 'Confirmation Code'])
        ->addColumn(['data' => 'room_name', 'name' => 'rooms.name', 'title' => 'Room Name'])
        ->addColumn(['data' => 'total_amount', 'name' => 'reservation.total', 'title' => 'Total Amount'])
        ->addColumn(['data' => 'status', 'name' => 'reservation.status', 'title' => 'Status'])
        ->addColumn(['data' => 'created_at', 'name' => 'reservation.created_at', 'title' => 'Created At'])
        ->addColumn(['data' => 'updated_at', 'name' => 'reservation.updated_at', 'title' => 'Updated At'])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false,'exportable' => false, 'printable' => false])
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
                        // 'I' => '1',
                        // 'J' => '1',
                        // 'K' => '1',
                        // 'L' => '1',
                        // 'M' => '1',
                        // 'N' => '1',
                        // 'O' => '1',
                        // 'P' => '1',
                        // 'Q' => '1',
                        // 'R' => '1',
                        // 'S' => '1',
                        // 'T' => '1',
                    );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
}
