<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = "rooms";
    protected $primaryKey = "room_num";

    public function location(){
        return $this->belongsTo(Location::class,'location_id','Location_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
     }
    use HasFactory;
}
