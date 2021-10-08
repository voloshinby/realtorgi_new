<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\Feedback;


class FeedbackController extends BaseController
{

    protected $feedback = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Feedback $feedback)
    {
        // $this->middleware('auth:api');
        $this->feedback = $feedback;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbacks = $this->feedback->latest()->paginate(1000);

        return $this->sendResponse($feedbacks, 'Feedbacks list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $feedback = $this->feedback->create([
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'status' => 'new'
        ]);

        return $this->sendResponse($feedback, 'Feedback Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $feedback = $this->feedback->findOrFail($id);

        return $this->sendResponse($feedback, 'Feedback Details');
    }

    public function update(Request $request, $id)
    {
        $feedback = $this->feedback->findOrFail($id);

        $feedback->update([
            'status' => 'read'
        ]);

        return $this->sendResponse($feedback, 'Notification has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $this->authorize('isAdmin');

        $feedback = $this->feedback->findOrFail($id);

        $feedback->delete();

        return $this->sendResponse($feedback, 'Feedback has been Deleted');
    }
}
