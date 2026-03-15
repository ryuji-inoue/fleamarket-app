<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout()
    {
        // 1. ユーザー作成
        $user = User::factory()->create();

        // 2. ログイン状態にする
        $this->actingAs($user);

        // ログイン確認
        $this->assertAuthenticated();

        // 3. ログアウトボタン押下
        $response = $this->post('/logout');

        // 期待：ログアウトされている
        $this->assertGuest();

        // リダイレクト確認（任意）
        $response->assertRedirect('/');
    }
}