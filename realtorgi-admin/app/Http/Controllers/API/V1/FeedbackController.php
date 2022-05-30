<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Mail;


class FeedbackController extends BaseController
{

    protected $feedback = '';
    protected $user = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Feedback $feedback, User $user)
    {
        // $this->middleware('auth:api');
        $this->feedback = $feedback;
        $this->user = $user;
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
            'user_id' => $request->get('user'),
            'status' => 'new'
        ]);

        $user = $this->user->where('id', 160)->first(); //todo: change to admin ID
        Mail::send('emails.feedbackConfirm', $feedback->toArray(),
            function ($message) use ($user) {
                $message->to($user->email, 'Администратор')->subject('Новая заявка на организацию торгов');
            }
        );

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

    public function allList()
    {
        $users = Feedback::select('name', 'id', 'phone')->get();

        return $this->sendResponse($users, 'Feedbacks list');
    }
}
