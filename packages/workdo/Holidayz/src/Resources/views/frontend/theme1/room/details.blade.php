@extends('holidayz::frontend.layouts.theme1')
@section('page-title')
    {{__('Details')}}
@endsection
@php
    $path = get_file('uploads/rooms');
    $path2 = get_file('/');
@endphp

@section('content')
    <div class="wrapper" style="margin-top: 70.5966px;">
        <section class="room-page-main-section border-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 col-12">
                        <div class="room-main-img room-main-img-left">
                            @if ($room->image)
                                <img id="image" src="{{ $path . '/' . $room->image }}" class="big-logo room-image img-fluid">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-7 col-12 small-img-wrapper">
                        <div class="row">
                            @foreach ($room->getImages->take(4) as $item)
                                <div class="col-sm-6 col-12">
                                    <div class="room-main-img">
                                        <img id="image" src="{{ $path2 . '/' . $item->name }}"
                                            class="big-logo room-image img-fluid">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <section class="room-desc border-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-8 col-12">
                        <div class="room-desc-left border-bottom">
                            <h3>{{ $room->room_type }}</h3>
                            <ul class="d-flex room-item">
                                @foreach ($room->features as $feature)
                                    <li class="d-flex">
                                        <i class="{{ $feature->icon }}"></i>
                                        <span>{{ $feature->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <p>{!! $room->short_description !!}</p>
                        </div>

                        @if (count($facilities) > 0)
                            <div class="room-services tabs-wrapper border-bottom">
                                <h4>{{ __('Extra services') }}</h4>
                                <ul class="cat-tab tabs border-bottom">

                                    @foreach ($facilities as $key => $item)
                                        <li class="tab-link @if ($key == 0) active @endif"
                                            data-tab="facilities_{{ $item->id }}">
                                            <a href="javascript:;" class="">{{ $item->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tabs-container">
                                    @foreach ($facilities as $key => $facility)
                                        <div class="tab-content @if ($key == 0) active @endif"
                                            id="facilities_{{ $facility->id }}">
                                            @foreach ($facility->getChildFacilities as $item)
                                                <div class="transfer-det">
                                                    <div class="d-flex transfer-title">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46"
                                                            viewBox="0 0 46 46" fill="none">
                                                            <path
                                                                d="M30.3063 5.11902H15.5306C11.0647 5.11902 8.60455 7.57916 8.60455 12.0451V32.3616C8.60455 35.5864 9.90481 37.7473 12.2985 38.7096V39.7494C12.2985 40.5141 12.919 41.1346 13.6837 41.1346C14.4483 41.1346 15.0689 40.5141 15.0689 39.7494V39.2544C15.2259 39.26 15.3681 39.2877 15.5306 39.2877H30.3063C30.4688 39.2877 30.611 39.26 30.768 39.2544V39.7494C30.768 40.5141 31.3886 41.1346 32.1532 41.1346C32.9179 41.1346 33.5384 40.5141 33.5384 39.7494V38.7096C35.9321 37.7473 37.2323 35.5882 37.2323 32.3616V12.0451C37.2323 7.57916 34.7722 5.11902 30.3063 5.11902ZM15.5306 7.88945H30.3063C33.2189 7.88945 34.4619 9.13245 34.4619 12.0451V25.4355H11.375V12.0451C11.375 9.13245 12.618 7.88945 15.5306 7.88945ZM30.3063 36.5172H15.5306C12.618 36.5172 11.375 35.2742 11.375 32.3616V28.2059H34.4619V32.3616C34.4619 35.2742 33.2189 36.5172 30.3063 36.5172ZM19.6863 12.0451C19.6863 11.2805 20.3069 10.6599 21.0715 10.6599H24.7654C25.53 10.6599 26.1506 11.2805 26.1506 12.0451C26.1506 12.8097 25.53 13.4303 24.7654 13.4303H21.0715C20.3069 13.4303 19.6863 12.8097 19.6863 12.0451ZM30.3432 32.3616C30.3432 33.3811 29.5158 34.2085 28.4962 34.2085C27.4767 34.2085 26.6401 33.3811 26.6401 32.3616C26.6401 31.3421 27.4564 30.5146 28.4778 30.5146H28.4962C29.5158 30.5146 30.3432 31.3421 30.3432 32.3616ZM19.2615 32.3616C19.2615 33.3811 18.434 34.2085 17.4145 34.2085C16.395 34.2085 15.5584 33.3811 15.5584 32.3616C15.5584 31.3421 16.3747 30.5146 17.3961 30.5146H17.4145C18.434 30.5146 19.2615 31.3421 19.2615 32.3616ZM40.9263 19.4329V24.9738C40.9263 25.7384 40.3057 26.359 39.541 26.359C38.7764 26.359 38.1558 25.7384 38.1558 24.9738V19.4329C38.1558 18.6683 38.7764 18.0477 39.541 18.0477C40.3057 18.0477 40.9263 18.6683 40.9263 19.4329ZM7.68108 19.4329V24.9738C7.68108 25.7384 7.0605 26.359 6.29586 26.359C5.53122 26.359 4.91064 25.7384 4.91064 24.9738V19.4329C4.91064 18.6683 5.53122 18.0477 6.29586 18.0477C7.0605 18.0477 7.68108 18.6683 7.68108 19.4329Z"
                                                                fill="#AB9A86" />
                                                        </svg>
                                                        <div>
                                                            <h4>{{ $item->name }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="transfer-price">
                                                        <div class="price">
                                                            <ins>{{ currency_format_with_sym($item->price,$hotel->created_by,$hotel->workspace) }}</ins>
                                                        </div>
                                                        <a class="btn-secondary transfer-btn add-to-service"
                                                            data-url={{ route('addToservice', [$slug, $item->id]) }}
                                                            data-id="{{ $item->id }}"
                                                            data-price="{{ $item->price }}">{{ __('Select') }}</a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="room-facilities tabs-wrapper">
                            <h4>{{ __('Room Facilities') }}</h4>
                            <ul class="cat-tab tabs border-bottom">
                                <li class="tab-link active" data-tab="tab-3">
                                    <a href="javascript:;" class="">{{ __('Room Information') }}</a>
                                </li>
                                <li class="tab-link" data-tab="tab-6">
                                    <a href="javascript:;" class="">{{ __('Reviews') }}</a>
                                </li>
                            </ul>
                            <div class="tabs-container">
                                <div class="tab-content active" id="tab-3">
                                    <p>{!! $room->description !!}</p>

                                    <div class="d-flex room-capacity-box">
                                        <ul>
                                            <li>{{ __('Max capacity') }}:</li>
                                            <li>{{ $room->adults }} {{ __('Adults') }}, {{ $room->children }} {{ __('Children (Max guests:') }}'
                                                {{ $room->adults + $room->children }})</li>
                                        </ul>
                                        @if (isset($hotel->check_in) && isset($hotel->check_out))
                                            <ul>
                                                <li>{{ __('Check-In and Check-Out Time') }}</li>
                                                <li>{{ __('Check-in:') }}
                                                    {{ \Workdo\Holidayz\Entities\Utility::company_Time_formate($hotel->check_in,$hotel->created_by,$hotel->workspace) }}<br>{{ __('Check-out') }}:
                                                    {{ \Workdo\Holidayz\Entities\Utility::company_Time_formate($hotel->check_out,$hotel->created_by,$hotel->workspace) }}</li>
                                            </ul>
                                        @endif
                                    </div>

                                    @if(count($room->features) > 0)
                                        <div class="facilities-summry">
                                            <h4 class="h6">{{ __('Room Facilities') }}:</h4>
                                            <div class="d-flex fac-list">
                                                @foreach ($room->features as $item)
                                                    <ul>
                                                        <li>
                                                            <i class="{{ $item->icon }}"></i>
                                                            <span>{{ $item->name }}</span>
                                                        </li>
                                                    </ul>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @php
                                        $images = Workdo\Holidayz\Entities\RoomsImages::where('workspace', $hotel->workspace)->get();
                                    @endphp
                                    @if (count($room->getImages) > 0)
                                        <div class="hotel-imges">
                                            <h4 class="h6">{{ __('Hotel Images') }}:</h4>
                                            <div class="hotel-img-inner">
                                                @php
                                                    // $imagePath = get_file('uploads/hotel/');
                                                    $imagePath = get_file('/');
                                                @endphp
                                                @foreach ($room->getImages as $item)
                                                    <div>
                                                        <img src="{{ $imagePath . $item->name }}" style="width: 272px; height: 184px;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if ($hotel->policy)
                                        <div class="hotel-polices">
                                            <h4 class="h6">{{ __('Hotel Policies') }}:</h4>
                                            {!! $hotel->policy !!}
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-content" id="tab-6">
                                    <div class="reviews">
                                        <div class="rating-det border-bottom">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 col-12">
                                                    <div class="rating-summry">
                                                        <p class="rat-sub-title">{{ __('Ratings Summary') }}</p>
                                                        <h4 class="h2">{{ __('4.0') }} <span>/ {{ __('5') }}</span></h4>
                                                        <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/images/rating.png') }}">
                                                        <p>{{ __('Based on 1 review') }} </p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-7 col-12">
                                                    <p class="rat-sub-title">{{ __('Categories') }}</p>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-3">
                                                            <div class="rating-summry">
                                                                <div class="rating-catry">
                                                                    <div class="rating-car-box">
                                                                        <h4>{{ __('4.0') }}</h4>
                                                                    </div>
                                                                    <p>{{ __('Food') }}</p>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6 col-sm-3">
                                                            <div class="rating-summry">
                                                                <div class="rating-catry">
                                                                    <div class="rating-car-box">
                                                                        <h4>{{ __('4.0') }}</h4>
                                                                    </div>
                                                                    <p>{{ __('Room service') }}</p>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="rating-filter border-bottom">
                                            <h4>{{ __('Filters') }}:</h4>
                                            <div class="form-group">
                                                <label>{{ __('SORT BY') }}:</label>
                                                <div class="nice-select form-control">
                                                    <span class="current">{{ __('PRICE') }}</span>
                                                    <ul class="list">
                                                        <li data-value="PRICE" class="option selected focus">{{ __('PRICE') }}</li>
                                                        <li data-value="PRICE" class="option">{{ __('PRICE') }}</li>
                                                        <li data-value="PRICE" class="option">{{ __('PRICE') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clients-review">
                                            <div class="clients-review-box">
                                                <div>
                                                    <h4>{{ __('Good location and comfortable for short stays') }}</h4>
                                                    <p>{{ __('Hotel was good and rooms were fine. Location was good. Lift facility
                                                        was there. Fair prices.Hotel was good and rooms
                                                        were fine. Location was good. Lift facility was there. Fair
                                                        prices.Hotel was good and rooms were fine. Location was
                                                        good. Lift facility was there. Fair prices.') }}</p>
                                                    <p>{{ __('0 people found it helpful.') }}</p>
                                                </div>
                                                <div>
                                                    <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/images/rating.png') }}">
                                                    <p>{{ __('04/11/2023') }}</p>
                                                </div>
                                            </div>
                                            <div class="review-reply-box">
                                                <h4>{{ __($hotel->name) }}</h4>
                                                <p>{{ __('has replied on Apr 11, 2023') }}</p>
                                                <p>{{ __('Dear Guest,We are pleased to hear such kind words from you.Looking
                                                    forward to welcoming you again!') }}</p>
                                            </div>
                                            <div class="clients-review-box border-top">
                                                <div>
                                                    <h4>{{ __('Good location and comfortable for short stays') }}</h4>
                                                    <p>{{ __('Hotel was good and rooms were fine. Location was good. Lift facility
                                                        was there. Fair prices.Hotel was good and rooms
                                                        were fine. Location was good. Lift facility was there. Fair
                                                        prices.Hotel was good and rooms were fine. Location was
                                                        good. Lift facility was there. Fair prices.') }}</p>
                                                    <p>{{ __('0 people found it helpful.') }}</p>
                                                </div>
                                                <div>
                                                    <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/images/rating.png') }}">
                                                    <p>{{ __('04/11/2023') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-12 bg-light">
                            <form action="#" id="room-details-form">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}" class="room_id">
                            <div class="book-room">
                                <h4>{{ __('Book your Room') }}</h4>
                                <div class="form-group">
                                    <label>{{ __('CHECK IN - CHECK OUT') }}</label>
                                    {{ Form::date('date', null, ['class' => 'form-control month-btn date-ranger total_day', 'id' => 'pc-daterangepicker-1','data-url'=>route('add.day',$slug), 'required' => true,'data-min-date'=> 'today']) }}
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Room') }}</label>
                                    <div class="nice-select form-control">
                                        <select name="room" id="room" class="form-control total_room" data-url="{{ route('add.room',$slug) }}">
                                            @for ($i = 1; $i <= $room->total_room; $i++)
                                                <option value={{ $i }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Adult') }}</label>
                                    <div class="nice-select form-control">
                                        <select name="adult" id="adult" class="form-control">
                                            @for ($i = 1; $i <= $room->adults; $i++)
                                                <option value={{ $i }} @if ($room->adults == $i) {{ __('selected') }} @endif>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Children') }}</label>
                                    <div class="nice-select form-control">
                                        <select name="children" id="children" class="form-control">
                                            @for ($i = 1; $i <= $room->children; $i++)
                                                <option value={{ $i }}  @if ($room->children == $i) {{ __('selected') }} @endif>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="price-group">
                                    <div>
                                        <p>{{ __('Room price') }}</p>
                                        <h4 class="regular_price">{{ currency_format_with_sym($room->final_price,$hotel->created_by,$hotel->workspace) }}</h4>
                                        <input type="hidden" name="room_price" class="room_price" value="{{ $room->final_price }}">
                                    </div>
                                    <h4 class="h2">+</h4>
                                    <div>
                                        <p>{{ __('Extra services') }}</p>
                                        <h4 class="extra_service_price">{{ currency_format_with_sym(0,$hotel->created_by,$hotel->workspace) }}</h4>
                                        <input type="hidden" class="extra_service" name="extra_service" value="0" readonly>
                                        <input type="hidden" class="main_extra_service" name="main_extra_service" value="0" readonly>
                                    </div>
                                </div>
                                <input type="hidden" name="serviceIds" class="serviceIds">

                                <div class="total-price">
                                    <p>{{ __('Subtotal') }}</p>
                                    <h4 class="h3 subtotal_price">{{ currency_format_with_sym($room->final_price,$hotel->created_by,$hotel->workspace) }}
                                    </h4>
                                    <input type="hidden" class="final_price" name="final_price"
                                        value="{{ $room->final_price }}" readonly>
                                        @php
                                            $availableRoom = $room->total_room - $room->getRoomTotal()[0]->total_booking_rooms;
                                        @endphp
                                    @if ($availableRoom <= 0)
                                        <p class="text-red">{{ __('Whoops,') }} {{ $availableRoom }} {{  __('rooms left') }}</p>
                                    @else
                                        <p class="text-red">{{ __('Hurry!') }} {{ $availableRoom }} {{  __('rooms left') }}</p>
                                    @endif
                                    <button class="btn-secondary booking-btn" @if ($availableRoom <= 0) disabled @endif>{{ __('Book Now') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </section>
        <section class="rooms-section padding-top padding-bottom">
            <div class="offset-left">
                <div class="section-title d-flex align-items-center justify-content-between">
                    <div class="section-title-left">
                        <p class="bef-line">{{ __('Explore') }}</p>
                        <h3>{{ __('Rooms & Suites') }}</h3>
                    </div>
                    <p>{{ __('It is a long established fact that a reader will be distracted by the readable content of a page when
                        looking at its layout.') }}
                    </p>
                </div>
                <div class="rooms-slider">
                    @php
                        $path = get_file('uploads');
                    @endphp
                    @foreach ($rooms as $key => $room)
                        <div class="room-item product-card">
                            <div class="room-item-inner product-card-inner">
                                <div class="product-image">
                                    <a href="{{ route('room.details', [$slug, $room->id]) }}">
                                        @if ($room->image)
                                            <img src="{{ $path . '/rooms/' . $room->image }}" alt="gridview-image"
                                                loading="lazy">
                                        @endif
                                    </a>
                                </div>
                                <div class="product-content">
                                    <div class="content-inner d-flex justify-content-between">
                                        <div>
                                            <h4>
                                                <a href="{{ route('room.details', [$slug, $room->id]) }}">
                                                    {{ $room->room_type }}
                                                </a>
                                            </h4>
                                            <ul class="d-flex icon-wrapper">
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
    </div>
@endsection

@push('script')
    <script>
        var arr = [];
        $(document).on('click', '.add-to-service', function(event) {
            event.preventDefault();
            if ($.inArray($(this).attr('data-id'), arr) !== -1) {
                show_toastr('Error', 'This service already added.', 'error');
            } else {
                arr.push($(this).attr('data-id'));
                var data = {
                    service_id: $(this).attr('data-id'),
                    finalPrice: $('.final_price').val(),
                    extraService: $('.extra_service').val(),
                    servicePrice: $(this).attr('data-price')
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "post",
                    url: $(this).attr('data-url'),
                    data: data,
                    cache: false,
                    beforeSend: function() {
                        $('.booking-btn').attr('disabled');
                    },
                    success: function(data) {
                        $('.extra_service_price').html(data.extra_service_diplay_price);
                        $('.subtotal_price').html(data.subtotal_diplay_price);
                        $('.final_price').val(data.subtotal_price);
                        $('.extra_service').val(data.extra_service_price);
                        $('.main_extra_service').val(data.extra_service_price);
                        $('.serviceIds').val(arr);
                    },
                    complete: function() {
                        $('.booking-btn').removeAttr('disabled');
                    },
                });
            }
        });

        $(document).on('change', '.total_room',function(){
            var data = {
                    room_price:$('.room_price').val(),
                    finalPrice: $('.final_price').val(),
                    extraService: $('.main_extra_service').val(),
                    date:$('.total_day').val(),
                    hotel_created_by:"{{$hotel->created_by}}",
                    hotel_workspace:"{{$hotel->workspace}}",
                    room:$(this).val()
                };
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "get",
                    url: $(this).attr('data-url'),
                    data: data,
                    cache: false,
                    beforeSend: function() {
                        $('.booking-btn').attr('disabled');
                    },
                    success: function(data) {
                        $('.regular_price').html(data.regular_price);
                        $('.extra_service_price').html(data.extra_service_diplay_price);
                        $('.subtotal_price').html(data.subtotal_diplay_price);
                        $('.final_price').val(data.subtotal_price);
                        $('.extra_service').val(data.extra_service_price);
                    },
                    complete: function() {
                        $('.booking-btn').removeAttr('disabled');
                    },
                });
        });

        $(document).on('change', '.total_day',function(){
            $date = $(this).val().split('to');
            if(typeof $date[0] != "undefined" && typeof $date[1] != "undefined" ){
                var data = {
                        room_price:$('.room_price').val(),
                        finalPrice: $('.final_price').val(),
                        extraService: $('.main_extra_service').val(),
                        room:$('.total_room').val(),
                        hotel_created_by:"{{$hotel->created_by}}",
                        hotel_workspace:"{{$hotel->workspace}}",
                        date:$(this).val()
                    };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "get",
                        url: $(this).attr('data-url'),
                        data: data,
                        cache: false,
                        beforeSend: function() {
                            $('.booking-btn').attr('disabled');
                        },
                        success: function(data) {
                            $('.regular_price').html(data.regular_price);
                            $('.extra_service_price').html(data.extra_service_diplay_price);
                            $('.subtotal_price').html(data.subtotal_diplay_price);
                            $('.final_price').val(data.subtotal_price);
                            $('.extra_service').val(data.extra_service_price);
                        },
                        complete: function() {
                            $('.booking-btn').removeAttr('disabled');
                        },
                    });
            }
        });

    if({{$availableRoom}} <= 0){
        show_toastr('Error', 'Room currently not available.', 'error');
    }else{
        $(document).on('submit', '#room-details-form', function(e) {
            e.preventDefault();
            var data = {
                date: $('.date-ranger').val(),
                room_id: $('.room_id').val(),
                serviceCharge : $('.extra_service').val(),
                serviceIds : $('.serviceIds').val(),
                room:$('.total_room').val()
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route('addToCart',$slug) }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(response) {
                    $('.cart-count').html(response.cart_count)
                    $(".cart-popup").toggleClass("active");
                    $(".cart-popup").html(response.data);
                    $("body").toggleClass("no-scroll");
                }
            });
        });
    }
    </script>
@endpush
