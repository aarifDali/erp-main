<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Workdo\Holidayz\Http\Controllers\BookingCouponsController;
use Workdo\Holidayz\Http\Controllers\FacilitiesController;
use Workdo\Holidayz\Http\Controllers\FeatureController;
use Workdo\Holidayz\Http\Controllers\HolidayzController;
use Workdo\Holidayz\Http\Controllers\HotelCustomerAuthController;
use Workdo\Holidayz\Http\Controllers\HotelCustomerController;
use Workdo\Holidayz\Http\Controllers\HotelCustomerForgotPasswordController;
use Workdo\Holidayz\Http\Controllers\HotelsController;
use Workdo\Holidayz\Http\Controllers\HotelServicesController;
use Workdo\Holidayz\Http\Controllers\PageOptionController;
use Workdo\Holidayz\Http\Controllers\RoomBookingCartController;
use Workdo\Holidayz\Http\Controllers\RoomBookingCheckoutController;
use Workdo\Holidayz\Http\Controllers\RoomsBookingBankTransferController;
use Workdo\Holidayz\Http\Controllers\RoomsBookingController;
use Workdo\Holidayz\Http\Controllers\RoomsController;
use Workdo\Holidayz\Http\Controllers\ThemesController;
use Workdo\Holidayz\Http\Controllers\TourHotelRoomBookingController;

