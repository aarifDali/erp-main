<?php

namespace Workdo\Holidayz\Http\Controllers;

use App\Events\BankTransferPaymentStatus;
use App\Models\BankTransferPayment;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\DataTables\RoomsBookingBankTransferDataTable;

class RoomsBookingBankTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(RoomsBookingBankTransferDataTable $dataTable)
    {
        if(\Auth::user()->isAbleTo('rooms booking manage'))
        {
            return $dataTable->render('holidayz::roomBookingBankTransfer.index');
        }
        else
        {
            return redirect()->back()->with('error', __('permission Denied'));
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
        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment)
        {
            if($bank_transfer_payment->type == 'roombookinginvoice')
            {
                $booking = \Workdo\Holidayz\Entities\RoomBookingCart::where('customer_id',$bank_transfer_payment->user_id)->get();
                $invoice_id = \Workdo\Holidayz\Entities\RoomBooking::bookingNumberFormat($bank_transfer_payment->order_id);
            }
            return view('holidayz::roomBookingBankTransfer.edit', compact('bank_transfer_payment','invoice_id'));
        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
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
        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment && $bank_transfer_payment->status == 'Pending')
        {
            $bank_transfer_payment->status = $request->status;
            $bank_transfer_payment->save();

            if($request->status == 'Approved')
            {
                if($bank_transfer_payment->type == 'roombookinginvoice')
                {
                    $hotel = \Workdo\Holidayz\Entities\Hotels::where(['workspace' => getActiveWorkSpace(), 'created_by' => creatorId(), 'is_active' => 1])->first();
                    $booking = \Workdo\Holidayz\Entities\RoomBooking::where('booking_number',$bank_transfer_payment->order_id)->first();
                    $booking->payment_status = 1;
                    $booking->save();
                    $bookingOrder = \Workdo\Holidayz\Entities\RoomBookingOrder::where('booking_id',$booking->id)->first();

                    event(new \Workdo\Holidayz\Events\CreateRoomBooking($request,$booking));
                    event(new BankTransferPaymentStatus($booking,$bank_transfer_payment->type,$bank_transfer_payment));
                    // Email notification
                    if(!empty(company_setting('New Room Booking By Hotel Customer')) && company_setting('New Room Booking By Hotel Customer')  == true)
                    {
                        $user = \App\Models\User::where('id',\Auth::user()->id)->first();
                        $customer = \Workdo\Holidayz\Entities\HotelCustomer::find($booking->user_id);
                        $room = \Workdo\Holidayz\Entities\Rooms::find($bookingOrder->room_id);
                        $uArr = [
                            'hotel_customer_name' => isset($customer->name) ? $customer->name : $booking->first_name,
                            'invoice_number' => $booking->booking_number,
                            'check_in_date' => $bookingOrder->check_in,
                            'check_out_date' => $bookingOrder->check_out,
                            'room_type' => $room->type,
                            'hotel_name' => $hotel->name,
                        ];

                        try
                        {
                            $resp = \App\Models\EmailTemplate::sendEmailTemplate('New Room Booking By Hotel Customer', [$user->email],$uArr);
                        }
                        catch(\Exception $e)
                        {
                            $resp['error'] = $e->getMessage();
                        }

                        return redirect()->back()->with('success', __('Room Booking Bank transfer request Approve successfully') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                    }
                    return redirect()->back()->with('success', __('Room Booking Bank transfer request Approve successfully. email notification is off.'));

                }
                // $bank_transfer_payment->delete();
                return redirect()->back()->with('success', __('Room Booking Bank transfer request Approve successfully'));
            }
            else
            {
                return redirect()->back()->with('success', __('Room Booking Bank transfer request Reject successfully'));
            }
        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment)
        {
            if($bank_transfer_payment->attachment)
            {
                delete_file($bank_transfer_payment->attachment);
            }
            $bank_transfer_payment->delete();

            return redirect()->back()->with('success', __('Room Booking Bank-transfer request successfully deleted.'));
        }
        else
        {
             return redirect()->back()->with('error', __('Request data not found!'));
        }
    }


    public function roomBookingPayWithBank(Request $request,$slug)
    {
        $validator  = \Validator::make(
            $request->all(),
            [
                'payment_receipt' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return response()->json(
                [
                    'status' => 'error',
                    'msg' => $messages->first()
                ]
            );
        }
        $hotel = \Workdo\Holidayz\Entities\Hotels::where('slug', $slug)->first();
        if($hotel){
            $bank_transfer_payment  = new  BankTransferPayment();
            if (!empty($request->payment_receipt))
            {
                $filenameWithExt = $request->file('payment_receipt')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('payment_receipt')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request,'payment_receipt',$fileNameToStore,'bank_transfer');
                if($uplaod['flag'] == 1)
                {
                    $bank_transfer_payment->attachment = $uplaod['url'];
                }
                else
                {
                    return response()->json(
                        [
                            'status' => 'error',
                            'msg' => $uplaod['msg']
                        ]
                    );
                }
            }
            $grandTotal = $coupon_id = 0;
            if (!auth()->guard('holiday')->user()) {
                $Carts = \Illuminate\Support\Facades\Cookie::get('cart');
                $Carts = json_decode($Carts, true);

                foreach ($Carts as $key => $value) {
                    //
                    $toDate = \Carbon\Carbon::parse($value['check_in']);
                    $fromDate = \Carbon\Carbon::parse($value['check_out']);

                    $days = $toDate->diffInDays($fromDate);
                    //
                    $grandTotal += $value['price'] * $value['room'] * $days;  //  * $value['room'] * $days
                    $grandTotal += ($value['serviceCharge']) ? $value['serviceCharge'] : 0;  // * $days
                }
            } else {
                $Carts = \Workdo\Holidayz\Entities\RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->get();
                foreach ($Carts as $key => $value) {
                    //
                    $toDate = \Carbon\Carbon::parse($value->check_in);
                    $fromDate = \Carbon\Carbon::parse($value->check_out);

                    $days = $toDate->diffInDays($fromDate);
                    //
                    $grandTotal += $value->price;  // * $value->room * $days
                    $grandTotal += ($value->service_charge) ? $value->service_charge : 0;  // * $days
                }
            }

            $price = $grandTotal;
            $coupons_id = 0;
            if (!empty($request->coupon)) {
                $coupons = \Workdo\Holidayz\Entities\BookingCoupons::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun     = $coupons->used_coupon();
                    $discount_value = ($price / 100) * $coupons->discount;
                    $price          = $price - $discount_value;
                    $coupons_id = $coupons->id;
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }
            $booking_number = \Workdo\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
            $bank_transfer_payment->order_id = $booking_number;
            $bank_transfer_payment->user_id = !empty(auth()->guard('holiday')->user()->id)?auth()->guard('holiday')->user()->id:0;
            $bank_transfer_payment->request = $hotel->id;
            $bank_transfer_payment->status = 'Pending';
            $bank_transfer_payment->type = $request->type;
            $bank_transfer_payment->price = $price;
            $bank_transfer_payment->price_currency  = company_setting('defult_currancy',$hotel->created_by,$hotel->workspace);
            $bank_transfer_payment->created_by = $hotel->created_by;
            $bank_transfer_payment->workspace = $hotel->workspace;
            $bank_transfer_payment->save();

            if ($coupons_id && $coupons_id != '') {
                $coupons = \Workdo\Holidayz\Entities\BookingCoupons::find($coupons_id);
                if (!empty($coupons)) {
                    $userCoupon         = new \Workdo\Holidayz\Entities\UsedBookingCoupons();
                    $userCoupon->customer_id   = isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0;
                    $userCoupon->coupon_id = $coupons->id;
                    $userCoupon->save();
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
            }
            $booking_number = \Workdo\Holidayz\Entities\RoomBookingCart::where('customer_id',$bank_transfer_payment->user_id)->get();
            // $hotel = \Workdo\Holidayz\Entities\Hotels::where(['workspace' => getActiveWorkSpace(), 'created_by' => creatorId(), 'is_active' => 1])->first();
            
            if($booking_number->isEmpty())
            {
                $Carts = \Illuminate\Support\Facades\Cookie::get('cart');
                $Carts = json_decode($Carts, true);
                $booking = \Workdo\Holidayz\Entities\RoomBooking::create([
                    'booking_number' => $bank_transfer_payment->order_id,
                    'user_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                    'payment_method' => __('Bank Transfer'),
                    'payment_status' => 0,
                    'invoice' => null,
                    'workspace' => $bank_transfer_payment->workspace,
                    'total' => isset($price) ? $price : 0,
                    'coupon_id' => isset($coupons_id) ? $coupons_id : 0,
                    'first_name' => $request->firstname,
                    'last_name' => $request->lastname,
                    'email' =>  $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'country' => ($request->country) ? $request->country : 'india',
                    'zipcode' => $request->zipcode,
                ]);
                foreach ($Carts as $key => $value) {
                    //
                    $toDate = \Carbon\Carbon::parse($value['check_in']);
                    $fromDate = \Carbon\Carbon::parse($value['check_out']);

                    $days = $toDate->diffInDays($fromDate);
                    //
                    $bookingOrder = \Workdo\Holidayz\Entities\RoomBookingOrder::create([
                        'booking_id' => $booking->id,
                        'customer_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                        'room_id' => $value['room_id'],
                        'workspace' => $value['workspace'],
                        'check_in' => $value['check_in'],
                        'check_out' => $value['check_out'],
                        'price' => $value['price'] * $value['room'] * $days,
                        'room' => $value['room'],
                        'service_charge' => $value['serviceCharge'],
                        'services' => $value['serviceIds'],
                    ]);
                    unset($Carts[$key]);
                }
                $cart_json = json_encode($Carts);
                \Illuminate\Support\Facades\Cookie::queue('cart', $cart_json, 1440);
            }
            else
            {
                $Carts = \Workdo\Holidayz\Entities\RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->get();
                $booking = \Workdo\Holidayz\Entities\RoomBooking::create([
                    'booking_number' => $bank_transfer_payment->order_id,
                    'user_id' => auth()->guard('holiday')->user()->id,
                    'payment_method' => __('Bank Transfer'),
                    'payment_status' => 0,
                    'invoice' => null,
                    'workspace' => $bank_transfer_payment->workspace,
                    'total' => isset($price) ? $price : 0,
                    'coupon_id' => isset($coupons_id) ? $coupons_id : 0,
                ]);
                foreach ($Carts as $key => $value) {
                    $bookingOrder = \Workdo\Holidayz\Entities\RoomBookingOrder::create([
                        'booking_id' => $booking->id,
                        'customer_id' => auth()->guard('holiday')->user()->id,
                        'room_id' => $value->room_id,
                        'workspace' => $value->workspace,
                        'check_in' => $value->check_in,
                        'check_out' => $value->check_out,
                        'price' => $value->price,   // * $value->room
                        'room' => $value->room,
                        'service_charge' => $value->service_charge,
                        'services' => $value->services,
                    ]);
                }
                \Workdo\Holidayz\Entities\RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->delete();
            }
            
            return redirect()->route('hotel.home', $slug)->with('success', __('Room Booking payment request send successfully').('<br> <span class="text-danger"> '.__('Your request will be approved by company and then your payment will be activated.').'</span>'));

        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

}
