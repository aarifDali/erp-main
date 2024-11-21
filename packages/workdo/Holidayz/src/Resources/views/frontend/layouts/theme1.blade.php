@php
    use Workdo\Holidayz\Entities\Hotels;

    $hotel = Hotels::where('slug', $slug)->first();
    $logo = get_file('uploads/logo');

    $company_logo = dark_logo('company_logo_dark');
    $company_logos = light_logo('company_logo_light');

    if(!empty(\Auth::user())){
        $SITE_RTL = company_setting('SITE_RTL');
    }else{
        $SITE_RTL = 'off';
    }

    $metatitle = isset($getseo['meta_title']) ? $getseo['meta_title'] : '';
    $metsdesc = isset($getseo['meta_desc']) ? $getseo['meta_desc'] : '';
    $meta_image = get_file('uploads/meta/');
    $meta_logo = isset($getseo['meta_image']) ? $getseo['meta_image'] : '';

    $path = get_file('uploads');

    $hotel = Hotels::where('slug', $slug)->where('is_active', 1)->first();

    if(!isset($getHotelThemeSetting)){
        if ($hotel) {
            $getHotelThemeSetting = \Workdo\Holidayz\Entities\Utility::getHotelThemeSetting($hotel->workspace, $hotel->theme_dir);
            $getHotelThemeSetting1 = [];
            if (!empty($getHotelThemeSetting['dashboard'])) {
                $getHotelThemeSetting1 = $getHotelThemeSetting;
                $getHotelThemeSetting = json_decode($getHotelThemeSetting['dashboard'], true);
            }
            if (empty($getHotelThemeSetting)) {
                $path = asset('packages/workdo/Holidayz/src/Resources/assets/'. $hotel->theme_dir . "/" . $hotel->theme_dir . ".json" );
                $getHotelThemeSetting = json_decode(file_get_contents($path), true);
            }
        }
    }

    $customPages = Workdo\Holidayz\Entities\PageOptions::where(['workspace' => $hotel->workspace])->get();

    $favicon = isset($company_settings['favicon']) ? $company_settings['favicon'] : (isset($admin_settings['favicon']) ? $admin_settings['favicon'] : 'uploads/logo/favicon.png');
@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>@yield('page-title') | {{ config('APP_NAME', ucfirst($hotel->name)) }}</title>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ $metatitle }}">
    <meta property="og:description" content="{{ $metsdesc }}">
    <meta property="og:image" content="{{ $meta_image . $meta_logo }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ $metatitle }}">
    <meta property="twitter:description" content="{{ $metsdesc }}">
    <meta property="twitter:image" content="{{ $meta_image . $meta_logo }}">


    <meta name="author" content="{{ env('APP_NAME') }}">
    <meta name="description" content="{{ $metsdesc }}">
    <meta name="keywords" content="{{ $metatitle }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="icon" href="{{ check_file($favicon) ? get_file($favicon) : get_file('uploads/logo/favicon.png') }}{{ '?' . time() }}" type="image/png">

    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&family=Outfit:wght@100;200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href='{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/css/main-style.css') }}'>
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/css/responsive.css') }}">



    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/css/flatpickr.min.css') }}">


    <meta name="base-url" content="{{ URL::to('/') }}">

    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/css/notifier.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/css/custom.css') }}">


    @stack('css')
    @stack('style')
</head>

