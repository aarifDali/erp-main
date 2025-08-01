<?php

namespace Workdo\AssistNow\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Workdo\AssistNow\DataTables\AssistnowClientDataTable;
use Workdo\AssistNow\Entities\AssistnowClient;
use Workdo\AssistNow\Entities\AssistnowDebtor;
use Workdo\AssistNow\Entities\ClientRelation;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AssistnowClientDataTable $dataTable)
    {
        $clients = AssistnowClient::with('relations')->where(['workspace'=>getActiveWorkSpace()])->get();
        return $dataTable->render('assistnow::clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $debtors = AssistnowDebtor::where(['created_by' => creatorId(), 'workspace' => getActiveWorkSpace()])->get();
        return view('assistnow::clients.create', ['debtors' => $debtors]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string|unique:assistnow_clients,client_id',
            'name' => 'required|string|max:255',
            'debtor_id' => 'nullable|exists:assistnow_debtors,id',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'email' => 'nullable|email|unique:assistnow_clients,email',
            'workspace' => 'nullable|integer',
            'created_by' => 'integer',
            'contacts' => 'nullable|array',
            'contacts.*.contact_name' => 'required_with:contacts|string|max:255',
            'contacts.*.relationship' => 'required_with:contacts|string|max:255',
            'contacts.*.phone' => 'required_with:contacts|string|max:20',
            'contacts.*.phone_2' => 'nullable|string|max:20',
            'contacts.*.phone_extra' => 'nullable|string|max:20',
            'contacts.*.email' => 'required_with:contacts|email|max:255',
        ]);
    
    
        $client = new AssistnowClient();
        $client->client_id = $request->client_id;
        $client->name = $request->name;
        $client->debtor_id = $request->debtor_id;
        $client->phone = $request->phone;
        $client->email = $request->email;
        $client->workspace = getActiveWorkSpace();
        $client->created_by = creatorId();
        $client->save();
    
        // If contacts exist, insert them
        if ($request->has('contacts')) {
            foreach ($request->contacts as $contact) {
                ClientRelation::create([
                    'client_id' => $client->id,
                    'contact_name' => $contact['contact_name'],
                    'relationship' => $contact['relationship'],
                    'phone' => $contact['phone'],
                    'phone_2' => $contact['phone_2'] ?? null,
                    'phone_extra' => $contact['phone_extra'] ?? null,
                    'email' => $contact['email'],
                ]);
            }
        }
        return redirect()->route('assistnow-clients.index')->with('success', __('The Client has been created successfully.'));
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

        $client = AssistnowClient::with('relations', 'debtor')->findOrFail($id);
        $debtors = AssistnowDebtor::where(['created_by' => creatorId(), 'workspace' => getActiveWorkSpace()])->get();

        return view('assistnow::clients.edit', compact('client', 'debtors'));
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'client_id' => 'required|string|unique:assistnow_clients,client_id,' . $id,
            'name' => 'required|string|max:255',
            'debtor_id' => 'nullable|exists:assistnow_debtors,id',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'email' => 'nullable|email|unique:assistnow_clients,email,' . $id,
            'workspace' => 'nullable|integer',
            'created_by' => 'integer',
            'contacts' => 'nullable|array',
            'contacts.*.id' => 'nullable|exists:client_relations,id',
            'contacts.*.contact_name' => 'required_with:contacts|string|max:255',
            'contacts.*.relationship' => 'required_with:contacts|string|max:255',
            'contacts.*.phone' => 'required_with:contacts|string|max:20',
            'contacts.*.phone_2' => 'nullable|string|max:20',
            'contacts.*.phone_extra' => 'nullable|string|max:20',
            'contacts.*.email' => 'required_with:contacts|email|max:255',
        ]);

        $client = AssistnowClient::findOrFail($id);

        $client->update([
            'client_id' => $request->client_id,
            'name' => $request->name,
            'debtor_id' => $request->debtor_id,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        $existingClientRelationIds = $client->relations->pluck('id')->toArray();
        $updatedClientRelationIds = [];

        foreach ($request->contacts as $contact) {
            if (isset($contact['id']) && in_array($contact['id'], $existingClientRelationIds)) {
                // Update existing contact
                $existingContact = ClientRelation::findOrFail($contact['id']);
                $existingContact->update([
                    'contact_name' => $contact['contact_name'],
                    'relationship' => $contact['relationship'],
                    'phone' => $contact['phone'],
                    'phone_2' => $contact['phone_2'] ?? null,
                    'phone_extra' => $contact['phone_extra'] ?? null,
                    'email' => $contact['email'],
                ]);
                $updatedClientRelationIds[] = $contact['id'];
            } else {
                // Create new contact
                $newContact = ClientRelation::create([
                    'client_id' => $client->id,
                    'contact_name' => $contact['contact_name'],
                    'relationship' => $contact['relationship'],
                    'phone' => $contact['phone'],
                    'phone_2' => $contact['phone_2'] ?? null,
                    'phone_extra' => $contact['phone_extra'] ?? null,
                    'email' => $contact['email'],
                ]);
                $updatedClientRelationIds[] = $newContact->id;
            }
        }

        // Delete removed contacts
        $contactsToDelete = array_diff($existingClientRelationIds, $updatedClientRelationIds);
        ClientRelation::whereIn('id', $contactsToDelete)->delete();

        return redirect()->route('assistnow-clients.index')->with('success', __('The Client has been updated successfully.'));
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = AssistnowClient::with('relations')->findOrFail($id);
        $client->delete();

        return redirect()->route('assistnow-clients.index')->with('success', __('The Client has been deleted successfully.'));
    }
}
