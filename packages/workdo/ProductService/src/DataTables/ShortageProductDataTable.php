<?php 

// namespace Workdo\ProductService\DataTables;

// use Workdo\ProductService\Entities\ShortageProduct;
// use Illuminate\Database\Eloquent\Builder as QueryBuilder;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Yajra\DataTables\EloquentDataTable;
// use Yajra\DataTables\Html\Builder as HtmlBuilder;
// use Yajra\DataTables\Html\Button;
// use Yajra\DataTables\Html\Column;
// use Yajra\DataTables\Html\Editor\Editor;
// use Yajra\DataTables\Html\Editor\Fields;
// use Yajra\DataTables\Services\DataTable;


// class ShortageProductDataTable extends DataTable
// {
//     /**
//      * Build the DataTable class.
//      *
//      * @param QueryBuilder $query Results from query() method.
//      */
//     public function dataTable(QueryBuilder $query): EloquentDataTable
//     {
//         $rowColumn = ['image', 'sale_price', 'purchase_price', 'tax_id', 'category_id', 'unit_id', 'quantity', 'reorder_qty'];
//         $dataTable = (new EloquentDataTable($query))
//             ->addIndexColumn()
//             ->editColumn('product_service_id', function (ShortageProduct $shortageProduct) {
//                 $productService = $shortageProduct->productService; // Access the related ProductService
//                 $imagePath = check_file($productService->image) ? get_file($productService->image) : asset('packages/workdo/ProductService/src/Resources/assets/image/img01.jpg');
    
//                 return '<a href="' . $imagePath . '" target="_blank">
//                             <img src="' . $imagePath . '" class="rounded border-2 border border-primary" style="width:100px;" id="blah3">
//                         </a>';
//             })

//             ->editColumn('sale_price', function (ShortageProduct $shortageProduct) {
//                 return currency_format_with_sym($shortageProduct->productService->sale_price);
//             })
        
//             ->editColumn('purchase_price', function (ShortageProduct $shortageProduct) {
//                 return currency_format_with_sym($shortageProduct->productService->purchase_price);
//             })
//             ->editColumn('tax_id', function (ShortageProduct $shortageProduct) {
//                 return str_replace(',', ',<br>', $shortageProduct->productService->tax_names);
//             })
//             ->editColumn('category_id', function (ShortageProduct $shortageProduct) {
//                 return optional($shortageProduct->productService->categorys)->name ?? '';
//             })
//             ->filterColumn('category_id', function ($query, $keyword) {
//                 $query->whereHas('category', function ($q) use ($keyword) {
//                     $q->where('name', 'like', "%$keyword%");
//                 });
//             })
//             ->filterColumn('unit_id', function ($query, $keyword) {
//                 $query->whereHas('units', function ($q) use ($keyword) {
//                     $q->where('name', 'like', "%$keyword%");
//                 });
//             })
//             ->filterColumn('tax_id', function ($query, $keyword) {
//                 $query->where('taxes.name', 'like', "%$keyword%");
//             })
//             ->editColumn('unit_id', function (ShortageProduct $shortageProduct) {
//                 return optional($shortageProduct->productService->units)->name ?? '';
//             })
//             ->editColumn('quantity', function (ShortageProduct $shortageProduct) {
//                 if ($shortageProduct->productService->type == 'product' || $shortageProduct->productService->type == 'parts' || $shortageProduct->productService->type == 'consignment' || $shortageProduct->productService->type == 'rent' || $shortageProduct->productService->type == 'music institute') {
//                     $quantity = $shortageProduct->productService->quantity;
//                 } else {
//                     $quantity = '-';
//                 }
//                 return $quantity;
//             });
//         return $dataTable->rawColumns($rowColumn);
        
//     }

//     /**
//      * Get the query source of dataTable.
//      */
//     public function query(ShortageProduct $model, Request $request)
//     {
//         DB::statement('SET SESSION group_concat_max_len = 1000000');

