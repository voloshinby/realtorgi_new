<?php

namespace App\Http\Controllers\API\V1;

// use App\Http\Requests\Auctions\AuctionRequest;
use App\Models\User;
use App\Models\Auction;
use App\Models\AuctionConfirm;
use App\Models\Notification;
use App\Models\Lot;
use Illuminate\Http\Request;
use App\Models\ExportData;
use Illuminate\Support\Facades\Mail;

class AuctionConfirmController extends BaseController
{
    protected $auctionConfirm = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuctionConfirm $auctionConfirm)
    {
        // $this->middleware('auth:api');
        $this->auctionConfirm = $auctionConfirm;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auctionConfirms = $this->auctionConfirm->latest()->with(['user' , 'lot', 'auction'])->where('confirmed_admin' , false)->paginate(1000);

        return $this->sendResponse($auctionConfirms, 'AuctionConfirms list');
    }

    public function allApplications($id){
        $auctionConfirms = $this->auctionConfirm->latest()->with(['user' , 'lot', 'auction'])->where('user_id' , $id)->paginate(1000);

        return $this->sendResponse($auctionConfirms, 'AuctionConfirms list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auctionConfirm = $this->auctionConfirm->create([
            'user_id' => $request->get('user_id'),
            'lot_id' => $request->get('lot_id'),
            'confirmed_admin' => false,
            'confirmed_user' => false,
        ]);

        $lot = Lot::where('id', $request->get('lot_id'))->first();
        $user = User::where('id', $request->get('user_id'))->first();

        if($request->get('user_id') != 0){
            Notification::create([
                'user_id' => $request->get('user_id'),
                'title' => 'Отправлена заявка на участие',
                'text' => 'Вы успешно отправили заявку на участие в аукционе под номером '.$lot['lot_number'],
                'status' => 'new',
            ]);

            Notification::create([
                'user_id' => '0',
                'text' => 'Пользователь с именем '.$user['first_name'].' '.$user['last_name'].' подал заявку на участие в лоте под номером '.$lot['lot_number'],
                'status' => 'new',
            ]);
        } else {

            Notification::create([
                'user_id' => '0',
                'text' => 'Пользователь с именем '.$user['first_name'].' '.$user['last_name'].' подал заявку на участие в лоте под номером '.$lot['lot_number'],
                'status' => 'new',
            ]);

        }

        return $this->sendResponse($auctionConfirm , 'AuctionConfirm Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $auctionConfirm = $this->auctionConfirm->with(['user' , 'lot', 'auction'])->findOrFail($id);

        return $this->sendResponse($auctionConfirm, 'AuctionConfirm Details');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AuctionConfirm  $auctionConfirm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $auctionConfirm = $this->auctionConfirm->findOrFail($id);
        $user = User::where('id', $auctionConfirm->user_id)->first();
        $lot = Lot::where('id', $auctionConfirm->lot_id)->first();

        if($auctionConfirm->confirmed_admin == 0){
            $auctionConfirm->update([
                'confirmed_admin' => true,
            ]);

            Notification::create([
                'user_id' => $user['id'],
                'title' => 'Ваша заявка была одобрена',
                'text' => 'Заявка на участие в аукционе под номером '.$lot->lot_number.' была одобрена.',
                'status' => 'new',
            ]);

            Mail::send('emails.auctionConfirm', $lot->toArray(),
                function($message) use ($user){
                    $message->to($user['email'])->subject('Заявка на участие в торгах одобрена');
                }
            );

        } else {
            $auctionConfirm->update([
                'confirmed_user' => true,
            ]);

            ExportData::create([
                'user_id' => $auctionConfirm->user_id,
                'lot_id' => $auctionConfirm->lot_id,
                'user_ip' => $_SERVER['REMOTE_ADDR'],
            ]);

            Notification::create([
                'user_id' => '0',
                'text' => 'Пользователь с именем '.$user['first_name'].' '.$user['last_name'].' подтвердил участие в лоте под номером '.$lot['lot_number'],
                'status' => 'new',
            ]);
        }

        return $this->sendResponse($auctionConfirm, 'AuctionConfirm Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // $this->authorize('isAdmin');

        $auctionConfirm = $this->auctionConfirm->findOrFail($id);

        $user = User::where('id', $auctionConfirm->user_id)->first();
        $lot = Lot::where('id', $auctionConfirm->lot_id)->first();

        $auctionConfirm->delete();

        Notification::create([
            'user_id' => $user['id'],
            'text' => 'Администратор отклонил вашу заявку на участие в торгах №'.$lot['lot_number'],
            'status' => 'new',
        ]);

        return $this->sendResponse($auctionConfirm, 'Auction has been Deleted');
    }

    public function upload(Request $request)
    {
        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('upload'), $fileName);

        return response()->json(['success' => true]);
    }
}
