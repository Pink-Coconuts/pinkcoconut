<?php

/**
 * Messages Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Messages
 * @author      Trioangle Product Team
 * @version     1.5.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Messages;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use App\Http\Start\Helpers;
use App\Http\Helper\PaymentHelper;
use Validator;
use DB;
use Auth;
use DateTime;
use Session;
use JWTAuth;


class MessagesController extends Controller
{ 
  protected $payment_helper; // Global variable for Helpers instance
    
    public function __construct(PaymentHelper $payment)
    {
        $this->payment_helper = $payment;
        $this->helper = new Helpers;
    }
   /**
     *Send Message
     *
     * @param  Get method inputs
     * @return Response in Json Format
     */
  public function send_message(Request $request)
  { 
    $rules     = array( 


                       'host_user_id'    =>   'required|exists:users,id',

                       'message_type'    =>   'required|exists:message_type,id',

                       'reservation_id'  =>   'required|exists:reservation,id',

                       'message'         =>   'required'
                   
                      );

    if($request->list_type=="Experiences")
    {
      $rules['room_id'] = 'required|exists:host_experiences,id';
      $niceNames = array('room_id'  => 'Experience Id'); 
    }

    else
    {
      $rules['room_id'] = 'required|exists:rooms,id';
      $niceNames = array('room_id'  => 'Room Id'); 

    }


    $messages  = array('required' => ':attribute is required.');

    $validator = Validator::make($request->all(), $rules, $messages);

    $validator->setAttributeNames($niceNames); 


         if ($validator->fails()) 
         {
            $error=$validator->messages()->toArray();

               foreach($error as $er)
               {
                    $error_msg[]=array($er);

               } 
  
                return response()->json([

                                'success_message'=>$error_msg['0']['0']['0'],

                                'status_code'=>'0']);
         }
         else
         {  

          $user = JWTAuth::parseToken()->authenticate();
           //set user token to session for getting  user time based on time zone.
           Session::put('get_token',$request->token); 
           //Prevent Host Sending Message to Host 
          if($user->id==$request->host_user_id)
          {
              return response()->json([

                          'success_message' => 'You Can Not Send Messages to Your Own Reservation',

                          'status_code'     => '0'
                                 ]);

          }

          $user_id=$user->id;

          $host_id=$request->host_user_id;

          $result=Reservation::where('id',$request->reservation_id)

                              ->where('room_id',$request->room_id)

                              ->where(function ($query)use($user_id,$host_id)
                               {
                                $query->where('host_id','=',$user_id)->orWhere('host_id', '=',$host_id);
                               })

                              ->where(function ($query)use($user_id,$host_id)
                               {
                               $query->where('user_id','=',$user_id)->orWhere('user_id', '=',$host_id);
                              });
                           //->where('host_id',$request->host_user_id);
           //check valid user or not
          $count= $result->count(); 
          if($count==0)
           {
                return response()->json([

                              'success_message' => 'Reservation details Mismatch',

                              'status_code'     => '0'
                                 
                                       ]);

           }

          $messages = new Messages;

          $messages->room_id        = $request->room_id;
          $messages->reservation_id = $request->reservation_id;
          $messages->user_to        = $request->host_user_id;
          $messages->user_from      = $user->id;
          $messages->message        = $this->helper->phone_email_remove($request->message); 
          $messages->message_type   = $request->message_type;
          $messages->list_type   = $request->list_type;

          $messages->save(); //save message details

          return response()->json([

                              'success_message' => 'Message Send Successfully',

                              'status_code'     => '1',

                              'message'         => $this->helper->phone_email_remove($request->message),

                               'message_time'   => $messages->created_time

                               ]);
         }
  }
}
  
