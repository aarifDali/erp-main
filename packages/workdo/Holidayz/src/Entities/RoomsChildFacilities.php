<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomsChildFacilities extends Model
{
    use HasFactory;

    protected $table = 'rooms_childfacilities';

    protected $fillable = [
        'facilities_id',
        'name',
        'price',
        'created_by',
        'workspace',
    ];

}
