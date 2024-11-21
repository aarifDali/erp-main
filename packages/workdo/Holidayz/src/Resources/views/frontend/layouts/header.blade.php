@php
//open after change

    $lang =  isset(\Auth::guard('holiday')->user()->lang) ? \Auth::guard('holiday')->user()->lang : 'en';

    $customPages = Workdo\Holidayz\Entities\PageOptions::where(['workspace' => $hotel->workspace])->where('enable_page_header',1)->get();
    $routes = ['hotel.home','search.rooms'];
    $logoPath = get_file('uploads/hotel_logo');
    $defaultlogoPath = get_file('uploads/logo');

    if(isset($getHotelThemeSetting1) && isset($getHotelThemeSetting1['enable_top_bar']) && isset($getHotelThemeSetting1['top_bar_title']) && isset($getHotelThemeSetting1['top_bar_number']) && isset($getHotelThemeSetting1['top_bar_whatsapp']) && isset($getHotelThemeSetting1['top_bar_instagram']) && isset($getHotelThemeSetting1['top_bar_twitter']) && isset($getHotelThemeSetting1['top_bar_messenger'])){
        $storethemesetting = $getHotelThemeSetting1;
    }else{
        $storethemesetting = Workdo\Holidayz\Entities\HotelThemeSettings::demoStoreThemeSetting($hotel->workspace, $hotel->theme_dir);
    }
@endphp


