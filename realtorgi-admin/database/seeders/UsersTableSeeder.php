<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('users')->where('email', 'admin@gmail.com')->delete();

        DB::table('users')->insert(
            [
                [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'admin@gmail.com',
                    'password' => bcrypt('123456'),
                    'type' => 'admin',
                    'login' => uniqid(rand()),
                    'type_user' => 'jur',
                    'full_name' => 'John Doe',
                    'passport_number' => 'B123123123',
                    'passport_issuer' => 'incraasd',
                    'passport_personal' => '1231231',
                    'residence_address' => 'Random Address',
                    'phone' => '123123123',
                    'inn' => '21312312',
                    'short_name' => 'Короткое название фирмы',
                    'manager_data' => 'Manager data',
                    'place_of_residence' => 'Some place',
                    'registration_address' => 'Some address',
                    'bank_details' => 'Bank details',
                    'legal_entity' => 'Юр адрес',
                    'active' => 1
                ],
                [
                    'first_name' => 'Brandon',
                    'last_name' => 'Uri',
                    'email' => 'brandon@gmail.com',
                    'password' => bcrypt('123456'),
                    'type' => 'user',
                    'login' => uniqid(rand()),
                    'type_user' => 'ip',
                    'full_name' => 'John Doe',
                    'passport_number' => 'B123123123',
                    'passport_issuer' => 'incraasd',
                    'passport_personal' => '1231231',
                    'residence_address' => 'Random Address',
                    'phone' => '123123123',
                    'inn' => '21312312',
                    'short_name' => 'Короткое название фирмы',
                    'manager_data' => 'Manager data',
                    'place_of_residence' => 'Some place',
                    'registration_address' => 'Some address',
                    'bank_details' => 'Bank details',
                    'legal_entity' => 'Юр адрес',
                    'active' => 1
                ],
                [
                    'first_name' => 'Mike',
                    'last_name' => 'Wasowski',
                    'email' => 'mike@gmail.com',
                    'password' => bcrypt('123456'),
                    'type' => 'user',
                    'login' => uniqid(rand()),
                    'type_user' => 'phys',
                    'full_name' => 'John Doe',
                    'passport_number' => 'B123123123',
                    'passport_issuer' => 'incraasd',
                    'passport_personal' => '1231231',
                    'residence_address' => 'Random Address',
                    'phone' => '123123123',
                    'inn' => '21312312',
                    'short_name' => 'Короткое название фирмы',
                    'manager_data' => 'Manager data',
                    'place_of_residence' => 'Some place',
                    'registration_address' => 'Some address',
                    'bank_details' => 'Bank details',
                    'legal_entity' => 'Юр адрес',
                    'active' => 1
                ]
            ]
        );
    }
}
