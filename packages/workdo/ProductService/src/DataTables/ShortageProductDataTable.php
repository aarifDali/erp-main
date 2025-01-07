<?php 

namespace Workdo\ProductService\DataTables;

use Workdo\ProductService\Entities\ShortageProduct;
use Illuminate\Support\Facades\Auth;
use Workdo\ProductService\Entities\ProductService;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Builder as HtmlBuilder;


class ShortageProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $rowColumn = ['image', 'sale_price', 'purchase_price', 'quantity', 'action'];
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                // Determine the image path
                $path = check_file($row->productService->image) 
                    ? get_file($row->productService->image) 
                    : asset('packages/workdo/ProductService/src/Resources/assets/image/img01.jpg');
    
                // Return image with clickable link
                return '<a href="' . $path . '" target="_blank">
                            <img src="' . $path . '" class="rounded border-2 border-primary" style="width:100px;" id="blah3">
                        </a>';
            })
            ->addColumn('name', function ($row) {
                return $row->productService->name;
            })
            ->addColumn('sku', function ($row) {
                return $row->productService->sku;
            })
            ->addColumn('quantity', function ($row) {
                return $row->productService->quantity;
            })
            ->addColumn('sale_price', function ($row) {
                return currency_format_with_sym($row->productService->sale_price);
            })
            ->addColumn('purchase_price', function ($row) {
                return currency_format_with_sym($row->productService->purchase_price);
            })
            ->addColumn('reorder_qty', function ($row) {
                return $row->productService->reorder_qty;
            })
            
            ->addColumn('action', function ($row) {
                return '<a href="' . route('shortage-product.show', $row->id) . '" 
                            class="btn btn-sm btn-warning" 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="top" 
                            title="' . __('View') . '">
                            <i class="fas fa-eye"></i>
                        </a>';
            })
            ->rawColumns($rowColumn); // For rendering HTML in action column
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ShortageProduct $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ShortageProduct $model)
    {
        return $model->newQuery()->with('productService'); // Eager load the related product service
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        $dataTable =  $this->builder()
            ->setTableId('shortage-products-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->dom('Bfrtip') // Optional DOM configuration
            ->orderBy(1)
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

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('No')
            ->title(__('No'))
            ->data('DT_RowIndex')
            ->name('DT_RowIndex')
            ->searchable(false)
            ->orderable(false),
            ['data' => 'image', 'image' => 'productService.image', 'title' => __('Image')],
            ['data' => 'name', 'name' => 'productService.name', 'title' => __('Name')],
            ['data' => 'sku', 'name' => 'productService.sku', 'title' => __('SKU')],
            ['data' => 'quantity', 'name' => 'productService.quantity', 'title' => __('QTY')],
            ['data' => 'sale_price', 'name' => 'productService.sale_price', 'title' => __('Sale Price')],
            ['data' => 'purchase_price', 'name' => 'productService.purchase_price', 'title' => __('Purchase Price')],
            ['data' => 'reorder_qty', 'name' => 'productService.reorder_qty', 'title' => __('Reorder QTY')],
            // ['data' => 'action', 'name' => 'action', 'title' => __('Action'), 'orderable' => false, 'searchable' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ShortageProducts_' . date('YmdHis');
    }
}

