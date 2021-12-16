<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Users\UserRequest;
use App\Models\AuctionBets;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\AuctionConfirm;

class UserController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!\Gate::allows('isAdmin')) {
        //     return $this->unauthorizedResponse();
        // }
        // $this->authorize('isAdmin');

        $users = User::latest()->with(['gallery', 'files'])->paginate(1000);

        return $this->sendResponse($users, 'Users list');
    }

    public function list()
    {
        $users = User::pluck('first_name', 'id');

        return $this->sendResponse($users, 'Users    list');
    }

    public function allList()
    {
        $users = User::select('first_name', 'last_name', 'id')->get();

        return $this->sendResponse($users, 'Users    list');
    }

    public function lotList(Request $request)
    {

        $bets = AuctionBets::select('lot_id')->groupBy('lot_id')->where('user_id', $request->id)->get();

        $betIds = [];

        foreach ($bets as $bet) {
            $betIds[] = $bet->lot_id;
        }

        $lots = Lot::whereIn('id', $betIds)->with(['auction', 'gallery', 'files', 'category'])->paginate(1000);

        return $this->sendResponse($lots, 'Lots list');
    }

    public function betList(Request $request)
    {
        $bets = AuctionBets::where('user_id', $request->id)->with('lot')->paginate(1000);

        return $this->sendResponse($bets, 'Bets list');
    }

    public function auctionConfirms($id)
    {
        $confirms = AuctionConfirm::latest()->where('user_id', $id)->get();

        return $this->sendResponse($confirms, 'Список заявок');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Users\UserRequest $request
     *
     * @param $id
     *
     * @throws \Illuminate\Validation\ValidationException
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'login' => uniqid(rand()),
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'registration_code' => rand(100000, 999999),
            'type' => 'user',
            'type_user' => $request->get('type_user'),
        ]);

        if (isset($user) && !is_null($user)) {

            Notification::create([
                'user_id' => 0,
                'text' => 'Был зарегистрирован новый пользователь с именем ' . $user->first_name . ' ' . $user->last_name,
                'status' => 'new',
            ]);

            Mail::send('emails.registrationCode', $user->toArray(),
                function ($message) use ($user) {
                    $message->to($user->email, 'Клиент')->subject('Подтверждение регистрации на realtorgi.by');
                }
            );
        }

        return $this->sendResponse($user, 'User Created Successfully');
    }

    public function confirm_user(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->get('registration_code') == $user->registration_code) {
            $user->update([
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);

            Mail::send('emails.registration', $user->toArray(),
                function ($message) use ($user) {
                    $message->to($user->email, 'Клиент')->subject('Вы успешно подтвердили регистрацию');
                }
            );
        } else {
            return $this->sendResponse($user, 'Ошибка, введите верный код');
        }

        return $this->sendResponse($user, 'Подтверждение прошло успешно');

    }

    public function login(Request $request)
    {

        $user = User::where('email', $request->get('email'))->with('gallery', 'files')->first();

        if(isset($user) && !is_null($user)){
            if(Hash::check($request->get('password'), $user->password)){
                Session::put('email', $user->email);
            } else {
                return $this->sendResponse('Error', 'Неправильный пароль');
            }
        } else {
            return $this->sendResponse('Error', 'Неправильная электронная почта');
        }

        return $this->sendResponse($user, 'User has been logined');
    }

    /**
     * Update the resource in storage
     *
     * @param  \App\Http\Requests\Users\UserRequest  $request
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());

        if (isset($user) && !is_null($user)) {

            Notification::create([
                'user_id' => $id,
                'title' => 'Изменения данных.',
                'text' => 'Вы успешно сменили данные своего аккаунта.',
                'status' => 'new',
            ]);

            Notification::create([
                'user_id' => 0,
                'text' => 'Был изменен профиль пользователя с именем ' . $user->first_name . ' ' . $user->last_name,
                'status' => 'new',
            ]);

            Mail::send('emails.userChange', $user->toArray(),
                function($message) use ($user){
                    $message->to($user->email, 'Клиент')->subject('Изменения данных пользователя realtorgi.by');
                }
            );
        }

        return $this->sendResponse($user, 'User Information has been updated');
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

        $user = User::findOrFail($id);
        // delete the user

        $user->delete();

        return $this->sendResponse([$user], 'User has been Deleted');
    }
}