Route::middleware(['web'])->group(function ()
{
Route::group(['middleware' => ['auth', 'verified','PlanModuleCheck:Holidayz']], function () {
    Route::get('dashboard/holidayz', [HolidayzController::class, 'index'])->name('holidayz.dashboard')->middleware(['auth']);

    // Hotels
    Route::resource('hotels', HotelsController::class)->middleware(['auth',]);

    // hotels fetures route
    Route::resource('hotel-services', HotelServicesController::class)->middleware(['auth',]);

    // Room Type Routes
    Route::resource('hotel-rooms', RoomsController::class)->only(['index', 'store', 'create', 'edit', 'destroy']);
    Route::delete('images/delete/{id}', [RoomsController::class, 'imageDelete'])->name('images.delete');
    Route::match(['post', 'put'], 'rooms/{id}', [RoomsController::class, 'update'])->name('rooms.update');


    // customer routes
    Route::resource('hotel-customer', HotelCustomerController::class);

    // features routes
    Route::resource('hotel-room-features', FeatureController::class)->middleware(['auth',]);

    // facilities Routes
    Route::resource('hotel-room-facilities', FacilitiesController::class)->middleware(['auth',]);

    // booking Routes
    Route::resource('hotel-room-booking', RoomsBookingController::class)->middleware(['auth',]);

    Route::get('hotel-room-booking-calender', [RoomsBookingController::class, 'calender'])->name('calender');//booking
    Route::any('booking/list', [RoomsBookingController::class, 'index'])->name('booking.list.index');
    Route::get('hotel-booking/show/{id}', [RoomsBookingController::class, 'invoiceView'])->name('room.booking.show');
    Route::any('get_booking_data', [RoomsBookingController::class, 'get_booking_data'])->name('booking.get_booking_data');//booking

    Route::get('booking/edit/{id}', [RoomsBookingController::class, 'bookingOrderEdit'])->name('bookingorder.edit');
    Route::delete('booking/delete/{id}', [RoomsBookingController::class, 'bookingOrderDestroy'])->name('bookingorder.destroy');
    Route::put('bookings/update/{id}', [RoomsBookingController::class, 'BookingOrderUpdate'])->name('bookingorder.update');
    Route::get('main/booking/edit/{id}', [RoomsBookingController::class, 'MainBookingOrderEdit'])->name('main-booking.edit');
    Route::put('main/booking/update/{id}', [RoomsBookingController::class, 'MainBookingOrderUpdate'])->name('mainbookingorder.update');

    // themes routes
    Route::post('hotel/{slug?}', [ThemesController::class, 'changeTheme'])->name('hotel.changetheme')->middleware(['auth']);
    Route::get('{slug?}/edit-theme/{theme?}', [ThemesController::class, 'EditTheme'])->name('hotel.edittheme')->middleware(['auth']);
    Route::post('{slug?}/store-edit-theme/{theme?}', [ThemesController::class, 'HotelEditTheme'])->name('hotel.hoteledittheme');
    Route::get('{slug?}/delete-social-image/{theme?}/{key}', [ThemesController::class, 'HotelDeleteThemeImage'])->name('hotel.hoteldeletethemeimage');


    // coupon routes
    Route::resource('room-booking-coupon', BookingCouponsController::class)->middleware(['auth',]);

    Route::get('add-dayprice', [RoomsBookingController::class, 'addDayPrice'])->name('add.dayprice');

    // custom page Routes
    Route::resource('hotel-custom-page', PageOptionController::class)->middleware(['auth',]);

    Route::resource('room-booking-bank-transfer', RoomsBookingBankTransferController::class)->middleware(['auth',]);
});

Route::post('/hotel/{slug}/bank/transfer/roombooking', [RoomsBookingBankTransferController::class, 'roomBookingPayWithBank'])->name('room.booking.invoice.pay.with.bank');
Route::get('pdf/view/{id}', [RoomsBookingController::class, 'pdfView'])->name('pdf.view');

//strat frontend
Route::group(['prefix' => 'hotel/{slug}', 'middleware' => ['SetLocale']], function ($slug)
{
    // coustomer.apply.coupon
    Route::post('customer-apply-coupon', [BookingCouponsController::class, 'customerAppyCoupon'])->name('coustomer.apply.coupon');

    Route::get('customer/login/{lang}', [HotelCustomerAuthController::class, 'index'])->name('customer.login.page');
    Route::post('customer/signin', [HotelCustomerAuthController::class, 'login'])->name('customer.login');
    Route::post('customer/signup', [HotelCustomerAuthController::class, 'register'])->name('customer.register');
    Route::post('customer/logout', [HotelCustomerAuthController::class, 'logout'])->name('customer.logout');


    Route::get('page/{pageslug}',[HolidayzController::class, 'customPage'])->name('frontend.custom.page');


    // language change route
    Route::get('/change/{lang}', [HolidayzController::class, 'changeLanguage'])->name('change.lang');

    // checkout route
    Route::get('/checkout', [RoomBookingCheckoutController::class, 'index'])->name('checkout');

    // subscribe
    Route::post('subscribe', [HotelCustomerController::class, 'subscribe'])->name('subscribe');

    // cart rotues
    Route::get('add-room', [RoomBookingCartController::class, 'addRoom'])->name('add.room');
    Route::get('add-day', [RoomBookingCartController::class, 'addDay'])->name('add.day');

    Route::post('add-to-cart', [RoomBookingCartController::class, 'addToCart'])->name('addToCart');
    Route::post('cart-list', [RoomBookingCartController::class, 'CartList'])->name('Cart.list');
    Route::post('cart-remove', [RoomBookingCartController::class, 'cart_remove'])->name('cart.remove');
    Route::post('add-to-service/{serviceId}', [RoomBookingCartController::class, 'addToservice'])->name('addToservice');
    Route::post('service-list', [RoomBookingCartController::class, 'SerivceList'])->name('serivce.list');

    //Home page
    Route::get('/holiday', [HolidayzController::class, 'customerHome'])->name('hotel.home'); //link copy
    Route::get('customer-profile', [HotelCustomerController::class, 'profile'])->name('customer.profile');
    Route::post('customer/password/update', [HotelCustomerController::class, 'passwordUpdate'])->name('customer.password.update');
    Route::post('customer/profile/update', [HotelCustomerController::class, 'ProfileUpdate'])->name('customer.profile.update');
    Route::get('customer-booking', [HotelCustomerController::class, 'CustomerBooking'])->name('customer.booking');


    // filter wise get rooms route
    Route::match(['get', 'post'], 'room-search', [RoomsController::class, 'searchRooms'])->name('search.rooms');

    // room routes
    Route::get('/room/details/{roomId}', [RoomsController::class, 'details'])->name('room.details');

    //Forgot Password
    Route::get('hotel-customer-password/',[HotelCustomerForgotPasswordController::class, 'showLinkRequestForm'])->name('hotel.customer.password.request');
    Route::post('hotel-customer-password/email',[HotelCustomerForgotPasswordController::class, 'postHoteCustomerEmail'])->name('hotel.customer.password.email');

    // reset password
    Route::get('hotel-customer-password/reset/{token}',[HotelCustomerForgotPasswordController::class, 'getHoteCustomerPassword'])->name('hotel.customer.password.reset');
    Route::post('hotel-customer-password/reset',[HotelCustomerForgotPasswordController::class, 'updateHoteCustomerPassword'])->name('hotel.customer.password.update');

    Route::fallback(function ($slug) {
        return view('holidayz::frontend.error.404', ['slug' => $slug]);
    });

});

// Tour - Room  Booking
Route::resource('tour-hotelroom-booking', TourHotelRoomBookingController::class)->middleware(['auth',]);
Route::post('get-person-name-in-create', [TourHotelRoomBookingController::class, 'GetPersonNameInCreate'])->name('get.person.name.in.create');
});
