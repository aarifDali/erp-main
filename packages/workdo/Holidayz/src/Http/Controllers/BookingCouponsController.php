<?php

namespace Workdo\Holidayz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\DataTables\BookingCouponsDataTable;
use Workdo\Holidayz\Entities\BookingCoupons;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\UsedBookingCoupons;
use Workdo\Holidayz\Events\CreateBookingCoupon;
use Workdo\Holidayz\Events\DestroyBookingCoupon;
use Workdo\Holidayz\Events\UpdateBookingCoupon;

class BookingCouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(BookingCouponsDataTable $dataTable)
    {
        if (\Auth::user()->isAbleTo('customer coupon manage')) {
            return $dataTable->render('holidayz::coupon.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (\Auth::user()->isAbleTo('customer coupon create')) {
            return view('holidayz::coupon.create');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('customer coupon create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'unique:booking_coupons|required',
                    'discount' => 'required|numeric',
                    'limit' => 'required|numeric',
                    'manualCode' => 'unique:booking_coupons,code',
                    'autoCode' => 'unique:booking_coupons,code',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            if (empty($request->manualCode) && empty($request->autoCode)) {
                return redirect()->back()->with('error', 'Coupon code is required');
            }
            if($request['icon-input'] == 'manual'){
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'manualCode' => 'required|unique:booking_coupons,code',
                    ]
                );
            }else{
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'autoCode' => 'required|unique:booking_coupons,code',
                    ]
                );
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $coupon           = new BookingCoupons();
            $coupon->name     = $request->name;
            $coupon->discount = $request->discount;
            $coupon->limit    = $request->limit;
            if (!empty($request->manualCode && $request['icon-input'] == 'manual')) {
                $coupon->code = strtoupper($request->manualCode);
            }
            if (!empty($request->autoCode) && $request['icon-input'] == 'auto') {
                $coupon->code = $request->autoCode;
            }
            $coupon->workspace = getActiveWorkSpace();
            $coupon->created_by = creatorId();

            $coupon->save();
            event(new CreateBookingCoupon($request,$coupon));
            return redirect()->back()->with('success', __('Coupon has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (\Auth::user()->isAbleTo('customer coupon show')) {
            $userCoupons = UsedBookingCoupons::where('coupon_id', $id)->get();

            return view('holidayz::coupon.view', compact('userCoupons'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (\Auth::user()->isAbleTo('customer coupon edit')) {
            $coupon = BookingCoupons::find($id);
            return view('holidayz::coupon.edit', ['coupon' => $coupon]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (\Auth::user()->isAbleTo('customer coupon edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'discount' => 'required|numeric',
                    'limit' => 'required|numeric',
                    'code' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $coupon           = BookingCoupons::find($id);
            $coupon->name     = $request->name;
            $coupon->discount = $request->discount;
            $coupon->limit    = $request->limit;
            $coupon->code     = $request->code;
            $coupon->save();

            event(new UpdateBookingCoupon($request,$coupon));
            return redirect()->back()->with('success', __('Coupon details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if(\Auth::user()->isAbleTo('customer coupon delete'))
        {
            $coupon = BookingCoupons::find($id);
            BookingCoupons::find($id)->delete();

            event(new DestroyBookingCoupon($coupon));
            return redirect()->back()->with('success', __('Coupon has been deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function customerAppyCoupon(Request $request,$slug)
    {

        $hotel = Hotels::where('slug',$slug)->first();
        if($hotel){
            if($request->coupon_code == null || $request->coupon_code == ''){
                return response()->json(
                    [
                        'is_success' => false,
                        'message' => __('Please Enter The Coupon Code.'),
                    ]
                );
            }
            if($request->coupon_code != ''){
                $coupon = BookingCoupons::where('code', $request->coupon_code)->where(['is_active' => '1','workspace' => $hotel->workspace])->first();
                if(!empty($coupon))
                {
                    $usedCoupun = $coupon->used_coupon();
                    if($coupon->limit == $usedCoupun)
                    {
                        return response()->json(
                            [
                                'is_success' => false,
                                'message' => __('This coupon code has expired.')
                            ]
                        );
                    }else{

                        $discount_value = ($request->totalprice / 100) * $coupon->discount;
                        $plan_price     = $request->totalprice - $discount_value;
                        $price          = currency_format_with_sym($request->totalprice - $discount_value,$hotel->created_by,$hotel->workspace);
                        $discount_value = '-' . currency_format_with_sym($discount_value,$hotel->created_by,$hotel->workspace);

                        return response()->json([
                            'is_success' => true,
                            'message' => __('Couopon successfully applied'),
                            'code' => $coupon->code,
                            'discount_price' => $discount_value,
                            'final_price' => $price
                        ]);
                    }

                }else{
                    return response()->json([
                        'is_success' => false,
                        'message' => __('Coupon Not found'),
                        'code' => ''
                    ]);
                }

            }
        }else{
            return response()->json([
                'is_success' => false,
                'message' => __('Hotel Not found.'),
                'code' => ''
            ]);
        }
    }
}
