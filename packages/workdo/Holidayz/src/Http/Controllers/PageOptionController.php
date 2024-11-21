<?php

namespace Workdo\Holidayz\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\Holidayz\DataTables\PageOptionDataTable;
use Workdo\Holidayz\Entities\PageOptions;
use Workdo\Holidayz\Events\CreatePageOption;
use Workdo\Holidayz\Events\DestroyPageOption;
use Workdo\Holidayz\Events\UpdatePageOption;

class PageOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PageOptionDataTable $dataTable)
    {
        if (\Auth::user()->isAbleTo('custom pages manage')) {
            return $dataTable->render('holidayz::pageoption.index');
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
        if (\Auth::user()->isAbleTo('custom pages create')) {
            return view('holidayz::pageoption.create');
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
        if (\Auth::user()->isAbleTo('custom pages create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100|unique:hotel_page_options,name,NULL,id,workspace,' . getActiveWorkSpace(),
                ],
                ['name.unique' => 'Sorry, This Name Is Already Exists']
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $data     = [
                'name' => $request->name,
                'contents' => $request->contents,
                'workspace' => getActiveWorkSpace(),
                'enable_page_header' => !empty($request->enable_page_header) ? $request->enable_page_header : 'off',
                'enable_page_footer' => !empty($request->enable_page_footer) ? $request->enable_page_footer : 'off',
                'created_by' => creatorId(),
            ];
            $custom_page = PageOptions::create($data);
            event(new CreatePageOption($request,$custom_page));
            return redirect()->back()->with('success', __('Custom page has been created successfully.'));
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
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (\Auth::user()->isAbleTo('custom pages edit')) {
            $pageOption = PageOptions::find($id);
            return view('holidayz::pageoption.edit',['pageOption' => $pageOption]);
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
        if (\Auth::user()->isAbleTo('custom pages edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100|unique:hotel_page_options,name,' .$id . ',id,workspace,' . getActiveWorkSpace(),
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            PageOptions::find($id)->update($request->all());
            $custom_page = PageOptions::find($id);
            event(new UpdatePageOption($request,$custom_page));
            return redirect()->back()->with('success', __('Custom page details are updated successfully.'));
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
        if (\Auth::user()->isAbleTo('custom pages delete')) {
            $custom_page = PageOptions::find($id);
            PageOptions::find($id)->delete();
            event(new DestroyPageOption($custom_page));
            return redirect()->back()->with('success', __('Custom page has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
