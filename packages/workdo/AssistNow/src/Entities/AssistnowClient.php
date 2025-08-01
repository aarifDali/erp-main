<?php

namespace Workdo\AssistNow\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistnowClient extends Model
{
    use HasFactory;

    protected $table = 'assistnow_clients';

    protected $fillable = ['client_id', 'name', 'debtor_id', 'phone', 'email'];

    public function debtor()
    {
        return $this->belongsTo(AssistnowDebtor::class, 'debtor_id');
    }

    public function relations()
    {
        return $this->hasMany(ClientRelation::class, 'client_id');
    }
}
