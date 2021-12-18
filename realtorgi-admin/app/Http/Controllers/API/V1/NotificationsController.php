<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends BaseController
{
    protected $notification = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        // $this->middleware('auth:api');
        $this->notification = $notification;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = $this->notification->latest()->with('user')->where('user_id', 0)->paginate(200);

        return $this->sendResponse($notifications, 'Notifications list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->get('user_id') != 0) {
            $notification = $this->notification->create([
                'user_id' => $request->get('user_id'),
                'title' => $request->get('title'),
                'text' => $request->get('text'),
                'status' => 'new',
                'is_shown' => $request->get('is_shown'),
            ]);
        } else {
            $notification = $this->notification->create([
                'user_id' => '0',
                'text' => $request->get('text'),
                'status' => 'new',
            ]);
        }

        return $this->sendResponse($notification, 'Notification Created Successfully');
    }

    public function userList($id)
    {

        $notifications = $this->notification->where('user_id', $id)->paginate(1000);

        return $this->sendResponse($notifications, 'Notifications list');
    }

    public function updateAll(Request $request)
    {

        $notification = $this->notification->where('user_id', $request->get('user_id'))->update([
            'status' => $request->get('status'),
        ]);

        return $this->sendResponse($notification, 'Notifications has been updated');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = $this->notification->with('user')->findOrFail($id);

        return $this->sendResponse($notification, 'Notification Details');
    }

    public function update(Request $request, $id)
    {
        $notification = $this->notification->findOrFail($id);

        $notification->update([
            'is_view_by_admin' => true,
        ]);

        return $this->sendResponse($notification, 'Notification has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $this->authorize('isAdmin');

        $notification = $this->notification->findOrFail($id);

        $notification->delete();

        return $this->sendResponse($notification, 'Notification has been Deleted');
    }

    public function readAllNotifications()
    {
        $this->notification->where('is_view_by_admin', false)->update(['is_view_by_admin' => true]);

        return $this->sendResponse([], 'Notifications have been Read');
    }
}
