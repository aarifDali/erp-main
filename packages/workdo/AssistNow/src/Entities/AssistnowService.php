<?php

namespace Workdo\AssistNow\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistnowService extends Model
{
    use HasFactory;

    protected $table = 'assistnow_services';

    protected $fillable = [
        'name',
        'billing_interval',
        'description',
    ];
}
