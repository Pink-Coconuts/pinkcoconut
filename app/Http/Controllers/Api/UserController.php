<?php

/**
 * User Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    User
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\User;
use App\Models\ProfilePicture;
use App\Models\HomeCities;
use App\Models\PayoutPreferences;
use JWTAuth;
use Session;
use DateTime;
use DB;
use Validator;
use App\Http\Start\Helpers;


class UserController extends Controller
{
    public function user_details()
    {
      $user = JWTAuth::parseToken()->authenticate();

      $user = User::with(['profile_picture'])->whereId($user->id)->first();

      return response()->json(compact('user'));
    }

    public function home_cities()
    {
      $home_cities     = HomeCities::all();

      return response()->json(compact('home_cities'));
    }
    public function signup_details()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $user = User::with(['profile_picture'])->whereId($user->id)->select('id','first_name','last_name','email','dob')->first();
      $token = JWTAuth::getToken();
      $token = (string)$token;
      
    
    $user=array('status'=>'1','success_message'=>'1','token'=>$token,'id'=>$user->id,'first_name'=>$user->first_name,'last_name'=>$user->last_name,'email'=>$user->email,'dob'=>$user->dob,'image_url'=>$user->profile_picture->src);

        return response()->json(compact('user'));
    }
     /**
     * User Logout
     *
     * @param  Get method inputs
     * @return Response in Json 
     */
    public function logout(Request $request)
    {  
         //Deactive the Access Token
        JWTAuth::invalidate($request->token);

        Session::flush();

        return response()->json([

                                 'success_message' => 'Logout Successfully',

                                 'status_code'     => '1'

                               ]);

    }

    /**
     * Edit User Profile
     *
     * @param  Get method inputs
     * @return  Response in Json 
     */

  public function edit_profile(Request $request)
   {
    $this->helper=new Helpers;
     $user_token = JWTAuth::parseToken()->authenticate();

     $id         = $user_token->id;

     $rules      = array(

                      'first_name'  =>  'required | max:255',

                      'last_name'   =>  'required | max:255',

                      'dob'         =>  'date_format:"d-m-Y"| date |required',

                      'gender'      =>  'required',

                      'email'       =>  'required | email | max:255 ',

                       );

     $messages  = array(

                     'required'     =>   ':attribute is required.',

                     'email'        =>   'Invalid Email-ID.',

                     'regex'        =>   'Invalid Thumb Url.'

                      );

    $validator  = Validator::make($request->all(), $rules, $messages);
           
      if($validator->fails()) 
       {
         $error = $validator->messages()->toArray();

            foreach($error as $er)
            {
                $error_msg[]=array($er);

            } 
  
            return response()->json([

                              'success_message'=>$error_msg['0']['0']['0'],

                              'status_code'=>'0'

                                   ] );
       }
       else
       { 
         // Check Email Id is Already Exists or Not. 
        $email=$request->email;

        $email_result=DB::table('users')->where('email',$email)->get();

          if(count($email_result) == 1 ) 
           {
             if($email_result[0]->id != $id)
              {
                return response()->json([

                                'success_message'=>'Email ID Already Exists',

                                'status_code'=>'0'

                                       ] );
                  
              }
           }
              
           if (count($email_result) > 1 ) 
           {

              return response()->json([

                                'success_message'=>'Email ID Already Exists',

                                'status_code'=>'0'

                                    ] );
    
           }
         //check Image Exists or Not.
           
        // $file_name       =basename($request->user_thumb_url);

        // $chck_image_name =DB::table('profile_picture')->where('user_id',$user_token->id)

        //                       ->where('src',$file_name)->get();

        // if(count($chck_image_name)!= 1 && $request->user_thumb_url!='')
        //  { 
        //         $pro_pic=DB::table('profile_picture')->where('user_id',$user_token->id)

        //                      ->update(['src'=>$file_name,'photo_source'=>'Local']);
        //  }
         
         $from = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($request->dob)));
         
         $to   = new DateTime('today');
         
         $age  = $from->diff($to)->y; 
               //Check User Age Was Above 18 or Not
              if($age < 18)
              {
                return response()->json([

                                'success_message' => 'You Must be 18 or Older.',

                                'status_code'     => '0'
                                           
                                        ]);
              }
              else
              {

                $edit_user = array(

                    'first_name' =>  $request->first_name,

                    'last_name'  =>  $request->last_name,

                    'dob'        =>  date("Y-m-d", strtotime($request->dob)),

                    'live'       =>  $request->user_location,

                    'about'      =>  $request->about_me,

                    'school'     =>  $request->school,

                    'gender'     =>  $request->gender,

                    'email'      =>  $request->email,

                    'work'       =>  $request->work

                                 );
                 // Update The User Details.
                $result=DB::table('users')->where('id',$id)->update($edit_user);

                return response()->json([

                                        'success_message' => 'User Details Updated Successfully',
                              
                                        'status_code'     => '1'

                                       ]);
              }
       }

    }

      /**
     * Display User Profile
     *
     * @param  Get method inputs
     * @return Response in Json 
     */
    public function view_profile(Request $request)
    {
      $this->helper=new Helpers;
      $user_token  = JWTAuth::parseToken()->authenticate();

      $details_user= array();

      $user_details = User::where('id',$user_token->id)->first();
      
      $createDate   = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($user_details->created_at)));
        //Check dob empty or not
      if($user_details->dob=='0000-00-00')
      {
        
         $dob='';
      }
      else
      {  

         $c_dob = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($user_details->dob)));

         $dob   = $c_dob->format('d M Y');

      }
      $c_date   = $createDate->format('M Y');   

      $pro_pic  = @ProfilePicture::where('user_id',$user_token->id)->first();


      if($pro_pic->src != '')
      {
          if($pro_pic->photo_source=='Local')
          {
              $img       = $pro_pic->src;
              $file_name = basename($img);
              $type      = pathinfo($img, PATHINFO_EXTENSION);
              $basename  = basename($img,'.'.$type);
              $pro_img   = @$pro_pic->src;
              $small     = @$pro_pic->src;
              $large     = @$pro_pic->header_src510;
          }
          else
          {
              $pro_img =$pro_pic->src;
              $small   =$pro_pic->src;
              $large   =$pro_pic->src;
          }
      }
      else
      {
        $pro_img = url('/').'/images/user_pic-225x225.png';
        $small   = url('/').'/images/user_pic-225x225.png';
        $large   = url('/').'/images/user_pic-225x225.png';
      }

       
      $user_phone    =DB::table('users_phone_numbers')->where('user_id',$user_token->id)

                      ->where('status','=','Confirmed')->pluck('phone_number');

      $social_details=DB::table('users_verification')->where('user_id',$user_token->id)

                          ->first();
      //get payout count
      $payout_count=@PayoutPreferences::where('user_id',JWTAuth::parseToken()->authenticate()->id);                   

       $details_user['payout_count']  = $payout_count->count() !=null 

                                             ? $payout_count->count() : '0';                    

      $details_user['first_name']          = $user_details->first_name !='' 

                                             ? $user_details->first_name : '';

      $details_user['last_name']           = $user_details->last_name != '' 

                                             ? $user_details->last_name : '';

      $details_user['dob']                 = $dob != '' ? $dob : '';

      $details_user['user_location']       = $user_details->live != ''

                                             ?$user_details->live : '';

      $details_user['member_from']         = $user_details->since != '' 

                                             ? $user_details->since : '';

      $details_user['about_me']            = $user_details->about != '' 

                                             ? $user_details->about : '';

      $details_user['school']              = $user_details->school != '' 

                                             ? $user_details->school : '';

      $details_user['gender']              = $user_details->gender != '' 

                                             ? $user_details->gender : '';

      $details_user['email']               = $user_details->email != '' 

                                             ? $user_details->email : '';

      $details_user['phone_number']        = $user_phone != '' ? $user_phone : '';

      $details_user['work']                = $user_details->work != '' 

                                             ? $user_details->work : '';

      $details_user['is_email_connect']    = $social_details->email != '' 

                                             ? $social_details->email : '';

      $details_user['is_facebook_connect'] = $social_details->facebook != '' 

                                             ? $social_details->facebook : '';

      $details_user['is_google_connect']   = $social_details->google != '' 

                                             ? $social_details->google : '';

      $details_user['is_linkedin_connect'] = $social_details->linkedin != '' 

                                             ? $social_details->linkedin : '';

      $details_user['normal_image_url']    = $pro_img;

      $details_user['small_image_url']     = $small;

      $details_user['large_image_url']     = $large;


      return  response()->json([

                              'success_message'=>'User Details Listed Successfully.',

                              'status_code'    =>'1',

                              'user_details'   =>$details_user


                              ]); 
    }


    /*
     *Profile Image Upload
     *
     * @param  Post method inputs
     * @return Response in Json
    */
    public function upload_profile_images()
    {
      $this->helper = new Helpers;
      $request      = request();
      $user         = JWTAuth::toUser($request->token);
      $user_id      = $user->id;
      
    if ($request->file('image')) {
      $rules = [
        'image' => 'required|image|mimes:jpg,png,jpeg,gif',
      ];

      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails()) {
        return response()->json(
          [
            'status_message' => $validator->messages()->first(),
            'status_code' => '0',
          ]
        );
      }

      $file = $request->file('image');
      $path = '/images/users/'.$user_id.'/';

      if (UPLOAD_DRIVER == 'cloudinary') {
        $c = $this->helper->cloud_upload($file);
        if ($c['status'] != "error") {
          $file_name = $c['message']['public_id'];
        } else {
          return response()->json([
            'success_message' => $c['message'],
            'status_code' => "0",
          ]);
        }
      }
      else{
      $file_name = $this->helper->fileUpload($file, $path);

             //change compress image in 1440*960 
            $this->helper->compress_image("images/users/".$user_id."/".$file_name, "images/users/".$user_id."/".$file_name, 80, 1440, 960);

             //change compress image in 225*225 
            $li=$this->helper->compress_image("images/users/".$user_id."/".$file_name, "images/users/".$user_id."/".$file_name, 80, 225, 225);

             //change compress image in 510*510 
            $this->helper->compress_image("images/users/".$user_id."/".$file_name, "images/users/".$user_id."/".$file_name, 80, 510, 510);

             //change compress image in 1349*402 
            $this->helper->compress_image("images/users/".$user_id."/".$file_name, "images/users/".$user_id."/".$file_name, 80, 1349, 402);

            //change compress image in 450*250 
            $this->helper->compress_image("images/users/".$user_id."/".$file_name, "images/users/".$user_id."/".$file_name, 80, 450, 250);
          } 
      }
        ProfilePicture::where('user_id',$user_id)->update(['src'=>$file_name,'photo_source'=>'Local']);
        $pro_pic  = @ProfilePicture::where('user_id',$user_id)->first();
        $normal   = @$pro_pic->src;
        $small     = @$pro_pic->src;
        $large     = @$pro_pic->header_src510;
        return response()->json([
          'success_message'  => "Profile Image Upload Successfully",
          'status_code'      => "1",
          'normal_image_url' => $normal,
          'small_image_url'  => $small,
          'large_image_url'  => $large,
          'file_name'        => $file_name
        ]);
    }


    /*
     *Profile Image Upload
     *
     * @param  Post method inputs
     * @return Response in Json
    */
    public function upload_profile_image(Request $request)
    {
      $this->helper = new Helpers;
      $user         = JWTAuth::toUser($_POST['token']);
      $user_id      = $user->id;
      //ceck uploaded image is set or not
      if(isset($_FILES['image']))
      {
        $errors    = array();
        $file_name = time().'_'.$_FILES['image']['name'];
        $type      = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_tmp  = $_FILES['image']['tmp_name'];
        $dir_name = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$user_id;
        $f_name   = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$user_id.'/'.$file_name;
        
        //check file directory is created or not
        if(!file_exists($dir_name))
        {   //create file directory
           mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/users/'.$user_id, 0777, true);
        }
        if(UPLOAD_DRIVER=='cloudinary')
        {
          $c=$this->helper->cloud_upload($file_tmp);
          if($c['status']!="error")
          {
              $file_name=$c['message']['public_id'];    
          }
          else
          {
              return response()->json([
                'success_message'     => $c['message'],
                'status_code'         => "0"
              ]);
          }
        }
        else
        {
          //upload image from temp_file  to server file
         if(move_uploaded_file($file_tmp,$f_name))
          {
             //change compress image in 1440*960 
            $this->helper->compress_image("images/users/".$user_id."/".$file_name, "images/users/".$user_id."/".$file_name, 80, 1440, 960);

             //change compress image in 225*225 
            $li=$this->helper->compress_image("images/users/".$user_id."/".$file_name, "images/users/".$user_id."/".$file_name, 80, 225, 225);

             //change compress image in 510*510 
            $this->helper->compress_image("images/users/".$user_id."/".$file_name, "images/users/".$user_id."/".$file_name, 80, 510, 510);

             //change compress image in 1349*402 
            $this->helper->compress_image("images/users/".$user_id."/".$file_name, "images/users/".$user_id."/".$file_name, 80, 1349, 402);

            //change compress image in 450*250 
            $this->helper->compress_image("images/users/".$user_id."/".$file_name, "images/users/".$user_id."/".$file_name, 80, 450, 250);
          } 
        }
        @ProfilePicture::where('user_id',$user_id)->update(['src'=>$file_name,'photo_source'=>'Local']);
        $pro_pic  = @ProfilePicture::where('user_id',$user_id)->first();
        $normal   = @$pro_pic->src;
        $small     = @$pro_pic->src;
        $large     = @$pro_pic->header_src510;
        return response()->json([
          'success_message'  => "Profile Image Upload Successfully",
          'status_code'      => "1",
          'normal_image_url' => $normal,
          'small_image_url'  => $small,
          'large_image_url'  => $large,
          'file_name'        => $file_name
        ]);
    }
      
    }
    /**
     *Display user profile
     *
     * @param  Get method inputs
     * @return Response in Json
     */

    public function user_profile_details(Request $request)
    {   
      $this->helper=new Helpers;
      
      $rules    = array('other_user_id' => 'required|exists:users,id');

      $messages = array('required'      => ':attribute is required.');

       $validator = Validator::make($request->all(), $rules, $messages);
           
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
           
            //get host user details
            $host_user    = User::with(['profile_picture'])->find($request->other_user_id);
             //convet time string and split date 
            
            $createDate   = new DateTime(date('Y-m-d', $this->helper->custom_strtotime($host_user->created_at)));
                
            $created_date = date('Y-m-d');

            $user_details = array(

                            'large_image'   =>  $host_user->profile_picture->header_src510,

                            'first_name'    =>  $host_user->first_name,

                            'last_name'     =>  $host_user->last_name,

                            'about_me'      =>  $host_user->about,

                            'member_from'   =>  $host_user->since,

                            'user_location' =>  $host_user->live

                                );

            return response()->json([

                          'success_message' => 'User Details Listed Successfully',

                          'status_code'     => '1',

                          'user_details'    => $user_details

                                    ]);

          }

    }

 /**
     *Display payout details
     *   
     * @param  Get method request inputs
     * @return Response in Json
     */
    public function payout_details(Request $request)
    { 
        //get payout preferences details
        $payout_details = @PayoutPreferences::where('user_id',JWTAuth::parseToken()->authenticate()->id)->get();

        foreach ($payout_details as $payout_result)
        {
          $data[]=@array(

                       'payout_id'     =>  $payout_result->id,

                        'user_id'      =>  $payout_result->user_id,

                       'payout_method' =>  $payout_result->payout_method !=null

                                           ?$payout_result->payout_method :'',

                       'paypal_email'  =>  $payout_result->paypal_email !=null

                                           ?$payout_result->paypal_email :'',

                       'set_default'   =>  ucfirst($payout_result->default),


                       );

        }
        if(@$data==null)
        { 
           return response()->json(['success_message'=>'No Data Found','status_code'=>'0']);

        }

         return response()->json([

                  'success_message'=> 'PayoutPreferences Details Listed Successfully',

                  'status_code'    => '1',

                  'payout_details' => @$data!=null ? $data :array()

                                 ]);
     
    }

   /**
     *Payout Set Default and Delete
     *   
     * @param  Get method request inputs
     * @param  Type  Default   Set Default payout 
     * @param  Type  Delete    Delete payout Details
     * @return Response in Json
     */
   public function payout_changes(Request $request,EmailController $email_controller)
   {

     $rules     = array(

                        'payout_id'    =>   'required|exists:payout_preferences,id',

                        'type'         =>   'required'

                        );

     $niceNames = array('payout_id'    =>   'Payout Id'); 

     $messages  = array('required'     =>   ':attribute is required.');

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

                'success_message' => $error_msg['0']['0']['0'],

                'status_code'     => '0'

                                ]);
      }

       //check valid user or not
        $check_user = PayoutPreferences::where('id',$request->payout_id)

                      ->where('user_id',JWTAuth::parseToken()->authenticate()->id)

                      ->first();

        if(count($check_user)<1)    
        {

        return response()->json([

                                 'success_message' => 'Permission Denied',

                                 'status_code'     => '0'

                                ]);

        }  

       //check valid type or not
      if($request->type!='default' && $request->type !='delete')
      {

        return response()->json([

                                 'success_message' => 'The Selected Type Is Invalid',

                                 'status_code'     => '0'

                                ]);

      }

      //set default payout
      if($request->type=='default')
      {  

        $payout = PayoutPreferences::where('id',$request->payout_id)->first();

        if($payout->default == 'yes')
        {

            return response()->json([

                  'success_message'=>'The Given Payout Id is Already Defaulted',

                  'status_code'=>'0']);
        }
        else
        {
             //Changed default option No in all Payout based on user id
            $payout_all = PayoutPreferences::where('user_id',JWTAuth::parseToken()->authenticate()->id)->update(['default'=>'no']);

            $payout->default = 'yes';

            $payout->save();//save payout detils

            $email_controller->payout_preferences($payout->id, 'default_update');

            return response()->json([

                  'success_message' => 'Payout Preferences is Successfully Selected Default',

                  'status_code'     => '1'

                                   ]);

        }

      }
       //Delete payout
      if($request->type=='delete')
      {
        $payout = PayoutPreferences::where('id',$request->payout_id)->first();

        if($payout->default == 'yes')
        {
            return response()->json([

                  'success_message' => 'Permission Denied to Delete the Default Payout',

                  'status_code'     => '0'

                                   ]);
        }
        else
        {
            $payout->delete(); //Delete payout.

            $email_controller->payout_preferences($request->payout_id, 'delete');

              return response()->json([

                  'success_message' => 'Payout Details Deleted Successfully',

                  'status_code'     => '1'

                                     ]);
        }

      }
   }


 }