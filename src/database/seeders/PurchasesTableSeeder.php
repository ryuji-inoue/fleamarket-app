<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;

class PurchasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $item = Item::where('status', 1)->first() ?? Item::first();
        $payment = Payment::first();

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_id' => $payment->id,
            'postal_code' => '1000001',
            'address' => '東京都千代田区1-1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
