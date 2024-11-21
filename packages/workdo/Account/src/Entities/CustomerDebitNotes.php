<?php

namespace Workdo\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerDebitNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill',
        'vendor',
        'amount',
        'date',
        'status',
    ];


    public function custom_vendor()
    {
        return $this->hasOne(\Workdo\Account\Entities\Vender::class, 'vendor_id', 'vendor');
    }

    public static $statues = [
        'Pending',
        'Partially Used',
        'Fully Used',
    ];
}
