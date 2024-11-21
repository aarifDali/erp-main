<?php

namespace Workdo\Holidayz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\Entities\RoomBookingCart;
use Workdo\Holidayz\Entities\BookingCoupons;
use Workdo\Holidayz\Entities\Hotels;
use App\Models\Utility;

class RoomBookingCheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug)
    {
        $hotel = Hotels::where('slug', $slug)->where('is_active', '1')->first();

        if($hotel){
            if(!auth()->guard('holiday')->user()) {
                $response = RoomBookingCart::cart_list_cookie();
                $response = json_decode(json_encode($response));
            } else {
                $response = RoomBookingCart::customer_cart_list_($hotel->workspace);
                $response = json_decode(json_encode($response));
            }

            $coupons = BookingCoupons::where(['workspace'=> $hotel->workspace,'is_active' => 1])->get();
            return view('holidayz::frontend.' . $hotel->theme_dir . '.checkout',['slug' => $slug,'response' => $response,'hotel' => $hotel,'coupons' => $coupons]);
        }else{
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('holidayz::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('holidayz::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('holidayz::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
