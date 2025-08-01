<?php

namespace Workdo\AssistNow\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRelation extends Model
{
    use HasFactory;

    protected $table = 'client_relations';

    protected $fillable = ['client_id', 'contact_name', 'relationship', 'phone', 'phone_2', 'phone_extra', 'email'];

    public function client()
    {
        return $this->belongsTo(AssistnowClient::class, 'client_id');
    }
}
