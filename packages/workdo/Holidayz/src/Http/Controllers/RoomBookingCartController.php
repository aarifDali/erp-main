<?php

namespace Workdo\Holidayz\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\Entities\RoomBookingCart;
use Workdo\Holidayz\Entities\RoomsChildFacilities;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\Rooms;
use Illuminate\Support\Facades\Cookie;

class RoomBookingCartController extends Controller
{
    public $serviceIds = [];

    public function addToCart(Request $request,$slug)
    {
        if($request->date){
            $date = explode('to', $request->date);
            if(!empty($date[0]) && !empty($date[1])){
                $startDate = trim($date[0]);
                $endDate = trim($date[1]);
                $value = [
                    'check_in' => $startDate,
                    'check_out' => $endDate,
                    'room' => $request->room
                ];
                session(['date' => $value]);
            }else{
                return response()->json([
                    'is_success' => false,
                    'message' => __('Please Select Check In - Check Out Both Date'),
                ],401);
            }
        }
        $hotel = Hotels::where('slug',$slug)->first();
        if($hotel){
            if (!auth()->guard('holiday')->user()) {
                $response =  RoomBookingCart::addtocart_cookie($request->room_id, $hotel->workspace,$request->all());
                return response()->json($response);
            }

            $cart = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id,'room_id' => $request->room_id,'workspace' => $hotel->workspace])->first();
            $room = Rooms::find($request->room_id);
            

            
            if(!$cart){
                
                $date = session('date');
                $toDate = Carbon::parse($date['check_in']);
                $fromDate = Carbon::parse($date['check_out']);
                $days = $toDate->diffInDays($fromDate);
                RoomBookingCart::create([
                    'room_id' => $request->room_id,
                    'customer_id' => auth()->guard('holiday')->user()->id,
                    'workspace' => $hotel->workspace,
                    'service_charge' => $request->serviceCharge,
                    'services' => $request->serviceIds,
                    'room' => $date['room'],
                    'price' => $room->final_price * $date['room'] * $days,
                    'check_in' => $date['check_in'],
                    'check_out' => $date['check_out']
                ]);


                $carts_array = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id,'workspace' => $hotel->workspace])->get();
                $priceSum = $serviceCharge =  $price = 0;
                foreach($carts_array as $key => $value){
                    $roomdata = Rooms::find($value->room_id);

                    $toDate = Carbon::parse($value->check_in);
                    $fromDate = Carbon::parse($value->check_out);
            
                    $days = $toDate->diffInDays($fromDate);
                    // $days = $days+1;

                    $price += $roomdata->final_price * $value->room * $days;    // * $days
                    $serviceCharge += $value->service_charge;   // * $days
                }
                $priceSum = $price;
                
                return response()->json([
                    'cart_count' => RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id,'workspace' => $hotel->workspace])->count(),
                    'is_success' => true,
                    'message' => __('Add To Cart Success'),
                    'data' => view('holidayz::frontend.' . $hotel->theme_dir . '.cart.popup', compact('hotel', 'slug','room','priceSum','carts_array','serviceCharge'))->render()
                ]);
                
            }else{

                $carts_array = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id,'workspace' => $hotel->workspace])->get();

                $priceSum = $serviceCharge =  $price = 0;
                foreach($carts_array as $key => $value){
                    $roomdata = Rooms::find($value->room_id);

                    $toDate = Carbon::parse($value->check_in);
                    $fromDate = Carbon::parse($value->check_out);
            
                    $days = $toDate->diffInDays($fromDate);
                    // $days = $days+1;

                    $priceSum += $roomdata->final_price * $value->room * $days; // * $days
                    $serviceCharge += $value->service_charge;   // * $days
                }

                return response()->json([
                    'cart_count' => RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id,'workspace' => $hotel->workspace])->count(),
                    'is_success' => false,
                    'message' => __('Add To Cart fail'),
                    'data' => view('holidayz::frontend.' . $hotel->theme_dir . '.cart.popup', compact('hotel', 'slug','room','priceSum','carts_array','serviceCharge'))->render()
                ]);
            }
        }
    }

    public function CartList(Request $request,$slug)
    {
        $hotel = Hotels::where('slug',$slug)->first();
        if($hotel){
            if(!auth()->guard('holiday')->user()) {
                
                $response = RoomBookingCart::cart_list_cookie();
                $response = json_decode(json_encode($response));
            } else {
                $response = RoomBookingCart::customer_cart_list_($hotel->workspace);
                $response = json_decode(json_encode($response));
            }

            $return['status'] = $response->status;
            $return['message'] = $response->message;
            $return['sub_total'] = 0;

            if($response->status == 1) {
                $return['count'] = $response->data->items;
                $return['sub_total'] = $response->data->cart_final_price;
                $return['html'] = view('holidayz::frontend.' . $hotel->theme_dir . '.cart.cartdrawer', compact('response', 'slug'))->render();
            }

            return response()->json($return);
        }
    }

    public function cart_remove(Request $request,$slug)
    {
        $hotel = Hotels::where('slug',$slug)->where('is_active', '1')->first();

        $cartCount = $total = $conveniencefees = $subtotal = 0;
        if(!auth()->guard('holiday')->user()) {
            $Carts = Cookie::get('cart');
            $Carts = json_decode($Carts, true);
            unset($Carts[$request->cart_id]);
            $cart_json = json_encode($Carts);
            $cartCount = count($Carts);
            Cookie::queue('cart', $cart_json, 1440);
            foreach ($Carts as $key => $value) {
                $total += $value['price'];
                $total += ($value['serviceCharge']) ? $value['serviceCharge'] : 0;
            }
        } else {
            RoomBookingCart::where('id', $request->cart_id)->delete();
            $Carts = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->get();
            foreach ($Carts as $key => $value) {
                $total += $value->price;
                $total += ($value->service_charge) ? $value->service_charge : 0;
            }
            $cartCount = $Carts->count();
        }

        $subtotal = $total + $conveniencefees;

        return response()->json([
            'total' => currency_format_with_sym($total,$hotel->created_by,$hotel->workspace),
            'conveniencefees' =>  currency_format_with_sym($conveniencefees,$hotel->created_by,$hotel->workspace),
            'subtotal' =>  currency_format_with_sym($subtotal,$hotel->created_by,$hotel->workspace),
            'count' => $cartCount,
            'is_success' => true,
            'message' => __('Remove cart successfully')
        ]);
    }

    public function addToservice(Request $request,$slug,$serviceId)
    {
        $finalPrice = (int)$request->finalPrice + (int)$request->servicePrice;
        $servicePrice = (int)$request->extraService + $request->servicePrice;
        $hotel = Hotels::where('slug',$slug)->where('is_active', '1')->first();
        return response()->json([
            'is_success' => true,
            'message' => __('Service Added Successfully'),
            'extra_service_diplay_price' => currency_format_with_sym($servicePrice,$hotel->created_by,$hotel->workspace),
            'subtotal_diplay_price' => currency_format_with_sym($finalPrice,$hotel->created_by,$hotel->workspace),
            'extra_service_price' => $servicePrice,
            'subtotal_price' => $finalPrice,
            'serviceIds' => $serviceId
        ],200);
    }


    public function SerivceList(Request $request,$slug)
    {
        if(!auth()->guard('holiday')->user()) {
            $Carts = Cookie::get('cart');
            $Carts = json_decode($Carts, true);
            $data = [];
            if($Carts[$request->cart_id]['serviceIds']){
                $data = RoomsChildFacilities::whereIn('id',(explode(",",$Carts[$request->cart_id]['serviceIds'])))->get();
            }
            $hotel = Hotels::where('slug',$slug)->where('is_active', '1')->first();
            return response()->json([
                'is_success' => true,
                'message' => 'Service get successfully',
                'data' => view('holidayz::frontend.theme1.cart.extraservice',['data' => $data,'hotel' => $hotel])->render()
            ]);

        } else {
            $Cart = RoomBookingCart::find($request->cart_id);
            $data = [];
            if($Cart->services){
                $data = RoomsChildFacilities::whereIn('id',(explode(",",$Cart->services)))->get();
            }
            $hotel = Hotels::where('slug',$slug)->where('is_active', '1')->first();
            return response()->json([
                'is_success' => true,
                'message' => 'Service get successfully',
                'data' => view('holidayz::frontend.theme1.cart.extraservice',['data' => $data,'hotel' => $hotel])->render()
            ]);
        }
    }

    public function addRoom(Request $request)
    {
        if(isset($request->date) && !empty($request->date)){
            $date = explode('to', $request->date);
            if(isset($date[0]) && isset($date[1])){
                $startDate = trim($date[0]);
                $endDate = trim($date[1]);
                $toDate = Carbon::parse($startDate);
                $fromDate = Carbon::parse($endDate);

                $days = $toDate->diffInDays($fromDate);

                $finalPrice = ((int)$request->room_price + (int)$request->extraService) * (int)$request->room * (int)$days;
                $servicePrice = ((int)$request->extraService + $request->servicePrice) * (int)$request->room * (int)$days;
                return response()->json([
                    'is_success' => true,
                    'message' => __('Room Added Successfully'),
                    'regular_price' => currency_format_with_sym((int)$request->room_price * (int)$request->room * (int)$days, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                    'extra_service_diplay_price' => currency_format_with_sym($servicePrice, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                    'subtotal_diplay_price' => currency_format_with_sym($finalPrice, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                    'extra_service_price' => $servicePrice,
                    'subtotal_price' => $finalPrice,
                ],200);
            }
        }else{
            $finalPrice = ((int)$request->room_price + (int)$request->extraService) * (int)$request->room;
            $servicePrice = ((int)$request->extraService + $request->servicePrice) * (int)$request->room;
            return response()->json([
                'is_success' => true,
                'message' => __('Room Added Successfully'),
                'regular_price' => currency_format_with_sym((int)$request->room_price * (int)$request->room, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                'extra_service_diplay_price' => currency_format_with_sym($servicePrice, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                'subtotal_diplay_price' => currency_format_with_sym($finalPrice, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                'extra_service_price' => $servicePrice,
                'subtotal_price' => $finalPrice,
            ],200);
        }
    }

    public function addDay(Request $request)
    {
        $date = explode('to', $request->date);
        if(isset($date[0]) && isset($date[1])){
            $startDate = trim($date[0]);
            $endDate = trim($date[1]);
            $toDate = Carbon::parse($startDate);
            $fromDate = Carbon::parse($endDate);

            $days = $toDate->diffInDays($fromDate);

            $finalPrice = ((int)$request->room_price + (int)$request->extraService) * (int)$request->room * (int)$days;
            $servicePrice = ((int)$request->extraService + $request->servicePrice) * (int)$request->room * (int)$days;
            return response()->json([
                'is_success' => true,
                'message' => __('Date Approved Successfully'),
                'regular_price' => currency_format_with_sym((int)$request->room_price * (int)$request->room * (int)$days, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                'extra_service_diplay_price' => currency_format_with_sym($servicePrice, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                'subtotal_diplay_price' => currency_format_with_sym($finalPrice, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                'extra_service_price' => $servicePrice,
                'subtotal_price' => $finalPrice,
            ],200);
        }else{
            return response()->json([
                'is_success' => true,
                'message' => __('Date Approved Successfully'),
                'regular_price' => currency_format_with_sym((int)$request->room_price * (int)$request->room, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                'extra_service_diplay_price' => currency_format_with_sym($request->extraService, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                'subtotal_diplay_price' => currency_format_with_sym($request->finalPrice, (int)$request->hotel_created_by, (int)$request->hotel_workspace),
                'extra_service_price' => $request->extraService,
                'subtotal_price' => $request->finalPrice,
            ],200);
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('holidayz::index');
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
