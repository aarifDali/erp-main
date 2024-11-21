<?php

namespace Workdo\Paypal\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Plan;
use App\Models\Order;
use App\Models\Setting;
use App\Models\WorkSpace;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Workdo\Fleet\Entities\Vehicle;
use PayPal\Rest\ApiContext;
use Illuminate\Support\Facades\Session;
use Workdo\Paypal\Entities\PaypalUtility;
use Workdo\Paypal\Events\PaypalPaymentStatus;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\RoomBookingCart;
use Workdo\Holidayz\Entities\BookingCoupons;
use Workdo\Holidayz\Entities\HotelCustomer;
use Workdo\Holidayz\Entities\RoomBooking;
use Workdo\Holidayz\Entities\RoomBookingOrder;
use Workdo\Holidayz\Entities\UsedBookingCoupons;
use Workdo\Holidayz\Events\CreateRoomBooking;
use Workdo\BeautySpaManagement\Entities\BeautyBooking;
use Workdo\BeautySpaManagement\Entities\BeautyReceipt;
use Workdo\BeautySpaManagement\Entities\BeautyService;
use Workdo\EventsManagement\Entities\EventBookingOrder;
use Workdo\EventsManagement\Entities\EventsBookings;
use Workdo\EventsManagement\Entities\EventsMange;
use Workdo\Bookings\Entities\BookingsAppointment;
use Workdo\Bookings\Entities\BookingsCustomer;
use Workdo\Bookings\Entities\BookingsPackage;
use Workdo\MovieShowBookingSystem\Entities\MovieSeatBooking;
use Workdo\MovieShowBookingSystem\Entities\MovieSeatBookingOrder;
use Workdo\ParkingManagement\Entities\Parking;
use Workdo\ParkingManagement\Entities\Payment;
use Workdo\Facilities\Entities\FacilitiesService;
use Workdo\Facilities\Entities\FacilitiesBooking;
use Workdo\Facilities\Entities\FacilitiesReceipt;
use Workdo\PropertyManagement\Entities\Property;
use Workdo\PropertyManagement\Entities\PropertyTenantRequest;
use Workdo\VehicleBookingManagement\Entities\Booking;
use Workdo\VehicleBookingManagement\Entities\VehicleBookingPayments;

class PaypalController extends Controller
{
    // private $_api_context;
    protected $invoiceData;
    public $paypal_mode;
    public $paypal_client_id;
    public $paypal_secret_key;
    public $enable_paypal;
    public $currancy;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function setting(Request $request)
    {
        if (Auth::user()->isAbleTo('paypal manage')) {
            if ($request->has('paypal_payment_is_on')) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'company_paypal_mode' => 'required|string',
                        'company_paypal_client_id' => 'required|string',
                        'company_paypal_secret_key' => 'required|string',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
            }
            $post = $request->all();
            unset($post['_token']);
            unset($post['_method']);
            if ($request->has('paypal_payment_is_on')) {
                foreach ($post as $key => $value) {
                    // Define the data to be updated or inserted
                    $data = [
                        'key' => $key,
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ];

                    // Check if the record exists, and update or insert accordingly
                    Setting::updateOrInsert($data, ['value' => $value]);
                }
            } else {
                // Define the data to be updated or inserted
                $data = [
                    'key' => 'paypal_payment_is_on',
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => 'off']);
            }

