<?php

namespace Tests\Feature\ProfileProfile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * プロフィールページにユーザー情報と関連商品情報が正しく表示されることを確認
     */
    public function test_user_profile_information_display()
    {
        Storage::fake('public'); // 画像アップロード用フェイク

        // --- ユーザー作成 ---
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => UploadedFile::fake()->image('profile.jpg')->hashName()
        ]);

        $this->actingAs($user);

        // --- 出品商品作成 ---
        $item1 = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品1'
        ]);
        $item2 = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品2'
        ]);

        // --- 購入商品作成 ---
        $seller = User::factory()->create();
        $purchasedItem1 = Item::factory()->create(['user_id' => $seller->id]);
        $purchasedItem2 = Item::factory()->create(['user_id' => $seller->id]);

        // --- Payment を作成（外部キー制約対応） ---
        $payment1 = Payment::factory()->create();
        $payment2 = Payment::factory()->create();

        // --- 購入情報作成 ---
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem1->id,
            'payment_id' => $payment1->id,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区西新宿2-8-1',
            'building' => '新宿NSビル',
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem2->id,
            'payment_id' => $payment2->id,
            'postal_code' => '234-5678',
            'address' => '東京都渋谷区渋谷1-1-1',
            'building' => '渋谷ビル101号',
        ]);

        // --- 出品商品タブにアクセス ---
        $response = $this->get("/mypage?page=sell");
        $response->assertStatus(200);
        $response->assertSeeText('テストユーザー'); // ユーザー名
        $response->assertSee($user->profile_image); // プロフィール画像
        $response->assertSeeText('出品商品1');
        $response->assertSeeText('出品商品2');

        // --- 購入商品タブにアクセス ---
        $response = $this->get("/mypage?page=buy");
        $response->assertStatus(200);
        $response->assertSeeText($purchasedItem1->name);
        $response->assertSeeText($purchasedItem2->name);
    }

    /**
     * 未ログインユーザーはプロフィールページにアクセスできない
     */
    public function test_guest_cannot_access_profile()
    {
        $response = $this->get("/mypage");

        $response->assertRedirect('/login');
    }
}