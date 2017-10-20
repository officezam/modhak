<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
            'name' => 'Amir Shahzad',
            'type' => 'admin',
            'email' => 'officezam@gmail.com',
            'address' => 'Al-madin Electric Store Bata Choke Gulgasht colony Multan',
            'phone' => '03007272332',
            'password' => bcrypt('123456'),
            ],
            [
                'name' => 'Ghazanfar Rehman',
                'type' => 'admin',
                'email' => 'grehman@gmail.com',
                'address' => 'Canada',
                'phone' => '+17802456176',
                'password' => bcrypt('pass2word'),
            ],
            [
                'name' => 'Ghazanfar Rehman',
                'type' => 'admin',
                'email' => 'moon3204@gmail.com',
                'address' => 'Canada',
                'phone' => '17802456176',
                'password' => bcrypt('pass2word'),
            ]
        ]);
    }
}
