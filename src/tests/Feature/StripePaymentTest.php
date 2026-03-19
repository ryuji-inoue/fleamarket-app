<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StripePaymentTest extends TestCase
{
    // テスト実行ごとにデータベースをリセットする
    use RefreshDatabase;

    /**
     * Stripe決済ページへリダイレクトできるかのテスト
     */
    public function test_user_can_access_stripe_checkout()
    {
        // テスト用ユーザーを作成
        $user = User::factory()->create();

        // テスト用の商品を作成
        $item = Item::factory()->create([
            'price' => 1000
        ]);

        // ログイン状態でStripe決済ルートにPOSTリクエスト
        $response = $this->actingAs($user)
            ->post("/purchase/{$item->id}/stripe");

        // Stripe Checkoutは外部ページにリダイレクトするため
        // HTTPステータス302（リダイレクト）が返ることを確認
        $response->assertStatus(302);
    }
}