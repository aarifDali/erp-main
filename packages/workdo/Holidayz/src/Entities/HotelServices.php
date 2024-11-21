<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelServices extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace','name','position','created_by','is_active','icon','image'
    ];
    

    public function lastId()
    {
        $id = 0;
        $last_rec = self::orderBy('id','Desc')->first();
        if($last_rec != null){
            $id = $last_rec->position;
        }
        return $id;
    }

    public function getSubServices()
    {
        return $this->hasMany(HotelSubServices::class,'service_id','id');
    }

}
