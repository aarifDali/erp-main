<?php

namespace Workdo\Holidayz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\DataTables\RoomFacilitiesDataTable;
use Workdo\Holidayz\Entities\RoomsChildFacilities;
use Workdo\Holidayz\Entities\RoomsFacilities;
use Workdo\Holidayz\Events\CreateRoomFacility;
use Workdo\Holidayz\Events\DestroyRoomFacility;
use Workdo\Holidayz\Events\UpdateRoomFacility;
use Workdo\ProductService\Entities\Tax;

class FacilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(RoomFacilitiesDataTable $dataTable)
    {
        if (\Auth::user()->isAbleTo('facilities manage')) {
            return $dataTable->render('holidayz::facilities.index');
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
        if (\Auth::user()->isAbleTo('facilities create')) {
            $taxes = Tax::where('created_by', '=', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('holidayz::facilities.create', ['taxes' => $taxes]);
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
        if (\Auth::user()->isAbleTo('facilities create')) {
            unset($request['_token']);

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'child_facilities' => 'required',
                ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $facility = new RoomsFacilities();
            $facility->name = $request->name;
            $facility->short_description = $request->short_description;
            $facility->tax_id = !empty($request->tax_id) ? implode(',', $request->tax_id) : '';
            $facility->status = ($request->status) ? 1 : 0;
            $facility->workspace = getActiveWorkSpace();
            $facility->created_by = creatorId();
            $facility->save();
            foreach ($request->child_facilities as $key => $value) {
                RoomsChildFacilities::create([
                    'facilities_id' => $facility->id,
                    'name' => $value['sub_name'],
                    'price' => $value['sub_price'],
                    'created_by' => creatorId(),
                    'workspace' => getActiveWorkSpace()
                ]);
            }

            event(new CreateRoomFacility($request,$facility));
            return redirect()->back()->with('success', __('Facility has been created successfully.'));
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
        if (\Auth::user()->isAbleTo('facilities edit')) {
            $facilitty = RoomsFacilities::with(['getChildFacilities'])->find($id);

            $taxes = Tax::where('created_by', '=', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
            if(count($taxes) == 0){
                $taxes = [0 => 'No Tax'];
            }
            $facilitty->tax_id = explode(',', $facilitty->tax_id);
            return view('holidayz::facilities.edit',['facilitty' => $facilitty,'taxes' => $taxes]);
        }else{
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
        if (\Auth::user()->isAbleTo('facilities edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'child_facilities' => 'required',
                ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            
            $facility = RoomsFacilities::find($id);
            $facility->name = $request->name;
            $facility->short_description = $request->short_description;
            $facility->tax_id = !empty($request->tax_id) ? implode(',', $request->tax_id) : '';
            $facility->status = ($request->status) ? 1 : 0;
            $facility->workspace = getActiveWorkSpace();
            $facility->created_by = creatorId();
            $facility->update();
            RoomsChildFacilities::where('facilities_id',$id)->delete();
            foreach($request->child_facilities as $key => $value){
                RoomsChildFacilities::create([
                    'facilities_id' => $id,
                    'name' => $value['sub_name'],
                    'price' => $value['sub_price'],
                    'created_by' => creatorId(),
                    'workspace' => getActiveWorkSpace()
                ]);
            }

            event(new UpdateRoomFacility($request,$facility));
            return redirect()->back()->with('success', __('Facility are updated successfully.'));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (\Auth::user()->isAbleTo('facilities delete')) {
            $facility = RoomsFacilities::find($id);
            RoomsFacilities::find($id)->delete();

            event(new DestroyRoomFacility($facility));
            return redirect()->back()->with('success', __('Facility has been deleted.'));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
