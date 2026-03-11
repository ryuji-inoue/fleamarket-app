<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('comments')->insert([
            [
                'user_id' => 1,
                'item_id' => 1,
                'content' => 'とても良い商品ですね！',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'item_id' => 1,
                'content' => 'まだ購入可能ですか？',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'item_id' => 2,
                'content' => '動作は問題ないでしょうか？',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'item_id' => 3,
                'content' => '購入を検討しています。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
