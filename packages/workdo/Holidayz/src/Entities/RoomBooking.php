<?php

namespace Workdo\Holidayz\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomBooking extends Model
{
    use HasFactory;

    protected $table = "room_booking";

    protected $fillable = [
        'booking_number',
        'user_id',
        'room_id',
        'adults',
        'children',
        'sub_total',
        'total',
        'coupon_id',
        'payment_method',
        'payment_status',
        'invoice',
        'workspace',
        'created_by',
        'first_name',
        'last_name', 'email', 'phone', 'address', 'city', 'country', 'zipcode',
        'apartment_type_id',
        'amount_to_pay',
        'discount_amount',
    ];


    public function getUserDetails()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function getCustomerDetails()
    {
        return $this->hasOne(HotelCustomer::class, 'id', 'user_id');
    }

    public function getCouponDetails()
    {
        return $this->hasOne(BookingCoupons::class, 'id', 'coupon_id');
    }

    public function getRoomDetails()
    {
        return $this->hasOne(Rooms::class, 'id', 'room_id');
    }

    public function checkDateTime()
    {
        $m = $this;
        $m->duration = 15;
        if (\Carbon\Carbon::parse($m->check_in)->addMinutes($m->duration)->gt(\Carbon\Carbon::now())) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function bookingNumberFormat($number,$company_id = null,$workspace = null)
    {
        if(!empty($company_id) && empty($workspace))
        {
            $data = !empty(company_setting('booking_prefix',$company_id)) ? company_setting('booking_prefix',$company_id) : '#BOOK';
        }
        elseif(!empty($company_id) && !empty($workspace))
        {
            $data = !empty(company_setting('booking_prefix',$company_id,$workspace)) ? company_setting('booking_prefix',$company_id,$workspace) : '#BOOK';
        }
        else
        {
            $data = !empty(company_setting('booking_prefix')) ? company_setting('booking_prefix') : '#BOOK';
        }

        return $data. sprintf("%05d", $number);
    }

    public function GetBookingOrderDetails()
    {
        return $this->hasMany(RoomBookingOrder::class, 'booking_id', 'id');
    }

}
