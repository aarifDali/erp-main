<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Paddle\Billable;

class HotelCustomer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Billable;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        // 'password',
        'workspace',
        'dob',
        'newsletter',
        'avatar',
        'opt_in',
        'last_name',
        'group_access',
        'id_number',
        // 'company',
        // 'vat_number',
        'home_phone',
        'mobile_phone',
        'other',
        'is_active',
        'id_proof'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAddresses()
    {
        return $this->hasOne(Addresses::class, 'user_id', 'id');
    }
    public function getIdProofPathAttribute()
    {
        // Check if the ID proof exists and return the URL
        return $this->id_proof ? url('uploads/public/id_proofs/' . $this->id_proof) : null;
    }

}
