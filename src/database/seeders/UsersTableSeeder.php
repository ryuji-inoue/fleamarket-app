<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
                'name' => 'テストユーザー',
                'email' => 'test@test.com',
                'postal_code' => '1000001',
                'address' => '東京都千代田区千代田1-1',
                'building' => 'テストビル101',
                'profile_image' => null,
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => '出品者',
                'email' => 'seller@test.com',
                'postal_code' => '1500001',
                'address' => '東京都渋谷区神宮前1-1',
                'building' => 'サンプルマンション202',
                'profile_image' => null,
                'password' => Hash::make('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
