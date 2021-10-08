<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Auctions\AuctionBetsRequest;
use App\Models\AuctionBets;
use App\Models\Auction;
use App\Models\Lot;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class AuctionBetsController extends BaseController
{
    protected $bet = '';
    protected $lot = '';
    protected $auction = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuctionBets $bet, Auction $auction, Lot $lot)
    {
        // $this->middleware('auth:api');
        $this->bet = $bet;
        $this->auction = $auction;
        $this->lot = $lot;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bets = $this->bet->latest()->with(['user', 'lot'])->paginate(1000);

        return $this->sendResponse($bets, 'Bets list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Auctions\AuctionBetsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuctionBetsRequest $request)
    {

        $lot = $this->lot->findOrFail($request->get('lot_id')); //Получаем лот на который была сделана ставка
        $auction = $this->auction->findOrFail($lot->auction_id); //Получаем аукцион к которому привязан лот
        $user = User::where('id', $request->get('user_id'))->first(); // Получаем юзера который сделал ставку
        $curr_time = date('U');

        $all_bets = $this->bet->where('lot_id', $request->get('lot_id'))->get();

        $data = [
            'lot_number' => $lot->lot_number,
            'login' => $lot->login,
            'time' => date('d-m-Y H:i:s'),
            'bet' => $request->get('bet')
        ];

        $user_bet = $request->get('bet'); // ставка которую ввел юзер

        $unix_minutes = date('U', strtotime("3 minutes")); // текущее время по юниксу + 3 минуты

        $bet = $this->bet->create([
            'user_id' => $request->get('user_id'),
            'lot_id' => $request->get('lot_id'),
            // 'bet_amount' => $bet,
            'bet_amount' => $user_bet
        ]);

        $seconds = abs((int)$auction->end_selling - (int)$curr_time);
        $minutes = floor($seconds / 60);

        if($minutes < 3){
            $auction->update([
                'ends_at' => (int)$auction->ends_at + 180,
            ]);
        }

        // if($auction->step == 1 && $lot->step == 2){ // если шаг аукциона по начальной цене а шаг лота по проценту

        //     if(!empty($lot->price_step) && !is_null($lot->price_step)){ // если не пустой шаг процента и не равняется null, иначе по умолчанию будет 5%
        //         $price_step_percent = str_replace('%', '', $lot->price_step); // удаляем % из шага ставки (если есть) и переводим в инт
        //         $price_step_percent = (int)$price_step_percent;

        //         if($price_step_percent < 10){
        //             $price_step_percent = '0.0'.$price_step_percent; // если шаг ставки меньше 10%, то ставим перед значением 0.0
        //             $price_step_percent = (double)$price_step_percent;
        //         } else {
        //             $price_step_percent = '0.'.$price_step_percent; // иначе ставим 0.шаг ставки
        //             $price_step_percent = (double)$price_step_percent;
        //         }
        //     } else {
        //         $price_step_percent = 0.05;
        //     }

        //     $bet_step_one = ($lot->price_start * $price_step_percent) + $lot->price_start; // переводим шаг ставки в сумму ставки

        //     $one_bet = $this->bet->where('lot_id', $lot->id)->orderBy('id', 'desc')->first();

        //     if($bet_step_one < $user_bet){
        //         if(isset($one_bet) && !is_null($one_bet)){

        //             // $bet = ($lot->price_start * $user_bet) + $one_bet->bet_amount;

        //             $bet = $this->bet->create([
        //                 'user_id' => $request->get('user_id'),
        //                 'lot_id' => $request->get('lot_id'),
        //                 // 'bet_amount' => $bet,
        //                 'bet_amount' => $user_bet
        //             ]);

        //             if($auction->ends_at <= $unix_minutes){
        //                 $auction->update([
        //                     'ends_at' => (int)$auction->ends_at + 180,
        //                 ]);
        //             }

        //         } else {

        //             $bet = $this->bet->create([
        //                 'user_id' => $request->get('user_id'),
        //                 'lot_id' => $request->get('lot_id'),
        //                 // 'bet_amount' => $bet_step_one,
        //                 'bet_amount' => $user_bet
        //             ]);

        //             if($auction->ends_at <= $unix_minutes){
        //                 $auction->update([
        //                     'ends_at' => (int)$auction->ends_at + 180,
        //                 ]);
        //             }

        //         }
        //     } else {
        //         return $this->sendResponse('Error', 'Пожалуйста, введите ставку больше шага торгов');
        //     }


        // } else if($auction->step == 2 && $lot->step == 2) {

        //     $one_bet = $this->bet->where('lot_id', $lot->id)->orderBy('id', 'desc')->first();

        //     if(!empty($lot->price_step) && !is_null($lot->price_step)){
        //         $price_step_percent = str_replace('%', '', $lot->price_step);
        //         $price_step_percent = (int)$price_step_percent;

        //         if($price_step_percent < 10){
        //             $price_step_percent = '0.0'.$price_step_percent;
        //             $price_step_percent = (double)$price_step_percent;
        //         } else {
        //             $price_step_percent = '0.'.$price_step_percent;
        //             $price_step_percent = (double)$price_step_percent;
        //         }
        //     } else {
        //         $price_step_percent = 0.05;
        //     }

        //     if(isset($one_bet) && !is_null($one_bet)){

        //         $bet_step_two = $one_bet->bet_amount;
        //         $bet_step_two = ($bet_step_two * $price_step_percent) + $bet_step_two;

        //         $bet = $this->bet->create([
        //             'user_id' => $request->get('user_id'),
        //             'lot_id' => $request->get('lot_id'),
        //             'bet_amount' => $bet_step_two,
        //         ]);

        //         if($auction->ends_at <= $unix_minutes){
        //             $auction->update([
        //                 'ends_at' => (int)$auction->ends_at + 180,
        //             ]);
        //         }


        //     } else {
        //         $bet_step_two = $lot->price_start;
        //         $bet_step_two = ($lot->price_start * $price_step_percent) + $bet_step_two;

        //         $bet = $this->bet->create([
        //             'user_id' => $request->get('user_id'),
        //             'lot_id' => $request->get('lot_id'),
        //             'bet_amount' => $bet_step_two,
        //         ]);

        //         if($auction->ends_at <= $unix_minutes){
        //             $auction->update([
        //                 'ends_at' => (int)$auction->ends_at + 180,
        //             ]);
        //         }

        //     }

        // } else if ($lot->step == 1) {
        //     $one_bet = $this->bet->where('lot_id', $lot->id)->orderBy('id', 'desc')->first();

        //     if(isset($one_bet) && !is_null($one_bet)){

        //         $bet_step_three = $one_bet->bet_amount + (double)$lot->price_step;

        //         $bet = $this->bet->create([
        //             'user_id' => $request->get('user_id'),
        //             'lot_id' => $request->get('lot_id'),
        //             'bet_amount' => $bet_step_three,
        //         ]);

        //         if($auction->ends_at <= $unix_minutes){
        //             $auction->update([
        //                 'ends_at' => (int)$auction->ends_at + 180,
        //             ]);
        //         }

        //     } else {

        //         $bet_step_three = (double)$lot->price_step + $lot->price_start;

        //         $bet = $this->bet->create([
        //             'user_id' => $request->get('user_id'),
        //             'lot_id' => $request->get('lot_id'),
        //             'bet_amount' => $bet_step_three,
        //         ]);

        //         if($auction->ends_at <= $unix_minutes){
        //             $auction->update([
        //                 'ends_at' => (int)$auction->ends_at + 180,
        //             ]);
        //         }

        //     }
        // }

        if($request->get('user_id') != 0){
            Notification::create([
                'user_id' => $request->get('user_id'),
                'title' => 'Вы сделали ставку на лот под номером '.$lot->lot_number,
                'text' => 'Вы сделали ставку на лот под номером '.$lot->lot_number,
                'status' => 'new',
            ]);

            Notification::create([
                'user_id' => 0,
                'text' => 'Пользователь с именем '.$user['first_name'].' '.$user['last_name'].' сделал ставку на лот под номером '.$lot->lot_number,
                'status' => 'new',
            ]);

            foreach($all_bets as $all_bet){
                $user_query = User::where('id', $all_bet['user_id'])->first();

                if($all_bet['user_id'] != $request->get('user_id')){
                    Mail::send('emails.newBet', $data,
                        function($message) use ($user_query){
                            $message->to($user_query['email'])->subject('Пользователь сделал ставку');
                        }
                    );
                }
            }

        } else {
            Notification::create([
                'user_id' => 0,
                'text' => 'Пользователь с именем '.$user['first_name'].' '.$user['last_name'].' сделал ставку на лот под номером '.$lot->lot_id,
                'status' => 'new',
            ]);
        }

        return $this->sendResponse($bet, 'Bet Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bet = $this->bet->with(['user', 'lot'])->findOrFail($id);

        return $this->sendResponse($bet, 'Bet Details');
    }

    public function list($id){

        $bets = $this->bet->with(['user'])->where('lot_id', $id)->get();

        return $this->sendResponse($bets, 'Bets');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AuctionBets  $bet
     * @return \Illuminate\Http\Response
     */
    public function update(AuctionBetsRequest $request, $id)
    {
        $bet = $this->bet->where('lot_id', $request->lot_id)->orderBy('bet_amount', 'desc')->first();
        $user = User::where('id', $request->get('user_id'))->first();
        $lot = $this->lot->where('id', $request->get('lot_id'))->first();

        $bet->update([
            'winner' => 1
        ]);

        Notification::create([
            'user_id' => 0,
            'text' => 'Пользователь с именем '.$user['first_name'].' '.$user['last_name'].' выиграл лот под номером '.$lot['lot_number'],
            'status' => 'new'
        ]);

        return $this->sendResponse($bet, 'Bet Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // $this->authorize('isAdmin');

        $bet = $this->bet->findOrFail($id);

        $bet->delete();

        return $this->sendResponse($bet, 'Bet has been Deleted');
    }

    public function upload(Request $request)
    {
        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('upload'), $fileName);

        return response()->json(['success' => true]);
    }
}
