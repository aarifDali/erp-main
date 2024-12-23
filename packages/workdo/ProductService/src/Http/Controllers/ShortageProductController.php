<?php

namespace Workdo\ProductService\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Workdo\ProductService\DataTables\ShortageProductDataTable;


class ShortageProductController extends Controller
{
    public function index(ShortageProductDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('product&service manage')) {
            return $dataTable->render('product-service::shortage_product');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
