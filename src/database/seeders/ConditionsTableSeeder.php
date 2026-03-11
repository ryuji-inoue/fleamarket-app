<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Condition; 

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Condition::insert([
            ['name' => '新品・未使用', 'sort' => 1],
            ['name' => '未使用に近い', 'sort' => 2],
            ['name' => '目立った傷や汚れなし', 'sort' => 3],
            ['name' => 'やや傷や汚れあり', 'sort' => 4],
            ['name' => '傷や汚れあり', 'sort' => 5],
            ['name' => '全体的に状態が悪い', 'sort' => 6],
        ]);
    }
}
