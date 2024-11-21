<?php

namespace Workdo\Holidayz\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\Holidayz\Entities\RoomBooking;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RoomsBookingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['booking_number', 'total', 'payment_status', 'invoice'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('booking_number', function (RoomBooking $booking) {
                $html = '<a href="#" class="btn btn-sm btn-icon btn-outline-primary me-2"
                            data-bs-toggle="tooltip" title="" data-title="Invoice"
                            data-ajax-popup="true" data-size="lg"
                            data-url="' . route('pdf.view', $booking->id) . '" data-toggle="tooltip"
                            >';
                $html .= \Workdo\Holidayz\Entities\RoomBooking::bookingNumberFormat($booking->booking_number);
                $html .= '</a>';
                return $html;
            })
            ->filterColumn('booking_number', function ($query, $keyword) {
                $prefix         = !empty(company_setting('booking_prefix')) ? company_setting('booking_prefix') : '#BOOK';
                $formattedValue = str_replace($prefix, '', $keyword);
                $query->where('booking_number', ltrim($formattedValue, "0"));
            })
            ->editColumn('total', function (RoomBooking $booking) {
                return currency_format_with_sym($booking->amount_to_pay);
            })
            ->filterColumn('payment_status', function ($query, $keyword) {
                if (stripos('Paid', $keyword) !== false) {
                    $query->where('payment_status', 1);
                }
                elseif (stripos('Unpaid', $keyword) !== false) {
                    $query->orWhere('payment_status', 0);
                }
            })
            ->editColumn('payment_status', function (RoomBooking $booking) {
                if ($booking->payment_status == 1){
                    $html = '<span class="badge fix_badge bg-primary p-2 px-3">Paid</span>';
                }else{
                    $html = '<span class="badge fix_badge bg-danger p-2 px-3">Unpaid</span>';
                }
                return $html;
            })
            ->editColumn('invoice', function (RoomBooking $booking) {
                $html = '<a href="#" class="btn btn-sm btn-icon  bg-light-secondary me-2"
                            data-bs-toggle="tooltip" title="" data-bs-original-title="Invoice"
                            data-ajax-popup="true" data-size="lg" data-title="Invoice"
                            data-url="' . route('pdf.view', $booking->id) . '" data-toggle="tooltip"
                            title="{{ __("Invoice") }}">
                            <i class="fa fa-file-pdf"></i>
                        </a>';
                return $html;
            });

            if (\Laratrust::hasPermission('rooms booking show') ||
            \Laratrust::hasPermission('rooms booking edit') ||
            \Laratrust::hasPermission('rooms booking delete'))
            {
                $dataTable->addColumn('action', function (RoomBooking $booking) {
                    return view('holidayz::booking.hotel_room_booking_action', compact('booking'));
                });
                $rowColumn[] = 'action';
            }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(RoomBooking $model, Request $request): QueryBuilder
    {
        $roomBooking = $model->with(['getUserDetails', 'getRoomDetails', 'GetBookingOrderDetails'])->where('workspace',getActiveWorkSpace());
        if ($request->type == 1 && $request->type != null) {
            $roomBooking = $roomBooking->where('payment_status', 1);
        } elseif ($request->type == 0 && $request->type != null) {
            $roomBooking = $roomBooking->where('payment_status', 0);
        }

        if ($request->check_in) {
            $roomBooking = $roomBooking->whereRelation('GetBookingOrderDetails', 'check_in', '>=', $request->check_in);
        }
        if ($request->check_out) {
            $roomBooking = $roomBooking->whereRelation('GetBookingOrderDetails', 'check_out', '<=', $request->check_out);
        }
        return $roomBooking;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('hotel-room-booking-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => 'function(d) {
                    var check_in = $("input[name=check_in]").val()
                    d.check_in = check_in

                    var check_out = $("input[name=check_out]").val()
                    d.check_out = check_out

                    var type = $("select[name=type]").val();
                    d.type = type
                }',
            ])
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

                                         $("body").on("click", "#applyfilter", function() {

                                            if (!$("input[name=check_in]").val() && !$("input[name=check_out]").val() && !$("select[name=type]").val()) {
                                                toastrs("Error!", "Please select Atleast One Filter ", "error");
                                                return;
                                            }

                                            $("#hotel-room-booking-table").DataTable().draw();
                                        });

                                        $("body").on("click", "#clearfilter", function() {
                                            $("input[name=check_in]").val("")
                                            $("input[name=check_out]").val("")
                                            $("select[name=type]").val("")
                                            $("#hotel-room-booking-table").DataTable().draw();
                                        });

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
            Column::make('booking_number')->title(__('Booking No.')),
            Column::make('total')->title(__('Rent')),
            Column::make('payment_method')->title(__('Paid Via')),
            Column::make('payment_status')->title(__('Payment Status'))->addClass('text-capitalize'),
            Column::make('invoice')->title(__('Invoice'))->searchable(false)->orderable(false)->exportable(false)->printable(false),
        ];

        if (\Laratrust::hasPermission('rooms booking show') ||
            \Laratrust::hasPermission('rooms booking edit') ||
            \Laratrust::hasPermission('rooms booking delete')) {
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
        return 'RoomsBooking_' . date('YmdHis');
    }
}
