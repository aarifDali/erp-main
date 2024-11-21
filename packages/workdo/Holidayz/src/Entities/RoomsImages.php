<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomsImages extends Model
{
    use HasFactory;

    protected $table = "rooms_images";

    protected $fillable = [
        'name',
        'room_id',
        'workspace',
        'created_by',
    ];
    
    
}