//         $shortageProducts = $model->select(
//                 'shortage_products.id as shortage_product_id',
//                 'product_services.image',
//                 'product_services.sale_price',
//                 'product_services.purchase_price',
//                 'product_services.tax_id',
//                 'product_services.category_id',
//                 'product_services.unit_id',
//                 'product_services.quantity',
//                 'product_services.reorder_qty',
//                 'product_services.name as product_service_name',
//                 'product_services.sku',
//                 'product_services.type',
//                 DB::raw('GROUP_CONCAT(taxes.name SEPARATOR ", ") as tax_names')
//             )
//             ->leftJoin('product_services', 'product_services.id', '=', 'shortage_products.product_service_id')
//             ->leftJoin('taxes', function ($join) {
//                 $join->on('taxes.id', '=', DB::raw("SUBSTRING_INDEX(SUBSTRING_INDEX(product_services.tax_id, ',', numbers.n), ',', -1)"))
//                     ->crossJoin(DB::raw('(SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) numbers'))
//                     ->whereRaw('CHAR_LENGTH(product_services.tax_id) - CHAR_LENGTH(REPLACE(product_services.tax_id, ",", "")) + 1 >= numbers.n');
//             })
//             ->where('product_services.created_by', creatorId())
//             ->where('product_services.workspace_id', getActiveWorkSpace())
//             ->groupBy('shortage_products.id');

//         if (!empty($request->category)) {
//             $shortageProducts->where('product_services.category_id', $request->category);
//         }

//         if (!empty($request->item_type)) {
//             $shortageProducts->where('product_services.type', $request->item_type);
//         }

//         return $shortageProducts->with(['productService.categorys', 'productService.units']);
//     }



//     /**
//      * Optional method if you want to use the html builder.
//      */
//     public function html(): HtmlBuilder
//     {
//         return $this->builder()
//                     ->setTableId('shortageproduct-table')
//                     ->columns($this->getColumns())
//                     ->minifiedAjax()
//                     //->dom('Bfrtip')
//                     ->orderBy(1)
//                     ->language([
//                         "paginate" => [
//                             "next" => '<i class="ti ti-chevron-right"></i>',
//                             "previous" => '<i class="ti ti-chevron-left"></i>'
//                         ],
//                         'lengthMenu' => __("_MENU_") . __('Entries Per Page'),
//                         "searchPlaceholder" => __('Search...'),
//                         "search" => "",
//                         "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')
//                     ])
//                     ->selectStyleSingle()
//                     ->buttons([
//                         Button::make('excel'),
//                         Button::make('csv'),
//                         Button::make('pdf'),
//                         Button::make('print'),
//                         Button::make('reset'),
//                         Button::make('reload')
//                     ]);
//     }

//     /**
//      * Get the dataTable columns definition.
//      */
//     public function getColumns(): array
//     {
//         return [
//             Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
//             Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
//             Column::make('image')->title(__('Image'))->orderable(false)->searchable(false),
//             Column::make('name')->title(__('Name')),
//             Column::make('sku')->title(__('Sku')),
//             Column::make('sale_price')->title(__('Sale Price')),
//             Column::make('purchase_price')->title(__('Purchase Price')),
//             Column::make('tax_id')->title(__('Tax')),
//             Column::make('category_id')->title(__('Category')),
//             Column::make('unit_id')->title(__('Unit')),
//             Column::make('quantity')->title(__('Quantity')),
//             Column::make('reorder_qty')->title(__('Reorder Qty')),
//             Column::make('type')->title(__('Type')),
//             Column::computed('action')
//                 ->exportable(false)
//                 ->printable(false)
//                 ->width(60)
                
//         ];
//     }

//     /**
//      * Get the filename for export.
//      */
//     protected function filename(): string
//     {
//         return 'ShortageProduct_' . date('YmdHis');
//     }
// }

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

