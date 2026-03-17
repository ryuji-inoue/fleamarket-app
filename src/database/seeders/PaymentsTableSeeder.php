<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment; 

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Payment::create(['name' => 'コンビニ払い']);
        Payment::create(['name' => 'カード払い']);
        Payment::create(['name' => '代金引換']);
    }
}
