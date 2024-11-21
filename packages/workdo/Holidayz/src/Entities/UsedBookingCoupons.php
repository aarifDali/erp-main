<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsedBookingCoupons extends Model
{
    use HasFactory;

    protected $table = "used_booking_coupons";

    protected $fillable = [
        'customer_id',
        'coupon_id'
    ];
    
    public function userDetail()
    {
        return $this->hasOne(HotelCustomer::class,'id','customer_id');
    }

}
