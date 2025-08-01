<?php

namespace Workdo\AssistNow\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Workdo\Hrm\Entities\Employee;


class TaskAssignment extends Model
{
    use HasFactory;
    
    protected $fillable = ['employee_id', 'client_id', 'service_id', 'service_date', 'time_spent', 'custom_price', 'total_charge'];
    protected $casts = [
        'service_date' => 'date',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function client() {
        return $this->belongsTo(AssistnowClient::class);
    }

    public function service() {
        return $this->belongsTo(AssistnowService::class);
    }

    public function calculateCharge()
    {
        $billingDuration = $this->service->billing_interval; // Get billing duration in mins
        return ceil($this->time_spent / $billingDuration) * $this->service_charge;
    }
}
