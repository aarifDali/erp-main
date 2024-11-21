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
use Workdo\Stripe\Http\Controllers\StripeController;

Route::group(['middleware' => ['web', 'auth', 'verified','PlanModuleCheck:Stripe']], function () {
    Route::prefix('stripe')->group(function() {
        Route::post('/setting/store', [StripeController::class,'setting'])->name('stripe.setting.store');
    });
});
Route::middleware(['web'])->group(function ()
{
    Route::prefix('stripe')->group(function() {
        Route::post('/plan/company/payment', [StripeController::class,'planPayWithStripe'])->name('plan.pay.with.stripe')->middleware(['auth']);
        Route::get('/plan/company/status', [StripeController::class,'planGetStripeStatus'])->name('plan.get.payment.status')->middleware(['auth']);
    });
    Route::post('/invoice-pay-with-stripe', [StripeController::class, 'invoicePayWithStripe'])->name('invoice.pay.with.stripe');
    Route::get('/stripe/invoice/{invoice_id}/{type}', [StripeController::class, 'getInvoicePaymentStatus'])->name('invoice.stripe');

    Route::post('course/stripe/{slug?}', [StripeController::class,'coursePayWithStripe'])->name('course.pay.with.stripe');
    Route::get('course/stripe/{slug?}', [StripeController::class, 'getCoursePaymentStatus'])->name('course.stripe');

    Route::post('/stripe/{slug?}', [StripeController::class,'contentPayWithStripe'])->name('content.pay.with.stripe');
    Route::get('content/stripe/{slug?}', [StripeController::class, 'getContentPaymentStatus'])->name('content.stripe');

    Route::prefix('hotel/{slug}')->group(function() {
        Route::post('customer/stripe', [StripeController::class,'BookinginvoicePayWithStripe'])->name('booking.stripe.post');
    });
    Route::get('/invoice/stripe/{invoice_id}/{type}', [StripeController::class, 'getBookingInvoicePaymentStatus'])->name('booking.stripe');

    Route::prefix('stripe')->group(function() {
        Route::post('/property/tenant/payment', [StripeController::class,'propertyPayWithStripe'])->name('property.pay.with.stripe')->middleware(['auth']);
        Route::get('/property/tenant/status', [StripeController::class,'propertyGetStripeStatus'])->name('property.get.payment.status.stripe')->middleware(['auth']);
    });

    Route::any('vehicle-booking/stripe/{slug}/{lang?}', [StripeController::class, 'vehicleBookingWithStripe'])->name('vehicle.booking.with.stripe');
    Route::get('vehicle-booking/stripe/status/{slug}/{lang?}', [StripeController::class, 'vehicleBookingStatus'])->name('vehicle.booking.status.stripe');

    Route::post('/memberplan-pay-with-stripe', [StripeController::class, 'memberplanPayWithStripe'])->name('memberplan.pay.with.stripe');
    Route::get('/stripe/invoice/{membershipplan_id}', [StripeController::class, 'getMemberPlanPaymentStatus'])->name('memberplan.stripe');

    Route::post('/beauty-spa-pay-with-stripe/{slug?}', [StripeController::class,'BeautySpaPayWithStripe'])->name('beauty.spa.pay.with.stripe');
    Route::get('/beauty-spa/stripe/{slug?}', [StripeController::class, 'getBeautySpaPaymentStatus'])->name('beauty.spa.stripe');

    Route::post('/bookings-pay-with-stripe/{slug?}', [StripeController::class,'BookingsPayWithStripe'])->name('bookings.pay.with.stripe');
    Route::get('/bookings/stripe/{slug?}', [StripeController::class, 'getBookingsPaymentStatus'])->name('bookings.stripe');
    Route::post('/movie-show-booking-pay-with-stripe/{slug?}', [StripeController::class,'MovieShowBookingPayWithStripe'])->name('movie.show.booking.pay.with.stripe');
    Route::get('/movie-show-booking-system/stripe/{slug?}', [StripeController::class, 'getMovieShowBookingPaymentStatus'])->name('movie.show.booking.stripe');

    Route::post('{slug}/parking-pay-with-stripe/{lang?}', [StripeController::class,'parkingPayWithStripe'])->name('parking.pay.with.stripe');
    Route::get('{slug}/parking/stripe/{id}/{amount}/{lang?}', [StripeController::class, 'getParkingPaymentStatus'])->name('parking.stripe');


    Route::post('/event-show-booking-pay-with-stripe/{slug?}', [StripeController::class,'EventShowBookingPayWithStripe'])->name('event.show.booking.pay.with.stripe');
    Route::get('/event-show-booking-system/stripe/{slug?}', [StripeController::class, 'getEventShowBookingPaymentStatus'])->name('event.show.booking.stripe');

    Route::post('/facilities-pay-with-stripe/{slug?}', [StripeController::class,'FacilitiesPayWithStripe'])->name('facilities.pay.with.stripe');
    Route::get('/facilities/stripe/{slug?}', [StripeController::class, 'getFacilitiesPaymentStatus'])->name('facilities.stripe');


    Route::post('/property-booking-pay-with-stripe/{slug?}', [StripeController::class, 'PropertyBookingPayWithStripe'])->name('property.booking.pay.with.stripe');
    Route::get('/property-booking/stripe/{slug?}', [StripeController::class,'GetPropertyBookingPaymentStatus'])->name('property.booking.stripe');

});
