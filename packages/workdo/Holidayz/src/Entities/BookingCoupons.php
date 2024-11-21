<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingCoupons extends Model
{
    use HasFactory;

    protected $table = "booking_coupons";

    protected $fillable = [
        'name',
        'code',
        'discount',
        'limit',
        'description',
        'is_active',
        'workspace',
        'created_by'
    ];


    public function getUses()
    {
        return $this->hasMany(UsedBookingCoupons::class,'coupon_id','id');

    }

    public function used_coupon()
    {
        return $this->hasMany(UsedBookingCoupons::class,'coupon_id','id')->count();
    }
    
}
