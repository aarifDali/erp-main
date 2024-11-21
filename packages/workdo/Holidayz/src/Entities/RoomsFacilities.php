<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomsFacilities extends Model
{
    use HasFactory;

    protected $table = "rooms_facilities";

    protected $fillable = [
        'name',
        'short_description',
        'workspace',
        'created_by',
        'tax_id',
        'status'
    ];


    public function getChildFacilities()
    {
        return $this->hasMany(RoomsChildFacilities::class,'facilities_id','id');
    }

}
