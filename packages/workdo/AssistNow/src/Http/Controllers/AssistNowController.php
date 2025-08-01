<?php

namespace Workdo\AssistNow\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Workdo\AssistNow\Entities\AssistnowClient;
use Workdo\AssistNow\Entities\AssistnowService;
use Workdo\AssistNow\Entities\TaskAssignment;
use Workdo\Hrm\Entities\Employee;

use function Termwind\render;

class AssistNowController extends Controller
{
    public function index() {
        
        $clients = AssistnowClient::where(['workspace' => getActiveWorkSpace()])->count();
        $emp     = User::where('created_by', '=', Auth::user()->id)->emp()->where('workspace_id', getActiveWorkSpace())->get()->toArray();
        $countEmployee = count($emp);
        $tasks = TaskAssignment::where(['workspace' => getActiveWorkSpace()])->count();
        $services = AssistnowService::where(['workspace' => getActiveWorkSpace()])->count();
        $latestCustomers = AssistnowClient::where(['workspace' => getActiveWorkSpace()])->orderBy('id', 'Desc')->latest()->take(5)->get();
        $transdate   = date('Y-m-d', time());
        $completedTasks = TaskAssignment::where(['workspace' => getActiveWorkSpace()])
            ->whereMonth('service_date', date('m'))
            ->whereYear('service_date', date('Y'))
            ->selectRaw('DATE(service_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format data for chart
        $taskData = [
            'dates' => $completedTasks->pluck('date'),
            'counts' => $completedTasks->pluck('count')
        ];
        $data =[
            'clients' => $clients,
            'countEmployees' => $countEmployee,
            'tasks' => $tasks,
            'services' => $services,
            'transdate' => $transdate,
            'taskData' => $taskData,
            'latestCustomers' => $latestCustomers,
        ];
        return view('assistnow::dashboard.dashboard', $data);
    }    
}
