<?php

namespace Workdo\AssistNow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\AssistNow\Entities\AssistnowClient;
use Workdo\AssistNow\Entities\AssistnowService;
use Workdo\AssistNow\Entities\TaskAssignment;
use Workdo\Hrm\Entities\Employee;

class TaskAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = TaskAssignment::with(['employee', 'client', 'service'])->where(['workspace'=>getActiveWorkSpace()])->paginate(10);
        return view('assistnow::taskAssignment.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where(['workspace' => getActiveWorkSpace()])->get();
        $clients   = AssistnowClient::where(['created_by' => creatorId(), 'workspace' => getActiveWorkSpace()])->get();
        $services  = AssistnowService::where(['created_by' => creatorId(), 'workspace' => getActiveWorkSpace()])->get();

        return view('assistnow::taskAssignment.create', compact('employees', 'clients', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id'    => 'required|exists:employees,id',
            'client_id'      => 'required|exists:assistnow_clients,id',
            'service_id'     => 'required|exists:assistnow_services,id',
            'fund_recieved'  => 'required|numeric|min:0',
            'service_date'   => 'required',
            'time_spent'     => 'required|integer|min:1',
            'service_charge' => 'required|numeric|min:0',
            'total_charge'   => 'required|numeric|min:0',
        ]);
    
        $task = new TaskAssignment();
        $task->employee_id    = $request->employee_id;
        $task->client_id      = $request->client_id;
        $task->service_id     = $request->service_id;
        $task->fund_recieved  = $request->fund_recieved;
        $task->service_date   = $request->service_date;
        $task->time_spent     = $request->time_spent;
        $task->service_charge = $request->service_charge;
        $task->total_charge   = $request->total_charge;
        $task->workspace      = getActiveWorkSpace();
        $task->created_by     = creatorId();
        $task->save();
    
        return redirect()->route('task-assignments.index')->with('success', 'Task assignment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = TaskAssignment::findOrFail($id);
        $employees = Employee::where(['created_by' => creatorId(), 'workspace' => getActiveWorkSpace()])->get();
        $clients   = AssistnowClient::where(['created_by' => creatorId(), 'workspace' => getActiveWorkSpace()])->get();
        $services  = AssistnowService::where(['created_by' => creatorId(), 'workspace' => getActiveWorkSpace()])->get();

        return view('assistnow::taskAssignment.edit', compact('task', 'employees', 'clients', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'employee_id'    => 'required|exists:employees,id',
            'client_id'      => 'required|exists:assistnow_clients,id',
            'service_id'     => 'required|exists:assistnow_services,id',
            'fund_recieved'  => 'required|numeric|min:0',
            'service_date'   => 'required',
            'time_spent'     => 'required|integer|min:1',
            'service_charge' => 'required|numeric|min:0',
            'total_charge'   => 'required|numeric|min:0',
        ]);

        $task = TaskAssignment::findOrFail($id);
        $task->service_charge = $request->service_charge;
        $task->fund_recieved = $request->fund_recieved;
        $task->update([
            'employee_id' => $request->employee_id,
            'client_id' => $request->client_id,
            'service_id' => $request->service_id,
            'time_spent' => $request->time_spent,
            'service_date'=>$request->service_date,
            // 'service_charge' => $request->service_charge,
            'total_charge' => $request->total_charge,
        ]);

        return redirect()->route('task-assignments.index')->with('success', 'Task updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = TaskAssignment::findOrFail($id);
        $task->delete();

        return redirect()->route('task-assignments.index')->with('success', __('The Task deleted successfully.'));
    }
}
