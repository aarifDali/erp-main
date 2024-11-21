<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Addresses extends Model
{
    use HasFactory;

    protected $table = "addresses";

    protected $fillable = [
        'user_id','alias','address','address_2','city','zip_code','state'
    ];

}
