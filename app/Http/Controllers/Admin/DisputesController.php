<?php

/**
 * Disputes Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Disputes
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\Reservation;
use App\Models\HostExperienceCalendar;
use App\Models\Payouts;
use App\Models\Messages;
use App\Models\Calendar;
use App\Models\HostPenalty;
use App\Models\Rooms;
use App\Models\Fees;
use App\Models\Currency;
use App\Models\Disputes;
use App\Models\DisputeMessages;
use App\Models\DisputeDocuments;
use Auth;
use DB;
use App\Http\Start\Helpers;
use App\Http\Helper\PaymentHelper;
use DateTime;
use Validator;
use App\DataTables\DisputesDataTable;

class DisputesController extends Controller
{
    /**
     * Load Current Trips page.
     *
     * @return view Current Trips File
     */
    protected $helper; // Global variable for Helpers instance
    
    protected $payment_helper; // Global variable for PaymentHelper instance

    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Disputes
     *
     * @param array $dataTable  Instance of DisputesDataTable
     * @return datatable
     */
    public function index(DisputesDataTable $dataTable)
    {
        return $dataTable->render('admin.disputes.view');
    }

    /**
    * To view the disputes details 
    * @param $request Illuminate\Http\Request
    * @return disputes details view
    */
    public function details(Request $request)
    {
        $data['user_id'] = @Auth::user()->id; 
        $dispute = Disputes::with(['dispute_documents', 'dispute_messages'])->where('id', $request->id);
        $dispute = $data['dispute'] = $dispute->first();
        
        if(!$dispute)
        {
            return redirect(ADMIN_URL.'/disputes');
        }

        return view('admin.disputes.details', $data);
    }

    /**
    * To close the dispute status
    * @param $request Illuminate\Http\Request
    * @return disputes details view
    */
    public function close(Request $request)
    {
        $dispute = Disputes::where('id', $request->id);
        $dispute = $data['dispute'] = $dispute->first();
        
        if(!$dispute)
        {
            return redirect(ADMIN_URL.'/disputes');
        }

        $dispute->status = 'Closed';
        $dispute->admin_status = 'Confirmed';
        $dispute->save();

        $this->helper->flash_message('success', 'The dispute has been successfully closed!');
        return redirect(ADMIN_URL.'/dispute/details/'.$dispute->id);
    }

    /**
    * To add message to the Host/Guest
    * @param $request Illuminate\Http\Request
    * @return disputes thread list item view
    */
    public function admin_message(Request $request)
    {
        $dispute = Disputes::find($request->id);
        if(!$dispute)
        {
            return json_encode(['status' => 'danger']);
        }

        $user_id = @Auth::user()->id;

        $rules = [
            'message' => 'required',
            'message_for' => 'required|in:Host,Guest'
        ];
        $messages = [];
        $attributes= [  
            'message' => 'Message',
            'message_for' => 'Message for',
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        if($validator->fails())
        {
            $errors = $validator->messages();
            if($request->documents)
            {            
                foreach(@$request->documents as $k => $v)
                {
                    if($errors->first('documents.'.$k))
                    {
                        $errors->add('documents', $errors->first('documents.'.$k));
                    }
                }
            }

            return json_encode(['status' => 'error', 'errors' => $errors]);
        }

        $dispute_message                = new DisputeMessages;
        $dispute_message->dispute_id    = $dispute->id;
        $dispute_message->message_by    = 'Admin';
        $dispute_message->message_for   = $request->message_for;
        $dispute_message->user_from     = 0;
        $dispute_message->user_to       = $request->message_for == 'Host' ? $dispute->reservation->host_id : $dispute->reservation->user_id;
        $dispute_message->currency_code = $dispute->reservation->currency_code;
        $dispute_message->message       = $request->message;
        $dispute_message->save();

        $email_controller = new EmailController;
        $email_controller->dispute_admin_conversation($dispute_message->id);

        $thread_list_item = view('admin.disputes.thread_list_item', ['message' => $dispute_message])->render();
        return json_encode(['status' => 'success', 'content' => $thread_list_item]);
    }

    /**
     * To confirm the final dipsute amount decided by users
     *
     * @param Request $request
     * @return Redirect dispute details page 
     **/
    function confirm_amount(Request $request)
    {
        $dispute = Disputes::find($request->id);
        if(!$dispute)
        {
            return redirect(ADMIN_URL.'/disputes');
        }
        if($dispute->status != 'Closed' || $dispute->admin_status != 'Open')
        {
            return redirect(ADMIN_URL.'/dispute/details/'.$dispute->id);
        }

        $user_id = @Auth::user()->id;

        $reservation = $dispute->reservation;

        $final_dispute_amount = $this->payment_helper->currency_convert($dispute->getOriginal('currency_code'), $reservation->currency_code, $dispute->final_dispute_amount);
        
        $host_fee_percentage        = Fees::find(2)->value > 0 ? Fees::find(2)->value : 0;
        $host_payout_ratio          = (1 - ($host_fee_percentage / 100));

        if($dispute->dispute_by == 'Guest')
        {
            $total_amount_without_service_fee = $reservation->total - $reservation->service;
            
            $guest_payout = Payouts::where('reservation_id', $dispute->reservation->id)->where('user_type','guest')->first();

            $guest_refund_amount    = $final_dispute_amount;
            if($guest_payout)
            {
                $guest_refund_amount+= $guest_payout->amount;
            }
            $host_payout_amount     = $total_amount_without_service_fee - $guest_refund_amount;
            $host_fee               = ($host_payout_amount * ($host_fee_percentage / 100));
            $host_payout_amount     = $host_payout_amount * $host_payout_ratio;

            $reservation->host_fee  = $host_fee;
            $reservation->save();
        }
        else if($dispute->dispute_by == 'Host')
        {
            $host_payout = Payouts::where('reservation_id', $dispute->reservation->id)->where('user_type','host')->first();

            $guest_refund_amount    = 0;
            $host_payout_amount     = $final_dispute_amount;
            if($host_payout)
            {
                $host_payout_amount += $host_payout->amount;
            }
            $host_payout_amount = $host_payout_amount + $reservation->hostPayouts->total_penalty_amount;
        }
    
        $this->payment_helper->payout_refund_processing($reservation, $guest_refund_amount, $host_payout_amount, 0);

        $dispute->admin_status = 'Confirmed';
        $dispute->save();

        $this->helper->flash_message('success', 'The payout details updated!');
        return redirect(ADMIN_URL.'/dispute/details/'.$dispute->id);
    }

}
