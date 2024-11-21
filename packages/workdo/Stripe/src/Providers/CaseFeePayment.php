<?php

namespace Workdo\Stripe\Providers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;
use Workdo\LegalCaseManagement\Entities\FeesReceipt;

class CaseFeePayment extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['legalcasemanagement::fees.billpay'], function ($view)
        {
            try {
                $ids = \Request::segment(3);
                if(!empty($ids))
                {
                    try {
                        $feesId = Crypt::decrypt($ids);
                        $invoice = FeesReceipt::find($feesId);
                        $company_settings = getCompanyAllSetting($invoice->created_by,$invoice->workspace);
                        $type = 'feesreceipt';
                        if(module_is_active('Stripe', $invoice->created_by) && ((isset($company_settings['stripe_is_on']) ? $company_settings['stripe_is_on']:'off') == 'on') && (isset($company_settings['stripe_key'])) && (isset($company_settings['stripe_secret'])))
                        {
                            $view->getFactory()->startPush('invoice_payment_tab', view('stripe::payment.sidebar'));
                            $view->getFactory()->startPush('invoice_payment_div', view('stripe::payment.nav_containt_div',compact('company_settings','invoice','type')));
                        }
                    } catch (\Throwable $th)
                    {

                    }
                }
            } catch (\Throwable $th) {

            }
        });
    }

    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
