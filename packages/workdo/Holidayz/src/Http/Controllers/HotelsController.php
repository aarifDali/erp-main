<?php

namespace Workdo\Holidayz\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\Entities\Hotels;
use PhpParser\Node\Expr\Empty_;
use Rawilk\Settings\Support\Context;
use Workdo\Holidayz\Events\CreateHotel;
use Workdo\Holidayz\Events\UpdateHotel;
use App\Models\Setting;

class HotelsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('holidayz::index');
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
        if (\Auth::user()->isAbleTo('holidayz manage')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'email' => 'required|email',
                    'phone' => 'required',
                    'ratting' => 'required',
                    'check_in' => 'required',
                    'check_out' => 'required',
                    'short_description' => 'required',
                    'address' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'policy' => 'required',
                    'description' => 'required',
                    'booking_prefix' => 'required',
                    'country' => 'required|string|max:255',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            
            // Define the data to be updated or inserted
            $data = [
                'key' => 'booking_prefix',
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => $request->booking_prefix]);

            // Settings Cache forget
            comapnySettingCacheForget();

            $hotel = Hotels::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->first();

            if(isset($request->is_active) && $request->is_active == "on"){
                $request['is_active'] = 1;
            }elseif(isset($request->is_active) && $request->is_active == "off"){
                $request['is_active'] = 0;
            }

            if (isset($hotel) && !empty($hotel)) {
                if ($request->has('hotel_logo')) {
                    $dir = 'hotel_logo/';
                    $Name = 'hotel_logo_' . time() . '.' . $request->hotel_logo->getClientOriginalExtension();
                    $path = upload_file($request, 'hotel_logo', $Name, $dir, []);
                    if ($path['flag'] != 1) {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    $request['image'] = $Name;
                    
                    $old_logo = $hotel->logo;
                    if (check_file('uploads/hotel_logo/'. $old_logo)) {
                        delete_file('uploads/hotel_logo/'. $old_logo);
                    }
                }
                if ($request->has('hotel_invoice_logo')) {
                    $dir = 'hotel_logo/';
                    $Name2 = 'invoice_logo_' . time() . '.' . $request->hotel_invoice_logo->getClientOriginalExtension();
                    $path = upload_file($request, 'hotel_invoice_logo', $Name2, $dir, []);
                    if ($path['flag'] != 1) {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    $request['invoice_image'] = $Name2;

                    $old_logo = $hotel->invoice_logo;
                    if (check_file('uploads/hotel_logo/'. $old_logo)) {
                        delete_file('uploads/hotel_logo/'. $old_logo);
                    }
                }

                $hotel->name = $request->name;
                $hotel->workspace = getActiveWorkSpace();
                $hotel->email = $request->email;
                $hotel->phone = $request->phone;
                $hotel->ratting = $request->ratting;
                $hotel->check_in = $request->check_in;
                $hotel->check_out = $request->check_out;
                $hotel->short_description = $request->short_description;
                $hotel->city = $request->city;
                $hotel->state = $request->state;
                $hotel->address = $request->address;
                $hotel->zip_code = $request->zip_code;
                $hotel->description = $request->description;
                $hotel->logo = ($request->image) ? $request->image : $hotel->logo;
                $hotel->invoice_logo = ($request->invoice_image) ? $request->invoice_image : $hotel->invoice_logo;
                $hotel->hotel_theme = ($request->hotel_theme) ? $request->hotel_theme : 'theme1-v1';
                $hotel->theme_dir = ($request->theme_dir) ? $request->theme_dir : 'theme1';
                $hotel->created_by = creatorId();
                $hotel->is_active = $request->is_active;
                $hotel->policy = $request->policy;
                $hotel->country = $request->country;

                $hotel->update();

                event(new UpdateHotel($request,$hotel));
            } else {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email',
                        'phone' => 'required',
                        'ratting' => 'required',
                        'check_in' => 'required',
                        'check_out' => 'required',
                        'short_description' => 'required',
                        'address' => 'required',
                        'state' => 'required',
                        'city' => 'required',
                        'zip_code' => 'required',
                        'policy' => 'required',
                        'description' => 'required',
                        'booking_prefix' => 'required',
                    ]
                );
    
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $request['created_by'] = creatorId();
                if ($request->has('hotel_logo')) {
                    $dir = 'hotel_logo/';
                    $Name = 'hotel_logo_' . time() . '.' . $request->hotel_logo->getClientOriginalExtension();
                    $path = upload_file($request, 'hotel_logo', $Name, $dir, []);
                    if ($path['flag'] != 1) {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    $request['image'] = $Name;
                }
                if ($request->has('hotel_invoice_logo')) {
                    $dir = 'hotel_logo/';
                    $Name = 'invoice_logo_' . time() . '.' . $request->hotel_invoice_logo->getClientOriginalExtension();
                    $path = upload_file($request, 'hotel_invoice_logo', $Name, $dir, []);
                    if ($path['flag'] != 1) {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    $request['invoice_logo'] = $Name;
                }
                $hotel = new Hotels();
                $hotel->name = $request->name;
                $hotel->workspace = getActiveWorkSpace();
                $hotel->email = $request->email;
                $hotel->phone = $request->phone;
                $hotel->ratting = $request->ratting;
                $hotel->check_in = $request->check_in;
                $hotel->check_out = $request->check_out;
                $hotel->short_description = $request->short_description;
                $hotel->city = $request->city;
                $hotel->state = $request->state;
                $hotel->address = $request->address;
                $hotel->enable_storelink = 'on';
                $hotel->zip_code = $request->zip_code;
                $hotel->description = $request->description;
                $hotel->logo = ($request->image) ? $request->image : null;
                $hotel->invoice_logo = ($request->invoice_logo) ? $request->invoice_logo : null;
                $hotel->hotel_theme = ($request->hotel_theme) ? $request->hotel_theme : 'theme1-v1';
                $hotel->theme_dir = ($request->theme_dir) ? $request->theme_dir : 'theme1';
                $hotel->policy = $request->policy;
                $hotel->created_by = creatorId();
                $hotel->is_active = $request->is_active;
                $hotel->country = $request->country ?? 'India';

                $hotel->save();

                event(new CreateHotel($request,$hotel));

                //Email notification
                if(!empty(company_setting('New Hotel')) && company_setting('New Hotel')  == true)
                {
                    $uArr = [
                        'hotel_email' => $request->email,
                        'hotel_name' => $request->name,
                        'hotel_contact' => $request->phone,
                    ];

                    try
                    {
                        $resp = EmailTemplate::sendEmailTemplate('New Hotel', [$hotel->id => $hotel->email],$uArr);
                    }
                    catch(\Exception $e)
                    {
                        $resp['error'] = $e->getMessage();
                    }

                    return redirect()->back()->with('success', __('Hotel setting save sucessfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                }
                return redirect()->back()->with('success', __('Hotel setting save sucessfully. email notification is off.'));
            }
            return redirect()->back()->with('success', __('Hotel setting save sucessfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
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
