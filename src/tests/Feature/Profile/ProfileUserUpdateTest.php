<?php

namespace Tests\Feature\Profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileUserUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザー情報変更画面に初期値が正しく表示されることを確認
     */
    public function test_user_profile_edit_initial_values()
    {
        Storage::fake('public'); // プロフィール画像用フェイクストレージ

        // --- テストユーザー作成 ---
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => UploadedFile::fake()->image('profile.jpg')->hashName(),
            'postal_code' => '1000001',
            'address' => '東京都千代田区1-1',
            'building' => 'ビル101'
        ]);

        // --- ログイン ---
        $this->actingAs($user);

        // --- プロフィール編集ページにアクセス ---
        $response = $this->get("/mypage/edit");

        $response->assertStatus(200);

        // --- 初期値の確認 ---
        // 1. ユーザー名
        $response->assertSee('value="テストユーザー"', false);

        // 2. 郵便番号
        $response->assertSee('value="1000001"', false);

        // 3. 住所
        $response->assertSee('東京都千代田区1-1');

        // 4. 建物名
        $response->assertSee('value="ビル101"', false);

        // 5. プロフィール画像（imgタグで表示されているか）
        $response->assertSee($user->profile_image);
    }

    /**
     * 未ログインユーザーは編集ページにアクセスできない
     */
    public function test_guest_cannot_access_profile_edit()
    {
        $response = $this->get("/mypage/edit");

        $response->assertRedirect('/login');
    }
}