<body class="color-v1">
    <svg style="display: none;">
        <symbol viewBox="0 0 6 5" id="slickarrow">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M5.89017 2.75254C6.03661 2.61307 6.03661 2.38693 5.89017 2.24746L3.64017 0.104605C3.49372 -0.0348681 3.25628 -0.0348681 3.10984 0.104605C2.96339 0.244078 2.96339 0.470208 3.10984 0.609681L5.09467 2.5L3.10984 4.39032C2.96339 4.52979 2.96339 4.75592 3.10984 4.8954C3.25628 5.03487 3.49372 5.03487 3.64016 4.8954L5.89017 2.75254ZM0.640165 4.8954L2.89017 2.75254C3.03661 2.61307 3.03661 2.38693 2.89017 2.24746L0.640165 0.104605C0.493719 -0.0348682 0.256282 -0.0348682 0.109835 0.104605C-0.0366115 0.244078 -0.0366115 0.470208 0.109835 0.609681L2.09467 2.5L0.109835 4.39032C-0.0366117 4.52979 -0.0366117 4.75592 0.109835 4.8954C0.256282 5.03487 0.493719 5.03487 0.640165 4.8954Z">
            </path>
        </symbol>
    </svg>
    <!--header start here-->
    @include('holidayz::frontend.layouts.header')
    <!--header end here-->
    <!--wrapper start here-->
    <div class="home-wrapper">
        @yield('content')
    </div>
    <!---wrapper end here-->
    <!--footer start here-->
    @if ($getHotelThemeSetting[10]['section_enable'] == 'on' || $getHotelThemeSetting[18]['section_enable'] == 'on')
        <footer class="site-footer">
            <div class="container">
                @if ($getHotelThemeSetting[10]['section_enable'] == 'on')
                <div class="footer-row">
                    @foreach ($getHotelThemeSetting as $key => $storethemesetting)
                        @foreach ($storethemesetting['inner-list'] as $keyy => $theme)
                            @if ($theme['field_slug'] == 'homepage-subscribe-enable' && $theme['field_default_text'] == 'on')
                                <div class="footer-col footer-subscribe-col footer-link-4">
                                    <div class="footer-widget">
                                        <div class="footer-subscribe">
                                            <h4>{{ __('Get Subscription') }}</h4>
                                        </div>
                                        <p>{{ __('Sign up to our newsletter to be the first to hear about great offers, new opening and events.') }}
                                        </p>
                                        <div class="footer-subscribe-form">
                                            <form action="{{ route('subscribe',$slug) }}" method="post">
                                                @csrf
                                                <div class="input-wrapper">
                                                    <input type="email" name="email" placeholder="Type Your Email..." required>
                                                    <button class="btn-subscibe"
                                                        type="submit">{{ __('Subscribe') }}</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (
                                $getHotelThemeSetting[11]['inner-list'][0]['field_default_text'] == 'on' &&
                                    $theme['field_slug'] == 'homepage-footer-header-quick-link-name-1')
                                <div class="footer-col footer-link footer-link-1">
                                    <div class="footer-widget">
                                        @foreach ($getHotelThemeSetting as $key => $storethemesetting)
                                            @foreach ($storethemesetting['inner-list'] as $keyy => $theme)
                                                @if ($theme['field_name'] == 'Enable Quick Link 1')
                                                    @foreach ($storethemesetting['inner-list'] as $kk => $title)
                                                        @if ($kk == 1)
                                                            <h4>
                                                                {{ __($title['field_default_text']) }}
                                                            </h4>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @if (
                                                    !empty($theme['field_slug'] == 'homepage-header-quick-link-name-1') &&
                                                        !empty($storethemesetting['homepage-header-quick-link-name-1']))
                                                    @foreach ($storethemesetting['homepage-header-quick-link-name-1'] as $keys => $th)
                                                        @foreach ($storethemesetting['homepage-header-quick-link-1'] as $link_key => $storethemesettinglink)
                                                            @if ($keys == $link_key)
                                                                <ul>
                                                                    <li><a href="{{ $storethemesettinglink }}"
                                                                            target="_blank">{{ $th }}</a>
                                                                    </li>
                                                                </ul>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @if ($theme['field_slug'] == 'homepage-header-quick-link-name-1')
                                                        @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                                            @foreach ($storethemesetting['inner-list'] as $kk => $title)
                                                                @if ($kk == 0)
                                                                    <ul>
                                                                        <li><a href="{{ $kk == 1 ? $title['field_default_text'] : '' }}"
                                                                                target="_blank">{{ __($title['field_default_text']) }}</a>
                                                                        </li>
                                                                    </ul>
                                                                @endif
                                                            @endforeach
                                                        @endfor
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif


                            @if (
                                $getHotelThemeSetting[13]['inner-list'][0]['field_default_text'] == 'on' &&
                                    $theme['field_slug'] == 'homepage-footer-header-quick-link-name-2')
                                <div class="footer-col footer-link-2">
                                    <div class="footer-widget">
                                        @foreach ($getHotelThemeSetting as $key => $storethemesetting)
                                            @foreach ($storethemesetting['inner-list'] as $keyy => $theme)
                                                @if (
                                                    !empty($theme['field_slug'] == 'homepage-header-quick-link-name-2') &&
                                                        !empty($storethemesetting['homepage-header-quick-link-name-2']))
                                                    @foreach ($storethemesetting['homepage-header-quick-link-name-2'] as $keys => $th)
                                                        @foreach ($storethemesetting['homepage-header-quick-link-2'] as $link_key => $storethemesettinglink)
                                                            @if ($keys == $link_key)
                                                                <ul>
                                                                    <li><a href="{{ $storethemesettinglink }}"
                                                                            target="_blank">{{ $th }}</a>
                                                                    </li>
                                                                </ul>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @if ($theme['field_slug'] == 'homepage-header-quick-link-name-2')
                                                        @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                                            @foreach ($storethemesetting['inner-list'] as $kk => $title)
                                                                @if ($kk == 0)
                                                                    <ul>
                                                                        <li>
                                                                            <a href="{{ $kk == 1 ? $title['field_default_text'] : '' }}"
                                                                                target="_blank">{{ __($title['field_default_text']) }}</a>
                                                                        </li>
                                                                    </ul>
                                                                @endif
                                                            @endforeach
                                                        @endfor
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($getHotelThemeSetting[15]['inner-list'][1]['field_default_text'] == 'on' &&  $theme['field_slug'] == 'homepage-footer-header-quick-link-name-3')
                                <div class="footer-col footer-link-2">
                                    <div class="footer-widget">
                                        @foreach ($getHotelThemeSetting as $key => $storethemesetting)
                                            @foreach ($storethemesetting['inner-list'] as $keyy => $theme)
                                                @if (
                                                    !empty($theme['field_slug'] == 'homepage-header-quick-link-name-3') &&
                                                        !empty($storethemesetting['homepage-header-quick-link-name-3']))
                                                    @foreach ($storethemesetting['homepage-header-quick-link-name-3'] as $keys => $th)
                                                        @foreach ($storethemesetting['homepage-header-quick-link-3'] as $link_key => $storethemesettinglink)
                                                            @if ($keys == $link_key)
                                                                <ul>
                                                                    <li><a href="{{ $storethemesettinglink }}"
                                                                            target="_blank">{{ $th }}</a>
                                                                    </li>
                                                                </ul>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @if ($theme['field_slug'] == 'homepage-header-quick-link-name-3')
                                                        @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                                            @foreach ($storethemesetting['inner-list'] as $kk => $title)
                                                                @if ($kk == 0)
                                                                    <ul>
                                                                        <li><a href="{{ $kk == 1 ? $title['field_default_text'] : '' }}"
                                                                                target="_blank">{{ __($title['field_default_text']) }}</a>
                                                                        </li>
                                                                    </ul>
                                                                @endif
                                                            @endforeach
                                                        @endfor
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if ($theme['field_slug'] == 'homepage-payment-enable' && $theme['field_default_text'] == 'on')
                                <div class="footer-col footer-link footer-link-3">
                                    <div class="footer-widget">
                                        <h4> Payment Accepted </h4>
                                        <div class="d-flex footer-list-social">
                                            <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/images/p1.png') }}">
                                            <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/images/p2.png') }}">
                                            <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/images/p3.png') }}">
                                            <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/images/p4.png') }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
                @endif

                {{-- if any issue so uncomment above code and comment below code and custom.css code remove .social-link  --}}
                @if ($getHotelThemeSetting[18]['section_enable'] == 'on')
                    <div class="footer-bottom">
                        <div class="row align-items-center">
                            @if ($getHotelThemeSetting[18]['section_enable'] == 'on')
                                <div class="col-md-6">
                                    <p>{{ $getHotelThemeSetting[18]['inner-list'][0]['field_default_text'] }}</p>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <ul class="social-link">
                                    <li>
                                        <p>{{ __('Follow us on :') }}</p>
                                    </li>
                                    @if (isset($getHotelThemeSetting[19]['homepage-footer-2-social-icon']) ||
                                            isset($getHotelThemeSetting[19]['homepage-footer-2-social-link']))
                                        @if (isset($getHotelThemeSetting[19]['inner-list'][1]['field_default_text']) &&
                                                isset($getHotelThemeSetting[19]['inner-list'][0]['field_default_text']))
                                            @foreach ($getHotelThemeSetting[19]['homepage-footer-2-social-icon'] as $icon_key => $hotelthemesettingicon)
                                                @foreach ($getHotelThemeSetting[19]['homepage-footer-2-social-link'] as $link_key => $hotelthemesettinglink)
                                                    @if ($icon_key == $link_key)
                                                        <li>
                                                            <a target="_blank" href="{{ $hotelthemesettinglink }}">
                                                                {!! $hotelthemesettingicon !!}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                    @else
                                        @for ($i = 0; $i < $getHotelThemeSetting[19]['loop_number']; $i++)
                                            @if (isset($getHotelThemeSetting[19]['inner-list'][1]['field_default_text']) &&
                                                    isset($getHotelThemeSetting[19]['inner-list'][0]['field_default_text']))
                                                <li>
                                                    <a target="_blank"
                                                        href="{{ $getHotelThemeSetting[19]['inner-list'][1]['field_default_text'] }}">
                                                        {!! $getHotelThemeSetting[19]['inner-list'][0]['field_default_text'] !!}
                                                    </a>
                                                </li>
                                            @endif
                                        @endfor
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </footer>
    @endif
    <!--footer end here-->
    <!--video popup start-->
    <div id="popup-box" class="overlay-popup">
        <div class="popup-inner">
            <div class="content">
                <a class=" close-popup" href="javascript:void(0)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="34" viewBox="0 0 35 34"
                        fill="none">
                        <line x1="2.29695" y1="1.29289" x2="34.1168" y2="33.1127" stroke="white"
                            stroke-width="2">
                        </line>
                        <line x1="0.882737" y1="33.1122" x2="32.7025" y2="1.29242" stroke="white"
                            stroke-width="2">
                        </line>
                    </svg>
                </a>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/9xwazD5SyVg"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen="">
                </iframe>
            </div>
        </div>
    </div>
    <!--video popup end -->
    <div class="overlay"></div>
    <!-- Mobile menu start here -->
    <div class="mobile-menu-wrapper">
        <div class="menu-close-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 20 18">
                <path fill="#24272a"
                    d="M19.95 16.75l-.05-.4-1.2-1-5.2-4.2c-.1-.05-.3-.2-.6-.5l-.7-.55c-.15-.1-.5-.45-1-1.1l-.1-.1c.2-.15.4-.35.6-.55l1.95-1.85 1.1-1c1-1 1.7-1.65 2.1-1.9l.5-.35c.4-.25.65-.45.75-.45.2-.15.45-.35.65-.6s.3-.5.3-.7l-.3-.65c-.55.2-1.2.65-2.05 1.35-.85.75-1.65 1.55-2.5 2.5-.8.9-1.6 1.65-2.4 2.3-.8.65-1.4.95-1.9 1-.15 0-1.5-1.05-4.1-3.2C3.1 2.6 1.45 1.2.7.55L.45.1c-.1.05-.2.15-.3.3C.05.55 0 .7 0 .85l.05.35.05.4 1.2 1 5.2 4.15c.1.05.3.2.6.5l.7.6c.15.1.5.45 1 1.1l.1.1c-.2.15-.4.35-.6.55l-1.95 1.85-1.1 1c-1 1-1.7 1.65-2.1 1.9l-.5.35c-.4.25-.65.45-.75.45-.25.15-.45.35-.65.6-.15.3-.25.55-.25.75l.3.65c.55-.2 1.2-.65 2.05-1.35.85-.75 1.65-1.55 2.5-2.5.8-.9 1.6-1.65 2.4-2.3.8-.65 1.4-.95 1.9-1 .15 0 1.5 1.05 4.1 3.2 2.6 2.15 4.3 3.55 5.05 4.2l.2.45c.1-.05.2-.15.3-.3.1-.15.15-.3.15-.45z" />
            </svg>
        </div>





        <div class="mobile-menu-bar">
            <ul>
                <li class="mobile-item">
                    <a href="{{ route('hotel.home',$slug) }}" class="acnav-label">{{ __('Home') }}</a>
                </li>
                @if(count($customPages) > 0)
                    @foreach ($customPages as $page)
                        <li class="mobile-item">
                            <a href="{{ route('frontend.custom.page',[$slug,$page->slug]) }}" class="acnav-label">
                                {{ $page->name }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    <!-- Mobile menu end here -->



    {{-- cart popup model --}}
    <div class="cart-popup"></div>
    {{-- cart popup model --}}


    <!--scripts start here-->
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/custom.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/slick.min.js') }}"></script>
    <!--scripts end here-->

    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/flatpickr.min.js') }}"></script>

    <script>
        $(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {

            var data = {};
            var title1 = $(this).data("title");

            var title2 = $(this).data("bs-original-title");
            var title3 = $(this).data("original-title");
            var title = (title1 != undefined) ? title1 : title2;
            var title=(title != undefined) ? title : title3;

            $('.modal-dialog').removeClass('modal-xl');
            var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
            var url = $(this).data('url');
            $("#commonModal .modal-title").html(title);
            $("#commonModal .modal-dialog").addClass('modal-' + size);

            if ($('#vc_name_hidden').length > 0) {
                data['vc_name'] = $('#vc_name_hidden').val();
            }
            if ($('#warehouse_name_hidden').length > 0) {
                data['warehouse_name'] = $('#warehouse_name_hidden').val();
            }
            if ($('#discount_hidden').length > 0) {
                data['discount'] = $('#discount_hidden').val();
            }
            $.ajax({
                url: url,
                data: data,
                success: function (data) {
                    $('#commonModal .modal-body').html(data);
                    $("#commonModal").modal('show');
                    // daterange_set();
                    // taskCheckbox();
                    // common_bind("#commonModal");
                    // commonLoader();

                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('Error', data.error, 'error')
                }
            });

        });

        $(document).ready(function() {
            daterange();
        });

        function daterange() {
            if ($(".date-ranger").length > 0) {
                document.querySelector(".date-ranger").flatpickr({
                    altInput: true,
                    allowInput: true,
                    mode: "range"
                });
            }

            if ($("#pc-daterangepicker-2").length > 0) {
                document.querySelector("#pc-daterangepicker-2").flatpickr({
                    altInput: true,
                    allowInput: true,
                    mode: "range"
                });
            }
        }


        $(document).on('click', '.addcart-btn', function(e) {
            var room_id = $(this).attr('room_id');
            var date = $(this).attr('date');
            var room = $(this).attr('total_room');
            var data = {
                room_id: room_id,
                date: date,
                room: room
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route('addToCart', $slug) }}',
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




        $('.cart-drawer').click(function() {
            get_cartlist();
        });


        $(document).on('click', '.remove_item_from_cart', function(e) {
            var cart_id = $(this).attr('data-id');
            var data = {
                cart_id: cart_id
            }
            $.ajax({
                url: '{{ route('cart.remove', $slug) }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(response) {
                    get_cartlist();
                }
            });
        });


        function get_cartlist() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route('Cart.list', $slug) }}',
                method: 'POST',
                context: this,
                success: function(response) {
                    if (response.status == 0) {
                        $('.cart-header').css("pointer-events", "auto");
                        $('.cart-count').html(0);
                        $('.cartDrawer .closecart').click();
                    } else {
                        $('.cart-count').html(response.count);
                        $('.cartDrawer').html(response.html);
                        $('body').addClass('no-scroll cartOpen');
                        $('.overlay').addClass('cart-overlay');
                    }
                }
            });
        }
    </script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/notifier.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/custom.js') }}{{ '?' . time() }}"></script>


    <script>
        function show_toastr(title, message, type) {
            var o, i;
            var icon = '';
            var cls = '';
            if (type == 'success') {
                cls = 'primary';
                notifier.show('Success', message, 'success', site_url + '/public/assets/images/notification/ok-48.png', 4000);
            } else {
                cls = 'danger';
                notifier.show('Error', message, 'danger', site_url + '/public/assets/images/notification/high_priority-48.png', 4000);
            }
        }

    </script>
     @if ($message = Session::get('success'))
        <script>
            show_toastr('{{ __('Success') }}', '{!! $message !!}', 'success')
        </script>
    @endif

    @if ($message = Session::get('error'))
        <script>
            show_toastr('{{ __('Error') }}', '{!! $message !!}', 'error')
        </script>
    @endif
    @stack('customer_live_chat_script')

    @stack('script')
</body>

</html>
