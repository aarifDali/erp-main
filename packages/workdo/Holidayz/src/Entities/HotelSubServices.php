<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelSubServices extends Model
{
    use HasFactory;

    protected $table = "hotel_subservices";

    protected $fillable = [
        'name','is_active'
    ];
    
}
