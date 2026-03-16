<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Item;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::pluck('id')->toArray();
        $items = Item::pluck('id')->toArray();

        $comments = [
            'とても良い商品ですね！',
            'まだ購入可能ですか？',
            '動作は問題ないでしょうか？',
            '購入を検討しています。',
        ];

        foreach ($comments as $comment) {

            DB::table('comments')->insert([
                'user_id' => $users[array_rand($users)],
                'item_id' => $items[array_rand($items)],
                'content' => $comment,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }
    }
}
