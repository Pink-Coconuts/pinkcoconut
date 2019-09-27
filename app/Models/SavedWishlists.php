<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class SavedWishlists extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'saved_wishlists';

    public $timestamps = false;
    protected $appends = ['photo_name','rooms_count','host_experience_count'];
    // Join with wishlists table
    public function wishlists()
    {
        return $this->belongsTo('App\Models\Wishlists','wishlist_id','id');
    }
    public function getRoomsCountAttribute()
    {
        return @DB::table('saved_wishlists')->where('list_type','Rooms')->where('wishlist_id', $this->attributes['wishlist_id'])->count();
    }
    public function getHostExperienceCountAttribute()
    {
        return @DB::table('saved_wishlists')->where('list_type','Experiences')->where('wishlist_id', $this->attributes['wishlist_id'])->count();
    }
    // Join with rooms table
    public function rooms()
    {
        return $this->belongsTo('App\Models\Rooms','room_id','id')->where('status','Listed');
    }
    //Get rooms  photo_name URL
    public function getPhotoNameAttribute()
    {
        if($this->attributes['list_type']=='Rooms')
        {
            $result = RoomsPhotos::where('room_id', $this->attributes['room_id'])->where('featured','Yes');
            if($result->count() == 0)
                return "room_default_no_photos.png";
            else
                return $result->first()->name;
        }
        else
        {
            $result = HostExperiencePhotos::where('host_experience_id', $this->attributes['room_id']);
            if($result->count() == 0)
                return url('/')."/images/room_default_no_photos.png";
            else
                return $result->first()->image_url;
        }
    }
    // Join with Host Experience table
    public function host_experiences()
    {
        return $this->belongsTo('App\Models\HostExperiences','room_id','id');
    }

    // Join with rooms_photos table
    public function rooms_photos()
    {
        return $this->hasMany('App\Models\RoomsPhotos','room_id','room_id');
    }

    // Join with rooms_price table
    public function rooms_price()
    {
        return $this->belongsTo('App\Models\RoomsPrice','room_id','room_id');
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
