<?php

namespace Workdo\Holidayz\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Workdo\Holidayz\DataTables\HotelCustomerDataTable;
use Workdo\Holidayz\Entities\Addresses;
use Workdo\Holidayz\Entities\HotelCustomer;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\RoomBooking;
use Workdo\Holidayz\Events\CreateHotelCustomer;
use Workdo\Holidayz\Events\DestroyHotelCustomer;
use Workdo\Holidayz\Events\UpdateHotelCustomer;


class HotelCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(HotelCustomerDataTable $dataTable)
    {
        if (\Auth::user()->isAbleTo('hotel customer manage')) {
            return $dataTable->render('holidayz::hotel_customer.customers.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (\Auth::user()->isAbleTo('hotel customer create')) {
            if(module_is_active('CustomField')){
                $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'Holidayz')->where('sub_module','Hotel Customers')->get();
            }else{
                $customFields = null;
            }
            return view('holidayz::hotel_customer.customers.create', compact('customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('hotel customer create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'last_name' => 'required|max:120',
                    'email' => 'required|email|unique:hotel_customers,email',
                    'mobile_phone' => 'required',
                    'dob' => 'required|date',
                    'id_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
                ]
            );

            if ($validator->fails()) {
                // Check if the request is AJAX and return JSON error response
                if ($request->ajax()) {
                    return response()->json(['error' => $validator->errors()->first()], 422);
                }
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            if ($request->hasFile('id_proof')) {
                $fileName = time() . '_' . $request->id_proof->getClientOriginalName();
                $path = $request->file('id_proof')->storeAs('public/id_proofs', $fileName);
                
                if (!$path) {
                    return response()->json(['error' => 'File upload failed'], 500);
                }

                // Set the id_proof attribute to the filename
                $request['id_proof'] = $fileName;
            }

            $request['created_by'] = creatorId();
            $request['workspace'] = getActiveWorkSpace();
            $request['type'] = 'customer';
            $request['group_access'] = json_encode($request->group_access, true);

            $customer = HotelCustomer::create($request->all());
            $request['user_id'] = $customer->id;

            Addresses::create($request->all());

            if(module_is_active('CustomField'))
            {
                \Workdo\CustomField\Entities\CustomField::saveData($customer, $request->customField);
            } 

            $hotel = null;  
            event(new CreateHotelCustomer($request,$customer,$hotel));

            //Email notification
            if(!empty(company_setting('New Hotel Customer')) && company_setting('New Hotel Customer')  == true)
            {
                $uArr = ['hotel_customer_email' => $request->input('email')];

                try {
                    $resp = EmailTemplate::sendEmailTemplate('New Hotel Customer', [$customer->id => $customer->email],$uArr);
                } catch(\Exception $e){
                    $resp['error'] = $e->getMessage();
                }

                $message = __('The customer has been created successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : '');
            } else {
                $message = __('The customer has been created successfully. Email notification is off.');
            }

            // If AJAX, return JSON with customer data
            if ($request->ajax()) {
                return response()->json(['success' => $message, 'customer' => $customer]);
            }

            return redirect()->back()->with('success', $message);
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
        if (\Auth::user()->isAbleTo('hotel customer edit')) {
            $customer = HotelCustomer::with(['getAddresses'])->find($id);

            if ($customer->id_proof) {
                $customer->id_proof_path = asset('storage/id_proofs/' . $customer->id_proof);
            } else {
                $customer->id_proof_path = null;
            }

            if(module_is_active('CustomField')){
                $customer->customField = \Workdo\CustomField\Entities\CustomField::getData($customer, 'Holidayz','Hotel Customers');
                $customFields             = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Holidayz')->where('sub_module','Hotel Customers')->get();
            }else{
                $customFields = null;
            }
            return view('holidayz::hotel_customer.customers.edit', ['customer' => $customer], compact('customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (\Auth::user()->isAbleTo('hotel customer edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'last_name' => 'required|max:120',
                    'email' => 'required',
                    'dob' => 'required',
                    'id_proof' => 'file|mimes:jpeg,png,jpg,pdf|max:2048',
                ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $user =  HotelCustomer::find($id);
           
            if ($request->hasFile('id_proof')) {
                if ($user->id_proof) {
                    Storage::delete('public/id_proofs/' . $user->id_proof); 
                }

                $Name = time() . '_' . $request->id_proof->getClientOriginalName();
                $path = $request->file('id_proof')->storeAs('public/id_proofs', $Name); 
                
                if (!$path) {
                    return response()->json([
                        'flag' => 'error',
                        'status' => false,
                        'msg' => 'File upload failed'
                    ]);
                }

                $user->id_proof = $Name; 
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'last_name' => $request->last_name,
                'dob' => $request->dob,
                'group_access' => json_encode($request->group_access, true),
                'is_active' => $request->is_active,
                'newsletter' => ($request->newsletter == 1) ? 1 : 0,
                'opt_in' => ($request->opt_in == 1) ? 1 : 0,
                // 'id_number' => $request->id_number,
                // 'company' => $request->company,
                // 'vat_number' => $request->vat_number,
                'home_phone' => $request->home_phone,
                'mobile_phone' => $request->mobile_phone,
                'other' => $request->other,
                'id_proof' => $user->id_proof,
            ]);
            $address = Addresses::updateOrCreate(['user_id' => $id],[
                'alias' => $request->alias,
                'address' => $request->address,
                'address_2' => $request->address_2,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'state' => $request->state
            ]);

            if(module_is_active('CustomField'))
            {
                \Workdo\CustomField\Entities\CustomField::saveData($user, $request->customField);
            }
            $hotel = null;
            event(new UpdateHotelCustomer($request, $user, $hotel));
            return redirect()->back()->with('success', __('The customer details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denided'));
        }
    }


    public function profile(Request $request, $slug)
    {
        $hotel = Hotels::where('slug', $slug)->where('is_active', '1')->first();
        if ($hotel && auth()->guard('holiday')->user()) {
            return view('holidayz::frontend.' . $hotel->theme_dir . '.customer.profile', ['slug' => $slug]);
        } else {
            return redirect()->back()->with('error', __('Hotel Not Found.'));
        }
    }

    public function ProfileUpdate(Request $request, $slug)
    {

        $hotel = Hotels::where('slug', $slug)->where('is_active', '1')->first();
        if ($hotel) {
            $request->validate([
                'name' => 'required|max:8',
                'email' => 'required|max:100|unique:hotel_customers,name,' . auth()->guard('holiday')->user()->id . ',id,workspace,' . $hotel->workspace,
                'mobile_phone' => 'required'
            ]);
            try {

                $profile = HotelCustomer::find(auth()->guard('holiday')->user()->id);
                $avatar = null;
                if ($request->avatar != null && $request->has('avatar')) {
                    $dir = 'hotel-customer-avatar/';
                    $Name = ($profile->avatar) ? $profile->avatar : time() . rand(1, 9999) . '.' . $request->avatar->getClientOriginalExtension();
                    $path = upload_file($request, 'avatar', $Name, $dir, []);
                    $avatar = $Name;
                    if ($path['flag'] != 1) {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
                $profile = $profile->update([
                    'name'           => $request->name,
                    'email'          => $request->email,
                    'last_name'      => $request->last_name,
                    'dob'            => $request->dob,
                    'mobile_phone'   => $request->mobile_phone,
                    'avatar'         => $avatar
                ]);
                
                if ($profile) {
                    $customer = HotelCustomer::find(auth()->guard('holiday')->user()->id);
                    event(new UpdateHotelCustomer($request,$customer,$hotel));
                    return redirect()->route('customer.profile', $slug)->with('success', __('Profile Update Successfully'));
                } else {
                    return redirect()->back()->with('error', __('Profile Update Fail. Please try again'));
                }
            } catch (\Exception $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {
                    return redirect()->back()->with('error', __('Email Already Exist.'));
                } else {
                    return redirect()->back()->with('error', $e->getMessage());
                }
            }
        } else {
            return redirect()->back()->with('error', __('Hotel Not found'));
        }
    }


    public function passwordUpdate(Request $request, $slug)
    {
        $hotel = Hotels::where('slug', $slug)->where('is_active', '1')->first();
        if ($hotel) {
            $request->validate(
                [
                    'old_password' => ['required'],
                    'password' => ['required'],
                    'password_confirmation' => ['required', 'same:password'],
                ],
                [
                    'old_password.required' => __('Old Password field is required'),
                    'password.required' => __('password field is required'),
                    'password_confirmation.same' => __("Confirm password does's match")
                ]
            );

            if (!(Hash::check($request->get('old_password'), auth()->guard('holiday')->user()->password))) {
                return redirect()->back()->with("error", __("Your current password does not matches with the password you provided. Please try again."));
            }
            $customer = auth()->guard('holiday')->user();
            $customer->password = Hash::make($request->password);
            $customer->save();
            
            event(new UpdateHotelCustomer($request,$customer,$hotel));
            return redirect()->back()->with('success', __('Password Updated Successfully'));
        } else {
            return redirect()->back()->with('error', __('Hotel Not found'));
        }
    }

    public function CustomerBooking(Request $request, $slug)
    {
        $hotel = Hotels::where('slug', $slug)->where('is_active', '1')->first();
        if ($hotel) {
            $bookings = RoomBooking::with(['getCouponDetails', 'getRoomDetails', 'GetBookingOrderDetails'])->where('user_id', auth()->guard('holiday')->user()->id)->where('payment_status',1)->get();
            return view('holidayz::frontend.theme1.customer.booking', ['slug' => $slug, 'hotel' => $hotel, 'bookings' => $bookings]);
        } else {
            return redirect()->back()->with('error', __('Hotel Not found'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (\Auth::user()->isAbleTo('hotel customer delete')) {
            $customer = HotelCustomer::find($id);
            if(module_is_active('CustomField'))
            {
                $customFields = \Workdo\CustomField\Entities\CustomField::where('module','sales')->where('sub_module','quotes')->get();
                foreach($customFields as $customField)
                {
                    $value = \Workdo\CustomField\Entities\CustomFieldValue::where('record_id', '=', $customer->id)->where('field_id',$customField->id)->first();
                    if(!empty($value)){
                        $value->delete();
                    }
                }
            }
            HotelCustomer::find($id)->delete();

            Addresses::where('user_id', $id)->delete();
            event(new DestroyHotelCustomer($customer));
            return redirect()->back()->with('success', __('The customer has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function subscribe(Request $request,$slug)
    {
        return redirect()->back()->with('success', __('Subscribe successfully'));
        $hotel = Hotels::where('slug', $slug)->first();
        if ($hotel){
            $validator = \Validator::make(
                $request->all(),
                [
                    'email' => 'required|max:100|unique:subscribers,email,NULL,id,workspace,' . $hotel->workspace,
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->with('error', __('Email already Subscribe'));
            }

            $subscribe = Subscribers::create([
                'email' => $request->email,
                'workspace' => $hotel->workspace
            ]);
            return redirect()->back()->with('success', __('Subscribe successfully'));
        }else{
            return redirect()->back()->with('error', __('Hotel Not found'));
        }
    }

}
