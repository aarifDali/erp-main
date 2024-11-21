<?php

namespace Workdo\Holidayz\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Workdo\Holidayz\Entities\RoomsFacilities;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RoomFacilitiesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['short_description', 'tax_id', 'status'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('short_description', function (RoomsFacilities $facility) {
                return wordwrap($facility->short_description,150,"<br>\n");
            })
            ->editColumn('tax_id', function (RoomsFacilities $facility) {
                if ($facility->tax_names) {
                    return str_replace(',', ',<br>', $facility->tax_names);
                }else{
                    return '-';
                }
            })
            ->filterColumn('tax_id', function ($query, $keyword) {
                $query->where('taxes.name', 'like', "%$keyword%");
            })
            ->filterColumn('status', function ($query, $keyword) {
                if (stripos('Active', $keyword) !== false) {
                    $query->where('status', 1);
                }
                elseif (stripos('In Active', $keyword) !== false) {
                    $query->orWhere('status', 0);
                }
            })
            ->editColumn('status', function (RoomsFacilities $facility) {
                if ($facility->status == 1){
                    $html = '<span class="badge fix_badge bg-primary p-2 px-3">Active</span>';
                }else{
                    $html = '<span class="badge fix_badge bg-danger p-2 px-3">In Active</span>';
                }
                return $html;
            });

            if (\Laratrust::hasPermission('facilities edit') ||
            \Laratrust::hasPermission('facilities delete'))
            {
                $dataTable->addColumn('action', function (RoomsFacilities $facility) {
                    return view('holidayz::facilities.hotel_room_facilities_action', compact('facility'));
                });
                $rowColumn[] = 'action';
            }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(RoomsFacilities $model): QueryBuilder
    {
        return $model->select('rooms_facilities.*', DB::raw('GROUP_CONCAT(taxes.name) as tax_names'))
            ->leftJoin('taxes', function ($join) {
                $join->on('taxes.id', '=', DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(rooms_facilities.tax_id, ',', numbers.n), ',', -1)"))
                    ->crossJoin(DB::raw('(SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) numbers'))
                    ->whereRaw('CHAR_LENGTH(rooms_facilities.tax_id) - CHAR_LENGTH(REPLACE(rooms_facilities.tax_id, ",", "")) + 1 >= numbers.n');
            })
            ->where('rooms_facilities.created_by', creatorId())
            ->where('rooms_facilities.workspace', getActiveWorkSpace())
            ->groupBy('rooms_facilities.id');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('hotel-room-facilities-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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
            ])
            ->initComplete('function() {
                var table = this;
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }');

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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('name')->title(__('Name')),
            Column::make('short_description')->title(__('Short Description')),
            Column::make('tax_id')->title(__('Tax')),
            Column::make('status')->title(__('Status'))->addClass('text-capitalize'),
        ];

        if (\Laratrust::hasPermission('facilities edit') ||
            \Laratrust::hasPermission('facilities delete')) {
            $columns[] = Column::computed('action')
                ->title(__('Action'))
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ;
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'RoomFacilities_' . date('YmdHis');
    }
}
