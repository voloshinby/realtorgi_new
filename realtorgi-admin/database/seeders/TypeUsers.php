<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_users')->insert([
            'type' => 'Физическое лицо',
            'type' => 'Индивидуальный предприниматель',
            'type' => 'Юридическое лицо'
        ]);
    }
}
