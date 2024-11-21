<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomBookingOrder extends Model
{
    use HasFactory;

    protected $table = "room_booking_order";

    protected $fillable = [
        'booking_id',
        'customer_id',
        'room_id',
        'workspace',
        'check_in',
        'check_out',
        'price',
        'service_charge',
        'services',
        'room',
        'apartment_type_id',
        'discount_amount',
        'amount_to_pay',
    ];

    public function apartmentType()
    {
        return $this->belongsTo( ApartmentType::class,'apartment_type_id','id');
    }
    
    public function getUserDetails()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function getRoomDetails()
    {
        return $this->hasOne(Rooms::class,'id','room_id');
    }

    public function checkDateTime(){
        $m = $this;
        $m->duration = 15;
        if (\Carbon\Carbon::parse($m->check_in)->addMinutes($m->duration)->gt(\Carbon\Carbon::now())) {
            return 1;
        }else{
            return 0;
        }
    }


    public function getBookingDetails()
    {
        return $this->hasOne(RoomBooking::class,'id','booking_id');
    }

}
