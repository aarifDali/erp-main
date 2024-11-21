<?php

namespace Workdo\Account\Providers;

use App\Events\BankTransferPaymentStatus;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use App\Events\CompanyMenuEvent;
use App\Events\CompanySettingEvent;
use App\Events\CompanySettingMenuEvent;
use App\Events\CreatePaymentInvoice;
use App\Events\DefaultData;
use App\Events\GivePermissionToRole;
use App\Events\SentInvoice;
use App\Events\UpdateInvoice;
use Workdo\Account\Listeners\CompanyMenuListener;
use Workdo\Account\Listeners\CompanySettingListener;
use Workdo\Account\Listeners\CompanySettingMenuListener;
use Workdo\Account\Listeners\InvoiceBalanceTransfer;
use Workdo\AamarPay\Events\AamarPaymentStatus;
use Workdo\Account\Events\CreatePaymentBill;
use Workdo\Account\Events\SentBill;
use Workdo\Account\Events\UpdateBill;
use Workdo\Account\Listeners\BillPaymentCreate;
use Workdo\Account\Listeners\BillSent;
use Workdo\Account\Listeners\BillUpdate;
use Workdo\Account\Listeners\DataDefault;
use Workdo\Account\Listeners\GiveRoleToPermission;
use Workdo\Account\Listeners\InvoicePaymentCreate;
use Workdo\Account\Listeners\InvoiceSent;
use Workdo\Account\Listeners\InvoiceUpdate;
use Workdo\AuthorizeNet\Events\AuthorizeNetStatus;
use Workdo\Benefit\Events\BenefitPaymentStatus;
use Workdo\Cashfree\Events\CashfreePaymentStatus;
use Workdo\Coingate\Events\CoingatePaymentStatus;
use Workdo\Fedapay\Events\FedapayPaymentStatus;
use Workdo\Flutterwave\Events\FlutterwavePaymentStatus;
use Workdo\Iyzipay\Events\IyzipayPaymentStatus;
use Workdo\Khalti\Events\KhaltiPaymentStatus;
use Workdo\Mercado\Events\MercadoPaymentStatus;
use Workdo\Midtrans\Events\MidtransPaymentStatus;
use Workdo\Mollie\Events\MolliePaymentStatus;
use Workdo\Paddle\Events\PaddlePaymentStatus;
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

class EventServiceProvider extends Provider
{
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    protected $listen = [
        CompanyMenuEvent::class => [
            CompanyMenuListener::class,
        ],
        CompanySettingEvent::class => [
            CompanySettingListener::class,
        ],
        CompanySettingMenuEvent::class => [
            CompanySettingMenuListener::class,
        ],
        DefaultData :: class => [
            DataDefault::class,
        ],
        GivePermissionToRole::class => [
            GiveRoleToPermission::class,
        ],

        StripePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaypalPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        FlutterwavePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaystackPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        RazorpayPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        MolliePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PayfastPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        YooKassaPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaytabPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        SSpayPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        ToyyibpayPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        SkrillPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        IyzipayPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaytrPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        AamarPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        BenefitPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        CashfreePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        CoingatePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        MercadoPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaytmPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaddlePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        MidtransPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        XenditPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        TapPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        KhaltiPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PhonePePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        AuthorizeNetStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PayHerePaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        PaiementProPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        FedapayPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        BankTransferPaymentStatus::class =>[
            InvoiceBalanceTransfer::class
        ],
        SentInvoice::class =>[
            InvoiceSent::class
        ],
        CreatePaymentBill::class =>[
            BillPaymentCreate::class
        ],
        SentBill::class =>[
            BillSent::class
        ],
        UpdateBill::class =>[
            BillUpdate::class
        ],
        CreatePaymentInvoice::class =>[
            InvoicePaymentCreate::class
        ],
        UpdateInvoice::class =>[
            InvoiceUpdate::class
        ]
    ];

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            __DIR__ . '/../Listeners',
        ];
    }
}
