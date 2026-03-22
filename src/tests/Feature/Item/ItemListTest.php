<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 初期データをシードする
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * 商品一覧ページに全ての商品が表示されることを確認
     */
    public function test_all_items_are_displayed()
    {
        // 商品を5件作成
        $items = Item::factory()->count(5)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        // 作成した商品名が全て表示されていることを確認
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    /**
     * 購入済み商品には「Sold」ラベルが表示されることを確認
     */
    public function test_sold_label_is_displayed_for_purchased_items()
    {
        // 購入済みの商品を作成 (status=1)
        $item = Item::factory()->create([
            'status' => 1
        ]);

        $response = $this->get('/');

        // 「Sold」ラベルが表示されることを確認
        $response->assertSee('Sold');

        // 商品名も表示されることを確認
        $response->assertSee($item->name);
    }

    /**
     * ログインユーザーが出品した商品は一覧に表示されないことを確認
     */
    public function test_items_from_logged_in_user_are_not_displayed()
    {
        $user = User::factory()->create();

        // ログインユーザーとして振る舞う
        $this->actingAs($user);

        // ログインユーザーが出品した商品
        $myItem = Item::factory()->create([
            'user_id' => $user->id
        ]);

        // 他のユーザーの商品
        $otherItem = Item::factory()->create();

        $response = $this->get('/');

        // 自分の商品は表示されない
        $response->assertDontSee($myItem->name);

        // 他ユーザーの商品は表示される
        $response->assertSee($otherItem->name);
    }
}