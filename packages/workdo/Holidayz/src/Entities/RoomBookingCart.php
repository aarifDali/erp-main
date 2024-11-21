<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class RoomBookingCart extends Model
{
    use HasFactory;

    protected $table = "room_booking_carts";

    protected $fillable = [
        'customer_id',
        'room_id',
        'workspace',
        'check_in',
        'check_out',
        'service_charge',
        'services',
        'price',
        'room'
    ];
    

    public static function addtocart_cookie($room_id = 0, $workspace = 0,$customer_id = 0)
    {
        $cart_count = 0;
        $room = Rooms::find($room_id);
        $hotel = Hotels::where('workspace',$workspace)->first();
        $slug = $hotel->slug;

        $date = session('date');

        $key_name = $room_id.'_'.$workspace;
        $cart[$key_name]['room_id'] = $room_id;
        $cart[$key_name]['workspace'] = $workspace;
        $cart[$key_name]['price'] = $room->final_price;
        $cart[$key_name]['serviceCharge'] = request()->serviceCharge;
        $cart[$key_name]['serviceIds'] = request()->serviceIds;
        $cart[$key_name]['room'] = $date['room'];
        $cart[$key_name]['check_in'] = $date['check_in'];
        $cart[$key_name]['check_out'] = $date['check_out'];
        $cart[$key_name]['created_at'] = now();

        $cart_Cookie = Cookie::get('cart');
        if(!empty($cart_Cookie)){
            $cart_array = json_decode($cart_Cookie, true);
            $carts_array = json_decode($cart_Cookie, true);

            $priceSum = $serviceCharge = 0;
            foreach($carts_array as $key => $value){
                //
                $toDate = \Carbon\Carbon::parse($value['check_in']);
                $fromDate = \Carbon\Carbon::parse($value['check_out']);
        
                $days = $toDate->diffInDays($fromDate);
                //
                $priceSum += $value['price'] * $value['room'] * $days;  // * $days
                $serviceCharge += $value['serviceCharge'];  // * $days
            }


            if(!empty($cart_array[$key_name])) {
                $cart_count = count($cart_array);
                return $res_array = [
                    'cart_count' => $cart_count,
                    'is_success' => false,
                    'message' => __('already in cart'),
                    'data' => view('holidayz::frontend.' . $hotel->theme_dir . '.cart.popup', compact('hotel', 'slug','room','carts_array','priceSum','serviceCharge'))->render()
                ];
            } else {
                $cart_array = array_merge($cart_array, $cart);
                $cart_count = count($cart_array);
                $cart_json = json_encode($cart_array);
                Cookie::queue('cart', $cart_json, 1440);
            }

        } else {
            $cart_json = json_encode($cart);
            Cookie::queue('cart', $cart_json, 1440);
            $cart_count = 1;
        }

        if (!empty($cart_count)) {
            $priceSum = $serviceCharge = 0;
            foreach($cart as $key => $value){
                //
                $toDate = \Carbon\Carbon::parse($value['check_in']);
                $fromDate = \Carbon\Carbon::parse($value['check_out']);
        
                $days = $toDate->diffInDays($fromDate);
                //
                $priceSum += $value['price'] * $value['room'] * $days;  // * $days
                $serviceCharge += $value['serviceCharge'];  // * $days
            }
            return $res_array = [
                'cart_count' => $cart_count,
                'is_success' => true,
                'message' => __('cart added successfully'),
                'data' => view('holidayz::frontend.' . $hotel->theme_dir . '.cart.popup', compact('hotel', 'slug','room','cart_count','priceSum','serviceCharge'))->render()
            ];
        } else {
            return $res_array = [
                'cart_count' => $cart_count,
                'is_success' => false,
                'message' => __('Cart is empty'),
                'data' => view('holidayz::frontend.' . $hotel->theme_dir . '.cart.popup', compact('hotel', 'slug','room'))->render()
            ];
        }

    }

    public static function CartCount($hotel = null) //$slug
    {
        $return = 0;

        if (Auth::guard('holiday')->user()) {
            $return = RoomBookingCart::where('customer_id', Auth::guard('holiday')->user()->id)
            ->where('workspace', $hotel->workspace)
            ->count();
        } else {
            $cart_Cookie = Cookie::get('cart');
            if(!empty($cart_Cookie)){
                $cart_array = json_decode($cart_Cookie, true);
                $return = count($cart_array);
            }
        }
        return $return;
    }

    public static function cookie_to_cart($customer_id = 0,$slug = null)
    {

        if($customer_id != 0) {
            $cart_Cookie = Cookie::get('cart');
            if(!empty($cart_Cookie)) {
                $hotel = Hotels::where('slug',$slug)->first();
                $rooms = json_decode($cart_Cookie);
                foreach ($rooms as $key => $item) {
                    $cart = RoomBookingCart::where(['customer_id' => $customer_id,'workspace' => $hotel->workspace,'room_id' => $item->room_id])->first();
                    if(!$cart){
                        $room = Rooms::find($item->room_id);
                        $cart = new RoomBookingCart();
                        $cart->customer_id = $customer_id;
                        $cart->room_id = $room->id;
                        $cart->price = $room->final_price;
                        $cart->workspace = $hotel->workspace;
                        $cart->room = $item->room;
                        $cart->check_in = $item->check_in;
                        $cart->check_out = $item->check_out;
                        $cart->service_charge = $item->serviceCharge;
                        $cart->services = $item->serviceIds;
                        $cart->save();
                    }
                }
                $empty_cart = [];
                $cart_json = json_encode($empty_cart);
                Cookie::queue('cart', $cart_json, 1440);
            }
        }
    }

    public static function cart_list_cookie()
    {
        $Carts = $cart_Cookie = Cookie::get('cart');


        if(empty($Carts)) {
            $na = [];
            $Carts = json_encode($na);
        }
        $Carts = json_decode($Carts);
        $cart_array = [];
        $cart_final_price = 0;
        $cart_array['room_list'] = [];
        $items = 0;
        $date = session('date');
        if(!empty($Carts)) {
            foreach ($Carts as $key => $cart_value) {
                $room = Rooms::find($cart_value->room_id);
                //
                $toDate = \Carbon\Carbon::parse($cart_value->check_in);
                $fromDate = \Carbon\Carbon::parse($cart_value->check_out);
        
                $days = $toDate->diffInDays($fromDate);
                //
                $cart_array['room_list'][$key]['cart_id'] = $key;
                $cart_array['room_list'][$key]['price'] = $room->final_price * $cart_value->room * $days;
                $cart_array['room_list'][$key]['cart_created'] = $cart_value->created_at;
                $cart_array['room_list'][$key]['serviceCharge'] = $cart_value->serviceCharge;
                $cart_array['room_list'][$key]['serviceIds'] = $cart_value->serviceIds;
                $cart_array['room_list'][$key]['room_name'] = $room->room_type;
                $cart_array['room_list'][$key]['room_id'] = $room->id;
                $cart_array['room_list'][$key]['room'] = (int)$cart_value->room;
                $cart_array['room_list'][$key]['capacity'] = $room->adults.' Adults & '.$room->children.' Children';
                $cart_array['room_list'][$key]['base_price'] = $room->base_price * $cart_value->room * $days;
                $cart_array['room_list'][$key]['check_in'] = $cart_value->check_in;
                $cart_array['room_list'][$key]['check_out'] = $cart_value->check_out;
                $cart_array['room_list'][$key]['final_price'] = $room->final_price;
                $cart_array['room_list'][$key]['image'] = ($room->image) ? $room->image : '';
                $cart_array['room_list'][$key]['short_description'] = $room->short_description;
                $cart_array['room_list'][$key]['description'] = $room->description;
                $cart_array['room_list'][$key]['tags'] = $room->tags;
                
                $cart_final_price += $room->final_price * $cart_value->room * $days;
                $cart_final_price += $cart_value->serviceCharge;
                $items += 1;
            }
        }
        $cart_array['cart_final_price'] = $cart_final_price;
        $cart_array['items'] = $items;

        if (!empty($cart_array)) {
            $res_array = [
                'status' => 1,
                'message' => 'cart get successfully',
                'data' => $cart_array
            ];
            return $res_array;
        } else {
            return [];
        }
    }

    public static function customer_cart_list_($workspace = 0)
    {
        $Carts = RoomBookingCart::where(['workspace' => $workspace,'customer_id' => auth()->guard('holiday')->user()->id])->get();
        if(empty($Carts)) {
            $na = [];
            $Carts = json_encode($na);
        }
        $Carts = json_decode($Carts);
        $cart_array = [];
        $cart_final_price = 0;
        $cart_array['room_list'] = [];
        $items = 0;

        // Get unique room_ids from $Carts          (if any issue remove below two query)
        $roomIds = collect($Carts)->pluck('room_id')->unique();
        // Eager load the Rooms instances for all unique room_ids
        $rooms = Rooms::whereIn('id', $roomIds)->get();

        if(!empty($Carts)) {
            foreach ($Carts as $key => $cart_value) {
                $room = $rooms->where('id', $cart_value->room_id)->first();
                if($room){
                    //
                    $toDate = \Carbon\Carbon::parse($cart_value->check_in);
                    $fromDate = \Carbon\Carbon::parse($cart_value->check_out);
            
                    $days = $toDate->diffInDays($fromDate);
                    //
                    $cart_array['room_list'][$key]['cart_id'] = $cart_value->id;
                    $cart_array['room_list'][$key]['price'] = $cart_value->price;
                    $cart_array['room_list'][$key]['cart_created'] = $cart_value->created_at;
                    $cart_array['room_list'][$key]['serviceCharge'] = ($cart_value->service_charge) ? $cart_value->service_charge: 0;
                    $cart_array['room_list'][$key]['serviceIds'] = $cart_value->services;
                    $cart_array['room_list'][$key]['room_name'] = $room->room_type;
                    $cart_array['room_list'][$key]['room_id'] = $room->id;
                    $cart_array['room_list'][$key]['room'] = $cart_value->room;
                    $cart_array['room_list'][$key]['capacity'] = $room->adults.' Adults & '.$room->children.' Children';
                    $cart_array['room_list'][$key]['base_price'] = $room->base_price * $cart_value->room * $days;
                    $cart_array['room_list'][$key]['check_in'] = $cart_value->check_in;
                    $cart_array['room_list'][$key]['check_out'] = $cart_value->check_out;
                    $cart_array['room_list'][$key]['final_price'] = $room->final_price;
                    $cart_array['room_list'][$key]['image'] = isset($room->image) ? $room->image : $room->getImages[0]->name;
                    $cart_array['room_list'][$key]['short_description'] = $room->short_description;
                    $cart_array['room_list'][$key]['description'] = $room->description;
                    $cart_array['room_list'][$key]['tags'] = $room->tags;
                    
                    $cart_final_price += $room->final_price * $cart_value->room * $days;
                    $cart_final_price += $cart_value->service_charge;
                    $items += 1;
                }
            }
        }
        $cart_array['cart_final_price'] = $cart_final_price;
        $cart_array['items'] = $items;
        if (!empty($cart_array)) {
            $res_array = [
                'status' => 1,
                'message' => 'cart get successfully',
                'data' => $cart_array
            ];
            return $res_array;
        } else {
            return [];
        }
    }

    public function getRoomDetail()
    {
        return $this->hasOne(Rooms::class,'room_id','id');
    }

}
