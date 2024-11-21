<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Hotel Boutique">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>{{__('404 Not Found')}}</title>
    <meta name="description" content="Hotel Boutique">
    <meta name="keywords" content="Hotel Boutique">
    <link rel="shortcut icon" href="assets/images/favicon.png">
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&family=Outfit:wght@100;200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/css/main-style.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/css/responsive.css') }}">
    <style>
        /**** error main css ***/
        .error-main-sec .eroor-content {
            text-align: center;
        }

        .error-main-sec {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error-main-sec .eroor-content .large-text {
            font-size: 120px;
            font-family: var(--third-font);
            font-weight: 500;
            line-height: 75%;
        }

        .error-main-sec .eroor-content .section-title h3 {
            margin: 25px 0 20px;
        }

        .error-main-sec .eroor-content .form-input {
            max-width: 400px;
            width: 100%;
            margin: 0 auto 20px;
            position: relative;
        }

        .error-main-sec .eroor-content .form-input input {
            padding: 14px 40px 14px 20px;
            border-radius: 15px;
        }

        .error-main-sec .eroor-content .form-input .input-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            transform: translateY(-50%);
            background: transparent;
            border: none;
        }

        .error-main-sec .eroor-content .form-input .input-btn svg path {
            fill: var(--black);
        }

        .error-main-sec .shop-btn a {
            text-decoration: underline;
        }

        .error-main-sec .error-img {
            max-width: 300px;
            width: 100%;
            margin: auto;
        }
    </style>
</head>

<body>
    <!--wrapper start here-->
    <main class="wrapper">
        <section class="error-main-sec pb">
            <div class="container">
                <div class="eroor-content">
                    <div class="section-title">
                        <h2 class="large-text">{{ __('404') }}</h2>
                        <h3>{{ __('Oops! Hotel Not Found!') }}</h3>
                        <p>{{ __('The page you requested does not exist.') }}</p>
                    </div>
                    <div class="shop-btn">
                        <a href="{{ route('login') }}" class="back-btn btn" style="text-decoration: none;">
                            {{ __('Return to Home') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!---wrapper end here-->
    <!--scripts start here-->
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/swiper-bundle.min.js') }}" defer="defer"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/frontend/assets/js/custom.js') }}" defer="defer"></script>
    <!--scripts end here-->
</body>

</html>
