<?php

namespace Tests\Feature\Purchase;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;
use Mockery;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 購入処理と購入済みステータス確認
     * テスト手順：
     * 1. ユーザーと出品者作成
     * 2. 商品作成（未購入）
     * 3. Stripe モックして購入処理
     * 4. purchasesテーブルに購入情報が登録されるか
     * 5. 商品ステータスが購入済みになるか
     * 6. 商品一覧に「sold」表示
     * 7. マイページ購入一覧に商品名表示
     */
    public function test_purchase_process_and_sold_label()
    {
        // 1. テスト用データ作成
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 0, // 未購入
        ]);
        $payment = Payment::factory()->create();

        // 2. Stripe Checkout Session をモック
        $mock = Mockery::mock('alias:Stripe\Checkout\Session');
        $mock->shouldReceive('create')->andReturn((object)[
            'url' => '/fake-stripe-url'
        ]);

        // 3. ユーザーにログイン
        $this->actingAs($buyer);

        // 4. セッションに購入情報をセットして成功処理を呼ぶ
        $this->withSession([
            'purchase_data' => [
                'payment_id' => $payment->id,
                'postal_code' => '1000001',
                'address' => '東京都千代田区1-1',
                'building' => 'テストビル',
            ]
        ])->get(route('purchase.success', ['item' => $item->id]));

        // 5. DBに購入情報が登録されていること
        $this->assertDatabaseHas('purchases', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_id' => $payment->id,
            'postal_code' => '1000001',
            'address' => '東京都千代田区1-1',
            'building' => 'テストビル',
        ]);

        // 6. 商品ステータスが購入済み(1)になっていること
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 1,
        ]);

        // 7. 商品一覧画面で「sold」が表示されること
        $response = $this->get(route('items.index'));
        $response->assertSee('sold');

        // 8. マイページ購入一覧に商品名が表示されること
        $response = $this->get(route('mypage', ['page' => 'buy']));
        $response->assertSee($item->name);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}