            // Settings Cache forget
            AdminSettingCacheForget();
            comapnySettingCacheForget();
            return redirect()->back()->with('success', __('Paypal Setting save successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    // get paypal payment setting
    public function paymentConfig($id = null, $workspace = Null)
    {
        $company_settings = getCompanyAllSetting($id, $workspace);
        $this->currancy = isset($company_settings['defult_currancy']) ? $company_settings['defult_currancy'] : '$';
        $this->enable_paypal = isset($company_settings['paypal_payment_is_on']) ? $company_settings['paypal_payment_is_on'] : 'off';

        if ($company_settings['company_paypal_mode'] == 'live') {
            config(
                [
                    'paypal.live.client_id' => isset($company_settings['company_paypal_client_id']) ? $company_settings['company_paypal_client_id'] : '',
                    'paypal.live.client_secret' => isset($company_settings['company_paypal_secret_key']) ? $company_settings['company_paypal_secret_key'] : '',
                    'paypal.mode' => isset($company_settings['company_paypal_mode']) ? $company_settings['company_paypal_mode'] : '',
                ]
            );
        } else {
            config(
                [
                    'paypal.sandbox.client_id' => isset($company_settings['company_paypal_client_id']) ? $company_settings['company_paypal_client_id'] : '',
                    'paypal.sandbox.client_secret' => isset($company_settings['company_paypal_secret_key']) ? $company_settings['company_paypal_secret_key'] : '',
                    'paypal.mode' => isset($company_settings['company_paypal_mode']) ? $company_settings['company_paypal_mode'] : '',
                ]
            );
        }
    }

    public function invoicePayWithPaypal(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['amount' => 'required|numeric', 'invoice_id' => 'required']
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $invoice_id = $request->input('invoice_id');
        $type = $request->type;
        if ($type == 'invoice') {
            $invoice = \App\Models\Invoice::find($invoice_id);
            $user_id = $invoice->created_by;
            $workspace = $invoice->workspace;
            $payment_id = $invoice->id;
        } elseif ($type == 'retainer') {

            $invoice = \Workdo\Retainer\Entities\Retainer::find($invoice_id);
            $user_id = $invoice->created_by;
            $workspace = $invoice->workspace;
            $payment_id = $invoice->id;
        }

        $this->invoiceData = $invoice;
        $this->paymentConfig($user_id, $workspace);
        $currency     = $this->currancy;
        $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

        if (!in_array($currency, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }
        $get_amount = $request->amount;
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        if ($invoice) {
            if ($get_amount > $invoice->getDue()) {
                return redirect()->back()->with('error', __('Invalid amount.'));
            } else {
                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('invoice.paypal', [$payment_id, $get_amount, $type]),
                        "cancel_url" => route('invoice.paypal', [$payment_id, $get_amount, $type]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => $this->currancy = company_setting('defult_currancy', $user_id, $workspace),

                                "value" => $get_amount
                            ]
                        ]
                    ]
                ]);
                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()->back()->with('error', 'Something went wrong.');
                } else {
                    if ($request->type == 'invoice') {
                        return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', $response['message'] ?? 'Something went wrong.');
                    } elseif ($request->type == 'retainer') {
                        return redirect()->route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', $response['message'] ?? 'Something went wrong.');
                    }
                }
                return redirect()->back()->with('error', __('Unknown error occurred'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getInvoicePaymentStatus(Request $request, $invoice_id, $amount, $type)
    {
        if ($type == 'invoice') {
            $invoice = \App\Models\Invoice::find($invoice_id);
            $this->paymentConfig($invoice->created_by, $invoice->workspace);
            $this->invoiceData = $invoice;

            if ($invoice) {
                $payment_id = Session::get('paypal_payment_id');
                Session::forget('paypal_payment_id');
                if (empty($request->PayerID || empty($request->token))) {
                    return redirect()->route('invoice.show', $invoice_id)->with('error', __('Payment failed'));
                }
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                try {
                    $invoice_payment = new \App\Models\InvoicePayment();
                    $invoice_payment->invoice_id = $invoice_id;
                    $invoice_payment->date = Date('Y-m-d');
                    $invoice_payment->account_id = 0;
                    $invoice_payment->payment_method = 0;
                    $invoice_payment->amount = $amount;
                    $invoice_payment->order_id = $orderID;
                    $invoice_payment->currency = $this->currancy;
                    $invoice_payment->payment_type = __('PayPal');
                    $invoice_payment->save();

                    $due = $invoice->getDue();
                    if ($due <= 0) {
                        $invoice->status = 4;
                        $invoice->save();
                    } else {
                        $invoice->status = 3;
                        $invoice->save();
                    }
                    if (module_is_active('Account')) {
                        //for customer balance update
                        \Workdo\Account\Entities\AccountUtility::updateUserBalance('customer', $invoice->customer_id, $invoice_payment->amount, 'debit');
                    }
                    event(new PaypalPaymentStatus($invoice, $type, $invoice_payment));

                    return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('success', __('Invoice paid Successfully!'));
                } catch (\Exception $e) {
                    return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('success', $e->getMessage());
                }
            } else {
                return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('success', __('Invoice not found.'));
            }
        } elseif ($type == 'retainer') {
            $retainer = \Workdo\Retainer\Entities\Retainer::find($invoice_id);
            $this->paymentConfig($retainer->created_by, $retainer->workspace);

            $this->invoiceData = $retainer;
            if ($retainer) {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $payment_id = Session::get('paypal_payment_id');
                Session::forget('paypal_payment_id');
                if (empty($request->PayerID || empty($request->token))) {
                    return redirect()->route('retainer.show', $invoice_id)->with('error', __('Payment failed'));
                }

                try {
                    $retainer_payment = new \Workdo\Retainer\Entities\RetainerPayment();
                    $retainer_payment->retainer_id = $invoice_id;
                    $retainer_payment->date = Date('Y-m-d');
                    $retainer_payment->account_id = 0;
                    $retainer_payment->payment_method = 0;
                    $retainer_payment->amount = $amount;
                    $retainer_payment->order_id = $orderID;
                    $retainer_payment->currency = $this->currancy;
                    $retainer_payment->payment_type = __('PayPal');
                    $retainer_payment->save();
                    $due = $retainer->getDue();

                    if ($due <= 0) {
                        $retainer->status = 4;
                        $retainer->save();
                    } else {
                        $retainer->status = 2;
                        $retainer->save();
                    }
                    //for customer balance update
                    \Workdo\Retainer\Entities\RetainerUtility::updateUserBalance('customer', $retainer->customer_id, $retainer_payment->amount, 'debit');

                    event(new PaypalPaymentStatus($retainer, $type, $retainer_payment));

                    return redirect()->route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('success', __('Retainer paid Successfully!'));
                } catch (\Exception $e) {
                    return redirect()->route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('success', $e->getMessage());
                }
            } else {

                return redirect()->route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('success', __('Retainer not found.'));
            }
        }
    }

    public function planPayWithPaypal(Request $request)
    {
        $plan = Plan::find($request->plan_id);
        $user_counter = !empty($request->user_counter_input) ? $request->user_counter_input : 0;
        $workspace_counter = !empty($request->workspace_counter_input) ? $request->workspace_counter_input : 0;
        $user_module = !empty($request->user_module_input) ? $request->user_module_input : '0';
        $duration = !empty($request->time_period) ? $request->time_period : 'Month';
        $user_module_price = 0;
        if (!empty($user_module) && $plan->custom_plan == 1) {
            $user_module_array = explode(',', $user_module);
            foreach ($user_module_array as $key => $value) {
                $temp = ($duration == 'Year') ? ModulePriceByName($value)['yearly_price'] : ModulePriceByName($value)['monthly_price'];
                $user_module_price = $user_module_price + $temp;
            }
        }
        $user_price = 0;
        if ($user_counter > 0) {
            $temp = ($duration == 'Year') ? $plan->price_per_user_yearly : $plan->price_per_user_monthly;
            $user_price = $user_counter * $temp;
        }
        $workspace_price = 0;
        if ($workspace_counter > 0) {
            $temp = ($duration == 'Year') ? $plan->price_per_workspace_yearly : $plan->price_per_workspace_monthly;
            $workspace_price = $workspace_counter * $temp;
        }
        $plan_price = ($duration == 'Year') ? $plan->package_price_yearly : $plan->package_price_monthly;
        $counter = [
            'user_counter' => $user_counter,
            'workspace_counter' => $workspace_counter,
        ];

        $admin_settings = getAdminAllSetting();
        $currency     = $admin_settings['defult_currancy'];
        $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

        if (!in_array($currency, $supported_currencies)) {
            return redirect()->route('plans.index')->with('error', __('Currency is not supported.'));
        }
        if ($admin_settings['company_paypal_mode'] == 'live') {
            config(
                [
                    'paypal.live.client_id' => isset($admin_settings['company_paypal_client_id']) ? $admin_settings['company_paypal_client_id'] : '',
                    'paypal.live.client_secret' => isset($admin_settings['company_paypal_secret_key']) ? $admin_settings['company_paypal_secret_key'] : '',
                    'paypal.mode' => isset($admin_settings['company_paypal_mode']) ? $admin_settings['company_paypal_mode'] : '',
                ]
            );
        } else {
            config(
                [
                    'paypal.sandbox.client_id' => isset($admin_settings['company_paypal_client_id']) ? $admin_settings['company_paypal_client_id'] : '',
                    'paypal.sandbox.client_secret' => isset($admin_settings['company_paypal_secret_key']) ? $admin_settings['company_paypal_secret_key'] : '',
                    'paypal.mode' => isset($admin_settings['company_paypal_mode']) ? $admin_settings['company_paypal_mode'] : '',
                ]
            );
        }
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));

        if ($plan) {
            try {
                if ($request->coupon_code) {
                    $plan_price = CheckCoupon($request->coupon_code, $plan_price, $plan->id);
                }

                $price = $plan_price + $user_module_price + $user_price + $workspace_price;

                if ($price <= 0) {
                    $assignPlan = DirectAssignPlan($plan->id, $duration, $user_module, $counter, 'PAYPAL', $request->coupon_code);
                    if ($assignPlan['is_success']) {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                    } else {
                        return redirect()->route('plans.index')->with('error', __('Something went wrong, Please try again,'));
                    }
                }
                $provider->getAccessToken();

                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('plan.get.paypal.status', [
                            $plan->id,
                            'amount' => $price,
                            'user_module' => $user_module,
                            'counter' => $counter,
                            'duration' => $duration,
                            'coupon_code' => $request->coupon_code,
                        ]),
                        "cancel_url" => route('plan.get.paypal.status', [
                            $plan->id,
                            'amount' => $price,
                            'user_module' => $user_module,
                            'counter' => $counter,
                            'duration' => $duration,
                            'coupon_code' => $request->coupon_code,

                        ]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => admin_setting('defult_currancy'),
                                "value" => $price,

                            ]
                        ]
                    ]
                ]);

                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('plans.index', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))
                        ->with('error', 'Something went wrong. OR Unknown error occurred');
                } else {
                    return redirect()
                        ->route('plans.index', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }
            } catch (\Exception $e) {

                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        } else {

            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetPaypalStatus(Request $request, $plan_id)
    {
        $user = Auth::user();
        $plan = Plan::find($plan_id);
        if ($plan) {
            $admin_settings = getAdminAllSetting();
            if ($admin_settings['company_paypal_mode'] == 'live') {
                config(
                    [
                        'paypal.live.client_id' => isset($admin_settings['company_paypal_client_id']) ? $admin_settings['company_paypal_client_id'] : '',
                        'paypal.live.client_secret' => isset($admin_settings['company_paypal_secret_key']) ? $admin_settings['company_paypal_secret_key'] : '',
                        'paypal.mode' => isset($admin_settings['company_paypal_mode']) ? $admin_settings['company_paypal_mode'] : '',
                    ]
                );
            } else {
                config(
                    [
                        'paypal.sandbox.client_id' => isset($admin_settings['company_paypal_client_id']) ? $admin_settings['company_paypal_client_id'] : '',
                        'paypal.sandbox.client_secret' => isset($admin_settings['company_paypal_secret_key']) ? $admin_settings['company_paypal_secret_key'] : '',
                        'paypal.mode' => isset($admin_settings['company_paypal_mode']) ? $admin_settings['company_paypal_mode'] : '',
                    ]
                );
            }

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            try {
                if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                    if ($response['status'] == 'COMPLETED') {
                        $statuses = __('succeeded');
                    }

                    $order = Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => !empty($plan->name) ? $plan->name : 'Basic Package',
                            'plan_id' => $plan->id,
                            'price' => !empty($request->amount) ? $request->amount : 0,
                            'price_currency' => admin_setting('defult_currancy'),
                            'txn_id' => '',
                            'payment_type' => __('PayPal'),
                            'payment_status' => $statuses,
                            'receipt' => null,
                            'user_id' => $user->id,
                        ]
                    );
                    $type = 'Subscription';
                    $user = User::find($user->id);
                    $assignPlan = $user->assignPlan($plan->id, $request->duration, $request->user_module, $request->counter);
                    if ($request->coupon_code) {

                        UserCoupon($request->coupon_code, $orderID);
                    }
                    $value = Session::get('user-module-selection');

                    event(new PaypalPaymentStatus($plan, $type, $order));

                    if (!empty($value)) {
                        Session::forget('user-module-selection');
                    }

                    if ($assignPlan['is_success']) {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                    } else {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                    }
                } else {
                    return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
                }
            } catch (\Exception $e) {
                return redirect()->route('plans.index')->with('error', __('Transaction has been failed.'));
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function coursePayWithPaypal(Request $request, $slug)
    {
        $cart = session()->get($slug);
        $products = $cart['products'];

        $store = \Workdo\LMS\Entities\Store::where('slug', $slug)->first();

        $this->paymentConfig($store->created_by, $store->workspace_id);
        $currency     = $this->currancy;
        $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

        if (!in_array($currency, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        Session::put('paypal_payment_id', $paypalToken['access_token']);
        $objUser = Auth::user();

        $total_price = 0;
        $sub_totalprice = 0;
        $product_name = [];
        $product_id = [];

        foreach ($products as $key => $product) {
            $product_name[] = $product['product_name'];
            $product_id[] = $product['id'];
            $sub_totalprice += $product['price'];
            $total_price += $product['price'];
        }

        if ($products) {
            try {
                $coupon_id = null;
                if (isset($cart['coupon']) && isset($cart['coupon'])) {
                    if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                        $discount_value = ($sub_totalprice / 100) * $cart['coupon']['coupon']['discount'];
                        $total_price = $sub_totalprice - $discount_value;
                    } else {
                        $discount_value = $cart['coupon']['coupon']['flat_discount'];
                        $total_price = $sub_totalprice - $discount_value;
                    }
                }
                if ($total_price <= 0) {
                    $assignCourse = \Workdo\LMS\Entities\LmsUtility::DirectAssignCourse($store, 'Coingate');
                    if ($assignCourse['is_success']) {
                        return redirect()->route(
                            'store-complete.complete',
                            [
                                $store->slug,
                                \Illuminate\Support\Facades\Crypt::encrypt($assignCourse['courseorder_id']),
                            ]
                        )->with('success', __('Transaction has been success'));
                    } else {
                        return redirect()->route('store.cart', $store->slug)->with('error', __('Something went wrong, Please try again,'));
                    }
                }
                $company_settings = getCompanyAllSetting($store->created_by, $store->workspace_id);
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('course.paypal', $store->slug),
                        "cancel_url" => route('course.paypal', $store->slug),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => !empty($company_settings['defult_currancy']) ? $company_settings['defult_currancy'] : 'INR',
                                "value" => $total_price,
                            ],
                        ],
                    ],
                ]);
                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('store.slug', [$store->slug])
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->route('store.slug', [$store->slug])
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }
                Session::put('paypal_payment_id', $paypalToken->id);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Unknown error occurred'));
            }
        } else {
            return redirect()->back()->with('error', __('is deleted.'));
        }
    }


    public function GetCoursePaymentStatus(Request $request, $slug)
    {
        $store = \Workdo\LMS\Entities\Store::where('slug', $slug)->first();

        $cart = session()->get($slug);
        if (isset($cart['coupon'])) {
            $coupon = $cart['coupon']['coupon'];
        }
        $products = $cart['products'];
        $sub_totalprice = 0;
        $product_name = [];
        $product_id = [];

        foreach ($products as $key => $product) {
            $product_name[] = $product['product_name'];
            $product_id[] = $product['id'];
            $sub_totalprice += $product['price'];
        }
        if (!empty($coupon)) {
            if ($coupon['enable_flat'] == 'off') {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice = $sub_totalprice - $discount_value;
            } else {
                $discount_value = $coupon['flat_discount'];
                $totalprice = $sub_totalprice - $discount_value;
            }
        }
        if ($products) {
            $this->paymentConfig($store->created_by, $store->workspace_id);
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $payment_id = Session::get('paypal_payment_id');
            try {
                $order = new \Workdo\LMS\Entities\CourseOrder();
                $latestOrder = \Workdo\LMS\Entities\CourseOrder::orderBy('created_at', 'DESC')->first();
                if (!empty($latestOrder)) {
                    $order->order_nr = '#' . str_pad($latestOrder->id + 1, 4, "100", STR_PAD_LEFT);
                } else {
                    $order->order_nr = '#' . str_pad(1, 4, "100", STR_PAD_LEFT);
                }

                $statuses = '';
                if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                    if ($response['status'] == 'COMPLETED') {
                        $statuses = __('successful');
                    }
                    $company_settings = getCompanyAllSetting($store->created_by, $store->workspace_id);

                    $student = Auth::guard('students')->user();
                    $course_order = new \Workdo\LMS\Entities\CourseOrder();
                    $course_order->order_id = '#' . time();
                    $course_order->name = $student->name;
                    $course_order->card_number = '';
                    $course_order->card_exp_month = '';
                    $course_order->card_exp_year = '';
                    $course_order->student_id = $student->id;
                    $course_order->course = json_encode($products);
                    $course_order->price = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                    $course_order->coupon = !empty($cart['coupon']['coupon']['id']) ? $cart['coupon']['coupon']['id'] : '';
                    $course_order->coupon_json = json_encode(!empty($coupon) ? $coupon : '');
                    $course_order->discount_price = !empty($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                    $course_order->price_currency = !empty($company_settings['defult_currancy']) ? $company_settings['defult_currancy'] : 'USD';
                    $course_order->txn_id = $payment_id;
                    $course_order->payment_type = __('PayPal');
                    $course_order->payment_status = $statuses;
                    $course_order->receipt = '';
                    $course_order->store_id = $store['id'];
                    $course_order->save();

                    foreach ($products as $course_id) {
                        $purchased_course = new \Workdo\LMS\Entities\PurchasedCourse();
                        $purchased_course->course_id = $course_id['product_id'];
                        $purchased_course->student_id = $student->id;
                        $purchased_course->order_id = $course_order->id;
                        $purchased_course->save();

                        $student = \Workdo\LMS\Entities\Student::where('id', $purchased_course->student_id)->first();
                        $student->courses_id = $purchased_course->course_id;
                        $student->save();
                    }

                    $type = 'coursepayment';

                    if (!empty($company_settings['New Course Order']) && $company_settings['New Course Order'] == true) {
                        $course = \Workdo\LMS\Entities\Course::whereIn('id', $product_id)->get()->pluck('title');
                        $course_name = implode(', ', $course->toArray());
                        $user = User::where('id', $store->created_by)->where('workspace_id', $store->workspace_id)->first();
                        $uArr = [
                            'student_name' => $student->name,
                            'course_name' => $course_name,
                            'store_name' => $store->name,
                            'order_url' => route('user.order', [$store->slug, \Illuminate\Support\Facades\Crypt::encrypt($course_order->id),]),
                        ];
                        try {
                            // Send Email
                            $resp = EmailTemplate::sendEmailTemplate('New Course Order', [$user->id => $user->email], $uArr, $store->created_by);
                        } catch (\Exception $e) {
                            $resp['error'] = $e->getMessage();
                        }
                    }

                    event(new PaypalPaymentStatus($store, $type, $course_order));
                    session()->forget($slug);

                    return redirect()->route(
                        'store-complete.complete',
                        [
                            $store->slug,
                            \Illuminate\Support\Facades\Crypt::encrypt($course_order->id),
                        ]
                    )->with('success', __('Transaction has been') . ' ' . $statuses);
                } else {
                    return redirect()->back()->with('error', __('Transaction has been') . ' ' . $statuses);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Transaction has been failed.'));
            }
        } else {
            return redirect()->back()->with('error', __('is deleted.'));
        }
    }

    public function contentPayWithPaypal(Request $request, $slug)
    {
        $cart = session()->get($slug);
        $products = $cart['products'];

        $store = \Workdo\TVStudio\Entities\TVStudioStore::where('slug', $slug)->first();

        $this->paymentConfig($store->created_by, $store->workspace_id);
        $currency     = $this->currancy;
        $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

        if (!in_array($currency, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        Session::put('paypal_payment_id', $paypalToken['access_token']);
        $objUser = Auth::user();

        $total_price = 0;
        $sub_totalprice = 0;
        $product_name = [];
        $product_id = [];

        foreach ($products as $key => $product) {
            $product_name[] = $product['product_name'];
            $product_id[] = $product['id'];
            $sub_totalprice += $product['price'];
            $total_price += $product['price'];
        }

        if ($products) {
            try {
                $coupon_id = null;
                if (isset($cart['coupon']) && isset($cart['coupon'])) {
                    if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                        $discount_value = ($sub_totalprice / 100) * $cart['coupon']['coupon']['discount'];
                        $total_price = $sub_totalprice - $discount_value;
                    } else {
                        $discount_value = $cart['coupon']['coupon']['flat_discount'];
                        $total_price = $sub_totalprice - $discount_value;
                    }
                }
                if ($total_price <= 0) {
                    $assignCourse = \Workdo\TVStudio\Entities\TVStudioUtility::DirectAssignCourse($store, 'Coingate');
                    if ($assignCourse['is_success']) {
                        return redirect()->route(
                            'store-complete.complete',
                            [
                                $store->slug,
                                \Illuminate\Support\Facades\Crypt::encrypt($assignCourse['courseorder_id']),
                            ]
                        )->with('success', __('Transaction has been success'));
                    } else {
                        return redirect()->route('store.cart', $store->slug)->with('error', __('Something went wrong, Please try again,'));
                    }
                }
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('content.paypal', $store->slug),
                        "cancel_url" => route('content.paypal', $store->slug),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => !empty(company_setting('defult_currancy', $store->created_by, $store->workspace_id)) ? company_setting('defult_currancy', $store->created_by, $store->workspace_id) : 'INR',
                                "value" => $total_price,
                            ],
                        ],
                    ],
                ]);
                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('store.slug', [$store->slug])
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->route('store.slug', [$store->slug])
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }
                Session::put('paypal_payment_id', $paypalToken->id);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Unknown error occurred'));
            }
        } else {
            return redirect()->back()->with('error', __('is deleted.'));
        }
    }


    public function GetContentPaymentStatus(Request $request, $slug)
    {
        $store = \Workdo\TVStudio\Entities\TVStudioStore::where('slug', $slug)->first();

        $cart = session()->get($slug);
        if (isset($cart['coupon'])) {
            $coupon = $cart['coupon']['coupon'];
        }
        $products = $cart['products'];
        $sub_totalprice = 0;
        $product_name = [];
        $product_id = [];

        foreach ($products as $key => $product) {
            $product_name[] = $product['product_name'];
            $product_id[] = $product['id'];
            $sub_totalprice += $product['price'];
        }
        if (!empty($coupon)) {
            if ($coupon['enable_flat'] == 'off') {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice = $sub_totalprice - $discount_value;
            } else {
                $discount_value = $coupon['flat_discount'];
                $totalprice = $sub_totalprice - $discount_value;
            }
        }
        if ($products) {

            $this->paymentConfig($store->created_by, $store->workspace_id);
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $payment_id = Session::get('paypal_payment_id');
            try {
                $order = new \Workdo\TVStudio\Entities\TVStudioOrder();
                $latestOrder = \Workdo\TVStudio\Entities\TVStudioOrder::orderBy('created_at', 'DESC')->first();
                if (!empty($latestOrder)) {
                    $order->order_nr = '#' . str_pad($latestOrder->id + 1, 4, "100", STR_PAD_LEFT);
                } else {
                    $order->order_nr = '#' . str_pad(1, 4, "100", STR_PAD_LEFT);
                }

                $statuses = '';

                if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                    if ($response['status'] == 'COMPLETED') {
                        $statuses = __('successful');
                    }

                    $customer = Auth::guard('customers')->user();
                    $content_order = new \Workdo\TVStudio\Entities\TVStudioOrder();
                    $content_order->order_id = '#' . time();
                    $content_order->name = $customer->name;
                    $content_order->card_number = '';
                    $content_order->card_exp_month = '';
                    $content_order->card_exp_year = '';
                    $content_order->customer_id = $customer->id;
                    $content_order->content = json_encode($products);
                    $content_order->price = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                    $content_order->coupon = !empty($cart['coupon']['coupon']['id']) ? $cart['coupon']['coupon']['id'] : '';
                    $content_order->coupon_json = json_encode(!empty($coupon) ? $coupon : '');
                    $content_order->discount_price = !empty($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                    $content_order->price_currency = !empty(company_setting('defult_currancy', $store->created_by, $store->workspace_id)) ? company_setting('defult_currancy', $store->created_by, $store->workspace_id) : 'USD';
                    $content_order->txn_id = $payment_id;
                    $content_order->payment_type = __('PayPal');
                    $content_order->payment_status = $statuses;
                    $content_order->receipt = '';
                    $content_order->store_id = $store['id'];
                    $content_order->save();

                    $product = $content_order->content;

                    $products = json_decode($product);
                    foreach ($products as $content_id) {

                        $purchased_content = new \Workdo\TVStudio\Entities\TVStudioPurchasedContent();
                        $purchased_content->content_id = $content_id->product_id;
                        $purchased_content->customer_id = $customer->id;
                        $purchased_content->order_id = $content_order->id;

                        $purchased_content->save();

                        $customer = \Workdo\TVStudio\Entities\TVStudioCustomer::where('id', $purchased_content->customer_id)->first();
                        $customer->contents_id = $purchased_content->content_id;

                        $customer->save();
                    }

                    session()->forget($slug);
                    $type = 'contentpayment';
                    event(new PaypalPaymentStatus($store, $type ,$content_order));
                    return redirect()->route(
                        'tv.store-complete.complete',
                        [
                            $store->slug,
                            \Illuminate\Support\Facades\Crypt::encrypt($content_order->id),
                        ]
                    )->with('success', __('Transaction has been') . ' ' . $statuses);
                } else {
                    return redirect()->back()->with('error', __('Transaction has been') . ' ' . $statuses);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Transaction has been failed.'));
            }
        } else {
            return redirect()->back()->with('error', __('is deleted.'));
        }
    }

    // Holidayz

    public function BookingPayWithPaypal(Request $request, $slug)
    {
        $hotel = Hotels::where('slug', $slug)->first();
        if ($hotel) {
            $grandTotal = 0;
            if (!auth()->guard('holiday')->user()) {
                $Carts = Cookie::get('cart');
                $Carts = json_decode($Carts, true);
                foreach ($Carts as $key => $value) {
                    //
                    $toDate = \Carbon\Carbon::parse($value['check_in']);
                    $fromDate = \Carbon\Carbon::parse($value['check_out']);

                    $days = $toDate->diffInDays($fromDate);
                    //
                    $grandTotal += $value['price'] * $value['room'] * $days;
                    $grandTotal += ($value['serviceCharge']) ? $value['serviceCharge'] : 0;
                }
            } else {
                $Carts = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->get();
                foreach ($Carts as $key => $value) {
                    $grandTotal += $value->price;   // * $value->room
                    $grandTotal += ($value->service_charge) ? $value->service_charge : 0;
                }
            }

            try {
                $this->paymentConfig($hotel->created_by, $hotel->workspace);
                $currency     = $this->currancy;
                $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

                if (!in_array($currency, $supported_currencies)) {
                    return redirect()->back()->with('error', __('Currency is not supported.'));
                }
                $provider = new PayPalClient;
                $provider->setApiCredentials(config('paypal'));
                $coupon_id = null;
                $get_amount = $grandTotal;
                if (!empty($request->coupon)) {
                    $coupons = BookingCoupons::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        $discount_value = ($get_amount / 100) * $coupons->discount;
                        $get_amount = $get_amount - $discount_value;
                        $coupon_id = $coupons->id;
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                $get_amount = number_format((float) $get_amount, 2, '.', '');
                session()->put('guestInfo', $request->only(['firstname', 'email', 'address', 'country', 'lastname', 'phone', 'city', 'zipcode']));
                if ($get_amount <= 0) {
                    return $this->GetBookingPaymentStatus($request, $slug, $get_amount, $coupon_id);
                }

                $paypalToken = $provider->getAccessToken();


                $data = [$slug, $get_amount, 0];
                if ($coupon_id) {
                    $data = [$slug, $get_amount, $coupon_id];
                }

                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('booking.get.payment.status.paypal', $data),
                        "cancel_url" => route('booking.get.payment.status.paypal', $data),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => !empty(company_setting('defult_currancy', $hotel->created_by, $hotel->workspace)) ? company_setting('defult_currancy', $hotel->created_by, $hotel->workspace) : '$',
                                "value" => $get_amount
                            ]
                        ]
                    ]
                ]);

                if (isset($response['id']) && $response['id'] != null) {
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()->back()->with('error', $response['message'] ?? 'Something went wrong.');
                } else {
                    return redirect()->back()->with('error', $response['message'] ?? 'Something went wrong.');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', __('Hotel Not found.'));
        }
    }

    public function GetBookingPaymentStatus(Request $request, $slug, $price, $coupon_id = 0)
    {
        $hotel = Hotels::where(['slug' => $slug, 'is_active' => 1])->first();
        if ($hotel) {
            $guestDetails = session()->get('guestInfo');
            $this->paymentConfig($hotel->created_by, $hotel->workspace);
            if (!auth()->guard('holiday')->user()) {
                try {
                    $Carts = Cookie::get('cart');
                    $Carts = json_decode($Carts, true);
                    $coupons = BookingCoupons::find($coupon_id);
                    if (!empty($coupons)) {
                        $userCoupon = new UsedBookingCoupons();
                        $userCoupon->customer_id = isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0;
                        $userCoupon->coupon_id = $coupons->id;
                        $userCoupon->save();
                    }
                    if ($price <= 0) {
                        $booking_number = \Workdo\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                        $booking = RoomBooking::create([
                            'booking_number' => $booking_number,
                            'user_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                            'payment_method' => __('PAYPAL'),
                            'payment_status' => 1,
                            'invoice' => null,
                            'workspace' => $hotel->workspace,
                            'created_by' => $hotel->created_by,
                            'first_name' => $guestDetails['firstname'],
                            'last_name' => $guestDetails['lastname'],
                            'email' => $guestDetails['email'],
                            'phone' => $guestDetails['phone'],
                            'address' => $guestDetails['address'],
                            'city' => $guestDetails['city'],
                            'country' => ($guestDetails['country']) ? $guestDetails['country'] : 'india',
                            'zipcode' => $guestDetails['zipcode'],
                            'total' => $price,
                            'coupon_id' => ($coupon_id) ? $coupon_id : 0,
                        ]);
                        foreach ($Carts as $key => $value) {
                            //
                            $toDate = \Carbon\Carbon::parse($value['check_in']);
                            $fromDate = \Carbon\Carbon::parse($value['check_out']);

                            $days = $toDate->diffInDays($fromDate);
                            //
                            $bookingOrder = RoomBookingOrder::create([
                                'booking_id' => $booking->id,
                                'customer_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                                'room_id' => $value['room_id'],
                                'workspace' => $value['workspace'],
                                'check_in' => $value['check_in'],
                                'check_out' => $value['check_out'],
                                'price' => $value['price'] * $value['room'] * $days,
                                'room' => $value['room'],
                                'service_charge' => $value['serviceCharge'],
                                'services' => $value['serviceIds'],
                            ]);
                            unset($Carts[$key]);
                        }
                        $cart_json = json_encode($Carts);
                        Cookie::queue('cart', $cart_json, 1440);
                        session()->forget('guestInfo');

                        event(new CreateRoomBooking($request, $booking));
                        $type = "roombookinginvoice";
                        event(new PaypalPaymentStatus($hotel, $type, $booking));

                        //Email notification
                        if (!empty(company_setting('New Room Booking By Hotel Customer', $hotel->created_by, $hotel->workspace)) && company_setting('New Room Booking By Hotel Customer', $hotel->created_by, $hotel->workspace) == true) {
                            $user = User::where('id', $hotel->created_by)->first();
                            $customer = HotelCustomer::find($booking->user_id);
                            $room = \Workdo\Holidayz\Entities\Rooms::find($bookingOrder->room_id);
                            $uArr = [
                                'hotel_customer_name' => isset($customer->name) ? $customer->name : $booking->first_name,
                                'invoice_number' => $booking->booking_number,
                                'check_in_date' => $bookingOrder->check_in,
                                'check_out_date' => $bookingOrder->check_out,
                                'room_type' => $room->type,
                                'hotel_name' => $hotel->name,
                            ];

                            try {
                                $resp = EmailTemplate::sendEmailTemplate('New Room Booking By Hotel Customer', [$user->email], $uArr);
                            } catch (\Exception $e) {
                                $resp['error'] = $e->getMessage();
                            }

                            return redirect()->route('hotel.home', $slug)->with('success', __('Booking Successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                        }
                        return redirect()->route('hotel.home', $slug)->with('success', 'Booking Successfully. email notification is off.');
                        return redirect()->route('hotel.home', $slug)->with('success', __('Transaction Complete.'));
                    } else {
                        $provider = new PayPalClient;
                        $provider->setApiCredentials(config('paypal'));
                        $provider->getAccessToken();
                        $response = $provider->capturePaymentOrder($request['token']);
                        $payment_id = Session::get('paypal_payment_id');

                        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                            $booking_number = \Workdo\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                            $booking = RoomBooking::create([
                                'booking_number' => $booking_number,
                                'user_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                                'payment_method' => __('PAYPAL'),
                                'payment_status' => 1,
                                'invoice' => null,
                                'workspace' => $hotel->workspace,
                                'created_by' => $hotel->created_by,
                                'first_name' => $guestDetails['firstname'],
                                'last_name' => $guestDetails['lastname'],
                                'email' => $guestDetails['email'],
                                'phone' => $guestDetails['phone'],
                                'address' => $guestDetails['address'],
                                'city' => $guestDetails['city'],
                                'country' => ($guestDetails['country']) ? $guestDetails['country'] : 'india',
                                'zipcode' => $guestDetails['zipcode'],
                                'total' => $price,
                                'coupon_id' => ($coupon_id) ? $coupon_id : 0,
                            ]);
                            foreach ($Carts as $key => $value) {
                                //
                                $toDate = \Carbon\Carbon::parse($value['check_in']);
                                $fromDate = \Carbon\Carbon::parse($value['check_out']);

                                $days = $toDate->diffInDays($fromDate);
                                //
                                $bookingOrder = RoomBookingOrder::create([
                                    'booking_id' => $booking->id,
                                    'customer_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                                    'room_id' => $value['room_id'],
                                    'workspace' => $value['workspace'],
                                    'check_in' => $value['check_in'],
                                    'check_out' => $value['check_out'],
                                    'price' => $value['price'] * $value['room'] * $days,
                                    'room' => $value['room'],
                                    'service_charge' => $value['serviceCharge'],
                                    'services' => $value['serviceIds'],
                                ]);
                                unset($Carts[$key]);
                            }
                            $cart_json = json_encode($Carts);
                            Cookie::queue('cart', $cart_json, 1440);
                            session()->forget('guestInfo');

                            event(new CreateRoomBooking($request, $booking));
                            $type = "roombookinginvoice";
                            event(new PaypalPaymentStatus($hotel, $type, $booking));

                            //Email notification
                            if (!empty(company_setting('New Room Booking By Hotel Customer', $hotel->created_by, $hotel->workspace)) && company_setting('New Room Booking By Hotel Customer', $hotel->created_by, $hotel->workspace) == true) {
                                $user = User::where('id', $hotel->created_by)->first();
                                $customer = HotelCustomer::find($booking->user_id);
                                $room = \Workdo\Holidayz\Entities\Rooms::find($bookingOrder->room_id);
                                $uArr = [
                                    'hotel_customer_name' => isset($customer->name) ? $customer->name : $booking->first_name,
                                    'invoice_number' => $booking->booking_number,
                                    'check_in_date' => $bookingOrder->check_in,
                                    'check_out_date' => $bookingOrder->check_out,
                                    'room_type' => $room->type,
                                    'hotel_name' => $hotel->name,
                                ];

                                try {
                                    $resp = EmailTemplate::sendEmailTemplate('New Room Booking By Hotel Customer', [$user->email], $uArr);
                                } catch (\Exception $e) {
                                    $resp['error'] = $e->getMessage();
                                }

                                return redirect()->route('hotel.home', $slug)->with('success', __('Booking Successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                            }
                            return redirect()->route('hotel.home', $slug)->with('success', 'Booking Successfully. email notification is off.');
                            return redirect()->route('hotel.home', $slug)->with('success', __('Transaction Complete.'));
                        } else {
                            return redirect()->back()->with('error', __('Transaction Fail Please try again.'));
                        }
                    }
                } catch (\Exception $e) {
                    return redirect()->route('hotel.home', $slug)->with('error', __('Transaction Fail.'));
                }
            } else {
                $Carts = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->get();
                $coupons = BookingCoupons::find($coupon_id);

                if (!empty($coupons)) {
                    $userCoupon = new UsedBookingCoupons();
                    $userCoupon->customer_id = isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0;
                    $userCoupon->coupon_id = $coupons->id;
                    $userCoupon->save();
                }
                if ($price <= 0) {
                    $booking_number = \Workdo\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                    $booking = RoomBooking::create([
                        'booking_number' => $booking_number,
                        'user_id' => auth()->guard('holiday')->user()->id,
                        'payment_method' => __('PAYPAL'),
                        'payment_status' => 1,
                        'invoice' => null,
                        'workspace' => $hotel->workspace,
                        'created_by' => $hotel->created_by,
                        'total' => $price,
                        'coupon_id' => ($coupon_id) ? $coupon_id : 0,
                    ]);
                    foreach ($Carts as $key => $value) {
                        $bookingOrder = RoomBookingOrder::create([
                            'booking_id' => $booking->id,
                            'customer_id' => auth()->guard('holiday')->user()->id,
                            'room_id' => $value->room_id,
                            'workspace' => $value->workspace,
                            'check_in' => $value->check_in,
                            'check_out' => $value->check_out,
                            'price' => $value->price,   // * $value->room
                            'room' => $value->room,
                            'service_charge' => $value->service_charge,
                            'services' => $value->services,
                        ]);
                    }
                    RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->delete();

                    event(new CreateRoomBooking($request, $booking));
                    $type = "roombookinginvoice";
                    event(new PaypalPaymentStatus($hotel, $type, $booking));

                    //Email notification
                    if (!empty(company_setting('New Room Booking By Hotel Customer', $hotel->created_by, $hotel->workspace)) && company_setting('New Room Booking By Hotel Customer', $hotel->created_by, $hotel->workspace) == true) {
                        $user = User::where('id', $hotel->created_by)->first();
                        $customer = HotelCustomer::find($booking->user_id);
                        $room = \Workdo\Holidayz\Entities\Rooms::find($bookingOrder->room_id);
                        $uArr = [
                            'hotel_customer_name' => isset($customer->name) ? $customer->name : $booking->first_name,
                            'invoice_number' => $booking->booking_number,
                            'check_in_date' => $bookingOrder->check_in,
                            'check_out_date' => $bookingOrder->check_out,
                            'room_type' => $room->type,
                            'hotel_name' => $hotel->name,
                        ];

                        try {
                            $resp = EmailTemplate::sendEmailTemplate('New Room Booking By Hotel Customer', [$user->email], $uArr);
                        } catch (\Exception $e) {
                            $resp['error'] = $e->getMessage();
                        }

                        return redirect()->route('hotel.home', $slug)->with('success', __('Booking Successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                    }
                    return redirect()->route('hotel.home', $slug)->with('success', 'Booking Successfully. email notification is off.');
                    return redirect()->route('hotel.home', $slug)->with('success', __('Transaction Complete.'));
                } else {
                    $provider = new PayPalClient;
                    $provider->setApiCredentials(config('paypal'));
                    $provider->getAccessToken();
                    $response = $provider->capturePaymentOrder($request['token']);
                    $payment_id = Session::get('paypal_payment_id');
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                        $booking_number = \Workdo\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                        $booking = RoomBooking::create([
                            'booking_number' => $booking_number,
                            'user_id' => auth()->guard('holiday')->user()->id,
                            'payment_method' => __('PAYPAL'),
                            'payment_status' => 1,
                            'invoice' => null,
                            'workspace' => $hotel->workspace,
                            'created_by' => $hotel->created_by,
                            'total' => $price,
                            'coupon_id' => ($coupon_id) ? $coupon_id : 0,
                        ]);
                        foreach ($Carts as $key => $value) {
                            $bookingOrder = RoomBookingOrder::create([
                                'booking_id' => $booking->id,
                                'customer_id' => auth()->guard('holiday')->user()->id,
                                'room_id' => $value->room_id,
                                'workspace' => $value->workspace,
                                'check_in' => $value->check_in,
                                'check_out' => $value->check_out,
                                'price' => $value->price,   // * $value->room
                                'room' => $value->room,
                                'service_charge' => $value->service_charge,
                                'services' => $value->services,
                            ]);
                        }
                        RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->delete();

                        event(new CreateRoomBooking($request, $booking));
                        $type = "roombookinginvoice";
                        event(new PaypalPaymentStatus($hotel, $type, $booking));

                        //Email notification
                        if (!empty(company_setting('New Room Booking By Hotel Customer', $hotel->created_by, $hotel->workspace)) && company_setting('New Room Booking By Hotel Customer', $hotel->created_by, $hotel->workspace) == true) {
                            $user = User::where('id', $hotel->created_by)->first();
                            $customer = HotelCustomer::find($booking->user_id);
                            $room = \Workdo\Holidayz\Entities\Rooms::find($bookingOrder->room_id);
                            $uArr = [
                                'hotel_customer_name' => isset($customer->name) ? $customer->name : $booking->first_name,
                                'invoice_number' => $booking->booking_number,
                                'check_in_date' => $bookingOrder->check_in,
                                'check_out_date' => $bookingOrder->check_out,
                                'room_type' => $room->type,
                                'hotel_name' => $hotel->name,
                            ];

                            try {
                                $resp = EmailTemplate::sendEmailTemplate('New Room Booking By Hotel Customer', [$user->email], $uArr);
                            } catch (\Exception $e) {
                                $resp['error'] = $e->getMessage();
                            }

                            return redirect()->route('hotel.home', $slug)->with('success', __('Booking Successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                        }
                        return redirect()->route('hotel.home', $slug)->with('success', 'Booking Successfully. email notification is off.');
                        return redirect()->route('hotel.home', $slug)->with('success', __('Transaction Complete.'));
                    } else {
                        return redirect()->back()->with('error', __('Transaction Fail Please try again.'));
                    }
                }
            }
        }
    }


    public function propertyPayWithPaypal(Request $request)
    {
        $invoice = \Workdo\PropertyManagement\Entities\PropertyInvoice::find($request->invoice_id);
        $user_id = $invoice->created_by;
        $wokspace = $invoice->workspace;
        self::paymentConfig($user_id, $wokspace);
        $currency     = $this->currancy;
        $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

        if (!in_array($currency, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }
        if (isset($this->enable_paypal) && $this->enable_paypal == 'on') {

            $tenant = Auth::user();
            $user = User::find($tenant->user_id);

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));

            if ($invoice) {

                $invoiceID = $invoice->id;

                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('property.get.paypal.status', ['invoice_id' => encrypt($invoiceID), 'amount' => $invoice->total_amount]),
                        "cancel_url" => route('property.get.paypal.status', ['invoice_id' => encrypt($invoiceID), 'amount' => $invoice->total_amount]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => $this->currancy = company_setting('defult_currancy', $user_id),
                                "value" => $invoice->total_amount
                            ]
                        ]
                    ]
                ]);

                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()->back()->with('error', 'Something went wrong.');
                } else {
                    return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', $response['message'] ?? 'Something went wrong.');
                }

                return redirect()->back()->with('error', __('Unknown error occurred'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Please Enter Paypal Details.'));
        }
    }

    public function propertyGetPaypalStatus(Request $request)
    {
        $invoice_id = decrypt($request->invoice_id);
        $invoice = \Workdo\PropertyManagement\Entities\PropertyInvoice::find($invoice_id);
        $this->paymentConfig($invoice->created_by, $invoice->workspace);
        $this->invoiceData = $invoice;

        if ($invoice) {
            $payment_id = Session::get('paypal_payment_id');
            Session::forget('paypal_payment_id');
            if (empty($request->PayerID || empty($request->token))) {
                return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Payment failed'));
            }
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            try {
                $tenant = \Workdo\PropertyManagement\Entities\Tenant::where('user_id', Auth::user()->id)->first();

                $invoice_payment = new \Workdo\PropertyManagement\Entities\PropertyInvoicePayment();
                $invoice_payment->invoice_id = $invoice_id;
                $invoice_payment->user_id = $tenant->id;
                $invoice_payment->date = date('Y-m-d');
                $invoice_payment->amount = isset($request->amount) ? $request->amount : 0;
                $invoice_payment->payment_type = __('PayPal');
                $invoice_payment->receipt = '';
                $invoice_payment->save();


                $invoice->status = 'Paid';
                $invoice->save();

                $type = 'propertyinvoice';
                event(new PaypalPaymentStatus($invoice, $type, $invoice_payment));

                //Email notification
                if (!empty(company_setting('Property Invoice Payment Create', $invoice->created_by, $invoice->workspace)) && company_setting('Property Invoice Payment Create', $invoice->created_by, $invoice->workspace) == true) {
                    // $user = User::where('id',$invoice->created_by)->first();
                    $user = User::find(Auth::user()->id);
                    $uArr = [
                        'payment_name' => isset(Auth::user()->name) ? Auth::user()->name : '',
                        'invoice_number' => \Workdo\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoice_id),
                        'payment_amount' => $invoice_payment->amount,
                        'payment_date' => $invoice_payment->date,
                    ];

                    try {
                        $resp = EmailTemplate::sendEmailTemplate('Property Invoice Payment Create', [$user->email], $uArr);
                    } catch (\Exception $e) {
                        $resp['error'] = $e->getMessage();
                    }

                    return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Invoice paid Successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                }

                return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Invoice paid Successfully! email notification is off.'));
            } catch (\Exception $e) {
                return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', $e->getMessage());
            }
        } else {
            return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Invoice not found.'));
        }
    }

    public function vehicleBookingWithPaypal(Request $request, $slug, $lang = '')
    {
        if ($lang == '') {
            $lang = 'en';
        }
        \App::setLocale($lang);
        $workspace = WorkSpace::where('slug', $slug)->first();
        if ($workspace) {
            try {
                $this->paymentConfig($workspace->created_by, $workspace->id);
                $currency     = $this->currancy;
                $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

                if (!in_array($currency, $supported_currencies)) {
                    return redirect()->back()->with('error', __('Currency is not supported.'));
                }
                $provider = new PayPalClient;
                $provider->setApiCredentials(config('paypal'));
                $get_amount = $request->total_price;
                $route_id = intval($request->route_id);
                $get_amount = number_format((float) $get_amount, 2, '.', '');

                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        'return_url' => url('vehicle-booking-payment/paypal/status') . '?' . http_build_query([
                            'slug' => $slug,
                            'lang' => $lang,
                            'amount' => $get_amount,
                            'request_data' => $request->all(),
                            'route_id' => intval($request->route_id),
                        ]),
                        "cancel_url" => url('vehicle-booking-payment/paypal/status', [
                            'slug' => $slug,
                            'lang' => $lang,
                            'amount' => $get_amount,
                        ]),
                    ],
                    "purchase_units" => [
                        [
                            "amount" => [
                                "currency_code" => !empty(company_setting('defult_currancy', $workspace->created_by, $workspace->id)) ? company_setting('defult_currancy', $workspace->created_by, $workspace->id) : 'USD',
                                "value" => $get_amount
                            ]
                        ]
                    ]
                ]);
                if (isset($response['id']) && $response['id'] != null) {
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()->route('vehicle.booking.manage', [$slug,$lang])->with('error', $response['message'] ?? 'Something went wrong.');
                } else {
                    return redirect()->route('vehicle.booking.manage', [$slug,$lang])->with('error', $response['message'] ?? 'Something went wrong.');
                }
            } catch (\Exception $e) {
                return redirect()->route('vehicle.booking.manage', [$slug,$lang])->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('vehicle.booking.manage', [$slug,$lang])->with('error', __('Payment Not found.'));
        }
    }

    public function vehicleBookingStatus(Request $request)
    {
        try {
            $workspace = WorkSpace::where('slug', $request->slug)->first();
            $this->paymentConfig($workspace->created_by, $workspace->id);

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $payment_id = Session::get('paypal_payment_id');

            try {
                if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                    if ($response['status'] == 'COMPLETED') {
                        $request_data   = $request->request_data;
                        $vehicle_route  = json_decode($request_data['vehicle_route'], true);
                        $selectedSeats  = explode(',', $request_data['selectedSeats']);
                        $bookingData    = [];
                        foreach ($selectedSeats as $index => $seatNumber) {
                            $userData = $request_data['users'][$index + 1] ?? null;

                            if ($userData) {
                                $booking = Booking::create([
                                    'vehicle_id'    => $vehicle_route['vehicle_id'],
                                    'route_id'      => $request_data['route_id'],
                                    'date'          => $request_data['trip_date'],
                                    'seat_number'   => $seatNumber,
                                    'user_name'     => $userData['name'],
                                    'age'           => $userData['age'],
                                    'status'        => 'Paid',
                                    'email_id'      => $request_data['email'],
                                    'from'          => $vehicle_route['starting_point'],
                                    'to'            => $vehicle_route['dropping_point'],
                                    'workspace'     => $workspace->id,
                                    'created_by'    => $workspace->created_by,
                                ]);
                                $bookingData[] = [
                                    'booking_id'    => $booking->id,
                                    'seat_number'   => $seatNumber,
                                ];
                            }
                        }
                        $bookingIds     = [];
                        $seatNumbers    = [];
                        foreach ($bookingData as $bookingInfo) {
                            $bookingIds[]   = $bookingInfo['booking_id'];
                            $seatNumbers[]  = $bookingInfo['seat_number'];
                        }

                        // Convert arrays to comma-separated strings
                        $bookingIdsString   = implode(',', $bookingIds);
                        $seatNumbersString  = implode(',', $seatNumbers);

                        $payment = VehicleBookingPayments::create([
                            'booking_id'    => $bookingIdsString,
                            'seat_number'   => $seatNumbersString,
                            'payment_type'  => __('PayPal'),
                            'amount'        => $request_data['total_price'],
                            'payment_date'  => now(),
                            'workspace'     => $workspace->id,
                            'created_by'    => $workspace->created_by,
                        ]);

                        $type = 'vehiclebookingpayment';

                        event(new PaypalPaymentStatus($request_data, $type, $payment));

                        return redirect()->route('vehicle.booking.checkout', [$request->slug, $payment->booking_id,$request->lang])->with('success', __('Payment added Successfully'));
                    }
                } else {
                    return redirect()->route('vehicle.booking.manage', [$request->slug,$request->lang])->with('error', __('Payment Fail.'));
                }
            } catch (Exception $e) {

                return redirect()->route('vehicle.booking.manage', [$request->slug,$request->lang])->with('error', __('Something Wrent Wrong.'));
            }
        } catch (\Exception $exception) {
	    	return redirect()->route('vehicle.booking.manage', [$request->slug,$request->lang])->with('error', __('Something Wrent Wrong.'));
        }
    }

    public function memberplanPayWithpaypal(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['amount' => 'required|numeric', 'membershipplan_id' => 'required']
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $membershipplan = \Workdo\GymManagement\Entities\AssignMembershipPlan::where('id', $request->membershipplan_id)->first();
        $this->paymentConfig($membershipplan->created_by, $membershipplan->workspace);
        $currency     = $this->currancy;
        $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

        if (!in_array($currency, $supported_currencies)) {
            return redirect()->back()->with('error', __('Currency is not supported.'));
        }
        $get_amount = $request->amount;
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));

        if ($membershipplan) {

            $paypalToken = $provider->getAccessToken();
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('memberplan.paypal', [$membershipplan->id, $get_amount]),
                    "cancel_url" =>  route('memberplan.paypal', [$membershipplan->id, $get_amount]),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => $this->currancy = company_setting('defult_currancy', $membershipplan->created_by, $membershipplan->workspace),

                            "value" => $get_amount
                        ]
                    ]
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                // redirect to approve href
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        return redirect()->away($links['href']);
                    }
                }
                return redirect()->back()->with('error', 'Something went wrong.');
            } else {
                return redirect()->route('pay.membership.plan', encrypt($membershipplan->user_id))->with('error', $response['message'] ?? 'Something went wrong.');
            }

            return redirect()->back()->with('error', __('Unknown error occurred'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getMemberPlanPaymentStatus(Request $request, $membershipplan_id, $amount)
    {
        $membershipplan = \Workdo\GymManagement\Entities\AssignMembershipPlan::where('id', $membershipplan_id)->first();
        $this->paymentConfig($membershipplan->created_by, $membershipplan->workspace);

        if ($membershipplan) {
            $payment_id = Session::get('paypal_payment_id');
            Session::forget('paypal_payment_id');

            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            try {
                $membershipplan_payment                  = new \Workdo\GymManagement\Entities\MembershipPlanPayment();
                $membershipplan_payment->member_id       = !empty($membershipplan->member_id) ? $membershipplan->member_id : null;
                $membershipplan_payment->user_id         = $membershipplan->user_id;
                $membershipplan_payment->date            = date('Y-m-d');
                $membershipplan_payment->amount          = isset($amount) ? $amount : 0;
                $membershipplan_payment->order_id        = $orderID;
                $membershipplan_payment->currency        = isset($get_data['currency']) ? $get_data['currency'] : 'INR';
                $membershipplan_payment->payment_type    = __('PayPal');
                $membershipplan_payment->receipt         = '';
                $membershipplan_payment->save();

                $type = 'membershipplan';
                event(new PaypalPaymentStatus($membershipplan, $type, $membershipplan_payment));

                return redirect()->route('pay.membership.plan', encrypt($membershipplan->user_id))->with('success', __('Payment added Successfully!'));
            } catch (\Exception $e) {
                return redirect()->route('pay.membership.plan', encrypt($membershipplan->user_id))->with('success', $e->getMessage());
            }
        } else {
            return redirect()->route('pay.membership.plan', encrypt($membershipplan->user_id))->with('success', __('Membership Plan not found.'));
        }
    }

    // Beauty Spa Module
    public function BeautySpaPayWithPaypal(Request $request, $slug)
    {
        $service = BeautyService::find($request->service_id);

        $price = $service->price * $request->person;
        $request->$price = $price;

        if ($price > 0) {
            $this->paymentConfig($service->created_by, $service->workspace);
            $currency     = $this->currancy;
            $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

            if (!in_array($currency, $supported_currencies)) {
                return redirect()->back()->with('error', __('Currency is not supported.'));
            }
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();

            try {
                // Check if 'access_token' key exists in the array
                if (isset($paypalToken['access_token'])) {
                    Session::put('paypal_payment_id', $paypalToken['access_token']);
                } else {
                    // Handle the case where 'access_token' is not present
                    $msg = __('Unable to retrieve PayPal access token.');
                    return redirect()->back()->with('msg', $msg);
                }

                $data = $request->all();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('beauty.spa.paypal', ['slug' => $slug] + $data),
                        "cancel_url" => route('beauty.spa.paypal', ['slug' => $slug] + $data),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => admin_setting('defult_currancy'),
                                "value" => $price,
                            ]
                        ]
                    ]
                ]);
                if (isset($response['id'])) {
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }

                    return redirect()->back()->with('error', __('Something went wrong.'));
                } else {
                    $msg = __('Something went wrong.');
                    return redirect()->back()->with('msg', $msg);
                }
            } catch (\Exception $e) {
                $msg = __('Unknown error occurred');
                return redirect()->back()->with('msg', $msg);
            }
        }
    }

    public function GetBeautySpaPaymentStatus(Request $request, $slug)
    {
        try {
            $workspace = WorkSpace::where('id', $slug)->first();
            $this->paymentConfig($workspace->created_by, $workspace->workspace);

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);

            try {
                $statuses = '';
                if (isset($response['status']) && $response['status'] == 'COMPLETED') {

                    $beautybooking                  = new BeautyBooking();
                    $beautybooking->name            = $request->name;
                    $beautybooking->service         = $request->service_id;
                    $beautybooking->date            = $request->date;
                    $beautybooking->number          = $request->number;
                    $beautybooking->email           = $request->email;
                    $beautybooking->stage_id        = 2;
                    $beautybooking->person           = $request->person;
                    $beautybooking->gender          = $request->gender;
                    $beautybooking->start_time      = $request->start_time;
                    $beautybooking->end_time        = $request->end_time;
                    $beautybooking->payment_option  = $request->payment_option;
                    $beautybooking->workspace       = $workspace->id;
                    $beautybooking->created_by      = $workspace->created_by;
                    $beautybooking->save();

                    if ($response['status'] == 'COMPLETED') {
                        $statuses = __('successful');
                    }

                    $beautyreceipt                  = new BeautyReceipt();
                    $beautyreceipt->booking_id      = $beautybooking->id;
                    $beautyreceipt->name            = $beautybooking->name;
                    $beautyreceipt->service         = $beautybooking->service;
                    $beautyreceipt->number          = $beautybooking->number;
                    $beautyreceipt->gender          = $beautybooking->gender;
                    $beautyreceipt->start_time      = $beautybooking->start_time;
                    $beautyreceipt->end_time        = $beautybooking->end_time;
                    $beautyreceipt->price           = $request->price;
                    $beautyreceipt->payment_type    =  __('PayPal');
                    $beautyreceipt->workspace       = $workspace->id;
                    $beautyreceipt->created_by      = $workspace->created_by;
                    $beautyreceipt->save();

                    $type = 'beautypayment';

                    event(new PaypalPaymentStatus($beautybooking, $type, $beautyreceipt));

                    $msg = __('Payment has been success.');
                    return redirect()->route('beauty.home',[$slug])->with('msg',$msg);

                } else {
                    $msg = __('Transaction has been failed');
                    return redirect()->back()->with('msg', $msg);
                }
            } catch (\Exception $e) {

                $e = __('Transaction has been failed');
                return redirect()->back()->with('msg', $e);
            }
        } catch (\Exception $exception) {

            $e = __('Transaction has been failed');
            return redirect()->back()->with('msg', $e);
        }
    }

    // Movie Show Booking Module
    public function MovieShowBookingPayWithPaypal(Request $request, $slug)
    {
        $seatsData = json_decode($request->input('seatsData'), true);
        $workspace = WorkSpace::where('slug', $slug)->first();
        $price = $request->totalPrice;
        $data = $request->all();

        if ($price > 0) {
            $this->paymentConfig($workspace->created_by, $workspace->id);
            $currency     = $this->currancy;
            $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

            if (!in_array($currency, $supported_currencies)) {
                return redirect()->back()->with('error', __('Currency is not supported.'));
            }

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();
            try {

                if (isset($paypalToken['access_token'])) {
                    Session::put('paypal_payment_id', $paypalToken['access_token']);
                } else {

                    $msg = __('Unable to retrieve PayPal access token.');
                    return redirect()->back()->with('msg', $msg);
                }

                $data = $request->all();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('movie.show.booking.paypal', ['slug' => $slug] + $data),
                        "cancel_url" => route('movie.show.booking.paypal', ['slug' => $slug] + $data),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => admin_setting('defult_currancy'),
                                "value" => $price,
                            ]
                        ]
                    ]
                ]);
                if (isset($response['id'])) {
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }

                    return redirect()->back()->with('error', __('Something went wrong.'));
                } else {
                    $msg = __('Something went wrong.');
                    return redirect()->back()->with('msg', $msg);
                }
            } catch (\Exception $e) {
                $msg = __('Unknown error occurred');
                return redirect()->back()->with('msg', $msg);
            }
        }
    }

    public function GetMovieShowBookingPaymentStatus(Request $request, $slug)
    {

        $seatsData = json_decode($request->seatsData, true);
        $workspace = WorkSpace::where('slug', $slug)->first();
        $this->paymentConfig($workspace->created_by, $workspace->id);
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        try {
            $statuses = '';
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {

                foreach ($seatsData as $seatData) {
                    $seatBooking = new MovieSeatBooking();
                    $seatBooking->movie_id = $seatData['movieId'];
                    $seatBooking->seating_template_detail_id = $seatData['seatingTemplateDetailId'];
                    $seatBooking->row = $seatData['row'];
                    $seatBooking->column = $seatData['column'];
                    $seatBooking->booking_date = $request->date;
                    $seatBooking->booking_show_time = $request->ShowTime;
                    $seatBooking->workspace = $workspace->id;
                    $seatBooking->created_by = $workspace->created_by;
                    $seatBooking->save();
                }

                if ($response['status'] == 'COMPLETED') {
                    $statuses = __('successful');
                }

                $orderID = crc32(uniqid('', true));

                $movieseatbooking                  = new MovieSeatBookingOrder();
                $movieseatbooking->movie_id        = $request->movieId;
                $movieseatbooking->order_id        = $orderID;
                $movieseatbooking->seat_data       = $request->seatsData;
                $movieseatbooking->booking_show_time = $request->ShowTime;
                $movieseatbooking->name            = $request->name;
                $movieseatbooking->email           = $request->email;
                $movieseatbooking->mobile_number   = $request->mobile_number;
                $movieseatbooking->price           = $request->totalPrice;
                $movieseatbooking->payment_type    =  __('PayPal');
                $movieseatbooking->payment_status       = 'successful';
                $movieseatbooking->workspace       = $workspace->id;
                $movieseatbooking->created_by      = $workspace->created_by;

                $movieseatbooking->save();

                $type = 'movieshowbookingpayment';

                event(new PaypalPaymentStatus($seatBooking, $type, $movieseatbooking));

                return redirect()->route('movie.print.ticket', [
                    'slug' => $slug,
                    'seatBooking' => Crypt::encrypt($seatBooking->id),
                    'movieseatbooking' => Crypt::encrypt($movieseatbooking->id),
                ])->with('success', __('Payment has been successful'));
            } else {
                $msg = __('Transaction has been failed');
                return redirect()->back()->with('msg', $msg);
            }
        } catch (\Exception $e) {
            $e = __('Transaction has been failed');
            return redirect()->back()->with('msg', $e);
        }
    }

    //parking
    public function parkingPayWithPaypal(Request $request, $slug, $lang = '')
    {
        if ($request->payment) {

            $parking = Parking::find($request->parking_id);

            if ($lang == '') {
                $lang = !empty(company_setting('defult_language', $parking->created_by, $parking->workspace)) ? company_setting('defult_language', $parking->created_by, $parking->workspace) : 'en';
            }
            \App::setLocale($lang);

            $this->paymentConfig($parking->created_by, $parking->workspace_id);
            $currency     = $this->currancy;
            $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

            if (!in_array($currency, $supported_currencies)) {
                return redirect()->back()->with('error', __('Currency is not supported.'));
            }
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();
            \Session::put('paypal_payment_id', $paypalToken['access_token']);

            if ($parking) {
                try {
                    if ($parking->amount <= 0) {
                        $parking_payment                       = new Payment();
                        $parking_payment->parking_id           = $parking->id;
                        $parking_payment->amount               = isset($parking['amount']) ? $parking['amount'] : 0;
                        $parking_payment->name                 = $parking->name;
                        $parking_payment->order_id             = '#' . time();
                        $parking_payment->amount_currency      = !empty(company_setting('defult_currancy', $parking->created_by, $parking->workspace)) ? company_setting('defult_currancy', $parking->created_by, $parking->workspace) : 'USD';
                        $parking_payment->payment_type         = __('PayPal');
                        $parking_payment->payment_status       = 'successful';
                        $parking_payment->receipt              = '';
                        $parking_payment->workspace            = $parking->workspace;
                        $parking_payment->created_by           = $parking->created_by;
                        $parking_payment->save();
                        //parking status update
                        $parking->status = 2; //paid
                        $parking->save();


                        $type = 'parkingpayment';
                        event(new PaypalPaymentStatus($parking, $type, $parking_payment));
                        return redirect()->route('parking.home', $slug)->with('successs', __('Payment has been success'));
                    }
                    $response = $provider->createOrder([
                        "intent" => "CAPTURE",
                        "application_context" => [
                            "return_url" => route('parking.paypal', [$slug, $parking->id, $lang]),
                            "cancel_url" => route('parking.paypal', [$slug, $parking->id]),
                        ],
                        "purchase_units" => [
                            0 => [
                                "amount" => [
                                    "currency_code" => !empty(company_setting('defult_currancy', $parking->created_by, $parking->workspace)) ? company_setting('defult_currancy', $parking->created_by, $parking->workspace) : 'INR',
                                    "value" => $parking->amount,
                                ],
                            ],
                        ],
                    ]);

                    if (isset($response['id']) && $response['id'] != null) {
                        // redirect to approve href
                        foreach ($response['links'] as $links) {
                            if ($links['rel'] == 'approve') {
                                return redirect()->away($links['href']);
                            }
                        }
                        return redirect()->back()->with('error', __('Something went wrong.'));
                    } else {
                        return redirect()->back()
                            ->with('error', 'Something went wrong.');
                    }
                    Session::put('paypal_payment_id', $paypalToken->id);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __('Unknown error occurred'));
                }
            } else {
                return redirect()->back()->with('error', __('is deleted.'));
            }
        } else {
            return redirect()->back()->with('error', __('Please select payment method'));
        }
    }

    public function GetParkingPaymentStatus(Request $request, $slug, $parking_id, $lang = '')
    {
        try {
            if (!empty($parking_id)) {
                $parking    = Parking::find($parking_id);
                if ($lang == '') {
                    $lang = !empty(company_setting('defult_language', $parking->created_by, $parking->workspace)) ? company_setting('defult_language', $parking->created_by, $parking->workspace) : 'en';
                }
                \App::setLocale($lang);
                $this->paymentConfig($parking->created_by, $parking->workspace_id);
                $provider = new PayPalClient;
                $provider->setApiCredentials(config('paypal'));
                $provider->getAccessToken();
                $response = $provider->capturePaymentOrder($request['token']);
                $payment_id = \Session::get('paypal_payment_id');
                try {
                    $statuses = '';
                    if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                        if ($response['status'] == 'COMPLETED') {
                            $statuses = __('successful');
                        }
                        $parking_payment                  = new Payment();
                        $parking_payment->order_id        =  '#' . time();
                        $parking_payment->name            = $parking->name;
                        $parking_payment->parking_id      = $parking->id;
                        $parking_payment->amount          = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
                        $parking_payment->amount_currency = !empty(company_setting('defult_currancy', $parking->created_by, $parking->workspace_id)) ? company_setting('defult_currancy', $parking->created_by, $parking->workspace_id) : 'USD';
                        $parking_payment->txn_id          = $payment_id;
                        $parking_payment->payment_type    = __('PayPal');
                        $parking_payment->payment_status  = $statuses;
                        $parking_payment->receipt         = '';
                        $parking_payment->workspace       = $parking->workspace;
                        $parking_payment->created_by      = $parking->created_by;
                        $parking_payment->save();
                        //parking status update
                        $parking->status = 2; //paid
                        $parking->save();

                        $type = 'parkingpayment';

                        event(new PaypalPaymentStatus($parking, $type, $parking_payment));

                        return redirect()->route(
                            'parking.home',
                            [$slug, $lang],
                        )->with('successs', __('Payment has been success'));
                    } else {
                        return redirect()->back()->with('error', __('Transaction has been failed.'));
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __('Transaction has been failed.'));
                }
            } else {
                return redirect()->back()->with('error', __('is deleted.'));
            }
        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    // Bookings Module
    public function BookingsPayWithPaypal(Request $request, $slug)
    {
        $package = BookingsPackage::find($request->package);
        $price = $package->price;
        if ($price > 0) {
            $this->paymentConfig($package->created_by, $package->workspace);
            $currency     = $this->currancy;
            $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

            if (!in_array($currency, $supported_currencies)) {
                return redirect()->back()->with('error', __('Currency is not supported.'));
            }
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();

            try {
                // Check if 'access_token' key exists in the array
                if (isset($paypalToken['access_token'])) {
                    Session::put('paypal_payment_id', $paypalToken['access_token']);
                } else {
                    // Handle the case where 'access_token' is not present
                    $msg = __('Unable to retrieve PayPal access token.');
                    return redirect()->back()->with('msg', $msg);
                }

                $data = $request->all();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('bookings.paypal', ['slug' => $slug] + $data),
                        "cancel_url" => route('bookings.paypal', ['slug' => $slug] + $data),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => admin_setting('defult_currancy'),
                                "value" => $price,
                            ]
                        ]
                    ]
                ]);

                if (isset($response['id'])) {
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()->back()->with('error', __('Something went wrong.'));
                } else {
                    $msg = __('Something went wrong.');
                    return redirect()->back()->with('msg', $msg);
                }
            } catch (\Exception $e) {
                $msg = __('Unknown error occurred');
                return redirect()->back()->with('msg', $msg);
            }
        }
    }

    public function GetBookingsPaymentStatus(Request $request, $slug)
    {
        try {

            $workspace = WorkSpace::where('id', $slug)->first();
            $this->paymentConfig($workspace->created_by, $workspace->workspace);
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $data = $request->all();
            $package = BookingsPackage:: find($data['package']);

            try {
                    $workspace  = WorkSpace::where('id', $slug)->first();
                        $bookingscustomer = BookingsCustomer::where('email', $data['email'])->first();

                    if ($bookingscustomer) {

                        $bookingscustomer->name        = isset($data['name']) ? $data['name'] : $bookingscustomer->name;
                        $bookingscustomer->client_id   = isset($data['client_name']) ? $data['client_name'] : $bookingscustomer->client_id;
                        $bookingscustomer->number      = isset($data['number']) ? $data['number'] : $bookingscustomer->number;
                        $bookingscustomer->customer    = isset($data['customer']) ? $data['customer'] : $bookingscustomer->customer;
                        $bookingscustomer->workspace   = $workspace->id;
                        $bookingscustomer->created_by  = $workspace->created_by;
                        $bookingscustomer->save();
                    } else {

                        $bookingscustomer = new BookingsCustomer();
                        $bookingscustomer->name        = isset($data['name']) ? $data['name'] : '';
                        $bookingscustomer->client_id   = isset($data['client_name']) ? $data['client_name'] : '';
                        $bookingscustomer->number      = isset($data['number']) ? $data['number'] : '';
                        $bookingscustomer->email       = isset($data['email']) ? $data['email'] : '';
                        $bookingscustomer->customer    = isset($data['customer']) ? $data['customer'] : '';
                        $bookingscustomer->workspace   = $workspace->id;
                        $bookingscustomer->created_by  = $workspace->created_by;
                        $bookingscustomer->save();
                    }

                    $bookingsappointment                  = new BookingsAppointment();
                    $bookingsappointment->appointment_id  = $this->AppointmentNumber($slug);
                    $bookingsappointment->date            = isset($data['date']) ? $data['date'] :'-';
                    $bookingsappointment->service         = isset($data['service']) ? $data['service'] :'-';
                    $bookingsappointment->package         = isset($data['package']) ? $data['package'] :'-';
                    $bookingsappointment->staff           = isset($data['staff']) ? $data['staff'] :'-';
                    $bookingsappointment->client_id       = isset($data['client_id']) ? $data['client_id'] : $bookingscustomer->id ;
                    $bookingsappointment->start_time      = isset($data['start_time']) ? $data['start_time'] :'-';
                    $bookingsappointment->end_time        = isset($data['end_time']) ? $data['end_time'] :'-';
                    $bookingsappointment->your_country    = isset($data['your_country']) ? $data['your_country'] :'-';
                    $bookingsappointment->your_state      = isset($data['your_state']) ? $data['your_state'] :'-';
                    $bookingsappointment->your_city       = isset($data['your_city']) ? $data['your_city'] :'-';
                    $bookingsappointment->your_address    = isset($data['your_address']) ? $data['your_address'] :'-';
                    $bookingsappointment->your_zip_code   = isset($data['your_zip_code']) ? $data['your_zip_code'] :'-';
                    $bookingsappointment->our_country     = isset($data['our_country']) ? $data['our_country'] :'-';
                    $bookingsappointment->our_state       = isset($data['our_state']) ? $data['our_state'] :'-';
                    $bookingsappointment->our_city        = isset($data['our_city']) ? $data['our_city'] :'-';
                    $bookingsappointment->our_zip_code    = isset($data['our_zip_code']) ? $data['our_zip_code'] :'-';
                    $bookingsappointment->payment         = 'Paypal';
                    $bookingsappointment->stage_id        = 2;
                    $bookingsappointment->workspace       = $workspace->id;
                    $bookingsappointment->created_by      = $workspace->created_by;
                    $bookingsappointment->save();

                    $type = 'bookingspayment';

                    event(new PaypalPaymentStatus($package, $type, $bookingsappointment));

                    $msg = __('Payment has been success.');
                    return redirect()->back()->with('msg',$msg);


            } catch (\Exception $e) {
                $e = __('Transaction has been failed');
                return redirect()->back()->with('msg', $e);
            }
        } catch (\Exception $exception) {
            $e = __('Transaction has been failed');
            return redirect()->back()->with('msg', $e);
        }
    }

    function AppointmentNumber($slug)
    {
        $workspace  = WorkSpace::where('id', $slug)->first();
        $workspace = $workspace->id;
        $appointment_id = BookingsAppointment::where('workspace', $workspace)->max('appointment_id');
        if ($appointment_id == null) {
            return 1;
        } else {
            return $appointment_id + 1;
        }
    }



  //Event Show booking

  public function EventShowBookingPayWithPaypal(Request $request, $slug)
  {
      $event = EventsMange::find($request->event);
      $workspace = WorkSpace::where('slug', $slug)->first();
      $price = $request->price * $request->person;
      $data = $request->all();

      if ($price > 0) {

          $this->paymentConfig($event->created_by, $event->workspace);
          $currency     = $this->currancy;
          $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

          if (!in_array($currency, $supported_currencies)) {
              return redirect()->back()->with('error', __('Currency is not supported.'));
          }
          $provider = new PayPalClient;
          $provider->setApiCredentials(config('paypal'));
          $paypalToken = $provider->getAccessToken();

          try {
              // Check if 'access_token' key exists in the array
              if (isset($paypalToken['access_token'])) {
                  Session::put('paypal_payment_id', $paypalToken['access_token']);
              } else {
                  // Handle the case where 'access_token' is not present
                  $msg = __('Unable to retrieve PayPal access token.');
                  return redirect()->back()->with('msg', $msg);
              }

              $data = $request->all();
              $response = $provider->createOrder([
                  "intent" => "CAPTURE",
                  "application_context" => [
                      "return_url" => route('event.show.booking.paypal', ['slug' => $slug] + $data),
                      "cancel_url" => route('event.show.booking.paypal', ['slug' => $slug] + $data),
                  ],
                  "purchase_units" => [
                      0 => [
                          "amount" => [
                              "currency_code" => admin_setting('defult_currancy'),
                              "value" => $price,
                          ]
                      ]
                  ]
              ]);

              if (isset($response['id'])) {
                  foreach ($response['links'] as $links) {
                      if ($links['rel'] == 'approve') {
                          return redirect()->away($links['href']);
                      }
                  }
                  return redirect()->back()->with('error', __('Something went wrong.'));
              } else {
                  $msg = __('Something went wrong.');
                  return redirect()->back()->with('msg', $msg);
              }
          } catch (\Exception $e) {
              $msg = __('Unknown error occurred');
              return redirect()->back()->with('msg', $msg);
          }
      }
  }

  public function GetEventShowBookingPaymentStatus(Request $request, $slug)
  {
      try {
          $workspace = WorkSpace::where('slug', $slug)->first();
          $this->paymentConfig($workspace->created_by, $workspace->id);
          $provider = new PayPalClient;
          $provider->setApiCredentials(config('paypal'));
          $provider->getAccessToken();
          $response = $provider->capturePaymentOrder($request['token']);
          $orderID = crc32(uniqid('', true));

          try {
              $statuses = '';
              if (isset($response['status']) && $response['status'] == 'COMPLETED') {

                  $eventbooking                  = new EventsBookings();
                  $eventbooking->name            = $request->name;
                  $eventbooking->event           = $request->event;
                  $eventbooking->date            = $request->date;
                  $eventbooking->number          = $request->number;
                  $eventbooking->email           = $request->email;
                  $eventbooking->person          = $request->person;
                  $eventbooking->start_time      = $request->start_time;
                  $eventbooking->end_time        = $request->end_time;
                  $eventbooking->payment_option  = $request->payment_option;
                  $eventbooking->workspace       = $workspace->id;
                  $eventbooking->created_by      = $workspace->created_by;
                  $eventbooking->save();

                  if ($response['status'] == 'COMPLETED') {
                      $statuses = __('successful');
                  }


                  $eventbookingorder                  = new EventBookingOrder();
                  $eventbookingorder->booking_id      = $eventbooking->id;
                  $eventbookingorder->order_id        = $orderID;
                  $eventbookingorder->name            = $eventbooking->name;
                  $eventbookingorder->event_id        = $eventbooking->event;
                  $eventbookingorder->number          = $eventbooking->number;
                  $eventbookingorder->start_time      = $eventbooking->start_time;
                  $eventbookingorder->end_time        = $eventbooking->end_time;
                  $eventbookingorder->price           = $request->price;
                  $eventbookingorder->payment_type    =  __('PayPal');
                  $eventbookingorder->workspace       = $workspace->id;
                  $eventbookingorder->created_by      = $workspace->created_by;
                  $eventbookingorder->save();

                  $type = 'eventshowpayment';

                  event(new PaypalPaymentStatus($eventbooking, $type, $eventbookingorder));

                  return redirect()->route('event.print.ticket', [
                      'slug' => $slug,
                      'eventBooking' => Crypt::encrypt($eventbooking->id),
                      'eventbookingorder' => Crypt::encrypt($eventbookingorder->id),
                  ])->with('success', __('Payment has been successfully'));
              } else {
                  $msg = __('Transaction has been failed');
                  return redirect()->back()->with('msg', $msg);
              }
          } catch (\Exception $e) {
              $e = __('Transaction has been failed');
              return redirect()->back()->with('msg', $e);
          }
      } catch (\Exception $exception) {
          $e = __('Transaction has been failed');
          return redirect()->back()->with('msg', $e);
      }
  }

      // Facilities Module
    public function FacilitiesPayWithPaypal(Request $request, $slug)
    {
        $service = FacilitiesService::find($request->service_id);
        $workspace = WorkSpace::where('id', $slug)->first();
        $price = $request->price;

        if ($price > 0) {
            $this->paymentConfig($service->created_by, $service->workspace);
            $currency     = $this->currancy;
            $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

            if (!in_array($currency, $supported_currencies)) {
                return redirect()->back()->with('error', __('Currency is not supported.'));
            }
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();

            try {
                // Check if 'access_token' key exists in the array
                if (isset($paypalToken['access_token'])) {
                    Session::put('paypal_payment_id', $paypalToken['access_token']);
                } else {
                    // Handle the case where 'access_token' is not present
                    $msg = __('Unable to retrieve PayPal access token.');
                    return redirect()->back()->with('msg', $msg);
                }
                $data = $request->all();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('facilities.paypal', ['slug' => $slug] + $data),
                        "cancel_url" => route('facilities.paypal', ['slug' => $slug] + $data),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => admin_setting('defult_currancy'),
                                "value" => $price,
                            ]
                        ]
                    ]
                ]);
                if (isset($response['id'])) {
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }

                    return redirect()->back()->with('error', __('Something went wrong.'));
                } else {
                    $msg = __('Something went wrong.');
                    return redirect()->back()->with('msg', $msg);
                }
            } catch (\Exception $e) {
                $msg = __('Unknown error occurred');
                return redirect()->back()->with('msg', $msg);
            }
        }
        else{
            $service = FacilitiesService::find($request->service_id);

            $facilitiesBooking                  = new FacilitiesBooking();
            $facilitiesBooking->name            = $request->name;
            $facilitiesBooking->client_id       = 0;
            $facilitiesBooking->service         = $service->item_id ?? '';
            $facilitiesBooking->date            = $request->date;
            $facilitiesBooking->number          = $request->number;
            $facilitiesBooking->email           = $request->email;
            $facilitiesBooking->gender          = $request->gender;
            $facilitiesBooking->start_time      = $request->start_time;
            $facilitiesBooking->end_time        = $request->end_time;
            $facilitiesBooking->person          = $request->person;
            $facilitiesBooking->payment_option  = $request->payment_option;
            $facilitiesBooking->stage_id        = 2;
            $facilitiesBooking->workspace       = $service->workspace;
            $facilitiesBooking->created_by      = $service->created_by;
            $facilitiesBooking->save();


            $facilitiesreceipt                  = new FacilitiesReceipt();
            $facilitiesreceipt->booking_id      = $facilitiesBooking->id;
            $facilitiesreceipt->name            = $facilitiesBooking->name;
            $facilitiesreceipt->client_id       = $facilitiesBooking->client_id;
            $facilitiesreceipt->service         = $facilitiesBooking->service;
            $facilitiesreceipt->number          = $facilitiesBooking->number;
            $facilitiesreceipt->gender          = $facilitiesBooking->gender;
            $facilitiesreceipt->start_time      = $facilitiesBooking->start_time;
            $facilitiesreceipt->end_time        = $facilitiesBooking->end_time;
            $facilitiesreceipt->price           = $request->price;
            $facilitiesreceipt->workspace       = $service->workspace;
            $facilitiesreceipt->created_by      = $service->created_by;
            $facilitiesreceipt->save();

            $type = 'facilitiespayment';

            event(new PaypalPaymentStatus($facilitiesBooking, $type, $facilitiesreceipt));

            $msg = __('Payment has been success.');
            return redirect()->route('facilities.booking',[$workspace->slug])->with('msg',$msg);
        }
    }

    public function GetFacilitiesPaymentStatus(Request $request, $slug)
    {
        try {
            $workspace = WorkSpace::where('id', $slug)->first();
            $this->paymentConfig($workspace->created_by, $workspace->workspace);

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);

            try {
                $statuses = '';
                if (isset($response['status']) && $response['status'] == 'COMPLETED') {

                    $service = FacilitiesService::find($request->service_id);

                    $facilitiesBooking                  = new FacilitiesBooking();
                    $facilitiesBooking->name            = $request->name;
                    $facilitiesBooking->client_id       = 0;
                    $facilitiesBooking->service         = $service->item_id ?? '';
                    $facilitiesBooking->date            = $request->date;
                    $facilitiesBooking->number          = $request->number;
                    $facilitiesBooking->email           = $request->email;
                    $facilitiesBooking->gender          = $request->gender;
                    $facilitiesBooking->start_time      = $request->start_time;
                    $facilitiesBooking->end_time        = $request->end_time;
                    $facilitiesBooking->person          = $request->person;
                    $facilitiesBooking->payment_option  = $request->payment_option;
                    $facilitiesBooking->stage_id        = 2;
                    $facilitiesBooking->workspace       = $workspace->id;
                    $facilitiesBooking->created_by      = $workspace->created_by;
                    $facilitiesBooking->save();


                    $facilitiesreceipt                  = new FacilitiesReceipt();
                    $facilitiesreceipt->booking_id      = $facilitiesBooking->id;
                    $facilitiesreceipt->name            = $facilitiesBooking->name;
                    $facilitiesreceipt->client_id       = $facilitiesBooking->client_id;
                    $facilitiesreceipt->service         = $facilitiesBooking->service;
                    $facilitiesreceipt->number          = $facilitiesBooking->number;
                    $facilitiesreceipt->gender          = $facilitiesBooking->gender;
                    $facilitiesreceipt->start_time      = $facilitiesBooking->start_time;
                    $facilitiesreceipt->end_time        = $facilitiesBooking->end_time;
                    $facilitiesreceipt->price           = $request->price;
                    $facilitiesreceipt->workspace       = $workspace->id;
                    $facilitiesreceipt->created_by      = $workspace->created_by;
                    $facilitiesreceipt->save();

                    $type = 'facilitiespayment';

                    event(new PaypalPaymentStatus($facilitiesBooking, $type, $facilitiesreceipt));

                    $msg = __('Payment has been success.');
                    return redirect()->route('facilities.booking',[$workspace->slug])->with('msg',$msg);

                } else {
                    $msg = __('Transaction has been failed');
                    return redirect()->back()->with('msg', $msg);
                }
            } catch (\Exception $e) {

                $e = __('Transaction has been failed');
                return redirect()->back()->with('msg', $e);
            }
        } catch (\Exception $exception) {

            $e = __('Transaction has been failed');
            return redirect()->back()->with('msg', $e);
        }
    }


    // Property Booking Front End Side
    public function PropertyBookingPayWithPaypal(Request $request, $slug)
    {
        $property = Property::find($request->property);
        $workspace = WorkSpace::where('slug', $slug)->first();
        if (!$workspace) {
            return redirect()->back()->with('error', __('Workspace not found.'));
        }
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:180',
                'email' => ['required',
                                    \Illuminate\Validation\Rule::unique('users')->where(function ($query) use ($workspace) {
                                    return $query->where('created_by', $workspace->created_by)->where('workspace_id',$workspace->id);
                                })
                    ],
                'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $price = $request->price;
        $data = $request->all();
        if($price > 0) {
            $this->paymentConfig($property->created_by, $property->workspace);
            $currency     = $this->currancy;
            $supported_currencies = ['USD','EUR','GBP','CAD','AUD','JPY','CNY','CHF','HKD','SGD','INR','BRL','BRL','MXN','ZAR'];

            if (!in_array($currency, $supported_currencies)) {
                return redirect()->back()->with('error', __('Currency is not supported.'));
            }
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();

            try {

                // Check if 'access_token' key exists in the array
                    if (isset($paypalToken['access_token'])) {
                        Session::put('paypal_payment_id', $paypalToken['access_token']);
                    } else {
                        // Handle the case where 'access_token' is not present
                        $msg = __('Unable to retrieve PayPal access token.');
                        return redirect()->back()->with('msg', $msg);
                    }

                    $data = $request->all();
                    $response = $provider->createOrder([
                        "intent" => "CAPTURE",
                        "application_context" => [
                            "return_url" => route('property.booking.paypal', ['slug' => $slug] + $data),
                            "cancel_url" => route('property.booking.paypal', ['slug' => $slug] + $data),
                        ],
                        "purchase_units" => [
                            0 => [
                                "amount" => [
                                    "currency_code" => admin_setting('defult_currancy'),
                                    "value" => $price,
                                ]
                            ]
                        ]
                    ]);

                    if (isset($response['id'])) {
                        foreach ($response['links'] as $links) {
                            if ($links['rel'] == 'approve') {
                                return redirect()->away($links['href']);
                            }
                        }
                        return redirect()->back()->with('error', __('Something went wrong.'));
                    } else {
                        $msg = __('Something went wrong.');
                        return redirect()->back()->with('msg', $msg);
                    }

            }catch (\Exception $e) {
              $msg = __('Unknown error occurred');
              return redirect()->back()->with('msg', $msg);
          }
        }
    }


    public function GetPropertyBookingPaymentStatus(Request $request, $slug)
    {
        try {
            $workspace = WorkSpace::where('slug', $slug)->first();
            $this->paymentConfig($workspace->created_by, $workspace->workspace);

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            try {
                $statuses = '';
                if (isset($response['status']) && $response['status'] == 'COMPLETED') {

                    $tenant_request                  = new PropertyTenantRequest();
                    $tenant_request->name            = $request->name;
                    $tenant_request->email           = $request->email;
                    $tenant_request->mobile_no       = $request->mobile_number;
                    $tenant_request->property_id     = $request->property;
                    $tenant_request->unit_id         = $request->unit;
                    $tenant_request->total_amount    = $request->price;
                    $tenant_request->workspace       = $workspace->id;
                    $tenant_request->created_by      = $workspace->created_by;
                    $tenant_request->save();



                    $msg =  __('Booking Request Send Successfully.');
                    return redirect()->back()->with('success', $msg);

                    // $msg = __('Payment has been success.');
                    // return redirect()->route('property.listing',[$workspace->slug])->with('msg',$msg);

                }else {
                    $msg = __('Transaction has been failed');
                    return redirect()->back()->with('msg', $msg);
                }
            }  catch (\Exception $e) {
                $e = __('Something went wrong.');
                return redirect()->back()->with('msg', $e);
            }
        }catch (\Exception $exception) {

            $e = __('Transaction has been failed');
            return redirect()->back()->with('msg', $e);
        }

    }
}
