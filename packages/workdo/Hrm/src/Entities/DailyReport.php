<?php

namespace Workdo\Hrm\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'report_date', 'remarks', 'workspace', 'created_by'];

    public function tasks()
    {
        return $this->hasMany(DailyReportTask::class);
    }

    public function employees()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }

}
