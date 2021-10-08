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

class NotificationEveryDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auction:notification_day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron every day for nofifications';

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

                $diff = abs($start_selling - $curr);

                $one_day_time = floor($diff / 3600);

                if($one_day_time == 24){
                    if($starts_at <= (int)$curr && $start_selling >= (int)$curr){

                        $lots = Lot::where('auction_id', $auction['id'])->get();

                        foreach($lots as $lot){
                            if($lot['status'] == 'Предстоящие'){
                                $user_ids = AuctionConfirm::where('lot_id', $lot['id'])->get();

                                $data = [
                                    'start_selling' => date('H:i', strtotime($start_selling)),
                                    'auction_number' => $auction['auction_number'],
                                    'lot_number' => $lot['lot_number'],
                                ];

                                foreach($user_ids as $user_id){
                                    $user = User::where('id', $user_id['user_id'])->first();

                                    Mail::send('emails.one_day_auction', $data,
                                        function($message) use ($user){
                                            $message->to($user['email'])->subject('1 день до начала торгов');
                                        }
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
