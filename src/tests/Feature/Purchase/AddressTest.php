<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Payment;

class AddressTest extends TestCase
{
    use RefreshDatabase;

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

        $this->assertEquals(
            '123-4567',
            session('purchase_address.postal_code')
        );
    }

    public function test_address_is_saved_when_item_is_purchased()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);
        $payment = Payment::factory()->create(['id' => 1]);

        // session に住所をセットして購入処理
        $this->actingAs($user)
             ->withSession([
                 'purchase_address' => [
                     'postal_code' => '123-4567',
                     'address' => '東京都渋谷区',
                     'building' => 'テストビル',
                 ]
             ])
             ->post("/purchase/{$item->id}", [
                 'payment_id' => $payment->id
             ]);

        // purchasesテーブル確認
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'user_id' => $user->id,
            'payment_id' => 1,
        ]);

        // 商品ステータス確認
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 1
        ]);
    }
}