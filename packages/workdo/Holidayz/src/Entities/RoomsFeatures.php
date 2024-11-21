<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomsFeatures extends Model
{
    use HasFactory;

    protected $table = "rooms_features";

    protected $fillable = [
        'name','position','icon','workspace','created_by','status'
    ];

}
