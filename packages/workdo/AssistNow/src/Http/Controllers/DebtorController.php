<?php

namespace Workdo\AssistNow\Http\Controllers;

use Braintree\Http;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Square\Http\HttpResponse;
use Workdo\AssistNow\Entities\AssistnowDebtor;

class DebtorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $debtors = AssistnowDebtor::where([
            'workspace' => getActiveWorkSpace(),
            'created_by' => creatorId()
        ])->get(); 
        return view('assistnow::debtors.index', compact('debtors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('assistnow::debtors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'workspace' => 'nullable|integer',
            'created_by' => 'integer',
        ]);

        $debtor = new AssistnowDebtor();
        $debtor->name = $request->input('name');
        $debtor->workspace = getActiveWorkSpace(); 
        $debtor->created_by = creatorId(); 
        $debtor->save();

        return redirect()->route('assistnow-debtors.index')
            ->with('success', 'Debtor saved successfully!');
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
        $debtor = AssistnowDebtor::findOrFail($id);
        return view('assistnow::debtors.edit', compact('debtor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        try {
            // Find the service
            $debtor = AssistnowDebtor::findOrFail($id);
    
            // Update service details
            $debtor->update([
                'name' => $request->name,
            ]);
    
            // Return success response
            return redirect()->route('assistnow-debtors.index')
                ->with('success', 'Debtor updated successfully!');
    
        } catch (\Exception $e) {
            // Handle errors
            return redirect()->route('assistnow-debtors.index')
                ->with('error', __('Failed to update debtor. Please try again.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $debtor = AssistnowDebtor::findOrFail($id);
    
        if ($debtor->clients()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete debtor. Delete associated clients first.');
        }
    
        $debtor->delete();

        return redirect()->route('assistnow-debtors.index')
            ->with('success', 'Debtor deleted successfully!');
    }
}
