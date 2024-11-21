<?php

namespace Workdo\Holidayz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\DataTables\RoomFeaturesDataTable;
use Workdo\Holidayz\Entities\RoomsFeatures;
use Workdo\Holidayz\Events\CreateRoomFeature;
use Workdo\Holidayz\Events\DestroyRoomFeature;
use Workdo\Holidayz\Events\UpdateRoomFeature;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(RoomFeaturesDataTable $dataTable)
    {
        if (\Auth::user()->isAbleTo('feature manage')) {
            return $dataTable->render('holidayz::features.index');
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
        if (\Auth::user()->isAbleTo('feature create')) {
            return view('holidayz::features.create');
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
        if (\Auth::user()->isAbleTo('feature create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $request['created_by'] = \auth::user()->id;
            $request['workspace'] = getActiveWorkSpace();
            $feature = RoomsFeatures::create($request->all());

            event(new CreateRoomFeature($request,$feature));
            return redirect()->back()->with('success', __('Feature has been created successfully.'));
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
        if (\Auth::user()->isAbleTo('feature edit')) {
            $feature = RoomsFeatures::find($id);
            return view('holidayz::features.edit', ['feature' => $feature]);
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
        if (\Auth::user()->isAbleTo('feature edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $savefeature = RoomsFeatures::find($id);
            $savefeature->name = $request->name;
            $savefeature->icon = isset($request->icon)&&!empty($request->icon)?$request->icon:$request->old_icon;
            $savefeature->save();
            
            $feature = RoomsFeatures::find($id);
            event(new UpdateRoomFeature($request,$feature));
            return redirect()->back()->with('success', __('Feature details are updated successfully.'));
        } else {
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
        if (\Auth::user()->isAbleTo('feature delete')) {
            $feature = RoomsFeatures::find($id);
            RoomsFeatures::find($id)->delete();

            event(new DestroyRoomFeature($feature));
            return redirect()->back()->with('success', __('Feature has been deleted.'));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
