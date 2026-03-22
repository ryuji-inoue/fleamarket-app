<?php

namespace Tests\Feature\Favorite;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいね機能の網羅テスト
     * ・いいねを登録することができる
     * ・いいね済みの状態をビューで確認できる
     * ・いいねを解除することができる
     */
    public function test_favorite_add_and_remove()
    {
        // --- ユーザー作成＆ログイン ---
        $user = User::factory()->create();
        $this->actingAs($user);

        // --- 商品作成 ---
        $item = Item::factory()->create();

        // --- 1. いいね追加 ---
        $response = $this->post("/favorite/{$item->id}");
        $response->assertStatus(200);
        $response->assertJson(['status' => 'ok']);

        // DBに登録されていることを確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        // いいね合計値が1になっていることを確認
        $item->refresh();
        $this->assertEquals(1, $item->favorites_count);

        // --- 2. ビューでいいね済み状態確認 ---
        $response = $this->get("/items/{$item->id}");

        // 画像で「いいね済み」を判定
        $response->assertSee('heart-on.png');

        // またはいいね数の <span> で確認
        $response->assertSee('<span>1</span>', false);

        // --- 3. いいね解除 ---
        // storeが追加/削除兼用なので再度POST
        $response = $this->post("/favorite/{$item->id}");
        $response->assertStatus(200);
        $response->assertJson(['status' => 'ok']);

        // DBから削除されていることを確認
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        // いいね合計値が0になっていることを確認
        $item->refresh();
        $this->assertEquals(0, $item->favorites_count);

        // ビュー上でも解除されていることを確認
        $response = $this->get("/items/{$item->id}");
        $response->assertSee('<span>0</span>', false);
    }
}