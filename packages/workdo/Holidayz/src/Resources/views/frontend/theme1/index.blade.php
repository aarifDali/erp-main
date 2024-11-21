@extends('holidayz::frontend.layouts.theme1')
@section('page-title')
    {{__('Home')}}
@endsection
@php
    $path1 = get_file('uploads');
    $path = get_file('packages/workdo/Holidayz/src/Resources/assets/');
@endphp
@section('content')
    @foreach ($getHotelThemeSetting as $key => $section)
        {{-- home header section --}}
        @if ($section['section_name'] == 'Header' && $section['section_enable'] == 'on')
            <section class="home-banner-section">
                <div class="banner-main-slider">
                    <div class="banner-itm">
                        <div class="banner-inner">
                            <div class="banner-img">
                                @if(file_exists(base_path().'/'.'uploads/'.$section['inner-list'][2]['field_default_text']))
                                    <img src="{{ $path1.'/'.$section['inner-list'][2]['field_default_text'] }}">
                                @else
                                    <img src="{{ $path.'/'.$section['inner-list'][2]['field_default_text'] }}">
                                @endif
                            </div>
                            <div class="banner-content">
                                <div class="container banner-content-inner">
                                    <p>{{ __('Welcome to') }}</p>
                                    <div class="section-title">
                                        <h2 class="h1">{{ $hotel->name }} {{ $section['inner-list'][0]['field_default_text'] }}</h2>
                                    </div>
                                    <p>{{ $section['inner-list'][1]['field_default_text'] }}</p>

                                    <form class="services-search-form" action="{{ route('search.rooms', $slug) }}" method="post">
                                        @csrf
                                        <div class="d-flex form-service">
                                            <div class="">
                                                <div class="input-wrapper">
                                                    <label>{{ __('CHECK IN - CHECK OUT') }}</label>
                                                    {{ Form::date('date',  null , array('class' => 'form-control month-btn date-ranger','id'=>'pc-daterangepicker-1' ,'required' => true,'data-min-date'=>'today')) }}
                                                    @error('error')
                                                        <p style="color: red;">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="">
                                                <div class="input-wrapper">
                                                    <label>{{ __('ROOMS') }}</label>
                                                    <select name="rooms" id="rooms" class="form-control">
                                                        @for ($i = 1; $i < 10; $i++)
                                                            <option value={{ $i }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="">
                                                <div class="input-wrapper">
                                                    <label>{{ __('ADULT') }}</label>
                                                    <select name="adult" id="adult" class="form-control">
                                                        @for ($i = 1; $i < 10; $i++)
                                                            <option value={{ $i }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="">
                                                <div class="input-wrapper">
                                                    <label>{{ __('CHILDREN') }}</label>
                                                    <select name="children" id="children" class="form-control">
                                                        @for ($i = 0; $i < 10; $i++)
                                                            <option value={{ $i }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class=" btn-check-service">
                                                <button type="submit" href="#" class="btn w-100 h-100">
                                                    {{ __('Check') }}<br>{{ __('Availabity') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- home intro section --}}
        @if ($section['section_name'] == 'Intro' && $section['section_enable'] == 'on')
            <section class="welcome-section padding-top padding-bottom border-bottom">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-3 col-sm-12">
                            <div class="welcome-text">
                                <div class="section-title">
                                    <h3 class="h4">{{ $section['inner-list'][0]['field_default_text'] }}</h3>
                                </div>
                                <p>{{ $section['inner-list'][1]['field_default_text'] }}</a>
                            </div>
                        </div>
                        <div class="col-md-9 col-12">
                            <div class="welcome-img">
                                @if(file_exists(base_path().'/'.'uploads/'.$section['inner-list'][2]['field_default_text']))
                                    <img src="{{ $path1.'/'.$section['inner-list'][2]['field_default_text'] }}">
                                @else
                                    <img src="{{ $path.'/'.$section['inner-list'][2]['field_default_text'] }}">
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- home Room section --}}
        @if ($section['section_name'] == 'Room' && $section['section_enable'] == 'on' && count($rooms) > 0)
            <section class="rooms-section padding-top padding-bottom">
                <div class="offset-left">
                    <div class="section-title d-flex align-items-center justify-content-between">
                        <div class="section-title-left">
                            <p class="bef-line">{{ __('Explore') }}</p>
                            <h3>{{ $section['inner-list'][0]['field_default_text'] }}</h3>
                        </div>
                        <p>{{ $section['inner-list'][1]['field_default_text'] }}</p>
                    </div>
                    <div class="rooms-slider">
                        @foreach ($rooms as $key => $room)
                            <div class="room-item product-card">
                                <div class="room-item-inner product-card-inner">
                                    <div class="product-image">
                                        <a href="{{ route('room.details',[$slug,$room->id]) }}">
                                            @if ($room->image)
                                                <img src="{{ $path1 . '/rooms/' . $room->image }}" alt="gridview-image" loading="lazy">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <div class="content-inner d-flex justify-content-between">
                                            <div>
                                                <h4>
                                                    <a href="{{ route('room.details',[$slug,$room->id]) }}">
                                                        {{ $room->room_type }}
                                                    </a>
                                                </h4>
                                                <ul class="d-flex icon-wrapper ">
                                                    @foreach ($room->features as $feature)
                                                        <li class="d-flex">
                                                            <i class="{{ $feature->icon }}"></i>
                                                            <span>{{ $feature->name }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="price">
                                                <ins>{{ currency_format_with_sym($room->final_price,$hotel->created_by,$hotel->workspace) }}</ins>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- home about section --}}
        @if ($section['section_name'] == 'About Us' && $section['section_enable'] == 'on')
            <section class="about-us-section">
                <div class="offset-left">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-5 col-12">
                            <div class="about-content">
                                <div class="section-title">
                                    <h3>{{ $section['inner-list'][0]['field_default_text'] }}</h3>
                                </div>
                                <p><b>{{ $section['inner-list'][1]['field_default_text'] }}</b></p>

                                <p>{{ $section['inner-list'][2]['field_default_text'] }}</p>
                                <a href="#" class="btn d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="20" viewBox="0 0 19 20" fill="none">
                                        <path
                                            d="M14.3575 3.848H13.3904V3.26773C13.3904 2.94742 13.1304 2.68746 12.8101 2.68746C12.4898 2.68746 12.2299 2.94742 12.2299 3.26773V3.848H7.20087V3.26773C7.20087 2.94742 6.94091 2.68746 6.6206 2.68746C6.30029 2.68746 6.04033 2.94742 6.04033 3.26773V3.848H5.07322C3.20243 3.848 2.17188 4.87856 2.17188 6.74934V14.8731C2.17188 16.7439 3.20243 17.7745 5.07322 17.7745H14.3575C16.2283 17.7745 17.2589 16.7439 17.2589 14.8731V6.74934C17.2589 4.87856 16.2283 3.848 14.3575 3.848ZM5.07322 5.00854H6.04033V5.58881C6.04033 5.90911 6.30029 6.16908 6.6206 6.16908C6.94091 6.16908 7.20087 5.90911 7.20087 5.58881V5.00854H12.2299V5.58881C12.2299 5.90911 12.4898 6.16908 12.8101 6.16908C13.1304 6.16908 13.3904 5.90911 13.3904 5.58881V5.00854H14.3575C15.5776 5.00854 16.0983 5.52923 16.0983 6.74934V7.32961H3.33241V6.74934C3.33241 5.52923 3.85311 5.00854 5.07322 5.00854ZM14.3575 16.6139H5.07322C3.85311 16.6139 3.33241 16.0932 3.33241 14.8731V8.49015H16.0983V14.8731C16.0983 16.0932 15.5776 16.6139 14.3575 16.6139ZM7.40978 11.0046C7.40978 11.4317 7.06394 11.7783 6.63609 11.7783C6.20901 11.7783 5.85843 11.4317 5.85843 11.0046C5.85843 10.5776 6.20127 10.231 6.62835 10.231H6.63609C7.06317 10.231 7.40978 10.5776 7.40978 11.0046ZM10.5046 11.0046C10.5046 11.4317 10.1587 11.7783 9.73086 11.7783C9.30378 11.7783 8.9532 11.4317 8.9532 11.0046C8.9532 10.5776 9.29604 10.231 9.72311 10.231H9.73086C10.1579 10.231 10.5046 10.5776 10.5046 11.0046ZM13.5993 11.0046C13.5993 11.4317 13.2535 11.7783 12.8256 11.7783C12.3985 11.7783 12.048 11.4317 12.048 11.0046C12.048 10.5776 12.3908 10.231 12.8179 10.231H12.8256C13.2527 10.231 13.5993 10.5776 13.5993 11.0046ZM7.40978 14.0994C7.40978 14.5265 7.06394 14.8731 6.63609 14.8731C6.20901 14.8731 5.85843 14.5265 5.85843 14.0994C5.85843 13.6723 6.20127 13.3257 6.62835 13.3257H6.63609C7.06317 13.3257 7.40978 13.6723 7.40978 14.0994ZM10.5046 14.0994C10.5046 14.5265 10.1587 14.8731 9.73086 14.8731C9.30378 14.8731 8.9532 14.5265 8.9532 14.0994C8.9532 13.6723 9.29604 13.3257 9.72311 13.3257H9.73086C10.1579 13.3257 10.5046 13.6723 10.5046 14.0994ZM13.5993 14.0994C13.5993 14.5265 13.2535 14.8731 12.8256 14.8731C12.3985 14.8731 12.048 14.5265 12.048 14.0994C12.048 13.6723 12.3908 13.3257 12.8179 13.3257H12.8256C13.2527 13.3257 13.5993 13.6723 13.5993 14.0994Z"
                                            fill="black" />
                                    </svg>
                                    <span>{{ __('Book Appointment') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-7 col-12">
                            <div class="about-img">
                                @if(file_exists(base_path().'/'.'uploads/'.$section['inner-list'][3]['field_default_text']))
                                    <img src="{{ $path1.'/'.$section['inner-list'][3]['field_default_text'] }}" alt="about">
                                @else
                                    <img src="{{ $path.'/'.$section['inner-list'][3]['field_default_text'] }}" alt="about">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- our rooms section --}}
        @if ($section['section_name'] == 'Our Room' && $section['section_enable'] == 'on')
            <section class="about-us-section our-rooms-section">
                <div class="offset-right">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-md-7 col-12">
                            <div class="our-room-img">
                                @if(file_exists(base_path().'/'.'uploads/'.$section['inner-list'][3]['field_default_text']))
                                    <img src="{{ $path1.'/'.$section['inner-list'][3]['field_default_text'] }}" alt="about">
                                @else
                                    <img src="{{ $path.'/'.$section['inner-list'][3]['field_default_text'] }}" alt="about">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-5 col-12">
                            <div class="about-content our-room-content">
                                <div class="section-title">
                                    <h3>{{ $section['inner-list'][0]['field_default_text'] }}</h3>
                                </div>
                                <p><b>{!! $section['inner-list'][1]['field_default_text'] !!}</b></p>
                                <p>{{ $section['inner-list'][2]['field_default_text'] }}</p>
                                <a href="#" class="btn d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="20" viewBox="0 0 19 20" fill="none">
                                        <path
                                            d="M14.3575 3.848H13.3904V3.26773C13.3904 2.94742 13.1304 2.68746 12.8101 2.68746C12.4898 2.68746 12.2299 2.94742 12.2299 3.26773V3.848H7.20087V3.26773C7.20087 2.94742 6.94091 2.68746 6.6206 2.68746C6.30029 2.68746 6.04033 2.94742 6.04033 3.26773V3.848H5.07322C3.20243 3.848 2.17188 4.87856 2.17188 6.74934V14.8731C2.17188 16.7439 3.20243 17.7745 5.07322 17.7745H14.3575C16.2283 17.7745 17.2589 16.7439 17.2589 14.8731V6.74934C17.2589 4.87856 16.2283 3.848 14.3575 3.848ZM5.07322 5.00854H6.04033V5.58881C6.04033 5.90911 6.30029 6.16908 6.6206 6.16908C6.94091 6.16908 7.20087 5.90911 7.20087 5.58881V5.00854H12.2299V5.58881C12.2299 5.90911 12.4898 6.16908 12.8101 6.16908C13.1304 6.16908 13.3904 5.90911 13.3904 5.58881V5.00854H14.3575C15.5776 5.00854 16.0983 5.52923 16.0983 6.74934V7.32961H3.33241V6.74934C3.33241 5.52923 3.85311 5.00854 5.07322 5.00854ZM14.3575 16.6139H5.07322C3.85311 16.6139 3.33241 16.0932 3.33241 14.8731V8.49015H16.0983V14.8731C16.0983 16.0932 15.5776 16.6139 14.3575 16.6139ZM7.40978 11.0046C7.40978 11.4317 7.06394 11.7783 6.63609 11.7783C6.20901 11.7783 5.85843 11.4317 5.85843 11.0046C5.85843 10.5776 6.20127 10.231 6.62835 10.231H6.63609C7.06317 10.231 7.40978 10.5776 7.40978 11.0046ZM10.5046 11.0046C10.5046 11.4317 10.1587 11.7783 9.73086 11.7783C9.30378 11.7783 8.9532 11.4317 8.9532 11.0046C8.9532 10.5776 9.29604 10.231 9.72311 10.231H9.73086C10.1579 10.231 10.5046 10.5776 10.5046 11.0046ZM13.5993 11.0046C13.5993 11.4317 13.2535 11.7783 12.8256 11.7783C12.3985 11.7783 12.048 11.4317 12.048 11.0046C12.048 10.5776 12.3908 10.231 12.8179 10.231H12.8256C13.2527 10.231 13.5993 10.5776 13.5993 11.0046ZM7.40978 14.0994C7.40978 14.5265 7.06394 14.8731 6.63609 14.8731C6.20901 14.8731 5.85843 14.5265 5.85843 14.0994C5.85843 13.6723 6.20127 13.3257 6.62835 13.3257H6.63609C7.06317 13.3257 7.40978 13.6723 7.40978 14.0994ZM10.5046 14.0994C10.5046 14.5265 10.1587 14.8731 9.73086 14.8731C9.30378 14.8731 8.9532 14.5265 8.9532 14.0994C8.9532 13.6723 9.29604 13.3257 9.72311 13.3257H9.73086C10.1579 13.3257 10.5046 13.6723 10.5046 14.0994ZM13.5993 14.0994C13.5993 14.5265 13.2535 14.8731 12.8256 14.8731C12.3985 14.8731 12.048 14.5265 12.048 14.0994C12.048 13.6723 12.3908 13.3257 12.8179 13.3257H12.8256C13.2527 13.3257 13.5993 13.6723 13.5993 14.0994Z"
                                            fill="black" />
                                    </svg>
                                    <span>{{ __('Book Appointment') }}</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        @endif


        {{-- amenities section --}}
        @if ($section['section_name'] == 'Amenities' && $section['section_enable'] == 'on' && $amenities->count() > 0 )
            <section class="amenities-section padding-top border-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="amenities-content">
                                <div class="section-title">
                                <p class="bef-line">{{ __('Facilities') }}</p>
                                <h3>{{ $section['inner-list'][0]['field_default_text'] }}</h3>
                                </div>
                                <p>{{ $section['inner-list'][1]['field_default_text'] }}</p>
                                <ul>
                                    @foreach ($amenities->take(5) as $item)
                                        <li>
                                            <a href="#">
                                                <i class="{{ $item->icon }}"></i>
                                                <span>{{ $item->name }}</span >
                                                </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="amenitoes-img-center">
                                @if (isset($amenitiesImages[0]))
                                <img src="{{ $path1.'/'.'amenities/'.$amenitiesImages[0] }}" alt="about">
                                @endif
                                @if (isset($amenitiesImages[1]))
                                <img src="{{ $path1.'/'.'amenities/'.$amenitiesImages[1] }}" alt="about">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="amenitoes-img-right">
                                @if (isset($amenitiesImages[2]))
                                <img src="{{ $path1.'/'.'amenities/'.$amenitiesImages[2] }}" alt="about">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- testimonial section --}}
        @if ($section['section_name'] == 'Testimonial' && $section['section_enable'] == 'on')
            <section class="testimonial-section padding-top padding-bottom">
                <div class="container">
                    <div class="section-title text-center">
                        <p class="bef-line">{{ __('Testimonials') }}</p>
                        <h3>{{ __('Testimonials') }}</h3>
                    </div>
                    <div class="testimonial-slider">

                        @for ($i = 0; $i < $section['loop_number']; $i++)
                            <div class="test-itm">
                                <div class="test-content">
                                    @if(isset($section['homepage-testimonial-card-description']))
                                        <h4>“{{ $section['homepage-testimonial-card-description'][$i] }}“</h4>
                                    @else
                                        <h4>“{{ $section['inner-list'][4]['field_default_text'] }}“</h4>
                                    @endif
                                    <div class="star-rating d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                            <path
                                                d="M10.0549 0.306152L12.8437 6.67109L19.7589 7.35651L14.5673 11.9757L16.0523 18.7642L10.0549 15.2541L4.05754 18.7642L5.54258 11.9757L0.350951 7.35651L7.26615 6.67109L10.0549 0.306152Z"
                                                fill="black" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                            <path
                                                d="M10.0549 0.306152L12.8437 6.67109L19.7589 7.35651L14.5673 11.9757L16.0523 18.7642L10.0549 15.2541L4.05754 18.7642L5.54258 11.9757L0.350951 7.35651L7.26615 6.67109L10.0549 0.306152Z"
                                                fill="black" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                            <path
                                                d="M10.0549 0.306152L12.8437 6.67109L19.7589 7.35651L14.5673 11.9757L16.0523 18.7642L10.0549 15.2541L4.05754 18.7642L5.54258 11.9757L0.350951 7.35651L7.26615 6.67109L10.0549 0.306152Z"
                                                fill="black" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                            <path
                                                d="M10.0549 0.306152L12.8437 6.67109L19.7589 7.35651L14.5673 11.9757L16.0523 18.7642L10.0549 15.2541L4.05754 18.7642L5.54258 11.9757L0.350951 7.35651L7.26615 6.67109L10.0549 0.306152Z"
                                                fill="black" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                                            <path
                                                d="M10.0549 0.306152L12.8437 6.67109L19.7589 7.35651L14.5673 11.9757L16.0523 18.7642L10.0549 15.2541L4.05754 18.7642L5.54258 11.9757L0.350951 7.35651L7.26615 6.67109L10.0549 0.306152Z"
                                                fill="black" />
                                        </svg>
                                    </div>
                                    @if(isset($section['homepage-testimonial-card-title']) && isset($section['homepage-testimonial-card-sub-text']))
                                        <p><b>{{ $section['homepage-testimonial-card-title'][$i] }}</b> <br> {{ $section['homepage-testimonial-card-sub-text'][$i] }}</p>
                                        <div class="amenitoes-img-center" style="text-align: -webkit-center;">
                                            @if (isset($section['homepage-testimonial-card-image']))
                                                @if(file_exists(base_path().'/'.'uploads/'.$section['homepage-testimonial-card-image'][$i]['field_prev_text']))
                                                    <img src="{{ $path1.'/'.$section['homepage-testimonial-card-image'][$i]['field_prev_text'] }}" alt="about" style="vertical-align: middle;
                                                        width: 70px;
                                                        height: 60px;
                                                        border-radius: 50%;">
                                                @else
                                                    <img src="{{ $path.'/'.$section['homepage-testimonial-card-image'][$i]['field_prev_text'] }}" alt="about" style="vertical-align: middle;
                                                        width: 70px;
                                                        height: 60px;
                                                        border-radius: 50%;">
                                                @endif
                                            @else
                                            <img src="{{ $path.'/'.$section['inner-list'][1]['field_default_text'] }}" alt="about">
                                            @endif
                                        </div>
                                    @else
                                        <p><b>{{ $section['inner-list'][2]['field_default_text'] }}</b> <br> {{ $section['inner-list'][3]['field_default_text'] }}</p>
                                    @endif
                                    
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </section>
        @endif

        {{-- Swimming Section  --}}
        @if ($section['section_name'] == 'Swimming Pool' && $section['section_enable'] == 'on')
            <section class="swiming-pool-section">
                <div class="offset-right">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-md-7 col-12">
                            <div class="our-room-img">
                                @if(file_exists(base_path().'/'.'uploads/'.$section['inner-list'][2]['field_default_text']))
                                    <img src="{{ $path1.'/'.$section['inner-list'][2]['field_default_text'] }}" alt="swiming-pool">
                                @else
                                    <img src="{{ $path.'/'.$section['inner-list'][2]['field_default_text'] }}" alt="swiming-pool">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-5 col-12">
                            <div class="swiming-pool-content">
                                <div class="section-title">
                                    <h3>{{ $section['inner-list'][0]['field_default_text'] }}</h3>
                                </div>
                                <p>{{ $section['inner-list'][1]['field_default_text'] }}</p>
                                <a href="#" class="d-flex align-items-center clock-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" viewBox="0 0 17 18" fill="none">
                                        <path
                                            d="M15.2546 4.62601C15.1505 4.73226 15.0125 4.78537 14.875 4.78537C14.7412 4.78537 14.6067 4.73508 14.5032 4.63379L13.0866 3.24548C12.8776 3.04007 12.8741 2.70358 13.0788 2.49392C13.2849 2.28425 13.6205 2.28072 13.8309 2.48614L15.2475 3.87444C15.4558 4.07986 15.4593 4.41634 15.2546 4.62601ZM3.91715 3.21289C4.12469 3.00535 4.12469 2.66886 3.91715 2.46132C3.70961 2.25378 3.37313 2.25378 3.16558 2.46132L1.74892 3.87799C1.54138 4.08553 1.54138 4.42201 1.74892 4.62955C1.85233 4.73297 1.98836 4.78537 2.12436 4.78537C2.26036 4.78537 2.39638 4.73368 2.49979 4.62955L3.91715 3.21289ZM12.138 14.2197L13.1262 15.2127C13.3337 15.421 13.3323 15.7574 13.1248 15.9643C13.0214 16.0677 12.8853 16.1187 12.75 16.1187C12.614 16.1187 12.4773 16.0663 12.3739 15.9622L11.2002 14.7828C10.3828 15.1809 9.46905 15.4104 8.50005 15.4104C7.53105 15.4104 6.618 15.1809 5.79987 14.7828L4.62618 15.9622C4.52276 16.0663 4.38605 16.1187 4.25005 16.1187C4.11476 16.1187 3.97872 16.067 3.8753 15.9643C3.66776 15.7574 3.66638 15.421 3.87392 15.2127L4.86206 14.2197C3.31435 13.0921 2.30213 11.2702 2.30213 9.21245C2.30213 5.79474 5.08234 3.01453 8.50005 3.01453C11.9178 3.01453 14.698 5.79474 14.698 9.21245C14.698 11.2702 13.6857 13.0921 12.138 14.2197ZM8.50005 14.3479C11.332 14.3479 13.6355 12.0444 13.6355 9.21245C13.6355 6.38053 11.332 4.07703 8.50005 4.07703C5.66813 4.07703 3.36463 6.38053 3.36463 9.21245C3.36463 12.0444 5.66813 14.3479 8.50005 14.3479ZM9.0313 9.02049V6.40748C9.0313 6.11423 8.7933 5.87623 8.50005 5.87623C8.2068 5.87623 7.9688 6.11423 7.9688 6.40748V9.24081C7.9688 9.38177 8.02473 9.51708 8.12461 9.61625L9.54128 11.0329C9.64469 11.1363 9.78071 11.1887 9.91671 11.1887C10.0527 11.1887 10.1887 11.137 10.2922 11.0329C10.4997 10.8254 10.4997 10.4889 10.2922 10.2813L9.0313 9.02049Z"
                                            fill="black" />
                                    </svg>
                                    <span>{{ __('Open Daily: 7:00 pm - 11:00 pm') }}</span>
                                </a>
                                <a href="#" class="btn d-flex align-items-center">
                                    <span>{{ __('Read More') }}</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        @endif

        {{-- Blog Section  --}}
        @if ($section['section_name'] == 'Offers & News' && $section['section_enable'] == 'on')
            <section class="blog-section padding-top padding-bottom border-bottom">
                <div class="container">
                    <div class="blog-title text-center">
                        <p class="bef-line">{{ $section['section_name'] }}</p>
                        <div class="section-title">
                            <h3>{{ $section['section_name'] }}</h3>
                        </div>
                        <p>{{ __('Discover our collection of inspiring offers, seasonal specials and themed escapes. Book your stay directly with us online or over the phone on any rate, and enjoy exclusive inclusions.') }}</p>
                    </div>
                    <div class="blog-slider">
                        @for ($i = 0; $i < $section['loop_number']; $i++)
                            <div class="blog-item product-card">
                                <div class="blog-item-inner product-card-inner">
                                    <div class="product-image">
                                        <a href="#">
                                            @if(isset($section['homepage-blog-image']))
                                                @if(file_exists(base_path().'/'.'uploads/'.$section['homepage-blog-image'][$i]['field_prev_text']))
                                                    <img src="{{ $path1.'/'.$section['homepage-blog-image'][$i]['field_prev_text'] }}" alt="about">
                                                @else
                                                    <img src="{{ $path.'/'.$section['homepage-blog-image'][$i]['field_prev_text'] }}" alt="about">
                                                @endif
                                            @else
                                                <img src="{{ $path.'/'.$section['inner-list'][3]['field_default_text'] }}" alt="about">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <div class="content-inner d-flex justify-content-between">
                                            <div>
                                                <div class="card-sub-title d-flex align-items-center">
                                                    @if(isset($section['homepage-sub-title']) && isset($section['homepage-blog-date']))
                                                        <p>{{ $section['homepage-sub-title'][$i] }}</p>
                                                        <span class="opacity">{{ $section['homepage-blog-date'][$i] }}</span>
                                                    @else
                                                        <p>{{ $section['inner-list'][1]['field_default_text'] }}</p>
                                                        <span class="opacity">{{ $section['inner-list'][2]['field_default_text'] }}</span>
                                                    @endif
                                                </div>
                                                <h4>
                                                    <a href="#0">
                                                        @if(isset($section['homepage-blog-title']))
                                                            {{ $section['homepage-blog-title'][$i] }}
                                                        @else
                                                            {{ $section['inner-list'][0]['field_default_text'] }}
                                                        @endif
                                                    </a>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </section>
        @endif

        {{--social section  --}}
        @if ($section['section_name'] == 'Instagram' && $section['section_enable'] == 'on')
            <section class="instagram-section padding-top border-bottom">
                <div class="container">
                    <div class="instagram-title text-center">
                        <p class="bef-line">{{ __('Post') }}</p>
                        <div class="section-title">
                            <h3>{{ $section['section_name'] }}</h3>
                        </div>
                        <p>{{ '@'.str_replace(' ', '_', $hotel->name) }}</p>
                    </div>
                </div>
                <div class="insta-slider">
                    @foreach ($section['inner-list'] as $image)
                        @if (!empty($image['image_path']))
                            @foreach ($image['image_path'] as $img)
                                <div class="insta-itm">
                                    <div class="insta-itm-inner">
                                        <div class="insta-itm-img">
                                            @if(file_exists(base_path().'/'.'uploads/'.$img))
                                                <img src="{{$path1 .'/'. (!empty($img) ? $img : 'storego-image.png') }}" alt="insta">
                                            @else
                                                <img src="{{$path .'/'. (!empty($img) ? $img : 'storego-image.png') }}" alt="insta">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @for ($i = 1; $i <= 6 ; $i++)
                                <div class="insta-itm">
                                    <div class="insta-itm-inner">
                                        <div class="insta-itm-img">
                                            <img src="{{$path .'/'.'theme1/social/insta'.$i.'.png' }}" alt="insta">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach

@endsection
