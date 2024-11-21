<?php

namespace Workdo\Holidayz\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\Entities\HotelCustomer;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\RoomBooking;
use Workdo\Holidayz\Entities\RoomBookingOrder;
use Workdo\Holidayz\Entities\Rooms;
use Workdo\TourTravelManagement\Entities\HotelRoomBooking;
use Workdo\TourTravelManagement\Entities\PersonDetails;
use Workdo\TourTravelManagement\Entities\TourBooking;
use Workdo\TourTravelManagement\Entities\TourInquiry;

class TourHotelRoomBookingController extends Controller
{
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
    public function create(Request $request)
    {
        if (\Auth::user()->isAbleTo('rooms booking create')) {
            $rooms = Rooms::where(['created_by' => creatorId(), 'workspace' => getActiveWorkSpace()])->get();
            $tour_Id = $request->tour_id;
            $bookings = TourBooking::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->latest('created_at')
                ->where('tour_id', $tour_Id)
                ->exists();
            if ($bookings == true) {
                $bookingInquiries = TourBooking::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->where('tour_id', $tour_Id)->pluck('inquiry_id');

                $inquiryArray = [];
                foreach ($bookingInquiries as $bookingInquiry) {
                    $values = explode(',', $bookingInquiry);
                    $inquiryArray = array_merge($inquiryArray, $values);
                }
                $selectedTourInquiries = [];
                foreach ($inquiryArray as $inquiry_id) {
                    $selectedinquiries = TourInquiry::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->where('tour_id', '=', $tour_Id)->where('id', $inquiry_id)->get(['id', 'person_name', 'tour_destination'])->toArray();
                    $selectedTourInquiries[] = $selectedinquiries;
                }
            }
            return view('holidayz::tour_hotel_booking.create', ['rooms' => $rooms, 'selectedTourInquiries' => $selectedTourInquiries, 'tour_Id' => $tour_Id]);
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
        $hotelCustomer = HotelCustomer::where('email', $request->email_id)->first();
        $hotel = Hotels::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->where('is_active', 1)->get()->first();

        if (isset($hotel) && !empty($hotel)) {
            if (\Auth::user()->isAbleTo('hotel customer create') && \Auth::user()->isAbleTo('rooms booking create')) {

                // CUSTOMER STORE
                if (\Auth::user()->isAbleTo('hotel customer create')) {
                    if (!isset($hotelCustomer) && empty($hotelCustomer)) {
                        $validator = \Validator::make(
                            $request->all(), [
                                'inquiry_id' => 'required|string|max:120',
                                'password' => 'required|string|max:120',
                                'value' => 'required',
                                'room_id' => 'required|string|max:120',
                                'check_in' => 'required|date',
                                'check_out' => 'required|date',
                                'room' => 'required|string|max:120',
                                'total' => 'required|string|max:120',
                                'payment_method' => 'required|string|max:120',
                                'payment_status' => 'required|string|max:120',
                            ]
                        );
                        if ($validator->fails()) {
                            $messages = $validator->getMessageBag();

                            return redirect()->back()->with('error', $messages->first());
                        }

                        $tourId = $request->tour_Id;
                        $inquiryId = $request->inquiry_id;
                        $Email_Id = $request->email_id;

                        $tourInquiryInformation = TourInquiry::where('created_by', creatorId())
                            ->where('workspace', getActiveWorkSpace())
                            ->where('tour_id', $tourId)
                            ->where('id', $inquiryId)
                            ->where('email_id', $Email_Id)
                            ->first();
                        $inquiryPerson_name = $tourInquiryInformation->person_name;
                        $inquiryPersonMobile_No = $tourInquiryInformation->mobile_no;

                        $customer = new HotelCustomer();
                        $customer['name'] = $inquiryPerson_name;
                        $customer['email'] = $Email_Id;
                        $customer['password'] = \Hash::make($request->password);
                        $customer['type'] = 'customer';
                        $customer['mobile_phone'] = $inquiryPersonMobile_No;
                        $customer['workspace'] = getActiveWorkSpace();
                        $customer->save();
                        $customerId = $customer->id ?? '';
                        // $hotel = null;
                        // event(new CreateHotelCustomer($request,$customer,$hotel));

                        // Email notification
                        if (!empty(company_setting('New Hotel Customer')) && company_setting('New Hotel Customer') == true) {
                            $uArr = [
                                'hotel_customer_email' => $request->input('email'),
                                'hotel_customer_password' => $request->input('password'),
                            ];

                            try
                            {
                                $resp = EmailTemplate::sendEmailTemplate('New Hotel Customer', [$customer->id => $customer->email], $uArr);
                            } catch (\Exception $e) {
                                $resp['error'] = $e->getMessage();
                            }

                            return redirect()->back()->with('success', __('Customer successfully Created.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                        }
                    } else {
                        $customerId = $hotelCustomer->id ?? '';
                    }

                } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }

                // ROOM BOOKING
                if (\Auth::user()->isAbleTo('rooms booking create')) {
                    if ($customerId != '') {

                        $validator = \Validator::make(
                            $request->all(),
                            [
                                'room_id' => 'required',
                                'check_in' => 'required',
                                'check_out' => 'required',
                                'room' => 'required',
                                'payment_method' => 'required',
                                'payment_status' => 'required',
                            ]
                        );
                        if ($validator->fails()) {
                            $messages = $validator->getMessageBag();
                            return redirect()->back()->with('error', $messages->first());
                        }
                        $booking = RoomBooking::create([
                            'booking_number' => \Workdo\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number'),
                            'total' => $request->total,
                            'user_id' => $customerId,
                            'payment_method' => $request->payment_method,
                            'payment_status' => $request->payment_status,
                            'workspace' => getActiveWorkSpace(),
                            'created_by' => creatorId(),
                        ]);

                        $bookingOrder = RoomBookingOrder::create([
                            'booking_id' => $booking->id,
                            'customer_id' => $customerId,
                            'room_id' => $request->room_id,
                            'check_in' => $request->check_in,
                            'check_out' => $request->check_out,
                            'price' => $request->total,
                            'room' => $request->room,
                            'workspace' => getActiveWorkSpace(),
                        ]);
                        // event(new CreateRoomBooking($request, $booking));


                        // TOUR TRAVEL MANAGEMENT MODULE NO CODE
                        $values = !empty($request->value) ? $request->value : [];
                        if (isset($booking) && isset($bookingOrder)) {

                            $hotelRoomBooking = new HotelRoomBooking();
                            $hotelRoomBooking['room_booking_id'] = $booking->id;
                            $hotelRoomBooking['room_booking_order_id'] = $bookingOrder->id;
                            $hotelRoomBooking['room_id'] = $request->room_id;
                            $hotelRoomBooking['customer_id'] = $customerId;
                            $hotelRoomBooking['inquiry_id'] = $request->inquiry_id;
                            $hotelRoomBooking['tour_id'] = $request->tour_Id;
                            $hotelRoomBooking['person_id'] = implode(',', $values);
                            $hotelRoomBooking['room'] = $request->room;
                            $hotelRoomBooking['check_in']  = $request->check_in;
                            $hotelRoomBooking['check_out'] = $request->check_out;
                            $hotelRoomBooking['total_room_price'] = $request->total;
                            $hotelRoomBooking['workspace'] = getActiveWorkSpace();
                            $hotelRoomBooking['created_by'] = creatorId();
                            $hotelRoomBooking->save();
                        }

                        //Email notification
                        if (!empty(company_setting('New Room Booking Invoice')) && company_setting('New Room Booking Invoice') == true) {
                            $customer = HotelCustomer::find($request->user_id);
                            $paymentStatus = ['0' => 'UnPaid', '1' => 'Paid'];
                            $booking->payment_status = $paymentStatus[$request->payment_status];
                            $uArr = [
                                'invoice_id' => $booking->booking_number,
                                'invoice_customer' => $customer->name,
                                'invoice_payment_status' => $booking->payment_status,
                                'invoice_sub_total' => $request->total,
                                'created_at' => $booking->created_at,
                            ];

                            try
                            {
                                $resp = EmailTemplate::sendEmailTemplate('New Room Booking Invoice', [$customer->id => $customer->email], $uArr);
                            } catch (\Exception $e) {
                                $resp['error'] = $e->getMessage();
                            }

                            if ($request->payment_status == 1) {
                                if (!empty(company_setting('New Room Booking Invoice Payment')) && company_setting('New Room Booking Invoice Payment') == true) {
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
                                        $respp = EmailTemplate::sendEmailTemplate('New Room Booking Invoice Payment', [$customer->email], $uArr);
                                    } catch (\Exception $e) {
                                        $respp['error'] = $e->getMessage();
                                    }

                                    return redirect()->back()->with('success', __('Booking Successfully Created!') . ((isset($respp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $respp['error'] . '</span>' : ''));
                                }
                            }
                            return redirect()->back()->with('success', __('Booking Successfully Created!') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                        }
                        return redirect()->back()->with('success', __('Booking Successfully Created! email notification is off.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }

            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Hotel Not Found.'));
        }
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

    public function GetPersonNameInCreate(Request $request)
    {
        $tourId = $request->tour_Id;
        $inquiryId = $request->Inquiry_Id;

        $personDetails = PersonDetails::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->where('tour_id', $tourId)
            ->where('inquiry_id', $inquiryId)
            ->pluck('person_name', 'id');

            $tourInquiry = TourInquiry::where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->where('tour_id', $tourId)
            ->where('id', $inquiryId)
            ->first();

            if ($tourInquiry) {
                // $inquiryData = [
                //     $tourInquiry->id => $tourInquiry->person_name,
                // ];
                $inquiryPersonEmailId = isset($tourInquiry->email_id) ? $tourInquiry->email_id : '';

            }

            $allPersonName = $personDetails->toArray();
        return response()->json([
            'allPersonName' => $allPersonName,
            'inquiryPersonEmailId' => $inquiryPersonEmailId,
        ]);
    }

}
