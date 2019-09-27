<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;

class Hotelpartners extends Model{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hotelpartners';
    public $timestamps = false;
    public $appends = ['image_url'];

    public function getImageUrlAttribute(){
        return $src = url('/').'/images/hotel_partners/'.$this->attributes['image'];
    }

 }
