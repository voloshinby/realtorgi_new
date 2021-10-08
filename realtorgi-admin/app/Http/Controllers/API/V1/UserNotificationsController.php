<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\UserNotifications;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification;

class UserNotificationsController extends BaseController
{
    protected $notification = '';
    protected $user = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserNotifications $notification, User $user)
    {
        // $this->middleware('auth:api');
        $this->notification = $notification;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = $this->notification->latest()->with('user', 'gallery', 'files')->where('confirmed', 0)->paginate(1000);

        return $this->sendResponse($notifications, 'Notifications list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $notification = $this->notification->create([
            'user_id' => $request->get('user_id'),
            'type_user' => $request->get('type_user'),
            'full_name' => $request->get('full_name'),
            'passport_number' => $request->get('passport_number'),
            'passport_issuer' => $request->get('passport_issuer'),
            'passportDate' => $request->get('passportDate'),
            'passport_personal' => $request->get('passport_personal'),
            'registration_address' => $request->get('registration_address'),
            'residence_address' => $request->get('residence_address'),
            'phone' => $request->get('phone'),
            'inn' => $request->get('inn'),
            'short_name' => $request->get('short_name'),
            'egr_date' => $request->get('egr_date'),
            'manager_data' => $request->get('manager_data'),
            'place_of_residence' => $request->get('place_of_residence'),
            'bank_details' => $request->get('bank_details'),
            'legal_entity' => $request->get('legal_entity'),
            'active' => 0,
            'confirmed' => 0,
        ]);

        $user = $this->user->where('id', $notification->user_id)->first();

        if(isset($user) && !is_null($user)){
            // Mail::send('emails.userProfileCreated', $user->toArray(),
            //     function($message) use ($user){
            //         $message->to($user->email, 'Клиент')->subject('Создание профиля realtorgi.by');
            //     }
            // );
        }

        return $this->sendResponse($notification, 'Auction Created Successfully');
    }

    public function update(Request $request, $id)
    {
        $notification = $this->notification->findOrFail($id);

        if(!empty($request->get('comment')) && !is_null($request->get('comment'))){
            Notification::create([
                'user_id' => $notification->id,
                'title' => 'Запрос документов для профиля.',
                'text' => $request->get('comment'),
                'status' => 'new'
            ]);
            $notification->delete();
        } else {

            $notificationUpdate = $notification->update($request->all());

            if($notificationUpdate){

                $userConfirm = $this->user->where('id', $notification->user_id)->first();

                $userConfirm->update([
                    'type_user' => $request->get('type_user'),
                    'full_name' => $request->get('full_name'),
                    'passport_number' => $request->get('passport_number'),
                    'passport_issuer' => $request->get('passport_issuer'),
                    'passportDate' => $request->get('passportDate'),
                    'passport_personal' => $request->get('passport_personal'),
                    'registration_address' => $request->get('registration_address'),
                    'residence_address' => $request->get('residence_address'),
                    'phone' => $request->get('phone'),
                    'inn' => $request->get('inn'),
                    'short_name' => $request->get('short_name'),
                    'egr_date' => $request->get('egr_date'),
                    'manager_data' => $request->get('manager_data'),
                    'place_of_residence' => $request->get('place_of_residence'),
                    'bank_details' => $request->get('bank_details'),
                    'legal_entity' => $request->get('legal_entity'),
                ]);

                if($userConfirm){

                    $confirm = $this->user->where('id', $notification->user_id)->first();

                    Notification::create([
                        'user_id' => $confirm->id,
                        'title' => 'Изменения данных.',
                        'text' => 'Вы успешно сменили данные своего аккаунта.',
                        'status' => 'new'
                    ]);

                    Mail::send('emails.userInfoConfirm', $confirm->toArray(),
                        function($message) use ($confirm){
                            $message->to($confirm->email, 'Клиент')->subject('Успешная смена профиля realtorgi.by');
                        }
                    );

                    $notification->delete();
                }
            }
        }

        return $this->sendResponse($notification, 'Category Information has been updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = $this->notification->with('user')->findOrFail($id);

        return $this->sendResponse($notification, 'Notification Details');
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

        $notification = $this->notification->findOrFail($id);

        $user = $this->user->where('id', $notification->user_id)->first();

        $notification->delete();

        if($user){

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Изменения данных профиля.',
                'text' => 'Администратор не одобрил изменения данных профиля.',
                'status' => 'new'
            ]);

            // Mail::send('emails.userProfileDelete', $user->toArray(),
            //     function($message) use ($user){
            //         $message->to($user->email, 'Клиент')->subject('Профиль не был одобрен администратором realtorgi.by');
            //     }
            // );

            $notification->delete();
        }

        return $this->sendResponse($notification, 'Notification has been Deleted');
    }
}
