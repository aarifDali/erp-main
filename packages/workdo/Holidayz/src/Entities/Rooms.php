<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rooms extends Model
{
    use HasFactory;

    protected $table = "rooms";

    protected $fillable = [
        'room_type',
        'short_description',
        'apartment_type_id',
        'description',
        'tags',
        'adults',
        'children',
        'total_room',
        'base_price',
        'final_price',
        'image',
        'is_active',
        'workspace',
        'created_by'
    ];

    public function apartmentType()
    {
        return $this->belongsTo( ApartmentType::class,'apartment_type_id','id');
    }

    public function getImages()
    {
        return $this->hasMany(RoomsImages::class,'room_id','id');
    }

    public function features()
    {
        return $this->hasMany(RoomsFeatures::class,'workspace','workspace');
    }

    public function getRoomTotal()
    {
        return RoomBookingOrder::select(\DB::raw("SUM(room) as total_booking_rooms"))->where('room_id',$this->id)->get();
    }

}
