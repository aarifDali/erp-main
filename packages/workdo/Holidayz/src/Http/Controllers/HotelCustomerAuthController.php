<?php

namespace Workdo\Holidayz\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\Entities\HotelCustomer;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\RoomBookingCart;
use Workdo\Holidayz\Events\CreateHotelCustomer;

class HotelCustomerAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($slug = null,$lang = 'en')
    { 
        $hotel = Hotels::where('slug', $slug)->where('is_active', '1')->first();
        if(empty($hotel))
        {
            return redirect()->back()->with('error', __('Hotel not available'));
        }
        if (\Workdo\Holidayz\Entities\Utility::CustomerAuthCheck($slug) == true){
            return redirect()->route('hotel.home',$slug);
        }else{
            return view('holidayz::frontend.' . $hotel->theme_dir . '.auth.login', compact('hotel', 'slug'));
        }
    }

    public function login(Request $request,$slug)
    { 
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $hotelWorkspace = Hotels::where('slug',$slug)->where('is_active', '1')->first()->workspace;
        $credentials =[
            'email' => $request->email,
            'password' => $request->password,
            'workspace' => $hotelWorkspace
        ];
        $hotelCustomer = HotelCustomer::where('email',$request->email)->first();
        if(isset($hotelCustomer)){
            if($hotelCustomer->is_active == 1){
                if(\Auth::guard('holiday')->attempt($credentials, true)){
                    $user_id = \Auth::guard('holiday')->user()->id;
                    RoomBookingCart::cookie_to_cart($user_id,$slug);
                    return redirect()->route('hotel.home',$slug)->with('success','Signed in');
                }
            }else{
                return redirect()->route('customer.login.page',[$slug,'en'])->with('error',__('Your account has been disabled. Contact to Your site admin for enable account.'));
            }
        }
        return redirect()->route('customer.login.page',[$slug,'en'])->with('error',__('Login details are not valid'));
    }


    public function register(Request $request,$slug)
    { 
        try{
            $hotelWorkspace = Hotels::where('slug',$slug)->where('is_active', '1')->first()->workspace;
            $hotel = Hotels::where('slug',$slug)->where('is_active', '1')->first();
            $request->validate([
                'name' => 'required|max:120',
                'email' => 'required|max:100|unique:hotel_customers,email,NULL,id,workspace,' . $hotelWorkspace,
                'password' => 'required',
                'password_confirmation' => 'required_with:password|same:password'
            ],[
                'name.required' => 'Name field is required',
                'email.required' => 'Email field is required',
                'password.required' => 'password field is required'
            ]);

            $customer = HotelCustomer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'lang' => 'en',
                'workspace' => $hotelWorkspace
            ]);
            auth()->guard('holiday')->login($customer);
            if(\Auth::guard('holiday')->user()){
                $user_id = \Auth::guard('holiday')->user()->id;
                RoomBookingCart::cookie_to_cart($user_id,$slug);

                event(new CreateHotelCustomer($request,$customer,$hotel));

                //Email notification
                if(!empty(company_setting('New Hotel Customer',$hotel->created_by,$hotel->workspace)) && company_setting('New Hotel Customer',$hotel->created_by,$hotel->workspace)  == true)
                {
                    $Assign_user = User::where('id',$hotel->created_by)->first();
                    
                    $uArr = [
                        'hotel_customer_email' => $request->input('email'),
                        'hotel_customer_password' => $request->input('password'),
                    ];

                    try
                    {
                        $resp = EmailTemplate::sendEmailTemplate('New Hotel Customer', [$Assign_user->id => $Assign_user->email],$uArr,$hotel->created_by,$hotel->workspace);
                    }
                    catch(\Exception $e)
                    {
                        $resp['error'] = $e->getMessage();
                    }

                    return redirect()->route('hotel.home',$slug)->with('success', __('Register successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color: red">' . $resp['error'] . '</span>' : ''));
                }
                return redirect()->back()->with('success', __('Register successfully email notification is off.'));

            }
            return redirect()->route('customer.login.page',[$slug,'en'])->with('error1',__('Register fail Please try again!'));

        }catch(\Exception $e){
            return redirect()->route('customer.login.page',[$slug,'en'])->with('error1',$e->getMessage());
        }
    }

    public function logout(Request $request,$slug)
    {
        $hotel = Hotels::where('slug',$slug)->where('is_active', '1')->first();
        if(empty($hotel))
        {
            return redirect()->back()->with('error', __('Hotel not available'));
        }
        
        \Auth::guard('holiday')->logout();
        return redirect()->route('hotel.home',$slug);
    }



    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('holidayz::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('holidayz::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('holidayz::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
