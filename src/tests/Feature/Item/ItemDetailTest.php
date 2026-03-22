<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;
use App\Models\Condition; 
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品詳細の網羅テスト
     * ・商品詳細ページに必要な情報がすべて表示されること
     * ・複数選択のカテゴリが商品詳細ページに表示されること　を確認
     */
 public function test_item_detail_information_display()
    {
        Storage::fake('public');

        // --- ユーザー作成 ---
        $user = User::factory()->create();

        // --- カテゴリ作成 ---
        $category1 = Category::factory()->create(['name' => '家電']);
        $category2 = Category::factory()->create(['name' => 'スマホ']);

        // --- 条件作成 ---
        $condition = Condition::factory()->create(['name' => '新品', 'sort' => 1]);

        // --- 商品作成 ---
        $item = Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品説明',
            'price' => 10000,
            'condition_id' => $condition->id,
            'user_id' => $user->id,
            'image_path' => UploadedFile::fake()->image('item.jpg')->store('items', 'public'),
        ]);

        // --- カテゴリ紐付け ---
        $item->categories()->attach([$category1->id, $category2->id]);

        // --- コメント作成 ---
        $commentUser = User::factory()->create(['name' => 'コメントユーザー']);
        Comment::factory()->create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);

        // --- 商品詳細ページ取得 ---
        $response = $this->actingAs($user)->get(route('items.show', $item));

        $response->assertStatus(200);

        // --- 商品の基本情報確認 ---
        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee('¥' . number_format($item->price)); // 価格はビュー表示に合わせる
        $response->assertSee($item->description);

        // --- 商品カテゴリ確認 ---
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);

        // --- 商品の状態確認 ---
        $response->assertSee($condition->name);

        // --- コメント確認 ---
        $response->assertSee('コメントユーザー');
        $response->assertSee('テストコメント');

        // --- 画像確認 ---
        Storage::disk('public')->assertExists($item->image_path);
    }
}