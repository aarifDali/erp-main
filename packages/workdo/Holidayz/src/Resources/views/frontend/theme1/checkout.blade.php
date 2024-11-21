@extends('holidayz::frontend.layouts.theme1')
@section('page-title')
    {{__('Checkout')}}
@endsection
@push('style')
    <style>
        .error {
            color: red;
        }

        .acnav-label-1 {
            padding: 15px 35px 15px 0;
            position: relative;
            font-size: 24px;
            font-weight: 600;
            width: 100%;
        }
    </style>
@endpush
@section('content')
    @php
        $path = get_file('uploads');
    @endphp
    <div class="wrapper" style="margin-top: 70.5966px;">
        <input type="hidden" name="slug" class="slug" value="{{ $slug }}">
        <section class="price-summery checkout-page padding-bottom">
            <div class="price-summery-title checkout-title border-bottom">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>{{ __('Rooms & Price Summary') }}</h2>
                    </div>
                </div>
            </div>
            <div class="price-summery-detl checkout-info">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-12">
                            <div class="checkout-info-left">
                                <div class="set has-children is-open step-1">
                                    <a href="javascript:;" class="acnav-label acnav-label-1">
                                        <span>{{ __('Rooms Information') }}</span>
                                    </a>
                                    <div class="acnav-list">

                                        @foreach ($response->data->room_list as $item)
                                            <div class="checkout-card room_cart_{{ $item->cart_id }}">
                                                <div class="checkout-card-img">
                                                    @if ($item->image)
                                                        <img src="{{ $path . '/rooms/' . $item->image }}" alt="img">
                                                    @endif
                                                </div>
                                                <div class="checkout-card-det">
                                                    <h4>{{ $item->room_name }}</h4>
                                                    <p class="checkout-card-det-sub-tit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                            height="19" viewBox="0 0 18 19" fill="none">
                                                            <path
                                                                d="M9 16.418C8.89125 16.418 8.7825 16.3865 8.688 16.3235L8.29124 16.0602C5.96024 14.5204 2.4375 12.194 2.4375 8.35547C2.4375 4.73672 5.38125 1.79297 9 1.79297C12.6187 1.79297 15.5625 4.73672 15.5625 8.35547C15.5625 12.194 12.0398 14.5204 9.70876 16.0602L9.312 16.3235C9.2175 16.3865 9.10875 16.418 9 16.418ZM9 2.91797C6.0015 2.91797 3.5625 5.35697 3.5625 8.35547C3.5625 11.5887 6.654 13.631 8.91075 15.1212L9 15.1805L9.08925 15.1212C11.3468 13.6302 14.4375 11.5887 14.4375 8.35547C14.4375 5.35697 11.9985 2.91797 9 2.91797Z"
                                                                fill="black" />
                                                        </svg>
                                                        <span>
                                                            @if ($hotel->name)
                                                                {{ $hotel->name }},
                                                            @endif
                                                            @if ($hotel->city)
                                                                {{ $hotel->city }},
                                                            @endif
                                                            @if ($hotel->state)
                                                                {{ $hotel->state }},
                                                            @endif
                                                            {{ isset($hotel->country) ? $hotel->country : 'India' }}
                                                            @if ($hotel->zip_code)
                                                                ,{{ $hotel->zip_code }}
                                                            @endif
                                                        </span>
                                                    </p>
                                                    <ul class="d-flex ckt-card-service">
                                                        @php
                                                            $room = Workdo\Holidayz\Entities\Rooms::find($item->room_id);
                                                        @endphp

                                                        @foreach ($room->features as $feature)
                                                            <li class="d-flex">
                                                                <i class="{{ $feature->icon }}"></i>
                                                                <span>{{ $feature->name }}</span>
                                                            </li>
                                                        @endforeach


                                                    </ul>
                                                    <div class="check-inout-det">
                                                        <div>
                                                            <p>{{ __('CHECK IN') }}</p>
                                                            <h6>{{ company_date_formate($item->check_in,$hotel->created_by,$hotel->workspace) }}</h6>
                                                        </div>
                                                        <div>
                                                            <p>{{ __('CHECK OUT') }}</p>
                                                            <h6>{{ company_date_formate($item->check_out,$hotel->created_by,$hotel->workspace) }}
                                                            </h6>
                                                        </div>
                                                        <div>
                                                            <p>{{ __('OCCUPANCY') }}</p>
                                                            <h6>{{ $item->capacity }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="card-price-det">
                                                        <div class="card-price-det-left">
                                                            <div>
                                                                <p>{{ currency_format_with_sym($item->final_price,$hotel->created_by,$hotel->workspace) }}
                                                                    <span>/{{ __('per night') }}</span>
                                                                </p>
                                                                <span
                                                                    class="opacity">{{ __('Total rooms price (incl. all
                                                                                                                                                                                                            taxes)') }}</span>
                                                            </div>
                                                            <div>
                                                                <p class="theme-color">*</p>
                                                            </div>
                                                            <div>
                                                                @php
                                                                $toDate = \Carbon\Carbon::parse($item->check_in);
                                                                $fromDate = \Carbon\Carbon::parse($item->check_out);
                                                                $days = $toDate->diffInDays($fromDate);
                                                                @endphp
                                                                <p>{{ \Workdo\Holidayz\Entities\Utility::day_count($item->check_in,$item->check_out) }}
                                                                {{-- <p>{{ $days }} --}}
                                                                </p>
                                                                <span>{{ __('Night Stay') }}</span>
                                                            </div>
                                                            <div>
                                                                <p class="theme-color">+</p>
                                                            </div>
                                                            <div>
                                                                <p>{{ currency_format_with_sym($item->serviceCharge,$hotel->created_by,$hotel->workspace) }}
                                                                </p>
                                                                <span class="theme-color extra-service-popup"
                                                                    style="cursor: pointer;"
                                                                    data-cart-id="{{ $item->cart_id }}">{{ __('Extra services') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="card-price-det-right text-right">
                                                            @php
                                                            $finalPrice = $item->final_price * $item->room  * $days;
                                                            $grandTotal = $item->serviceCharge + $finalPrice;
                                                            @endphp
                                                            <p>{{ currency_format_with_sym($grandTotal,$hotel->created_by,$hotel->workspace) }} <span>/
                                                                    {{ __('per night') }}</span></p>
                                                            <span class="opacity">
                                                                {{ __('incl. all taxes.') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="close-icn-btn" data-id="{{ $item->cart_id }}">
                                                        <a href="#0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="25"
                                                                height="25" viewBox="0 0 25 25" fill="none">
                                                                <path
                                                                    d="M12.3413 1.58057C6.41331 1.58057 1.59131 6.40357 1.59131 12.3306C1.59131 18.2576 6.41331 23.0806 12.3413 23.0806C18.2693 23.0806 23.0913 18.2576 23.0913 12.3306C23.0913 6.40357 18.2693 1.58057 12.3413 1.58057ZM12.3413 21.5806C7.24031 21.5806 3.09131 17.4316 3.09131 12.3306C3.09131 7.22957 7.24031 3.08057 12.3413 3.08057C17.4423 3.08057 21.5913 7.22957 21.5913 12.3306C21.5913 17.4316 17.4423 21.5806 12.3413 21.5806ZM15.8713 9.8606L13.4013 12.3306L15.8713 14.8005C16.1643 15.0935 16.1643 15.5686 15.8713 15.8616C15.7253 16.0076 15.5333 16.0815 15.3413 16.0815C15.1493 16.0815 14.9573 16.0086 14.8113 15.8616L12.3413 13.3915L9.87131 15.8616C9.72531 16.0076 9.53331 16.0815 9.34131 16.0815C9.14931 16.0815 8.95731 16.0086 8.81131 15.8616C8.51831 15.5686 8.51831 15.0935 8.81131 14.8005L11.2813 12.3306L8.81131 9.8606C8.51831 9.5676 8.51831 9.09256 8.81131 8.79956C9.10431 8.50656 9.57931 8.50656 9.87231 8.79956L12.3423 11.2696L14.8123 8.79956C15.1053 8.50656 15.5803 8.50656 15.8733 8.79956C16.1643 9.09256 16.1643 9.5686 15.8713 9.8606Z"
                                                                    fill="black" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <a href="#"
                                            class="btn d-flex align-items-center confirm-btn confirm-btn-checkout step-one-proceed-btn">
                                            <span>{{ __('Proceed') }}</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                                viewBox="0 0 19 19" fill="none">
                                                <path
                                                    d="M9.35303 1.44727C4.90703 1.44727 1.29053 5.06377 1.29053 9.50977C1.29053 13.9558 4.90703 17.5723 9.35303 17.5723C13.799 17.5723 17.4155 13.9558 17.4155 9.50977C17.4155 5.06377 13.799 1.44727 9.35303 1.44727ZM9.35303 16.4473C5.52728 16.4473 2.41553 13.3355 2.41553 9.50977C2.41553 5.68402 5.52728 2.57227 9.35303 2.57227C13.1788 2.57227 16.2905 5.68402 16.2905 9.50977C16.2905 13.3355 13.1788 16.4473 9.35303 16.4473ZM12.8719 9.7251C12.8434 9.7941 12.8023 9.85627 12.7505 9.90802L10.5005 12.158C10.391 12.2675 10.247 12.323 10.103 12.323C9.95903 12.323 9.81501 12.2683 9.70551 12.158C9.48575 11.9383 9.48575 11.582 9.70551 11.3622L10.9955 10.0723H6.35303C6.04253 10.0723 5.79053 9.82027 5.79053 9.50977C5.79053 9.19927 6.04253 8.94727 6.35303 8.94727H10.9948L9.70477 7.65729C9.48502 7.43754 9.48502 7.08126 9.70477 6.86151C9.92452 6.64176 10.2808 6.64176 10.5005 6.86151L12.7505 9.11151C12.8023 9.16326 12.8434 9.22543 12.8719 9.29443C12.9289 9.43243 12.9289 9.5871 12.8719 9.7251Z"
                                                    fill="white"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                @if (!auth()->guard('holiday')->user())
                                    <div class="set step-2">
                                        <a href="javascript:;" class="acnav-label-1">
                                            <span>{{ __('Guest Information') }}</span>
                                        </a>
                                        <div class="acnav-list guest-info" style="display: none">
                                            <div style="margin-bottom: 10px">
                                                <p>{{ __('Already have an account? ') }}<a
                                                        href="{{ route('customer.login.page', [$slug, 'en']) }}"
                                                        style="color: #e0c08a">{{ __('Login now') }}</a>
                                                    {{ __('to make checkout process faster
                                                                                                                                                            and time saving.') }}
                                                </p>
                                                <br>
                                                <button class="btn check-out-btn">{{ __('Guest Checkout') }}</button>
                                                <a href="{{ route('customer.login.page', [$slug, 'en']) }}"
                                                    class="btn">{{ __('Create An Account') }}</a>
                                            </div>
                                            <form id="formdata" class="guest-form">
                                                <div class="row guest-form" style="display: none">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('First Name') }}</label>
                                                            <input type="text" class="form-control" name="firstname"
                                                                placeholder="{{__('Enter Your First Name')}}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ __('Email') }}</label>
                                                            <input type="email" class="form-control" placeholder="{{__('Enter Your Email')}}"
                                                                required name="email">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ __('Address') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{__('Enter Your Address')}}"
                                                                name="address" required="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ __('Country') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{__('Enter Your Country')}}"
                                                                name="country" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Last Name') }}</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="{{__('Enter Your Last Name')}}" name="lastname" required="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ __('Phone') }}</label>
                                                            <input type="number" class="form-control"
                                                                placeholder="{{__('Enter Your Phone Number')}}" name="phone" required="">
                                                                <div class="text-xs" style="color: red; font-size: 11px">{{ __('Please add phone no with country code. (ex. +91)') }}</div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ __('City') }}</label>
                                                            <input type="text" class="form-control" placeholder="{{__('Enter Your City')}}"
                                                                name="city" required="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ __('Zipcode') }}</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="{{__('Enter Your Zip Code')}}" name="zipcode" required="">
                                                        </div>
                                                    </div>
                                                    <a href="#"
                                                        class="btn d-flex align-items-center confirm-btn confirm-btn-checkout step-two-proceed-btn">
                                                        <span>{{ __('Proceed') }}</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="19"
                                                            height="19" viewBox="0 0 19 19" fill="none">
                                                            <path
                                                                d="M9.35303 1.44727C4.90703 1.44727 1.29053 5.06377 1.29053 9.50977C1.29053 13.9558 4.90703 17.5723 9.35303 17.5723C13.799 17.5723 17.4155 13.9558 17.4155 9.50977C17.4155 5.06377 13.799 1.44727 9.35303 1.44727ZM9.35303 16.4473C5.52728 16.4473 2.41553 13.3355 2.41553 9.50977C2.41553 5.68402 5.52728 2.57227 9.35303 2.57227C13.1788 2.57227 16.2905 5.68402 16.2905 9.50977C16.2905 13.3355 13.1788 16.4473 9.35303 16.4473ZM12.8719 9.7251C12.8434 9.7941 12.8023 9.85627 12.7505 9.90802L10.5005 12.158C10.391 12.2675 10.247 12.323 10.103 12.323C9.95903 12.323 9.81501 12.2683 9.70551 12.158C9.48575 11.9383 9.48575 11.582 9.70551 11.3622L10.9955 10.0723H6.35303C6.04253 10.0723 5.79053 9.82027 5.79053 9.50977C5.79053 9.19927 6.04253 8.94727 6.35303 8.94727H10.9948L9.70477 7.65729C9.48502 7.43754 9.48502 7.08126 9.70477 6.86151C9.92452 6.64176 10.2808 6.64176 10.5005 6.86151L12.7505 9.11151C12.8023 9.16326 12.8434 9.22543 12.8719 9.29443C12.9289 9.43243 12.9289 9.5871 12.8719 9.7251Z"
                                                                fill="white"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                <div class="set @if (auth()->guard('holiday')->user()) step-2 @else step-3 @endif">
                                    <a href="javascript:;" class="acnav-label-1">
                                        <span>{{ __('Payment Information') }}</span>
                                    </a>
                                    <div class="acnav-list guest-info" style="display: none">

                                        {{-- bank transfer--}}
                                        @if (!empty(company_setting('bank_transfer_payment_is_on', $hotel->created_by,$hotel->workspace)) && company_setting('bank_transfer_payment_is_on', $hotel->created_by,$hotel->workspace) == 'on')
                                            <div class="price-summery-card">
                                                <div class="price-sm-card-top">
                                                    <div class="price-pay-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="43"
                                                            height="43" viewBox="0 0 43 43" fill="none">
                                                            <path
                                                                d="M5.31775 21.5268C5.27046 21.5268 5.22152 21.525 5.17248 21.5198C4.45094 21.4409 3.93079 20.7914 4.0096 20.0716C4.64533 14.2625 8.15143 9.25879 13.3879 6.68786C13.7959 6.48821 14.2775 6.51271 14.6611 6.75264C15.0463 6.99257 15.281 7.41478 15.281 7.86838V13.2098C15.281 13.9348 14.6926 14.5233 13.9675 14.5233C13.2425 14.5233 12.6541 13.9348 12.6541 13.2098V10.1608C9.28628 12.504 7.07781 16.1836 6.62247 20.357C6.54891 21.0295 5.97799 21.5268 5.31775 21.5268ZM36.9833 22.2361C36.26 22.1591 35.6138 22.6774 35.535 23.3989C35.0797 27.5723 32.8712 31.2518 29.5034 33.5968V30.5478C29.5034 29.8228 28.915 29.2344 28.1899 29.2344C27.4649 29.2344 26.8765 29.8228 26.8765 30.5478V35.8894C26.8765 36.343 27.1111 36.765 27.4964 37.005C27.7083 37.1363 27.9483 37.2029 28.1899 37.2029C28.3878 37.2029 28.5858 37.1591 28.7697 37.068C34.0079 34.4971 37.514 29.4936 38.148 23.6845C38.225 22.963 37.7031 22.315 36.9833 22.2361ZM18.0131 15.6512V8.64591C18.0131 6.1783 19.4859 4.70544 21.9535 4.70544H32.4614C34.9291 4.70544 36.4019 6.1783 36.4019 8.64591V15.6512C36.4019 18.1188 34.9291 19.5916 32.4614 19.5916H21.9535C19.4859 19.5916 18.0131 18.1188 18.0131 15.6512ZM33.7749 15.6512V11.7107H20.6401V15.6512C20.6401 16.6827 20.922 16.9647 21.9535 16.9647H32.4614C33.493 16.9647 33.7749 16.6827 33.7749 15.6512ZM20.6401 8.64591V9.08374H33.7749V8.64591C33.7749 7.61438 33.493 7.33242 32.4614 7.33242H21.9535C20.922 7.33242 20.6401 7.61438 20.6401 8.64591ZM25.4562 13.462H22.8292C22.1042 13.462 21.5157 14.0505 21.5157 14.7755C21.5157 15.5006 22.1042 16.089 22.8292 16.089H25.4562C26.1812 16.089 26.7697 15.5006 26.7697 14.7755C26.7697 14.0505 26.1812 13.462 25.4562 13.462ZM24.1427 27.9104V34.9157C24.1427 37.3833 22.6698 38.8561 20.2022 38.8561H9.69433C7.22672 38.8561 5.75387 37.3833 5.75387 34.9157V27.9104C5.75387 25.4428 7.22672 23.9699 9.69433 23.9699H20.2022C22.6698 23.9699 24.1427 25.4428 24.1427 27.9104ZM8.38084 27.9104V28.3482H21.5157V27.9104C21.5157 26.8789 21.2338 26.5969 20.2022 26.5969H9.69433C8.6628 26.5969 8.38084 26.8789 8.38084 27.9104ZM21.5157 34.9157V30.9752H8.38084V34.9157C8.38084 35.9472 8.6628 36.2291 9.69433 36.2291H20.2022C21.2338 36.2291 21.5157 35.9472 21.5157 34.9157ZM13.197 32.7265H10.57C9.84494 32.7265 9.2565 33.315 9.2565 34.04C9.2565 34.765 9.84494 35.3535 10.57 35.3535H13.197C13.922 35.3535 14.5104 34.765 14.5104 34.04C14.5104 33.315 13.922 32.7265 13.197 32.7265Z"
                                                                fill="#AB9A86"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="price-pay-info">
                                                        <h4>{{ 'Bank Transfer' }}</h4>
                                                        <p>{{ __('Order processing will be longer') }}</p>
                                                    </div>

                                                </div>
                                                <div class="container" style="padding-bottom: 15px;">
                                                    <form method="post" action="{{ route('room.booking.invoice.pay.with.bank', $slug) }}"
                                                        class="require-validation" id="payment-form" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="type" value="roombookinginvoice">
                                                        <input type="hidden" name="firstname" class="firstnameforminput">
                                                        <input type="hidden" name="email" class="emailforminput">
                                                        <input type="hidden" name="address" class="addressforminput">
                                                        <input type="hidden" name="country" class="countryforminput">
                                                        <input type="hidden" name="lastname" class="lastnameforminput">
                                                        <input type="hidden" name="phone" class="phoneforminput">
                                                        <input type="hidden" name="city" class="cityforminput">
                                                        <input type="hidden" name="zipcode" class="zipcodeforminput">
                                                        <input type="hidden" name="coupon" class="applied_coupon_code">
                                                        <div class="row mt-2">
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <p style="font-weight: 600;font-size: 16px;">{{ __('Bank Details :') }}</p>
                                                                    <p class="">
                                                                        {!!company_setting('bank_number',$hotel->created_by,$hotel->workspace) !!}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <p style="font-weight: 600;font-size: 16px;">{{ __('Payment Receipt') }}</p>
                                                                    <div class="choose-files">
                                                                    <label for="payment_receipt">
                                                                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i></div>
                                                                        <input type="file" class="form-control" required="" accept="image/png, image/jpeg, image/jpg, .pdf" name="payment_receipt" id="payment_receipt" data-filename="payment_receipt" onchange="document.getElementById('blah3').src = window.URL.createObjectURL(this.files[0])">
                                                                    </label>
                                                                    <p class="text-danger error_msg d-none">{{ __('This field is required')}}</p>

                                                                    <img class="mt-2" width="70px"  id="blah3">
                                                                </div>
                                                                    <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                                                </div>
                                                            </div>
                                                            <small class="text-danger" style="padding-left: 10px;color: red;">{{ __('first, make a payment and take a screenshot or download the receipt and upload it.')}}</small>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="error" style="display: none;">
                                                                    <div class='alert-danger alert'>
                                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            <button class="btn btn-primary"
                                                                type="submit">{{ __('Make Payment') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif

                                        @stack('hotel_room_booking_payment_div')


                                        {{-- Paymentwall --}}
                                        @if (isset($HotelPayments['is_paymentwall_enabled']) && $HotelPayments['is_paymentwall_enabled'] == 'on')
                                            <div class="price-summery-card" id="paymentwall_payment">
                                                <div class="price-sm-card-top">
                                                    <div class="price-pay-icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="43"
                                                            height="43" viewBox="0 0 43 43" fill="none">
                                                            <path
                                                                d="M5.31775 21.5268C5.27046 21.5268 5.22152 21.525 5.17248 21.5198C4.45094 21.4409 3.93079 20.7914 4.0096 20.0716C4.64533 14.2625 8.15143 9.25879 13.3879 6.68786C13.7959 6.48821 14.2775 6.51271 14.6611 6.75264C15.0463 6.99257 15.281 7.41478 15.281 7.86838V13.2098C15.281 13.9348 14.6926 14.5233 13.9675 14.5233C13.2425 14.5233 12.6541 13.9348 12.6541 13.2098V10.1608C9.28628 12.504 7.07781 16.1836 6.62247 20.357C6.54891 21.0295 5.97799 21.5268 5.31775 21.5268ZM36.9833 22.2361C36.26 22.1591 35.6138 22.6774 35.535 23.3989C35.0797 27.5723 32.8712 31.2518 29.5034 33.5968V30.5478C29.5034 29.8228 28.915 29.2344 28.1899 29.2344C27.4649 29.2344 26.8765 29.8228 26.8765 30.5478V35.8894C26.8765 36.343 27.1111 36.765 27.4964 37.005C27.7083 37.1363 27.9483 37.2029 28.1899 37.2029C28.3878 37.2029 28.5858 37.1591 28.7697 37.068C34.0079 34.4971 37.514 29.4936 38.148 23.6845C38.225 22.963 37.7031 22.315 36.9833 22.2361ZM18.0131 15.6512V8.64591C18.0131 6.1783 19.4859 4.70544 21.9535 4.70544H32.4614C34.9291 4.70544 36.4019 6.1783 36.4019 8.64591V15.6512C36.4019 18.1188 34.9291 19.5916 32.4614 19.5916H21.9535C19.4859 19.5916 18.0131 18.1188 18.0131 15.6512ZM33.7749 15.6512V11.7107H20.6401V15.6512C20.6401 16.6827 20.922 16.9647 21.9535 16.9647H32.4614C33.493 16.9647 33.7749 16.6827 33.7749 15.6512ZM20.6401 8.64591V9.08374H33.7749V8.64591C33.7749 7.61438 33.493 7.33242 32.4614 7.33242H21.9535C20.922 7.33242 20.6401 7.61438 20.6401 8.64591ZM25.4562 13.462H22.8292C22.1042 13.462 21.5157 14.0505 21.5157 14.7755C21.5157 15.5006 22.1042 16.089 22.8292 16.089H25.4562C26.1812 16.089 26.7697 15.5006 26.7697 14.7755C26.7697 14.0505 26.1812 13.462 25.4562 13.462ZM24.1427 27.9104V34.9157C24.1427 37.3833 22.6698 38.8561 20.2022 38.8561H9.69433C7.22672 38.8561 5.75387 37.3833 5.75387 34.9157V27.9104C5.75387 25.4428 7.22672 23.9699 9.69433 23.9699H20.2022C22.6698 23.9699 24.1427 25.4428 24.1427 27.9104ZM8.38084 27.9104V28.3482H21.5157V27.9104C21.5157 26.8789 21.2338 26.5969 20.2022 26.5969H9.69433C8.6628 26.5969 8.38084 26.8789 8.38084 27.9104ZM21.5157 34.9157V30.9752H8.38084V34.9157C8.38084 35.9472 8.6628 36.2291 9.69433 36.2291H20.2022C21.2338 36.2291 21.5157 35.9472 21.5157 34.9157ZM13.197 32.7265H10.57C9.84494 32.7265 9.2565 33.315 9.2565 34.04C9.2565 34.765 9.84494 35.3535 10.57 35.3535H13.197C13.922 35.3535 14.5104 34.765 14.5104 34.04C14.5104 33.315 13.922 32.7265 13.197 32.7265Z"
                                                                fill="#AB9A86"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="price-pay-info">
                                                        <h4>{{ 'Paymentwall' }}</h4>
                                                        <p>{{ __('Order processing will be longer') }}</p>
                                                    </div>
                                                    <form role="form"
                                                        action="{{ route('booking.paymentwallpayment', $slug) }}"
                                                        method="post" id="paymentwall-payment-form"
                                                        class="w3-container w3-display-middle w3-card-4">
                                                        @csrf
                                                        <input type="hidden" name="firstname"
                                                            class="firstnameforminput">
                                                        <input type="hidden" name="email" class="emailforminput">
                                                        <input type="hidden" name="address" class="addressforminput">
                                                        <input type="hidden" name="country" class="countryforminput">
                                                        <input type="hidden" name="lastname" class="lastnameforminput">
                                                        <input type="hidden" name="phone" class="phoneforminput">
                                                        <input type="hidden" name="city" class="cityforminput">
                                                        <input type="hidden" name="zipcode" class="zipcodeforminput">
                                                        <input type="hidden" name="coupon" class="applied_coupon_code">
                                                        <div class="form-group text-right">
                                                            <button type="submit" class="btn" id="pay_with_mollie"
                                                                id="pay_with_paymentwall">{{ __('Pay Now') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                        {{-- Paymentwall end --}}


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="checkout-summery">
                                <h5>{{ __('Total Summary') }}</h5>
                                <ul>
                                    @php
                                        $ConvenienceFees = 0;
                                    @endphp
                                    <li>{{ __('Total Rooms Cost in cart (tax incl.)') }}<span
                                            class="total_price">{{ currency_format_with_sym($response->data->cart_final_price,$hotel->created_by,$hotel->workspace) }}</span>
                                    </li>
                                    <li>{{ __('Coupon') }}<span
                                            class="coupon_price">{{ currency_format_with_sym(0,$hotel->created_by,$hotel->workspace) }}</span>
                                    </li>
                                    <li>{{ __('Convenience Fees') }}<span
                                            class="convenience_fees">{{ currency_format_with_sym($ConvenienceFees,$hotel->created_by,$hotel->workspace) }}</span>
                                    </li>
                                    <li>{{ __('Total (tax incl.)') }}<span
                                            class="sub_total">{{ currency_format_with_sym($response->data->cart_final_price + $ConvenienceFees,$hotel->created_by,$hotel->workspace) }}</span>
                                    </li>
                                </ul>
                                <div class="mini-cart-footer-total-row d-flex align-items-center justify-content-between">
                                    <div class="mini-total-lbl">
                                        {{ __('Subtotal') }}:
                                    </div>
                                    <div class="mini-total-price sub_total">
                                        {{ currency_format_with_sym($response->data->cart_final_price + $ConvenienceFees,$hotel->created_by,$hotel->workspace) }}
                                    </div>
                                </div>
                                <div class="aply-cpn">
                                    <h5>{{ __('Apply Coupon') }}</h5>
                                    <p>{{ __('Have a promocode?') }}</p>
                                    <form action="javascript:;" id="coupon-form" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label>{{ __('PROMOCODE') }}</label>
                                            <input type="text" class="form-control coupon_code"
                                                placeholder="Please Apply Coupon Code" name="coupon" required="">
                                        </div>
                                        <a href="#"
                                            class="btn d-flex align-items-center confirm-btn confirm-btn-checkout app-coupon-code-btn">
                                            <span>{{ __('Apply coupon') }}</span>
                                        </a>
                                    </form>
                                    <span class="avabl-cpl-line">{{ __('AVAILABLE COUPONS') }}</span>
                                    @foreach ($coupons as $item)
                                        <p><span>{{ $item->code }}</span> - {{ $item->name }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection



@push('script')
<script type="text/javascript" src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/additional-methods.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/jquery.steps.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var form = $("#formdata");
            form.validate({
                rules: {
                    'firstname': "required",
                    'lastname': "required",
                    'email': "required",
                },
                messages: {
                    'firstname': "<span class='text-danger billing_data_error'> please enter first name </span>",
                    'lastname': "<span class='text-danger billing_data_error'>please enter last name </span>",
                    'email': "<span class='text-danger billing_data_error'>please enter valid email </span>",
                }
            });
        });
    </script>



    @if (!empty(company_setting('payfast_payment_is_on', $hotel->created_by,$hotel->workspace)) && company_setting('payfast_payment_is_on', $hotel->created_by,$hotel->workspace) == 'on')
        <script>
            $('.step-two-proceed-btn').on('click', function(event) {
                var form_is_valid = $("#formdata").valid();
                if (form_is_valid == false) {
                    return false;
                }

                $('.step-2').removeClass('is-open');
                $('.step-2').children('div').css('display', 'none');

                $('.firstnameforminput').val($("input[name='firstname']").val());
                $('.emailforminput').val($("input[name='email']").val());
                $('.addressforminput').val($("input[name='address']").val());
                $('.countryforminput').val($("input[name='country']").val());
                $('.lastnameforminput').val($("input[name='lastname']").val());
                $('.phoneforminput').val($("input[name='phone']").val());
                $('.cityforminput').val($("input[name='city']").val());
                $('.zipcodeforminput').val($("input[name='zipcode']").val());



                get_payfast_status(amount = 0, coupon = null);


                $('.step-3').addClass('has-children is-open');
                $('.step-3').children('div').removeAttr('style');
            });
        </script>
    @else
        <script>
            $('.step-two-proceed-btn').on('click', function(event) {
                var form_is_valid = $("#formdata").valid();
                if (form_is_valid == false) {
                    return false;
                }

                $('.step-2').removeClass('is-open');
                $('.step-2').children('div').css('display', 'none');

                $('.firstnameforminput').val($("input[name='firstname']").val());
                $('.emailforminput').val($("input[name='email']").val());
                $('.addressforminput').val($("input[name='address']").val());
                $('.countryforminput').val($("input[name='country']").val());
                $('.lastnameforminput').val($("input[name='lastname']").val());
                $('.phoneforminput').val($("input[name='phone']").val());
                $('.cityforminput').val($("input[name='city']").val());
                $('.zipcodeforminput').val($("input[name='zipcode']").val());

                $('.step-3').addClass('has-children is-open');
                $('.step-3').children('div').removeAttr('style');
            });
        </script>
    @endif

    <script>
        $('.step-one-proceed-btn').on('click', function(event) {
            $('.step-1').removeClass('is-open');
            $('.step-1').children('div').css('display', 'none');

            $('.step-2').addClass('has-children is-open');
            $('.step-2').children('div').removeAttr('style');
        });



        $('.extra-service-popup').on('click', function() {
            var cart_id = $(this).attr('data-cart-id');
            var data = {
                cart_id: cart_id
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route('serivce.list', $slug) }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(response) {

                    $(".cart-popup").toggleClass("active");
                    $(".cart-popup").html(response.data);
                    $("body").toggleClass("no-scroll");
                }
            });
        });

        $(document).on('click', '.close-icn-btn', function(e) {
            var cart_id = $(this).attr('data-id');
            var data = {
                cart_id: cart_id
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route('cart.remove', $slug) }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(response) {
                    if (response.is_success) {
                        $('.total_price').html(response.total);
                        $('.convenience_fees').html(response.conveniencefees);
                        $('.sub_total').html(response.subtotal);
                        if (response.count == 0) {
                            window.location.href = '{{ route('hotel.home', $slug) }}';
                        }
                        $('.room_cart_' + cart_id).remove();
                    }
                }
            });
        });

        $('.check-out-btn').on('click', function(event) {
            $('.guest-form').removeAttr('style');
        });

        $('.coupon-form').on('submit', function(event) {
            alert('asd');
        });


        $(document).on('click', '.app-coupon-code-btn', function(e) {
            event.preventDefault();
            var couponCode = $('.coupon_code').val();
            var total = $('.total_price').html();
            var totalprice = parseFloat(total.replace(/[^\d.-]/g, ''));
            var data = {
                coupon_code: couponCode,
                totalprice: totalprice
            }
            if (couponCode.length > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ route('coustomer.apply.coupon', $slug) }}',
                    method: 'POST',
                    data: data,
                    context: this,
                    success: function(response) {
                        if (response.is_success) {
                            $('.coupon_price').html(response.discount_price);
                            $('.sub_total').html(response.final_price);
                            show_toastr('success', response.message, 'success');
                            $('.applied_coupon_code').val(response.code);
                            get_payfast_status();
                        } else {
                            show_toastr('Error', response.message, 'msg');
                        }
                    }
                });
            } else {
                show_toastr('Error', 'Please enter coupon code', 'msg');
            }
        });
    </script>
@endpush

@push('script')
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="{{ asset('js/jquery.form.js') }}"></script>
    <script src="{{ asset('custom/libs/jquery-mask-plugin/dist/jquery.mask.min.js') }}"></script>
@endpush
