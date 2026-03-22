<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品出品画面で必要な情報が保存できることを確認
     */
    public function test_item_creation_saves_all_fields()
    {
        Storage::fake('public');

        // ユーザー作成 & ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // カテゴリ作成
        $category1 = Category::factory()->create(['name' => '家電']);
        $category2 = Category::factory()->create(['name' => 'スマホ']);

        // 条件作成
        $condition = Condition::factory()->create(['name' => '新品', 'sort' => 1]);

        // 出品商品データ
        $itemData = [
            'name' => 'iPhone 14',
            'brand' => 'Apple',
            'description' => '最新モデルです',
            'price' => 120000,
            'status' => 0, // コントローラーでは 0 固定
            'condition_id' => $condition->id,
            'categories' => [$category1->id, $category2->id],
            'image' => UploadedFile::fake()->image('item.jpg')
        ];

        // POST
        $response = $this->post(route('items.store'), $itemData);
        $response->assertRedirect('/'); // 商品一覧へリダイレクト

        // DB保存確認
        $this->assertDatabaseHas('items', [
            'name' => 'iPhone 14',
            'brand' => 'Apple',
            'description' => '最新モデルです',
            'price' => 120000,
            'status' => 0,
            'condition_id' => $condition->id,
            'user_id' => $user->id
        ]);

        // カテゴリ保存確認
        $item = Item::where('name', 'iPhone 14')->first();
        $categoryIds = $item->categories()->pluck('categories.id')->toArray();
        $this->assertEqualsCanonicalizing([$category1->id, $category2->id], $categoryIds);

        // 画像保存確認
        Storage::disk('public')->assertExists($item->image_path);
    }

    /**
     * 未ログインユーザーは出品画面にアクセスできない
     */
    public function test_guest_cannot_access_item_create()
    {
        $response = $this->get('/sell');
        $response->assertRedirect('/login');
    }
}