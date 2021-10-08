<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\AuctionBets;
use App\Models\AuctionConfirm;
use App\Models\Lot;
use App\Models\User;
use App\Models\ExportData;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Auctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auction:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update auctions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $curr = Carbon::now()->timestamp;
        $auctions = Auction::get();

        if(isset($auctions) || !is_null($auctions)){
            foreach($auctions as $auction){

                $starts_at = (int)$auction['starts_at'];
                $ends_at = (int)$auction['ends_at'];
                $start_selling = (int)$auction['start_selling'];
                $end_selling = (int)$auction['end_selling'];

                $diff = strtotime($start_selling) - strtotime($curr);

                $one_day_time = $diff / 3600;

                // if($ends_at <= (int)$curr){
                //     $lots = Lot::where('auction_id', $auction['id'])->get();

                //     foreach($lots as $lot){
                //         $countUsers = AuctionConfirm::where('confirmed_user', 1)->where('confirmed_admin', 1)->where('lot_id', $lot['id'])->count();

                //         if($countUsers < 2 && $lot['status'] != 'Несостоявшиеся'){
                //             Lot::where('id', $lot['id'])->update([
                //                 'status' => 'Несостоявшиеся'
                //             ]);
                //         }
                //     }

                // }
                if ($start_selling <= (int)$curr && $end_selling >= (int)$curr){
                    $lots = Lot::where('auction_id', $auction['id'])->get();

                    foreach($lots as $lot){
                        $countUsers = AuctionConfirm::where('confirmed_user', 1)->where('confirmed_admin', 1)->where('lot_id', $lot['id'])->count();

                        if($countUsers > 1){
                            Lot::where('id', $lot['id'])->update([
                                'status' => 'Текущие'
                            ]);
                        } else {
                            Lot::where('id', $lot['id'])->update([
                                'status' => 'Несостоявшиеся'
                            ]);
                        }
                    }
                }
                else if($end_selling <= (int)$curr){
                    $lots = Lot::where('auction_id', $auction['id'])->get();

                    foreach($lots as $lot){
                        $countUsers = AuctionConfirm::where('confirmed_user', 1)->where('confirmed_admin', 1)->where('lot_id', $lot['id'])->count();
                        $countBets = AuctionBets::where('lot_id', $lot['id'])->count();

                        if($countUsers > 1 && $countBets > 0){
                            Lot::where('id', $lot['id'])->update([
                                'status' => 'Состоявшиеся'
                            ]);

                            // ExportData::where('lot_id', $lot['id'])->update(['done', 1]);

                            $maxBet = AuctionBets::where('lot_id', $lot['id'])->max('bet_amount');

                            $bet = AuctionBets::where('lot_id', $lot['id'])->where('bet_amount', $maxBet)->first();

                            $user = User::where('id', $bet['user_id'])->first();

                            $lot = Lot::where('id', $bet['lot_id'])->first();

                            if($bet['winner'] == 0){
                                $bet->update([
                                    'winner' => 1
                                ]);

                                Notification::create([
                                    'title' => 'Победа в аукционе',
                                    'text' => 'Пользователь '.$user['first_name'].' '.$user['last_name'].' победил в лоте под номером '.$lot['lot_number'],
                                    'user_id' => 0,
                                    'status' => 'new',
                                ]);

                                Mail::send('emails.auctionWinner', $lot->toArray(),
                                    function($message) use ($user){
                                        $message->to($user['email'])->subject('Победа в аукционе');
                                    }
                                );
                            }

                        } else {
                            Lot::where('id', $lot['id'])->update([
                                'status' => 'Несостоявшиеся'
                            ]);
                        }

                    }
                }
                else if($starts_at <= (int)$curr && $start_selling >= (int)$curr){
                    $lots = Lot::where('auction_id', $auction['id'])->get();

                    foreach($lots as $lot){

                        if($lot['status'] != 'Предстоящие'){
                            Lot::where('id', $lot['id'])->update([
                                'status' => 'Предстоящие'
                            ]);
                        }
                    }
                }
                else {
                    continue;
                }

            }

        }

        $this->info('Success');
    }
}