<header class="site-header header-style-one home-header border-bottom @if (!in_array(request()->route()->getName(), $routes)) myHeader @endif">
    @if( !empty($storethemesetting['enable_top_bar']) && $storethemesetting['enable_top_bar'] == 'on')
    <div class="header-top main-navigationbar">
        <div class="container-xl">
            <div class="header-top-info">
                <div class="header-top-left">
                    <p><i class="fas fa-bell"></i> {{ $storethemesetting['top_bar_title'] }}</p>
                </div>
                <div class="header-top-right">
                    <ul class="ac">
                        <li>
                            <a href="tel:{{ $storethemesetting['top_bar_number'] }}">
                                <i class="fas fa-phone-volume"></i> <b>{{ $storethemesetting['top_bar_number'] }}</b>{{__(' Request a call')}}</a>
                        </li>
                        <li>
                            <a href="{{ $storethemesetting['top_bar_whatsapp'] }}" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ $storethemesetting['top_bar_instagram'] }}" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ $storethemesetting['top_bar_twitter'] }}" target="_blank">
                                <i class="fab fa-twitter-square"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ $storethemesetting['top_bar_messenger'] }}" target="_blank">
                                <i class="far fa-envelope"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="main-navigationbar">
        <div class="container-xl">
            <div class="navigationbar-row d-flex align-items-center">
                <div class="logo-col">
                    <h1>
                        <a href="{{ route('hotel.home',$slug) }}">
                            <img src="{{ isset($hotel->logo) ?  $logoPath .'/'. $hotel->logo : $defaultlogoPath .'/'.'logo_dark.png' }}"  alt="logo" width="40%"/>
                        </a>
                    </h1>
                </div>
                <div class="menu-items-col">
                    <ul class="main-nav">
                        <li class="menu-lnk">
                            <a href="{{ route('hotel.home',$slug) }}">
                                {{ __('Home') }}
                            </a>

                        </li>
                        @if(count($customPages) > 0)
                            @foreach ($customPages as $page)
                                <li class="menu-lnk">
                                    <a href="{{ route('frontend.custom.page',[$slug,$page->slug]) }}">
                                        {{ $page->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <div class="menu-right-btns">
                        <ul class="d-flex align-items-center">
                            @if (Workdo\Holidayz\Entities\Utility::CustomerAuthCheck($hotel->slug) == true) {{-- remove \Auth::check() || --}}
                                <li class="menu-lnk has-item price-lang-btn">
                                    <a href="#">
                                        {{ __('Account') }}
                                        <svg class="account-svg" xmlns="http://www.w3.org/2000/svg" height="512" viewBox="0 0 32 32" width="512"><g id="user_account_people_man" data-name="user, account, people, man"><path d="m23.7373 16.1812a1 1 0 1 0 -1.4062 1.4218 8.9378 8.9378 0 0 1 2.6689 6.397c0 1.2231-3.5059 3-9 3s-9-1.7778-9-3.002a8.9385 8.9385 0 0 1 2.6348-6.3627 1 1 0 1 0 -1.4141-1.4141 10.9267 10.9267 0 0 0 -3.2207 7.7788c0 3.2476 5.667 5 11 5s11-1.7524 11-5a10.92 10.92 0 0 0 -3.2627-7.8188z"/><path d="m16 17a7 7 0 1 0 -7-7 7.0081 7.0081 0 0 0 7 7zm0-12a5 5 0 1 1 -5 5 5.0059 5.0059 0 0 1 5-5z"/></g></svg>
                                    </a>
                                    <div class="menu-dropdown" style="min-width: 120px;">
                                        <ul>
                                            <li>
                                                <a href="{{ route('customer.profile',$slug) }}" class="dropdown-item">
                                                    <span>{{ __('My Profile') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('customer.booking',$slug) }}" class="dropdown-item ">
                                                    <span>{{ __('My Booking') }}</span>
                                                </a>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('customer.logout', $slug) }}" id="customer_logout">
                                                    <a href="{{ route('customer.logout', $slug) }}" onclick="event.preventDefault(); this.closest('form').submit();"
                                                        class="dropdown-item">
                                                        <i class="ti ti-power"></i>
                                                        @csrf
                                                        {{ __('Log Out') }}
                                                    </a>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('customer.login.page', [$slug,$lang]) }}" class="call-btn">
                                        {{ __('Sign in') }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="#" class="cart-btn cart-header1 d-flex align-items-center cart-drawer">
                                    <span class="desk-only">{{ __('Cart') }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17"
                                        viewBox="0 0 16 17" fill="none">
                                        <path
                                            d="M6.70612 15.6249C6.70612 16.1712 6.26278 16.6145 5.71653 16.6145C5.17107 16.6145 4.72299 16.1712 4.72299 15.6249C4.72299 15.0787 5.16236 14.6354 5.70861 14.6354H5.71653C6.26278 14.6354 6.70612 15.0787 6.70612 15.6249ZM12.0499 14.6354H12.0419C11.4957 14.6354 11.0563 15.0787 11.0563 15.6249C11.0563 16.1712 11.5036 16.6145 12.0499 16.6145C12.5961 16.6145 13.0394 16.1712 13.0394 15.6249C13.0394 15.0787 12.5961 14.6354 12.0499 14.6354ZM15.7588 5.72277L14.9561 10.6042C14.7479 11.7489 14.2301 13.052 12.034 13.052H5.49011C4.41423 13.052 3.48715 12.2485 3.33515 11.1829L2.13973 2.81894C2.07006 2.33444 1.64974 1.96948 1.16049 1.96948H0.950684C0.622934 1.96948 0.356934 1.70348 0.356934 1.37573C0.356934 1.04798 0.622934 0.781982 0.950684 0.781982H1.16126C2.23713 0.781982 3.16421 1.58552 3.31621 2.6511L3.38898 3.15698H13.6174C14.2649 3.15698 14.8746 3.44198 15.2894 3.93914C15.7034 4.43552 15.8751 5.08627 15.7588 5.72277ZM13.786 10.4023L13.8706 9.88535H8.86735C8.5396 9.88535 8.2736 9.61935 8.2736 9.2916C8.2736 8.96385 8.5396 8.69785 8.86735 8.69785H14.0662L14.5888 5.5201C14.6434 5.22085 14.5658 4.92477 14.3765 4.69915C14.1873 4.47352 13.9111 4.34368 13.6166 4.34368H3.55762L4.51077 11.0151C4.58043 11.4996 5.00086 11.8645 5.49011 11.8645H12.034C13.2983 11.8645 13.6063 11.3927 13.786 10.4023Z"
                                            fill="white" />
                                    </svg>
                                    <span class="count cart-count">{!! Workdo\Holidayz\Entities\RoomBookingCart::CartCount($hotel) !!}</span>
                                </a>
                            </li>

                            <li class="menu-lnk has-item price-lang-btn custom-menu-lnk">
                                <a href="#">
                                    @php

                                        if(session()->get('lang')){
                                            $lang =  session()->get('lang');
                                        }

                                        if(auth()->guard('holiday')->user()){
                                            $lang =  isset(Auth::guard('holiday')->user()->lang) ? Auth::guard('holiday')->user()->lang : 'en';
                                            // $lang =  'en';
                                        }
                                    @endphp
                                    {{ Str::upper($lang) }}
                                                <svg class="account-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="512" height="512"><g id="Layer_2" data-name="Layer 2"><path d="M87.95636,73.23224a44.29242,44.29242,0,0,0,6.54358-23.23145L94.5,50l-.00006-.00079a44.2927,44.2927,0,0,0-6.54376-23.23169l-.02442-.03815a44.5022,44.5022,0,0,0-75.8634-.00031l-.02472.03864a44.51347,44.51347,0,0,0-.00018,46.46436l.02514.03918a44.50213,44.50213,0,0,0,75.86292-.00037Zm-32.26825,13.641a10.81448,10.81448,0,0,1-2.8894,1.99561,6.52134,6.52134,0,0,1-5.59748,0,13.62135,13.62135,0,0,1-5.04809-4.44233,39.77474,39.77474,0,0,1-5.74762-12.47064Q43.19588,71.538,50,71.53021q6.80127,0,13.59521.42572a50.19826,50.19826,0,0,1-2.438,6.71222A25.80323,25.80323,0,0,1,55.68811,86.87329ZM10.587,52.5H28.536a88.30459,88.30459,0,0,0,1.62274,14.91418q-7.35983.64766-14.68207,1.779A39.23059,39.23059,0,0,1,10.587,52.5Zm4.88964-21.69324Q22.796,31.941,30.16388,32.58618A88.15014,88.15014,0,0,0,28.5376,47.5H10.587A39.2306,39.2306,0,0,1,15.47662,30.80676ZM44.31183,13.12665a10.81146,10.81146,0,0,1,2.8894-1.99561,6.52134,6.52134,0,0,1,5.59748,0,13.62131,13.62131,0,0,1,5.04809,4.44232A39.77482,39.77482,0,0,1,63.59436,28.044Q56.804,28.46185,50,28.46973q-6.80127-.00009-13.59528-.42578a50.18985,50.18985,0,0,1,2.43805-6.71216A25.80254,25.80254,0,0,1,44.31183,13.12665ZM89.413,47.5H71.464a88.31173,88.31173,0,0,0-1.62274-14.91425q7.35992-.64764,14.68207-1.779A39.2306,39.2306,0,0,1,89.413,47.5ZM35.18756,67.02545A82.69645,82.69645,0,0,1,33.53729,52.5H66.4632a82.67828,82.67828,0,0,1-1.64728,14.52563Q57.41607,66.54,50,66.53027,42.58927,66.53018,35.18756,67.02545Zm29.62482-34.051A82.70224,82.70224,0,0,1,66.46259,47.5H33.53674A82.67914,82.67914,0,0,1,35.184,32.97424q7.39985.4855,14.816.49543Q57.41074,33.46967,64.81238,32.97449ZM71.46228,52.5H89.413a39.23052,39.23052,0,0,1-4.88971,16.69318q-7.31936-1.13435-14.68719-1.77942A88.14559,88.14559,0,0,0,71.46228,52.5ZM81.52539,26.20477q-6.39945.92331-12.83734,1.462a57.01792,57.01792,0,0,0-2.9754-8.39581,35.48007,35.48007,0,0,0-4.13984-7.04529A39.49152,39.49152,0,0,1,81.52539,26.20477ZM22.06915,22.06915a39.48682,39.48682,0,0,1,16.3559-9.84289c-.09369.12134-.19006.2373-.28241.36114A45.64338,45.64338,0,0,0,31.321,27.66754q-6.43816-.54528-12.84643-1.46277A39.82535,39.82535,0,0,1,22.06915,22.06915Zm-3.5946,51.726q6.39943-.9234,12.83728-1.462A57.01789,57.01789,0,0,0,34.28729,80.729a35.48425,35.48425,0,0,0,4.13983,7.04529A39.49154,39.49154,0,0,1,18.47455,73.79517Zm59.45624,4.13562a39.48587,39.48587,0,0,1-16.3559,9.84289c.09369-.12134.19-.2373.28241-.36114A45.64338,45.64338,0,0,0,68.679,72.3324q6.43816.54528,12.84643,1.46277A39.82535,39.82535,0,0,1,77.93079,77.93079Z"/></g></svg>

                                </a>
                                <div class="menu-dropdown">
                                    <ul>

                                        @foreach (languages() as $key => $language)
                                        <li>
                                            <a href="{{ route('change.lang', [$slug,$key]) }}"
                                                class="dropdown-item " style="@if ($key == $lang)color:#ff3333;@endif">
                                                <span>{{ Str::ucfirst($language) }}</span>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="mobile-menu mobile-only">
                        <button class="mobile-menu-button">
                            <div class="one"></div>
                            <div class="two"></div>
                            <div class="three"></div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

  <!--cart popup start here-->
  <div class="overlay cart-overlay"></div>
  <div class="cartDrawer"></div>
  <!--cart popup end here-->

