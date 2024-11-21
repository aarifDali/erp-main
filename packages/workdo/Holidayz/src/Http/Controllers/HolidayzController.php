<?php

namespace Workdo\Holidayz\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\Holidayz\Entities\HotelCustomer;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\HotelServices;
use Workdo\Holidayz\Entities\PageOptions;
use Workdo\Holidayz\Entities\RoomBooking;
use Workdo\Holidayz\Entities\RoomBookingOrder;
use Workdo\Holidayz\Entities\Rooms;

class HolidayzController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug = null)
    {
        if (\Auth::user()->isAbleTo('holidayz dashboard manage')) {
            if (Auth::check() && Auth::user()->type != 'customer') {
                if (auth()->user()->type == 'super admin') {

                    $owners = User::where('type', 'owner')->get();
                    $chartData = $this->getOrderChart(['duration' => 'week']);

                    $data = [
                        'owner' =>  $owners->count(),
                        'chartData' => $chartData
                    ];
                    return view('holidayz::dashboard.dashboard');

                    return view('superadmin.dashboard.super_admin', $data);
                } else {
                    $transdate   = date('Y-m-d', time());
                    $customers = HotelCustomer::where(['workspace' => getActiveWorkSpace()])->where('type', 'customer')->count();
                    $hotels = Hotels::where(['created_by' => creatorId()])->count();
                    $hotels_data = Hotels::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->first();
                    $bookings = RoomBooking::where(['workspace' => getActiveWorkSpace()]);
                    $latestCustomers = HotelCustomer::where(['workspace' => getActiveWorkSpace()])->where('type', 'customer')->orderBy('id', 'Desc')->latest()->take(5)->get();
                    $data['incExpLineChartData'] = $this->getIncExpLineChartDate();
                    $invoices = $calenderTasks = [];

                    $bookingData = $bookings->with('getCustomerDetails')->get();


                    foreach ($bookingData as $key => $booking) {
                        $calenderTasks[] = [
                            'title' => ($booking->getCustomerDetails) ? $booking->getCustomerDetails->name : $booking->first_name,
                            'start' => ($booking->GetBookingOrderDetails) ? $booking->GetBookingOrderDetails->min('check_in') : '',
                            'end' => ($booking->GetBookingOrderDetails) ? $booking->GetBookingOrderDetails->max('check_out') : '',
                            'url' => route('room.booking.show', [$booking->id]),
                            'className' => 'deal bg-primary border-primary',
                        ];
                    }
                    $invoices = $bookings->orderBy('id', 'Desc')->latest()->take(5)->with('getCustomerDetails')->get();

                    $bookings = $bookings->count();
                    $data = [
                        'customers' => $customers,
                        'hotels' => $hotels,
                        'hotels_data' => $hotels_data,
                        'bookings' => $bookings,
                        'data' =>  $data,
                        'latestCustomers' => $latestCustomers,
                        'invoices' => $invoices,
                        'transdate' => $transdate,
                        'calenderTasks' => $calenderTasks
                    ];

                    return view('holidayz::dashboard.dashboard', $data);
                }
            } else {
                if (!file_exists(storage_path() . "/installed")) {
                    header('location:install');
                    die;
                } else {
                    if ($slug != null) {
                        $hotelDetail = Hotels::where('slug', $slug)->where('is_active', 1)->first();
                        if ($hotelDetail) {
                            $rooms = Rooms::where('hotel_id', $hotelDetail->id)->get();
                            $getHotelThemeSetting = \Workdo\Holidayz\Entities\Utility::getHotelThemeSetting($hotelDetail->id, $hotelDetail->theme_dir);

                            $getStoreThemeSetting1 = [];

                            if (!empty($getHotelThemeSetting['dashboard'])) {
                                $getHotelThemeSetting = json_decode($getHotelThemeSetting['dashboard'], true);
                                $getHotelThemeSetting1 = \Workdo\Holidayz\Entities\Utility::getHotelThemeSetting($hotelDetail->id, $hotelDetail->theme_dir);
                            }
                            if (empty($getHotelThemeSetting)) {
                                $path = storage_path() . "/uploads/" . $hotelDetail->theme_dir . "/" . $hotelDetail->theme_dir . ".json";

                                $getHotelThemeSetting = json_decode(file_get_contents($path), true);
                            }
                            $data = [
                                'hotel' => $hotelDetail,
                                'rooms' => $rooms,
                                'getHotelThemeSetting' => $getHotelThemeSetting
                            ];
                            return view('holidayz::frontend.' . $hotelDetail->theme_dir . '.index', $data);
                        } else {
                            abort(404, __('Hotel not found'));
                        }
                    } else {
                        return redirect('login');
                    }
                }
            }
        }
    }


    public function customerHome($slug = null)
    {
        session()->flash('date');
        $hotel = Hotels::where('slug', $slug)->where('is_active', '1')->first();
        if (empty($hotel)) {
            return view('holidayz::frontend.error.404');
        }
        $amenities = HotelServices::where('workspace', $hotel->workspace)->get();

        $amenitiesImages = $amenities->whereNotNull('image')->pluck('image')->take(3);
        $rooms = Rooms::where('workspace', $hotel->workspace)->where('is_active', '1')->with('features')->get();
        $getHotelThemeSetting = \Workdo\Holidayz\Entities\Utility::getHotelThemeSetting($hotel->workspace, $hotel->theme_dir);//getActiveWorkSpace()
        $getHotelThemeSetting1 = [];
        if (!empty($getHotelThemeSetting['dashboard'])) {
            $getHotelThemeSetting1 = $getHotelThemeSetting;
            $getHotelThemeSetting = json_decode($getHotelThemeSetting['dashboard'], true);
        }

        if (empty($getHotelThemeSetting)) {
            $path = asset('packages/workdo/Holidayz/src/Resources/assets/'. $hotel->theme_dir . "/" . $hotel->theme_dir . ".json" );
            $getHotelThemeSetting = json_decode(file_get_contents($path), true);
        }
        return view('holidayz::frontend.' . $hotel->theme_dir . '.index', compact('slug', 'hotel', 'getHotelThemeSetting', 'rooms', 'amenities', 'amenitiesImages', 'getHotelThemeSetting1'));
    }

    public function getIncExpLineChartDate()
    {

        $usr = \Auth::user();
        $m = date("m");
        $de = date("d");
        $y = date("Y");
        $format = 'Y-m-d';
        $arrDate = [];
        $arrDateFormat = [];

        for ($i = 0; $i <= 9 - 1; $i++) {
            $date = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));

            $arrDay[] = date('D', mktime(0, 0, 0, $m, ($de - $i), $y));
            $arrDate[] = $date;
            $arrDateFormat[] = date("d-M", strtotime($date));
        }

        $dataArr['day'] = $arrDateFormat;

        // Retrieve all RoomBookingOrder instances for the given dates
        $bookings = RoomBookingOrder::selectRaw('DATE(check_in) as date, count(id) as count')
            ->whereIn('check_in', $arrDate)
            ->groupBy('date')
            ->get();

        // Populate $incomeArr with counts for each date
        $incomeArr = [];
        foreach ($arrDate as $date) {
            $booking = $bookings->where('date', $date)->first();
            $incomeArr[] = $booking ? str_replace(",", "", number_format($booking->count, 2)) : 0;
        }

        $dataArr['bookings'] = $incomeArr;

        return $dataArr;
    }


    public function changeLanguage(Request $request,$slug,$lang)
    {
        if(Auth::guard('holiday')->user()){
            $user       = Auth::guard('holiday')->user();
            $user->lang = $lang;
            $user->save();
            return redirect()->back()->with('success', __('Language change successfully.'));
        }else{
            session()->put('lang',$lang);
            return redirect()->back()->with('success', __('Language change successfully.'));
        }
    }


    public function customPage($slug , $pageSlug)
    {
        $hotel = Hotels::where('slug',$slug)->where('is_active', '1')->first();
        if($hotel){
            $PageDetails = PageOptions::where('slug',$pageSlug)->first();
            if($PageDetails){
                return view('holidayz::frontend.'.$hotel->theme_dir.'.custompage',['pagedetails' => $PageDetails,'slug' => $slug]);
            }else{
                return redirect()->route('hotel.home',$slug)->with('error', __('Page Not Found.'));
            }
        }else{
            return redirect()->back()->with('error', __('Hotel Not Found.'));
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
