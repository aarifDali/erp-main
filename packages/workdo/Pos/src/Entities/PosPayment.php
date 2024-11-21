<?php

namespace Workdo\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pos_id',
        'date',
        'amount',
        'discount',
        'discount_amount',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Workdo\Pos\Database\factories\PosPaymentFactory::new();
    }
    public function bankAccount()
    {
        return $this->hasOne(\Workdo\Pos\Entities\BankAccount::class, 'id', 'account_id');
    }
}
