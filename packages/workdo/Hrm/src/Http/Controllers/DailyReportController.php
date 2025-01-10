<?php

namespace Workdo\Hrm\Http\Controllers;

use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Workdo\Hrm\DataTables\DailyReportDataTable;
use Workdo\Hrm\Entities\DailyReport;
use Workdo\Hrm\Entities\DailyReportTask;

class DailyReportController extends Controller
{
    public function index(DailyReportDataTable $dataTable) {
        $currentWorkspace = getActiveWorkSpace();
        $todayDailyReport = DailyReport::whereDate('created_at', now()->toDateString())
        ->where('user_id', Auth::user()->id)
        ->with('tasks')->first();
        return $dataTable->render('hrm::dailyreport.index',compact('todayDailyReport'));
    }

    

    public function create() {
        return view('hrm::dailyreport.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'tasks' => 'required|array|min:1',
            'tasks.*.description' => 'required|string',
            'tasks.*.start_time' => 'date_format:H:i',
            'tasks.*.end_time' => 'date_format:H:i|after:tasks.*.start_time',
            'tasks.*.status' => 'required|string|in:In Progress,Completed,Pending',
            'tasks.*.attachment' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'remarks' => 'nullable|string',
            'workspace' => 'nullable|integer',
            'created_by' => 'integer',
        ]);

        $dailyReport = DailyReport::create([
            'user_id' => Auth::user()->id,
            'report_date' => $validated['report_date'],
            'remarks' => $validated['remarks'] ?? null,
            'workspace' => getActiveWorkSpace(),
            'created_by' => creatorId(),
            
        ]);

        foreach ($validated['tasks'] as $task) {
            $attachmentPath = null;
            if (isset($task['attachment']) && $task['attachment'] instanceof \Illuminate\Http\UploadedFile) {
                $fileName = time() . '_' . $task['attachment']->getClientOriginalName();
        
                $path = $task['attachment']->storeAs('attachments', $fileName, 'public');
        
                if (!$path) {
                    return response()->json(['error' => 'File upload failed'], 500);
                }
        
                $attachmentPath = $fileName; 
            }

            DailyReportTask::create([
                'daily_report_id' => $dailyReport->id,
                'description' => $task['description'],
                'start_time' => $task['start_time'],
                'end_time' => $task['end_time'],
                'status' => $task['status'],
                'attachment' => $attachmentPath,
            ]);
        }

        return redirect()->route('daily-report.index')
            ->with('success', 'Daily Report created successfully!');
    }

    public function show($id)
    {
        $dailyReport = DailyReport::with([
            'tasks',
            'employees.employee.branch',
            'employees.employee.department',
            'employees.employee.designation',
        ])->findOrFail($id);

        $employee = $dailyReport->employees->employee;

        $companySettings = getCompanyAllSetting();

        return view('hrm::dailyreport.show', compact('dailyReport', 'employee', 'companySettings'));
    }


    public function edit($id)
    {
        $dailyReport = DailyReport::with('tasks')->findOrFail($id);

        return view('hrm::dailyreport.edit', compact('dailyReport'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'tasks' => 'required|array|min:1',
            'tasks.*.id' => 'nullable|integer|exists:daily_report_tasks,id',
            'tasks.*.description' => 'required|string',
            'tasks.*.start_time' => 'nullable|date_format:H:i',
            'tasks.*.end_time' => 'nullable|date_format:H:i|after:tasks.*.start_time',
            'tasks.*.status' => 'required|string|in:In Progress,Completed,Pending',
            'tasks.*.attachment' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'tasks.*.existing_attachment' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $dailyReport = DailyReport::findOrFail($id);

        $dailyReport->update([
            'report_date' => $validated['report_date'],
            'remarks' => $validated['remarks'] ?? $dailyReport->remarks,
        ]);

        $existingTaskIds = $dailyReport->tasks->pluck('id')->toArray();
        $updatedTaskIds = [];
        foreach ($validated['tasks'] as $task) {
            $attachmentPath = null;
            
            if(!empty($task['attachment'])) {
                $file = $task['attachment'];
                $fileName = time().'_'.$task['attachment']->getClientOriginalName();
                $file->storeAs('attachments', $fileName, 'public');
                $attachmentPath = $fileName;
            } elseif (!empty($task['existing_attachment'])) {
                $attachmentPath = $task['existing_attachment'];
            }
        
            if (!empty($task['id']) && in_array($task['id'], $existingTaskIds)) {
                $existingTask = DailyReportTask::findOrFail($task['id']);
                $existingTask->update([
                    'description' => $task['description'],
                    'start_time' => $task['start_time'],
                    'end_time' => $task['end_time'],
                    'status' => $task['status'],
                    'attachment' => $attachmentPath, 
                ]);
                $updatedTaskIds[] = $task['id']; 
            } else {
                DailyReportTask::create([
                    'daily_report_id' => $dailyReport->id,
                    'description' => $task['description'],
                    'start_time' => $task['start_time'],
                    'end_time' => $task['end_time'],
                    'status' => $task['status'],
                    'attachment' => $attachmentPath,
                ]);
            }
        }
        
        $tasksToDelete = array_diff($existingTaskIds, $updatedTaskIds);
        DailyReportTask::whereIn('id', $tasksToDelete)->delete();

        return redirect()->route('daily-report.index')
            ->with('success', 'Daily Report updated successfully!');
    }


    public function destroy($id)
    {
        $dailyReport = DailyReport::with('tasks')->findOrFail($id);

        foreach ($dailyReport->tasks as $task) {
            if (!empty($task->attachment)) {
                Storage::disk('public')->delete('attachments/' . $task->attachment);
            }
            $task->delete(); 
        }
        $dailyReport->delete();

        return redirect()->route('daily-report.index')
            ->with('success', 'Daily Report deleted successfully!');
    }

}



