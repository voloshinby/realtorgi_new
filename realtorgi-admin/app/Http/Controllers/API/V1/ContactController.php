<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Contacts;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
    protected $contact = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Contacts $contact)
    {
        //$this->middleware('auth:api');
        $this->contact = $contact;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = $this->contact->latest()->paginate(1000);

        return $this->sendResponse($contacts, 'Messages list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contact = $this->contact->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'text' => $request->get('text')
        ]);

        return $this->sendResponse($contact, 'Message Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = $this->contact->findOrFail($id);

        return $this->sendResponse($contact, 'Message Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contact = $this->contact->findOrFail($id);

        $contact->update([
            'status' => 'read'
        ]);

        return $this->sendResponse($contact, 'Message Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        //$this->authorize('isAdmin');

        $contact = $this->contact->findOrFail($id);

        $contact->delete();

        return $this->sendResponse($contact, 'Message has been Deleted');
    }
}
