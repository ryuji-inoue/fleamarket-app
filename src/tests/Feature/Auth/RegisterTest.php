<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 名前が入力されていない場合、バリデーションメッセージが表示されることを確認
     */
    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        // セッションに 'name' エラーがあることを確認
        $response->assertSessionHasErrors('name');
    }

    /**
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示されることを確認
     */
    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * パスワードが入力されていない場合、バリデーションメッセージが表示されることを確認
     */
    public function test_password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => '',
            'password_confirmation' => ''
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * パスワードが7文字以下の場合、バリデーションメッセージが表示されることを確認
     */
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => '1234567',
            'password_confirmation' => '1234567'
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示されることを確認
     */
    public function test_password_confirmation_must_match()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'different123'
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移されることを確認
     * ※メール認証機能実装後は、メール認証画面にリダイレクトされることを確認
     */
    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        // データベースにユーザーが作成されていることを確認
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com'
        ]);

        // メール認証画面にリダイレクトされることを確認
        $response->assertRedirect('/email/verify');
    }
}