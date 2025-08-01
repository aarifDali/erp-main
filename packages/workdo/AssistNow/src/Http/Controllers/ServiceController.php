<?php

namespace Workdo\AssistNow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Square\Http\HttpResponse;
use Workdo\AssistNow\Entities\AssistnowService;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = AssistnowService::where([
            'workspace' => getActiveWorkSpace(),
            'created_by' => creatorId()
        ])->get(); 

        return view('assistnow::services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('assistnow::services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'billing_interval' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'workspace' => 'nullable|integer',
            'created_by' => 'integer',
        ]);

        $service = new AssistnowService();
        $service->name = $request->input('name');
        $service->billing_interval = $request->input('billing_interval');
        $service->description = $request->input('description');
        $service->workspace = getActiveWorkSpace(); 
        $service->created_by = creatorId(); 
        $service->save();

        return redirect()->route('assistnow-services.index')
            ->with('success', 'Service created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $service = AssistnowService::findOrFail($id);
        return view('assistnow::services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'billing_interval' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);
    
        try {
            // Find the service
            $service = AssistNowService::findOrFail($id);
    
            // Update service details
            $service->update([
                'name' => $request->name,
                'billing_interval' => $request->billing_interval,
                'description' => $request->description,
            ]);
    
            // Return success response
            return redirect()->route('assistnow-services.index')
                ->with('success', 'Service updated successfully!');
    
        } catch (\Exception $e) {
            // Handle errors
            return redirect()->route('assistnow-services.index')
                ->with('error', __('Failed to update service. Please try again.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = AssistNowService::findOrFail($id);

        $service->delete();

        return redirect()->route('assistnow-services.index')
            ->with('success', 'Service deleted successfully!');
    }
}
