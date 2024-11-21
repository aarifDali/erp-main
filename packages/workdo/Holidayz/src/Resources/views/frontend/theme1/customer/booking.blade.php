@extends('holidayz::frontend.layouts.theme1')
@section('page-title')
    {{__('Booking')}}
@endsection
@section('content')
    <style>
        .login-section .create-account {
            max-width: 100%;
        }
        /* remove .btn css */
        .btn{
            background: #e2e3e5;
            color: #58646e;
            padding-left: 8px;
            padding-right: 8px;
            padding-top: 6px;
            padding-bottom: 6px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .btn:hover {
            color: black;
            text-decoration: none;
            background-color: white;
            border-color: transparent;
        }
    </style>
    <!--wrapper start here-->
    <div class="wrapper" style="padding-block: 100px">
        <section class="login-section padding-bottom  booking-section">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-lg-12 col-12">
                        <div class="login-left">
                            <div class="section-title">
                                <h3>{{ __('Booking') }}</h3>
                            </div>
                            <div class="create-account">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>{{ __('booking_number') }}</th>
                                            <th>{{ __('Total') }}</th>
                                            <th>{{ __('Coupon') }}</th>
                                            <th>{{ __('Payment Method') }}</th>
                                            <th>{{ __('Payment Status') }}</th>
                                            <th>{{ __('invoice') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookings as $booking)
                                            <tr>
                                                <td>{{ Workdo\Holidayz\Entities\RoomBooking::bookingNumberFormat($booking->booking_number,$hotel->created_by,$hotel->workspace) }}
                                                </td>
                                                <td>{{ currency_format_with_sym($booking->total,$hotel->created_by,$hotel->workspace) }}</td>
                                                <td>
                                                    @if ($booking->coupon_id != 0)
                                                        {{ $booking->getCouponDetails->name }}
                                                    @else
                                                        {{ __('-') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $booking->payment_method }}
                                                </td>
                                                <td>
                                                    @if ($booking->payment_status == 1)
                                                        <span
                                                            class="badge rounded-pill bg-primary">{{ __('Success') }}</span>
                                                    @else
                                                        <span
                                                            class="badge rounded-pill bg-danger">{{ __('Unpaid') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="#" data-bs-toggle="tooltip" title="" class="table-modal btn"
                                                        data-bs-original-title="Invoice" data-ajax-popup="true"
                                                        data-size="lg" data-title="Invoice"
                                                        data-url="{{ route('pdf.view', $booking->id) }}"
                                                        data-toggle="tooltip" title="{{ __('Invoice') }}">
                                                        <i class="fa fa-file-pdf"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal table-popup fade" id="commonModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:0px;">
                </div>
            </div>
        </div>
    </div>
    <!---wrapper end here-->
{{-- modal test for frontend popup  --}}
{{--  --}}

@endsection
@push('script')
<script>
    /*********  table Popup  ********/
    $(".table-modal").click(function() {
        $(".table-popup").toggleClass("active");
        $("body").toggleClass("no-scroll");
    });
    $(".close-search").click(function() {
        $(".table-popup").removeClass("active");
        $("body").removeClass("no-scroll");
    });
    $(".close-btn").click(function() {
        $(".table-popup").removeClass("active");
        $("body").removeClass("no-scroll");
    });
</script>
@endpush
