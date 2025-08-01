<?php

namespace Workdo\AssistNow\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Workdo\AssistNow\Entities\TaskAssignment;
use Workdo\Hrm\Entities\Employee;

class StaffReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $employeeId = $request->input('employee_id');

        $query = TaskAssignment::with(['employee', 'service'])->where(['workspace'=>getActiveWorkSpace()]);

        if ($startDate) {
            $query->whereDate('service_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('service_date', '<=', $endDate);
        }
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $tasks = $query->orderBy('service_date', 'asc')->get();

        $totalCharge = $tasks->sum('total_charge');
        $totalFundReceived = $tasks->sum('fund_recieved');
        $profit = $totalFundReceived - $totalCharge;

        $employees = Employee::where(['workspace' => getActiveWorkSpace()])->get();

        return view('assistnow::reports.staff', compact('tasks', 'totalCharge', 'totalFundReceived', 'profit', 'employees'));
    }

}
