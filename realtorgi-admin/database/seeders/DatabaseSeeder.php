<?php

namespace Database\Seeders;

use App\Models\Type_users;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(AuctionsSeeder::class);
        $this->call(LotsSeeder::class);
        $this->call(AuctionConfirmSeeder::class);
        //duplicate product for data
    }
}
