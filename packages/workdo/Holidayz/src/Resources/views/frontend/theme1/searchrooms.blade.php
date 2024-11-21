@extends('holidayz::frontend.layouts.theme1')
@section('page-title')
    {{__('Rooms')}}
@endsection
@php
    $path1 = get_file('uploads');
    $path = get_file('packages/workdo/Holidayz/src/Resources/assets/');
    $path2 = get_file('/'); //remove after changes 
    $companySettings = json_encode(getCompanyAllSetting($hotel->created_by,$hotel->workspace));
@endphp
@section('content')
    <div class="pdp-wrapper">
        {{-- home header section --}}
        @foreach ($getHotelThemeSetting as $key => $section)
            @if ($section['section_name'] == 'Header' && $section['section_enable'] == 'on')
                @if(file_exists(base_path().'/'.'uploads/'.$section['inner-list'][2]['field_default_text']))
                    <section class="common-banner-section"
                        style="background-image:url({{ $path1 . '/' . $section['inner-list'][2]['field_default_text'] }});">
                @else
                    <section class="common-banner-section"
                        style="background-image:url({{ $path . '/' . $section['inner-list'][2]['field_default_text'] }});">
                @endif
                    <div class="container">
                        <div class="common-banner-content text-center">
                            <div class="section-title">
                                <h2>{{ __('Rooms & Suites') }}</h2>
                            </div>
                            <p>{{ __('We have found some rooms that your needs.') }}</p>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
        <section class="product-listing-section">
            <div class="container">
                <div class="product-list-row row">
                    <div class="product-filter-left-column col-md-8 col-12">
                        <form class="filter-form" action="{{ route('search.rooms', $slug) }}" method="post">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date }}"/>
                            <input type="hidden" name="rooms" value="{{ $Totalrooms }}"/>
                            <input type="hidden" name="adult" value="{{ $Totaladult }}"/>
                            <input type="hidden" name="children" value="{{ $Totalchildren }}"/>
                            <div class="product-sorting-row d-flex align-items-center">
                                <div class="filter-title">
                                    <h4>{{ __('Filters') }}:</h4>
                                    <div class="filter-ic">
                                        <svg class="icon icon-filter" aria-hidden="true" focusable="false"
                                            role="presentation" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="none">
                                            <path fill-rule="evenodd"
                                                d="M4.833 6.5a1.667 1.667 0 1 1 3.334 0 1.667 1.667 0 0 1-3.334 0ZM4.05 7H2.5a.5.5 0 0 1 0-1h1.55a2.5 2.5 0 0 1 4.9 0h8.55a.5.5 0 0 1 0 1H8.95a2.5 2.5 0 0 1-4.9 0Zm11.117 6.5a1.667 1.667 0 1 0-3.334 0 1.667 1.667 0 0 0 3.334 0ZM13.5 11a2.5 2.5 0 0 1 2.45 2h1.55a.5.5 0 0 1 0 1h-1.55a2.5 2.5 0 0 1-4.9 0H2.5a.5.5 0 0 1 0-1h8.55a2.5 2.5 0 0 1 2.45-2Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="filter-select-box">
                                    <span class="sort-lbl">{{ __('Sort by') }}:</span>
                                    <select name="price" id="price"
                                        onchange="event.preventDefault(); this.closest('form').submit();">
                                        <option value="" selected="selected">{{__('PRICE')}}</option>
                                        @for($i = $HotelroomsAllData->min('final_price'); $i <= $HotelroomsAllData->max('final_price') + 20 ; $i+=100)
                                            <option value="{{ $i }}" @if ($price == $i) {{ 'selected' }} @endif>{{ currency_format_with_sym($i,$hotel->created_by,$hotel->workspace) }}</option>

                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </form>
                        <div class="product-filter-card-row row">
                            @if ($rooms->count() > 0)
                                @foreach ($rooms as $item)
                                    @php
                                        $total_booking_rooms = $item->getRoomTotal()[0]->total_booking_rooms;
                                    @endphp
                                    @if ($item->total_room > $total_booking_rooms)
                                        <div class="col-sm-6 col-12 product-card">
                                            <div class="room-item-inner product-card-inner">
                                                <div class="product-image-slider">
                                                    @php
                                                        $availableRoom = $item->total_room - $total_booking_rooms;
                                                    @endphp
                                                    @if ($item->image)
                                                        <div class="product-image">
                                                            <a href="{{ route('room.details', [$slug, $item->id]) }}"
                                                                tabindex="-1">
                                                                <img src="{{ $path1 . '/rooms/' . $item->image }}">
                                                            </a>
                                                            <div class="product-lbl">{{ __('Hurry!') }} {{ $availableRoom }}
                                                                {{ __('rooms left') }}</div>
                                                        </div>
                                                    @endif
                                                    
                                                    @foreach ($item->getImages as $key => $image)
                                                        <div class="product-image">
                                                            <a href="{{ route('room.details', [$slug, $item->id]) }}"
                                                                tabindex="-1">
                                                                <img src="{{ $path2 . $image->name }}">
                                                            </a>
                                                            <div class="product-lbl">{{ __('Hurry!') }} {{ $availableRoom }}
                                                                {{ __('rooms left') }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="product-content">
                                                    <div class="product-top-content">
                                                        <h3>
                                                            <a href="{{ route('room.details', [$slug, $item->id]) }}"
                                                                tabindex="-1">
                                                                {{ $item->room_type }}
                                                            </a>
                                                        </h3>
                                                        <ul class="d-flex icon-wrapper">

                                                            @foreach ($item->features as $feature)
                                                                <li class="d-flex">
                                                                    <i class="{{ $feature->icon }}"></i>
                                                                    <span>{{ $feature->name }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <p>
                                                            {!! $item->short_description !!}
                                                        </p>

                                                    </div>
                                                    <div class="product-bottom-content">
                                                        <div class="price">
                                                            {{ currency_format_with_sym($item->final_price,$hotel->created_by,$hotel->workspace) }}
                                                            <span>/ {{ __('per night') }}</span>
                                                            <del>
                                                                {{ currency_format_with_sym($item->base_price,$hotel->created_by,$hotel->workspace) }}
                                                            </del>
                                                        </div>
                                                        <div class="person-info">
                                                            {{ $item->adults + $item->children }} {{ __('Max Guests') }}:
                                                            {{ $item->adults }} {{ __('Adults') }}, {{ $item->children }}
                                                            {{ __('Children') }}
                                                        </div>
                                                        <div class="btn-select-wrp">
                                                            <a href="#"
                                                                class="btn-secondary btn  d-flex align-items-center addcart-btn"
                                                                room_id="{{ $item->id }}" date="{{ $date }}" total_room="{{ $Totalrooms }}">
                                                                <span>{{ __('Book Now') }}</span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="19"
                                                                    height="19" viewBox="0 0 19 19" fill="none">
                                                                    <path
                                                                        d="M9.0459 1.06299C4.5999 1.06299 0.983398 4.67949 0.983398 9.12549C0.983398 13.5715 4.5999 17.188 9.0459 17.188C13.4919 17.188 17.1084 13.5715 17.1084 9.12549C17.1084 4.67949 13.4919 1.06299 9.0459 1.06299ZM9.0459 16.063C5.22015 16.063 2.1084 12.9512 2.1084 9.12549C2.1084 5.29974 5.22015 2.18799 9.0459 2.18799C12.8716 2.18799 15.9834 5.29974 15.9834 9.12549C15.9834 12.9512 12.8716 16.063 9.0459 16.063ZM12.5648 9.34082C12.5363 9.40982 12.4952 9.47199 12.4434 9.52374L10.1934 11.7737C10.0839 11.8832 9.9399 11.9387 9.7959 11.9387C9.6519 11.9387 9.50788 11.884 9.39838 11.7737C9.17863 11.554 9.17863 11.1977 9.39838 10.978L10.6884 9.68799H6.0459C5.7354 9.68799 5.4834 9.43599 5.4834 9.12549C5.4834 8.81499 5.7354 8.56299 6.0459 8.56299H10.6876L9.39764 7.27301C9.17789 7.05326 9.17789 6.69698 9.39764 6.47723C9.61739 6.25748 9.97367 6.25748 10.1934 6.47723L12.4434 8.72723C12.4952 8.77898 12.5363 8.84116 12.5648 8.91016C12.6218 9.04816 12.6218 9.20282 12.5648 9.34082Z"
                                                                        fill="white" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <h1>{{ __('No Room Availble') }}</h1>
                            @endif
                        </div>
                    </div>
                    <div class="product-filter-right-column col-md-4 col-12">
                        <div class=" close-filter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50"
                                fill="none">
                                <path
                                    d="M27.7618 25.0008L49.4275 3.33503C50.1903 2.57224 50.1903 1.33552 49.4275 0.572826C48.6647 -0.189868 47.428 -0.189965 46.6653 0.572826L24.9995 22.2386L3.33381 0.572826C2.57102 -0.189965 1.3343 -0.189965 0.571605 0.572826C-0.191089 1.33562 -0.191186 2.57233 0.571605 3.33503L22.2373 25.0007L0.571605 46.6665C-0.191186 47.4293 -0.191186 48.666 0.571605 49.4287C0.952952 49.81 1.45285 50.0007 1.95275 50.0007C2.45266 50.0007 2.95246 49.81 3.3339 49.4287L24.9995 27.763L46.6652 49.4287C47.0465 49.81 47.5464 50.0007 48.0463 50.0007C48.5462 50.0007 49.046 49.81 49.4275 49.4287C50.1903 48.6659 50.1903 47.4292 49.4275 46.6665L27.7618 25.0008Z"
                                    fill="white"></path>
                            </svg>
                        </div>
                        <div class="product-filter-form">
                            <form class="location-form" action="{{ route('search.rooms', $slug) }}" method="post">
                                @csrf
                                <h4 class="filter-title">{{__('Search your room')}}</h4>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('CHECK IN - CHECK OUT') }}</label>
                                            {{ Form::date('date', $date, ['class' => 'form-control month-btn date-ranger', 'id' => 'pc-daterangepicker-2', 'required' => true,'data-min-date'=> 'today']) }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('ROOMS') }}</label>
                                            <select name="rooms" id="rooms" class="form-control">
                                                @for ($i = 0; $i < 10; $i++)
                                                    <option value={{ $i }}
                                                        @if ($Totalrooms == $i) {{ 'selected' }} @endif>
                                                        {{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('ADULT') }}</label>
                                            <select name="adult" id="adult" class="form-control">
                                                @for ($i = 0; $i < 10; $i++)
                                                    <option value={{ $i }}
                                                        @if ($Totaladult == $i) {{ 'selected' }} @endif>
                                                        {{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('CHILDREN') }}</label>
                                            <select name="children" id="children" class="form-control">
                                                @for ($i = 0; $i < 10; $i++)
                                                    <option value={{ $i }}
                                                        @if ($Totalchildren == $i) {{ 'selected' }} @endif>
                                                        {{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn-secondary " type="submit">
                                            {{ __('Search Rooms') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <form action="{{ route('search.rooms', $slug) }}" method="post">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date }}"/>
                            <input type="hidden" name="rooms" value="{{ $Totalrooms }}"/>
                            <input type="hidden" name="adult" value="{{ $Totaladult }}"/>
                            <input type="hidden" name="children" value="{{ $Totalchildren }}"/>
                            <div class="product-filter-body">
                                <div class="product-filter-body-inner">
                                    <h4 class="filter-title">{{__('Filters')}}</h4>
                                    @if (isset($room_type) && $room_type->count() > 0)
                                        <div class="product-widget product-room-widget">
                                            <div class="pro-itm">
                                                {{ __('ROOM TYPE') }}
                                            </div>
                                            <div class="pro-itm-inner">
                                                @foreach ($room_type as $item)
                                                    <div class="room-checkbox">
                                                        <label class="check-label" for="available1">
                                                            <span class="custom-checkbox">
                                                                <input id="available1" type="checkbox" name="roomtype[]"
                                                                    value="{{ $item->id }}">
                                                                <span class="room"></span>
                                                            </span>
                                                            <div class="room-name">
                                                            </div>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                    @endif
                                    @if ($features->count() > 0)
                                        <div class="product-widget product-room-widget">
                                            <div class="pro-itm">
                                                {{ __('Services') }}
                                            </div>
                                            <div class="pro-itm-inner">
                                                @foreach ($features as $item)
                                                    <div class="room-checkbox">
                                                        <label class="check-label" for="available10">
                                                            <span class="custom-checkbox">
                                                                <input id="available10" type="checkbox" name="features[]"
                                                                    value="{{ $item->id }}">
                                                                <span class="room"></span>
                                                            </span>
                                                            <div class="room-name">
                                                                {{ $item->name }}
                                                            </div>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="product-widget product-price-widget">
                                        <div class="pro-itm">
                                            {{ __('Price') }}
                                        </div>
                                        <div class="prize-select">
                                            <p class="min_value_label">{{ __('$0.00') }}</p>
                                            <input type="hidden" name="min_price" class="min_price">
                                            <p class="max_value_label">{{ __('$1,900.00') }}</p>
                                            <input type="hidden" name="max_price" class="max_price">
                                        </div>
                                        <div id="range-slider">
                                            <div id="slider-range"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-widget product-fil-btn">
                                    <button class="btn-secondary filter-btn">
                                        {{ __('FILTER') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection
@push('script')
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/css/jquery-ui.css') }}">
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/jquery-ui.js') }}"></script>
    <script>
        // Range slider - gravity forms
        $(function() {
            $("#slider-range").slider({
                range: true,
                min: 0,
                max: {{ $HotelroomsAllData->max('final_price') }},
                step: 10,
                values: [{{ $minPrice ? $minPrice : 0 }},
                    {{ $maxPrice ? $maxPrice : $HotelroomsAllData->max('final_price') }}
                ],
                slide: function(event, ui) {
                    $(".min_value_label").html(formatCurrency(ui.values[0] , '{{ $companySettings }}'));
                    $(".max_value_label").html(formatCurrency(ui.values[1] , '{{ $companySettings }}'));
                    $(".min_price").val(formatCurrency(ui.values[0] , '{{ $companySettings }}'));
                    $(".max_price").val(formatCurrency(ui.values[1] , '{{ $companySettings }}'));
                }
            });
            $(".min_value_label").html($("#slider-range").slider("values", 0));
            $(".max_value_label").html($("#slider-range").slider("values", 1));
            $(".min_price").val($("#slider-range").slider("values", 0));
            $(".max_price").val($("#slider-range").slider("values", 1));
        });
    </script>
@endpush
