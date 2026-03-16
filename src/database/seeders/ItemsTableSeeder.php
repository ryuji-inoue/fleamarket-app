<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Condition;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        $user = User::first();
        $conditions = Condition::pluck('id')->toArray();

        $items = [
            [
                'name' => '腕時計',
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'image_path' => 'items/Armani+Mens+Clock.jpg',
                'status' => 0,
            ],
            [
                'name' => 'HDD',
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'image_path' => 'items/HDD+Hard+Disk.jpg',
                'status' => 0,
            ],
            [
                'name' => '玉ねぎ3束',
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'image_path' => 'items/iLoveIMG+d.jpg',
                'status' => 0,
            ],
            [
                'name' => '革靴',
                'brand' => null,
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'image_path' => 'items/Leather+Shoes+Product+Photo.jpg',
                'status' => 0,
            ],
            [
                'name' => 'ノートPC',
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'image_path' => 'items/Living+Room+Laptop.jpg',
                'status' => 0,
            ],
            [
                'name' => 'マイク',
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'image_path' => 'items/Music+Mic+4632231.jpg',
                'status' => 1,
            ],
            [
                'name' => 'ショルダーバッグ',
                'brand' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'image_path' => 'items/Purse+fashion+pocket.jpg',
                'status' => 1,
            ],
            [
                'name' => 'タンブラー',
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'image_path' => 'items/Tumbler+souvenir.jpg',
                'status' => 1,
            ],
            [
                'name' => 'コーヒーミル',
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'image_path' => 'items/Waitress+with+Coffee+Grinder.jpg',
                'status' => 1,
            ],
            [
                'name' => 'メイクセット',
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'image_path' => 'items/iLoveIMG+d.jpg',
                'status' => 1,
            ],
        ];

        foreach ($items as $index => $item) {
            DB::table('items')->insert([
                'user_id' => $user->id,
                'name' => $item['name'],
                'brand' => $item['brand'],
                'description' => $item['description'],
                'price' => $item['price'],
                'image_path' => $item['image_path'],
                'condition_id' => $conditions[$index % count($conditions)],
                'status' => $item['status'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
