<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Payment;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_method_selection_reflected()
    {
        // 1. テスト用ユーザー作成
        $user = User::factory()->create([
            'postal_code' => '1000001',
            'address' => '東京都千代田区1-1',
            'building' => 'ビル101'
        ]);

        // 2. 出品者と商品作成
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 0
        ]);

        // 3. 支払い方法作成
        $payment1 = Payment::factory()->create(['name' => 'コンビニ払い']);
        $payment2 = Payment::factory()->create(['name' => 'カード払い']);

        // 4. ログイン
        $this->actingAs($user);

        // 5. 購入画面に payment_id を指定してアクセス
        $response = $this->get(route('purchase.create', ['item' => $item->id, 'payment_method' => $payment2->id]));

        // 6. 選択した支払い方法がビューに反映されていることを確認
        $response->assertSeeText('カード払い'); 

    }
}