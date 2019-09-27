<?php

/**
 * HostPenalty Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    HostPenalty
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HostPenaltyDataTable;
use App\Models\Reservation;
use App\Http\Start\Helpers;

class HostPenaltyController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for HostPenalty
     *
     * @param array $dataTable  Instance of HostPenaltyDataTable
     * @return datatable
     */
    public function index(HostPenaltyDataTable $dataTable)
    {
        return $dataTable->render('admin.reservations.host_penalty_view');
    }

}
