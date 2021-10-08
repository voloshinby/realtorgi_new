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

class NotificationEveryTenMinutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auction:notification_ten';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron every ten minutes for nofifications';

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

                $seconds = abs($start_selling - $curr);
                $ten_minutes_time = floor($seconds / 60);

                if($ten_minutes_time == 10){
                    if($starts_at <= (int)$curr && $start_selling >= (int)$curr){

                        $lots = Lot::where('auction_id', $auction['id'])->get();

                        foreach($lots as $lot){
                            if($lot['status'] == 'Предстоящие'){
                                $user_ids = AuctionConfirm::where('lot_id', $lot['id'])->get();
                                foreach($user_ids as $user_id){
                                    $user = User::where('id', $user_id['user_id'])->first();

                                    $data = [
                                        'start_selling' => date('H:i', strtotime($start_selling)),
                                        'auction_number' => $auction['auction_number'],
                                        'lot_number' => $lot['lot_number'],
                                    ];

                                    Mail::send('emails.ten_minutes_auction', $data,
                                        function($message) use ($user){
                                            $message->to($user['email'])->subject('10 минут до начала торгов');
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
