<?php

namespace Tests\Feature\Purchase;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class StripePaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Stripe決済ページへリダイレクトできるかのテスト
     */
    public function test_user_can_access_stripe_checkout()
    {
        // テスト用ユーザー作成
        $user = User::factory()->create();

        // テスト用商品作成
        $item = Item::factory()->create([
            'price' => 1000
        ]);

        // Stripe Checkout Session をモック
        $mock = Mockery::mock('alias:Stripe\Checkout\Session');
        $mock->shouldReceive('create')->andReturn((object)[
            'url' => '/fake-stripe-url'
        ]);

        // ログイン状態でStripe決済ルートにPOST
        $response = $this->actingAs($user)
            ->post(route('purchase.stripe', ['item' => $item->id]));

        // 外部Stripeページにリダイレクトされることを確認
        $response->assertRedirect();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}