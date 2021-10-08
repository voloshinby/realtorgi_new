<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuctionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('auctions')->insert(
            [
                [
                    'type' => 'econom',
                    'name' => 'Auction №1',
                    'auction_number' => rand(1, 999),
                    'organizer_requisites' => 'Реквизиты организатора',
                    'seller_id' => rand(1, 3),
                    'seller_phone' => '13123213',
                    'requisites' => 'Реквизиты',
                    'anticrisis_manager' => 'Антикризисный мэнэджер',
                    'anticrisis_manager_phone' => '31356',
                    'how_to_view_the_property' => 'Как посмотреть имущество',
                    'contact_person' => 'joe',
                    'compensation' => 'Возмещение затрат по аукциону',
                    'step' => 1,
                    'additional_info' => 'Доп информация',
                    'starts_at' => '1622127214',
                    'ends_at' => '1624805614',
                    'start_selling' => '1622127214',
                    'end_selling' => '1624805614'
                ],
                [
                    'type' => 'econom',
                    'name' => 'Auction №2',
                    'auction_number' => rand(1, 999),
                    'organizer_requisites' => 'Реквизиты организатора',
                    'seller_id' => rand(1, 3),
                    'seller_phone' => '13123213',
                    'requisites' => 'Реквизиты',
                    'anticrisis_manager' => 'Антикризисный мэнэджер',
                    'anticrisis_manager_phone' => '31356',
                    'how_to_view_the_property' => 'Как посмотреть имущество',
                    'contact_person' => 'joe',
                    'compensation' => 'Возмещение затрат по аукциону',
                    'step' => 1,
                    'additional_info' => 'Доп информация',
                    'starts_at' => '1622127214',
                    'ends_at' => '1624805614',
                    'start_selling' => '1622127214',
                    'end_selling' => '1624805614'
                ],
                [
                    'type' => 'econom',
                    'name' => 'Auction №3',
                    'auction_number' => rand(1, 999),
                    'organizer_requisites' => 'Реквизиты организатора',
                    'seller_id' => rand(1, 3),
                    'seller_phone' => '13123213',
                    'requisites' => 'Реквизиты',
                    'anticrisis_manager' => 'Антикризисный мэнэджер',
                    'anticrisis_manager_phone' => '31356',
                    'how_to_view_the_property' => 'Как посмотреть имущество',
                    'contact_person' => 'joe',
                    'compensation' => 'Возмещение затрат по аукциону',
                    'step' => 1,
                    'additional_info' => 'Доп информация',
                    'starts_at' => '1622127214',
                    'ends_at' => '1624805614',
                    'start_selling' => '1622127214',
                    'end_selling' => '1624805614'
                ],
                [
                    'type' => 'econom',
                    'name' => 'Auction №4',
                    'auction_number' => rand(1, 999),
                    'organizer_requisites' => 'Реквизиты организатора',
                    'seller_id' => rand(1, 3),
                    'seller_phone' => '13123213',
                    'requisites' => 'Реквизиты',
                    'anticrisis_manager' => 'Антикризисный мэнэджер',
                    'anticrisis_manager_phone' => '31356',
                    'how_to_view_the_property' => 'Как посмотреть имущество',
                    'contact_person' => 'joe',
                    'compensation' => 'Возмещение затрат по аукциону',
                    'step' => 1,
                    'additional_info' => 'Доп информация',
                    'starts_at' => '1622127214',
                    'ends_at' => '1624805614',
                    'start_selling' => '1622127214',
                    'end_selling' => '1624805614'
                ],
                [
                    'type' => 'classic',
                    'name' => 'Auction №5',
                    'auction_number' => rand(1, 999),
                    'organizer_requisites' => 'Реквизиты организатора',
                    'seller_id' => rand(1, 3),
                    'seller_phone' => '13123213',
                    'requisites' => 'Реквизиты',
                    'anticrisis_manager' => 'Антикризисный мэнэджер',
                    'anticrisis_manager_phone' => '31356',
                    'how_to_view_the_property' => 'Как посмотреть имущество',
                    'contact_person' => 'joe',
                    'compensation' => 'Возмещение затрат по аукциону',
                    'step' => 1,
                    'additional_info' => 'Доп информация',
                    'starts_at' => '1622127214',
                    'ends_at' => '1624805614',
                    'start_selling' => '1622127214',
                    'end_selling' => '1624805614'
                ],
                [
                    'type' => 'classic',
                    'name' => 'Auction №6',
                    'auction_number' => rand(1, 999),
                    'organizer_requisites' => 'Реквизиты организатора',
                    'seller_id' => rand(1, 3),
                    'seller_phone' => '13123213',
                    'requisites' => 'Реквизиты',
                    'anticrisis_manager' => 'Антикризисный мэнэджер',
                    'anticrisis_manager_phone' => '31356',
                    'how_to_view_the_property' => 'Как посмотреть имущество',
                    'contact_person' => 'joe',
                    'compensation' => 'Возмещение затрат по аукциону',
                    'step' => 1,
                    'additional_info' => 'Доп информация',
                    'starts_at' => '1622127214',
                    'ends_at' => '1624805614',
                    'start_selling' => '1622127214',
                    'end_selling' => '1624805614'
                ],
                [
                    'type' => 'classic',
                    'name' => 'Auction №7',
                    'auction_number' => rand(1, 999),
                    'organizer_requisites' => 'Реквизиты организатора',
                    'seller_id' => rand(1, 3),
                    'seller_phone' => '13123213',
                    'requisites' => 'Реквизиты',
                    'anticrisis_manager' => 'Антикризисный мэнэджер',
                    'anticrisis_manager_phone' => '31356',
                    'how_to_view_the_property' => 'Как посмотреть имущество',
                    'contact_person' => 'joe',
                    'compensation' => 'Возмещение затрат по аукциону',
                    'step' => 1,
                    'additional_info' => 'Доп информация',
                    'starts_at' => '1622127214',
                    'ends_at' => '1624805614',
                    'start_selling' => '1622127214',
                    'end_selling' => '1624805614'
                ],
            ]
        );
    }
}
