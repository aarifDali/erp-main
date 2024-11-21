@php
    $lang =  (\Auth::guard('holiday')->user()) ? \Auth::guard('holiday')->user()->lang : 'en';
    if(!empty(\Auth::user())){
        $SITE_RTL = company_setting('SITE_RTL');
    }else{
        $SITE_RTL = 'off';
    }
    $logo = get_file('uploads/logo');

    $company_logo = dark_logo('company_logo_dark');
    $company_logos = light_logo('company_logo_light');

    $metatitle = isset($getseo['meta_title']) ? $getseo['meta_title'] : '';
    $metsdesc = isset($getseo['meta_desc']) ? $getseo['meta_desc'] : '';
    $meta_image = get_file('uploads/meta/');
    $meta_logo = isset($getseo['meta_image']) ? $getseo['meta_image'] : '';
    $favicon = isset($company_settings['favicon']) ? $company_settings['favicon'] : (isset($admin_settings['favicon']) ? $admin_settings['favicon'] : 'uploads/logo/favicon.png');
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Hotel Boutique">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>{{ __('Forgot Password') }} | {{ config('APP_NAME', ucfirst($hotel->name)) }}</title>

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
    <link rel="shortcut icon" href="{{ $meta_image . $meta_logo }}">

    <link rel="icon" href="{{ check_file($favicon) ? get_file($favicon) : get_file('uploads/logo/favicon.png') }}{{ '?' . time() }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&family=Outfit:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href='{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/css/main-style.css') }}'>
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/css/responsive.css') }}">

    <style>
        .header-style-one .main-navigationbar .menu-items-col .main-nav>li>a,
        .menu-right-btns ul>li>a {
            color: var(--black);
        }

        .cart-btn path,
        .header-style-one .main-navigationbar .menu-items-col .main-nav>li.has-item a::after {
            fill: var(--black);
        }

        .cart-btn path,
        .header-style-one .main-navigationbar .menu-items-col .main-nav>li.has-item a::after {
            filter: invert(48%) sepia(13%) saturate(3207%) hue-rotate(130deg) brightness(0%) contrast(80%);
        }

        .invalid-feedback {
            color: red;
        }
    </style>
</head>

