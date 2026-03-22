<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite; // いいねモデル

class ItemMyListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 初期データをシード
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * ログインユーザーのマイリストには、いいねした商品だけが表示されることを確認
     */
    public function test_only_favorited_items_are_displayed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // ユーザーがいいねした商品
        $favoritedItem = Item::factory()->create();
        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $favoritedItem->id
        ]);

        // ユーザーがいいねしていない商品
        $otherItem = Item::factory()->create();

        $response = $this->get('/mylist');

        // いいねした商品は表示される
        $response->assertSee($favoritedItem->name);

        // いいねしていない商品は表示されない
        $response->assertDontSee($otherItem->name);
    }

    /**
     * 購入済み商品には「Sold」ラベルが表示されることを確認
     */
    public function test_sold_label_is_displayed_for_purchased_items_in_mylists()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // 購入済み商品
        $soldItem = Item::factory()->create([
            'status' => 1
        ]);
        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id
        ]);

        $response = $this->get('/mylist');

        // 商品名と「Sold」ラベルが表示される
        $response->assertSee($soldItem->name);
        $response->assertSee('Sold');
    }

    /**
     * 未認証ユーザーの場合、マイリストは表示されないことを確認
     */
    public function test_mylists_are_not_displayed_for_guests()
    {
        $favoritedItem = Item::factory()->create();

        $response = $this->get('/mylist');

        // 何も表示されない
        $response->assertDontSee($favoritedItem->name);
    }
}