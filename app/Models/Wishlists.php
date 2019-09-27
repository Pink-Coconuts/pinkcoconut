<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Wishlists extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wishlists';

    public $timestamps = false;

    protected $fillable = ['name'];

    public $appends = ['rooms_count', 'all_rooms_count','host_experience_count', 'all_host_experience_count'];

    public function setNameAttribute($input){
         $this->attributes['name'] = strip_tags($input);
    }

    // Join with saved_wishlists table
    public function saved_wishlists()
    {
        return $this->hasMany('App\Models\SavedWishlists','wishlist_id','id');
    }

    public function getRoomsCountAttribute()
    {
        return @DB::table('saved_wishlists')->where('saved_wishlists.list_type','Rooms')->where('saved_wishlists.wishlist_id', $this->attributes['id'])->join('rooms', 'rooms.id' ,'=', 'saved_wishlists.room_id')->where('rooms.status','Listed')->where('saved_wishlists.user_id', $this->attributes['user_id'])->count();
    }

    public function getAllRoomsCountAttribute()
    {
    	return @DB::table('saved_wishlists')->where('list_type','Rooms')->where('wishlist_id', $this->attributes['id'])->join('rooms', 'rooms.id' ,'=', 'saved_wishlists.room_id')->where('rooms.status','Listed')->count();
    }

    public function getHostExperienceCountAttribute()
    {
        return @DB::table('saved_wishlists')->where('list_type','Experiences')->where('wishlist_id', $this->attributes['id'])->where('user_id', $this->attributes['user_id'])->count();
    }

    public function getAllHostExperienceCountAttribute()
    {
        return @DB::table('saved_wishlists')->where('list_type','Experiences')->where('wishlist_id', $this->attributes['id'])->count();
    }

    //all_host_experience_image
    public function getAllHostExperienceImageAttribute()
    {
        $image =  $this->saved_wishlists()->with('host_experiences')->where('list_type','Experiences')->where('wishlist_id', $this->attributes['id'])->get();
        $all_image = $image->map(function ($item, $key) {
            return $item->host_experiences->photo_name;
        });

        return $all_image->values()->toArray();
    }

    //all_rooms_image
    public function getAllRoomsImageAttribute()
    {
        $image =  $this->saved_wishlists()->with('rooms')->where('list_type','Rooms')->where('wishlist_id', $this->attributes['id'])->get();
        $all_image = $image->map(function ($item, $key) {
            return $item->rooms->photo_name;
        });

        return $all_image->values()->toArray();
    }

    // Join with users table
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    // Join with profile_picture table
    public function profile_picture()
    {
        return $this->belongsTo('App\Models\ProfilePicture','user_id','user_id');
    }
}
