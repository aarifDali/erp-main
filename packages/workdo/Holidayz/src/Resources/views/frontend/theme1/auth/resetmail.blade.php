@php
    $lang =  (Auth::guard('holiday')->user()) ? Auth::guard('holiday')->user()->lang : 'en';
    $SITE_RTL = 'off';

    $logo = get_file('uploads/logo');
    $company_logo = dark_logo('company_logo_dark');
    $company_logos = light_logo('company_logo_light');

    $metatitle = isset($getseo['meta_title']) ? $getseo['meta_title'] : '';
    $metsdesc = isset($getseo['meta_desc']) ? $getseo['meta_desc'] : '';
    $meta_image = get_file('uploads/meta/');
    $meta_logo = isset($getseo['meta_image']) ? $getseo['meta_image'] : '';

    $hotel          = \Workdo\Holidayz\Entities\Hotels::where('slug', $slug)->where('is_active','1')->first();
    $favicon = isset($company_settings['favicon']) ? $company_settings['favicon'] : (isset($admin_settings['favicon']) ? $admin_settings['favicon'] : 'uploads/logo/favicon.png');
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Hotel Boutique">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

    <meta name="author" content="{{ env('APP_NAME') }}">
    <meta name="description" content="{{ $metsdesc }}">
    <meta name="keywords" content="{{ $metatitle }}">
    <link rel="shortcut icon" href="{{ $meta_image . $meta_logo }}">

    <link rel="icon" href="{{ check_file($favicon) ? get_file($favicon) : get_file('uploads/logo/favicon.png') }}{{ '?' . time() }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&family=Outfit:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href='{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/css/main-style.css') }}'>
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/css/responsive.css') }}">

    <style>
        .invalid-feedback {
            color: red;
        }

        .btn-login {
            font-size: 14px;
            color: #fff;
            font-family: 'Montserrat-SemiBold';
            background: #0f5ef7;
            margin-top: 20px;
            padding: 10px 30px;
            width: 100%;
            border-radius: 5px;
            border: none;
            text-decoration: none;
        }
        .size{
            font-size: 16px;
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
    <!--wrapper start here-->
    <div class="wrapper">
        <section class="login-section">
            <div class="offset-left">
                <div class="row">
                    <div class="col-md-12">
                        <div class="login-left">
                            <div class="section-title">
                                <h6>{{ $hotel->name }}</h6>
                            </div>
                            <p class="size">{{__('You are receiving this email because we received a password reset request for your account')}}</p><br><br>

                            <div><a href="{{ route('hotel.customer.password.reset',[$slug,$token]) }}" target="_blank" class="btn-login" >{{__('Reset Password')}}</a></div><br><br>

                            <p class="text-muted size">
                                {{ __('If you did not request a password reset, no further action is required..') }}
                            </p><br>
                            <p class="size">{{__('Regards')}},</p>
                            <p class="size">{{ $hotel->name }}</p>
                            <br>
                            <hr>
                            <p> {{__('If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:')}} <a href="{{ url('hotel/'.$slug.'/hotel-customer-password/reset/'.$token) }}">{{ url('hotel/'.$slug.'/hotel-customer-password/reset/'.$token) }}</a> </p><br>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!---wrapper end here-->
    <!--scripts start here-->
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/custom.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/custom.js') }}{{ '?' . time() }}"></script>
    <!--scripts end here-->
    @stack('customer_live_chat_script')

</body>

</html>
