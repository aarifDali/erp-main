<?php

namespace Workdo\Holidayz\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\DataTables\RoomsBookingDataTable;
use Workdo\Holidayz\Entities\ApartmentType;
use Workdo\Holidayz\Entities\BookingCoupons;
use Workdo\Holidayz\Entities\HotelCustomer;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\RoomBooking;
use Workdo\Holidayz\Entities\RoomBookingOrder;
use Workdo\Holidayz\Entities\Rooms;
use Workdo\Holidayz\Events\CreateRoomBooking;
use Workdo\Holidayz\Events\DestroyRoomBooking;
use Workdo\Holidayz\Events\StatusChangeRoomBooking;
use Workdo\Holidayz\Events\UpdateRoomBooking;

class RoomsBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, RoomsBookingDataTable $dataTable)
    {
        if (\Auth::user()->isAbleTo('rooms booking manage')) {
            return $dataTable->render('holidayz::booking.index', ['type' => $request->type, 'check_in' => $request->check_in, 'check_out' => $request->check_out]);
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
        if (\Auth::user()->isAbleTo('rooms booking create')) {

            $apartmentTypes = ApartmentType::all();
            $rooms = Rooms::where([
                'created_by' => creatorId(), 
                'workspace' => getActiveWorkSpace()
            ])->get();
            $customers = HotelCustomer::where(['workspace' => getActiveWorkSpace(), 'type' => 'customer'])->get();
            return view('holidayz::booking.create', ['rooms' => $rooms, 'customers' => $customers, 'apartmentTypes' => $apartmentTypes]);
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
        if (\Auth::user()->isAbleTo('rooms booking create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'apartment_type_id' => 'required|exists:apartment_types,id',
                    'room_id' => 'required',
                    'check_in' => 'required',
                    'check_out' => 'required',
                    'adults' => 'required',
                    'children' => 'required',
                    'room' => 'required',
                    'user_id' => 'required',
                    'payment_method' => 'required',
                    'payment_status' => 'required',
                ],[
                    'apartment_type_id.required' => 'Apartment type is missing.',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            // Validate room capacity
            $room = Rooms::find($request->room_id);
            if (!$room) {
                return redirect()->back()->with('error', __('Room not found.'));
            }

            $numberOfRooms = (int)$request->room;
            $maxAdults = $room->adults * $numberOfRooms;
            $maxChildren = $room->children * $numberOfRooms;
            $requestedAdults = (int)$request->adults;
            $requestedChildren = (int)$request->children;

            if ($requestedAdults > $maxAdults) {
                return redirect()->back()->with('error', __('The number of adults (') . $requestedAdults . __(') exceeds the maximum capacity of ') . $numberOfRooms . __(' room(s) (') . $maxAdults . __(' adults).'));
            }

            if ($requestedChildren > $maxChildren) {
                return redirect()->back()->with('error', __('The number of children (') . $requestedChildren . __(') exceeds the maximum capacity of ') . $numberOfRooms . __(' room(s) (') . $maxChildren . __(' children).'));
            }

            $hotelCountry = Hotels::where('created_by', creatorId())
                      ->where('workspace', getActiveWorkSpace())
                      ->value('country');

             // Check if hotelCountry is null
            if (is_null($hotelCountry)) {
                return redirect()->back()->with('error', 'Country is not set in Hotel Settings. Please update your hotel details.');
            }

            $customer = HotelCustomer::find($request->user_id);

            $booking = RoomBooking::create([
                'booking_number' => \Workdo\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number', creatorId()),
                'total' => $request->total_rent,
                'discount_amount' => $request->discount_amount,
                'amount_to_pay' => $request->amount_to_pay,
                'user_id' => $request->user_id,
                'first_name' =>$customer->name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'phone' => $customer->mobile_phone,
                'address' => $customer->address,
                'city' => $customer->city,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'country' => $hotelCountry,
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
                
            ]);

            $bookingOrder = RoomBookingOrder::create([
                'booking_id' => $booking->id,
                'customer_id' => $request->user_id,
                'room_id' => $request->room_id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'price' => $request->total_rent,
                'discount_amount' => $request->discount_amount,
                'amount_to_pay' => $request->amount_to_pay,
                'room' => $request->room,
                'workspace' => getActiveWorkSpace(),
                'apartment_type_id' => $request->apartment_type_id,
            ]);

            event(new CreateRoomBooking($request,$booking));

            //Email notification
            if(!empty(company_setting('New Room Booking Invoice')) && company_setting('New Room Booking Invoice')  == true)
            {
                $customer = HotelCustomer::find($request->user_id);
                $paymentStatus = ['0' => 'UnPaid', '1' => 'Paid'];
                $booking->payment_status    = $paymentStatus[$request->payment_status];
                $uArr = [
                    'invoice_id' => $booking->booking_number,
                    'invoice_customer' => $customer->name,
                    'invoice_payment_status' => $booking->payment_status,
                    'invoice_sub_total' => $request->total,
                    'created_at' => $booking->created_at,
                ];

                try
                {
                    $resp = EmailTemplate::sendEmailTemplate('New Room Booking Invoice', [$customer->id => $customer->email],$uArr);
                }
                catch(\Exception $e)
                {
                    $resp['error'] = $e->getMessage();
                }

                if($request->payment_status == 1){
                    if(!empty(company_setting('New Room Booking Invoice Payment')) && company_setting('New Room Booking Invoice Payment')  == true)
                    {
                        $customer = HotelCustomer::find($booking->user_id);
                        $hotel = \Workdo\Holidayz\Entities\Hotels::where(['workspace' => getActiveWorkSpace(), 'is_active' => 1])->first();
                        $uArr = [
                            'payment_name' => isset($customer->name) ? $customer->name : $booking->first_name,
                            'invoice_number' => $booking->booking_number,
                            'payment_amount' => $booking->total,
                            'payment_method' => $booking->payment_method,
                            'payment_date' => $booking->created_at,
                            'hotel_name' => $hotel->name,
                        ];

                        try
                        {
                            $respp = EmailTemplate::sendEmailTemplate('New Room Booking Invoice Payment', [$customer->email],$uArr);
                        }
                        catch(\Exception $e)
                        {
                            $respp['error'] = $e->getMessage();
                        }

                        return redirect()->route('hotel-room-booking.index')->with('success', __('Booking has been created successfully.') . ((isset($respp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $respp['error'] . '</span>' : ''));
                    }
                }

                return redirect()->route('hotel-room-booking.index')->with('success', __('Booking has been created successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->route('hotel-room-booking.index')->with('success', __('Booking has been created successfully. email notification is off.'));

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
            return redirect()->back()->with('error', __('permission Denied'));
    }
    
    public function invoiceView($id)
    {
        if (\Auth::user()->isAbleTo('rooms booking show') || auth()->user()->type == 'super admin') {
            $booking = RoomBooking::with(['getUserDetails', 'getRoomDetails', 'GetBookingOrderDetails'])->find($id);
            return view('holidayz::booking.invoice',['booking' => $booking]);
        } else {
            return redirect()->back()->with('error', __('permission Denied'));
        }
    }

    public function pdfView($id)
    {
        if (auth()->guard('holiday')->user() || \Auth::user()->isAbleTo('rooms booking manage')) {    // auth()->guard('holiday')->user() remove if any error when invoice open
            $booking = RoomBooking::with(['getUserDetails','getRoomDetails','GetBookingOrderDetails'])->find($id);
            return view('holidayz::booking.invoice',['booking' => $booking]);
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
        if (\Auth::user()->isAbleTo('rooms booking edit')) {
            $rooms = Rooms::where(['created_by' =>  creatorId(),'workspace' => getActiveWorkSpace()])->get();
            $customers = HotelCustomer::where(['workspace' => getActiveWorkSpace(),'type' => 'customer'])->get();
            $booking = RoomBooking::with(['getUserDetails','getRoomDetails'])->find($id);
            $bookingorders = RoomBookingOrder::where('booking_id',$id)->with('getBookingDetails')->get();

            return view('holidayz::booking.edit',['rooms' => $rooms,'customers' => $customers,'booking' => $booking,'bookingorders' => $bookingorders]);
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
        if (\Auth::user()->isAbleTo('rooms booking edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'room_id' => 'required',
                    'check_in' => 'required',
                    'check_out' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $room = Rooms::find($request->room_id);
            RoomBookingOrder::find($id)->update([
                "room" => $request->room,
                "room_id" => $request->room_id,
                "check_in" => $request->check_in,
                "check_out" => $request->check_out,
                'price' => $room->final_price
            ]);
            $booking = RoomBookingOrder::find($id);
            event(new UpdateRoomBooking($request,$booking));
            return redirect()->back()->with('success', __('Booking details are updated successfully.'));
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
        if (\Auth::user()->isAbleTo('rooms booking delete')) {
            $booking = RoomBooking::find($id);
            RoomBooking::find($id)->delete();
            RoomBookingOrder::where('booking_id',$id)->delete();
            event(new DestroyRoomBooking($booking));
            return redirect()->back()->with('success', __('Booking has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function calender()
    {
        $user      = auth()->user();
        $transdate = date('Y-m-d', time());
        $bookings = $calandar = [];
        if ($user->type == 'super admin' || $user->isAbleTo('rooms booking manage')) {
            $bookings = RoomBooking::where(['workspace' => getActiveWorkSpace()])->get();
        }
        foreach ($bookings as $booking) {
            $arr['id']        = $booking['id'];
            $arr['title']     = ($booking->getCustomerDetails) ? $booking->getCustomerDetails->name : $booking->first_name;
            $arr['start']     = ($booking->GetBookingOrderDetails) ? $booking->GetBookingOrderDetails->min('check_in') : '';
            $arr['end']       = ($booking->GetBookingOrderDetails) ? $booking->GetBookingOrderDetails->max('check_out') : '';
            $arr['className'] = 'event-primary';
            $arr['url']       = route('room.booking.show', $booking['id']);
            $calandar[]     = $arr;
        }
        return view('holidayz::booking.calender', compact('calandar', 'transdate', 'bookings'));
    }

    public function get_booking_data(Request $request)
    {
        if($request->get('calender_type') == 'goggle_calender')
        {
            $data = RoomBooking::with(['getCustomerDetails','getRoomDetails'])->where(['workspace' => getActiveWorkSpace()])->get();
            $arrayJson = [];
            foreach($data as $val)
            {
                $end_date=date_create($val->GetBookingOrderDetails->max('check_out'));
                date_add($end_date,date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id"=> $val->id,
                    "title" => ($val->user_id != 0) ? $val->getCustomerDetails->name : $val->first_name,
                    "start" => $val->GetBookingOrderDetails->min('check_in'),
                    "end" => date_format($end_date,"Y-m-d H:i:s"),
                    "className" => $val->color,
                    "textColor" => '#FFF',
                    'url'      => route('room.booking.show', $val->id),
                    "allDay" => true,
                ];
            }
        }
        else
        {
            $data = RoomBooking::with(['getCustomerDetails','getRoomDetails'])->where(['workspace' => getActiveWorkSpace()])->get();
            $arrayJson = [];
            foreach($data as $val)
            {
                $end_date=date_create($val->GetBookingOrderDetails->max('check_out'));
                date_add($end_date,date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id"=> $val->id,
                    "title" => ($val->user_id != 0) ? $val->getCustomerDetails->name : $val->first_name,
                    "start" => $val->GetBookingOrderDetails->min('check_in'),
                    "end" => date_format($end_date,"Y-m-d H:i:s"),
                    "className" => $val->color,
                    "textColor" => '#FFF',
                    'url'      => route('room.booking.show', $val->id),
                    "allDay" => true,
                ];
            }
        }
        return $arrayJson;
    }

    public function bookingOrderEdit($id)
    {
        if (\Auth::user()->isAbleTo('rooms booking edit')) {
            $apartmentTypes = ApartmentType::all();
            $rooms = Rooms::where(['created_by' =>  creatorId(),'workspace' => getActiveWorkSpace()])->get();
            $customers = HotelCustomer::where(['workspace' => getActiveWorkSpace(),'type' => 'customer'])->get();
            $bookingorders = RoomBookingOrder::find($id);

            return view('holidayz::booking.orderedit',['rooms' => $rooms,'customers' => $customers, 'apartmentTypes' => $apartmentTypes, 'bookingorders' => $bookingorders]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function BookingOrderUpdate(Request $request,$id)
    {
        if (\Auth::user()->isAbleTo('rooms booking edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'apartment_type_id' => 'required|exists:apartment_types,id',
                    'room_id' => 'required',
                    'check_in' => 'required',
                    'check_out' => 'required',
                    'room' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            RoomBookingOrder::find($id)->update([
                "room" => $request->room,
                "room_id" => $request->room_id,
                "check_in" => $request->check_in,
                "check_out" => $request->check_out,
                'price' => $request->sub_total,
                'discount_amount' => $request->discount_amount,
                'amount_to_pay' => $request->amount_to_pay,
                'service_charge' => $request->service_charge
            ]);

            $booking = RoomBookingOrder::find($id);
            $total_room = RoomBookingOrder::where('booking_id',$booking->booking_id)->get();
            $total_price = 0;
            $total_service_charge = 0;
            foreach($total_room as $one_room){
                $total_price += $one_room->price;
                $total_service_charge += $one_room->service_charge;
            }
            $total_cost = $total_price + $total_service_charge;
            $coupon_id = RoomBooking::find($booking->booking_id)->coupon_id;
            if($coupon_id != 0){
                $coupon_discount = BookingCoupons::find($coupon_id)->discount;

                $discount_value = ($total_cost / 100) * $coupon_discount;
                $total_cost = $total_cost - $discount_value;
            }
            
            RoomBooking::find($booking->booking_id)->update([
                'total' => $total_cost,
                'discount_amount' => $request->discount_amount,
                'amount_to_pay' => $request->amount_to_pay,
            ]);

            event(new UpdateRoomBooking($request,$booking));
            return redirect()->back()->with('success', __('Booking details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bookingOrderDestroy($id)
    {
        if (\Auth::user()->isAbleTo('rooms booking delete')) {
            RoomBookingOrder::find($id)->delete();
            return redirect()->back()->with('success', __('Booking has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function MainBookingOrderEdit($id)
    {
        if (\Auth::user()->isAbleTo('rooms booking edit')) {
            $rooms = Rooms::where(['created_by' =>  creatorId(),'workspace' => getActiveWorkSpace()])->get();
            $customers = HotelCustomer::where(['workspace' => getActiveWorkSpace(),'type' => 'customer'])->get();
            $bookings = RoomBooking::find($id);
            return view('holidayz::booking.mainorderedit',['rooms' => $rooms,'customers' => $customers,'bookings' => $bookings]);
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function MainBookingOrderUpdate(Request $request, $id)
    {
        if (\Auth::user()->isAbleTo('rooms booking edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'payment_method' => 'required',
                    'payment_status' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $booking = RoomBooking::find($id);
            $booking->payment_method   = $request->payment_method;
            $booking->payment_status   = $request->payment_status;
            $booking->save();
            event(new StatusChangeRoomBooking($request,$booking));

            //Email notification
            if(!empty(company_setting('Room Booking Invoice Status Updated')) && company_setting('Room Booking Invoice Status Updated')  == true)
            {
                $customer = HotelCustomer::find($booking->user_id);
                $booking_date = RoomBookingOrder::where('booking_id',$booking->id)->first();
                $paymentStatus = ['0' => 'UnPaid', '1' => 'Paid'];
                $booking->payment_status    = $paymentStatus[$request->payment_status];
                $uArr = [
                    'hotel_customer_name' => $customer->name,
                    'payment_status' => $booking->payment_status,
                    'room_booking_id' => $booking->booking_number,
                    'check_in_date' => $booking_date->check_in,
                ];

                try
                {
                    $resp = EmailTemplate::sendEmailTemplate('Room Booking Invoice Status Updated', [$customer->id => $customer->email],$uArr);
                }
                catch(\Exception $e)
                {
                    $resp['error'] = $e->getMessage();
                }

                return redirect()->back()->with('success', __('The status has been changed successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->back()->with('success', __('The status has been changed successfully. email notification is off.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }




    public function addDayPrice(Request $request)
    {
        if($request->room != null){
            $startDate = trim($request->date1);
            $endDate = trim($request->date2);
            $toDate = \Carbon\Carbon::parse($startDate);
            $fromDate = \Carbon\Carbon::parse($endDate);

            $days = $toDate->diffInDays($fromDate);
            if($request->total_room == null){
                $request->total_room = 1;
            }
            $room_price = rooms::find($request->room)->final_price;

            if(!isset($request->service_charge) || $request->service_charge == "0"){
                $finalPrice = (int)$room_price * (int)$request->total_room * (int)$days;
                $servicePrice = 0;
                return response()->json([
                    'is_success' => true,
                    'message' => __('Date Approved Successfully'),
                    'service_price' => $servicePrice,
                    'total_price' => $finalPrice,
                ],200);
            }else{
                $finalPrice = (int)$room_price * (int)$request->total_room * (int)$days;
                $servicePrice = (int)$request->service_charge * (int)$request->total_room * (int)$days;
                return response()->json([
                    'is_success' => true,
                    'message' => __('Date Approved Successfully'),
                    'service_price' => $servicePrice,
                    'total_price' => $finalPrice,
                ],200);
            }
        }
    }

}
