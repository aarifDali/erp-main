<?php

namespace App\Providers;

use App\Events\BankTransferRequestUpdate;
use App\Listeners\ReferralTransactionLis;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Workdo\AamarPay\Events\AamarPaymentStatus;
use Workdo\AuthorizeNet\Events\AuthorizeNetStatus;
use Workdo\Benefit\Events\BenefitPaymentStatus;
use Workdo\Cashfree\Events\CashfreePaymentStatus;
use Workdo\CinetPay\Events\CinetPayPaymentStatus;
use Workdo\Coingate\Events\CoingatePaymentStatus;
use Workdo\Fedapay\Events\FedapayPaymentStatus;
use Workdo\Flutterwave\Events\FlutterwavePaymentStatus;
use Workdo\Iyzipay\Events\IyzipayPaymentStatus;
use Workdo\Khalti\Events\KhaltiPaymentStatus;
use Workdo\Mercado\Events\MercadoPaymentStatus;
use Workdo\Midtrans\Events\MidtransPaymentStatus;
use Workdo\Mollie\Events\MolliePaymentStatus;
use Workdo\Nepalste\Events\NepalstePaymentStatus;
use Workdo\PaiementPro\Events\PaiementProPaymentStatus;
use Workdo\Payfast\Events\PayfastPaymentStatus;
use Workdo\PayHere\Events\PayHerePaymentStatus;
use Workdo\Paypal\Events\PaypalPaymentStatus;
use Workdo\Paystack\Events\PaystackPaymentStatus;
use Workdo\PayTab\Events\PaytabPaymentStatus;
use Workdo\Paytm\Events\PaytmPaymentStatus;
use Workdo\PayTR\Events\PaytrPaymentStatus;
use Workdo\PhonePe\Events\PhonePePaymentStatus;
use Workdo\Razorpay\Events\RazorpayPaymentStatus;
use Workdo\Skrill\Events\SkrillPaymentStatus;
use Workdo\SSPay\Events\SSpayPaymentStatus;
use Workdo\Stripe\Events\StripePaymentStatus;
use Workdo\Tap\Events\TapPaymentStatus;
use Workdo\Toyyibpay\Events\ToyyibpayPaymentStatus;
use Workdo\Xendit\Events\XenditPaymentStatus;
use Workdo\YooKassa\Events\YooKassaPaymentStatus;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BankTransferRequestUpdate::class => [
            ReferralTransactionLis::class,
        ],
        PaypalPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        StripePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        AamarPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        AuthorizeNetStatus::class => [
            ReferralTransactionLis::class,
        ],
        BenefitPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        CashfreePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        CinetPayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        CoingatePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        FedapayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        FlutterwavePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        IyzipayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        KhaltiPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        MercadoPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        MidtransPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        MolliePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaiementProPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PayfastPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PayHerePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaystackPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaytabPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaytmPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaytrPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PhonePePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        PaytabPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        RazorpayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        SkrillPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        SSpayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        TapPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        ToyyibpayPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        XenditPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        YooKassaPaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
        NepalstePaymentStatus::class => [
            ReferralTransactionLis::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
