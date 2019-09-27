<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Rooms;
use App\Models\Wishlists;
use App\Models\SavedWishlists;
use App\Models\User;
use Mail;
use Auth;
use App;
use App\Http\Start\Helpers;
use App\Mail\MailQueue;

class WishlistController extends Controller
{
    protected $helper; // Global variable for Helpers instance

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    public function wishlist_list(Request $request)
    {
        if(Auth::check()) {
            $type = ($request->type=='Homes' || $request->type=='Rooms')?'Rooms':'Experiences';
            $result = Wishlists::leftJoin('saved_wishlists', function($join) use($request,$type) {
                                $join->on('saved_wishlists.wishlist_id', '=', 'wishlists.id')->where('saved_wishlists.room_id', '=', $request->id)->where('list_type', $type);
                            })->where('wishlists.user_id', Auth::user()->id)->where('wishlists.name','!=','')->orderBy('wishlists.id','desc')->select(['wishlists.id as id', 'name', 'saved_wishlists.id as saved_id'])->get();

    	   return $result;
        }
        else {
                session(['url.intended' => url()->previous()]);

            return 'redirect';
        }
    }

    public function create(Request $request)
    {
        $wishlist = new Wishlists;

        $wishlist->name    = $request->data;
        $wishlist->user_id = Auth::user()->id;

        $wishlist->save();

        $where = array();
        if(isset($request->id)){
            $where['saved_wishlists.list_type'] = 'Rooms';
            $where['saved_wishlists.room_id'] = $request->id;
        }
        $result = Wishlists::leftJoin('saved_wishlists', function($join) use($where) {
                                $join->on('saved_wishlists.wishlist_id', '=', 'wishlists.id')->where($where);
                            })->where('wishlists.user_id', Auth::user()->id)->orderBy('wishlists.id','desc')->where('wishlists.name','!=','')->select(['wishlists.id as id', 'name', 'saved_wishlists.id as saved_id'])->get();
        
        return json_encode($result);
    }

    public function create_new_wishlist(Request $request)
    {
        $wishlist = new Wishlists;

        $wishlist->name    = $request->name;
        $wishlist->privacy = $request->privacy;
        $wishlist->user_id = Auth::user()->id;

        $wishlist->save();

        $this->helper->flash_message('success', trans('messages.wishlist.created_successfully')); // Call flash message function
        return redirect('wishlists/my');
    }

    public function edit_wishlist(Request $request)
    {
        $wishlist = Wishlists::find($request->id);

        $wishlist->name    = $request->name;
        $wishlist->privacy = $request->privacy;

        $wishlist->save();

        $this->helper->flash_message('success', trans('messages.wishlist.updated_successfully')); // Call flash message function
        return redirect('wishlists/'.$request->id);
    }

    public function delete_wishlist(Request $request)
    {
         
        $delete = Wishlists::whereId($request->id)->whereUserId(Auth::user()->id);

        if($delete->count()) {
            $counts=SavedWishlists::whereWishlistId($request->id)->delete();
            $delete->delete();
            $this->helper->flash_message('success', trans('messages.wishlist.deleted_successfully')); // Call flash message function
           $counts=Wishlists::whereId($request->id)->whereUserId(Auth::user()->id)->count();
            if($counts)
            return redirect('wishlists/my');
        else
            return redirect('dashboard');

        }
        else 
            return redirect('dashboard');
    }

    public function add_note_wishlist(Request $request)
    {
    	SavedWishlists::whereWishlistId($request->id)->whereUserId(Auth::user()->id)->whereRoomId($request->room_id)->update(['note' => $request->note]);
    }

