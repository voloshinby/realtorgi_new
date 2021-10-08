<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lots')->insert(
            [
                [
                    'lot_number' => rand(1, 999),
                    'auction_id' => rand(1, 7),
                    'name' => 'Lot 1',
                    'info' => 'Информация',
                    'price_start' => rand(5000, 9999),
                    'price_min' => rand(1, 4000),
                    'step' => 1,
                    'deposit' => rand(1, 1000),
                    'additional_info' => 'Доп информация',
                    'status' => 'Черновик',
                    'category_id' => rand(1, 7)
                ],
                [
                    'lot_number' => rand(1, 999),
                    'auction_id' => rand(1, 7),
                    'name' => 'Lot 2',
                    'info' => 'Информация',
                    'price_start' => rand(5000, 9999),
                    'price_min' => rand(1, 4000),
                    'step' => 1,
                    'deposit' => rand(1, 1000),
                    'additional_info' => 'Доп информация',
                    'status' => 'Черновик',
                    'category_id' => rand(1, 7)
                ],
                [
                    'lot_number' => rand(1, 999),
                    'auction_id' => rand(1, 7),
                    'name' => 'Lot 3',
                    'info' => 'Информация',
                    'price_start' => rand(5000, 9999),
                    'price_min' => rand(1, 4000),
                    'step' => 1,
                    'deposit' => rand(1, 1000),
                    'additional_info' => 'Доп информация',
                    'status' => 'Черновик',
                    'category_id' => rand(1, 7)
                ],
                [
                    'lot_number' => rand(1, 999),
                    'auction_id' => rand(1, 7),
                    'name' => 'Lot 4',
                    'info' => 'Информация',
                    'price_start' => rand(5000, 9999),
                    'price_min' => rand(1, 4000),
                    'step' => 1,
                    'deposit' => rand(1, 1000),
                    'additional_info' => 'Доп информация',
                    'status' => 'Черновик',
                    'category_id' => rand(1, 7)
                ],
                [
                    'lot_number' => rand(1, 999),
                    'auction_id' => rand(1, 7),
                    'name' => 'Lot 5',
                    'info' => 'Информация',
                    'price_start' => rand(5000, 9999),
                    'price_min' => rand(1, 4000),
                    'step' => 1,
                    'deposit' => rand(1, 1000),
                    'additional_info' => 'Доп информация',
                    'status' => 'Черновик',
                    'category_id' => rand(1, 7)
                ],
                [
                    'lot_number' => rand(1, 999),
                    'auction_id' => rand(1, 7),
                    'name' => 'Lot 6',
                    'info' => 'Информация',
                    'price_start' => rand(5000, 9999),
                    'price_min' => rand(1, 4000),
                    'step' => 1,
                    'deposit' => rand(1, 1000),
                    'additional_info' => 'Доп информация',
                    'status' => 'Черновик',
                    'category_id' => rand(1, 7)
                ],
                [
                    'lot_number' => rand(1, 999),
                    'auction_id' => rand(1, 7),
                    'name' => 'Lot 7',
                    'info' => 'Информация',
                    'price_start' => rand(5000, 9999),
                    'price_min' => rand(1, 4000),
                    'step' => 1,
                    'deposit' => rand(1, 1000),
                    'additional_info' => 'Доп информация',
                    'status' => 'Черновик',
                    'category_id' => rand(1, 7)
                ],
            ]
        );
    }
}
