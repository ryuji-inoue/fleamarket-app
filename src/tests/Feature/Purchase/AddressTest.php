<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Payment;

// Stripe モック用
use Mockery;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 配送先変更画面で登録した住所が商品購入画面に反映されていることを確認
     * テスト手順：
     * 1. ユーザーにログイン
     * 2. 送付先住所変更画面で住所を登録
     * 3. 商品購入画面を開き、セッションに登録されている住所が反映されていることを確認
     */
    public function test_updated_address_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);

        $this->actingAs($user);

        // 住所変更 POST
        $this->post("/purchase/address/{$item->id}", [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル'
        ]);

        // 商品購入画面でセッションに住所が反映されているか確認
        $this->assertEquals(
            '123-4567',
            session('purchase_address.postal_code')
        );

        $this->assertEquals(
            '東京都渋谷区',
            session('purchase_address.address')
        );

        $this->assertEquals(
            'テストビル',
            session('purchase_address.building')
        );
    }

    /**
     * 購入した商品に送付先住所が紐づいて登録されることを確認
     */
    public function test_address_is_saved_when_item_is_purchased()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);
        $payment = Payment::factory()->create(['id' => 1]);

        // Stripe Checkout Session をモック
        $mock = Mockery::mock('alias:Stripe\Checkout\Session');
        $mock->shouldReceive('create')->andReturn((object)[
            'url' => '/fake-stripe-url'
        ]);

        // session に住所をセットして購入処理
        $this->actingAs($user)
            ->withSession([
                'purchase_address' => [
                    'postal_code' => '123-4567',
                    'address' => '東京都渋谷区',
                    'building' => 'テストビル',
                ],
                'purchase_data' => [
                    'payment_id' => $payment->id,
                    'postal_code' => '123-4567',
                    'address' => '東京都渋谷区',
                    'building' => 'テストビル',
                ],
            ])
            // ここで直接 success メソッドにアクセス
            ->get("/purchase/success/{$item->id}");

        // purchasesテーブル確認：住所が正しく紐づいているか
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'user_id' => $user->id,
            'payment_id' => 1,
        ]);

        // 商品ステータス確認：購入済みに更新されているか
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 1
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }    
}