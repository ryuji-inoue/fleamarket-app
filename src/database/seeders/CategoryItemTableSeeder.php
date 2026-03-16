<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\Item;
use App\Models\Category;

class CategoryItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = Item::all();
        $categories = Category::pluck('id')->toArray();

        foreach ($items as $item) {

            $categoryId = $categories[array_rand($categories)];

            DB::table('item_category')->insert([
                'item_id' => $item->id,
                'category_id' => $categoryId,
            ]);
        }
    }
}