    public function save_wishlist(Request $request)
    {
        if($request->saved_id) {
            SavedWishlists::find($request->saved_id)->delete();
            return 'null';
        }
        else {
            $save_wishlist = new SavedWishlists;

            $save_wishlist->room_id     = $request->data;
            $save_wishlist->wishlist_id = $request->wishlist_id;
            $save_wishlist->user_id     = Auth::user()->id;

            $save_wishlist->save();

            return $save_wishlist->id;
        }
    }
    public function save_wishlist_experience(Request $request)
    {
        if($request->saved_id) {
            SavedWishlists::find($request->saved_id)->delete();
            return 'null';
        }
        else {
            $save_wishlist = new SavedWishlists;
            $save_wishlist->room_id     = $request->data;
            $save_wishlist->wishlist_id = $request->wishlist_id;
            $save_wishlist->user_id     = Auth::user()->id;
            $save_wishlist->list_type     = 'Experiences';
            $save_wishlist->save();

            return $save_wishlist->id;
        }
    }

    public function remove_saved_wishlist(Request $request)
    {
        SavedWishlists::whereWishlistId($request->id)->whereRoomId($request->room_id)->where('list_type',$request->type)->delete();
        return SavedWishlists::whereWishlistId($request->id)->get();
    }

    public function my_wishlists(Request $request)
    {
        if(!@$request->id || @Auth::user()->id == $request->id) {

            $data['result'] =Wishlists::with(['saved_wishlists' => function($query)
            {
                  $query->with(['rooms']);
            },
            'profile_picture'])->whereHas('saved_wishlists', function($query)
            {
                 $query->whereHas('rooms', function($query)
                 {
                    $query->where('status','Listed')->whereHas('users',function($query)
                    {
                        $query->where('status','Active');
                    });
                 })->where('list_type', 'Rooms')->orWhereHas('host_experiences', function($query)
                 {
                    $query->where('status','Listed')->whereHas('users',function($query)
                    {
                        $query->where('status','Active');
                    });
                 })->where('list_type', 'Experiences');
            })->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

            $data['owner'] = 1;
            $data['user'] = Auth::user();
        }
        else {
            $data['result'] = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms'=>function($query){
                    $query->where('status','Listed');
                },'host_experiences'])->where(function($query){
                    $query->whereHas('rooms', function($query){
                        $query->where('status','Listed');
                    })->orWhereHas('host_experiences', function($query){
                        
                    });
                });
            }, 'profile_picture'])->where('user_id', $request->id)->wherePrivacy('0')->orderBy('id', 'desc')->get();
            $data['owner'] = 0;
            $data['user'] = User::find($request->id);
        }
        
        /*if($data['result']->count() == 0)
            abort(404);*/
        

        $wish_count= wishlists::where('user_id',@Auth::user()->id)->where('name','!=','')->get();
        $data['count'] = $wish_count->count();

        return view('wishlists.my_wishlists', $data);
    }

    public function get_wishlists_home(Request $request)
    {
        $check = Wishlists::whereId($request->id)->whereUserId(@Auth::user()->id)->first();
        $wishlist = Wishlists::with([
            'saved_wishlists' => function($query){
                $query->with([
                    'rooms' => function($query){
                        $query->with('rooms_address');
                    },
                    'rooms_photos', 'rooms_price' => function($query){
                        $query->with('currency');
                    }, 
                    'users', 
                    'profile_picture'
                ])->whereHas('rooms',function($query){
                    $query->where('status','Listed')->whereHas('users',function($query){
                        $query->where('status','Active');
                    });
                })->where('list_type', 'Rooms');
        }])->where('id', $request->id);
        if($check) 
        {
            $wishlist =$wishlist->get();
        }
        else 
        {
            $wishlist =$wishlist->where('privacy','0')->get();
        }
        return $wishlist->tojson();
    }
    public function get_wishlists_experience(Request $request)
    {
        $check = Wishlists::whereId($request->id)->whereUserId(@Auth::user()->id)->first();
        $wishlist = Wishlists::with([
            'saved_wishlists' => function($query){
                $query->with([
                    'host_experiences' => function($query){
                        $query->with('host_experience_location','host_experience_photos','currency','city_details');
                    },
                    'users', 
                    'profile_picture'
                ])->whereHas('host_experiences',function($query){
                    $query->where('status','Listed')->whereHas('user',function($query){
                        $query->where('status','Active');
                    });
                })->where('list_type', 'Experiences');
        }])->where('id', $request->id);
        if($check) 
        {
            $wishlist =$wishlist->get();
        }
        else 
        {
            $wishlist =$wishlist->where('privacy','0')->get();
        }
        return $wishlist->tojson();
    }

    public function wishlist_details(Request $request)
    {
        $check = Wishlists::whereId($request->id)->whereUserId(@Auth::user()->id)->first();
        $wishlist = Wishlists::with([
            'saved_wishlists' => function($query){
                $query->with([
                    'rooms' => function($query){
                        $query->where('status','Listed');
                    },
                    'users', 
                    'profile_picture'
                ]);
        }])->where('id', $request->id);
        if($check) 
        {
            $data['owner'] = 1;
            $wishlist =$wishlist->get();
        }
        else 
        {
            $data['owner'] = 0;
            $wishlist =$wishlist->where('privacy','0')->get();
        }
        if(!$wishlist->count()){ 
            abort('404');
        }
        $data['result']=$wishlist;
        $data['count'] = 0;
        $data['wl_id']=$request->id;
        return view('wishlists.wishlist_details', $data);
    }

    public function share_email(Request $request)
    {
        $wishlist_id = $request->id;

        // set email data
        $email_array = explode(',', $request->email);
        $to_emails = array_filter(array_map('trim', $email_array));
        $message = $request->message;

        $data['url'] = url('/').'/';
        $data['locale'] = App::getLocale();
        $data['content'] = Auth::user()->first_name."'s Wish List Link: ".$data['url'].'wishlists/'.$wishlist_id.' <br><br>' . $message;
        $data['view_file'] = 'emails.custom_email';

        // send email to queue one by one
        foreach($to_emails as $email) {
            $user = User::where('email', $email)->get();
            $data['first_name'] = (@$user[0]->first_name) ? $user[0]->first_name : $email;
            $data['subject'] = Auth::user()->first_name . ' shared his Wish List';

            Mail::to($email)->queue(new MailQueue($data));
        }

        $this->helper->flash_message('success', trans('messages.wishlist.shared_successfully')); // Call flash message function
        return redirect('wishlists/'.$wishlist_id);
    }

    public function popular(Request $request)
    {
        $data['result'] = Rooms::with(['saved_wishlists' => function($query){
                $query->where('user_id', @Auth::user()->id);
            }])->wherePopular('Yes')->whereStatus('Listed')->get();

        if(!@$request->id || @Auth::user()->id == $request->id) {
            $result = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms'])->where('list_type', 'Rooms');
            }, 'profile_picture'])->where('user_id', @Auth::user()->id)->orderBy('id', 'desc')->get();
        }
        else {
            $result = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms']);
            }, 'profile_picture'])->where('user_id', $request->id)->wherePrivacy('0')->orderBy('id', 'desc')->get();
        }
        
        $data['count'] = $result->count();

        return view('wishlists.popular', $data);
    }

    public function picks(Request $request)
    {
        $data['result'] = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms'])->whereHas('rooms', function($query){
                    $query->where('status','Listed');
                });
            }, 'profile_picture'])->wherePrivacy('0')->wherePick('Yes')->orderBy('id', 'desc')->whereHas('users',function($query){
                $query->where('status','Active');
            })->get();

        if(!@$request->id || @Auth::user()->id == $request->id) {
            $result = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms']);
            }, 'profile_picture'])->where('user_id', @Auth::user()->id)->orderBy('id', 'desc')->get();
        }
        else {
            $result = Wishlists::with(['saved_wishlists' => function($query){
                $query->with(['rooms']);
            }, 'profile_picture'])->where('user_id', $request->id)->wherePrivacy('0')->orderBy('id', 'desc')->get();
        }
        
        $data['count'] = $result->count();
        
        return view('wishlists.picks', $data);
    }
}