<body class="color-v1" lang="en" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">
    <svg style="display: none;">
        <symbol viewBox="0 0 6 5" id="slickarrow">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M5.89017 2.75254C6.03661 2.61307 6.03661 2.38693 5.89017 2.24746L3.64017 0.104605C3.49372 -0.0348681 3.25628 -0.0348681 3.10984 0.104605C2.96339 0.244078 2.96339 0.470208 3.10984 0.609681L5.09467 2.5L3.10984 4.39032C2.96339 4.52979 2.96339 4.75592 3.10984 4.8954C3.25628 5.03487 3.49372 5.03487 3.64016 4.8954L5.89017 2.75254ZM0.640165 4.8954L2.89017 2.75254C3.03661 2.61307 3.03661 2.38693 2.89017 2.24746L0.640165 0.104605C0.493719 -0.0348682 0.256282 -0.0348682 0.109835 0.104605C-0.0366115 0.244078 -0.0366115 0.470208 0.109835 0.609681L2.09467 2.5L0.109835 4.39032C-0.0366117 4.52979 -0.0366117 4.75592 0.109835 4.8954C0.256282 5.03487 0.493719 5.03487 0.640165 4.8954Z">
            </path>
        </symbol>
    </svg>
    <!--header start here-->
    <!--header end here-->
    <!--wrapper start here-->
    <div class="wrapper">
        <section class="login-section">
            <div class="offset-left">
                <div class="row">
                    <div class="col-md-5">
                        <div class="login-left">
                            <a href="{{ route('hotel.home', $slug) }}" class="back-btn">
                                <span class="svg-ic">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5"
                                        viewBox="0 0 11 5" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                                            fill="white"></path>
                                    </svg>
                                </span>
                                {{ __('Back to Home') }}
                            </a>
                            <div class="section-title">
                                <h3>{{ __('Forgot') }} <b>{{ __('Password') }}</b></h3>
                            </div>
                            {!! Form::open(['route' => ['hotel.customer.password.update', $slug]], ['method' => 'post']) !!}
                            <div class="create-account">
                                <div class="form-container">
                                    <div class="form-heading">
                                        <h5>{{ __('Password Reset') }}</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <div class="form-group">
                                            <label for="email" class="form-label">{{ __('Email Address :') }}</label>
                                            {{ Form::text('email', null, ['class' => 'form-control form-control-lg', 'placeholder' => __('Enter Your Email'),'autofocus']) }}
                                            @error('email')
                                                <span class="error invalid-password text-danger" role="alert">
                                                    <strong>{{ __('We can not find a student with that email address') }}.</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="form-label">{{ __('Password :') }}</label>
                                            {{ Form::password('password', ['class' => 'form-control form-control-lg', 'placeholder' => __('Enter Your Password')]) }}
                                            @error('password')
                                                <span class="error invalid-password text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password :') }}</label>
                                            {{ Form::password('password_confirmation', ['class' => 'form-control form-control-lg', 'placeholder' => __('Confirm Password')]) }}
                                            @error('password_confirmation')
                                                <span class="error invalid-password_confirmation text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <button href="#" class="btn d-flex align-items-center login-btn" id="saveBtn">
                                            <span>{{ __('Reset Password') }}</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                                viewBox="0 0 19 19" fill="none">
                                                <path
                                                    d="M9.54395 1.31787C5.09795 1.31787 1.48145 4.93437 1.48145 9.38037C1.48145 13.8264 5.09795 17.4429 9.54395 17.4429C13.9899 17.4429 17.6064 13.8264 17.6064 9.38037C17.6064 4.93437 13.9899 1.31787 9.54395 1.31787ZM9.54395 16.3179C5.7182 16.3179 2.60645 13.2061 2.60645 9.38037C2.60645 5.55462 5.7182 2.44287 9.54395 2.44287C13.3697 2.44287 16.4814 5.55462 16.4814 9.38037C16.4814 13.2061 13.3697 16.3179 9.54395 16.3179ZM13.0629 9.5957C13.0344 9.6647 12.9932 9.72688 12.9415 9.77863L10.6915 12.0286C10.582 12.1381 10.4379 12.1936 10.2939 12.1936C10.1499 12.1936 10.0059 12.1389 9.89642 12.0286C9.67667 11.8089 9.67667 11.4526 9.89642 11.2328L11.1864 9.94287H6.54395C6.23345 9.94287 5.98145 9.69087 5.98145 9.38037C5.98145 9.06987 6.23345 8.81787 6.54395 8.81787H11.1857L9.89569 7.52789C9.67594 7.30814 9.67594 6.95187 9.89569 6.73212C10.1154 6.51237 10.4717 6.51237 10.6915 6.73212L12.9415 8.98212C12.9932 9.03387 13.0344 9.09604 13.0629 9.16504C13.1199 9.30304 13.1199 9.4577 13.0629 9.5957Z"
                                                    fill="black" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div class="col-md-7">

                        <div class="login-right">
                            <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/images/login.png') }}">
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
    <!---wrapper end here-->
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
                    <a href="#" class="acnav-label">{{ __('Home') }}</a>
                </li>
                <li class="mobile-item">
                    <a href="#" class="acnav-label">{{ __('Rooms & Suites') }}</a>
                </li>
                <li class="mobile-item">
                    <a href="#">{{ __('Restaurants & Bars') }}</a>
                </li>
                <li class="mobile-item">
                    <a href="#">{{ __('Facilities') }}</a>
                </li>
                <li class="mobile-item">
                    <a href="#">{{ __('Gallery') }}</a>
                </li>
                <li class="mobile-item">
                    <a href="#">{{ __('Offers & Events') }}</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Mobile menu end here -->
    <!--scripts start here-->
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/custom.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/custom.js') }}{{ '?' . time() }}"></script>
    <!--scripts end here-->
    @stack('customer_live_chat_script')

    <script>
        if ('{!! !empty($is_cart) && $is_cart == true !!}') {
            show_toastr('Error', 'You need to login!', 'error');
        }
    </script>

    @if ($message = Session::get('error'))
        <script>
            $('.error-message').html('{{ $message }}');
        </script>
    @endif

    @if ($message = Session::get('error1'))
        <script>
            $('.error-message-register').html('{{ $message }}');
        </script>
    @endif


</body>

</html>
