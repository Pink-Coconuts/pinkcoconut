<?php

/**
 * Host Experiences DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Host Experiences
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\HostExperiences;
use Yajra\Datatables\Services\DataTable;
use Helpers;
use Auth;

class HostExperiencesDataTable extends DataTable
{
    // protected $printPreview = 'path-to-print-preview-view';
    
    // protected $exportColumns = ['id', 'title', 'host_name', 'city', 'category', 'status', 'created_at', 'updated_at'];
    protected $base_url;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $host_experiences = $this->query();
        $this->base_url = url(ADMIN_URL.'/host_experiences');

        return $this->datatables
            ->of($host_experiences)
            ->addColumn('host_name', function ($host_experiences) {   
                return $host_experiences->user->first_name;   
            })
            ->addColumn('city', function ($host_experiences) {   
                return @$host_experiences->city_details->name;
            })
            ->addColumn('category', function ($host_experiences) {   
                return @$host_experiences->category_details->name;
            })
            ->addColumn('admin_status_options', function ($host_experiences) {
                $admin_status = '<form action="'.url(ADMIN_URL).'/update_hostexperience_status" method="post" name="admin_status_'.$host_experiences->id.'">
                <input type="hidden" name="id" value="'.$host_experiences->id.'">
                <select name="admin_status" onchange="this.form.submit()">';
                $pending_status=($host_experiences->admin_status=="Pending") ? $pending_status ="selected" : $pending_status ="";
                $admin_status .='<option value="Pending" '.$pending_status.' >Pending</option>';
                $approved_status=($host_experiences->admin_status=="Approved") ? $approved_status ="selected" : $approved_status ="";
                $admin_status .='<option value="Approved" '.$approved_status.' >Approved</option>';
                $rejected_status=($host_experiences->admin_status=="Rejected") ? $rejected_status ="selected" : $rejected_status ="";
                $admin_status .='<option value="Rejected" '.$rejected_status.' >Rejected</option>';
                $admin_status .='</select>
                </form>';

                return $admin_status;
            })
            ->addColumn('featured', function ($host_experiences) {

                $class = ($host_experiences->is_featured == 'No') ? 'danger' : 'success';

                $featured = '<a href="'.url(ADMIN_URL.'/feature_experience/'.$host_experiences->id).'" class="btn btn-xs btn-'.$class.'">'.$host_experiences->is_featured.'</a>';

                return $featured;
            })
            ->addColumn('action', function ($host_experiences) {   
                $edit_link = '<a href="'.$this->base_url.'/edit/'.$host_experiences->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;';
                $delete_link  = '<a data-href="'.$this->base_url.'/delete/'.$host_experiences->id.'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                $action_link = '';
                if(Auth::guard('admin')->user()->can('edit_host_experiences'))
                {
                    $action_link .= $edit_link;
                }
                if(Auth::guard('admin')->user()->can('delete_host_experiences'))
                {
                    $action_link .= $delete_link;
                }
                return $action_link;
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
        $host_experiences = HostExperiences::with(['user', 'city_details', 'category_details'])->get();

        return $this->applyScopes($host_experiences);
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
        ])
        ->addColumn(['data' => 'host_name', 'name' => 'host_name', 'title' => 'Host Name'])
        ->addColumn(['data' => 'city', 'name' => 'city', 'title' => 'City'])
        ->addColumn(['data' => 'category', 'name' => 'category', 'title' => 'Category'])
        ->columns([
            'status',
            'admin_status' => ['visible' => false],
        ])
        ->addColumn(['data' => 'admin_status_options', 'name' => 'admin_status_options', 'title' => 'Admin Status ', 'orderable' => false, 'searchable' => false])
        ->addColumn(['data' => 'featured', 'name' => 'featured', 'title' => 'Featured'])
        ->columns([
            'created_at',
            'updated_at',
        ])
        ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'exportable' => false, 'printable' => false])
        
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
            'B' => '20',
            'C' => '10',
            'D' => '10',
            'E' => '10',
            'F' => '10',
            'G' => '10',
            'H' => '10',
            'I' => '10',
        );
        return Helpers::buildExcelFile($this->getFilename(), $this->getDataForExport(), $width);
    }
    
    public function printPreview()
    {
        $data = $this->getDataForPrint();
        
        foreach ($data as $key => $value) {

            foreach ($value as $k => $v) {

                if($v=='' || $v==NULL)
                {                    
                    $data[$key][$k]='-';
                }   
                if($k=='Viewed Count'){ $data[$key][$k] =''.$v;}  
                if($k=='Id'){ $data[$key][$k] =''.$v;}                
            }            
        }
    
        $view = $this->printPreview ?: 'datatables::print';

        return $this->viewFactory->make($view, compact('data'));
    }
    protected function getDataForExport()
    {
        $data = parent::getDataForExport();
        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                if($v=='' || $v==NULL)
                {                    
                    $data[$key][$k]='-';
                }   
                if($k=='Viewed Count'){ $data[$key][$k] =''.$v;}  
                if($k=='Id'){ $data[$key][$k] =''.$v;}                
                unset($data[$key]['Admin Status ']);
            }
        }
        return $data;
    }
}
