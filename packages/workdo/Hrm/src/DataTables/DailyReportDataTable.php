<?php

namespace Workdo\Hrm\DataTables;

use Illuminate\Support\Facades\Auth;
use Workdo\Hrm\Entities\DailyReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DailyReportDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['report_date'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn();
        $dataTable->editColumn('user_id', function (DailyReport $daily_report) {
            return $daily_report->user_id ? $daily_report->employees->name ?? '-' : '-';
            $rowColumn[] = 'user_id';
        })

        ->filterColumn('user_id', function ($query, $keyword) {
            $query->whereHas('employees', function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            });
        });
        $dataTable->editColumn('report_date', function (DailyReport $daily_report) {
            return $daily_report->report_date ? company_date_formate($daily_report->report_date) ?? '-' : '-';
        })
        ->editColumn('remarks', function (DailyReport $daily_report) {
            return $daily_report->remarks ?? '-';
        });

        $dataTable->addColumn('action', function (DailyReport $daily_report) {
            return view('hrm::dailyreport.button', compact('daily_report'));
        });
        $rowColumn[] = 'action';
       
        return $dataTable->rawColumns($rowColumn);

    }

    public function query(DailyReport $model, Request $request): QueryBuilder
    {
        $employee = User::where('workspace_id', getActiveWorkSpace())
            ->leftjoin('employees', 'users.id', '=', 'employees.user_id')
            ->where('users.created_by', creatorId())->emp()
            ->select('users.id');
        
        $employee = $employee->get()->pluck('id');

        $daily_report = DailyReport::whereIn('user_id', $employee)
            ->where('workspace', getActiveWorkSpace())
            ->with('employees');

        if (Auth::user()->type == 'staff') {
            $daily_report = $daily_report->where('user_id', Auth::user()->id);
        }

        return $daily_report;
    }

    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('daily-report-table')
            ->columns($this->getColumns())
            ->orderBy(0)
                ->language([
                    "paginate" => [
                        "next" => '<i class="ti ti-chevron-right"></i>',
                        "previous" => '<i class="ti ti-chevron-left"></i>'
                    ],
                    'lengthMenu' => __("_MENU_") . __('Entries Per Page'),
                    "searchPlaceholder" => __('Search...'),
                    "search" => "",
                    "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')
                ]);
        $exportButtonConfig = [
            'extend' => 'collection',
            'className' => 'btn btn-light-secondary dropdown-toggle',
            'text' => '<i class="ti ti-download me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Export"></i>',
            'buttons' => [
                [
                    'extend' => 'print',
                    'text' => '<i class="fas fa-print me-2"></i> ' . __('Print'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'csv',
                    'text' => '<i class="fas fa-file-csv me-2"></i> ' . __('CSV'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'excel',
                    'text' => '<i class="fas fa-file-excel me-2"></i> ' . __('Excel'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
            ],
        ];

        $buttonsConfig = array_merge([
            $exportButtonConfig,
            [
                'extend' => 'reset',
                'className' => 'btn btn-light-danger',
            ],
            [
                'extend' => 'reload',
                'className' => 'btn btn-light-warning',
            ],
        ]);

        $dataTable->parameters([
            "dom" =>  "
        <'dataTable-top'<'dataTable-dropdown page-dropdown'l><'dataTable-botton table-btn dataTable-search tb-search  d-flex justify-content-end gap-2'Bf>>
        <'dataTable-container'<'col-sm-12'tr>>
        <'dataTable-bottom row'<'col-5'i><'col-7'p>>",
            'buttons' => $buttonsConfig,
            "drawCallback" => 'function( settings ) {
                var tooltipTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=tooltip]")
                  );
                  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                  });
                  var popoverTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=popover]")
                  );
                  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                  });
                  var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                  var toastList = toastElList.map(function (toastEl) {
                    return new bootstrap.Toast(toastEl);
                  });
            }'
        ]);

        $dataTable->language([
            'buttons' => [
                'create' => __('Create'),
                'export' => __('Export'),
                'print' => __('Print'),
                'reset' => __('Reset'),
                'reload' => __('Reload'),
                'excel' => __('Excel'),
                'csv' => __('CSV'),
            ]
        ]);

        return $dataTable;
    }

    public function getColumns() 
    {
        $column = [
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('report_date')->title(__('Date')),
            Column::make('remarks')->title(__('Remarks')),
        ];
        if (
            \Laratrust::hasPermission('attendance edit') ||
            \Laratrust::hasPermission('attendance delete')
            ) {
                $employee = [   
                Column::make('user_id')->title(__('Employee')),

                
            ];
            array_splice($column, 2, 0, $employee); 
        }
        $action = [
            Column::computed('action')
                ->title(__('Action'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                
        ];

        $column = array_merge($column, $action);

        return $column;
    }

    protected function filename(): string
    {
        return 'Mark Attendance_' . date('YmdHis');
    }
}