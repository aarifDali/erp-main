<?php

namespace Workdo\Holidayz\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Utility extends Model
{
    public function createSlug($table, $title, $id = 0)
    {
        // Normalize the title
        $slug = \Str::slug($title, '-');
        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($table, $slug, $id);
        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 100; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($table, $slug, $id = 0)
    {
        return \DB::table($table)->select()->where('slug', 'like', $slug . '%')->where('id', '<>', $id)->get();
    }

    public static function ActivePaymentGateway()
    {
        $company_settings = getCompanyAllSetting();
        $payment['cash'] = 'Cash';
        if(module_is_active('Stripe') && isset($company_settings['stripe_is_on']) && $company_settings['stripe_is_on']  == 'on'){                         $payment['stripe'] = 'Stripe';              }
        if(module_is_active('Paypal') && isset($company_settings['paypal_payment_is_on']) && $company_settings['paypal_payment_is_on']  == 'on'){                 $payment['paypal'] = 'Paypal';              }
        if(module_is_active('Flutterwave') && isset($company_settings['flutterwave_payment_is_on']) && $company_settings['flutterwave_payment_is_on']  == 'on'){       $payment['flutterwave'] = 'Flutterwave';    }
        if(module_is_active('Paystack') && isset($company_settings['paystack_payment_is_on']) && $company_settings['paystack_payment_is_on']  == 'on'){             $payment['paystack'] = 'Paystack';          }
        if(module_is_active('Razorpay') && isset($company_settings['razorpay_payment_is_on']) && $company_settings['razorpay_payment_is_on']  == 'on'){             $payment['razorpay'] = 'Razorpay';          }
        if(module_is_active('Mollie') && isset($company_settings['mollie_payment_is_on']) && $company_settings['mollie_payment_is_on']  == 'on'){                 $payment['mollie'] = 'Mollie';              }
        if(module_is_active('Payfast') && isset($company_settings['payfast_payment_is_on']) && $company_settings['payfast_payment_is_on']  == 'on'){               $payment['payfast'] = 'Payfast';            }
        if(module_is_active('SSPay') && isset($company_settings['sspay_payment_is_on']) && $company_settings['sspay_payment_is_on']  == 'on'){                   $payment['sspay'] = 'SSPay';                }
        if(module_is_active('Toyyibpay') && isset($company_settings['toyyibpay_payment_is_on']) && $company_settings['toyyibpay_payment_is_on']  == 'on'){           $payment['toyyibpay'] = 'Toyyibpay';        }
        if(module_is_active('Skrill') && isset($company_settings['skrill_payment_is_on']) && $company_settings['skrill_payment_is_on']  == 'on'){                 $payment['skrill'] = 'Skrill';              }
        if(module_is_active('Coingate') && isset($company_settings['coingate_payment_is_on']) && $company_settings['coingate_payment_is_on']  == 'on'){             $payment['coingate'] = 'Coingate';          }
        if(module_is_active('Mercado') && isset($company_settings['mercado_payment_is_on']) && $company_settings['mercado_payment_is_on']  == 'on'){               $payment['mercado'] = 'Mercado';            }
        if(module_is_active('Paytm') && isset($company_settings['paytm_payment_is_on']) && $company_settings['paytm_payment_is_on']  == 'on'){                   $payment['paytm'] = 'Paytm';                }
        if(isset($company_settings['bank_transfer_payment_is_on']) && $company_settings['bank_transfer_payment_is_on']  == 'on'){                                        $payment['bank transfer'] = 'Bank Transfer';}
        if(module_is_active('Benefit') && isset($company_settings['benefit_payment_is_on']) && $company_settings['benefit_payment_is_on']  == 'on'){               $payment['benefit'] = 'Benefit';            }
        if(module_is_active('PayTab') && isset($company_settings['paytab_payment_is_on']) && $company_settings['paytab_payment_is_on']  == 'on'){                 $payment['paytab'] = 'PayTab';              }
        if(module_is_active('PayTR') && isset($company_settings['paytr_is_on']) && $company_settings['paytr_is_on']  == 'on'){                           $payment['paytr'] = 'PayTR';                }
        if(module_is_active('AamarPay') && isset($company_settings['aamarpay_payment_is_on']) && $company_settings['aamarpay_payment_is_on']  == 'on'){             $payment['aamarpay'] = 'AamarPay';          }
        if(module_is_active('Cashfree') && isset($company_settings['cashfree_payment_is_on']) && $company_settings['cashfree_payment_is_on']  == 'on'){             $payment['cashfree'] = 'Cashfree';          }
        if(module_is_active('Iyzipay') && isset($company_settings['iyzipay_payment_is_on']) && $company_settings['iyzipay_payment_is_on']  == 'on'){               $payment['iyzipay'] = 'Iyzipay';            }
        if(module_is_active('YooKassa') && isset($company_settings['yookassa_payment_is_on']) && $company_settings['yookassa_payment_is_on']  == 'on'){             $payment['yookassa'] = 'YooKassa';          }
        if(module_is_active('Paddle') && isset($company_settings['paddle_payment_is_on']) && $company_settings['paddle_payment_is_on']  == 'on'){                 $payment['paddle'] = 'Paddle';              }
        if(module_is_active('Midtrans') && isset($company_settings['midtrans_is_on']) && $company_settings['midtrans_is_on']  == 'on'){                         $payment['midtrans'] = 'Midtrans';              }
        if(module_is_active('Xendit') && isset($company_settings['xendit_is_on']) && $company_settings['xendit_is_on']  == 'on'){                         $payment['xendit'] = 'Xendit';              }
        if(module_is_active('Tap') && isset($company_settings['tap_payment_is_on']) && $company_settings['tap_payment_is_on']  == 'on'){                         $payment['tap'] = 'Tap';              }

        return $payment;
    }

    public static function CustomerAuthCheck($slug = null)
    {
        if ($slug == null) {
            $slug = \Request::segment(1);
        }
        $auth_customer = \Auth::guard('holiday')->user();
        if (!empty($auth_customer))
        //
        {
            $workspace = Hotels::where('slug', $slug)->pluck('workspace')->first();
            $customer = HotelCustomer::where('workspace', $workspace)->where('email', $auth_customer->email)->count();
            if ($customer > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // public static function getLastId($table_name, $field_name)
    // {
    //     $id = 1;
    //     $last_rec = \DB::table($table_name)->orderBy('id', 'Desc')->first();
    //     if ($last_rec != null) {
    //         $id = $last_rec->$field_name + 1;
    //     }
    //     return $id;
    // }

    public static function getLastId($table_name, $field_name, $created_by)
    {
        $id = 1;
        $last_rec = \DB::table($table_name)
            ->where('created_by', $created_by)
            ->orderBy('id', 'desc')
            ->first();

        Log::info('Last record for created_by ' . $created_by, ['last_rec' => $last_rec]);

        if ($last_rec != null) {
            $id = $last_rec->$field_name + 1;
        }
        return $id;
    }

    public static function company_Time_formate($time, $company_id = null, $workspace = null)
    {
        if (!empty($company_id) && empty($workspace)) {
            $time_formate = !empty(company_setting('site_time_format', $company_id)) ? company_setting('site_time_format', $company_id) : 'H:i';
        } elseif (!empty($company_id) && !empty($workspace)) {
            $time_formate = !empty(company_setting('site_time_format', $company_id, $workspace)) ? company_setting('site_time_format', $company_id, $workspace) : 'H:i';
        } else {
            $time_formate = !empty(company_setting('site_time_format')) ? company_setting('site_time_format') : 'H:i';
        }
        return date($time_formate, strtotime($time));
    }

    public static function day_count($check_in, $check_out)
    {
        $toDate = \Carbon\Carbon::parse($check_in);
        $fromDate = \Carbon\Carbon::parse($check_out);
        $days = $toDate->diffInDays($fromDate);

        return (int)$days;
    }

    public static function themeOne()
    {
        $arr = [];

        $arr = [
            'theme1' => [
                'theme1-v1' => [
                    'img_path' => asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/theme1/Home.png'),
                    'color' => 'af8637',
                ],
                // 'theme1-v2' => [
                //     'img_path' => asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/theme1/Home-1.png'),
                //     'color' => '276968',
                // ],
                // 'theme1-v3' => [
                //     'img_path' => asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/theme1/Home-2.png'),
                //     'color' => '92bd88',
                // ],
                // 'theme1-v4' => [
                //     'img_path' => asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/theme1/Home-3.png'),
                //     'color' => 'e7d7bd',
                // ],
                // 'theme1-v5' => [
                //     'img_path' => asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/theme1/Home-4.png'),
                //     'color' => 'b7786f',
                // ],
            ],
        ];

        return $arr;
    }

    public static function getHotelThemeSetting($workspace = null, $theme_name = null)
    {
        $data = \DB::table('hotel_theme_settings');
        $settings = [];

        if ($workspace == null) {
            if (\Auth::check()) {
                $store_id = \Auth::user()->current_store;
            } else {
                $store_id = 0;
            }
        }

        if (\Auth::check()) {
            $data = $data->where('workspace', '=', $workspace)->where('theme_name', $theme_name);
        } else {
            $data = $data->where('workspace', '=', $workspace)->where('theme_name', $theme_name);
        }
        $data = $data->get();

        if ($data->count() > 0) {
            foreach ($data as $row) {
                $settings[$row->name] = $row->value;
            }
        }

        return $settings;
    }


    public static function json_upload_file($json, $key_name, $name, $path, $custom_validation = [])
    {
        $request = [
            $key_name => $json[$key_name]
        ];

        try {
            $settings = admin_setting('storage_setting');

            if (!empty(admin_setting('storage_setting'))) {

                if (admin_setting('storage_setting') == 'wasabi') {
                    config(
                        [
                            'filesystems.disks.wasabi.key' => admin_setting('wasabi_key'),
                            'filesystems.disks.wasabi.secret' => admin_setting('wasabi_secret'),
                            'filesystems.disks.wasabi.region' => admin_setting('wasabi_region'),
                            'filesystems.disks.wasabi.bucket' => admin_setting('wasabi_bucket'),
                            'filesystems.disks.wasabi.root' => admin_setting('wasabi_root'),
                            'filesystems.disks.wasabi.endpoint' => admin_setting('wasabi_url')
                        ]
                    );
                    $max_size = !empty(admin_setting('wasabi_max_upload_size')) ? admin_setting('wasabi_max_upload_size') : '2048';
                    $mimes =  !empty(admin_setting('wasabi_storage_validation')) ? admin_setting('wasabi_storage_validation') : '';
                } else if (admin_setting('storage_setting') == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => admin_setting('s3_key'),
                            'filesystems.disks.s3.secret' => admin_setting('s3_secret'),
                            'filesystems.disks.s3.region' => admin_setting('s3_region'),
                            'filesystems.disks.s3.bucket' => admin_setting('s3_bucket'),
                            // 'filesystems.disks.s3.url' => admin_setting('s3_url'),
                            // 'filesystems.disks.s3.endpoint' => admin_setting('s3_endpoint'),
                        ]
                    );
                    $max_size = !empty(admin_setting('s3_max_upload_size')) ? admin_setting('s3_max_upload_size') : '2048';
                    $mimes =  !empty(admin_setting('s3_storage_validation')) ? admin_setting('s3_storage_validation') : '';
                } else {
                    $max_size = !empty(admin_setting('local_storage_max_upload_size')) ? admin_setting('local_storage_max_upload_size') : '2048';
                    $mimes =  !empty(admin_setting('local_storage_validation')) ? admin_setting('local_storage_validation') : '';
                }

                $file = $json[$key_name];


                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . implode(",", $mimes),
                        'max:' . $max_size,

                    ];
                }

                $validator = \Validator::make($request, [

                    $key_name => $validation,
                ]);

                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if (admin_setting('storage_setting') == 'local') {
                        // $json[$key_name]->move(storage_path($path), $name);
                        $json[$key_name]->move($path , $name);
                        $path = $path . $name;
                    } else if (admin_setting('storage_setting') == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                        // $path = $path.$name;
                    } else if (admin_setting('storage_setting') == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );
                    }

                    $res = [
                        'flag' => 1,
                        'msg' => 'success',
                        'url' => $path,
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }


    public static function GivePermissionToRoles($role_id = null,$rolename = null)
    {


    }
    public static function defaultdata($company_id = null,$workspace_id = null)
    {

    }
}
