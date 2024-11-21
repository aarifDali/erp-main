<?php

namespace Workdo\Holidayz\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\Holidayz\Entities\Rooms;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RoomsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['image', 'type', 'name', 'base_price', 'final_price', 'is_active'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('image', function (Rooms $room) {
                if (check_file('uploads/rooms/'.$room->image) == false) {
                    $path = asset('packages/workdo/Holidayz/src/Resources/assets/image/img01.jpg');
                } else {
                    $path = 'uploads/rooms/'.$room->image;
                }
                $html = '<div class="image-fixsize"><a href="' . $path . '" target="_blank">
                            <img id="image" src="' . $path . '" class="wid-75 rounded border border-primary border-2 me-3 big-logo room-image img-fluid" width="35" >
                        </a></div>';
                return $html;
            })
            ->editColumn('type', function (Rooms $room) {
                return $room->apartmentType->name ?? '-';
            })
            ->editColumn('name', function (Rooms $room) {
                return $room->room_type ?? '-';
            })
            ->editColumn('base_price', function (Rooms $room) {
                return currency_format_with_sym($room->base_price);
            })
            ->editColumn('final_price', function (Rooms $room) {
                return currency_format_with_sym($room->final_price);
            })
            ->filterColumn('is_active', function ($query, $keyword) {
                if (stripos('Active', $keyword) !== false) {
                    $query->where('is_active', 1);
                }
                elseif (stripos('In Active', $keyword) !== false) {
                    $query->orWhere('is_active', 0);
                }
            })
            ->editColumn('is_active', function (Rooms $room) {
                if ($room->is_active == 1){
                    $html = '<span class="badge fix_badge bg-primary p-2 px-3">Active</span>';
                }else{
                    $html = '<span class="badge fix_badge bg-danger p-2 px-3">In Active</span>';
                }
                return $html;
            });

            if (\Laratrust::hasPermission('rooms edit') ||
            \Laratrust::hasPermission('rooms delete'))
            {
                $dataTable->addColumn('action', function (Rooms $room) {
                    return view('holidayz::rooms.hotel_room_action', compact('room'));
                });
                $rowColumn[] = 'action';
            }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Rooms $model): QueryBuilder
    {
        return $model->where('created_by', creatorId())->where('workspace',getActiveWorkSpace());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('hotel-rooms-table')
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
            Column::make('image')->title(__('Image'))->searchable(false)->orderable(false)->exportable(false),
            Column::make('type')->title(__('Type')),
            Column::make('room_type')->title(__('Name')),
            Column::make('adults')->title(__('Adults')),
            Column::make('children')->title(__('Children')),
            Column::make('total_room')->title(__('Total Rooms')),
            Column::make('base_price')->title(__('Base price')),
            Column::make('final_price')->title(__('Final price')),
            Column::make('is_active')->title(__('Status'))->addClass('text-capitalize'),
        ];

        if (\Laratrust::hasPermission('rooms edit') ||
            \Laratrust::hasPermission('rooms delete')) {
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
        return 'Rooms_' . date('YmdHis');
    }
}
