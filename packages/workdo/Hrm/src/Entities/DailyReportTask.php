<?php

namespace Workdo\Hrm\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReportTask extends Model
{
    use HasFactory;

    protected $fillable = ['daily_report_id', 'description', 'start_time', 'end_time', 'status', 'attachment'];
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }
}
