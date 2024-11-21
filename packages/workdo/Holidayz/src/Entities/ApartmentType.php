<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartmentType extends Model
{
    use HasFactory;
    protected $fillable = ['id','name','status'];
    public static function getAllApartmentTypes()
    {


        return self::all(); // Retrieve all records from the apartment_types table
    }

}