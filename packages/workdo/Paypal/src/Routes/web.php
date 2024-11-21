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
use Workdo\Paypal\Http\Controllers\PaypalController;

Route::group(['middleware' => ['web', 'auth', 'verified','PlanModuleCheck:Paypal']], function () {
    Route::prefix('paypal')->group(function() {
        Route::post('/setting/store', [PaypalController::class, 'setting'])->name('paypal.setting.store');
    });
});
Route::middleware(['web'])->group(function ()
{
    Route::post('plan-pay-with/paypal', [PaypalController::class, 'planPayWithPaypal'])->name('plan.pay.with.paypal')->middleware(['auth']);
    Route::get('plan-get-paypal-status/{plan_id}',[PaypalController::class,'planGetPaypalStatus'])->name('plan.get.paypal.status')->middleware(['auth']);
    Route::get('/invoice/paypal/{invoice_id}/{amount}/{type}',[PaypalController::class,'getInvoicePaymentStatus'])->name('invoice.paypal');

    Route::post('course-pay-with-paypal/{slug?}', [PaypalController::class, 'coursePayWithPaypal'])->name('course.pay.with.paypal');
    Route::get('{id}/course-get-payment-status{slug?}', [PaypalController::class,'GetCoursePaymentStatus'])->name('course.paypal');

    Route::post('content-pay-with-paypal/{slug?}', [PaypalController::class, 'contentPayWithPaypal'])->name('content.pay.with.paypal');
    Route::get('{id}/content-get-payment-status{slug?}', [PaypalController::class,'GetContentPaymentStatus'])->name('content.paypal');

    Route::prefix('hotel/{slug}')->group(function() {
    Route::post('pay-with/paypal', [PaypalController::class,'BookingPayWithPaypal'])->name('pay.with.paypal');
    Route::get('{amount}/get-payment-status/{couponid}', [PaypalController::class,'GetBookingPaymentStatus'])->name('booking.get.payment.status.paypal');
    });
    Route::post('/invoice-pay-with/paypal',[PaypalController::class,'invoicePayWithPaypal'])->name('invoice.pay.with.paypal');

    Route::prefix('paypal')->group(function() {
        Route::post('/property/tenant/payment', [PaypalController::class,'propertyPayWithPaypal'])->name('property.pay.with.paypal')->middleware(['auth']);
        Route::get('/property/tenant/status', [PaypalController::class,'propertyGetPaypalStatus'])->name('property.get.paypal.status')->middleware(['auth']);
    });

    Route::any('vehicle-booking-payment/paypal/status', [PaypalController::class, 'vehicleBookingStatus'])->name('vehicle.booking.paypal.status');
    Route::any('vehicle-booking/paypal/{slug}/{lang?}', [PaypalController::class, 'vehicleBookingWithPaypal'])->name('vehicle.booking.with.paypal');

    Route::post('/memberplan-pay-with-paypal', [PaypalController::class, 'memberplanPayWithpaypal'])->name('memberplan.pay.with.paypal');
    Route::get('/paypal/invoice/{membershipplan_id}/{amount}', [PaypalController::class, 'getMemberPlanPaymentStatus'])->name('memberplan.paypal');

    Route::get('/beauty-spa-payment-status/{slug?}', [PaypalController::class,'GetBeautySpaPaymentStatus'])->name('beauty.spa.paypal');
    Route::post('/beauty-spa-pay-with-paypal/{slug?}', [PaypalController::class, 'BeautySpaPayWithPaypal'])->name('beauty.spa.pay.with.paypal');

    Route::post('/bookings-pay-with-paypal/{slug?}', [PaypalController::class, 'BookingsPayWithPaypal'])->name('bookings.pay.with.paypal');
    Route::get('/bookings-payment-status/{slug?}', [PaypalController::class,'GetBookingsPaymentStatus'])->name('bookings.paypal');
    Route::get('/movie-show-booking-payment-status/{slug?}', [PaypalController::class,'GetMovieShowBookingPaymentStatus'])->name('movie.show.booking.paypal');
    Route::post('/movie-show-booking-pay-with-paypal/{slug?}', [PaypalController::class, 'MovieShowBookingPayWithPaypal'])->name('movie.show.booking.pay.with.paypal');

    Route::post('{slug}/parking-pay-with-paypal/{lang?}', [PaypalController::class, 'parkingPayWithPaypal'])->name('parking.pay.with.paypal');
    Route::get('{slug}/parking-payment-status/{id}/{lang?}', [PaypalController::class,'GetParkingPaymentStatus'])->name('parking.paypal');


    Route::post('/event-show-booking-pay-with-paypal/{slug?}', [PaypalController::class, 'EventShowBookingPayWithPaypal'])->name('event.show.booking.pay.with.paypal');
    Route::get('/event-show-booking-payment-status/{slug?}', [PaypalController::class,'GetEventShowBookingPaymentStatus'])->name('event.show.booking.paypal');

    Route::post('/facilities-pay-with-paypal/{slug?}', [PaypalController::class, 'FacilitiesPayWithPaypal'])->name('facilities.pay.with.paypal');
    Route::get('/facilities-payment-status/{slug?}', [PaypalController::class,'GetFacilitiesPaymentStatus'])->name('facilities.paypal');

    Route::post('/property-booking-pay-with-paypal/{slug?}', [PaypalController::class, 'PropertyBookingPayWithPaypal'])->name('property.booking.pay.with.paypal');
    Route::get('/property-booking-payment-status/{slug?}', [PaypalController::class,'GetPropertyBookingPaymentStatus'])->name('property.booking.paypal');
});
