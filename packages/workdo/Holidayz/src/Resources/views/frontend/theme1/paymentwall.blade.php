<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@php

@endphp

<script src="https://api.paymentwall.com/brick/build/brick-default.1.5.0.min.js"></script>
<div id="payment-form-container"> </div>
<script>
    var brick = new Brick({
        public_key: '{{ $hotelPaymentSettings['paymentwall_public_key'] }}', // please update it to Brick live key before launch your project
        amount: '{{ $price }}',
        currency: '{{ App\Models\Utility::getValByName('site_currency') }}',
        container: 'payment-form-container',
        action: '{{ route('booking.pay.with.paymentwall', [$slug, $data['coupon']]) }}',
        form: {
            merchant: 'Paymentwall',
            product: 'Booking',
            pay_button: 'Pay',
            show_zip: true, // show zip code
            show_cardholder: true // show card holder name
        }
    });
    brick.showPaymentForm(function(data) {
        if (data.flag == 1) {
            window.location.href = '{{ route('error.booking.show', [$slug, 1]) }}';
        } else {
            window.location.href = '{{ route('error.booking.show', [$slug, 2]) }}';
        }
    }, function(errors) {
        if (errors.flag == 1) {
            window.location.href = '{{ route('error.booking.show', [$slug, 1]) }}';
        } else {
            window.location.href = '{{ route('error.booking.show', [$slug, 2]) }}';
        }
    });
</script>
