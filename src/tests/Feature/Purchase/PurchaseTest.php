<?php

namespace Tests\Feature\Purchase;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_process_and_sold_label()
    {
        // 1. テスト用データ作成
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 0, // 未購入
        ]);
        $payment = Payment::factory()->create();

        // 2. ユーザーにログイン
        $this->actingAs($user);

        // 3. 商品購入処理を実行（POST）
        $response = $this->post(route('purchase.store', $item->id), [
            'payment_id' => $payment->id,
            'postal_code' => '1000001',
            'address' => '東京都千代田区1-1',
        ]);

        // 4. 購入が完了してリダイレクトされること
        $response->assertRedirect();

        // 5. DBに購入データが作成されていること
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 6. 商品のステータスが「sold（1）」になっていること
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 1,
        ]);

        // 7. 商品一覧画面で「sold」が表示されること
        $response = $this->get(route('items.index'));
        $response->assertSee('sold');

        // 8. プロフィールの購入した商品一覧に表示されること
        $response = $this->get(route('mypage', ['page' => 'buy']));
        $response->assertSee($item->name);
    }
}