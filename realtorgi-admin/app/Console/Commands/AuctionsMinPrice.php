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

class AuctionsMinPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auction:pricemin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'price min';

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
        $auctions = Auction::where('step', 2)->get();

        if(isset($auctions) || !is_null($auctions)){
            foreach($auctions as $auction){
                $starts_at = (int)$auction['starts_at'];
                $ends_at = (int)$auction['ends_at'];
                $start_selling = (int)$auction['start_selling'];
                $end_selling = (int)$auction['end_selling'];

                if($start_selling <= (int)$curr){

                    $diff = abs($start_selling - $curr);

                    $hours = floor($diff / 3600);

                    if($hours >= 2){

                        $hours_between_start_end_diff = abs($start_selling - $end_selling);

                        $hours_between_start_end = floor($hours_between_start_end_diff / 3600);

                        $lots = Lot::where('auction_id', $auction['id'])->where('status', 'Текущие')->get();

                        foreach($lots as $lot){
                            $countBets = AuctionBets::where('lot_id', $lot['id'])->count();
                            if($countBets > 0){
                                $diff_prices = $lot['price_start'] - $lot['price_min'];
                                $price_result = $diff_prices / $hours_between_start_end;

                                Lot::where('id', $lot['id'])->update([
                                    'price_start' => $lot['price_start'] - $price_result
                                ]);
                            }
                        }

                    }

                }
            }
        }
    }
}
