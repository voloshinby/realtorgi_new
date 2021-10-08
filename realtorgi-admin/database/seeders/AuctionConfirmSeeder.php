<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuctionConfirmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('auction_confirms')->insert([[
            'user_id' => 1,
            'lot_id' => 1,
            'confirmed_admin' => false,
            'confirmed_user' => false,
        ],
        [
            'user_id' => 2,
            'lot_id' => 2,
            'confirmed_admin' => false,
            'confirmed_user' => false,
        ],
        [
            'user_id' => 3,
            'lot_id' => 3,
            'confirmed_admin' => false,
            'confirmed_user' => false,
        ],
        ]
    );
    }
}
