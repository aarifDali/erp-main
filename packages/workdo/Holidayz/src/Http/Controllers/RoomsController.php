<?php

namespace Workdo\Holidayz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\Entities\Rooms;
use Illuminate\Support\Facades\File;
use Workdo\Holidayz\DataTables\RoomsDataTable;
use Workdo\Holidayz\Entities\Hotels;
use Workdo\Holidayz\Entities\HotelServices;
use Workdo\Holidayz\Entities\RoomBooking;
use Workdo\Holidayz\Entities\RoomBookingOrder;
use Workdo\Holidayz\Entities\RoomsFacilities;
use Workdo\Holidayz\Entities\RoomsFeatures;
use Workdo\Holidayz\Entities\RoomsImages;
use Workdo\Holidayz\Events\CreateRoom;
use Workdo\Holidayz\Events\DestroyRoom;
use Workdo\Holidayz\Events\UpdateRoom;
use Workdo\Holidayz\Entities\ApartmentType;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(RoomsDataTable $dataTable)
    {
        if (\Auth::user()->isAbleTo('rooms manage')) {
            $rooms = Rooms::with(['getImages'])->with(['apartmentType'])->where(['workspace' => getActiveWorkSpace()])->get();
            return $dataTable->render('holidayz::rooms.index', ['rooms' => $rooms]);
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
        $apartmentTypes = ApartmentType::all();
        return view('holidayz::rooms.create', compact('apartmentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('rooms create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'apartment_type_id' => 'required|exists:apartment_types,id',
                    'room_type' => 'required|max:120',
                    'adults' => 'required|numeric',
                    'children' => 'required|numeric',
                    'total_room' => 'required',
                    'base_price' => 'required',
                    'file' => 'required',
                    'final_price' => 'required',
                ],[
                    'apartment_type_id.required' => 'Apartment type is missing.',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'flag' => 'error',
                    'status' => false,
                    'msg' => $messages->first()
                ]);
            }

            if ($request->has('file')) {
                $Name = time() . $request->file->getClientOriginalExtension();
                $path = upload_file($request, 'file', $Name, 'rooms', []);
                if ($path['flag'] != 1) {
                    return response()->json([
                        'flag' => 'error',
                        'status' => false,
                        'msg' => $path['msg']
                    ]);
                }
                $request['image'] = $Name;
            }
            $request['created_by'] = creatorId();
            $request['workspace'] = getActiveWorkSpace();
            $request['is_active'] = ($request->status) ? 1 : 0;
            $file_name = [];
            if (!empty($request->multiple_files) && count($request->multiple_files) > 0) {
                foreach ($request->multiple_files as $key => $file) {
                    $filenameWithExt = $file->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $file->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $myRequest = new Request();
                    $myRequest->request->add(['image' => $file]);
                    $myRequest->files->add(['image' => $file]);
                    $uplaod = upload_file($myRequest, 'image', $fileNameToStore, 'rooms', []);

                    if ($uplaod['flag'] == 1) {
                        $file_name[] = $uplaod['url'];
                    } else {
                        return response()->json([
                            'flag' => 'error',
                            'status' => false,
                            'msg' => $uplaod['msg']
                        ]);
                    }
                }
            }

            $room = Rooms::create($request->all());

            if (!empty($file_name)) {
                foreach ($file_name as $file) {
                    $RoomsImages = new RoomsImages();
                    $RoomsImages->room_id = $room->id;
                    $RoomsImages->name = $file;
                    $RoomsImages->workspace = getActiveWorkSpace();
                    $RoomsImages->created_by = creatorId();
                    $RoomsImages->save();
                }
            }

            event(new CreateRoom($request,$room));
             // Redirect to index with success message
                return redirect()->back()->with('success', __('Room has been created successfully.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied!'));
            }
        //     return response()->json([
        //         'flag' => 'success',
        //         'status' => true,
        //         'msg' =>  __('Room has been created successfully.')
        //     ]);
        // } else {
        //     return response()->json([
        //         'flag' => 'error',
        //         'status' => false,
        //         'msg' =>  __('Permission denied!')
        //     ]);
        // }
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
    public function edit(Rooms $rooms, $id)
    {
        if (\Auth::user()->isAbleTo('rooms edit')) {
            $apartmentTypes = ApartmentType::all();
            $rooms = Rooms::with(['getImages'])->find($id);
            return view('holidayz::rooms.edit', ['room' => $rooms, 'apartmentTypes' => $apartmentTypes]);
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

        if (\Auth::user()->isAbleTo('rooms update')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'apartment_type_id' => 'required|exists:apartment_types,id',
                    'room_type' => 'required|max:120',
                    'adults' => 'required|numeric',
                    'children' => 'required|numeric',
                    'total_room' => 'required',
                    'base_price' => 'required',
                    'final_price' => 'required',
                ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'flag' => 'error',
                    'status' => false,
                    'msg' => $messages->first()
                ]);
            }
            
            $room = Rooms::find($id);
            if ($request->has('file')) {
                $dir = 'rooms/';
                $Name = ($room->image) ? $room->image : time() . rand(1, 9999) . '.' . $request->file->getClientOriginalExtension();
                $path = upload_file($request, 'file', $Name, $dir, []);
                if ($path['flag'] != 1) {
                    return response()->json([
                        'flag' => 'error',
                        'status' => false,
                        'msg' => $path['msg']
                    ]);
                }
                $request['image'] = $Name;
            }
            $file_name = [];
            if (!empty($request->multiple_files) && count($request->multiple_files) > 0) {
                foreach ($request->multiple_files as $key => $file) {
                    $filenameWithExt = $file->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $file->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $myRequest = new Request();
                    $myRequest->request->add(['image' => $file]);
                    $myRequest->files->add(['image' => $file]);
                    $uplaod = upload_file($myRequest, 'image', $fileNameToStore, 'rooms', []);
                    if ($uplaod['flag'] == 1) {
                        $file_name[] = $uplaod['url'];
                    } else {
                        return response()->json([
                            'flag' => 'error',
                            'status' => false,
                            'msg' => $uplaod['msg']
                        ]);
                    }
                }
            }

          if (!empty($file_name)) {
                foreach ($file_name as $file) {
                    $RoomsImages = new RoomsImages();
                    $RoomsImages->room_id = $room->id;
                    $RoomsImages->name = $file;
                    $RoomsImages->workspace = getActiveWorkSpace();
                    $RoomsImages->created_by = creatorId();
                    $RoomsImages->save();
                }
            }
            $request['is_active'] = ($request->status) ? 1 : 0;
            $room->update($request->all());

            $room = Rooms::find($id);
            event(new UpdateRoom($request,$room));
            return response()->json([
                'flag' => 'success',
                'msg' => __('Room details are updated successfully.')
            ]);
        } else {
            return response()->json([
                'flag' => 'fail',
                'msg' => __('Permission denied')
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (\Auth::user()->isAbleTo('rooms delete')) {
            $room = Rooms::find($id);
            $invoice_room=RoomBookingOrder::where('room_id',$id)->first();
            if(empty($invoice_room)){
                $destinationPath = 'uploads/rooms/' . $room->image;
                if (File::exists($destinationPath)) {
                    File::delete($destinationPath);
                }
                $files = RoomsImages::where('room_id', $id)->get();
                $destinationImagePath = 'uploads/rooms/';
                foreach ($files as $key => $file) {
                    if (File::exists($destinationImagePath . $file->name)) {
                        File::delete($destinationImagePath . $file->name);
                    }
                    $file->delete();
                }
                $room->delete();
                event(new DestroyRoom($room));
                return redirect()->back()->with('success', __('Room has been deleted.'));
            }else{

                return redirect()->back()->with('error', __('Please delete'.(!empty($invoice_room) ? ' Booking ' : '').'related record of this Room.'));

            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function imageDelete($id)
    {
        if (\Auth::user()->isAbleTo('rooms delete')) {
            $image = RoomsImages::find($id);
            $destinationPath = 'storage/uploads/rooms/' . $image->name;
            if (File::exists($destinationPath)) {
                File::delete($destinationPath);
            }
            $image->delete();
            return response()->json([
                'id' => $id,
                'success' => true,
                'message' =>  __('Image has been deleted.')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' =>  __('Permission denied!')
            ]);
        }
    }

    public function searchRooms(Request $request,$slug)
    {

        $validator = \Validator::make($request->all(), [
            'date' => 'required'
        ]);
        if($validator->fails()){
            return redirect()->route('hotel.home',$slug)->withErrors(["error" => "Date is required!"]);
        }

        $hotel = Hotels::where(['slug' => $slug,'is_active' => 1])->first();
        if($hotel){
            $getHotelThemeSetting = \Workdo\Holidayz\Entities\Utility::getHotelThemeSetting($hotel->workspace, $hotel->theme_dir);
            $getHotelThemeSetting1 = [];
            if (!empty($getHotelThemeSetting['dashboard'])) {
                $getHotelThemeSetting1 = $getHotelThemeSetting;
                $getHotelThemeSetting = json_decode($getHotelThemeSetting['dashboard'], true);
            }
            if (empty($getHotelThemeSetting)) {
                $path = asset('packages/workdo/Holidayz/src/Resources/assets/'. $hotel->theme_dir . "/" . $hotel->theme_dir . ".json" );

                $getHotelThemeSetting = json_decode(file_get_contents($path), true);
            }
            $rooms = Rooms::with(['features'])->where(['workspace' => $hotel->workspace]);
            $HotelroomsAllData = $rooms->get();
            
            if($request->date != null){

                $date = explode('to', $request->date);
                if(!empty($date[0]) && !empty($date[1])){
                    $startDate = trim($date[0]);
                    $endDate = trim($date[1]);

                    $bookingIds = RoomBookingOrder::where('workspace',$hotel->workspace)->where('check_in','<=',$startDate)->where('check_out','>=',$endDate)->get()->pluck('room_id');
                    $rooms= $rooms->whereNotIn('id',$bookingIds);
                    $value = [
                        'check_in' => $startDate,
                        'check_out' => $endDate,
                        'room' => $request->rooms
                    ];
                    session(['date' => $value]);
                }else{
                    return redirect()->back()->with('error', __('Please Select Check In - Check Out Both Date'));
                }
            }
            
            if($request->rooms > 0){
                $rooms= $rooms->where('total_room','>=',$request->rooms);
            }

            if($request->adult > 0){
                $rooms = $rooms->where('adults','>=',$request->adult);
            }
            if($request->children > 0){
                $rooms= $rooms->where('children','>=',$request->children);
            }
            if(isset($request->roomtype)){
                $rooms= $rooms->whereIn('room_type',$request->roomtype);
            }
            if(isset($request->min_price) && isset($request->max_price)){
                $rooms= $rooms->whereBetween('final_price',[$request->min_price,$request->max_price]);
            }
            if(isset($request->price)){
                $rooms= $rooms->where('final_price',$request->price);
            }

            if($request->show_only){
                $roombookingIds = RoomBooking::where(['workspace' => $hotel->workspace])->get()->pluck('room_id');
                if($request->show_only == 'availble'){
                    $rooms= $rooms->whereNotIn('id',$roombookingIds);
                }
                if($request->show_only == 'booking'){
                    $rooms= $rooms->whereIn('id',$roombookingIds);
                }
            }
            $rooms = $rooms->where('is_active','1')->get();
            $data = [
                'hotel' => $hotel,
                'slug' => $slug,
                'getHotelThemeSetting' => $getHotelThemeSetting,
                'getHotelThemeSetting1' => $getHotelThemeSetting1,
                'rooms' => $rooms,
                'features' => RoomsFeatures::where('workspace' , $hotel->workspace)->get(),
                'HotelroomsAllData' => $HotelroomsAllData,
                'Totalrooms' => $request->rooms,
                'Totaladult' => $request->adult,
                'Totalchildren' => $request->children,
                'minPrice' => $request->min_price,
                'maxPrice' => $request->max_price,
                'price' => $request->price,
                'showOnly' => $request->show_only,
                'featuresfilter' => $request->features,
                'date' => $request->date
            ];
            return view('holidayz::frontend.'.$hotel->theme_dir.'.searchrooms',$data);
        }else{
            return redirect()->back()->with('error', __('Room Not Found'));
        }
    }


    public function details(Request $request,$slug,$id)
    {
        $hotel = Hotels::where(['slug' => $slug,'is_active' => 1])->first();
        if($hotel){
            $room = Rooms::where('id',$id)->where('is_active','1')->first();
            if($room != null)
            {
                $facilities = RoomsFacilities::with(['getChildFacilities'])->where('workspace',$hotel->workspace)->where('status','1')->get();
                $hotelFeatures = HotelServices::where(['workspace' => $hotel->workspace,'is_active' => 1])->get();
                $data = [
                    'hotel' => $hotel,
                    'slug' => $slug,
                    'room' => $room,
                    'rooms' =>   $rooms = Rooms::with(['features'])->where(['workspace' => $hotel->workspace])->where('is_active','1')->get(),
                    'features' => RoomsFeatures::where('workspace',$hotel->workspace)->get(),
                    'facilities' => $facilities,
                    'hotelFeatures' => $hotelFeatures
                ];
                return view('holidayz::frontend.'.$hotel->theme_dir.'.room.details',$data);
            }else{
                return redirect()->back()->with('error', __('Room Not Found'));
            }
        }else{
            return redirect()->back()->with('error', __('Room Not Found'));
        }
    }

}
