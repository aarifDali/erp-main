<?php

namespace Workdo\Holidayz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\HotelThemeSettings;
use Workdo\Holidayz\Entities\PageOptions;
use Workdo\Holidayz\Entities\Utility;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Workdo\Holidayz\Entities\HotelCustomer;

class HotelCustomerForgotPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function showLinkRequestForm($slug)
    {
        $hotel          = Hotels::where('slug', $slug)->where('is_active','1')->first();
        if(isset($hotel->lang))
        {
            $lang = session()->get('lang');

            if(!isset($lang))
            {
                session(['lang' => $hotel->lang]);
                $hotellang=session()->get('lang');
                \App::setLocale(isset($hotellang) ? $hotellang : 'en');
            }
            else
            {
                session(['lang' => $lang]);
                $hotellang=session()->get('lang');
                \App::setLocale(isset($hotellang) ? $hotellang : 'en');
            }
        }
        $page_slug_urls = PageOptions::where('workspace', $hotel->workspace)->where('created_by',$hotel->created_by)->get();

        if(empty($hotel))
        {
            return redirect()->back()->with('error', __('Hotel not available'));
        }
        
        $getHotelThemeSetting = Utility::getHotelThemeSetting($hotel->workspace, $hotel->theme_dir);
        $getHotelThemeSetting1 = [];
        
        if(!empty($getHotelThemeSetting['dashboard'])) {
            $getHotelThemeSetting1 = $getHotelThemeSetting;
            $getHotelThemeSetting = json_decode($getHotelThemeSetting['dashboard'], true);
        }
        if (empty($getHotelThemeSetting)) {
            $path = asset('packages/workdo/Holidayz/src/Resources/assets/'. $hotel->theme_dir . "/" . $hotel->theme_dir . ".json" );
            $getHotelThemeSetting = json_decode(file_get_contents($path), true);
        }

        if(isset($getHotelThemeSetting1) && isset($getHotelThemeSetting1['enable_top_bar']) && isset($getHotelThemeSetting1['top_bar_title']) && isset($getHotelThemeSetting1['top_bar_number']) && isset($getHotelThemeSetting1['top_bar_whatsapp']) && isset($getHotelThemeSetting1['top_bar_instagram']) && isset($getHotelThemeSetting1['top_bar_twitter']) && isset($getHotelThemeSetting1['top_bar_messenger'])){
            $demoStoreThemeSetting = $getHotelThemeSetting1;
        }else{
            $demoStoreThemeSetting = HotelThemeSettings::demoStoreThemeSetting($hotel->workspace,$hotel->theme_dir);
        }

        return view('holidayz::frontend.' . $hotel->theme_dir . '.auth.password',compact('hotel','page_slug_urls','slug','demoStoreThemeSetting','getHotelThemeSetting','getHotelThemeSetting1'));
    }

    public function postHoteCustomerEmail(Request $request,$slug)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:hotel_customers',
            ]
        );
        $token = \Str::random(60);

        DB::table('password_resets')->insert(
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        try{
            $hotel          = Hotels::where('slug', $slug)->where('is_active','1')->first();
            $company_settings = getCompanyAllSetting($hotel->created_by,$hotel->workspace);

            if (isset($company_settings['mail_from_address']) && isset($company_settings['mail_from_name']) && !empty($company_settings['mail_from_address']) && !empty($company_settings['mail_from_name'])) 
            {
                $setconfing =  SetConfigEmail($hotel->created_by,$hotel->workspace);

                if ($setconfing ==  true) {
                    Mail::send(
                        'holidayz::frontend.' . $hotel->theme_dir . '.auth.resetmail', ['token' => $token,'slug'=>$slug], function ($message) use ($request , $hotel){
                            $message->from(company_setting('mail_from_address',$hotel->created_by,$hotel->workspace),company_setting('mail_from_name',$hotel->created_by,$hotel->workspace));
                            $message->to($request->email);
                            $message->subject('Reset Password Notification');
                        }
                    );
                }else{
                    return redirect()->back()->with('error', __('E-Mail has been not sent due to SMTP configuration!'));
                }
            }else{
                return redirect()->back()->with('error', __('E-Mail has been not sent due to SMTP configuration!'));
            }
        }catch(\Exception $e)
        {
            $smtp_error['status'] = false;
            $smtp_error['msg'] = $e->getMessage();
            return redirect()->back()->with('error', __('E-Mail has been not sent due to SMTP configuration!') . ((isset($smtp_error['status'])) ? '<br> <span class="text-danger">' . $smtp_error['msg'] . '</span>' : ''));
        }
        return redirect()->back()->with('success', __('We have e-mailed your password reset link!'));
    }

    public function getHoteCustomerPassword($slug,$token)
    {
        $hotel          = Hotels::where('slug', $slug)->where('is_active','1')->first();
        $page_slug_urls = PageOptions::where('workspace', $hotel->workspace)->get();
        if(empty($hotel))
        {
            return redirect()->back()->with('error', __('Hotel not available'));
        }
        
        $getHotelThemeSetting = Utility::getHotelThemeSetting($hotel->workspace, $hotel->theme_dir);
        $getHotelThemeSetting1 = [];
        
        if(!empty($getHotelThemeSetting['dashboard'])) {
            $getHotelThemeSetting1 = $getHotelThemeSetting;
            $getHotelThemeSetting = json_decode($getHotelThemeSetting['dashboard'], true);
        }
        if (empty($getHotelThemeSetting)) {
            $path = asset('packages/workdo/Holidayz/src/Resources/assets/'. $hotel->theme_dir . "/" . $hotel->theme_dir . ".json" );
            $getHotelThemeSetting = json_decode(file_get_contents($path), true);
        }

        if(isset($getHotelThemeSetting1) && isset($getHotelThemeSetting1['enable_top_bar']) && isset($getHotelThemeSetting1['top_bar_title']) && isset($getHotelThemeSetting1['top_bar_number']) && isset($getHotelThemeSetting1['top_bar_whatsapp']) && isset($getHotelThemeSetting1['top_bar_instagram']) && isset($getHotelThemeSetting1['top_bar_twitter']) && isset($getHotelThemeSetting1['top_bar_messenger'])){
            $demoStoreThemeSetting = $getHotelThemeSetting1;
        }else{
            $demoStoreThemeSetting = HotelThemeSettings::demoStoreThemeSetting($hotel->workspace,$hotel->theme_dir);
        }

        return view('holidayz::frontend.' . $hotel->theme_dir . '.auth.newpassword', compact('token','slug','hotel','page_slug_urls','demoStoreThemeSetting','getHotelThemeSetting','getHotelThemeSetting1'));
    }

    public function updateHoteCustomerPassword(Request $request,$slug)
    {
        $request->validate(
            [
                'email' => 'required|email|exists:hotel_customers',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',

            ]
        );

        $updatePassword = DB::table('password_resets')->where(
            [
                'email' => $request->email,
                'token' => $request->token,
            ]
        )->first();

        if(!$updatePassword)
        {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = HotelCustomer::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        $lang =  (\Auth::guard('holiday')->user()) ? \Auth::guard('holiday')->user()->lang : 'en';

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect()->route('customer.login.page',[$slug,$lang])->with('success', __('Your password has been successfully changed.'));

    }
}
