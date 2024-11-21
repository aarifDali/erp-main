<?php

namespace Workdo\Holidayz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\Entities\HotelServices;
use Workdo\Holidayz\Entities\HotelSubServices;
use Workdo\Holidayz\Events\CreateHotelService;
use Workdo\Holidayz\Events\DestroyHotelService;
use Workdo\Holidayz\Events\UpdateHotelService;

class HotelServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->isAbleTo('services manage')) {
            $services = HotelServices::with(['getSubServices'])->where(['workspace' => getActiveWorkSpace(), 'created_by' => creatorId()])->get()->all();
            return view('holidayz::services.index', ['services' => $services]);
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
        if (\Auth::user()->isAbleTo('services create')) {
            return view('holidayz::services.create');
        }else{
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
        if (\Auth::user()->isAbleTo('services create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'file' => 'required',
                    'category-group' => 'required',
                ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if ($request->has('file')) {
                $dir = 'amenities/';
                $Name = time() . rand(1, 9999) . '.' . $request->file->getClientOriginalExtension();
                $path = upload_file($request, 'file', $Name, $dir, []);
                if ($path['flag'] != 1) {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                $request['image'] = $Name;
            }
            $service = HotelServices::create([
                'name' => $request->name,
                'icon' => $request->icon,
                'image' => $request->image,
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId()
            ]);
            foreach ($request['category-group'] as $key => $value) {
                $subservice = new HotelSubServices();
                $subservice->name = $value['sub_services'];
                $subservice->service_id = $service->id;
                $subservice->is_active = 1;
                $subservice->save();
            }

            event(new CreateHotelService($request,$service));
            return redirect()->back()->with('success', __('Service has been created successfully.'));
        }else{
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
        if (\Auth::user()->isAbleTo('services edit')) {
            $service = HotelServices::with(['getSubServices'])->find($id);
            return view('holidayz::services.edit', ['service' => $service]);
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
        if (\Auth::user()->isAbleTo('services edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    // 'file' => 'required',
                    'category-group' => 'required',
                ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            
            $service = HotelServices::find($id);
            if ($request->has('file')) {
                $dir = 'amenities/';
                $Name = ($service->image) ? $service->image : time() . rand(1, 9999) . '.' . $request->file->getClientOriginalExtension();
                $path = upload_file($request, 'file', $Name, $dir, []);
                if ($path['flag'] != 1) {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                $request['image'] = $Name;
                $service->image = $request->image;
            }

            $service->icon = ($request->icon) ? $request->icon : $service->icon;
            $service->name = $request->name;
            $service->save();
            HotelSubServices::where('service_id', $id)->delete();
            if (isset($request['category-group'])) {
                foreach ($request['category-group'] as $key => $value) {
                    $subservice = new HotelSubServices();
                    $subservice->name = $value['sub_services'];
                    $subservice->service_id = $id;
                    $subservice->is_active = 1;
                    $subservice->save();
                }
            }

            event(new UpdateHotelService($request,$service));
            return redirect()->back()->with('success', __('Service are updated successfully.'));
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
        if (\Auth::user()->isAbleTo('services delete')) {
            $service = HotelServices::find($id);
            $service->delete();
            event(new DestroyHotelService($service));
            HotelSubServices::where('service_id',$id)->delete();
            return redirect()->back()->with('success', __('Service has been deleted.'));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
