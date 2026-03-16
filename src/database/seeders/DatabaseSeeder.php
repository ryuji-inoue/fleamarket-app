<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        User::factory()->create([
        'id' => 1
        ]);

        $this->call([

            
            UsersTableSeeder::class,        //itemsのuser_id用
            CategoriesTableSeeder::class,   //item_categoryのcategory_id用
            ConditionsTableSeeder::class,   //itemsのcondition_id用
            
            ItemsTableSeeder::class,
            PaymentsTableSeeder::class,     //Purchasesのpayment_id用
            PurchasesTableSeeder::class,

            CategoryItemTableSeeder::class,
            FavoritesTableSeeder::class,
            CommentsTableSeeder::class,
        ]);
    }
}
