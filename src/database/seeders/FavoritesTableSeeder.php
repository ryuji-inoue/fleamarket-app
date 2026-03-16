<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Item;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $items = Item::pluck('id')->take(3);

        foreach ($items as $itemId) {
            DB::table('favorites')->insert([
                'user_id' => $user->id,
                'item_id' => $itemId,
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
