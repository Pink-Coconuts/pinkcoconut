<?php

/**
 * Amenities Type DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Amenities Type
 * @author      Trioangle Product Team
 * @version     0.7.1
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Referrals;
use Yajra\Datatables\Services\DataTable;
use DB;

class ReferralsDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    // protected $exportColumns = ['id', 'name', 'description', 'status'];

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $referrals = $this->query();
        
        return $this->datatables
            ->of($referrals)
            ->addColumn('action', function ($referrals) {   
                return '<a href="'.url(ADMIN_URL.'/referral_details/'.$referrals->user_id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye"></i>Details</a>';
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
        $referrals = Referrals::join('users', function($join) {
                                $join->on('users.id', '=', 'referrals.user_id');
                            })->selectRaw('referrals.user_id, CONCAT(users.first_name, " ", users.last_name) as full_name, referrals.friend_id, (select count(*) from referrals as r1 where user_id = referrals.user_id) as signup_count, (select count(*) from reservation as re1 join referrals as jr on jr.friend_id = re1.user_id where jr.user_id = referrals.user_id) as booking_count, (select count(*) from rooms as rm1 join referrals as jr on jr.friend_id = rm1.user_id where jr.user_id = referrals.user_id) as listing_count')->groupBy('referrals.user_id');
        return $this->applyScopes($referrals);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
        // ->columns([
        //     'id',
        //     'referrer_name',
        //     'signup_count',
        //     'booking_count',
        //     'listing_count',
        // ])
        ->addColumn(['data' => 'user_id', 'name' => 'referrals.user_id', 'title' => 'Id'])
        ->addColumn(['data' => 'full_name', 'name' => 'first_name', 'title' => 'Referrer Name'])
        ->addColumn(['data' => 'signup_count', 'name' => 'signup_count', 'title' => 'Signup Count', 'searchable' => false])
        ->addColumn(['data' => 'booking_count', 'name' => 'booking_count', 'title' => 'Booking Count', 'searchable' => false])
        ->addColumn(['data' => 'listing_count', 'name' => 'listing_count', 'title' => 'Listing Count', 'searchable' => false])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
        ->parameters([
            'dom' => 'lBfrtip',
            'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset'],
        ]);
    }
}
