<?php

namespace Workdo\Hrm\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReportTask extends Model
{
    use HasFactory;

    protected $fillable = ['daily_report_id', 'description', 'time_spent', 'status', 'attachment'];

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }
}
