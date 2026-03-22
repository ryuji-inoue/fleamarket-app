<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Favorite;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品名で部分一致検索ができることを確認
     */
    public function test_search_items_partial_match()
    {
        // 商品を作成
        $iphone = Item::factory()->create(['name' => 'iPhone 14']);
        $macbook = Item::factory()->create(['name' => 'Macbook Pro']);

        // 部分一致検索
        $response = $this->get('/?keyword=iPh');

        // iPhoneは表示される
        $response->assertSee('iPhone 14');

        // Macbookは表示されない
        $response->assertDontSee('Macbook Pro');
    }

    /**
     * 検索結果がマイリストでも保持されることを確認
     */
    public function test_search_keyword_is_retained_in_mylists()
    {
        // ユーザー作成＆ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // 商品作成
        $item = Item::factory()->create(['name' => 'iPhone']);

        // ユーザーが商品にいいね
        Favorite::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id
        ]);

        // ホームページで検索
        $response = $this->get('/mylist?keyword=iPhone');

        // マイリストページに遷移
        $response = $this->get('/mylist?keyword=iPhone');

        // 検索キーワードが保持され、対象商品が表示される
        $response->assertSee('iPhone');
    }

    /**
     * 検索キーワードに一致する商品が存在しない場合、結果が表示されないことを確認
     */
    public function test_search_no_match_shows_nothing()
    {
        Item::factory()->create(['name' => 'Macbook']);

        // マイリストページで検索（存在しない商品）
        $response = $this->get('/mylist?keyword=iPhone');

        // iPhoneがないので表示されない
        $response->assertDontSee('Macbook');
    }
}